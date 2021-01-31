<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRequests;
use App\UserRequestPayment;
use App\RequestFilter;
use App\ProviderWallet;
use App\Provider;
use App\WalletRequests;
use App\ProviderSubscription;
use Carbon\Carbon;
use Auth;
use Setting;
use App\Helpers\Helper;
use App\Http\Controllers\ProviderResources\TripController;

use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use Square\Exceptions\ApiException;
use Square\SquareClient;

class ProviderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('provider');
        $this->middleware('demo', ['only' => [
                'update_password',
            ]]);
    }
    public function transfercheck()
    {
        dd("here");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('provider.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function incoming(Request $request)
    {
        return (new TripController())->index($request);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function accept(Request $request, $id)
    {
        return (new TripController())->accept($request, $id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function reject($id)
    {
        return (new TripController())->destroy($id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        return (new TripController())->update($request, $id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function rating(Request $request, $id)
    {
        return (new TripController())->rate($request, $id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function earnings()
    {
        $provider = Provider::where('id',\Auth::guard('provider')->user()->id)
                    ->with('service','accepted','cancelled')
                    ->get();

        $weekly = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment')
                    ->where('created_at', '>=', Carbon::now()->subWeekdays(7))
                    ->get();

        $weekly_sum = UserRequestPayment::with(['request' => function($query) {
                        $query->where('provider_id',\Auth::guard('provider')->user()->id);
                        $query->where('created_at', '>=', Carbon::now()->subWeekdays(7));
                    }])->sum('provider_pay');


        $today = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->where('created_at', '>=', Carbon::today())
                    ->count();

        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')->orderBy('id','desc')
                    ->get();

        $fully_sum = UserRequestPayment::with(['request'=> function($query) {
                        $query->where('provider_id', \Auth::guard('provider')->user()->id);
                        }])->sum('provider_pay');
        
        return view('provider.payment.earnings',compact('provider','weekly','fully','today','weekly_sum','fully_sum'));
    }

    /**
     * available.
     *
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        (new ProviderResources\ProfileController)->available($request);
        return back();
    }

    /**
     * Show the application change password.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_password()
    {
        return view('provider.profile.change_password');
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $this->validate($request, [
                'password' => 'required|confirmed',
                'old_password' => 'required',
            ]);

        $Provider = \Auth::user();

        if(password_verify($request->old_password, $Provider->password))
        {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return back()->with('flash_success', trans('admin.password_update'));
        } else {
            return back()->with('flash_error', trans('admin.password_error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function location_edit()
    {
        return view('provider.location.index');
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location_update(Request $request)
    {
        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

        if($Provider = \Auth::user()){

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return back()->with(['flash_success' => trans('api.provider.location_updated')]);

        } else {
            return back()->with(['flash_error' => trans('admin.provider_msgs.provider_not_found')]);
        }
    }

    /**
     * upcoming history.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trips()
    {
        $fully = (new ProviderResources\TripController)->upcoming_trips();
        return view('provider.payment.upcoming',compact('fully'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function cancel(Request $request) {
        try{

            (new TripController)->cancel($request);
            return back()->with(['flash_success' => trans('admin.provider_msgs.trip_cancelled')]);
        } catch (ModelNotFoundException $e) {
            return back()->with(['flash_error' => trans('admin.something_wrong')]);
        }
    }

    public function wallet_transation(Request $request){

        try{
            $wallet_transation = ProviderWallet::where('provider_id',Auth::user()->id)
                                ->orderBy('id','desc')
                                ->paginate(Setting::get('per_page', '10'));
            
            $pagination=(new Helper)->formatPagination($wallet_transation);   
            
            $wallet_balance=Auth::user()->wallet_balance;

            return view('provider.wallet.wallet_transation',compact('wallet_transation','pagination','wallet_balance'));
          
        }catch(Exception $e){
            return back()->with(['flash_error' => trans('admin.something_wrong')]);
        }
        
    }

    public function transfer(Request $request){

        $pendinglist = WalletRequests::where('from_id',Auth::user()->id)->where('request_from','provider')->where('status',0)->get();
        $wallet_balance=Auth::user()->wallet_balance;
        return view('provider.wallet.transfer',compact('pendinglist','wallet_balance'));
    }

    public function requestamount(Request $request)
    {
        
        
        $send=(new TripController())->requestamount($request);
        $response=json_decode($send->getContent(),true);
        
        if(!empty($response['error']))
            $result['flash_error']=$response['error'];
        if(!empty($response['success']))
            $result['flash_success']=$response['success'];

        return redirect()->back()->with($result);
    }

    public function requestcancel(Request $request)
    {
              
        $cancel=(new TripController())->requestcancel($request);
        $response=json_decode($cancel->getContent(),true);
        
        if(!empty($response['error']))
            $result['flash_error']=$response['error'];
        if(!empty($response['success']))
            $result['flash_success']=$response['success'];

        return redirect()->back()->with($result);
    }


    public function stripe(Request $request)
    {
        return (new ProviderResources\ProfileController)->stripe($request);
    }

    public function cards()
    {
        $cards = (new Resource\ProviderCardResource)->index();
       

        $provider_subscription = ProviderSubscription::where('provider_id',\Auth::user()->id)->orderBy('id','desc')->get();
        return view('provider.wallet.card',compact('cards','provider_subscription'));
    }
     public function processCard(Request $request)
    {
        
        
        // Pulled from the .env file and upper cased e.g. SANDBOX, PRODUCTION.
        //$upper_case_environment = strtoupper(getenv('ENVIRONMENT'));

        // The access token to use in all Connect API requests.
        // Set your environment as *sandbox* if you're just testing things out.
        $access_token =  'EAAAEBILJ3YXrpwl9wnMYrmqEAeaB1DW2T8BIYTyUBMyPPJOiWqnxfQS2vIR88DZ';

        // Initialize the Square client.
        $client = new SquareClient([
          'accessToken' => $access_token,  
          'environment' => 'sandbox'
        ]);

        

        // Fail if the card form didn't send a value for `nonce` to the server
        $nonce = $_POST['nonce'];
        if (is_null($nonce)) {
          return redirect()->back()->with(['danger'=>'Invalid card data']);
        }

        $payments_api = $client->getPaymentsApi();

        // To learn more about splitting payments with additional recipients,
        // see the Payments API documentation on our [developer site]
        // (https://developer.squareup.com/docs/payments-api/overview).
        $provider_monthly_charger = Setting::get('provider_monthly_charger') * 100;

        $money = new Money();
          // Monetary amounts are specified in the smallest unit of the applicable currency.
          // This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
        $money->setAmount($provider_monthly_charger);
        $money->setCurrency('USD');

          // Every payment you process with the SDK must have a unique idempotency key.
          // If you're unsure whether a particular payment succeeded, you can reattempt
          // it with the same idempotency key without worrying about double charging
          // the buyer.
        $create_payment_request = new CreatePaymentRequest($nonce, uniqid(), $money);

        // The SDK throws an exception if a Connect endpoint responds with anything besides
        // a 200-level HTTP code. This block catches any exceptions that occur from the request.
        try {
            
            $response = $payments_api->createPayment($create_payment_request);
            $response_subscription=json_decode($response->getBody(),true);
            //dd($response_subscription['payment']['id']);
            // $json_result = json_decode(stripslashes($response), true);
            // $json=str_replace("\\",'', $result);

             //echo "<pre>"; print($response);exit();
              // If there was an error with the request we will
              // print them to the browser screen here
            if ($response->isError()) {
                return redirect()->back()->with(['danger'=>'Api response has Errors']);
            }

            $provider_subscription_first = ProviderSubscription::where('provider_id',\Auth::user()->id)->orderBy('id','desc')->first();
            if($provider_subscription_first){
                $start_date = date('Y-m-d', strtotime('+1 day',strtotime($provider_subscription_first->end_date)));
                $end_date = date('Y-m-d', strtotime('+1 month',strtotime($provider_subscription_first->end_date)));
            }else{
                $start_date = date("Y-m-d");
                $end_date = date('Y-m-d', strtotime('+1 month'));
                $end_date = date('Y-m-d', strtotime('-1 day',strtotime($end_date)));
            }
            $provider_subscription = new ProviderSubscription();
            $provider_subscription->provider_id =\Auth::user()->id;
            $provider_subscription->transaction_id =$response_subscription['payment']['id'];
            $provider_subscription->description ="Subscription Payment";
            $provider_subscription->amount =Setting::get('provider_monthly_charger');
            $provider_subscription->start_date =$start_date;
            $provider_subscription->end_date =$end_date;
            $provider_subscription->status = "Pending";
            $provider_subscription->save();

            return redirect()->back()->with(['success'=>'Successfully payment']);
        } catch (ApiException $e) {
             return redirect()->back()->with(['danger'=>'Api response has Errors']);
        }

        return redirect()->back();
    }
}