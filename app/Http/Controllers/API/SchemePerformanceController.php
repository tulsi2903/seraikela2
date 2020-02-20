<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeStructure;
use App\SchemePerformance;
use App\Year;
use App\GeoStructure;
use App\Asset;

class SchemePerformanceController extends Controller
{
    //
    public $successStatus = 200;

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
        }


        // return after validate
        if(count($scheme_performance_datas)>0){
            return response()->json(['success' => $scheme_performance_datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }


    public function store_datas(Request $request){
        // getting datas
        /*
            year_id, block_id, panchayat_id, scheme_id, scheme_asset_id, attributes, coordinates, images 
        */
        $year_id = $request->year_id;
        $scheme_id = $request->scheme_id;
        $block_id = $request->block_id;
        $panchayat_id = $request->panchayat_id;
        $subdivision_id = GeoStructure::where('geo_id', $panchayat_id)->first()->sd_id;

        

    }
}
