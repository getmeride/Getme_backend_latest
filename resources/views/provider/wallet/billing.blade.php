@extends('provider.layout.app')

@section('content')
@include('provider.provider_header')
<style type="text/css">
    .margin_bottom_15{
        margin-bottom: 15px;
    }
</style>
<div class="pro-dashboard-content gray-bg">
    <div class="container">
        <div class="manage-docs pad30">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ Session::get('success') }}
                </div>
            @elseif(Session::has('danger'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ Session::get('danger') }}
                </div>
            @endif
            <div class="manage-doc-content">
                @include('common.notify')
                <div class="row no-margin">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <form class="profile-form" action="{{route('provider.billing.store')}}" method="POST"  role="form" id="requestform">
                        {{ csrf_field() }}
                            <h3><center>Bank Deposit</center></h3>
                            <input type="hidden" name="cashout_type" value="bank_deposit">
                            <div class=" bank_deposit common_select_dropdown" id="bank_deposit" >
                                <div class="col-md-12 col-sm-12">
                                    <input id="bank_deposit_full_name" type="text" class="form-control margin_bottom_15" name="bank_deposit_full_name" value="{{ old('bank_deposit_full_name',($bank_deposit) ? $bank_deposit->bank_deposit_full_name : '') }}" placeholder="Full Name of the  account holder" data-validation="required" >
                                    @if ($errors->has('bank_deposit_full_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_deposit_full_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="bank_deposit_routing_number" type="text" class="form-control margin_bottom_15" name="bank_deposit_routing_number" value="{{ old('bank_deposit_routing_number',($bank_deposit) ? $bank_deposit->bank_deposit_routing_number : '') }}" placeholder="ACH routing number"  data-validation="required">
                                    
                                    @if ($errors->has('bank_deposit_routing_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_deposit_routing_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="bank_deposit_account_number" type="text" class="form-control margin_bottom_15" name="bank_deposit_account_number" value="{{ old('bank_deposit_account_number',($bank_deposit) ? $bank_deposit->bank_deposit_account_number : '') }}" placeholder="Account Number" data-validation="required">
                                    
                                    @if ($errors->has('bank_deposit_account_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_deposit_account_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12 margin_bottom_15">
                                    <label class="checkbox-inline"><input type="checkbox" name="bank_deposit_account_type" value="checking" data-validation="checkbox_group" data-validation-qty="1" data-validation-error-msg="Please choose one account type" @if($bank_deposit && $bank_deposit->bank_deposit_account_type=="checking") checked="" @endif>Checking</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="bank_deposit_account_type" value="savings" data-validation="checkbox_group" data-validation-qty="1" data-validation-error-msg="Please choose one account type" @if($bank_deposit && $bank_deposit->bank_deposit_account_type=="savings") checked="" @endif>Savings</label>
                                    @if ($errors->has('bank_deposit_account_type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_deposit_account_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="bank_deposit_swift_code" type="text" class="form-control margin_bottom_15" name="bank_deposit_swift_code" value="{{ old('bank_deposit_swift_code',($bank_deposit) ? $bank_deposit->bank_deposit_swift_code : '') }}" placeholder="SWIFT/ BIC code" data-validation="required">
                                    
                                    @if ($errors->has('bank_deposit_swift_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_deposit_swift_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="bank_deposit_iban_number" type="text" class="form-control margin_bottom_15" name="bank_deposit_iban_number" value="{{ old('bank_deposit_iban_number',($bank_deposit) ? $bank_deposit->bank_deposit_iban_number : '') }}" placeholder="IBAN / Account Number" data-validation="required">
                                    
                                    @if ($errors->has('bank_deposit_iban_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bank_deposit_iban_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary log-teal-btn">
                                    Submit
                                    </button>
                                </div>
                            </div>     
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                       <form class="profile-form" action="{{route('provider.billing.store')}}" method="POST"  role="form" id="requestform1">
                        {{ csrf_field() }}
                             <input type="hidden" name="cashout_type" value="pay_by_zelle">
                            <h3><center>Pay By Zelle</center></h3>
                            <div class="row pay_by_zelle common_select_dropdown" id="pay_by_zelle" >
                                <div class="col-md-12 col-sm-12">
                                    <input id="pay_by_zelle_full_name" type="text" class="form-control margin_bottom_15" name="pay_by_zelle_full_name" value="{{ old('pay_by_zelle_full_name',($pay_by_zelle) ? $pay_by_zelle->pay_by_zelle_full_name : '') }}" placeholder="Full Name of the  account holder" data-validation="required">
                                    @if ($errors->has('pay_by_zelle_full_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pay_by_zelle_full_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="pay_by_zelle_mobile_number" type="phone" class="form-control margin_bottom_15" name="pay_by_zelle_mobile_number" value="{{ old('pay_by_zelle_mobile_number',($pay_by_zelle) ? $pay_by_zelle->pay_by_zelle_mobile_number : '') }}" data-stripe="number" maxlength="10" placeholder="Enter Your Mobile number assigned to your bank"  data-validation="required" onkeypress="return isNumberPayZelllMobileKey(event);">
                                    
                                    @if ($errors->has('pay_by_zelle_mobile_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pay_by_zelle_mobile_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="pay_by_zelle_email" type="email" class="form-control margin_bottom_15" name="pay_by_zelle_email" value="{{ old('pay_by_zelle_email',($pay_by_zelle) ? $pay_by_zelle->pay_by_zelle_email : '') }}" placeholder="Enter your email that associated with your bank" data-validation="email">
                                    
                                    @if ($errors->has('pay_by_zelle_email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pay_by_zelle_email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary log-teal-btn">
                                    Submit
                                    </button>
                                </div>
                            </div>
                        </form> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <form class="profile-form" action="{{route('provider.billing.store')}}" method="POST"  role="form" id="requestform2">
                        {{ csrf_field() }}
                            <h3><center>Cash Pickup</center></h3>
                             <input type="hidden" name="cashout_type" value="cash_pickup">
                            <div class="row cash_pickup common_select_dropdown" id="cash_pickup" >
                                <div class="col-md-12 col-sm-12">
                                    <input id="cashpickup_full_name" type="text" class="form-control margin_bottom_15" name="cashpickup_full_name" value="{{ old('cashpickup_full_name',($cash_pickup) ? $cash_pickup->cashpickup_full_name : '') }}" placeholder="Full Name" data-validation="alphanumeric" data-validation-allowing=" -" data-validation-error-msg="@lang('provider.profile.car_model') can only contain alphanumeric characters and - spaces">
                                    
                                    @if ($errors->has('cashpickup_full_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cashpickup_full_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="cashpickup_address" type="text" class="form-control margin_bottom_15" name="cashpickup_address" value="{{ old('cashpickup_address',($cash_pickup) ? $cash_pickup->cashpickup_address : '') }}" placeholder="Address"  data-validation="required">
                                    
                                    @if ($errors->has('cashpickup_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cashpickup_address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="cashpickup_city_state" type="text" class="form-control margin_bottom_15" name="cashpickup_city_state" value="{{ old('cashpickup_city_state',($cash_pickup) ? $cash_pickup->cashpickup_city_state : '') }}" placeholder="City and State" data-validation="required">
                                    
                                    @if ($errors->has('cashpickup_city_state'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cashpickup_city_state') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="cashpickup_country" type="text" class="form-control margin_bottom_15" name="cashpickup_country" value="{{ old('cashpickup_country',($cash_pickup) ? $cash_pickup->cashpickup_country : '') }}" placeholder="country" data-validation="required">
                                    
                                    @if ($errors->has('cashpickup_country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cashpickup_country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="cashpickup_mobile_number" type="phone"  class="form-control margin_bottom_15" name="cashpickup_mobile_number" value="{{ old('cashpickup_mobile_number',($cash_pickup) ? $cash_pickup->cashpickup_mobile_number : '') }}" data-stripe="number" maxlength="10" placeholder="Billing Mobile Number" data-validation="required" onkeypress="return isNumberCashPickupMobileKey(event);">
                                    
                                    @if ($errors->has('cashpickup_mobile_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cashpickup_mobile_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary log-teal-btn">
                                    Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script type="text/javascript">
    $.validate({
        modules : 'security',
    });
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
@endsection

