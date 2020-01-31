<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemePerformance;
use App\SchemeStructure;
use App\CheckMatchingPerformance;
Use Auth;
use App\GeoStructure;
use App\Year;


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
          $append_comment = unserialize($datas->comment);


          $get_not_duplicate_array = explode(",",$datas->not_duplicate);
          $get_duplicate_array = explode(",",$datas->duplicate);
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

               // if performance id is in array, then $data->type="duplicate"  OR "not_duplicate" 
               if(in_array($data->scheme_performance_id, $get_not_duplicate_array))
               {
                    $data->type = "not_duplicate";
               }
               else if(in_array($data->scheme_performance_id, $get_duplicate_array))
               {
                    $data->type = "duplicate";
               }
               else{
                    $data->type = "probable_duplicate";
               }

        
          }



       

          return ['Matching'=>$datas,'tmp_matching'=>$tmp_matching, 'id'=>$id, 'scheme_performance_id_to_append'=>$scheme_performance_id_to_append,'append_comment'=>$append_comment];
   }




 public function get_data(Request $request)
 {
     $diff = $request->scheme_performance_id;
     $duplicate = explode(",",$request->hidden_input_for_revert);
     $progress = explode(",",$request->hidden_input_for_inprogress);

     if(count($duplicate)!=0){
          $diff = array_diff($diff, $duplicate);
     }
     if(count($progress)!=0){
          $diff = array_diff($diff, $progress);
     }
     $diff = array_values($diff);

    
     $check_matching_performance = CheckMatchingPerformance::where('id',$request->matching_id)->first();
    
     $scheme_performance = SchemePerformance::where('scheme_performance_id',$check_matching_performance->scheme_performance_id)->select('scheme_performance_id','status')->first();

    //getting data from front end
     $check_matching_performance->duplicate = $request->hidden_input_for_revert ?? "";
     $check_matching_performance->not_duplicate = $request->hidden_input_for_inprogress ?? "";
     


     $check_matching_performance->probable_duplicate = implode(",",$diff);

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

     if($request->comment!="")
     {
          $check_matching_performance->comment =  serialize($request->comment);

     }
     else{
          $check_matching_performance->comment="";  
     }

   
     $check_matching_performance->save();
    
    
     return redirect('matching-schemes');


 }

 //functions for view page

public function view()
 {


     $year_datas = Year::orderBy('year_id')->where('status',1)->get();

     $scheme_datas = SchemeStructure::orderBy('scheme_id')->where('org_id',1)->get();

     $block_datas = GeoStructure::orderBy('geo_id')->where('level_id',3)->get();

     return view('matching-schemes.view')->with(compact('year_datas','scheme_datas','block_datas'));
}

public function get_panchayat_datas(Request $request)
{
    
   
     $datas = GeoStructure::where('bl_id', $request->block_id)->get();
    
    return $datas;
}

public function search_datas(Request $request)
{
    
     if($request->year_id && $request->scheme_id && $request->block_id && $request->panchayat_id !="")
     {
          $get_datas = SchemePerformance::where('year_id',$request->year_id)
               ->where('scheme_id',$request->scheme_id)
               ->where('block_id',$request->block_id)
               ->where('panchayat_id',$request->panchayat_id)
               ->get()->pluck('scheme_performance_id'); 
          
     }
     else{
          $get_datas = SchemePerformance::where('year_id',$request->year_id)
               ->where('scheme_id',$request->scheme_id)
               ->where('block_id',$request->block_id)
               ->get()->pluck('scheme_performance_id');
     }
      

     $get_matching_inprogress_performance_id = CheckMatchingPerformance::whereIn('scheme_performance_id',$get_datas)->where('status',1)->get();

     $get_matching_revert_performance_id = CheckMatchingPerformance::whereIn('scheme_performance_id',$get_datas)->where('status',0)->get();

     //  return $get_matching_scheme_performance_id;
    

    

      return view('matching-schemes.view');
}


}