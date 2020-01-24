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
          
        }
        return ["CheckMatchingPerformance"=>$CheckMatchingPerformance];
       

   }

   public function index()
   {
        // $datas = CheckMatchingPerformance::leftJoin('scheme_performance','scheme_performance.scheme_performance_id','=','chck_matching_performance.scheme_performance_id')
        // ->leftJoin('year')
        //             ->select('chck_matching_performance.*', 'scheme_performance.scheme_performance_id')
        //             ->get();
    $datas= CheckMatchingPerformance::get();
                    // foreach($datas as $data)
                    // {
                    //     // block data
                    //     $tmp = SchemePerformance::select('geo_name')->where('geo_id','=',$data->block_id)->first();
                    //     $data->block_name = $tmp->geo_name;
            
                    //     //panchayat data
                    //     $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->panchayat_id)->first();
                    //     $data->panchayat_name = $tmp->geo_name;
            
                    //     //subdivision data
                    //     $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->subdivision_id)->first();
                    //     $data->subdivision_name = $tmp->geo_name;
                    // }

        return view('matching-schemes.index')->with('datas', $datas);
   }
}
