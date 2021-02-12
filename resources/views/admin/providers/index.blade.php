@extends('admin.layout.base')

@section('title', 'Providers ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            @if(Setting::get('demo_mode') == 1)
        <div class="col-md-12" style="height:50px;color:red;">
                    ** Demo Mode : @lang('admin.demomode')
                </div>
                @endif
            <h5 class="mb-1">
                @lang('admin.provides.providers')
                @if(Setting::get('demo_mode', 0) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            <div class="col-md-12">    
            <div class="col-md-3"> 
            </div>
            <div class="col-md-6"> 
            <form action="{{route('admin.provider.index')}}" method="GET"> 
            <div class="col-md-6">
            <input type="text" class="form-control col-md-6" name="search" value="{{@Request::get('search')}}">
            </div>
            <div class="col-md-6"> 
            <button type="submit" class="btn btn-success btn-md col-md-6" ><span class="glyphicon glyphicon-search" style="font-size: 15px;">Search</span></button>
            </div> 
            </form>
            </div> 
            <div class="col-md-3" style="text-align: right;">
            @if(@Request::get('fleet') != 'fleet')
            <a href="{{ route('admin.provider.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>@lang('admin.provides.add_new_provider')</a> 
            @endif
            </div> 
            </div>  <br><br>  
            <table class="table table-striped table-bordered dataTable" id="table-5">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.provides.full_name')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>@lang('admin.provides.total_requests')</th>
                        <th>@lang('admin.provides.accepted_requests')</th>
                        <th>@lang('admin.provides.cancelled_requests')</th>
                        <th>@lang('admin.users.Wallet_Amount')</th>
                        <th>@lang('admin.users.Recharge')</th>
                        <th>@lang('admin.provides.online')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                @php($page = ($pagination->currentPage-1)*$pagination->perPage)
                @foreach($providers as $index => $provider)
                @php($page++)
                    <tr>
                        <td>{{ $page }}</td>
                        <td>{{ $provider->first_name }} {{ $provider->last_name }}</td>
                        @if(Setting::get('demo_mode', 0) == 1)
                            <td>{{ substr($provider->email, 0, 3).'****'.substr($provider->email, strpos($provider->email, "@")) }}</td>
                        @else
                            <td>{{ $provider->email }}</td>
                        @endif
                        @if(Setting::get('demo_mode', 0) == 1)
                            <td>+919876543210</td>
                        @else
                            <td>{{ $provider->mobile }}</td>
                        @endif
                        <td>{{ $provider->total_requests() }}</td>
                        <td>{{ $provider->accepted_requests() }}</td>
                        <td>{{ $provider->total_requests() - $provider->accepted_requests() }}</td>
                         <td>{{currency($provider->wallet_balance)}}</td>
                        <td><button type="button" class="btn btn-info btn-md pull-left" data-toggle="modal" data-target="#myModalRechargeWallet{{$provider->id}}"><i class="fa fa-money" aria-hidden="true"></i> Recharge Wallet</button> </td>
                        <td>
                            @if($provider->active_documents() == $total_documents && $provider->service != null)
                                 <a class="btn btn-success btn-block" href="{{route('admin.provider.document.index', $provider->id )}}">All Set!</a>
                            @else                               
                                <a class="btn btn-danger btn-block label-right" href="{{route('admin.provider.document.index', $provider->id )}}">Attention! <span class="btn-label">{{ $provider->pending_documents() }}</span></a>
                            @endif
                        </td>
                        <!-- <td>
                            @if($provider->service)
                                @if($provider->service->status == 'active')
                                    <label class="btn btn-block btn-primary">Yes</label>
                                @else
                                    <label class="btn btn-block btn-warning">No</label>
                                @endif
                            @else
                                <label class="btn btn-block btn-danger">N/A</label>
                            @endif
                        </td> -->
                        <td>
                            <div class="input-group-btn">
                                @if($provider->status == 'approved')
                                <a class="btn btn-danger btn-block" href="{{ route('admin.provider.disapprove', $provider->id ) }}">@lang('Disable')</a>
                                @else
                                <a class="btn btn-success btn-block" href="{{ route('admin.provider.approve', $provider->id ) }}">@lang('Enable')</a>
                                @endif
                                <button type="button" 
                                    class="btn btn-info btn-block dropdown-toggle"
                                    data-toggle="dropdown">@lang('admin.action')
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.provider.request', $provider->id) }}" class="btn btn-default"><i class="fa fa-search"></i> @lang('admin.History')</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.provider.statement', $provider->id) }}" class="btn btn-default"><i class="fa fa-account"></i> @lang('admin.Statements')</a>
                                    </li>
                                    @if( Setting::get('demo_mode') == 0)
                                    <li>
                                        <a href="{{ route('admin.provider.edit', $provider->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> @lang('admin.edit')</a>
                                    </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('admin.provider.destroy', $provider->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            @if( Setting::get('demo_mode') == 0)
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>@lang('admin.delete')</button>
                                            @endif
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- Recharge Wallet Model -->
                        <div class="modal fade" id="myModalRechargeWallet{{$provider->id}}" role="dialog">
                            <div class="modal-dialog modal-sm">
                            <form action="{{route('admin.provider.wallet.recharge')}}" method="POST">
                            {{csrf_field()}}
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Recharge Wallet</h4>
                                </div>  
                                <div class="modal-body">
                                <h4 class="modal-title">Current Balance : {{currency($provider->wallet_balance)}}</h4>
                                   <div class="form-group" style="margin: 10px 0px;">
                                        <input type="radio" id="credited" name="recharge_type" value="CREDITED" checked="" >
                                        <label for="credited">CREDITED</label>
                                        <input type="radio"  id="debited" name="recharge_type" value="DEBITED"  >
                                        <label for="debited">DEBITED</label>       
                                   </div>
                                

                                   <input type="hidden" name="provider_id" class="form-control" value="{{$provider->id}}">
                                   <input type="number" name="wallet_amount" class="form-control" placeholder="Enter Amount">
                                </div>
                                <div class="modal-footer">
                                <button type="submit" style="margin-left: 1em;" class="btn btn-info btn-md">Recharge</button>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                </div>
                              </div>
                            </form>
                            </div>
                      </div>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.provides.full_name')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>@lang('admin.provides.total_requests')</th>
                        <th>@lang('admin.provides.accepted_requests')</th>
                        <th>@lang('admin.provides.cancelled_requests')</th>
                        <th>@lang('admin.users.Wallet_Amount')</th>
                        <th>@lang('admin.users.Recharge')</th>
                        <th>@lang('admin.provides.online')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </tfoot>
            </table> 
            <div class="row"> 
                <div class="col-md-6 page_info">
                    Showing {{($pagination->currentPage-1)*$pagination->perPage+1}} to {{$pagination->currentPage*$pagination->perPage}}
                    of  {{$pagination->total}} entries                    
                </div>
                <div class="col-md-6 pagination_cover">
                {{$providers->appends(['search' => @Request::get('search')])->links()}} 
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
        if ( this.context.length ) {
            var jsonResult = $.ajax({
                url: "{{url('admin/provider')}}?page=all",
                data: {},
                success: function (result) {                       
                    p = new Array();
                    $.each(result.data, function (i, d)
                    {
                        var item = [d.id,d.first_name, d.last_name, d.email,d.mobile,d.rating, d.wallet_balance];
                        p.push(item);
                    });
                },
                async: false
            });
            var head=new Array();
            head.push("ID", "First Name", "Last Name", "Email", "Mobile", "Rating", "Wallet");
            return {body: p, header: head};
        }
    } );

    $('#table-5').DataTable( {
        responsive: true,
        paging:false,
            info:false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ], 
            "bFilter": false, 
            "bPaginate": false,
            "info":     false,
            "ordering": false,
    } );
</script>
@endsection