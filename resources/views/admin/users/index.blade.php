@extends('admin.layout.base')

@section('title', 'Users ')

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
                @lang('admin.users.Users')
                @if(Setting::get('demo_mode', 0) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif               
            </h5>
            <div class="col-md-12">    
            <div class="col-md-3"> 
            </div>
            <div class="col-md-6"> 
            <form action="{{route('admin.user.index')}}" method="GET"> 
            <div class="col-md-6">
            <input type="text" class="form-control col-md-6" name="search" value="{{@Request::get('search')}}">
            </div>
            <div class="col-md-6"> 
            <button type="submit" class="btn btn-success btn-md col-md-6" ><span class="glyphicon glyphicon-search" style="font-size: 15px;">Search</span></button>
            </div> 
            </form>
            </div> 
            <div class="col-md-3" style="text-align: right;"> 
            <a href="{{ route('admin.user.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New User</a>  
            </div> 
            </div>  <br><br>   
            <table class="table table-striped table-bordered dataTable" id="table-5">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.first_name')</th>
                        <th>@lang('admin.last_name')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>@lang('admin.users.Rating')</th>
                        <th>@lang('admin.users.Wallet_Amount')</th>
                        <th>@lang('admin.users.Recharge')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @php($page = ($pagination->currentPage-1)*$pagination->perPage)
                    @foreach($users as $index => $user)
                    @php($page++)
                    <tr>
                        <td>{{ $page }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>{{ substr($user->email, 0, 3).'****'.substr($user->email, strpos($user->email, "@")) }}</td>
                        @else
                        <td>{{ $user->email }}</td>
                        @endif
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>+919876543210</td>
                        @else
                        <td>{{ $user->mobile }}</td>
                        @endif
                        <td>{{ $user->rating }}</td>
                        <td>{{currency($user->wallet_balance)}}</td>
                        <td><button type="button" class="btn btn-info btn-md pull-left" data-toggle="modal" data-target="#myModalRechargeWallet{{$user->id}}"><i class="fa fa-money" aria-hidden="true"></i> Recharge Wallet</button> </td>
                        <td>
                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <a href="{{ route('admin.user.request', $user->id) }}" class="btn btn-info"><i class="fa fa-search"></i> @lang('admin.History')</a>
                                @if( Setting::get('demo_mode') == 0)
                                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.edit')</a>
                                <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> @lang('admin.delete')</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    <!-- Recharge Wallet Model -->
                        <div class="modal fade" id="myModalRechargeWallet{{$user->id}}" role="dialog">
                            <div class="modal-dialog modal-sm">
                            <form action="{{route('admin.user.wallet.recharge')}}" method="POST">
                            {{csrf_field()}}
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Recharge Wallet</h4>
                                </div>  
                                <div class="modal-body">
                                <h4 class="modal-title">Current Balance : {{currency($user->wallet_balance)}}</h4>
                                   <input type="hidden" name="user_id" class="form-control" value="{{$user->id}}">
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
                        <th>@lang('admin.first_name')</th>
                        <th>@lang('admin.last_name')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>@lang('admin.users.Rating')</th>
                        <th>@lang('admin.users.Wallet_Amount')</th>
                        <th>@lang('admin.users.Recharge')</th>
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
                {{$users->appends(['search' => @Request::get('search')])->links()}} 
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
                url: "{{url('admin/user')}}?page=all",
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
            head.push("ID", "First Name", "Last Name", "Email", "Mobile", "Rating", "Wallet Amount");            
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