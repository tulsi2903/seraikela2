<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeStructure;
use App\Year;
use App\GeoStructure;
use App\Asset;

class SchemePerformanceController extends Controller
{
    //
    public function store_datas(Request $request){
        // getting datas
        /*
            year_id, block_id, panchayat_id, scheme_id, scheme_asset_id, attributes, coordinates, images 
        */
        $year_id = $request->year_id;
        $scheme_id = $request->scheme_id;
        $block_id = $request->block_id;
        $panchayat_id = $request->panchayat_id;
        

    }
}
