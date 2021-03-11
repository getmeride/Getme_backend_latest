@extends('admin.layout.base')

@section('title', 'Subscription History ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            @if(Setting::get('demo_mode') == 1)
                <div class="col-md-12" style="height:50px;color:red;">
                    ** Demo Mode : @lang('admin.demomode')
                </div>
            @endif
            <h5 class="mb-1">Subscription History</h5>
            @if(count($requests) != 0)
            <div class="col-md-12">    
                <div class="col-md-3"></div>
                <div class="col-md-6"> 
                    <!-- <form action="{{route('admin.subscriptions.index')}}" method="GET"> 
                        <div class="col-md-6">
                            <input type="text" class="form-control col-md-6" name="search" value="{{@Request::get('search')}}">
                        </div>
                        <div class="col-md-6"> 
                            <button type="submit" class="btn btn-success btn-md col-md-6" ><span class="glyphicon glyphicon-search" style="font-size: 15px;">Search</span></button>
                        </div> 
                    </form> -->
                </div>  
            </div>
            @if(Session::has('flash_success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ Session::get('flash_success') }}
            </div>
            @elseif(Session::has('flash_error'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ Session::get('flash_error') }}
                </div>
            @endif
            <br><br>
            <table class="table table-striped table-bordered dataTable" id="table-4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mobile Number</th>
                        <th>Name</th>
                        <th>transaction_id</th>
                        <th>amount</th>
                        <th>start_date</th>
                        <th>end_date</th>
                        <th>status</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($requests as $index => $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ ($request->providerInfo) ? $request->providerInfo->mobile : '' }}</td>
                        <td>{{ ($request->providerInfo) ? $request->providerInfo->first_name.' '.$request->providerInfo->last_name : '' }}</td>
                        <td>{{ $request->transaction_id }}</td>
                        <td>{{ $request->amount }}</td>
                        <td>{{ $request->start_date }}</td>
                        <td>{{ $request->end_date }}</td>
                        <td>{{ $request->status }}</td>
                        <td>
                            @if($request->status =="Pending")
                                <a class="btn btn-success btn-block" href="{{route('admin.provider.subscription.update', $request->provider_id )}}">Approved</a>
                            @else
                                -
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Mobile Number</th>
                        <th>Name</th>
                        <th>transaction_id</th>
                        <th>amount</th>
                        <th>start_date</th>
                        <th>end_date</th>
                        <th>status</th>
                        <th>action</th>
                    </tr>
                </tfoot>
            </table> 
            <div class="row"> 
                <div class="col-md-6 page_info">
                    Showing {{($pagination->currentPage-1)*$pagination->perPage+1}} to {{$pagination->currentPage*$pagination->perPage}}
                    of  {{$pagination->total}} entries                    
                </div>
                <div class="col-md-6 pagination_cover">
                {{$requests->appends(['search' => @Request::get('search')])->links()}} 
                </div>
            </div>
            @else
            <h6 class="no-result">No results found</h6>
            @endif 
        </div>
    </div>
</div>  
@endsection