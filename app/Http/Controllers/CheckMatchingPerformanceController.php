<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemePerformance;
use App\SchemeStructure;
use App\CheckMatchingPerformance;
Use Auth;
use App\GeoStructure;

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
        
     $datas = CheckMatchingPerformance::leftJoin('scheme_performance','scheme_performance.scheme_performance_id','=','chck_matching_performance.scheme_performance_id')
               ->leftJoin('year','year.year_id','=','scheme_performance.year_id')
               ->leftJoin('scheme_assets','scheme_assets.scheme_asset_id','=','scheme_performance.scheme_asset_id')
               ->leftJoin('scheme_structure','scheme_structure.scheme_id','=','scheme_performance.scheme_id')
               ->leftJoin('geo_structure','geo_structure.geo_id','=','scheme_performance.block_id')
               ->select('chck_matching_performance.*', 'scheme_performance.scheme_performance_id','scheme_performance.attribute','scheme_performance.panchayat_id as panchayat_id','year.year_value','scheme_assets.scheme_asset_name','scheme_structure.scheme_name','geo_structure.geo_name')
               ->orderBy('chck_matching_performance.id','desc')
               ->get();

 
             
     foreach($datas as $data)
     {
          
          //panchayat data
          $tmp = GeoStructure::select('geo_name')->where('geo_id',$data['panchayat_id'])->first();
          $data->panchayat_name = $tmp->geo_name;

     
     }
        
        

          return view('matching-schemes.index')->with('datas', $datas);
   }

   public function get_matching_entries($id="")
   {
          $datas = CheckMatchingPerformance::leftJoin('scheme_performance','scheme_performance.scheme_performance_id','=','chck_matching_performance.scheme_performance_id')
          ->leftJoin('year','year.year_id','=','scheme_performance.year_id')
          ->leftJoin('scheme_assets','scheme_assets.scheme_asset_id','=','scheme_performance.scheme_asset_id')
          ->leftJoin('scheme_structure','scheme_structure.scheme_id','=','scheme_performance.scheme_id')
          ->leftJoin('geo_structure','geo_structure.geo_id','=','scheme_performance.block_id')
          ->select('chck_matching_performance.*', 'scheme_performance.scheme_performance_id','chck_matching_performance.matching_performance_id as matching_performance_id','scheme_performance.attribute as attribute','scheme_performance.panchayat_id as panchayat_id','year.year_value','scheme_assets.scheme_asset_name','scheme_structure.scheme_name','geo_structure.geo_name')
          ->orderBy('chck_matching_performance.id','desc')
          ->where('id',$id)
          ->first();
         
          $tmp_matching=count(explode(",",$datas->matching_performance_id));
          $tmp_matching_array=explode(",",$datas->matching_performance_id);
          $datas=SchemePerformance::leftJoin('year','year.year_id','=','scheme_performance.year_id')
                    ->leftJoin('scheme_assets','scheme_assets.scheme_asset_id','=','scheme_performance.scheme_asset_id')
                    ->leftJoin('scheme_structure','scheme_structure.scheme_id','=','scheme_performance.scheme_id')
                    ->leftJoin('geo_structure','geo_structure.geo_id','=','scheme_performance.block_id')
                    
                    ->whereIn('scheme_performance_id',$tmp_matching_array)
                    ->get();

                    

          foreach($datas as $data)
          {
               
               //panchayat data
               $tmp = GeoStructure::select('geo_name')->where('geo_id',$data['panchayat_id'])->first();
               $data->panchayat_name = $tmp->geo_name;
     
          
          }

         
       

          return ['Matching'=>$datas,'tmp_matching'=>$tmp_matching];
   }



   public function delete(Request $request){
        
          // return $request;

          $tmp_revert = $request->hidden_input_for_revert;
          $tmp_revert = ltrim($tmp_revert,",");
          print_r (explode(" ",$tmp_revert));

          $tmp_inprogress = $request->hidden_input_for_inprogress;
          $tmp_inprogress = ltrim($tmp_inprogress,",");
          print_r (explode(" ",$tmp_inprogress));

     for($i=0;$i<count($tmp_revert);$i++)
     {
         CheckMatchingPerformance::where('scheme_performance_id',$tmp_revert[$i])->delete();
     //     return $tmp_revert[$i];
     }

     for($i;$i<count($tmp_inprogress);$i++)
     {
          $scheme_performance = SchemePerformance::where('scheme_performance_id', $tmp_inprogress[$i])->first();
          if($scheme_performance)
          {
               $scheme_performance->status =  0;
          }
          $scheme_performance->save();
          
     }

    

     return redirect('matching-schemes');
 }

}
