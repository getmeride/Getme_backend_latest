<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function refundMoneyToUserStripe(Request $request){
        try{
           //dd($request->payment_id);
            $payment_id = $request->payment_id;
            $client_credentials = $request->client_credentials;
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.stripe.com/v1/refunds',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'charge=' . $payment_id,
                CURLOPT_SSL_VERIFYHOST =>false,
                CURLOPT_SSL_VERIFYPEER =>false,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $client_credentials,
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return true;
        }catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }
    public function refundMoneyToUserSquareup(Request $request){
        try{

            $amount =  $request->amount;
            $myUUID =  $request->myUUID;
            $payment_id =  $request->payment_id;
            $client_credentials =  $request->client_credentials;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://connect.squareupsandbox.com/v2/refunds');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n    \"amount_money\": {\n      \"currency\": \"USD\",\n      \"amount\": " . (int)$amount . "\n    },\n    \"idempotency_key\": \"$myUUID.\",\n    \"payment_id\": \"$payment_id\",\n    \"reason\": \"Cancel order\"\n  }");

            $headers = array();
            $headers[] = 'Square-Version: 2022-03-16';
            $headers[] = 'Authorization: Bearer ' . $client_credentials;
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            //dd($result);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return true;


        }catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }
}
