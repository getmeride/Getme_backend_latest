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
    window.locationId ='12316516';
  </script>


  <!-- link to the custom styles for SqPaymentForm -->
<link rel="stylesheet" type="text/css" href="{{ asset('asset/css/sq-payment-form.css') }}">

<div class="pro-dashboard-content gray-bg">
    <div class="container">
        <br>
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
        @elseif(Auth::guard('provider')->user()->status == "card" || Auth::guard('provider')->user()->status == "banned")
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Your Subscription expired, Please make subscription ASAP
            </div>
        @endif
        

        
      
        <!-- End Payment Form -->
        <div class="manage-docs ">
            <div class="manage-doc-content">
                <div class="manage-doc-section ">
                    @include('common.notify')                         
                    <div class="manage-doc-section-content border-top">
                        
                        <div class="tab-content list-content">
                            <div class="list-view " style="position: relative;">
                                @if(Setting::get('demo_mode', 0) == 1)
                                     <div class="col-md-12" style="height:50px;color:red;">
                                        ** Demo Mode : Use this card - CardNo:4000056655665556, MM:12, YY:20, CVV:123.
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <h3>Subscription History</h3>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="innerPaymentButton" style="text-align: right;margin: 20px 0px 10px 0px;">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-card-modal">Pay Subscription Payment</button>
                                        </div>
                                    </div>
                                @endif
                                <table class="earning-table table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>@lang('provider.description')</th>
                                            <th>@lang('provider.amount')</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Admin Approval</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($provider_subscription)!='0')    
                                        @foreach($provider_subscription as $key => $each)
                                            <tr>
                                                <td>{{$each->transaction_id }}</td>
                                                <td>{{ $each->description }}</td>
                                                <td>{{ $each->amount }}</td>
                                                <td>{{ $each->start_date }}</td>
                                                <td>{{ $each->end_date }}</td>
                                                <td>{{ $each->status }}</td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6"><center>No Data Found</center></td>
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
                    <h4 class="modal-title">Make Subscription Payment</h4>
                </div>
                <div class="modal-body">
                    <div class="square_div_section" >
                        <div class="sq-payment-form" style="width: 100%;border: 1px solid #C6C6C6;margin-top: 25px;">
                            <div id="sq-walletbox">
                            </div>
                            <div id="sq-ccbox">
                                <form id="nonce-form" novalidate action="{{route('provider.process.card')}}" method="post">
                                    {{csrf_field()}}
                                    <div class="sq-field">
                                        <label class="sq-label" >Card Number</label>
                                        <div id="sq-card-number" style="height: 50px;"></div>
                                    </div>
                                    <div class="sq-field-wrapper">
                                        <div class="sq-field sq-field--in-wrapper">
                                            <label class="sq-label" style="font-size: 14px;">CVV</label>
                                            <div id="sq-cvv" style="height: 50px;"></div>
                                        </div>
                                        <div class="sq-field sq-field--in-wrapper">
                                            <label class="sq-label" style="font-size: 14px;">Expiration</label>
                                            <div id="sq-expiration-date" style="height: 50px;"></div>
                                        </div>
                                        <div class="sq-field sq-field--in-wrapper">
                                            <label class="sq-label" style="font-size: 14px;">Postal</label>
                                            <div id="sq-postal-code" style="height: 50px;"></div>
                                        </div>
                                    </div>
                                    <div class="sq-field" style="    text-align: center;">
                                        <button id="sq-creditcard" class="sq-button" onclick="onGetCardNonce(event)" style="width: 300px;">
                                        Pay ${{ Setting::get('provider_monthly_charger')}} Subscription Now
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('asset/js/sq-payment-form.js') }}"></script>
@endsection

