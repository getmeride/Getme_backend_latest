@extends('user.layout.base')

@section('title', 'My Trips ')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.my_trips')</h4>
            </div>
        </div>

        <div class="row no-margin ride-detail">
            <div class="col-md-12">
            @if($trips->count() > 0)

                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>@lang('user.booking_id')</th>
                            <th>@lang('user.date')</th>
                            <th>@lang('user.profile.name')</th>
                            <th>@lang('user.amount')</th>
                            <th>@lang('user.type')</th>
                            <th>@lang('user.payment')</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($trips as $trip)

                        <tr data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed">
                            <td><span class="arrow-icon fa fa-chevron-right"></span></td>
                            <td>{{ $trip->booking_id }}</td>
                            <td>{{date('d-m-Y',strtotime($trip->assigned_at))}}</td>
                            @if($trip->provider)
                                <td>{{$trip->provider->first_name}} {{$trip->provider->last_name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($trip->payment)
                                <td>{{currency($trip->payment->total)}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($trip->service_type)
                                <td>{{$trip->service_type->name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>@lang('user.paid_via') {{$trip->payment_mode}}</td>
                        </tr>
                        <tr class="hiddenRow">
                            <td colspan="12">
                                <div class="accordian-body collapse row" id="trip_{{$trip->id}}">
                                    <div class="col-md-6">
                                        <div class="my-trip-left">
                                        <?php 
                                    $map_icon = asset('asset/img/marker-start.png');
                                    $static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=600x450&maptype=terrain&format=png&visual_refresh=true&markers=icon:".$map_icon."%7C".$trip->s_latitude.",".$trip->s_longitude."&markers=icon:".$map_icon."%7C".$trip->d_latitude.",".$trip->d_longitude."&path=color:0x191919|weight:8|enc:".$trip->route_key."&key=".Setting::get('map_key'); ?>
                                            <div class="map-static">
                                                <img src="{{$static_map}}" height="280px;">
                                            </div>
                                            <div class="from-to row no-margin">
                                                <div class="from">
                                                    <h5>@lang('user.from')</h5>
                                                    <h6>{{date('H:i A - d-m-y', strtotime($trip->started_at))}}</h6>
                                                    <p>{{$trip->s_address}}</p>
                                                </div>
                                                <div class="to">
                                                    <h5>@lang('user.to')</h5>
                                                    <h6>{{date('H:i A - d-m-y', strtotime($trip->finished_at))}}</h6>
                                                    <p>{{$trip->d_address}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="mytrip-right">

                                            <div class="fare-break">

                                                <h4 class="text-center">
                                                <strong>
                                                @if($trip->service_type)
                                                    {{$trip->service_type->name}}
                                                @endif
                                                 - @lang('user.fare_breakdown')</strong></h4>

                                                <h5>@lang('user.ride.base_price') <span>
                                                @if($trip->payment)
                                                    {{currency($trip->payment->fixed)}}
                                                @endif
                                                </span></h5>
                                                @if($trip->service_type->calculator=='MIN')
                                                    <h5>@lang('user.ride.minutes_price') <span>
                                                    @if($trip->payment)
                                                        {{currency($trip->payment->minute)}}
                                                    @endif
                                                    </span></h5>
                                                @endif
                                                @if($trip->service_type->calculator=='HOUR')
                                                    <h5>@lang('user.ride.hours_price') <span>
                                                    @if($trip->payment)
                                                        {{currency($trip->payment->hour)}}
                                                    @endif
                                                    </span></h5>
                                                @endif
                                                @if($trip->service_type->calculator=='DISTANCE')
                                                    <h5>@lang('user.ride.distance_price') <span>
                                                    @if($trip->payment)
                                                        {{currency($trip->payment->distance)}}
                                                    @endif
                                                    </span></h5>
                                                @endif
                                                @if($trip->service_type->calculator=='DISTANCEMIN')
                                                    <h5>@lang('user.ride.minutes_price') <span>
                                                    @if($trip->payment)
                                                        {{currency($trip->payment->minute)}}
                                                    @endif
                                                    </span></h5>
                                                    <h5>@lang('user.ride.distance_price') <span>
                                                    @if($trip->payment)
                                                        {{currency($trip->payment->distance)}}
                                                    @endif
                                                    </span></h5>
                                                @endif
                                                @if($trip->service_type->calculator=='DISTANCEHOUR')
                                                    <h5>@lang('user.ride.hours_price') <span>
                                                    @if($trip->payment)
                                                        {{currency($trip->payment->hour)}}
                                                    @endif
                                                    </span></h5>
                                                    <h5>@lang('user.ride.distance_price') <span>
                                                    @if($trip->payment)
                                                        {{currency($trip->payment->distance)}}
                                                    @endif
                                                    </span></h5>
                                                @endif
                                                
                                                 @if($trip->payment)
                                                    @if($trip->payment->wallet)
                                                        <h5>@lang('user.ride.wallet_deduction') <span>
                                                            {{currency($trip->payment->wallet)}}
                                                        </span></h5>
                                                    @endif
                                                @endif
                                                @if($trip->payment)
                                                    @if($trip->payment->discount)
                                                        <h5>@lang('user.ride.promotion_applied') <span>
                                                            {{currency($trip->payment->discount)}}
                                                        </span></h5>
                                                    @endif
                                                @endif
                                                @if($trip->payment)
                                                    @if($trip->payment->tips)
                                                        <h5>@lang('user.ride.tips') <span>
                                                            {{currency($trip->payment->tips)}}
                                                        </span></h5>
                                                    @endif
                                                @endif
                                                <h5><strong>@lang('user.ride.tax_price') </strong><span><strong>
                                                @if($trip->payment)
                                                {{currency($trip->payment->tax)}}
                                                @endif
                                                </strong></span></h5>
                                                <h5 class="big"><strong>@lang('user.charged') - {{$trip->payment_mode}} </strong><span><strong>
                                                @if($trip->payment)
                                                {{currency($trip->payment->total-$trip->payment->discount+$trip->payment->tips)}}
                                                @endif
                                                </strong></span></h5>

                                            </div>

                                            <div class="trip-user">
                                                @if($trip->provider)
                                                <div class="user-img" style="background-image: url({{img($trip->provider->avatar)}});">
                                                </div>
                                                @else
                                                    -
                                                @endif
                                                <div class="user-right">
                                                    @if($trip->provider)
                                                        <h5>{{$trip->provider->first_name}} {{$trip->provider->last_name}}</h5>
                                                    @else
                                                    <h5>- </h5>
                                                    @endif
                                                    @if($trip->rating)
                                                    <div class="rating-outer">
                                                        <input type="hidden" class="rating" value="{{$trip->rating->provider_rating}}" disabled="disabled" />

                                                    </div>
                                                    <p>{{$trip->rating->user_comment}}</p>
                                                     @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </td>
                        </tr>

                        @endforeach


                    </tbody>
                </table>
                @else
                    <hr>
                    <p style="text-align: center;">@lang('user.no_trips')</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('asset/js/rating.js') }}"></script>
<script type="text/javascript">
    $('.rating').rating();
</script>
@endsection