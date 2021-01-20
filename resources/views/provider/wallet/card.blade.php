<?php



if(Setting::get('CARD', 0) == 0){
    header('Location:/provider/profile');
    exit;
}   
?>
@extends('provider.layout.app')

@section('content')


@include('provider.provider_header')
<script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>
{{-- <script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script> --}}
    
  <script type="text/javascript">
    window.applicationId ='sandbox-sq0idb-EM5SwDGqHZkblJHg0osXJg';
    window.locationId ='EAAAEBILJ3YXrpwl9wnMYrmqEAeaB1DW2T8BIYTyUBMyPPJOiWqnxfQS2vIR88DZ';
  </script>


  <!-- link to the custom styles for SqPaymentForm -->
<link rel="stylesheet" type="text/css" href="{{ asset('asset/css/sq-payment-form.css') }}">

<div class="pro-dashboard-content gray-bg">
    <div class="container">
        <!-- Begin Payment Form -->
  <div class="sq-payment-form">
    <!--
      Square's JS will automatically hide these buttons if they are unsupported
      by the current device.
    -->
    <div id="sq-walletbox">
     {{--  <button id="sq-google-pay" class="button-google-pay"></button>
      <button id="sq-apple-pay" class="sq-apple-pay"></button>
      <button id="sq-masterpass" class="sq-masterpass"></button> --}}
      {{-- <div class="sq-wallet-divider">
        <span class="sq-wallet-divider__text">Or</span>
      </div> --}}
    </div>
    <div id="sq-ccbox">
      <!--
        You should replace the action attribute of the form with the path of
        the URL you want to POST the nonce to (for example, "/process-card").

        You need to then make a "Charge" request to Square's Payments API with
        this nonce to securely charge the customer.

        Learn more about how to setup the server component of the payment form here:
        https://developer.squareup.com/docs/payments-api/overview
      -->
      <form id="nonce-form" novalidate action="{{route('provider.process.card')}}" method="post">
        {{csrf_field()}}
        <div class="sq-field">
          <label class="sq-label">Card Number</label>
          <div id="sq-card-number"></div>
        </div>
        <div class="sq-field-wrapper">
          <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">CVV</label>
            <div id="sq-cvv"></div>
          </div>
          <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">Expiration</label>
            <div id="sq-expiration-date"></div>
          </div>
          <div class="sq-field sq-field--in-wrapper">
            <label class="sq-label">Postal</label>
            <div id="sq-postal-code"></div>
          </div>
        </div>
        <div class="sq-field">
          <button id="sq-creditcard" class="sq-button" onclick="onGetCardNonce(event)">
            Pay $10.00 Now
          </button>
        </div>
        <!--
          After a nonce is generated it will be assigned to this hidden input field.
        -->
        <div id="error"></div>
        <input type="hidden" id="card-nonce" name="nonce">
      </form>
    </div>
  </div>
  <!-- End Payment Form -->
        <div class="manage-docs pad30">
            <div class="manage-doc-content">
                <div class="manage-doc-section pad50">
                    <div class="manage-doc-section-head row no-margin">
                        <h3 class="manage-doc-tit">
                           
                        </h3>
                    </div>
                    @include('common.notify')                         
                   
                     <div class="manage-doc-section-content border-top">
                     <div class="tab-content list-content">
                        <div class="list-view pad30 " style="position: relative;">
                            @if(Setting::get('demo_mode', 0) == 1)
                                 <div class="col-md-12" style="height:50px;color:red;">
                                    ** Demo Mode : Use this card - CardNo:4000056655665556, MM:12, YY:20, CVV:123.
                                </div>
                            @else
                                <div class="col-md-8">
                                    <center>PLEASE NOTE PAYMENT MUST BE MADE ON TIME TO GO ONLINE TO WORK</center>
                                </div>
                                <div class="col-md-4">
                                    <SPAN><b>$0.0</b></SPAN>
                                    
                                </div>
                            @endif
                            <a href="#" class="sub-right pull-right" data-toggle="modal" data-target="#add-card-modal" style="margin-right: 10px;margin-bottom: 10px;position: absolute;
    right: 40px;top: 90px;">@lang('provider.card.add_debit_card')</a>
                            <table class="earning-table table table-responsive">
                                <thead>
                                    <tr>
                                        <th>@lang('provider.card.type')</th>
                                        <th>@lang('provider.card.four')</th>    
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($cards)!='0')    
                                    @foreach($cards as $each)
                                        <tr>
                                            <td>{{ $each->brand }}</td>
                                            <td>{{ $each->last_four }}</td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2">@lang('provider.card.notfound')</td>
                                       </tr>
                                    @endif
                                </tbody>

                            </table>

                             <table class="earning-table table table-responsive">
                                <thead>
                                    <tr>
                                        <th>@lang('provider.inr')</th>
                                        <th>@lang('provider.description')</th>
                                        <th>@lang('provider.amount')</th>
                                        <th>@lang('provider.status')</th>    
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($cards)!='0')    
                                    @foreach($cards as $each)
                                        <tr>
                                            <td>{{ $each->brand }}</td>
                                            <td>{{ $each->last_four }}</td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2">@lang('provider.card.notfound')</td>
                                       </tr>
                                    @endif
                                </tbody>

                            </table>
                        </div>
                     </div>
                     </div>
               
                </div>
            </div>
        </div>
    </div>
    <!-- Add Card Modal -->
    <div id="add-card-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">@lang('provider.card.add_debit_card')</h4>
          </div>
            <form id="payment-form" action="{{ url('provider/card/store') }}" method="POST" >
                {{ csrf_field() }}

          <input type="hidden" data-stripe="currency" value="usd">
          <div class="modal-body">
            <div class="row no-margin" id="card-payment">
                <div class="payment-errors" style="display: none">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <span id="errortxt"></span>
                    </div>
                </div>    
                <div class="form-group col-md-12 col-sm-12">
                    <label>@lang('provider.card.fullname')</label>
                    <input data-stripe="name" autocomplete="off" required type="text" class="form-control" placeholder="@lang('provider.card.fullname')">
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label>@lang('provider.card.card_no')</label>
                    <input data-stripe="number" type="text" onkeypress="return isNumberKey(event);" required autocomplete="off" maxlength="16" class="form-control" placeholder="@lang('provider.card.card_no')">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>@lang('provider.card.month')</label>
                    <input type="text" onkeypress="return isNumberKey(event);" maxlength="2" required autocomplete="off" class="form-control" data-stripe="exp-month" placeholder="MM">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>@lang('provider.card.year')</label>
                    <input type="text" onkeypress="return isNumberKey(event);" maxlength="2" required autocomplete="off" data-stripe="exp-year" class="form-control" placeholder="YY">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>@lang('provider.card.cvv')</label>
                    <input type="text" data-stripe="cvc" onkeypress="return isNumberKey(event);" required autocomplete="off" maxlength="4" class="form-control" placeholder="@lang('provider.card.cvv')">
                </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-default" >@lang('provider.card.add_card')</button>
          </div>
        </form>

        </div>

      </div>
    </div> 
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>


<script type="text/javascript">
    Stripe.setPublishableKey("{{ Setting::get('stripe_publishable_key')}}");

     var stripeResponseHandler = function (status, response) {
        var $form = $('#payment-form');

        if (response.error) {
            // Show the errors on the form
            $form.find('.payment-errors').text(response.error.message);
            $form.find('button').prop('disabled', false);
            alert(response.error.message);

        } else {
            // token contains id, last4, and card type
            var token = response.id;

            // Insert the token into the form so it gets submitted to the server
            $form.append($('<input type="hidden" id="stripeToken" name="stripe_token" />').val(token));

            jQuery($form.get(0)).submit();
            $("#add-card-modal").modal('toggle');
        }


    };
            
    $('#payment-form').submit(function (e) {            
        if ($('#stripeToken').length == 0)
        {
            
            var $form = $(this);
            $form.find('button').prop('disabled', true);                
            Stripe.card.createToken($form, stripeResponseHandler);
            return false;
        }
    });

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function set_default(id)
    {
        $.ajax({
            method : 'POST',
            url : '{{ url('provider/card/set') }}',
            data : '_token={{csrf_token()}}&id='+id,
            success:function(html)
            {
                if(html=='success')
                {
                    alert('Successfully made changes');
                }
                else{
                    alert('Something Went wrong'); 
                }
            }

        })
    }
</script>    
<!-- link to the local SqPaymentForm initialization -->
<script type="text/javascript" src="{{ asset('asset/js/sq-payment-form.js') }}"></script>
@endsection

