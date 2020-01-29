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
        
     $datas = CheckMatchingPerformance::leftJoin('scheme_performance','chck_matching_performance.scheme_performance_id','=','scheme_performance.scheme_performance_id')
               ->leftJoin('year','scheme_performance.year_id','=','year.year_id')
               ->leftJoin('scheme_assets','scheme_performance.scheme_asset_id','=','scheme_assets.scheme_asset_id')
               ->leftJoin('scheme_structure','scheme_performance.scheme_id','=','scheme_structure.scheme_id')
               ->leftJoin('geo_structure','scheme_performance.block_id','=','geo_structure.geo_id')
               ->select('chck_matching_performance.*', 'scheme_performance.scheme_performance_id','scheme_performance.attribute','scheme_performance.panchayat_id as panchayat_id','year.year_value','scheme_assets.scheme_asset_name','scheme_structure.scheme_name','scheme_structure.scheme_short_name','geo_structure.geo_name')
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
          $datas = CheckMatchingPerformance::where('id',$id)->first();
         
          $tmp_matching=count(explode(",",$datas->matching_performance_id));
          $tmp_matching_array=explode(",",$datas->matching_performance_id);
          $created_at=$datas->created_at;
          $updated_at=$datas->updated_at;
          $scheme_performance_id_to_append = $datas->scheme_performance_id;
          // $get_scheme_performance_id=$datas->scheme_performance_id;

          $CheckMatchingPerformance_id=$datas->id;
          $datas=SchemePerformance::leftJoin('year','scheme_performance.year_id','=','year.year_id')
                    ->leftJoin('scheme_assets','scheme_performance.scheme_asset_id','=','scheme_assets.scheme_asset_id')
                    ->leftJoin('scheme_structure','scheme_performance.scheme_id','=','scheme_structure.scheme_id')
                    ->leftJoin('geo_structure','scheme_performance.block_id','=','geo_structure.geo_id')
                    ->select('scheme_performance.scheme_performance_id','scheme_performance.attribute','geo_structure.geo_name as block_name ','scheme_performance.panchayat_id as panchayat_id','year.year_value','scheme_assets.scheme_asset_name','scheme_structure.scheme_name','scheme_structure.scheme_short_name','geo_structure.geo_name')
                    ->whereIn('scheme_performance_id',$tmp_matching_array)
                    ->get();

          foreach($datas as $data)
          {
               
                //panchayat data
                $data->created_at=$created_at;
                $data->updated_at=$updated_at;
               //  $data->scheme_performance_id = $get_scheme_performance_id;

               $tmp = GeoStructure::select('geo_name')->where('geo_id', $data['panchayat_id'])->first();
               $data->panchayat_name = $tmp->geo_name;
               $attribute[0]=unserialize($data['attribute']);
               $print_att="";
               foreach($attribute[0][0] as $key_at=>$value_att)
               {
                   $print_att=$value_att;
               }
               $data->attribute = $print_att;

          //  print_r( $print_att);
          }

          // return $datas;

          return ['Matching'=>$datas,'tmp_matching'=>$tmp_matching, 'id'=>$id, 'scheme_performance_id_to_append'=>$scheme_performance_id_to_append];
   }



//    public function delete(Request $request){
//      //    return $request;
//           $tmp_revert = $request->hidden_input_for_revert;
//           $tmp_revert = ltrim($tmp_revert,","); 

//           $CheckMatchingPerformance_id=$request->matching_id;
//           $tmp_inprogress = $request->hidden_input_for_inprogress;
//           $tmp_inprogress = ltrim($tmp_inprogress,",");

//           $tmp_inprogress_array=explode(",",$tmp_inprogress);
//           $sort_array=rsort($request->scheme_performance_id);
          
//           if(in_array($tmp_revert,$request->scheme_performance_id))
//           {
//                CheckMatchingPerformance::where('id',$CheckMatchingPerformance_id)->update(array('status'=>0));
//                // $scheme_performance = SchemePerformance::where('scheme_performance_id',$CheckMatchingPerformance_deatils->scheme_performance_id)->update(array('status'=>3));

//           }
        
//           if($tmp_inprogress_array==$request->scheme_performance_id)
//           {
//                $CheckMatchingPerformance_deatils=CheckMatchingPerformance::where('id',$CheckMatchingPerformance_id)->first();
//                $scheme_performance = SchemePerformance::where('scheme_performance_id',$CheckMatchingPerformance_deatils->scheme_performance_id)->update(array('status'=>0));
//                // echo $CheckMatchingPerformance_deatils->scheme_performance_id;
//                CheckMatchingPerformance::where('id',$CheckMatchingPerformance_id)->update(array('status'=>1));

//           }


//      return redirect('matching-schemes');
//  }

 public function get_data(Request $request)
 {
     //  return $request;
     $check_matching_performance = CheckMatchingPerformance::where('id',$request->matching_id)->first();
     // $scheme_performance = SchemePerformance::select('scheme_performance_id','status')->get();
     // return $check_matching_performance ;
     $scheme_performance = SchemePerformance::where('scheme_performance_id',$check_matching_performance->scheme_performance_id)->select('scheme_performance_id','status')->first();

    //getting data from front end
     $check_matching_performance->duplicate = $request->hidden_input_for_revert ?? "";
     $check_matching_performance->not_duplicate = $request->hidden_input_for_inprogress ?? "";
     $check_matching_performance->probable_duplicate="";
     if($request->hidden_input_for_revert !="")
     {
          $check_matching_performance->status = 0;
     }
     else{
          $check_matching_performance->status = 1;
     }

     $CheckMatchingPerformance_id=$request->get_scheme_performance_id;

     if($check_matching_performance->status == 1 && $check_matching_performance->scheme_performance_id!="")
     {
          
          $get_scheme_performance_id = $request->get_scheme_performance_id;//getting data from front end
          SchemePerformance::where('scheme_performance_id',$CheckMatchingPerformance_id)->update(array('status'=>0));


     }
     // return $request;

          $check_matching_performance->save();
              return $check_matching_performance;
   
    
    
     return redirect('matching-schemes');


 }

}