@extends('user.layout.base')

@section('title', 'Wallet ')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.my_wallet')</h4>
            </div>
        </div>
        @include('common.notify')

        <div class="row no-margin">
            <form action="{{url('add/money')}}" id="add_money" method="POST">
            {{ csrf_field() }}
                <div class="col-md-6">
                     
                    <div class="wallet">
                        <h4 class="amount">
                        	<span class="price">{{currency(Auth::user()->wallet_balance)}}</span>
                        	<span class="txt">@lang('user.in_your_wallet')</span>
                        </h4>
                    </div>                                                               

                </div>
                
                <div class="col-md-6">
                    
                    <h6><strong>@lang('user.add_money')</strong></h6>

                    <div class="input-group full-input">
                        <input type="number" id="amount" class="form-control" name="amount" placeholder="@lang('user.enter_amount')" >
                    </div>
                    <br>



                      <div id="sq-ccbox">
    <!--
      Be sure to replace the action attribute of the form with the path of
      the Transaction API charge endpoint URL you want to POST the nonce to
      (for example, "/process-card")
    -->
                        
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

                          <button id="sq-creditcard" class="button-credit-card" onclick="requestCardNonce(event)">@lang('user.add_money')</button>

                          <div id="error"></div>

                          <!--
                            After a nonce is generated it will be assigned to this hidden input field.
                          -->
                          <input type="hidden" id="card-nonce" name="nonce">
                          <input type="hidden" name="card_id" value="SQUARE">
                    
                      </div>

                
               

                </div>
               
            </form>

        </div> <br><br>
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">Wallet Transfer</h4>
            </div>
        </div>
        <div class="row no-margin">
            <form action="{{url('wallet/transfer')}}" method="POST">
            {{ csrf_field() }}
                <div class="col-md-3">
                <div><input type="text" name="search_user" class="form-control" placeholder="Enter User email"></div>
                <div id="dropbox" style="display: none;">
                    <select id="user" name="user" size="10" style="margin: 1px; width: 170px;height: 86px;""> 
                    </select>
                </div>
                </div>
                <div class="col-md-3">
                <div><input type="text" name="amount" class="form-control" placeholder="Enter Amount"></div>
                <div class="profile" style="height: 171px;width: 186px;overflow: scroll;overflow-x: hidden;">
                @foreach($users as $data)
                <input type="hidden" id="users_ids" name="users_ids[]" value="{{$data->id}}">
                <div class="profile{{$data->id}}" style="display: none;">
                    <div class="alert alert-success">
                          <div class="toast-header">
                            <img src="{{url('storage',$data->picture)}}" class="rounded mr-2" width="50%">  
                            <button type="button" class="close" data-id="{{$data->id}}">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="toast-body">
                            <p>Name : <strong>{{$data->first_name}} {{$data->last_name}}</strong></p>
                            <p>Email : <strong>{{$data->email}}</strong></p>
                          </div>
                    </div>
                </div>
                @endforeach
                </div>
                </div>
                <div class="col-md-3">
                <button type="submit" class="form-control btn btn-primary" >Transfer</button>
                </div>
            </form>
        </div>

        <div class="manage-doc-section-content border-top">
             <div class="tab-content list-content">
                <div class="list-view pad30 ">
                    <table class="earning-table table table-responsive">
                        <thead>
                            <tr>
                                <th>@lang('provider.sno')</th>
                                <th>@lang('provider.transaction_ref')</th>
                                <th>@lang('provider.transaction_desc')</th>
                                <th>@lang('provider.status')</th>
                                <th>@lang('provider.amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($page = ($pagination->currentPage-1)*$pagination->perPage)
                               @foreach($wallet_transation as $index=>$wallet)
                               @php($page++)
                                    <tr>
                                        <td>{{$page}}</td>
                                        <td>{{$wallet->transaction_alias}}</td>
                                        <td>{{$wallet->transaction_desc}}</td>
                                        <td>@if($wallet->type == 'C')  @lang('user.credit') @else @lang('user.debit') @endif</td>
                                        <td>{{currency($wallet->amount)}}
                                        </td>
                                       
                                    </tr>
                                @endforeach  
                        </tbody>

                    </table>
                     {{ $wallet_transation->links() }}
                </div>
             </div>
         </div>

    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.profile').hide();  
        
        $('input[name=search_user]').on("input",function(){  
        var search_user_email = this.value;
        if(search_user_email)
        {
            $.ajax({url: "{{ url('filter/user/email') }}/"+search_user_email,dataType: "json",
                   success: function(data){
                        console.log(data);
                        $('#user').html('');
                        if(data.users)
                        {
                        $.each( data.users, function( index, value ){ 
                            $('#dropbox').show();
                            console.log(value.email);
                            $('#user').append('<option value='+value.id+'>'+value.first_name+' '+value.last_name +'</option>'); 
                        });
                        } 
                  }});
        }
        else
        {
            $('#dropbox').hide();
            $('.profile').hide(); 
        }
        });
        $('#user').on("click",function(){  
            $('.profile'+this.value).show();
            $('.profile').show();  
        }); 
        $('.close').on("click",function(){  
            var user_id = $(this).attr('data-id');
            $('.profile'+user_id).hide();
        }); 
    }); 


</script>







<script type="text/javascript">
   
    function card(value){
        $('#card_id, #braintree').fadeOut(300);
        if(value == 'CARD'){
            $('#card_id').fadeIn(300);
        }else if(value == 'BRAINTREE'){
            $('#braintree').fadeIn(300);
        }
    }

</script>

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