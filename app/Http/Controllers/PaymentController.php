<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SendPushNotification;

use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeInvalidRequestError;

use Auth;
use Setting;
use Exception;

use App\Card;
use App\User;
use App\WalletPassbook;
use App\UserRequests;
use App\UserRequestPayment;
use App\WalletRequests;
use App\Provider;
use App\Fleet;

use App\Http\Controllers\ProviderResources\TripController;

use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use Square\Exceptions\ApiException;
use Square\SquareClient;

class PaymentController extends Controller
{
       /**
     * payment for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {

        $this->validate($request, [
                'request_id' => 'required|exists:user_request_payments,request_id|exists:user_requests,id,paid,0,user_id,'.Auth::user()->id
            ]);


        $UserRequest = UserRequests::find($request->request_id);
        
        $tip_amount=0;

        if($UserRequest->payment_mode == 'SQUARE'){

            $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first(); 
            
            if(isset($request->tips) && !empty($request->tips)){
                $tip_amount=round($request->tips,2);
            }
            
              $amount = $RequestPayment->payable+$tip_amount;


              $access_token = 'EAAAEAvSoq6fwjHL5evOYZENgCXgPinc-PIBFmO3DgqeH59dhp7WF7fDVVCIbIAz';


              # setup authorization
              \SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);
              # create an instance of the Transaction API class
              $transactions_api = new \SquareConnect\Api\TransactionsApi();
              $location_id = 'PFH972RPJ6WJQ';
              $nonce = $request->nonce;
              $amount =(int)$amount;
             // dd($amount);
              $request_body = array (
                  "card_nonce" => $nonce,
                  # Monetary amounts are specified in the smallest unit of the applicable currency.
                  # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
                  "amount_money" => array (
                      "amount" => $amount,
                      "currency" => "USD"
                  ),
                  # Every payment you process with the SDK must have a unique idempotency key.
                  # If you're unsure whether a particular payment succeeded, you can reattempt
                  # it with the same idempotency key without worrying about double charging
                  # the buyer.
                  "idempotency_key" => uniqid()
              );

              try {
                  $result = $transactions_api->charge($location_id,  $request_body);
                  if($result['errors']!=null){

                     if($request->ajax()) {
                          return response()->json(['error' => "Payment Failed"], 422);
                      } else {
                          return redirect('dashboard')->with('flash_error', "Payment Failed");
                      }

                  }
                  $transaction = $result->getTransaction();
                  $transactionID = $transaction["tenders"][0]["transaction_id"];
                  $transaction = $transaction["tenders"][0]["card_details"];
                  
                  if($transaction['status']=='CAPTURED'){

                        $RequestPayment->payment_id = $transactionID;
                        $RequestPayment->payment_mode = 'SQUARE';
                        $RequestPayment->card = $RequestPayment->payable;
                        $RequestPayment->payable = 0;
                        $RequestPayment->tips = $tip_amount;
                        $RequestPayment->provider_pay = $RequestPayment->provider_pay+$tip_amount;
                        $RequestPayment->save();

                        $UserRequest->paid = 1;
                        $UserRequest->status = 'COMPLETED';
                        $UserRequest->save();

                        //for create the transaction
                        (new TripController)->callTransaction($request->request_id);

                        if($request->ajax()) {
                           return response()->json(['message' => trans('api.paid')]); 
                        } else {
                            return redirect('dashboard')->with('flash_success', trans('api.paid'));
                        }

                      

                  }

              } catch (\SquareConnect\ApiException $e) {
                 $error = $e->getResponseBody();
                //  dd($error->errors[0]->detail);
                  if($request->ajax()) {
                        return response()->json(['error' => $error->errors[0]->detail], 422);
                    } else {
                        return redirect('dashboard')->with('flash_error', $error->errors[0]->detail);
                    }
              }





          }

        if($UserRequest->payment_mode == 'PAYPAL'){

            $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first(); 
            
            if(isset($request->tips) && !empty($request->tips)){
                $tip_amount=round($request->tips,2);
            }
            
            $amount = $RequestPayment->payable+$tip_amount;


            $random = mt_rand(1111,9999);


            $this->set_Braintree();
            $result = \Braintree_Transaction::sale([
              'amount' =>$amount,
              'paymentMethodNonce' => $request->braintree_nonce,
              'orderId' => $random,                          
            ]); 
           
            if($result->success == true) {


                $RequestPayment->payment_id = $random;
                $RequestPayment->payment_mode = 'PAYPAL';
                $RequestPayment->card = $RequestPayment->payable;
                $RequestPayment->payable = 0;
                $RequestPayment->tips = $tip_amount;
                $RequestPayment->provider_pay = $RequestPayment->provider_pay+$tip_amount;
                $RequestPayment->save();

                $UserRequest->paid = 1;
                $UserRequest->status = 'COMPLETED';
                $UserRequest->save();

                //for create the transaction
                (new TripController)->callTransaction($request->request_id);

                if($request->ajax()) {
                   return response()->json(['message' => trans('api.paid')]); 
                } else {
                    return redirect('dashboard')->with('flash_success', trans('api.paid'));
                }



            }else{

               if($request->ajax()){
                    return response()->json(['error' => 'Payment Failed'], 422);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Payment Failed');
                }

            }



        }

        if($UserRequest->payment_mode == 'CARD') {

            $RequestPayment = UserRequestPayment::where('request_id',$request->request_id)->first(); 
            
            if(isset($request->tips) && !empty($request->tips)){
                $tip_amount=round($request->tips,2);
            }
            
            $StripeCharge = ($RequestPayment->payable+$tip_amount) * 100;
            
           
            try {

                $Card = Card::where('user_id',Auth::user()->id)->where('is_default',1)->first();
                $stripe_secret = Setting::get('stripe_secret_key');

                Stripe::setApiKey(Setting::get('stripe_secret_key'));
                
                if($StripeCharge  == 0){

                $RequestPayment->payment_mode = 'CARD';
                $RequestPayment->card = $RequestPayment->payable;
                $RequestPayment->payable = 0;
                $RequestPayment->tips = $tip_amount;                
                $RequestPayment->provider_pay = $RequestPayment->provider_pay+$tip_amount;
                $RequestPayment->save();

                $UserRequest->paid = 1;
                $UserRequest->status = 'COMPLETED';
                $UserRequest->save();

                //for create the transaction
                (new TripController)->callTransaction($request->request_id);

                if($request->ajax()) {
                   return response()->json(['message' => trans('api.paid')]); 
                } else {
                    return redirect('dashboard')->with('flash_success', trans('api.paid'));
                }
               }else{
                
                $Charge = Charge::create(array(
                      "amount" => $StripeCharge,
                      "currency" => "usd",
                      "customer" => Auth::user()->stripe_cust_id,
                      "card" => $Card->card_id,
                      "description" => "Payment Charge for ".Auth::user()->email,
                      "receipt_email" => Auth::user()->email
                    ));

                /*$ProviderCharge = (($RequestPayment->total+$RequestPayment->tips - $RequestPayment->tax) - $RequestPayment->commision) * 100;

                $transfer = Transfer::create(array(
                    "amount" => $ProviderCharge,
                    "currency" => "usd",
                    "destination" => $Provider->stripe_acc_id,
                    "transfer_group" => "Request_".$UserRequest->id,
                  )); */    
                 
                $RequestPayment->payment_id = $Charge["id"];
                $RequestPayment->payment_mode = 'CARD';
                $RequestPayment->card = $RequestPayment->payable;
                $RequestPayment->payable = 0;
                $RequestPayment->tips = $tip_amount;
                $RequestPayment->provider_pay = $RequestPayment->provider_pay+$tip_amount;
                $RequestPayment->save();

                $UserRequest->paid = 1;
                $UserRequest->status = 'COMPLETED';
                $UserRequest->save();

                //for create the transaction
                (new TripController)->callTransaction($request->request_id);

                if($request->ajax()) {
                   return response()->json(['message' => trans('api.paid')]); 
                } else {
                    return redirect('dashboard')->with('flash_success', trans('api.paid'));
                }
              }

            } catch(StripeInvalidRequestError $e){
              
                if($request->ajax()){
                    return response()->json(['error' => $e->getMessage()], 500);
                } else {
                    return back()->with('flash_error', $e->getMessage());
                }
            } catch(Exception $e) {
                if($request->ajax()){
                    return response()->json(['error' => $e->getMessage()], 500);
                } else {
                    return back()->with('flash_error', $e->getMessage());
                }
            }
        }
    }


    /**
     * add wallet money for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_money(Request $request){
//dd($request);
        if($request->card_id=='SQUARE')
        {

            // $access_token = Setting::get('square_access_token');
            //$access_token = 'EAAAEAvSoq6fwjHL5evOYZENgCXgPinc-PIBFmO3DgqeH59dhp7WF7fDVVCIbIAz';
            $access_token =  env('access_token');

             // Initialize the Square client.
            $client = new SquareClient([
              'accessToken' => $access_token,  
              'environment' =>  env('SQUARE_env')
            ]);
            // Fail if the card form didn't send a value for `nonce` to the server
            //$nonce = $request->nonce;

            $payments_api = $client->getPaymentsApi();


              # setup authorization
              //\SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);
              # create an instance of the Transaction API class
              //$transactions_api = new \SquareConnect\Api\TransactionsApi();
            $location_id = env('SQUARE_UP_locationId');
            $nonce = $request->nonce;
            $amount =(int)($request->amount*100);
            
            $money = new Money();
            // Monetary amounts are specified in the smallest unit of the applicable currency.
            // This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
            $money->setAmount($amount);
            $money->setCurrency('USD');

            $create_payment_request = new CreatePaymentRequest($nonce, uniqid(), $money);
            // dd($amount);
          // $request_body = array (
          //     "card_nonce" => $nonce,
          //     # Monetary amounts are specified in the smallest unit of the applicable currency.
          //     # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
          //     "amount_money" => array (
          //         "amount" => $amount,
          //         "currency" => "USD"
          //     ),
          //     # Every payment you process with the SDK must have a unique idempotency key.
          //     # If you're unsure whether a particular payment succeeded, you can reattempt
          //     # it with the same idempotency key without worrying about double charging
          //     # the buyer.
          //     "idempotency_key" => uniqid()
          // );

          try {
              
                //$result = $transactions_api->charge($location_id,  $request_body);
                $result = $payments_api->createPayment($create_payment_request);
                $response_subscription=json_decode($result->getBody(),true);

                if($result->isError()){

                    if($request->ajax()) {
                        return response()->json(['error' => "Payment Failed"], 422);
                    } else {
                        return back()->with('flash_error', "Payment Failed");
                    }
                }
               // $transaction = $result->getTransaction();
                //$transaction = $transaction["tenders"][0]["card_details"];
                $transaction = $response_subscription['payment']['card_details'];
                if($transaction['status']=='CAPTURED'){

                  (new SendPushNotification)->WalletMoney(Auth::user()->id,currency($request->amount));

                  //for create the user wallet transaction
                  (new TripController)->userCreditDebit($request->amount,Auth::user()->id,1);

                  $wallet_balance=Auth::user()->wallet_balance+$request->amount;

                  if($request->ajax()){
                      return response()->json(['success' => currency($request->amount)." ".trans('api.added_to_your_wallet'), 'message' => currency($request->amount)." ".trans('api.added_to_your_wallet'), 'balance' => $wallet_balance]); 
                  } else {
                      return redirect('wallet')->with('flash_success',currency($request->amount).trans('admin.payment_msgs.amount_added'));
                  }

              }

          } catch (\SquareConnect\ApiException $e) {
             $error = $e->getResponseBody();
            //  dd($error->errors[0]->detail);
              if($request->ajax()) {
                    return response()->json(['error' => $error->errors[0]->detail], 422);
                } else {
                    return back()->with('flash_error', $error->errors[0]->detail);
                }
          }
      }


       if($request->card_id=='PAYPAL')
        {

            $this->validate($request, [

            'braintree_nonce'=>'required',                
            ]);


            $request_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
            //$user_type = $request->user_type;

            $random = mt_rand(1111,9999);


                    $this->set_Braintree();
                $result = \Braintree_Transaction::sale([
                  'amount' =>$request->amount,
                  'paymentMethodNonce' => $request->braintree_nonce,
                  'orderId' => $random,                          
                ]); 
                $UserID = Auth::user()->id;

                if($result->success == true) {

                     (new SendPushNotification)->WalletMoney(Auth::user()->id,currency($request->amount));

                      //for create the user wallet transaction
                      (new TripController)->userCreditDebit($request->amount,Auth::user()->id,1);

                      $wallet_balance=Auth::user()->wallet_balance+$request->amount;

                      if($request->ajax()){
                          return response()->json(['success' => currency($request->amount)." ".trans('api.added_to_your_wallet'), 'message' => currency($request->amount)." ".trans('api.added_to_your_wallet'), 'balance' => $wallet_balance]); 
                      } else {
                          return redirect('wallet')->with('flash_success',currency($request->amount).trans('admin.payment_msgs.amount_added'));
                      }


                }else{

                     if($request->ajax()){

                         return response()->json(['error' => 'Payment Failed'], 422);

                     }else{
                        return redirect('wallet')->with('flash_error','Payment Failed');
                     }

                     


                }

         



        }

        $this->validate($request, [
                'amount' => 'required|integer',
                'card_id' => 'required|exists:cards,card_id,user_id,'.Auth::user()->id
            ]);

        try{
            
            $StripeWalletCharge = $request->amount * 100;

            Stripe::setApiKey(Setting::get('stripe_secret_key'));

            $Charge = Charge::create(array(
                  "amount" => $StripeWalletCharge,
                  "currency" => "usd",
                  "customer" => Auth::user()->stripe_cust_id,
                  "card" => $request->card_id,
                  "description" => "Adding Money for ".Auth::user()->email,
                  "receipt_email" => Auth::user()->email
                ));            

            Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
            Card::where('card_id',$request->card_id)->update(['is_default' => 1]);

            //sending push on adding wallet money
            (new SendPushNotification)->WalletMoney(Auth::user()->id,currency($request->amount));

            //for create the user wallet transaction
            (new TripController)->userCreditDebit($request->amount,Auth::user()->id,1);

            $wallet_balance=Auth::user()->wallet_balance+$request->amount;

            if($request->ajax()){
                return response()->json(['success' => currency($request->amount)." ".trans('api.added_to_your_wallet'), 'message' => currency($request->amount)." ".trans('api.added_to_your_wallet'), 'balance' => $wallet_balance]); 
            } else {
                return redirect('wallet')->with('flash_success',currency($request->amount).trans('admin.payment_msgs.amount_added'));
            }

        } catch(StripeInvalidRequestError $e) {
            if($request->ajax()){
                 return response()->json(['error' => $e->getMessage()], 500);
            }else{
                return back()->with('flash_error',$e->getMessage());
            }
        } catch(Exception $e) {
          //dd($e);
            if($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', $e->getMessage());
            }
        }
    }


    /**
     * send money to provider or fleet.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_money(Request $request, $id){
            
        try{

            $Requests = WalletRequests::where('id',$id)->first();

            if($Requests->request_from=='provider'){
              $provider = Provider::find($Requests->from_id);
              $stripe_cust_id=$provider->stripe_cust_id;
              $email=$provider->email;
            }
            else{
              $fleet = Fleet::find($Requests->from_id);
              $stripe_cust_id=$fleet->stripe_cust_id;
              $email=$fleet->email;
            }

            if(empty($stripe_cust_id)){              
              throw new Exception(trans('admin.payment_msgs.account_not_found'));              
            }

            $StripeCharge = $Requests->amount * 100;

            Stripe::setApiKey(Setting::get('stripe_secret_key'));

            $tranfer = \Stripe\Transfer::create(array(
                     "amount" => $StripeCharge,
                     "currency" => "usd",
                     "destination" => $stripe_cust_id,
                     "description" => "Payment Settlement for ".$email                     
                 ));           

            //create the settlement transactions
            (new TripController)->settlements($id);

             $response=array();
            $response['success']=trans('admin.payment_msgs.amount_send');
           
        } catch(Exception $e) {
            $response['error']=$e->getMessage();           
        }

        return $response;
    }

        public function set_Braintree(){

        \Braintree_Configuration::environment(getenv('braintree_environment'));
        \Braintree_Configuration::merchantId(getenv('braintree_merchant_id'));
        \Braintree_Configuration::publicKey(getenv('braintree_public_key'));
        \Braintree_Configuration::privateKey(getenv('braintree_private_key'));
    }

    public function client_token()
    {
        $this->set_Braintree();
        $clientToken = \Braintree_ClientToken::generate();
        return response()->json(['token' => $clientToken]);
    }
}


