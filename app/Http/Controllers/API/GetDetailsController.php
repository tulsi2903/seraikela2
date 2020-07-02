<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeStructure;
use App\Year;
use App\GeoStructure;
use App\Asset;
use App\SchemeAsset;
use Illuminate\Support\Facades\Auth;

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
        $user_designation = Auth::user()->userRole;
        $geo_ids = [];

        if($request->block_id){
            $datas = GeoStructure::where('geo_id', $request->block_id)->select('geo_id','geo_name')->first();
        }
        else if($request->subdivision_id){
            $datas = GeoStructure::where('sd_id', $request->subdivision_id)->where('level_id', 3)->select('geo_id','geo_name')->get();
        }
        else{
            // getting block ids for that designation/ assigned
            if ($user_designation == 1) // dc
            {
                $geo_ids = GeoStructure::where('level_id', 3)->pluck('geo_id'); // all block_ids
            } 
            else if ($user_designation  == 2) { // sdo
                $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->where('level_id', '2')->first();
                if ($subdivision_id_tmp) { // if user is assigned to some subdivision
                    $geo_ids = GeoStructure::where('sd_id', $subdivision_id_tmp->geo_id)->pluck('geo_id'); // block_ids, panchayat_ids
                }
            } 
            else if ($user_designation == 3) { // bdo
                $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
                if ($block_id_tmp) { // if user is assigned to some block
                    $geo_ids = GeoStructure::where('officer_id', Auth::user()->id)->pluck('geo_id'); // block_ids, panchayat_ids
                }
                
                // return ["geo_ids"=>$block_id_tmp];
            } 
            else if ($user_designation == 4) { //po
                $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
                if ($panchayat_id_tmp) { // if user is assigned to some panchayats
                    $geo_ids = GeoStructure::where('geo_id', $panchayat_id_tmp->bl_id)->pluck('geo_id'); // block_ids, panchayat_ids
                }
            }
            $datas = GeoStructure::select('geo_id','geo_name')->where('level_id', 3)->whereIn('geo_id', $geo_ids)->get();
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
