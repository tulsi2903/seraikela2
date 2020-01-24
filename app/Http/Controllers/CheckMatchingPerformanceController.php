<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemePerformance;
use App\SchemeStructure;
use App\CheckMatchingPerformance;
Use Auth;

class CheckMatchingPerformanceController extends Controller
{
   public function insert_mathcingperformance($id="",$result="")
   {
        // echo $id;
        // exit;
        // $CheckMatchingPerformance="";
        if($result=="true")
        {
            $SchemePerformance_deatails=SchemePerformance::where('scheme_performance_id',$id)->first();
            $CheckMatchingPerformance=new CheckMatchingPerformance();
            $CheckMatchingPerformance->scheme_performance_id=$id;
            $CheckMatchingPerformance->performance_matching_value=566;
            $CheckMatchingPerformance->scheme_performance_status =$SchemePerformance_deatails->status;
            $CheckMatchingPerformance->created_by=Auth::user()->id;
            $CheckMatchingPerformance->updated_by=Auth::user()->id;
            $CheckMatchingPerformance->save();
            // return $CheckMatchingPerformance;
        }
        return ["CheckMatchingPerformance"=>$CheckMatchingPerformance];
        // echo $result;

   }
}
