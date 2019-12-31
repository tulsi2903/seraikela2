<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MgnregaPerformance;
use App\GeoStructure;
use App\MgnregaCategory;
use App\Year;

class MgnregaController extends Controller
{
    //
    public function performance(){
        $block_datas = GeoStructure::select('geo_id','geo_name')->where('level_id','3')->get();
        $mgnrega_category_datas = MgnregaCategory::get();
        $year_datas = Year::where('status','1')->get();

        return view("scheme-performance/mgnrega/index")->with(compact('block_datas','mgnrega_category_datas','year_datas'));
    }

    public function get_panchayat_datas(Request $request){
        $block_id = $request->block_id;

        $panchayat_datas = GeoStructure::select('geo_id', 'geo_name')->where('bl_id', $block_id)->get();
        return $panchayat_datas;
    }
}
