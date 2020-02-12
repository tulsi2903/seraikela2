<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeStructure;
use App\Year;
use App\GeoStructure;

class GetDetailsController extends Controller
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
