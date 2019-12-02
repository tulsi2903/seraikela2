<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchemeReviewController extends Controller
{
    //
    public function index(){
        $panchayat_datas = GeoStructure::select('geo_id','geo_name')->where('level_id','4')->get();
        $year_datas = Year::select('year_id','year_value')->get();
        return view('scheme-review.index')->with(compact('panchayat_datas','year_datas'));
     }
}
