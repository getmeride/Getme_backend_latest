<?php

namespace App\Http\Controllers\ProviderAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

use Setting;
use Validator;

use App\Helpers\Helper;
use App\Provider;
use App\ProviderService;
use App\ProviderBillingCashout;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/provider/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('provider.guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'mobile' => 'required|numeric|unique:providers',
            'country_code' => 'required',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'service_type' => 'required',
            'service_number' => 'required',
            'service_model' => 'required',
            'year' => 'required',
            'car_make' => 'required',
            'color' => 'required',
            'cashout_type' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Provider
     */
    protected function create(array $data)
    {   
        if(!empty($data['gender']))
            $gender=$data['gender'];
        else
            $gender='MALE';

        $Provider = Provider::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'gender' => $gender,
            'mobile' => $data['mobile'],
            'password' => bcrypt($data['password'])            
        ]);

        $provider_service = ProviderService::create([
            'provider_id' => $Provider->id,
            'service_type_id' => $data['service_type'],
            'service_number' => $data['service_number'],
            'service_model' => $data['service_model'],
            'year' => $data['year'],
            'car_make' => $data['car_make'],
            'color' => $data['color'],

        ]);

        if($data['cashout_type'] == "bank_deposit"){
            $provider_billing_cashout = ProviderBillingCashout::create([
                'provider_id' => $Provider->id,
                'cashout_type' => $data['cashout_type'],
                'bank_deposit_full_name' => $data['bank_deposit_full_name'],
                'bank_deposit_routing_number' => $data['bank_deposit_routing_number'],
                'bank_deposit_account_number' => $data['bank_deposit_account_number'],
                'bank_deposit_account_type' => $data['bank_deposit_account_type'],
                'bank_deposit_swift_code' => $data['bank_deposit_swift_code'],
                'bank_deposit_iban_number' => $data['bank_deposit_iban_number'],
            ]);
        }elseif($data['cashout_type'] == "pay_by_zelle"){
            $provider_billing_cashout = ProviderBillingCashout::create([
                'provider_id' => $Provider->id,
                'cashout_type' => $data['cashout_type'],
                'pay_by_zelle_full_name' => $data['pay_by_zelle_full_name'],
                'pay_by_zelle_mobile_number' => $data['pay_by_zelle_mobile_number'],
                'pay_by_zelle_email' => $data['pay_by_zelle_email']
            ]);
        }elseif($data['cashout_type'] == "cash_pickup"){
            $provider_billing_cashout = ProviderBillingCashout::create([
                'provider_id' => $Provider->id,
                'cashout_type' => $data['cashout_type'],
                'cashpickup_full_name' => $data['cashpickup_full_name'],
                'cashpickup_address' => $data['cashpickup_address'],
                'cashpickup_city_state' => $data['cashpickup_city_state'],
                'cashpickup_country' => $data['cashpickup_country'],
                'cashpickup_mobile_number' => $data['cashpickup_mobile_number']
            ]);
        }
        

        if(Setting::get('demo_mode', 0) == 1) {
            //$Provider->update(['status' => 'approved']);
            $provider_service->update([
                'status' => 'active',
            ]);
        }

        if(Setting::get('send_email', 0) == 1) {
            // send welcome email here
            Helper::site_registermail($Provider);
        }    
        
        return $Provider;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('provider.auth.register');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('provider');
    }
}
