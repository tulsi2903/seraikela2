<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeStructure;
use App\Year;
use App\GeoStructure;
use App\Asset;
use App\SchemeAsset;

class GetDetailsController extends Controller
{
    //
    public $successStatus = 200;

    // get year details
    public function get_years(Request $request){
        if($request->year_id){
            $datas = Year::where('year_id', $request->year_id)->select('year_id','year_value')->first();
        }
        else{
            $datas = Year::where('status', 1)->select('year_id','year_value')->get();
        }

        // return after validate
        if(count($datas)>0){
            return response()->json(['success' => $datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }


    // get block details
    public function get_blocks(Request $request){
        if($request->block_id){
            $datas = GeoStructure::where('geo_id', $request->block_id)->select('geo_id','geo_name')->first();
        }
        else if($request->subdivision_id){
            $datas = GeoStructure::where('sd_id', $request->subdivision_id)->where('level_id', 3)->select('geo_id','geo_name')->get();
        }
        else{
            $datas = GeoStructure::where('level_id', 3)->select('geo_id','geo_name')->get();
        }

        // return after validate
        if(count($datas)>0){
            return response()->json(['success' => $datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }


    // get panchayat details
    public function get_panchayat(Request $request){
        if($request->panchayat_id){
            $datas = GeoStructure::where('geo_id', $request->panchayat_id)->select('geo_id','geo_name')->first();
        }
        else if($request->block_id){
            $datas = GeoStructure::where('bl_id', $request->block_id)->where('level_id', 4)->select('geo_id','geo_name')->get();
        }
        else if($request->subdivision_id){
            $datas = GeoStructure::where('sd_id', $request->subdivision_id)->where('level_id', 4)->select('geo_id','geo_name')->get();
        }
        else{
            $datas = GeoStructure::where('level_id', 4)->select('geo_id','geo_name')->get();
        }

        // return after validate
        if(count($datas)>0){
            return response()->json(['success' => $datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }
}
