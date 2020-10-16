@extends('user.layout.auth')

@section('content')

<?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
<div class="full-page-bg" style="background-image: url({{$login_user}});">
<div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class="login-logo"><img src="{{ Setting::get('site_logo', asset('logo-black.png'))}}"></span>
                <h2>Create your account and get moving in minutes</h2>
                <p>Welcome to {{Setting::get('site_title','Tranxit')}}, the easiest way to get around at the tap of a button.</p>
            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                <div class="login-box row no-margin">
                    <div class="col-md-12">
                        <a class="log-blk-btn" href="{{url('login')}}">ALREADY HAVE AN ACCOUNT?</a>
                        <h3>Create a New Account</h3>
                    </div>
                    <form role="form" method="POST" action="{{ url('/register') }}">

                        <div id="first_step">
                            <div class="col-md-4">
                                <input value="+1" type="text" placeholder="+1" id="country_code" name="country_code" />
                            </div> 
                            
                            <div class="col-md-8">
                                <input type="text" autofocus id="phone_number" class="form-control" placeholder="Enter Phone Number" name="phone_number" value="{{ old('phone_number') }}" data-stripe="number" maxlength="10" onkeypress="return isNumberKey(event);" />
                            </div>

                            <div class="col-md-8">
                                @if ($errors->has('phone_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
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

                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" data-validation="alphanumeric" data-validation-allowing=" -" data-validation-error-msg="First Name can only contain alphanumeric characters and . - spaces">

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" data-validation="alphanumeric" data-validation-allowing=" -" data-validation-error-msg="Last Name can only contain alphanumeric characters and . - spaces">

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') }}" data-validation="email">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif                        
                            </div>

                            <div class="col-md-12">
                                <label class="checkbox-inline"><input type="checkbox" name="gender" value="MALE" data-validation="checkbox_group"  data-validation-qty="1" data-validation-error-msg="Please choose one gender">Male</label>
                                <label class="checkbox-inline"><input type="checkbox" name="gender"  value="FEMALE" data-validation="checkbox_group"  data-validation-qty="1" data-validation-error-msg="Please choose one gender">Female</label>
                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif                        
                            </div>

                            <div class="col-md-12">
                                <input type="password" class="form-control" name="password" placeholder="Password" data-validation="length" data-validation-length="min6" data-validation-error-msg="Password should not be less than 6 characters">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="password" placeholder="Re-type Password" class="form-control" name="password_confirmation" data-validation="confirmation" data-validation-confirm="password" data-validation-error-msg="Confirm Passsword is not matched">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="col-md-12">
                                <button class="log-teal-btn" type="submit">REGISTER</button>
                            </div>

                        </div>

                    </form>     

                    <div class="col-md-12">
                        <p class="helper">Or <a href="{{route('login')}}">Sign in</a> with your user account.</p>   
                    </div>

                </div>


                <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p></div>
                </div>
            </div>
        </div>
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
            $('#verificationCode').val('');
            $('#verification').modal('hide');
            $('#first_step').hide();
            $('#second_step').show();
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
</script>


@endsection
