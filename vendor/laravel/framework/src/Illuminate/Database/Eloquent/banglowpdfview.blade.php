<!doctype html>

    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="{{asset('public/admin/css/bootstrap.min.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <title>{{$basic_detail->property_type ?? '-'}}Flat Invoice</title>
    </head>
    <body>
    <style>
    /*css*/

    table tr,td,th{
        border: 1px solid;
        border-collapse: collapse;
        padding-left: 5px;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-right: 5px;
    }

    .border-0{
        border: 0px !important;
    }
    .border-top-0{
     border-top: 0px !important;   
    }
    </style>
    <br>
    <div class="maininvoice container" >
        {{-- Page 1 start --}}
        <section class="page1">
            
             {{-- <div class="innerHeader" style="width: 100%;float: left;">
                <div class="" style="width: 100%;float: left;">
                    <strong>  --}}
                    <p>{{ $basic_detail->register_no ?? '-'}}</p>
                    <p>Borrower Name:{{ $basic_detail->borrower_name ?? '-' }}(File No:{{ $basic_detail->bank_file_no ?? '-' }})</p>
                    <p>To,</p>
                    <p>State Bank of india, RACPC branch ambawadi ahmedabad</p>
                    <p><center>Valuation Report (In Respect of Flats)</center></p>
                  {{--  </strong>
                </div>
            </div>  --}}
            <table style="width: 100%">
                <tr>
                    <td>I</td>
                    <td colspan="2"><strong>GENERAL</strong></td>
                    <td>&nbsp;</td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td colspan="3">Purpose for which the valuation is made</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="2">2</td>
                    <td class="text-center">a)</td>
                    <td>Date of inspection</td>
                    <td class="text-center">:</td>
                    <?php 
                        $visit_date='';
                        if(!empty($basic_detail->visit_date)){
                            $visit_date=date('d-m-Y',strtotime($basic_detail->visit_date));
                        }
                    ?>
                    <td>{{ $visit_date ?? '-'}}</td>
                </tr>
                <tr>
                    <td class="text-center">b)</td>
                    <td>Date on which the valuation is made</td>
                    <td class="text-center">:</td>
                    <td>{{ date("d-m-Y") }}</td>
                </tr>
                <tr>
                    <td rowspan="4">3</td>
                    <td colspan="3">List of documents produced for perusal</td>
                    <td>&nbsp;</td>
                </tr>
                <tr >
                    <td colspan="2">i) &nbsp;&nbsp;Agreement for Sale</td>
                    <td class="text-center">:</td>
                    <td>iv) Rajachitthi</td>
                </tr>
                <tr >
                    <td colspan="2">ii) &nbsp;&nbsp;Index</td>
                    <td class="text-center">:</td>
                    <td>v) BU Permission</td>
                </tr>
                <tr >
                    <td colspan="2">iii) &nbsp;&nbsp;Approved Lay out Plan</td>
                    <td class="text-center">:</td>
                    <td>&nbsp;</td>
                </tr>
                <tr >
                    <td>4</td>
                    <td colspan="2">Name of the owner(s) and his/ Their address(es) with Phone &nbsp;no.&nbsp; (details of share of each owner in case of joint ownership</td>
                    <td class="text-center">:</td>
                    <td><strong>{{ $basic_detail->owner_name ?? '-' }}</strong></br>Ownership</td>
                </tr>
                <tr >
                    <td>5</td>
                    <td colspan="2">Brief description of the property</td>
                    <td class="text-center">:</td>
                    <td>Free Hold (Please refer title report)</td>
                </tr>
                <tr >
                    <td rowspan="6">6</td>
                    <td colspan="3">Location of property</td>
                    <td>&nbsp;</td>
                </tr>
                <tr >
                    <td class="text-center">a)</td>
                    <td>Plot No. / Survey No</td>
                    <td class="text-center">:</td>
                    <td><strong>Survey No : {{ $basic_detail->survey_no  ?? '-'}}</strong></td>
                </tr>
                <tr >
                    <td class="text-center">b)</td>
                    <td>Door No.</td>
                    <td class="text-center">:</td>
                    <td>Flat No: {{ $basic_detail->f_p_no ?? '-'}}</td>
                </tr>
                <tr >
                    <td class="text-center">c)</td>
                    <td>T.S. No/ Village</td>
                    <td class="text-center">:</td>
                    <td><strong>Mouje:{{ $basic_detail->mouje ?? '-'}}</strong></td>
                </tr>
                <tr >
                    <td class="text-center">d)</td>
                    <td>Ward/ Taluka</td>
                    <td class="text-center">:</td>
                    <td> Shela</td>
                </tr>
                <tr >
                    <td class="text-center">e)</td>
                    <td>Mandal / Distict</td>
                    <td class="text-center">:</td>
                    <td> Ahmedabad</td>
                </tr>
                <tr >
                    <td>&nbsp;</td>
                    <td class="text-center">f)</td>
                    <td>Date of issue and validity of layout of approved map / plan</td>
                    <td>&nbsp;</td>
                    <?php 
                    $date4='';
                        if(!empty($basic_detail->date4)){
                            $date4=date('d-m-Y',strtotime($basic_detail->date4));
                        }
                    ?>
                    <td>
                        <strong>
                            As per Approved Plan No : {{ $basic_detail->rajachitthi_no }} 
                            <br>Date : {{ $date4}}
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-center">g)</td>
                    <td>Approved map / plan issuing authority</td>
                    <td>&nbsp;</td>
                    <td>{{$basic_detail->plan_approving_authority ?? '-'}}</td>
                </tr>
                <tr >
                    <td>&nbsp;</td>
                    <td class="text-center">h)</td>
                    <td>Whether genuineness or authenticity of approved map / plan is verified</td>
                    <td>&nbsp;</td>
                    <td>Original plan not verified. However the photocopy relied upon seems to be genuine.</td>
                </tr>
                <tr >
                    <td>&nbsp;</td>
                    <td class="text-center">i)</td>
                    <td>Any other comments by our empanelled valuers on authentic of approved plan</td>
                    <td>&nbsp;</td>
                    <td>Original plan not verified. However the photocopy relied upon seems to be genuine.</td>
                </tr>
                <tr >
                    <td>7</td>
                    <td colspan="2" >Postal address of the property</td>
                    <td>&nbsp;</td>
                    <td><strong>{{ $basic_detail->postal_address ??  '-'}}</strong></td>
                </tr>
                <tr >
                    <td rowspan="3">8</td>
                    <td colspan="2">City / Town</td>
                    <td class="text-center">:</td>
                    <td>Ahmedabad</td>
                </tr>
                <tr >
                    <td colspan="2">Residential Area</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->type_of_property ?? '-'}}</td>
                </tr>
                <tr >
                    <td colspan="2">Commercial Area</td>
                    <td class="text-center">:</td>
                    <td>-</td>
                </tr>
            </table>
            <table style="width: 100%" style="border: 1px solid">
                <tr>
                    <td >&nbsp;</td>
                    <td colspan="2">Industrial Area</td>
                    <td class="text-center">:</td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="3">9</td>
                    <td colspan="2">Classification of the area</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->classification_of_locality ?? '-'}}</td>
                </tr>
                <tr >
                    <td class="text-center">i)</td>
                    <td>High / Middle /Poor</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->classification_second ?? '-'}} </td>
                </tr>
                <tr >
                    <td class="text-center">ii)</td>
                    <td>Urban / Semi Urban /Rural</td>
                    <td class="text-center">&nbsp;:</td>
                    <td>{{ $basic_detail->classification_first  ?? '-'}}</td>
                </tr>
                <tr >
                    <td>10</td>
                    <td colspan="2">Coming under Corporation limit Panchayat / Municipality</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->plan_approving_authority ?? '-'}}</td>
                </tr>
                <tr >
                    <td>&nbsp;11</td>
                    <td colspan="2">Whethe covered under any State/Central Govt. enactments (e.g Urban Land Ceiling Act) or Notified under agency area/Scheduled area/ cantonment area</td>
                    <td class="text-center">:</td>
                    <td>Please refer title report</td>
                </tr>
                <tr>
                    <td rowspan="5">12</td>
                    <td colspan="2">Boundaries of the property</td>
                    <td class="text-center">:</td>
                    <td style="padding: 0px 0px;">
                        <table style="width: 100%" class="border-0">
                            <tr class="border-0">
                                <td class="border-0 text-center" style="width: 50%;border-right: 1px solid !important">As per Agreement for sale</td>
                                <td class="border-0 text-center" style="width: 50%">Actual</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr >
                    <td colspan="2">North</td>
                    <td class="text-center">:</td>
                    <td style="padding: 0px 0px;">
                        <table style="width: 100%" class="border-0">
                            <tr class="border-0">
                                <td class="border-0 text-center" style="width: 50%;border-right: 1px solid !important">{{ $basic_detail->document_north  ?? '-'}}</td>
                                <td class="border-0 text-center" style="width: 50%">{{ $basic_detail->actual_north  ?? '-'}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr >
                    <td colspan="2">South</td>
                    <td class="text-center">:</td>
                    <td style="padding: 0px 0px;">
                        <table style="width: 100%" class="border-0">
                            <tr class="border-0">
                                <td class="border-0 text-center" style="width: 50%;border-right: 1px solid !important">{{ $basic_detail->document_south  ?? '-'}}</td>
                                <td class="border-0 text-center" style="width: 50%">{{ $basic_detail->actual_south   ?? '-'}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr >
                    <td colspan="2">East</td>
                    <td class="text-center">:</td>
                    <td style="padding: 0px 0px;">
                        <table style="width: 100%" class="border-0">
                            <tr class="border-0">
                                <td class="border-0 text-center" style="width: 50%;border-right: 1px solid !important">{{ $basic_detail->document_east  ?? '-'}}</td>
                                <td class="border-0 text-center" style="width: 50%">{{ $basic_detail->actual_east  ?? '-'}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr >
                    <td colspan="2">West</td>
                    <td class="text-center">:</td>
                    <td style="padding: 0px 0px;">
                        <table style="width: 100%" class="border-0">
                            <tr class="border-0">
                                <td class="border-0 text-center" style="width: 50%;border-right: 1px solid !important">{{ $basic_detail->document_west  ?? '-'}}</td>
                                <td class="border-0 text-center" style="width: 50%">{{ $basic_detail->actual_west  ?? '-'}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td rowspan="5">13</td>
                    <td colspan="2">Dimensions of the site</td>
                    <td>&nbsp;</td>
                    <td colspan="2">As per Actual</td>
                </tr>
                 <tr>
                    <td colspan="2">North</td>
                    <td>&nbsp;</td>
                    <td colspan="2" rowspan="4" >
                        {{ $basic_detail->document_north  ?? '-'}}</br>
                        {{ $basic_detail->document_south  ?? '-'}}</br>
                        {{ $basic_detail->document_east  ?? '-'}}</br>
                        {{ $basic_detail->document_west  ?? '-'}}</br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">South</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">East</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">West</td>
                    <td>&nbsp;</td>
                </tr>
                <tr >
                    <td>14</td>
                    <td colspan="2">Extent of the site</td>
                    <td class="text-center">:</td>
                    <td>As per Agreement for Sale Super Built up area : {{ $basic_detail->super_built_flat_docu_sqmt  ?? '-'}}Sqmt  <br> ({{ $basic_detail->super_built_flat_docu_sqyd  ?? '-'}} Sqft)</td>
                </tr>
                <tr >
                    <td>14.1</td>
                    <td colspan="2" >Latitude, Logitude & Co-ordinates of flat</td>
                    <td ></td>
                    <td>{{ $basic_detail->latitude  ?? '-'}}<br>{{ $basic_detail->longitude  ?? '-'}}  </td>
                </tr>
                <tr >
                    <td>15</td>
                    <td colspan="2">Extent of the site considered for valuation </td>
                    <td >&nbsp;</td>
                    <td>As per Agreement for Sale Super Built up area : {{ $basic_detail->super_built_flat_docu_sqmt  ?? '-'}}Sqmt  <br> ({{ $basic_detail->super_built_flat_docu_sqyd  ?? '-'}} Sqft)</td>
                </tr>
                <tr >
                    <td>16</td>
                    <td colspan="2">Whether occupied by the owner/tenant > If occupied by tenant,  since how long? Rent Received Per  Month</td>
                    <td class="text-center">&nbsp;:</td>
                    <td>{{ $basic_detail->occupied_by  ?? '-'}}</td>
                </tr>
            </table>
        </section>
        {{-- Apartment  Bullding --}}
        <section class="aprtBulding" style="margin-top: 10px;">
            <table style="width: 100%;">
                <tr>
                    <th width="2%"><strong>II</strong></th>
                    <th width="46%"><strong>APARTMENT BULDING</strong></th>
                    <th width="2%">&nbsp;</th>
                    <th width="50%">&nbsp;</th>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>Nature of the Apartment</td>
                    <td class="text-center">:</td>
                    <td>Residential</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Location</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->postal_address  ?? '-'}}</td>
                </tr>
                <tr>
                    <td rowspan="5"></td>
                    <td>T.S. No</td>
                    <td class="text-center">:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Block No/Survey No</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->survey_no  ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Ward No</td>
                    <td class="text-center">:</td>
                    <td>Sarkhej</td>
                </tr>
                <tr>
                    <td>Village/ Municipality/Corporation</td>
                    <td class="text-center">:</td>
                    <td>AUDA</td>
                </tr>
                <tr>
                    <td>Door No. Street or Road (Pin Code)</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->postal_address  ?? '-'}}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Description of the locality Residential / Commercial / Mixed</td>
                    <td class="text-center">:</td>
                    <td>Residential</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Year Of Construction</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->construvation_year  ?? '-'}}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Number of Floors</td>
                    <td class="text-center">:</td>
                    <td>12</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Type of Structure</td>
                    <td class="text-center">:</td>
                    <td>RCC Frame Structure</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Number of Dwelling units in the buillding</td>
                    <td class="text-center">:</td>
                    <td>Block J Total Unit : 48</td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Quanlity of Construction</td>
                    <td class="text-center">:</td>
                    <td>Good</td>
                </tr>
                <tr>
                    <td>9.</td>
                    <td>Apperarance of the Buliding</td>
                    <td class="text-center">:</td>
                    <td>Good</td>
                </tr>
                <tr>
                    <td>10.</td>
                    <td>Maintenance of the building</td>
                    <td class="text-center">:</td>
                    <td>Good</td>
                </tr>
                <tr>
                    <td>11.</td>
                    <td>Facilities Avaliable</td>
                    <td class="text-center">:</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="6">&nbsp;</td>
                    <td>Lift</td>
                    <td class="text-center">:</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td>Protected Water Supply</td>
                    <td class="text-center">:</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td>Underground Sewerage</td>
                    <td class="text-center">:</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td>Car Parking - Open / Covered</td>
                    <td class="text-center">:</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td>Is Compound wall existing</td>
                    <td class="text-center">:</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td>Is pavement laid around the buliding</td>
                    <td class="text-center">:</td>
                    <td>Yes</td>
                </tr>
            </table>
        </section>
        {{-- FLAT --}}
        <section class="flat" style="margin-top: 10px;">
            <table style="width: 100%;">
                <tr>
                    <th width="2%"><strong>III</strong></th>
                    <th width="46%"><strong>FLAT</strong></th>
                    <th width="2%">&nbsp;:</th>
                    <th width="50%">&nbsp;</th>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>The floor on which the flat is situated</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->floor_height ?? '-'}}th Floor</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Door No. of the flat</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->floor_height ?? '-'}}th Floor</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Specifications of the flat</td>
                    <td class="text-center">:</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="3">&nbsp;</td>
                    <td>Roof</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->type_of_construction ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Flooring</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->flooring ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Doors</td>
                    <td class="text-center">:</td>
                    <td>Wooden Frame with Wooden Glazed Shutter</td>
                </tr>
                <tr>
                    <td rowspan="6">&nbsp;</td>
                    <td>Windows</td>
                    <td class="text-center">:</td>
                    <td>Powder coated Aluminum glazed shutter</td>
                </tr>
                <tr>
                    <td>Glazed Dedo</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->glazed_tiles_dedo ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Electrification</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->electrification ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Plumbing & Sanitary Fittings</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->plumbing_sanitary ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Kitchen Platform</td>
                    <td class="text-center">:</td>
                    <td>{{$basic_detail->kitchen_platform ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Finishing</td>
                    <td class="text-center">:</td>
                    <td>Good</td>
                </tr>
                <tr>
                    <td rowspan="4">4</td>
                    <td>House Tax</td>
                    <td class="text-center">:</td>
                    <td>N.A</td>
                </tr>
                <tr>
                    <td>Assessment No.</td>
                    <td class="text-center">:</td>
                    <td>Not avaliable</td>
                </tr>
                <tr>
                    <td>Tax paid in the name of</td>
                    <td class="text-center">:</td>
                    <td>Not avaliable</td>
                </tr>
                <tr>
                    <td>Tax Amount</td>
                    <td class="text-center">:</td>
                    <td>N.A</td>
                </tr>
                <tr>
                    <td rowspan="2">5</td>
                    <td>Electricity Service Connection no</td>
                    <td class="text-center">:</td>
                    <td >Not Avaliable</td>
                </tr>
                <tr>
                    <td>Meter Card is in the name of</td>
                    <td class="text-center">:</td>
                    <td >Not Avaliable</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>How is the maintenance of the flat?</td>
                    <td class="text-center">:</td>
                    <td>Good</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Sale Deed executed in the name of</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->owner_name  ?? '-'}}</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>What is the undivided area of land as per Agreement for sale</td>
                    <td class="text-center">:</td>
                    <td>Not Available</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>What is the plinth area of the flat?</td>
                    <td class="text-center">:</td>
                    <td>As per agreement for sale super Bulit up area : 175 Sqmt (1,880 Sqft)</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>What is the floor space index (app.)</td>
                    <td class="text-center">:</td>
                    <td>Total FSI of Type I ** 6894.32 Sqmt (bulit up area)</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>What is the Built Up of the flat?</td>
                    <td class="text-center">:</td>
                    <td>As per Agreement for sale Super Bulit Up Area 175 Sqmt (1,880 Sqft)</td>
                </tr>
            </table>
        </section>
        {{-- Carpet Area --}}
        <section class="carpet_area">
            <div class="inside_area" style="padding: 15px 50px 0px 50px;border: 1px solid;border-top: 0px;">
                <table width="100%" class="border-0">
                    <tr class="border-0">
                        <td colspan="4" width="50%" class="text-center">
                            <strong>Carpet Area as per Site (in Sqft)</strong>
                        </td>
                        <td colspan="4" width="50%" class="text-center">
                            <strong>Carpet Area as per Plan (in Sqmt)</strong>
                        </td>
                    </tr>
                    @if($measurement_site)
                        @foreach($measurement_site as $key => $value)
                            <tr>
                                <td>{{ $value->name  ?? '-'}}</td>
                                <td>{{ $value->width  ?? '-'}}</td>
                                <td><i class="fa fa-times cross_sign" aria-hidden="true"></i></td>
                                <td>{{ $value->height  ?? '-'}}</td>
                                <td>{{ $measurement_plan[$key]->name  ?? '-'}}</td>
                                <td>{{ $measurement_plan[$key]->width  ?? '-'}}</td>
                                <td><i class="fa fa-times cross_sign" aria-hidden="true"></i></td>
                                <td>{{ $measurement_plan[$key]->height  ?? '-'}}</td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="4" class="text-center">Total &nbsp;{{ $basic_detail->measurement_site_sqft  ?? '-'}} sqft</td>
                                <td colspan="4" class="text-center">Total &nbsp;{{ $basic_detail->measurement_site_sqyd  ?? '-'}} sqyd</td>
                            </tr>
                    @endif  
                </table>
                <p>The Super built up to Bulit UP ratio = 100:61<br>
                The Super built up to Bulit UP ratio as per govt guideling= 100:75</p> 
            </div>
        </section>
        <section>
            <table width="100%" class="border-0">
                <tr class="border-0">
                    <td class="border-top-0">12</td>
                    <td class="border-top-0">Is it Posh/ I class/medium/Ordinary ?</td>
                    <td class="text-center border-top-0">:</td>
                    <td class="border-top-0" >Medium</td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>Is it being used for Residential or Commercial purpose</td>
                    <td class="text-center">:</td>
                    <td>Residential Only</td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>Is it Owner-occupied or let out ?</td>
                    <td class="text-center">:</td>
                    <td>Vacant</td>
                </tr>
                <tr>
                    <td>15</td>
                    <td>If rented, What is the monthly rent?</td>
                    <td class="text-center">:</td>
                    <td>N.A</td>
                </tr>
                <tr>
                    <td>IV</td>
                    <td><strong>MARKET ABILITY</strong></td>
                    <td class="text-center">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>How is the marketability?</td>
                    <td class="text-center">:</td>
                    <td>Good</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>What are the factors favoring for an extra potential Value?</td>
                    <td class="text-center">:</td>
                    <td>Developing area</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Any negative factors area observed which affect the market value in general?</td>
                    <td class="text-center">:</td>
                    <td>No apparent negative factors are observed</td>
                </tr>
                <tr>
                    <td><strong>V</strong></td>
                    <td><strong>Rate</strong></td>
                    <td class="text-center">:</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>After analyzing</td>
                    <td class="text-center">:</td>
                    <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Assuming it is</td>
                    <td class="text-center">:</td>
                    <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</td>
                </tr>
                <tr>
                    <td rowspan="3">3</td>
                    <td>Break - up for the rate</td>
                    <td class="text-center">:</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%" class="border-0">
                            <tr class="border-0">
                                <td class="border-0" width="50%" style="border-right: 1px solid !important">i)</td>
                                <td class="border-0" width="50%">Building + Services</td>
                            </tr>
                        </table>
                    </td>
                    <td class="text-center">:</td>
                    <td>N.A</td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%" class="border-0">
                            <tr class="border-0">
                                <td class="border-0" width="50%" style="border-right: 1px solid !important">ii)</td>
                                <td class="border-0" width="50%">Land + Others</td>
                            </tr>
                        </table>
                    </td>
                    <td class="text-center">:</td>
                    <td>N.A</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Guideline rate obtained from the Registrar's office (en evidence thereof to be enclosed</td>
                    <td class="text-center">:</td>
                    <td>janti rate </td>
                </tr>
                <tr>
                    <td><strong>VI</strong></td>
                    <td colspan="3"><strong>COMPOSITE RATE ADOPTED AFTER DEPRECIATION<strong></td>
                </tr>
                <tr>
                    <td>a.</td>
                    <td>Depreciated building rate</td>
                    <td class="text-center">:</td>
                    <td>N.A Composite comparison rate method</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Replacement cost of flat with services {V (3) i}</td>
                    <td class="text-center">:</td>
                    <td>N.A Composite comparison rate method</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Age of the building</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->age_of_building  ?? '-'}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Life of the building estimated</td>
                    <td class="text-center">:</td>
                    <td>{{ $basic_detail->residual_life  ?? '-'}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Depreciation percentage assuming the salvage value as 10%</td>
                    <td class="text-center">:</td>
                    <td>N.A Composite comparison rate method</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Depreciated Ratio of the building</td>
                    <td class="text-center">:</td>
                    <td>N.A Composite comparison rate method</td>
                </tr>
                <tr>
                    <td>b.</td>
                    <td>Total composite rate arrived for valuation</td>
                    <td class="text-center">:</td>
                    <td>RS. 3500/- per sqyd of Super Built Up area</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Depreciated building rate VI(a)</td>
                    <td class="text-center">:</td>
                    <td>N.A Composite comparison rate method</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Rate for Land & other V (3) ii</td>
                    <td class="text-center">:</td>
                    <td>N.A</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Total Composite Rate</td>
                    <td class="text-center">:</td>
                    <td>Rs 3500/- per sqyd of suoer Bulit Up area</td>
                </tr>
            </table>
        </section>
        <section style="padding: 10px 0px;">
            <strong>Details of Valuation</strong>
            <table>
                <tr>
                    <th width="2%">Sr. no.</th>
                    <th width="53%">Description</th>
                    <th width="15%">Super Built Area</th>
                    <th width="15%">Rate Per unit Rs.</th>
                    <th width="15%">Estimated Value Rs.</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Present Value of the flat (incl car parking ,if proviede)</td>
                    <td>1,880 Sqft</td>
                    <td>3500/- per Sqft</td>
                    <td>Rs. 65,80,000.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Wardrobes</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Showcases</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Kitchen Arrangements</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Superfine Finish</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Interior Docorations</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Electricity deposits / electrial fitting etc</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Extra collapsible gates / grill works etc..</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Potential value, if any</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>Others</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                    <td>N.A.</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><strong>Total</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Rs. 65,80,000.00</td>
                </tr>
            </table>
        </section> 
        <section>
            <div class="flat-image">
                @if($image_detail)
                    <div  style="width:100%;float: left;">
                        @foreach($image_detail as $value3)
                            <div class="flat_images">
                                <img src="{{ public_path('image/'.$value3->image)}}" style="width: 200px; height: 200px;float:left">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>                            
        </section>
        <section>
            <div class="flat-image">
                @if(!empty($basic_detail->map))
                    <div  style="width:100%;float: left;">
                      
                            <div class="flat_images">
                                <img src="{{ url('/public/image/'.$basic_detail->map)}}" style="    width: 100%;float: left;height: 100%;padding: 0px 10px 10px 0px;">
                            </div>
                        
                    </div>
                @endif
            </div>                            
        </section>
    </div>      
    </body>
    <!-- jQuery 3 -->
    <script src="{{ asset('public/admin/js/jquery.min.js')}}"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('public/admin/js/bootstrap.min.js')}}"></script>
    </html>