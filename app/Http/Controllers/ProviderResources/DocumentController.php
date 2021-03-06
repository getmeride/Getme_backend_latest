<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Document;
use App\ProviderDocument;
use App\Provider;
use Setting;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $VehicleDocuments = Document::where('type', 'VEHICLE')->get();
            $DriverDocuments = Document::where('type', 'DRIVER')->get();
            //$CashoutDocuments = Document::where('type', 'CASHOUT')->get();

            $Provider = \Auth::guard('provider')->user();

            return view('provider.document.index', compact('DriverDocuments', 'VehicleDocuments', 'Provider'));
        }catch(\Exception $e){
            
            //throw new \App\Exceptions\CustomException($e->getMessage());
        }
        // $VehicleDocuments = Document::vehicle()->get();
        // $DriverDocuments = Document::driver()->get();

       
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
        //
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
        
        $this->validate($request, [
                'document' => 'mimes:jpg,jpeg,png',
            ]);

        try {
            
            $Document = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)
                ->where('document_id', $id)->with('provider')->with('document')
                ->firstOrFail();

            Storage::delete($Document->url);

            $filename=str_replace(" ","",$Document->document->name);

            $ext = $request->file('document')->guessExtension();
            
            $path = $request->file('document')->storeAs(
                "provider/documents/".$Document->provider_id, $filename.'.'.$ext
            );

            $Document->update([
                    'url' => $path,
                    'status' => 'ASSESSING',
                ]);

           

        } catch (ModelNotFoundException $e) {

            $document = Document::find($id);
            $filename=str_replace(" ","",$document->name);
            $ext = $request->file('document')->guessExtension();
            $path = $request->file('document')->storeAs(
                "provider/documents/".\Auth::guard('provider')->user()->id, $filename.'.'.$ext
            );
            ProviderDocument::create([
                    'url' => $path,
                    'provider_id' => \Auth::guard('provider')->user()->id,
                    'document_id' => $id,
                    'status' => 'ASSESSING',
                ]);
            
        }



        //update document to card status
        //$total = Document::count();
        //$provider_total = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)->count();
        $VehicleDocuments = Document::where('type', 'VEHICLE')->get();
        $VehicleDocumentsArray=[];
        $VehicleDocumentsCount = 0;
        foreach ($VehicleDocuments as $key => $value) {
            $VehicleDocumentsArray[] = $value->id;
            $VehicleDocumentsCount += 1;
        }

        $DriverDocuments = Document::where('type', 'DRIVER')->get();
        $DriverDocumentsArray=[];
        $DriverDocumentsCount = 0;
        foreach ($DriverDocuments as $key => $value) {
            $DriverDocumentsArray[] = $value->id;
            $DriverDocumentsCount += 1;
        }

        // $CashoutDocuments = Document::where('type', 'CASHOUT')->get();
        // $CashoutDocumentsArray=[];
        // $CashoutDocumentsCount = 0;
        // foreach ($CashoutDocuments as $key => $value) {
        //     $CashoutDocumentsArray[] = $value->id;
        //     $CashoutDocumentsCount += 1;
        // }

        $provider_vehicle_total = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)->whereIn('document_id',$VehicleDocumentsArray)->count();
        
        $provider_driver_total = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)->whereIn('document_id',$DriverDocumentsArray)->count();
        
        // $provider_cashout_total = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)->whereIn('document_id',$CashoutDocumentsArray)->count();

        // echo $VehicleDocumentsCount.'--'.$provider_vehicle_total.'</br>';
        // echo $DriverDocumentsCount.'--'.$provider_driver_total.'</br>';
        // echo $CashoutDocumentsCount.'--'.$provider_cashout_total.'</br>';
        // exit();

        if($VehicleDocumentsCount==$provider_vehicle_total && $DriverDocumentsCount==$provider_driver_total ){
            if(Setting::get('CARD', 0) == 1) {
                Provider::where('id',\Auth::guard('provider')->user()->id)->where('status','document')->update(['status'=>'card']);
            }
            else{
                if(Setting::get('demo_mode', 0) == 1) {
                    Provider::where('id',\Auth::guard('provider')->user()->id)->where('status','document')->update(['status'=>'approved']);
                }
                else{
                    Provider::where('id',\Auth::guard('provider')->user()->id)->where('status','document')->update(['status'=>'onboarding']);
                }    
            }    
        }

        return back();
    }

    public function documentupdate($image, $id,$provider_id)
    {
       
        try {
           
            $Document = ProviderDocument::where('provider_id', $provider_id)
                ->where('document_id', $id)->with('provider')->with('document')
                ->firstOrFail();            

            Storage::delete($Document->url);

            $filename=str_replace(" ","",$Document->document->name);

            $ext = $image->guessExtension();
            
            $path = $image->storeAs(
                "provider/documents/".$Document->provider_id, $filename.'.'.$ext
            );

            $Document->update([
                    'url' => $path,
                    'status' => 'ASSESSING',
                ]);            

        } catch (ModelNotFoundException $e) {

            $document = Document::find($id);
            $filename=str_replace(" ","",$document->name);
            $ext = $image->guessExtension();
            $path = $image->storeAs(
                "provider/documents/".$provider_id, $filename.'.'.$ext
            );
            ProviderDocument::create([
                    'url' => $path,
                    'provider_id' => $provider_id,
                    'document_id' => $id,
                    'status' => 'ASSESSING',
                ]);
            
        }

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }   
}
