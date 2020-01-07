<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeGeoTarget;
use App\GeoStructure;
use App\SchemeIndicator;
use App\Year;
use App\SchemeStructure;
use App\Group;
use App\SchemeGeoTarget2;
use App\SchemePerformance2;
use App\Exports\SchemeGeoTargetExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SchemeAsset;



class SchemeGeoTargetController extends Controller
{
    public function index()
    {
        $datas = SchemeGeoTarget::leftJoin('year', 'scheme_geo_target.year_id', '=', 'year.year_id')
                                    ->select('scheme_geo_target.*','year.year_value')
                                    ->orderBy('scheme_geo_target_id','desc')
                                    ->get();

        foreach($datas as $data)
        {
            // block data
            $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->block_id)->first();
            $data->block_name = $tmp->geo_name;

            //panchayat data
            $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->panchayat_id)->first();
            $data->panchayat_name = $tmp->geo_name;

            //subdivision data
            $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->subdivision_id)->first();
            $data->subdivision_name = $tmp->geo_name;
        }

        return view('scheme-geo-target.add')->with('datas',$datas);
    }

    public function add(Request $request){

        $scheme_datas = SchemeStructure::select('scheme_id','scheme_name','scheme_short_name')->where('scheme_is','=','1')->get(); // only independent scheme (scheme_is == 1)
        $subdivision_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','2')->get();
        $year_datas = Year::select('year_id','year_value')->orderBy('year_value','asc')->get();
        $group_datas = Group::select('scheme_group_id','scheme_group_name')->orderBy('scheme_group_name','asc')->get();
        $block_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','3')->get();
        $panchayat_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','4')->get();

       
        return view('scheme-geo-target.add')->with(compact('scheme_datas','subdivision_datas','year_datas','group_datas','block_datas','panchayat_datas'));
    }

    // public function get_updated_datas(Request $request)
    // {
    //     $data =array();
    //     $data = SchemeGeoTarget::where('scheme_id',$request->scheme_id)->where('year_id',$request->year_id)->where('panchayat_id',$request->panchayat_id)->first();
    //     if($data)
    //     {
    //        return $data;
    //     }
    //     else
    //     {
    //         $temp_array=array();
    //         $temp_array['target']="";
    //         return $temp_array;
          
    //     }
    // }

    // public function store(Request $request)
    // {
    //     $scheme_geo_target = new SchemeGeoTarget;

    //     if(SchemeGeoTarget::where('scheme_id',$request->scheme_id)->where('year_id',$request->year_id)->where('panchayat_id',$request->panchayat_id)->exists()){
    //         $scheme_geo_target = SchemeGeoTarget::where('scheme_id',$request->scheme_id)->where('year_id',$request->year_id)->where('panchayat_id',$request->panchayat_id)->first();
    //     }

    //     if(isset($request->registration_no)){
    //         $registration_no = $request->registration_no;
    //         for($i=0;i<count($registration_no);$i++){
    //             $scheme_geo_target = new SchemeGeoTarget;
    //             $scheme_geo_target->scheme_id = $request->scheme_id;
    //             $scheme_geo_target->year_id = $request->year_id;
    //             $scheme_geo_target->subdivision_id = $request->subdivision_id;
    //             $scheme_geo_target->block_id = $request->block_id;
    //             $scheme_geo_target->panchayat_id = $request->panchayat_id;
    //             $scheme_geo_target->target = $request->current_target;

    //             $scheme_geo_target->registration_no = $request->registartion_no[$i];
    //             $scheme_geo_target->sanction_no = $request->sanction_no[$i];
    //             $scheme_geo_target->landmark = $request->landmark[$i];
    //             $scheme_geo_target->latitude = $request->latitude[$i];
    //             $scheme_geo_target->gallery = $request->gallery[$i];
    //             $scheme_geo_target->status = $request->status[$i];
    //             $scheme_geo_target->comments = $request->comments[$i];

    //             $scheme_geo_target->created_by = Auth::user()->id;
    //             $scheme_geo_target->updated_by = Auth::user()->id;
    //         }
    //     }

        
        

       

    //     if($scheme_geo_target->save()){
    //         session()->put('alert-class','alert-success');
    //         session()->put('alert-content','Scheme Geo Target have been successfully submitted !');
    //     }
    //     else{
    //         session()->put('alert-class','alert-danger');
    //         session()->put('alert-content','Something went wrong while adding new details !');
    //     }

    //     return redirect('scheme-geo-target');

    // }


    // public function store(Request $request){
    //     // received_data
    //     $year_id = $request->year_id;
    //     $scheme_id = $request->scheme_id;
    //     $geo_id = $request->panchayat_id; // panchayat
    //     $group_id = "0";
    //     if($request->$group_id){
    //         $group_id = $request->group_id;
    //     }
    //     $scheme_sanction_id = "0";
    //     if($request->scheme_sanction_id){
    //         $scheme_sanction_id = $request->scheme_sanction_id;
    //     }
    //     $new_scheme_sanction_id = "0";
    //     if($request->new_scheme_sanction_id){
    //         $new_scheme_sanction_id = $request->new_scheme_sanction_id;
    //     }
    //     $indicator_id=[];
    //     $indicator_id = $request->indicator_id; // array type, hidden input for each indicator tabs
    //     $geo_related=[];  // array_type, getting indicator ID for those who are selected
    //     if($request->geo_related)
    //     { 
    //         $geo_related = $request->geo_related;
    //     }
    //     $target=[];
    //     $target = $request->target; //array_type
    //     $scheme_performance_id = [];
    //     $scheme_performance_id = $request->scheme_performance_id;
    //     $indicator_sanction_id=[];
    //     $indicator_sanction_id = $request->indicator_sanction_id; //array_type
    //     $latitude=[];
    //     $latitude = $request->latitude; //array_type
    //     $longitude=[];
    //     $longitude = $request->longitude; //array_type
    //     $comments=[];
    //     $comments = $request->comments; //array_type

    //     // storing datas to scheme_geo_target
    //     $j_initial = 0;
    //     if($new_scheme_sanction_id){ // scheme sanction id not selected ie.e no previous data in scheme_geo_target OR (geo_id, year_id, scheme_id, indicator_id, group_id etc) combination is in DB but new sansction for same to be inserted
    //         // inserting datas indicator id wise, combinatin of above fields and indicator_id, target, geo_related
    //         for($i=0;$i<count($indicator_id);$i++){
    //             $scheme_geo_target_save = new SchemeGeoTarget2;
    //             $scheme_geo_target_save->year_id = $year_id;
    //             $scheme_geo_target_save->scheme_id = $scheme_id;
    //             $scheme_geo_target_save->geo_id = $geo_id;
    //             $scheme_geo_target_save->group_id = $group_id;
    //             $scheme_geo_target_save->scheme_sanction_id = $new_scheme_sanction_id;
    //             $scheme_geo_target_save->indicator_id = $indicator_id[$i];

    //             // for geo related
    //             if(in_array($indicator_id[$i], $geo_related)){
    //                 $scheme_geo_target_save->geo_related = "1";
    //             }
    //             else{
    //                 $scheme_geo_target_save->geo_related = "0";
    //             }

    //             $scheme_geo_target_save->target = $target[$i];
    //             $scheme_geo_target_save->created_by = '1';
    //             $scheme_geo_target_save->updated_by = '1';
    //             $scheme_geo_target_save->save();

    //             // storing datas scheme performance
    //             if($i==0)
    //             { $j_initial = 0; }
    //             else{
    //                 $j_initial = $j_initial+$target[$i-1];
    //             }
    //             $j_final = $j_initial + $target[$i];
    //             for($j=$j_initial;$j<$j_final;$j++)
    //             {
    //                 $scheme_performance_save = new SchemePerformance2;
    //                 $scheme_performance_save->scheme_geo_target_id = $scheme_geo_target_save->scheme_geo_target_id;
    //                 $scheme_performance_save->indicator_sanction_id = $indicator_sanction_id[$j];
    //                 $scheme_performance_save->latitude = $latitude[$j];
    //                 $scheme_performance_save->longitude = $longitude[$j];
    //                 $scheme_performance_save->status = "0"; // for not completed
    //                 $scheme_performance_save->completion_percentage = "0";
    //                 $scheme_performance_save->images = "";
    //                 $scheme_performance_save->comments = $comments[$j];
    //                 $scheme_performance_save->created_by = "1";
    //                 $scheme_performance_save->updated_by = "1";
    //                 $scheme_performance_save->save();
    //             }
    //         }

    //     }
    //     else{ // scheme_sanction_id selected i.e. to edit in DB
    //         for($i=0;$i<count($indicator_id);$i++){
    //             $scheme_geo_target_id_tmp = SchemeGeoTarget2::where('scheme_sanction_id', $scheme_sanction_id)->where('indicator_id', $indicator_id[$i])->first();

    //             $scheme_geo_target_save = SchemeGeoTarget2::find($scheme_geo_target_id_tmp->scheme_geo_target_id);
    //             $scheme_geo_target_save->year_id = $year_id;
    //             $scheme_geo_target_save->scheme_id = $scheme_id;
    //             $scheme_geo_target_save->geo_id = $geo_id;
    //             $scheme_geo_target_save->group_id = $group_id;
    //             $scheme_geo_target_save->scheme_sanction_id = $scheme_sanction_id;
    //             $scheme_geo_target_save->indicator_id = $indicator_id[$i];

    //             // for geo related
    //             if(in_array($indicator_id[$i], $geo_related)){
    //                 $scheme_geo_target_save->geo_related = "1";
    //             }
    //             else{
    //                 $scheme_geo_target_save->geo_related = "0";
    //             }

    //             $scheme_geo_target_save->target = $target[$i];
    //             $scheme_geo_target_save->created_by = '1';
    //             $scheme_geo_target_save->updated_by = '1';
    //             $scheme_geo_target_save->save();

    //             // storing datas scheme performance
    //             if($i==0)
    //             { $j_initial = 0; }
    //             else{
    //                 $j_initial = $j_initial+$target[$i-1];
    //             }
    //             $j_final = $j_initial + $target[$i];
    //             for($j=$j_initial;$j<$j_final;$j++)
    //             {
    //                 $scheme_performance_save = new SchemePerformance2;
    //                 if($scheme_performance_id[$j]){ // if a same indicator sanction ID is in DB
    //                     $scheme_performance_save = $scheme_performance_save->find($scheme_performance_id[$j]);
    //                 }
    //                 $scheme_performance_save->scheme_geo_target_id = $scheme_geo_target_save->scheme_geo_target_id;
    //                 $scheme_performance_save->indicator_sanction_id = $indicator_sanction_id[$j];
    //                 $scheme_performance_save->latitude = $latitude[$j];
    //                 $scheme_performance_save->longitude = $longitude[$j];
    //                 $scheme_performance_save->status = "0"; // for not completed
    //                 $scheme_performance_save->completion_percentage = "0";
    //                 $scheme_performance_save->images = "";
    //                 $scheme_performance_save->comments = $comments[$j];
    //                 $scheme_performance_save->created_by = "1";
    //                 $scheme_performance_save->updated_by = "1";
    //                 $scheme_performance_save->save();
    //             }
    //         }

    //         // deleting entries in scheme_performance, if any row has been deleted "to_delete_scheme_performance_id"
    //         if($request->to_delete_scheme_performance_id){
    //             $to_delete_scheme_performance_id = explode(",",$request->to_delete_scheme_performance_id);
    //             for($k=0;$k<count($to_delete_scheme_performance_id);$k++){
    //                 if(SchemePerformance2::find($to_delete_scheme_performance_id[$k])){
    //                     SchemePerformance2::where('scheme_performance_id',$to_delete_scheme_performance_id[$k])->delete();
    //                 }
    //             }
    //         }
    //     }

    //     return ["response"=>"success","request"=>$request,"to_delete"=>explode(",",$request->to_delete_scheme_performance_id)];
    // }


    public function get_panchayat_datas(Request $request)
    {
        $datas = GeoStructure::where('bl_id', $request->block_id)->get();
        return $datas;
    }

    public function get_target_details(Request $request){
        // getting datas from frontend (scheme_id, year_id, block_id, panchayat_id) and send target details according to panchayats/panchayat (acccording to block/panchayat selected)
        
        // received datas
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $block_id = $request->block_id;
        $panchayat_id = $request->panchayat_id;
        if($panchayat_id){
            $level = "panchayat"; // block/ panchayat
        }
        else{
            $level = "block"; // block/ panchayat
        }

        // to send datas
        $to_return = null;

        if($level=="block"){
            $geo_datas = GeoStructure::select("geo_name","geo_id")->where("bl_id", $block_id)->get(); // getting panchayat data form geo structure (all panchayat from selected block)
        }
        else{ // level=="panchayat"
            $geo_datas = GeoStructure::select("geo_name","geo_id")->where("geo_id", $panchayat_id)->get(); // getting panchayat data form geo structure fro selected panchayat
        }

        // getiing target datas
        foreach($geo_datas as $geo_data){
            $target_data = SchemeGeoTarget::select('scheme_geo_target_id','target')
                                        ->where('scheme_id', $scheme_id)
                                        ->where('year_id', $year_id)
                                        ->where('panchayat_id', $geo_data->geo_id) // panchayat
                                        ->first(); // it will get all panchayat target data in respective block

            // appending datas to send
            if($target_data){
                $geo_data->scheme_geo_target_id = $target_data->scheme_geo_target_id;
                $geo_data->target = $target_data->target;
            }
            else{
                $geo_data->scheme_geo_target_id = null;
                $geo_data->target = null;
            }
        }
        $to_return = $geo_datas; // assigning datas to a varible which is used to return datas


        // for response varibale
        if($to_return){
            $response = "success";
        }
        else{
            $response = "no_data";
        }
        return ["response"=>$response, "target_datas"=>$to_return];
    }

    public function save_target(Request $request){ // save functiuon to save target data of individual panchayat
        // recieved datas
        $scheme_geo_target = new SchemeGeoTarget;

        if($request->purpose=="add"){
            $scheme_geo_target->scheme_id = $request->scheme_id;
            $scheme_geo_target->year_id = $request->year_id;
            $scheme_geo_target->subdivision_id = GeoStructure::find($request->block_id)->sd_id;
            $scheme_geo_target->block_id = $request->block_id;
            $scheme_geo_target->panchayat_id = $request->panchayat_id;
        }
        else{ //$request->purpose == "edit" i.e. $request->scheme_geo_target_id is set
            $scheme_geo_target = $scheme_geo_target->find($request->scheme_geo_target_id);
        }

        $scheme_geo_target->target = $request->target;
        $scheme_geo_target->created_by = Auth::user()->id;
        $scheme_geo_target->updated_by = Auth::user()->id;
        if($scheme_geo_target->save()){
            return ["response"=>"success"];
        }
        else{
            return ["response"=>"failed"];
        }
    }

    // public function get_scheme_sanction_id(Request $request){
    //     // received datas
    //     $year_id = $request->year_id;
    //     $scheme_id = $request->scheme_id;
    //     $panchayat_id = $request->panchayat_id;
    //     $group_id = $request->group_id;
    //     $independent = $request->independent;

    //     if($independent=="0"){ // under group
    //         $data = SchemeGeoTarget2::select("scheme_sanction_id")
    //                                 ->where("year_id", $year_id)
    //                                 ->where("scheme_id", $scheme_id)
    //                                 ->where("geo_id", $panchayat_id)
    //                                 ->where("group_id", $group_id)
    //                                 ->distinct()
    //                                 ->get();
    //     }
    //     else{ // independent==1 (under govt)
    //         $data = SchemeGeoTarget2::select("scheme_sanction_id")
    //                                 ->where("year_id", $year_id)
    //                                 ->where("scheme_id", $scheme_id)
    //                                 ->where("geo_id", $panchayat_id)
    //                                 ->distinct()
    //                                 ->get();
    //     }

    //     if(count($data)!=0){
    //         $response = "success";
    //     }
    //     else{
    //         $response = "no_data";
    //     }

    //     return ["response"=>$response,"scheme_sanction_id"=>$data];
    // }

    // public function get_all_datas(Request $request){
    //     /*
    //     use to send all datas related to geo_target
    //     */
    //     // to send
    //     $to_return = [];

    //     // received datas
    //     $year_id = $request->year_id;
    //     $scheme_id = $request->scheme_id;
    //     $panchayat_id = $request->panchayat_id;
    //     $group_id = $request->group_id;
    //     $independent = $request->independent;
    //     $scheme_sanction_id = $request->scheme_sanction_id;
    //     $new_scheme_sanction_id = $request->new_scheme_sanction_id;
    //     $new_scheme_sanction_id_entered = $request->new_scheme_sanction_id_entered;

    //     $scheme_geo_target_datas = SchemeGeoTarget2::where('scheme_sanction_id', $scheme_sanction_id)->get();
    //     $unique_scheme_geo_target_ids = [];
    //     foreach($scheme_geo_target_datas as $scheme_geo_target_data){
    //         if(!in_array($scheme_geo_target_data->scheme_geo_target_id, $unique_scheme_geo_target_ids)){
    //             array_push($unique_scheme_geo_target_ids, $scheme_geo_target_data->scheme_geo_target_id);
    //         }
    //     }

    //     $indicator_datas = SchemeIndicator::where('scheme_id', $scheme_id)->get();

    //     // getting all rows/columns
    //     foreach($indicator_datas as $indicator_data)
    //     {
    //         $to_return_tmp = [];
    //         $found = false; //data found in geo target i.e. already assigned targets
    //         foreach($scheme_geo_target_datas as $scheme_geo_target_data){
    //             if($indicator_data->indicator_id==$scheme_geo_target_data->indicator_id)
    //             {
    //                 $to_return_tmp["indicator_id"] = $indicator_data->indicator_id;
    //                 $to_return_tmp["indicator_name"] = $indicator_data->indicator_name;
    //                 $to_return_tmp["geo_related"] = $scheme_geo_target_data->geo_related;
    //                 $to_return_tmp["target"] = $scheme_geo_target_data->target;
    //                 $to_return_tmp["indicator_datas"] = [];

    //                 // scheme_performance datas in ["indicator_datas] starts
    //                 $to_return_indicator_datas_tmp = [];
    //                 $scheme_performance_datas = SchemePerformance2::where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)->get();
    //                 foreach($scheme_performance_datas as $scheme_performance_data){
    //                     $tmp_to_push["scheme_performance_id"] = $scheme_performance_data->scheme_performance_id;
    //                     $tmp_to_push["indicator_sanction_id"] = $scheme_performance_data->indicator_sanction_id;
    //                     $tmp_to_push["latitude"] = $scheme_performance_data->latitude;
    //                     $tmp_to_push["longitude"] = $scheme_performance_data->longitude;
    //                     $tmp_to_push["comments"] = $scheme_performance_data->comments;
    //                     array_push($to_return_indicator_datas_tmp, $tmp_to_push);
    //                 }
    //                 if($to_return_indicator_datas_tmp){
    //                     $to_return_tmp["indicator_datas"] = $to_return_indicator_datas_tmp;
    //                 }
    //                 // scheme_performance_datas ends

    //                 $found = true; // if data found in geo target i.e. already assigned targets
    //                 array_push($to_return, $to_return_tmp);
    //             }
    //         }

    //         // no data data found in geo target i.e. already assigned targets
    //         if(!$found){
    //             $to_return_tmp["indicator_id"] = $indicator_data->indicator_id;
    //             $to_return_tmp["indicator_name"] = $indicator_data->indicator_name;
    //             $to_return_tmp["geo_related"] = '0';
    //             $to_return_tmp["target"] = '0';
    //             $to_return_tmp["indicator_datas"] = [];
    //             array_push($to_return, $to_return_tmp);
    //         }
    //     }

    //     if(count($to_return)!=0){
    //         $response = "success";
    //     }
    //     else{
    //         $response = "no_data";
    //     }

    //     return ["response"=>$response, "data"=>$to_return];
    // }


    // public function delete(Request $request)
    // {
    //      if(SchemeGeoTarget::find($request->scheme_geo_target_id)){
    //         SchemeGeoTarget::where('scheme_geo_target_id',$request->scheme_geo_target_id)->delete();
    //         session()->put('alert-class','alert-success');
    //         session()->put('alert-content','Deleted successfully !');
    //     }

    //     return redirect('scheme-geo-target');
    // }


    public function exportExcel_Scheme_Geo_structure()
    {
       # code...
       $data = array(1 => array("Scheme Geo target Sheet"));
       $data[] = array('Sl. No.', 'Scheme', 'Block Name', 'Panchayat', 'Target', 'Year');


       $datas = SchemeGeoTarget::
             leftJoin('scheme_structure', 'scheme_geo_target.scheme_id', '=', 'scheme_structure.scheme_id')
           ->leftJoin('geo_structure', 'scheme_geo_target.geo_id', '=', 'geo_structure.geo_id')
           ->leftJoin('scheme_indicator', 'scheme_geo_target.indicator_id', '=', 'scheme_indicator.indicator_id')
           ->leftJoin('year', 'scheme_geo_target.year_id', '=', 'year.year_id')
           ->leftJoin('scheme_group', 'scheme_geo_target.group_id', '=', 'scheme_group.scheme_group_id')
           ->select('scheme_geo_target.*', 'scheme_structure.scheme_name', 'scheme_structure.scheme_short_name', 'geo_structure.geo_name', 'geo_structure.level_id', 'geo_structure.parent_id', 'scheme_indicator.indicator_name', 'year.year_value', 'scheme_group.scheme_group_name')
           ->orderBy('scheme_geo_target.scheme_geo_target_id', 'desc')
           ->get();

       foreach ($datas as $key => $value) {
           if ($data->level_id == 4) {
               $tmp = GeoStructure::find($value->parent_id);
               if ($tmp->geo_name) {
                   $value->bl_name = $tmp->geo_name;
               } else {
                   $value->bl_name = "NA";
               }
           } else {
               $value->bl_name = "NA";
           }
           $data[] = array(
               $key + 1,
               $value->scheme_name,
               $value->bl_name,
               $value->geo_name,
               $value->target,
               $value->year_value,
           );
       }


       \Excel::create('Scheme-Geo-target-Sheet', function ($excel) use ($data) {

           // Set the title
           $excel->setTitle('Scheme-Geo-target-Sheet');

           // Chain the setters
           $excel->setCreator('Paatham')->setCompany('Paatham');

           $excel->sheet('Fees', function ($sheet) use ($data) {
               $sheet->freezePane('A3');
               $sheet->mergeCells('A1:I1');
               $sheet->fromArray($data, null, 'A1', true, false);
               $sheet->setColumnFormat(array('I1' => '@'));
           });
       })->download('xls');

    }
    public function exportPDF_Scheme_Geo_structure(){
        $SchemeGeoTarget_pdf = SchemeGeoTarget::leftJoin('scheme_structure', 'scheme_geo_target.scheme_id', '=', 'scheme_structure.scheme_id')
                    ->leftJoin('geo_structure', 'scheme_geo_target.geo_id', '=', 'geo_structure.geo_id')
                    ->leftJoin('scheme_indicator','scheme_geo_target.indicator_id','=','scheme_indicator.indicator_id')
                    ->leftJoin('year','scheme_geo_target.year_id','=','year.year_id')
                    ->leftJoin('scheme_group','scheme_geo_target.group_id','=','scheme_group.scheme_group_id')
                    ->select('scheme_geo_target.*','scheme_structure.scheme_name','scheme_structure.scheme_short_name','geo_structure.geo_name','geo_structure.level_id','geo_structure.parent_id','scheme_indicator.indicator_name','year.year_value','scheme_group.scheme_group_name')
                    ->orderBy('scheme_geo_target.scheme_geo_target_id','desc')
                    ->get();

                    $i=0;
                    foreach($SchemeGeoTarget_pdf as $data){
                        if($data->level_id==4){
                            $tmp = GeoStructure::find($data->parent_id);
                            if($tmp->geo_name)
                            { 
                                $SchemeGeoTarget_pdf[$i]->bl_name = $tmp->geo_name; 
                            }
                            else{
                            $SchemeGeoTarget_pdf[$i]->bl_name = "NA";
                            }
                            }
                            else{
                                $SchemeGeoTarget_pdf[$i]->bl_name = "NA";
                            }
                            $i++;
                    }
             
        date_default_timezone_set('Asia/Kolkata');
        $SchemeGeoTarget = date('d-m-Y H:i A');
        // echo "<pre>";
        // print_r($SchemeGeoTarget_pdf->toArray());
        // exit;


        $pdf = PDF::loadView('department/Createpdfs',compact('SchemeGeoTarget_pdf','SchemeGeoTarget'));
        return $pdf->download('SchemeGeoTarget.pdf');

    }

}
