@extends('provider.layout.auth')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css">
<div class="col-md-12">
    <a  class="log-blk-btn" href="{{ url('/provider/register') }}">@lang('provider.signup.create_new_acc')</a>
    <h3 style="margin-left: 15px;">@lang('provider.signup.sign_in')</h3>
</div>

<div class="col-md-12">
    <form role="form" method="POST" action="{{ url('/provider/login') }}">
        {{ csrf_field() }}

        {{-- <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="@lang('user.profile.email')" autofocus>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif --}}
        <div class="col-md-4 ">
            <input type="tel" name="country_code" id="country_code" value="{{ old('country_code') }}" placeholder="+1">
        </div>
         <div class="col-md-8 "> 
            <input type="phone" required id="phone_number" class="form-control" placeholder="Enter Phone Number" name="mobile" value="{{ old('mobile') }}" data-stripe="number"  maxlength="10" onkeypress="return isNumberKey(event);" />
            @if ($errors->has('mobile'))
                <span class="help-block">
                    <strong>{{ $errors->first('mobile') }}</strong>
                </span>
            @endif
        </div>
                         
        <div class="col-md-12">
            <input id="password" type="password" class="form-control" name="password" placeholder="@lang('provider.signup.password')">

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember">@lang('provider.signup.remember_me')
                </label>
            </div>
        </div>
        <div class="col-md-12" style="padding-bottom: 10px;">
            <div id="recaptcha-container"></div>
        </div>
        <br>
        <div class="col-md-12">
            <!-- <button type="submit" class="log-teal-btn">
                @lang('provider.signup.login')
            </button> -->
            <button type="submit" class="log-teal-btn submitBtn" style="display: none">LOGIN</button>
            <input type="button" class="log-teal-btn login_button"  value="@lang('provider.signup.login')" required=""/>
        </div>    

        <p class="helper"><a style="margin-left: 15px;" href="{{ url('/provider/password/reset') }}"> @lang('provider.signup.forgot_password')</a></p>   
    </form>
    @if(Setting::get('social_login', 0) == 1)
    <div class="col-md-12">
        <a href="{{ url('provider/auth/facebook') }}"><button type="submit" class="log-teal-btn fb"><i class="fa fa-facebook"></i>@lang('provider.signup.login_facebook')</button></a>
    </div>  
    <div class="col-md-12">
        <a href="{{ url('provider/auth/google') }}"><button type="submit" class="log-teal-btn gp"><i class="fa fa-google-plus"></i>@lang('provider.signup.login_google')</button></a>
    </div>
    @endif
</div>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
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
        var password=document.getElementById('password').value;
        if(country_code == ""){
            alert("please enter country code");
            return false;
        }else if(phone_number == ""){
            alert("please enter phone number");
            return false;
        }
        else if(password == ""){
            alert("please enter password");
            return false;
        }
        var number=country_code+''+phone_number;
        firebase.auth().signInWithPhoneNumber(number,window.recaptchaVerifier).then(function (confirmationResult) {
            window.confirmationResult=confirmationResult;
            coderesult=confirmationResult;
            //console.log(coderesult);
            if(coderesult.verificationId){
                //$('#verification').modal('show');
                 $('#phone_number').val(number);
                $('.submitBtn').click();
            }
        }).catch(function (error) {
           //swal("Error",error.message,"error");
            alert(error.message);
        });
    }
</script>    
<script type="text/javascript">
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
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/js/intlTelInput.min.js"></script>
<script type="text/javascript">
    $("#country_code").intlTelInput();
    $("#country_code").intlTelInput("setNumber", "+1");
</script>
@endsection
