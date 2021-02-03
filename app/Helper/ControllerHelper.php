<?php 

namespace App\Helpers;

use File;
use Setting;
use Illuminate\Support\Facades\Mail;
use App\WalletRequests;
use Auth;

class Helper
{

    public static function upload_picture($picture)
    {
        $file_name = time();
        $file_name .= rand();
        $file_name = sha1($file_name);
        if ($picture) {
            $ext = $picture->getClientOriginalExtension();
            $picture->move(public_path() . "/uploads", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            $s3_url = url('/').'/uploads/'.$local_url;
            
            return $s3_url;
        }
        return "";
    }


    public static function delete_picture($picture) {
        File::delete( public_path() . "/uploads/" . basename($picture));
        return true;
    }

    public static function generate_booking_id() {
        return Setting::get('booking_prefix').mt_rand(100000, 999999);
    }

    public static function subscription_sendmail($subscription,$subscriptionhistory,$shop){ 

        Mail::send('emails.subscription_invoice', ['subscription' => $subscription,'subscriptionhistory' => $subscriptionhistory,'shop' => $shop], function ($mail) use ($shop) {
           
            //$mail->to('tamilvanan@blockchainappfactory.com')->subject('Invoice');

            $mail->to($shop->email, $shop->name)->subject('Subscription Invoice');
        }); 
        return true;
    }
    public static function site_sendmail($user){

        $site_details=Setting::all();

        Mail::send('emails.invoice', ['Email' => $user], function ($mail) use ($user,$site_details) {
           
            //$mail->to('tamilvanan@blockchainappfactory.com')->subject('Invoice');

            $mail->to($user->user->email, $user->user->first_name.' '.$user->user->last_name)->subject('Invoice');
        });

        /*if( count(Mail::failures()) > 0 ) {

           echo "There was one or more failures. They were: <br />";

           foreach(Mail::failures() as $email_address) {
               echo " - $email_address <br />";
            }

        } else {
            echo "No errors, all sent successfully!";
        }*/

        return true;
    }

    public static function site_registermail($user){

        $site_details=Setting::all();
        
        Mail::send('emails.welcome', ['user' => $user], function ($mail) use ($user) {
           // $mail->from('harapriya@appoets.com', 'Your Application');

            //$mail->to('tamilvanan@blockchainappfactory.com')->subject('Invoice');

            $mail->to($user->email, $user->first_name.' '.$user->last_name)->subject('Welcome');
        });
        if( count(Mail::failures()) > 0 ) {
            $settings['key']="spam entry check";
            $settings['value'] = Request::fullUrl();
            Settings::create($settings);
        }else{
            return true;    
        }
        
    }

    public function formatPagination($pageobj){

        $results = new \stdClass();

        $results->links=$pageobj->links();
        $results->count=$pageobj->count();
        $results->currentPage=$pageobj->currentPage();
        $results->firstItem=$pageobj->firstItem();
        $results->hasMorePages=$pageobj->hasMorePages();
        $results->lastItem=$pageobj->lastItem();
        $results->lastPage=$pageobj->lastPage();
        $results->nextPageUrl=$pageobj->nextPageUrl();
        $results->perPage=$pageobj->perPage();
        $results->previousPageUrl=$pageobj->previousPageUrl();
        $results->total=$pageobj->total();
        //$results->url=$pageobj->url();  

        return $results;
    }

    public static function generate_request_id($type) {

        if($type=='provider'){
            $tr_str='PSET';
        }
        else{
            $tr_str='FSET';
        }

        $typecount=WalletRequests::where('request_from',$type)->count();

        if(!empty($typecount))
            $next_id=$typecount+1;
        else
            $next_id=1;

        $alias_id=$tr_str.str_pad($next_id, 6, 0, STR_PAD_LEFT); 
            
        return $alias_id;

    }
    public static function site_cashpickup_mail($user,$amount){

        $name=Auth::guard('provider')->user()->first_name.' '.Auth::guard('provider')->user()->last_name;
        $address="-";
        $country = "-";
        $city = "-";
        $postal_code = "-";
        if(Auth::guard('provider')->user()->profile){
            $address =Auth::guard('provider')->user()->profile->address.' '.Auth::guard('provider')->user()->profile->address_secondary;  
            $country =  Auth::guard('provider')->user()->profile->country;
            $city =  Auth::guard('provider')->user()->profile->city;
            $postal_code =  Auth::guard('provider')->user()->profile->postal_code;
        }
        $mobile =Auth::guard('provider')->user()->mobile; 
        $email = Auth::guard('provider')->user()->email; 
        

        Mail::send('emails.cashpickup', ['name' => $name,'address' => $address,'country' => $country,'mobile' => $mobile,'city' =>$city,'postal_code'=>$postal_code,'email' => $email,'amount'=>$amount], function ($mail) use ($name,$email) {
           
            //$mail->to('tamilvanan@blockchainappfactory.com')->subject('Invoice');

            $mail->to($email, $name)->subject('Remitly Cash Pickup');
        });

        if( count(Mail::failures()) > 0 ) {

             //echo "There was one or more failures. They were: <br />";

           foreach(Mail::failures() as $email_address) {
               //echo " - $email_address <br />";
            }

        } else {
            //echo "No errors, all sent successfully!";
        }
        //exit();
        return true;
    }


}
