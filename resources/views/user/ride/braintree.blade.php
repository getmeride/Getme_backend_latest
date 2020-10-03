@extends('user.layout.base')

@section('title', 'On Ride')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
    	@include('common.notify')
		<div class="row no-margin">
		    <div class="col-md-12">
		        <h4 class="page-title" id="ride_status"></h4>
		    </div>
		</div>
		
		<div class="row no-margin">
		        <div class="col-md-6" id="container" >
		    				      
		    		<form id="payment" action="{{url('/payment')}}" method="post">
			            {{ csrf_field() }}
			         
			                <div class="col-md-12">
			                    
			                    <h5><strong>Confirm Payment</strong></h5>

			    
			        <div class="status">
		                <h6>@lang('user.status')</h6>
		                <p>@lang('user.ride.dropped_ride')</p>
		            </div>
		            	<br/>
		            	<h5><strong>@lang('user.ride.ride_details')</strong></h5>
		            	<dl class="dl-horizontal left-right">
		            		<dt>@lang('user.booking_id')</dt>
				            <dd>{{$request->booking_id}}</dd>
		            		<dt>@lang('user.driver_name')</dt>
			                <dd>{{$request->provider->first_name}} {{$request->provider->last_name}}</dd>
			                <dt>@lang('user.service_number')</dt>
				                <dd>{{$request->provider_service->service_number}}</dd>
				                <dt>@lang('user.service_model')</dt>
				                <dd>{{$request->provider_service->service_model}}</dd>
			                <dt>@lang('user.driver_rating')</dt>
			                <dd>
			                	<div class="rating-outer">
		                            <input type="hidden" value={{$request->provider->rating}} name="rating" class="rating"/>
		                        </div>
			                </dd>
		            		<dt>@lang('user.payment_mode')</dt>
                        	<dd>{{$request->payment_mode}}</dd>
                        	<dt>@lang('user.ride.km')</dt>
                        	<dd>{{$request->distance}} kms</dd>
                        </dl>
		            	<h5><strong>@lang('user.ride.invoice')</strong></h5>


		            	<input type="hidden" name="request_id" value={{$request->id}} />
		            	<input type="hidden" name="tips" value={{$tip_amount}} />
		            	 <input type="hidden" name="braintree_nonce" value="" />

		            	 <input type="hidden" name="payment_mode" value="BRAINTREE" />



		            	<dl class="dl-horizontal left-right">
                          
                            <dt>@lang('user.ride.total')</dt>
                             <dd>{{currency($request->payment->total)}}</dd> 

                            <dt>@lang('user.ride.tips')</dt>
                            <dd>{{currency($tip_amount)}}</dd>


                            <dt class="big">@lang('user.ride.amount_paid')</dt>

                            <dd class="big">{{currency($amount)}}</dd>

                        </dl>
			                    <br>   
			                   
			                    
			                    <div id="braintree" class="col-md-12">
		                            <div id="paypal-container"></div>
		                        </div>

			                </div>
			            </form>                             
			                                       
		        </div>

		        <div class="col-md-6">
		            <dl class="dl-horizontal left-right">
		                <dt>@lang('user.request_id')</dt>
		                <dd>{{$request->id}}</dd>
		                <dt>@lang('user.time')</dt>
		                <dd>{{date('d-m-Y H:i A',strtotime($request->assigned_at))}}</dd>
		            </dl> 
		            <div class="user-request-map">

		                <div class="from-to row no-margin">
		                    <div class="from">
		                        <h5>@lang('user.from')</h5>
		                        <p>{{$request->s_address}}</p>
		                    </div>
		                    <div class="to">
		                        <h5>@lang('user.to')</h5>
		                        <p>{{$request->d_address}}</p>
		                    </div>
		                    <div class="type">
		                    	<h5>@lang('user.type')</h5>
		                        <p>{{$request->service_type->name}}</p>
		                    </div>
		                </div>
		                <?php 
		                    $map_icon = asset('asset/img/marker-start.png');
		                    $static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=600x450&maptype=roadmap&format=png&visual_refresh=true&markers=icon:".$map_icon."%7C".$request->s_latitude.",".$request->s_longitude."&markers=icon:".$map_icon."%7C".$request->d_latitude.",".$request->d_longitude."&path=color:0x191919|weight:8|enc:".$request->route_key."&key=".Setting::get('map_key'); ?>

		                    <div class="map-image">
		                    	<img src="{{$static_map}}">
		                    </div>                               
		            </div>                          
		        </div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://js.braintreegateway.com/web/dropin/1.14.1/js/dropin.min.js"></script>

<!-- Load PayPal's checkout.js Library. -->
<script src="https://www.paypalobjects.com/api/checkout.js" data-version-4 log-level="warn"></script>

<!-- Load the client component. -->
<script src="https://js.braintreegateway.com/web/3.45.0/js/client.min.js"></script>

<!-- Load the PayPal Checkout component. -->
<script src="https://js.braintreegateway.com/web/3.45.0/js/paypal-checkout.min.js"></script>

<script>
    var button = document.querySelector('#submit-button');
    var form = document.querySelector('#payment');
  

braintree.client.create({
  authorization: '{{$clientToken}}',
}, function (clientErr, clientInstance) {

  // Stop if there was a problem creating the client.
  // This could happen if there is a network error or if the authorization
  // is invalid.
  if (clientErr) {
    console.error('Error creating client:', clientErr);
    return;
  }

  // Create a PayPal Checkout component.
  braintree.paypalCheckout.create({
    client: clientInstance
  }, function (paypalCheckoutErr, paypalCheckoutInstance) {

    // Stop if there was a problem creating PayPal Checkout.
    // This could happen if there was a network error or if it's incorrectly
    // configured.
    if (paypalCheckoutErr) {
      console.error('Error creating PayPal Checkout:', paypalCheckoutErr);
      return;
    }

    // Set up PayPal with the checkout.js library
    paypal.Button.render({
      env: 'sandbox', // Or 'sandbox' ,production
      commit: true, // This will add the transaction amount to the PayPal button

      payment: function () {
       
        //alert(amount);
        return paypalCheckoutInstance.createPayment({
          flow: 'checkout', // Required
          amount: {{$amount}}, // Required
          currency: 'USD', // Required
          enableShippingAddress: true,
          shippingAddressEditable: false,
          shippingAddressOverride: {
            recipientName: '{{\Auth::user()->first_name}}',
            line1: '1234 Main St.',
            line2: 'Unit 1',
            city: 'Chicago',
            countryCode: 'US',
            postalCode: '60652',
            state: 'IL',
            phone: '{{\Auth::user()->mobile}}'
          }
        });
      },

      onAuthorize: function (data, actions) {
      
          // Submit `payload.nonce` to your server

            paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {
               document.querySelector('input[name="braintree_nonce"]').value = payload.nonce;
               console.log(payload.nonce);
               form.submit();
          });
        },

      onCancel: function (data) {
        console.log('checkout.js payment cancelled', JSON.stringify(data, 0, 2));
      },

      onError: function (err) {
        console.error('checkout.js error', err);
      }
    }, '#paypal-container').then(function () {
      // The PayPal button will be rendered in an html element with the id
      // `paypal-button`. This function will be called when the PayPal button
      // is set up and ready to be used.
    });

  });

}); 
    
</script>

@endsection