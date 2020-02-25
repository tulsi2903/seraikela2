<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeStructure;
use App\SchemePerformance;
use App\Year;
use App\GeoStructure;
use App\Asset;
use App\SchemeAsset;

class SchemePerformanceController extends Controller
{
    //
    public $successStatus = 200;

    // get scheme details
    public function get_schemes(Request $request){
        if($request->scheme_id){
            $datas = SchemeStructure::leftJoin('scheme_assets','scheme_assets.scheme_asset_id', '=', 'scheme_structure.scheme_asset_id')
                                    ->where('scheme_id', $request->scheme_id)
                                    ->select('scheme_structure.scheme_id','scheme_structure.scheme_short_name','scheme_structure.scheme_name','scheme_structure.scheme_is','scheme_structure.attributes','scheme_structure.scheme_asset_id','scheme_assets.scheme_asset_name')
                                    ->first();
            if($datas){
                $datas->attributes = unserialize($datas->attributes);
            }
        }
        else{
            $datas = SchemeStructure::leftJoin('scheme_assets','scheme_assets.scheme_asset_id', '=', 'scheme_structure.scheme_asset_id')
                                    ->where('status', 1)
                                    ->select('scheme_structure.scheme_id','scheme_structure.scheme_short_name','scheme_structure.scheme_name','scheme_structure.scheme_is','scheme_structure.attributes','scheme_structure.scheme_asset_id','scheme_assets.scheme_asset_name')
                                    ->get();
            if($datas){
                foreach($datas as $data){
                    $data->attributes = unserialize($data->attributes);
                }
            }
        }

        // return after validate
        if(count($datas)>0){
            return response()->json(['success' => $datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }

    // for scheme
    public function get_scheme_asset(Request $request){
        if($request->scheme_asset_id){
            $datas = SchemeAsset::where('scheme_asset_id', $request->scheme_asset_id)->select('scheme_asset_id','scheme_asset_name')->first();
        }
        else{
            $datas = SchemeAsset::select('scheme_asset_id','scheme_asset_name')->get();
        }

        // // return after validate
        if(count($datas)>0){
            return response()->json(['success' => $datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }

    //
    public function get_scheme_performabnce_datas(Request $request){
        $year_id = $request->year_id;
        $scheme_id = $request->scheme_id;
        $panchayat_id = $request->panchayat_id;

        $scheme_performance_datas = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->get();

        foreach($scheme_performance_datas as $scheme_performance_data){
            $scheme_performance_data->attribute = unserialize($scheme_performance_data->attribute);
            $scheme_performance_data->coordinates = unserialize($scheme_performance_data->coordinates);
            $scheme_performance_data->gallery = unserialize($scheme_performance_data->gallery);
            $scheme_performance_data->borders_connectivity = unserialize($scheme_performance_data->borders_connectivity);
        }


        // return after validate
        if(count($scheme_performance_datas)>0){
            return response()->json(['success' => $scheme_performance_datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }


    public function store_scheme_performance_datas(Request $request){
        // getting datas
        /*
            year_id, block_id, panchayat_id, scheme_id, scheme_asset_id, attributes, coordinates, images 
        */
        $year_id = $request->year_id;
        $scheme_id = $request->scheme_id;
        $block_id = $request->block_id;
        $panchayat_id = $request->panchayat_id;
        $subdivision_id = GeoStructure::where('geo_id', $panchayat_id)->first()->sd_id;

        // for attributes
        $scheme_data = SchemeStructure::where('scheme_id', $scheme_id)->first();
        $scheme_attributes = unserialize($scheme_data->attributes);
        $attribute = []; // to store
        foreach($scheme_attributes as $scheme_attribute){
            // $scheme_attribute["id"];
            if($request->has($scheme_attribute["id"])){
                $attribute[] = [$scheme_attribute["id"]=>$request->input($scheme_attribute["id"])];
            }
        }
        $attribute = serialize($attribute);

        // for coordinates
        $coordinates = [];
        for($i=0;$i<count($request->latitude);$i++){
            $coordinates[] = ["latitude"=>$request->latitude[$i],"longitude"=>$request->longitude[$i]];
        }
        $coordinates = serialize($coordinates);

        // for gallery
        $gallery = [];
        if($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $images_tmp_name = "scheme_performance-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                $file->move("public/uploaded_documents/scheme_performance/", $images_tmp_name);   // move the file to desired folder
                $gallery[] =  $upload_directory . $images_tmp_name;    // array push
            }
            $gallery = serialize($gallery);    // serializing datas
        }

        $scheme_asset_id = $request->scheme_asset_id;
        $status = $request->status;
        $comments = $request->comments;
        $connectivity_status = $request->connectivity_status;
        
        
        // storing datas
        $scheme_performance = new SchemePerformance;
        $scheme_performance->year_id = $year_id;
        $scheme_performance->scheme_id = $scheme_id;
        $scheme_performance->block_id = $block_id;
        $scheme_performance->panchayat_id = $panchayat_id;
        $scheme_performance->subdivision_id = $subdivision_id;

        $scheme_performance->attribute = $attribute;
        $scheme_performanve->coordinates = $coordinates;
        $scheme_performance->gallery = $gallery;

        $scheme_performance->scheme_asset_id = $scheme_asset_id;
        $scheme_performance->status = $status;
        $scheme_performance->comments = $comments;
        $scheme_performance->connectivity_status = $connectivity_status ?? 0;

        if($scheme_performance->save()){
            return response()->json(['success'=>'data_saved'], 201);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }
}
