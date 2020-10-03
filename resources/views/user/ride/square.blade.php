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
		    				      
		    		<form id="add_money" action="{{url('/payment')}}" method="post">
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
		            	 

		            	 <input type="hidden" name="payment_mode" value="SQUARE" />



		            	<dl class="dl-horizontal left-right">
                          
                            <dt>@lang('user.ride.total')</dt>
                             <dd>{{currency($request->payment->total)}}</dd> 

                            <dt>@lang('user.ride.tips')</dt>
                            <dd>{{currency($tip_amount)}}</dd>


                            <dt class="big">@lang('user.ride.amount_paid')</dt>

                            <dd class="big">{{currency($amount)}}</dd>

                        </dl>
			                    <br>   
			                                         <div id="sq-ccbox">
    <!--
      Be sure to replace the action attribute of the form with the path of
      the Transaction API charge endpoint URL you want to POST the nonce to
      (for example, "/process-card")
    -->                  <dl>
    	                    <dt>Card Details</dt>
    	
                          </dl>
                        
                          <fieldset>
                            <span class="label" style="color: black;">Card Number</span>
                            <div id="sq-card-number"></div>

                            <div class="third">
                              <span class="label" style="color: black;">Expiration</span>
                              <div id="sq-expiration-date"></div>
                            </div>

                            <div class="third">
                              <span class="label" style="color: black;">CVV</span>
                              <div id="sq-cvv"></div>
                            </div>

                            <div class="third" >
                              <span class="label" style="color: black;">Postal</span>
                              <div id="sq-postal-code"></div>
                            </div>
                          </fieldset>

                          <button id="sq-creditcard" class="button-credit-card" onclick="requestCardNonce(event)">Pay</button>

                          <div id="error"></div>

                          <!--
                            After a nonce is generated it will be assigned to this hidden input field.
                          -->
                          <input type="hidden" id="card-nonce" name="nonce">
                       
                    
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
 <script type="text/javascript" src="https://js.squareup.com/v2/paymentform">
    </script>

    <!-- link to the local SqPaymentForm initialization -->
    <script type="text/javascript" src="{{asset('asset/js/sqpaymentform.js')}}">
    </script>
<script>
   document.addEventListener("DOMContentLoaded", function(event) {
    if (SqPaymentForm.isSupportedBrowser()) {
      paymentForm.build();
      paymentForm.recalculateSize();
    }
  });
  </script>


@endsection