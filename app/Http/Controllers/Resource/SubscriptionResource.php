<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProviderSubscription;
use App\Helpers\Helper;
use Auth;
use Setting;
use DB;

class SubscriptionResource extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('demo', ['only' => ['destroy']]);
        $this->perpage = Setting::get('per_page', '10');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {  
            
            if(!empty($request->page) && $request->page=='all'){
                
                $providers = ProviderSubscription::orderBy('id', 'asc')->get();  

                return response()->json(array('success' => true, 'data'=>$providers));
            }  
            else
            {
                //$requests = UserRequests::RequestHistory()->paginate($this->perpage);
                $requests = ProviderSubscription::with(['providerInfo'])->orderBy('id', 'asc')->paginate($this->perpage);
               
            
                if($request->all())
                {
                    if(isset($request->search) && $request->search)
                    {   
                        $search = $request->search;
                        // $paid_status = ((strtolower($request->search) == 'paid') ? 1 : (strtolower($request->search) == 'not paid') ? 0 : '');
                        // if(strtolower($request->search) == 'paid') {
                        //     $paid_status = 1;
                        // } elseif(strtolower($request->search) == 'not paid') {
                        //     $paid_status = 0;
                        // } else {
                        //     $paid_status = '';
                        // }
                        // $requests = UserRequests::RequestHistory()
                        // ->where(function($query) use ($request, $paid_status) {
                        //     $query->where('booking_id', 'like', '%' . $request->search . '%');
                        //     $query->orwhere(function($query_1) use ($request, $paid_status) {
                        //         $query_1->where('status', 'like', '%' . $request->search . '%');
                        //         $query_1->orwhere(function($query_2) use ($request, $paid_status) {
                        //             $query_2->where('payment_mode', 'like', '%' . $request->search . '%');
                        //             $query_2->orwhere(function($query_3) use ($request, $paid_status) {
                        //                 $query_3->where(function($query_4) use ($request) {
                        //                     $query_4->whereHas('user', function($query) use($request) {
                        //                         $query->where(DB::raw('CONCAT(`first_name`, " ", `last_name`)'), 'like', '%' . $request->search . '%');
                        //                     });
                        //                 });
                        //                 $query_3->orwhere(function($query_4) use ($request, $paid_status) {
                        //                     $query_4->where(function($query_5) use ($request) {
                        //                         $query_5->whereHas('provider', function($query_6) use($request) {
                        //                             $query_6->where(DB::raw('CONCAT(`first_name`, " ", `last_name`)'), 'like', '%' . $request->search . '%');
                        //                         });
                        //                     });
                        //                     $query_4->orwhere(function($query_5) use ($request, $paid_status) {
                        //                         $query_5->where(function($query_6) use ($request) {
                        //                             $query_6->whereHas('payment', function($query_7) use($request) {
                        //                                 $query_7->where('total', 'like', '%' . $request->search . '%'); 
                        //                             });
                        //                         });
                        //                         $query_5->orwhere('paid', $paid_status);
                        //                     });
                        //                 });
                        //             });
                        //         });
                        //     });
                        // })
                        // ->paginate($this->perpage); 
                    }
                }
                
                $pagination=(new Helper)->formatPagination($requests);
                return view('admin.subscription.index', compact('requests','pagination'));   
            }
            
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    public function Fleetindex()
    {
        try {
            $requests = UserRequests::RequestHistory()
                        ->whereHas('provider', function($query) {
                            $query->where('fleet', Auth::user()->id );
                        })->get();
            return view('fleet.request.index', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduled()
    {
        try{
            $requests = UserRequests::where('status' , 'SCHEDULED')
                        ->RequestHistory()
                        ->get();

            return view('admin.request.scheduled', compact('requests'));
        } catch (Exception $e) {
             return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Fleetscheduled()
    {
        try{
            $requests = UserRequests::where('status' , 'SCHEDULED')
                         ->whereHas('provider', function($query) {
                            $query->where('fleet', Auth::user()->id );
                        })
                        ->get();

            return view('fleet.request.scheduled', compact('requests'));
        } catch (Exception $e) {
             return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $request = UserRequests::findOrFail($id);
            return view('admin.request.show', compact('request'));
        } catch (Exception $e) {
             return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    public function Fleetshow($id)
    {
        try {
            $request = UserRequests::findOrFail($id);
            return view('fleet.request.show', compact('request'));
        } catch (Exception $e) {
             return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    public function Accountshow($id)
    {
        try {
            $request = UserRequests::findOrFail($id);
            return view('account.request.show', compact('request'));
        } catch (Exception $e) {
             return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $Request = UserRequests::findOrFail($id);
            $Request->delete();
            return back()->with('flash_success', trans('admin.request_delete'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }

    public function Fleetdestroy($id)
    {
        try {
            $Request = UserRequests::findOrFail($id);
            $Request->delete();
            return back()->with('flash_success', trans('admin.request_delete'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.something_wrong'));
        }
    }
}
