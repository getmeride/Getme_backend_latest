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
            <input type="text" required id="mobile" class="form-control" placeholder="Enter Phone Number" name="mobile" value="{{ old('mobile') }}" data-stripe="number"  />
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

        <br>
        <div class="col-md-12">
            <button type="submit" class="log-teal-btn">
                @lang('provider.signup.login')
            </button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/js/intlTelInput.min.js"></script>
<script type="text/javascript">
    $("#country_code").intlTelInput();
    $("#country_code").intlTelInput("setNumber", "+1");
</script>
@endsection
