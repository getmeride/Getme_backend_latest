@extends('provider.layout.auth')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css">
<div class="col-md-12">
    <a class="log-blk-btn" href="{{ url('/provider/login') }}">@lang('provider.signup.already_register')</a>
    <h3>@lang('provider.signup.sign_up')</h3>
</div>

<div class="col-md-12">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/register') }}" enctype="multipart/form-data">

        <div id="first_step" style="display: block;">
           <div class="col-md-4">
               <input type="tel" name="country_code" id="country_code" placeholder="e.g. +1 702 123 4567">
            </div>  
            
            <div class="col-md-8">
                <input type="phone" autofocus id="phone_number" class="form-control" placeholder="@lang('provider.signup.enter_phone')" name="mobile" value="{{ old('mobile') }}" data-stripe="number" maxlength="10" onkeypress="return isNumberKey(event);"/>
            </div>

            <div class="col-md-8">
                @if ($errors->has('mobile'))
                    <span class="help-block">
                        <strong>{{ $errors->first('mobile') }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-md-12" style="padding-bottom: 10px;">
                <div id="recaptcha-container"></div>
            </div>

            <div class="col-md-12" style="padding-bottom: 10px;" id="mobile_verfication">
                <input type="button" class="log-teal-btn small login_button"  value="Verify Phone Number"/>
            </div>
        </div>

        {{ csrf_field() }}
        <!-- Modal -->
        <div class="modal fade" id="verification">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header w-100 floatleft">
                  <h4 class="modal-title">Verification Code</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body w-100 floatleft">
                    <div class="col-md-12 mt-3">
                        <input type="text" class="form-control" id="verificationCode" placeholder="Enter verification code">
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-primary verifybtn" onclick="codeverify();">Verify code</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>
        <div id="second_step" style="display: none;">
            <div>
                <input id="fname" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="@lang('provider.profile.first_name')" autofocus data-validation="alphanumeric" data-validation-allowing=" -" data-validation-error-msg="@lang('provider.profile.first_name') can only contain alphanumeric characters and . - spaces">
                @if ($errors->has('first_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                @endif
            </div>
            <div>
                <input id="lname" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="@lang('provider.profile.last_name')" data-validation="alphanumeric" data-validation-allowing=" -" data-validation-error-msg="@lang('provider.profile.last_name') can only contain alphanumeric characters and . - spaces">            
                @if ($errors->has('last_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                @endif
            </div>
            <div>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="@lang('provider.signup.email_address')" data-validation="email">            
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div>
                <label>Profile Picture</label>
                <input id="avatar" type="file" class="form-control" name="avatar"  data-validation="required" accept="image/x-png,image/gif,image/jpeg" >            
            </div>
            <div>
                <label class="checkbox-inline"><input type="checkbox" name="gender" value="MALE" data-validation="checkbox_group" data-validation-qty="1" data-validation-error-msg="Please choose one gender">@lang('provider.signup.male')</label>
                <label class="checkbox-inline"><input type="checkbox" name="gender" value="FEMALE" data-validation="checkbox_group" data-validation-qty="1" data-validation-error-msg="Please choose one gender">@lang('provider.signup.female')</label>
                @if ($errors->has('gender'))
                    <span class="help-block">
                        <strong>{{ $errors->first('gender') }}</strong>
                    </span>
                @endif
            </div>                        
            <div>
                <input id="password" type="password" class="form-control" name="password" placeholder="@lang('provider.signup.password')" data-validation="length" data-validation-length="min6" data-validation-error-msg="Password should not be less than 6 characters">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>    
            <div>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="@lang('provider.signup.confirm_password')" data-validation="confirmation" data-validation-confirm="password" data-validation-error-msg="Confirm Passsword is not matched">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>    
            <div>
                <select class="form-control" name="service_type" id="service_type" data-validation="required">
                    <option value="">Select Service</option>
                    @foreach(get_all_service_types() as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('service_type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('service_type') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <input id="service-number" type="text" class="form-control" name="service_number" value="{{ old('service_number') }}" placeholder="@lang('provider.profile.car_number')"  data-validation="required">
                    
                    @if ($errors->has('service_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('service_number') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-6 col-sm-12">
                    <select class="form-control" name="year" data-validation="required">
                        <option value="">Select Year</option>    
                        <?php for($year=2009;$year<=2033;$year++){?>
                            <option value="<?php echo $year;?>"><?php echo $year;?></option>
                        <?php }?>    
                    </select>
                    @if ($errors->has('year'))
                        <span class="help-block">
                            <strong>{{ $errors->first('year') }}</strong>
                        </span>
                     @endif
                </div>
            </div>  
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <input id="service-model" type="text" class="form-control" name="service_model" value="{{ old('service_model') }}" placeholder="@lang('provider.profile.car_model')"  data-validation="required">
                    
                    @if ($errors->has('service_model'))
                        <span class="help-block">
                            <strong>{{ $errors->first('service_model') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-4 col-sm-12">
                    <input id="car-make" type="text" class="form-control" name="car_make" value="{{ old('car_make') }}" placeholder="@lang('provider.profile.car_make')"  data-validation="required">
                    
                    @if ($errors->has('car_make'))
                        <span class="help-block">
                            <strong>{{ $errors->first('car_make') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-4 col-sm-12">
                    <input id="color" type="text" class="form-control" name="color" value="{{ old('color') }}" placeholder="@lang('provider.profile.color')"  data-validation="required">
                    
                    @if ($errors->has('color'))
                        <span class="help-block">
                            <strong>{{ $errors->first('color') }}</strong>
                        </span>
                    @endif
                </div>
            </div>  
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <select class="form-control billing_information" name="cashout_type" data-validation="required">
                        <option value="">Select Payout Option</option>    
                        <option value="bank_deposit">Bank Deposit</option>
                        <option value="pay_by_zelle">Pay By Zelle</option>
                        <option value="cash_pickup">Cash Pickup</option>
                    </select>
                    @if ($errors->has('cashout_type'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cashout_type') }}</strong>
                        </span>
                     @endif
                </div>
            </div>
            <div class="row bank_deposit common_select_dropdown" id="bank_deposit" style="display: none;">
                <div class="col-md-12 col-sm-12">
                    <input id="bank_deposit_full_name" type="text" class="form-control" name="bank_deposit_full_name" value="{{ old('bank_deposit_full_name') }}" placeholder="Full Name of the  account holder" data-validation="required">
                    @if ($errors->has('bank_deposit_full_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank_deposit_full_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="bank_deposit_routing_number" type="text" class="form-control" name="bank_deposit_routing_number" value="{{ old('bank_deposit_routing_number') }}" placeholder="ACH routing number"  data-validation="required">
                    
                    @if ($errors->has('bank_deposit_routing_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank_deposit_routing_number') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="bank_deposit_account_number" type="text" class="form-control" name="bank_deposit_account_number" value="{{ old('bank_deposit_account_number') }}" placeholder="Account Number" data-validation="required">
                    
                    @if ($errors->has('bank_deposit_account_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank_deposit_account_number') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <label class="checkbox-inline"><input type="checkbox" name="bank_deposit_account_type" value="checking" data-validation="checkbox_group" data-validation-qty="1" data-validation-error-msg="Please choose one account type">Checking</label>
                    <label class="checkbox-inline"><input type="checkbox" name="bank_deposit_account_type" value="savings" data-validation="checkbox_group" data-validation-qty="1" data-validation-error-msg="Please choose one account type">Savings</label>
                    @if ($errors->has('bank_deposit_account_type'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank_deposit_account_type') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="bank_deposit_swift_code" type="text" class="form-control" name="bank_deposit_swift_code" value="{{ old('bank_deposit_swift_code') }}" placeholder="SWIFT/ BIC code" data-validation="required">
                    
                    @if ($errors->has('bank_deposit_swift_code'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank_deposit_swift_code') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="bank_deposit_iban_number" type="text" class="form-control" name="bank_deposit_iban_number" value="{{ old('bank_deposit_iban_number') }}" placeholder="IBAN / Account Number" data-validation="required">
                    
                    @if ($errors->has('bank_deposit_iban_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank_deposit_iban_number') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row pay_by_zelle common_select_dropdown" id="pay_by_zelle" style="display: none;">
                <div class="col-md-12 col-sm-12">
                    <input id="pay_by_zelle_full_name" type="text" class="form-control" name="pay_by_zelle_full_name" value="{{ old('pay_by_zelle_full_name') }}" placeholder="Full Name of the  account holder" data-validation="required">
                    @if ($errors->has('pay_by_zelle_full_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('pay_by_zelle_full_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="pay_by_zelle_mobile_number" type="phone" class="form-control" name="pay_by_zelle_mobile_number" value="{{ old('pay_by_zelle_mobile_number') }}" data-stripe="number" maxlength="10" placeholder="Enter Your Mobile number assigned to your bank"  data-validation="required" onkeypress="return isNumberPayZelllMobileKey(event);">
                    
                    @if ($errors->has('pay_by_zelle_mobile_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('pay_by_zelle_mobile_number') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="pay_by_zelle_email" type="email" class="form-control" name="pay_by_zelle_email" value="{{ old('pay_by_zelle_email') }}" placeholder="Enter your email that associated with your bank" data-validation="email">
                    
                    @if ($errors->has('pay_by_zelle_email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('pay_by_zelle_email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row cash_pickup common_select_dropdown" id="cash_pickup" style="display: none;">
                <div class="col-md-12 col-sm-12">
                    <input id="cashpickup_full_name" type="text" class="form-control" name="cashpickup_full_name" value="{{ old('cashpickup_full_name') }}" placeholder="Full Name" data-validation="alphanumeric" data-validation-allowing=" -" data-validation-error-msg="@lang('provider.profile.car_model') can only contain alphanumeric characters and - spaces">
                    
                    @if ($errors->has('cashpickup_full_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cashpickup_full_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="cashpickup_address" type="text" class="form-control" name="cashpickup_address" value="{{ old('cashpickup_address') }}" placeholder="Address"  data-validation="required">
                    
                    @if ($errors->has('cashpickup_address'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cashpickup_address') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="cashpickup_city_state" type="text" class="form-control" name="cashpickup_city_state" value="{{ old('cashpickup_city_state') }}" placeholder="City and State" data-validation="required">
                    
                    @if ($errors->has('cashpickup_city_state'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cashpickup_city_state') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="cashpickup_country" type="text" class="form-control" name="cashpickup_country" value="{{ old('cashpickup_country') }}" placeholder="country" data-validation="required">
                    
                    @if ($errors->has('cashpickup_country'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cashpickup_country') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12">
                    <input id="cashpickup_mobile_number" type="phone"  class="form-control" name="cashpickup_mobile_number" value="{{ old('cashpickup_mobile_number') }}" data-stripe="number" maxlength="10" placeholder="Billing Mobile Number" data-validation="required" onkeypress="return isNumberCashPickupMobileKey(event);">
                    
                    @if ($errors->has('cashpickup_mobile_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cashpickup_mobile_number') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <button type="submit" class="log-teal-btn">
                @lang('provider.signup.register')
            </button>

        </div>
    </form>
</div>
@endsection


@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

<!-- The core Firebase JS SDK is always required and must be listed first -->


<script type="text/javascript">
    window.onload=function () {
        render();
    };
    function render() {
        window.recaptchaVerifier=new firebase.auth.RecaptchaVerifier('recaptcha-container');
        recaptchaVerifier.render();
    }
</script>
<script type="text/javascript">
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "{{env('apiKey')}}",
        authDomain: "{{env('authDomain')}}",
        databaseURL: "{{env('databaseURL')}}",
        projectId: "{{env('projectId')}}",
        storageBucket: "{{env('storageBucket')}}",
        messagingSenderId: "{{env('messagingSenderId')}}",
        appId: "{{env('appId')}}"
      };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    $('.login_button').click(function(){
        phoneAuth();
    });
    function phoneAuth() {
        var country_code = document.getElementById('country_code').value;
        var phone_number=document.getElementById('phone_number').value;
        if(country_code == ""){
            alert("please enter country code");
            return false;
        }else if(phone_number == ""){
            alert("please enter phone number");
            return false;
        }
        var number=country_code+''+phone_number;
        firebase.auth().signInWithPhoneNumber(number,window.recaptchaVerifier).then(function (confirmationResult) {
            window.confirmationResult=confirmationResult;
            coderesult=confirmationResult;
            console.log(coderesult);
            if(coderesult.verificationId){
                $('#verification').modal('show');
            }
        }).catch(function (error) {
           //swal("Error",error.message,"error");
            alert(error.message);
        });
    }
    function submitPhoneNumberAuthCode() {
        console.log("submitPhoneNumberAuthCode");
        var code = document.getElementById("code").value;
        confirmationResult
          .confirm(code)
          .then(function(result) {
            var user = result.user;
        })
        .catch(function(error) {
           alert(error.message);
        });
    }
    function codeverify() {
        console.log("codeverify");
        var code=document.getElementById('verificationCode').value;
        coderesult.confirm(code).then(function (result) {
            var mobile_no=$('#mobile_no').val();
            var country_code = document.getElementById('country_code').value;
            var phone_number=document.getElementById('phone_number').value;
            var number=country_code+''+phone_number;
            $('#verificationCode').val('');
            $('#verification').modal('hide');
            $('#first_step').hide();
            $('#second_step').show();
            $('#phone_number').val(number);
            // $.ajax({
            //     type:"post",
            //     url:"loginajax.php",
            //     data:{mobile_no:mobile_no},
            //     success:function(){
            //         location.reload();
            //     }
            // })

        }).catch(function (error) {
           alert(error.message);
        });
    }
    function submitPhoneNumberAuth() {
        var phoneNumber = document.getElementById("phoneNumber").value;
        var appVerifier = window.recaptchaVerifier;
        firebase
          .auth()
          .signInWithPhoneNumber(phoneNumber, appVerifier)
          .then(function(confirmationResult) {
            window.confirmationResult = confirmationResult;
          })
          .catch(function(error) {
            swal("Error",error,'error');
           
          });
    }
</script>



<script type="text/javascript">
    $.validate({
        modules : 'security',
    });
    $('.checkbox-inline').on('change', function() {
        $('.checkbox-inline').not(this).prop('checked', false);  
    });
    function isNumberKey(evt)
    {   
        var edValue = document.getElementById("phone_number");
        var s = edValue.value;
        if (event.keyCode == 13) {
            event.preventDefault();
            if(s.length>=10){
                smsLogin();
            }
        }
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function isNumberPayZelllMobileKey(evt)
    {   
        var edValue = document.getElementById("pay_by_zelle_mobile_number");
        var s = edValue.value;
        if (event.keyCode == 13) {
            event.preventDefault();
            if(s.length>=10){
                //smsLogin();
            }
        }
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function isNumberCashPickupMobileKey(evt)
    {   
        var edValue = document.getElementById("cashpickup_mobile_number");
        var s = edValue.value;
        if (event.keyCode == 13) {
            event.preventDefault();
            if(s.length>=10){
                //smsLogin();
            }
        }
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/js/intlTelInput.min.js"></script>
<script type="text/javascript">
    $("#country_code").intlTelInput();
    $("#country_code").intlTelInput("setNumber", "+1");
    $(document).ready(function(){
        $("select.billing_information").change(function(){
            var billing_information = $(this).children("option:selected").val();
            if(billing_information == "bank_deposit"){
                $('.common_select_dropdown').hide();
                $('#bank_deposit').show();
            }else if(billing_information == "pay_by_zelle"){
                $('.common_select_dropdown').hide();
                $('#pay_by_zelle').show();
            }else if(billing_information == "cash_pickup"){
                $('.common_select_dropdown').hide();
                $('#cash_pickup').show();
            }else{
                $('.common_select_dropdown').hide();   
            }
        });
    });
</script>
@endsection

