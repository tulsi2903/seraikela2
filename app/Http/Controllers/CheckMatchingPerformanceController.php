<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemePerformance;
use App\SchemeStructure;
use App\CheckMatchingPerformance;
use Auth;
use App\GeoStructure;
use App\Year;


class CheckMatchingPerformanceController extends Controller
{
     public function insert_mathcingperformance($id = "", $result = "")
     {
          if ($result == "true") {
               $SchemePerformance_deatails = SchemePerformance::where('scheme_performance_id', $id)->first();
               $CheckMatchingPerformance = new CheckMatchingPerformance();
               $CheckMatchingPerformance->scheme_performance_id = $id;
               $CheckMatchingPerformance->performance_matching_value = 566;
               $CheckMatchingPerformance->scheme_performance_status = $SchemePerformance_deatails->status;
               $CheckMatchingPerformance->created_by = Auth::user()->id;
               $CheckMatchingPerformance->updated_by = Auth::user()->id;
               $CheckMatchingPerformance->save();
          }
          return ["CheckMatchingPerformance" => $CheckMatchingPerformance];
     }

     public function index(Request $request)
     {
          $search = false;
          $scheme_datas = SchemeStructure::select('scheme_id', 'scheme_name', 'scheme_short_name')->where('status', 1)->orderBy('scheme_id', 'DESC')->get(); // only independent scheme (scheme_is == 1)
          $year_datas = Year::select('year_id', 'year_value')->where('status', 1)->orderBy('year_value', 'asc')->get();
          $block_datas = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->where('level_id', '=', '3')->get();
          $panchayat_datas = GeoStructure::where('bl_id', -1)->where('level_id', '=', '4')->get(); // no data, initially

          $to_search_scheme_id = [0];
          $to_search_year_id = [0];
          $to_search_block_id = [0];
          $to_search_panchayat_id = [0];
          if($request->search=="yes"){
               if($request->scheme_id=="all"){
                    $to_search_scheme_id = $scheme_datas->pluck('scheme_id');
               }
               else{
                    $to_search_scheme_id = [$request->scheme_id];
               }

               if($request->year_id=='all'){
                    $to_search_year_id = $year_datas->pluck('year_id');
               }
               else{
                    $to_search_year_id = [$request->year_id];
               }

               if($request->block_id=='all'){
                    $to_search_panchayat_id = GeoStructure::where('level_id', '=', '4')->pluck('geo_id');
               }
               else{
                    $panchayat_datas = GeoStructure::where('bl_id', $request->block_id)->where('level_id', '=', '4')->get();
                    $to_search_block_id = [$request->block_id];
                    if($request->panchayat_id=='all'){
                         $to_search_panchayat_id = $panchayat_datas->pluck('geo_id');
                    }
                    else{
                         $to_search_panchayat_id = [$request->panchayat_id];
                    }
               }

          }

          $datas = CheckMatchingPerformance::leftJoin('scheme_performance', 'chck_matching_performance.scheme_performance_id', '=', 'scheme_performance.scheme_performance_id')
               ->leftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
               ->leftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
               ->leftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
               ->leftJoin('geo_structure', 'scheme_performance.block_id', '=', 'geo_structure.geo_id')
               ->whereIn('scheme_performance.scheme_id', $to_search_scheme_id)
               ->whereIn('scheme_performance.year_id', $to_search_year_id)
               ->whereIn('scheme_performance.panchayat_id', $to_search_panchayat_id)
               ->select('chck_matching_performance.*', 'scheme_performance.scheme_performance_id', 'scheme_performance.attribute', 'scheme_performance.panchayat_id as panchayat_id', 'year.year_value', 'scheme_assets.scheme_asset_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_short_name', 'scheme_structure.attributes as scheme_attributes', 'geo_structure.geo_name')
               ->orderBy('scheme_performance.scheme_id', 'desc')
               ->get();
          foreach ($datas as $data) {
               //panchayat data
               $tmp = GeoStructure::select('geo_name')->where('geo_id', $data['panchayat_id'])->first();
               $data->panchayat_name = $tmp->geo_name;

               $attributes = unserialize($data->attribute);
               $scheme_attributes = unserialize($data->scheme_attributes); // getting attrubutes
               $scheme_attr_simplified = [];
               foreach ($scheme_attributes as $scheme_attributes) {
                    $scheme_attr_simplified[$scheme_attributes["id"]] = $scheme_attributes["name"];
               }
               $attr_string = "";
               foreach ($attributes as $attribute) {
                    foreach($attribute as $key=>$value){
                         $attr_string .= $scheme_attr_simplified[$key].": ".$value.",<br/>";
                    }
               }
               $data->attribute = rtrim($attr_string, ',<br/>');
          }
          
          return view('matching-schemes.index')->with(compact('datas', 'scheme_datas', 'year_datas', 'block_datas', 'panchayat_datas', 'to_search_scheme_id', 'to_search_year_id', 'to_search_block_id', 'to_search_panchayat_id'));
     }

     public function get_matching_entries($id = "")
     {
          $datas = CheckMatchingPerformance::where('id', $id)->first();
          $get_data = $datas;


          $tmp_matching = count(explode(",", $datas->matching_performance_id));
          $tmp_matching_array = explode(",", $datas->matching_performance_id);
          $created_at = $datas->created_at;
          $updated_at = $datas->updated_at;
          $scheme_performance_id_to_append = $datas->scheme_performance_id;
          $append_comment = unserialize($datas->comment);


          $get_not_duplicate_array = explode(",", $datas->not_duplicate);
          $get_duplicate_array = explode(",", $datas->duplicate);
          // $get_scheme_performance_id=$datas->scheme_performance_id;

          $CheckMatchingPerformance_id = $datas->id;
          $datas = SchemePerformance::leftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
               ->leftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
               ->leftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
               ->leftJoin('geo_structure', 'scheme_performance.block_id', '=', 'geo_structure.geo_id')
               ->select('scheme_performance.scheme_performance_id', 'scheme_performance.attribute', 'geo_structure.geo_name as block_name ', 'scheme_performance.panchayat_id as panchayat_id', 'year.year_value', 'scheme_assets.scheme_asset_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_short_name', 'scheme_structure.attributes as scheme_attributes', 'geo_structure.geo_name')
               ->whereIn('scheme_performance_id', $tmp_matching_array)
               ->get();



          foreach ($datas as $data) {

               //panchayat data
               $data->created_at = $created_at;
               $data->updated_at = $updated_at;
               //  $data->scheme_performance_id = $get_scheme_performance_id;

               $tmp = GeoStructure::select('geo_name')->where('geo_id', $data['panchayat_id'])->first();
               $data->panchayat_name = $tmp->geo_name;
               $attribute[0] = unserialize($data['attribute']);
               $print_att = "";
               foreach ($attribute[0][0] as $key_at => $value_att) {
                    $print_att = $value_att;
               }
               $data->attribute = $print_att;

               // if performance id is in array, then $data->type="duplicate"  OR "not_duplicate" 
               if (in_array($data->scheme_performance_id, $get_not_duplicate_array)) {
                    $data->type = "not_duplicate";
               } else if (in_array($data->scheme_performance_id, $get_duplicate_array)) {
                    $data->type = "duplicate";
               } else {
                    $data->type = "probable_duplicate";
               }
          }


          return ['Data' => $get_data, 'Matching' => $datas, 'tmp_matching' => $tmp_matching, 'id' => $id, 'scheme_performance_id_to_append' => $scheme_performance_id_to_append, 'append_comment' => $append_comment];
     }


     public function get_panchayat_datas(Request $request)
     {
          $datas = GeoStructure::where('bl_id', $request->block_id)->get();
          return $datas;
     }

     public function get_all_matching_datas(Request $request)
     {
          // $request->id is primary_key
          $check_matching_performance_datas = CheckMatchingPerformance::where('id', $request->id)->first();

          $scheme_performance_id = $check_matching_performance_datas->scheme_performance_id;
          $matching_performance_ids = explode(",", $check_matching_performance_datas->matching_performance_id);
          $probable_duplicate = explode(",", $check_matching_performance_datas->probable_duplicate);
          $not_duplicate = explode(",", $check_matching_performance_datas->not_duplicate);
          $duplicate = explode(",", $check_matching_performance_datas->duplicate);
          $comment = unserialize($check_matching_performance_datas->comment);

          $matching_performance_datas = SchemePerformance::leftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
               ->leftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
               ->leftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
               ->leftJoin('geo_structure', 'scheme_performance.block_id', '=', 'geo_structure.geo_id')
               ->select('scheme_performance.scheme_performance_id', 'scheme_performance.attribute', 'geo_structure.geo_name as block_name ', 'scheme_performance.panchayat_id as panchayat_id', 'year.year_value', 'scheme_assets.scheme_asset_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_short_name', 'scheme_structure.attributes as scheme_attributes', 'geo_structure.geo_name')
               ->whereIn('scheme_performance_id', $matching_performance_ids)
               ->get();


          foreach ($matching_performance_datas as $i=>$matching_performance_data) {

               $tmp = GeoStructure::select('geo_name')->where('geo_id', $matching_performance_data['panchayat_id'])->first();
               $matching_performance_data->panchayat_name = $tmp->geo_name;
               $attributes = unserialize($matching_performance_data->attribute);
               $scheme_attributes = unserialize($matching_performance_data->scheme_attributes); // getting attrubutes
               $scheme_attr_simplified = [];
               foreach ($scheme_attributes as $scheme_attributes) {
                    $scheme_attr_simplified[$scheme_attributes["id"]] = $scheme_attributes["name"];
               }
               $attr_string = "";
               foreach ($attributes as $attribute) {
                    foreach($attribute as $key=>$value){
                         $attr_string .= $scheme_attr_simplified[$key].": ".$value.",<br/>";
                    }
               }
               $matching_performance_data->attribute = rtrim($attr_string, ',<br/>');

               if(in_array($matching_performance_data->scheme_performance_id, $probable_duplicate)) {
                    $matching_performance_data->type = "probable_duplicate";
               }
               else if(in_array($matching_performance_data->scheme_performance_id, $not_duplicate)) {
                    $matching_performance_data->type = "not_duplicate";
               }
               else if(in_array($matching_performance_data->scheme_performance_id, $duplicate)) {
                    $matching_performance_data->type = "duplicate";
               }
               else{
                    $matching_performance_data->type = 'na'; 
               }

               // for comment
               $matching_performance_data->comment = $comment[$i];
          }

          if(count($matching_performance_datas)>0){
               $response="success";
          }
          else{
               $response="no_data";
          }

          return ["matching_performance_datas"=>$matching_performance_datas, "response"=>$response];
          // return ['Data' => $get_data, 'Matching' => $datas, 'tmp_matching' => $tmp_matching, 'id' => $request->id, 'scheme_performance_id_to_append' => $scheme_performance_id_to_append, 'append_comment' => $append_comment];
     }

     public function assign_to(Request $request){
          // return $request;

          $response = ""; // to send back
          $id = $request->id; // primary_key

          $matching_performance_ids = $request->matching_performance_ids; // array
          $status = $request->status; // array


          $probable_duplicate = [];
          $not_duplicate = [];
          $duplicate = [];

          for($i=0;$i<count($matching_performance_ids);$i++){
               if($status[$i]=="not_duplicate"){
                    $not_duplicate[] = $matching_performance_ids[$i];
               }
               else if($status[$i]=="duplicate"){
                    $duplicate[] = $matching_performance_ids[$i];
               }
               else{ // == probable_duplicate or anything else
                    $probable_duplicate[] = $matching_performance_ids[$i];
               }
          }


          $update_data = new CheckMatchingPerformance;
          $update_data = $update_data->find($id);
          if($update_data){
               $update_data->probable_duplicate = implode(",", $probable_duplicate);
               $update_data->duplicate = implode(",", $duplicate);
               $update_data->not_duplicate = implode(",", $not_duplicate);
               $update_data->comment = serialize($request->comment);
               if($update_data->save()){
                    $response = "success";
               }
               else{
                    $response = "error_occured";
               }
          }
          else{
               $response = "error_occured";
          }

          // deciding if all matching data are not dupliacte then actual performance data should be sanctioned
          $to_change_status = 4; // open
          if($matching_performance_ids == $not_duplicate){
               $to_change_status = 2; // santioned
          }
          else if($matching_performance_ids == $duplicate){
               $to_change_status = 3; // cancel
          }
          $scheme_performance_update = SchemePerformance::find(CheckMatchingPerformance::find($id)->scheme_performance_id);
          if($scheme_performance_update)
          {
               $scheme_performance_update->status = $to_change_status; // sanctioned
               $scheme_performance_update->save(); 
          }

          return ["response"=>$response];
     }




     public function get_data(Request $request)
     {


          $check_matching_performance = CheckMatchingPerformance::where('id', $request->matching_id)->first();

          $scheme_performance = SchemePerformance::where('scheme_performance_id', $check_matching_performance->scheme_performance_id)->select('scheme_performance_id', 'status')->first();




          if ($request->comment != "") {
               $check_matching_performance->comment =  serialize($request->comment);
          } else {
               $check_matching_performance->comment = "";
          }

          // if ($check_matching_performance->duplicate != "") {
          //      $check_matching_performance->status = 0;
          // } else {
          //      $check_matching_performance->status = 1;
          // }

          // $CheckMatchingPerformance_id = $request->get_scheme_performance_id;

          // if ($check_matching_performance->status == 1 && $check_matching_performance->scheme_performance_id != "") {

          //      $get_scheme_performance_id = $request->get_scheme_performance_id; //getting data from front end
          //      SchemePerformance::where('scheme_performance_id', $CheckMatchingPerformance_id)->update(array('status' => 0));
          // }


          $check_matching_performance->save();


          return redirect('matching-schemes');
     }

     //functions for view page

     public function view()
     {


          $year_datas = Year::orderBy('year_id')->where('status', 1)->get();

          $scheme_datas = SchemeStructure::orderBy('scheme_id')->where('org_id', 1)->get();

          $block_datas = GeoStructure::orderBy('geo_id')->where('level_id', 3)->get();


          return view('matching-schemes.view')->with(compact('year_datas', 'scheme_datas', 'block_datas'));
     }

     public function search_datas(Request $request)
     {

          //  return $request;
          if ($request->year_id && $request->scheme_id && $request->block_id && $request->panchayat_id != "") {
               $get_datas = SchemePerformance::where('year_id', $request->year_id)
                    ->where('scheme_id', $request->scheme_id)
                    ->where('block_id', $request->block_id)
                    ->where('panchayat_id', $request->panchayat_id)
                    ->get()->pluck('scheme_performance_id');
          } else {
               $get_datas = SchemePerformance::where('year_id', $request->year_id)
                    ->where('scheme_id', $request->scheme_id)
                    ->where('block_id', $request->block_id)
                    ->get()->pluck('scheme_performance_id');
          }


          $get_matching_inprogress_performance_id = CheckMatchingPerformance::whereIn('scheme_performance_id', $get_datas)->where('status', 1)->get();

          $get_matching_revert_performance_id = CheckMatchingPerformance::whereIn('scheme_performance_id', $get_datas)->where('status', 0)->get();

          //  return $get_matching_scheme_performance_id;

          $datas = CheckMatchingPerformance::where('status', $request->matching_schemes)->get()->pluck('scheme_performance_id');
          $matching_datas = $datas;
          //     return $datas;

          // $tmp_matching_array=explode(",",$datas->matching_performance_id);
          // $created_at=$datas->created_at;
          // $updated_at=$datas->updated_at;
          //  $scheme_performance_id_to_append = $datas->scheme_performance_id;
          //   $append_comment = unserialize($datas->comment);
          //  $scheme_performance_id_to_append = $datas->scheme_performance_id;

          // $get_not_duplicate_array = explode(",",$datas->not_duplicate);
          // $get_duplicate_array = explode(",",$datas->duplicate);
          // // $get_scheme_performance_id=$datas->scheme_performance_id;

          //  $CheckMatchingPerformance_id=$datas->id;
          $datas = SchemePerformance::leftJoin('chck_matching_performance', 'scheme_performance.scheme_performance_id', '=', 'chck_matching_performance.scheme_performance_id')
               ->leftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
               ->leftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
               ->leftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
               ->leftJoin('geo_structure', 'scheme_performance.block_id', '=', 'geo_structure.geo_id')
               ->select('chck_matching_performance.matching_performance_id', 'chck_matching_performance.id as chck_matching_performance_id', 'scheme_performance.scheme_performance_id', 'scheme_performance.attribute', 'geo_structure.geo_name as block_name', 'scheme_performance.panchayat_id as panchayat_id', 'year.year_value', 'scheme_assets.scheme_asset_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_short_name', 'geo_structure.geo_name')
               ->where('scheme_performance.year_id', $request->year_id)
               ->where('scheme_performance.scheme_id', $request->scheme_id)
               ->where('scheme_performance.block_id', $request->block_id)
               ->where('scheme_performance.panchayat_id', $request->panchayat_id)
               ->whereIn('scheme_performance.scheme_performance_id', $matching_datas)
               ->get('scheme_performance.scheme_performance_id');

          // return $matching_datas;

          // $tmp_matching=count(explode(",",$datas->matching_performance_id));

          foreach ($datas as $data) {

               //panchayat data
               //  $data->created_at=$created_at;
               //  $data->updated_at=$updated_at;
               //  $data->scheme_performance_id = $get_scheme_performance_id;

               $tmp = GeoStructure::select('geo_name')->where('geo_id', $data['panchayat_id'])->first();
               $data->panchayat_name = $tmp->geo_name;
               $attribute[0] = unserialize($data['attribute']);
               $print_att = "";
               // print_r($attribute[0]);
               foreach ($attribute[0][0] as $key_at => $value_att) {
                    $print_att = $value_att;
               }
               $data->attribute = $print_att;
          }
          // $data = CheckMatchingPerformance::where('id',$id)->first();

          $id = array();
          $tmp_matching = array();
          $scheme_performance_id_to_append = array();
          $append_comment = array();

          return ['Matching' => $datas, 'tmp_matching' => $tmp_matching, 'id' => $id, 'scheme_performance_id_to_append' => $scheme_performance_id_to_append, 'append_comment' => $append_comment];

          return view('matching-schemes.view');
     }


     public function get_matching_entries_view($id = "")
     {
          $datas = CheckMatchingPerformance::where('id', $id)->first();
          $get_data = $datas;


          $tmp_matching = count(explode(",", $datas->matching_performance_id));
          $tmp_matching_array = explode(",", $datas->matching_performance_id);
          $created_at = $datas->created_at;
          $updated_at = $datas->updated_at;
          $scheme_performance_id_to_append = $datas->scheme_performance_id;
          $append_comment = unserialize($datas->comment);


          $get_not_duplicate_array = explode(",", $datas->not_duplicate);
          $get_duplicate_array = explode(",", $datas->duplicate);
          // $get_scheme_performance_id=$datas->scheme_performance_id;

          $CheckMatchingPerformance_id = $datas->id;
          $datas = SchemePerformance::leftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
               ->leftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
               ->leftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
               ->leftJoin('geo_structure', 'scheme_performance.block_id', '=', 'geo_structure.geo_id')
               ->select('scheme_performance.scheme_performance_id', 'scheme_performance.attribute', 'geo_structure.geo_name as block_name ', 'scheme_performance.panchayat_id as panchayat_id', 'year.year_value', 'scheme_assets.scheme_asset_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_short_name', 'geo_structure.geo_name')
               ->whereIn('scheme_performance_id', $tmp_matching_array)
               ->get();



          foreach ($datas as $data) {

               //panchayat data
               $data->created_at = $created_at;
               $data->updated_at = $updated_at;
               //  $data->scheme_performance_id = $get_scheme_performance_id;

               $tmp = GeoStructure::select('geo_name')->where('geo_id', $data['panchayat_id'])->first();
               $data->panchayat_name = $tmp->geo_name;
               $attribute[0] = unserialize($data['attribute']);
               $print_att = "";
               foreach ($attribute[0][0] as $key_at => $value_att) {
                    $print_att = $value_att;
               }
               $data->attribute = $print_att;

               // if performance id is in array, then $data->type="duplicate"  OR "not_duplicate" 
               if (in_array($data->scheme_performance_id, $get_not_duplicate_array)) {
                    $data->type = "not_duplicate";
               } else if (in_array($data->scheme_performance_id, $get_duplicate_array)) {
                    $data->type = "duplicate";
               } else {
                    $data->type = "probable_duplicate";
               }
          }


          return ['Data' => $get_data, 'Matching' => $datas, 'tmp_matching' => $tmp_matching, 'id' => $id, 'scheme_performance_id_to_append' => $scheme_performance_id_to_append, 'append_comment' => $append_comment];
     }


     public function get_undo_datas(Request $request)
     {
          $undo_data = CheckMatchingPerformance::find($request->id);
          $get_scheme_performance_id = CheckMatchingPerformance::where('scheme_performance_id', $request->matching_id)->first();



          if ($get_scheme_performance_id != "") {
               $new_probable_duplicate_array = explode(",", $get_scheme_performance_id->probable_duplicate);
               $get_scheme_performance_id_duplicate_array = explode(",", $get_scheme_performance_id->duplicate);
               $get_scheme_performance_id_not_duplicate_array = explode(",", $get_scheme_performance_id->not_duplicate);
               $get_matching_performance_id = explode(",",$get_scheme_performance_id->matching_performance_id);

          
               //For duplicate datas
               if (in_array($undo_data->scheme_performance_id, $get_scheme_performance_id_duplicate_array) == 1) {
                    $key = array_search($undo_data->scheme_performance_id, $get_scheme_performance_id_duplicate_array);
                    unset($get_scheme_performance_id_duplicate_array[$key]);
                    if (count($get_scheme_performance_id_duplicate_array) != 0) {
                         $duplicate_array = array_values($get_scheme_performance_id_duplicate_array);
                    }
                    else {
                         CheckMatchingPerformance::where('scheme_performance_id', $request->matching_id)->update(['duplicate' => implode(",", $get_scheme_performance_id_duplicate_array)]);
                    }
                         //Code for status change in check_matching_performance && scheme_performance table
                          if($get_scheme_performance_id_duplicate_array != "")
                         {
                              CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>0));
                              SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>3));
          
                         }
                         else if(count($get_scheme_performance_id_not_duplicate_array) == count($get_matching_performance_id)) 
                         {
                              CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>1));
                              SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>0));
          
                         }
                         
                         else{
                              CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>2));
                              SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>2));
                         }
                   
                    array_push($new_probable_duplicate_array, $undo_data->scheme_performance_id);
                    CheckMatchingPerformance::where('scheme_performance_id', $request->matching_id)->update(['probable_duplicate' => implode(",", $new_probable_duplicate_array)]);
               }
               //For not duplicate datas
               else if (in_array($undo_data->scheme_performance_id, $get_scheme_performance_id_not_duplicate_array) == 1) {

                    $key = array_search($undo_data->scheme_performance_id, $get_scheme_performance_id_not_duplicate_array);
                    unset($get_scheme_performance_id_not_duplicate_array[$key]);
                    if (count($get_scheme_performance_id_not_duplicate_array) != 0) {
                        
                         $not_duplicate_array = array_values($get_scheme_performance_id_not_duplicate_array);
                    } else {
                        
                         CheckMatchingPerformance::where('scheme_performance_id', $request->matching_id)->update(['not_duplicate' => implode(",", $get_scheme_performance_id_not_duplicate_array)]);
                    }
                         //Code for status change in check_matching_performance && scheme_performance table
                         if(count($get_scheme_performance_id_not_duplicate_array) == count($get_matching_performance_id))
                         {
                              CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>1));
                              SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>0));
          
                         }
                         else if($get_scheme_performance_id_duplicate_array != "")
                         {
                              CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>0));
                              SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>3));
          
                         }
                         else
                         {
                              CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>2));
                              SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>2));
                         }
                 
                    array_push($new_probable_duplicate_array, $undo_data->scheme_performance_id);
                    CheckMatchingPerformance::where('scheme_performance_id', $request->matching_id)->update(['probable_duplicate' => implode(",", $new_probable_duplicate_array)]);


               } else {
                    $response=false;
               }
          }
          
         


          $matching_id = $request->matching_id;
          
          if ($undo_data->probable_duplicate) {
               $probable_duplicate_array = explode(",", $undo_data->probable_duplicate);
          } else {
               $probable_duplicate_array = [];
          }
          $not_duplicate_array  =  explode(",", $undo_data->not_duplicate);
          $duplicate_array = explode(",", $undo_data->duplicate);
          $matching_performance_id = explode(",",$undo_data->matching_performance_id);
          if (in_array($matching_id, $not_duplicate_array)) {

               // remove form not duplicate => add in probable duplicate => update both entries (probable, no duplicate);
               $key = array_search($matching_id, $not_duplicate_array);
               unset($not_duplicate_array[$key]); // remove form not duplicate
               $not_duplicate_array = array_values($not_duplicate_array); // reindexing
               array_push($probable_duplicate_array, $matching_id); // add in probable duplicate
                                            
                    //Code for status change in check_matching_performance && scheme_performance table
                    if(count($not_duplicate_array) == count($matching_performance_id))
                    {
                         CheckMatchingPerformance::where('scheme_performance_id', $undo_data->scheme_performance_id)->update(array('status'=>1));
                         SchemePerformance::where('scheme_performance_id',$undo_data->scheme_performance_id)->update(array('status'=>0));
     
                    }
                    else if($duplicate_array != "")
                    {
                         CheckMatchingPerformance::where('scheme_performance_id', $undo_data->scheme_performance_id)->update(array('status'=>0));
                         SchemePerformance::where('scheme_performance_id',$undo_data->scheme_performance_id)->update(array('status'=>3));
                    }
                    else{
                         CheckMatchingPerformance::where('scheme_performance_id', $undo_data->scheme_performance_id)->update(array('status'=>2));
                         SchemePerformance::where('scheme_performance_id',$undo_data->scheme_performance_id)->update(array('status'=>2)); 
                    }
          } 
          else if (in_array($matching_id, $duplicate_array)) {

               // remove form duplicate => add in probable duplicate => update both entries (probable, duplicate);
               $key = array_search($matching_id, $duplicate_array);
               unset($duplicate_array[$key]); // remove form not duplicate
               $duplicate_array = array_values($duplicate_array); // reindexing
               array_push($probable_duplicate_array, $matching_id); // add in probable duplicate
                                       
                    //Code for status change in check_matching_performance && scheme_performance table
                    if($duplicate_array != "")
                    {
                         CheckMatchingPerformance::where('scheme_performance_id', $undo_data->scheme_performance_id)->update(array('status'=>0));
                         SchemePerformance::where('scheme_performance_id',$undo_data->scheme_performance_id)->update(array('status'=>3));
     
                    }
                    else if($duplicate_array == "")
                    {
                         if(count($not_duplicate_array) == count($matching_performance_id))
                         {
                              CheckMatchingPerformance::where('scheme_performance_id', $undo_data->scheme_performance_id)->update(array('status'=>1));
                              SchemePerformance::where('scheme_performance_id',$undo_data->scheme_performance_id)->update(array('status'=>0)); 
                         }
                    }
                    else{
                         CheckMatchingPerformance::where('scheme_performance_id', $undo_data->scheme_performance_id)->update(array('status'=>2));
                         SchemePerformance::where('scheme_performance_id',$undo_data->scheme_performance_id)->update(array('status'=>2)); 
                    }
          }
          else {
               return ["response" => false];
          }

          $undo_data->probable_duplicate = implode(",", $probable_duplicate_array);
          $undo_data->not_duplicate = implode(",", $not_duplicate_array);
          $undo_data->duplicate = implode(",", $duplicate_array);

          $matching_performance_ids_count = count(explode(",", $undo_data->matching_performance_id));
          $selected_performance_ids_count = 0;
          if ($undo_data->not_duplicate) {
               $selected_performance_ids_count += count(explode(",", $undo_data->not_duplicate));
          }
          if ($undo_data->duplicate) {
               $selected_performance_ids_count += count(explode(",", $undo_data->duplicate));
          }

          if ($undo_data->save()) {
               return ["response" => true, "matching_performance_ids_count" => $matching_performance_ids_count, "selected_performance_ids_count" => $selected_performance_ids_count];
          } else {
               return ["response" => false, "matching_performance_ids_count" => $matching_performance_ids_count, "selected_performance_ids_count" => $selected_performance_ids_count];
          }
     }

     public function status_duplicate(Request $request)
     {

          $status_change = CheckMatchingPerformance::find($request->id);
          $get_scheme_performance_id = CheckMatchingPerformance::where('scheme_performance_id', $request->matching_id)->first();
          $matching_performance_id_array = explode(",", $get_scheme_performance_id->matching_performance_id);
          $search_record = in_array($request->scheme_performance_id, $matching_performance_id_array);
          $new_probable_duplicate_array = explode(",", $get_scheme_performance_id->probable_duplicate);
          $duplicate_array = explode(",",$get_scheme_performance_id->duplicate);
         
          //Related button status change after clicking on duplicate button
          if ($search_record == 1) {
               $get_scheme_performance_id->duplicate = $request->scheme_performance_id;
               $key = array_search($request->scheme_performance_id, $new_probable_duplicate_array);
               unset($new_probable_duplicate_array[$key]);
               $get_scheme_performance_id->probable_duplicate = implode(",", $new_probable_duplicate_array);
              
               $get_scheme_performance_id->save();
               if($duplicate_array != "")
               {
                    CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>0));
                    SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>3));
 
               }
          }
         


          //Status change after clicking on duplicate button
          $matching_id = $request->matching_id;
          $probable_duplicate_array = explode(",", $status_change->probable_duplicate);
          $duplicate_array = explode(",", $status_change->duplicate);

          if ($status_change->duplicate == "") {
               $status_change->duplicate =  $matching_id;
               $key = array_search($matching_id, $probable_duplicate_array);
               unset($probable_duplicate_array[$key]);
              
          } else {
               $status_change->duplicate = $status_change->duplicate . "," . $matching_id;
               $key = array_search($matching_id, $probable_duplicate_array);
               unset($probable_duplicate_array[$key]);
               
          }
         
          $status_change->probable_duplicate = implode(",", $probable_duplicate_array);


          $matching_performance_ids_count = count(explode(",", $status_change->matching_performance_id));
          $selected_performance_ids_count = 0;
          if ($status_change->not_duplicate) {
               $selected_performance_ids_count += count(explode(",", $status_change->not_duplicate));
          }
          if ($status_change->duplicate) {
               $selected_performance_ids_count += count(explode(",", $status_change->duplicate));
          }



          if ($status_change->save()) {
               if($duplicate_array != "")
               {
                    CheckMatchingPerformance::where('scheme_performance_id', $status_change->scheme_performance_id)->update(array('status'=>0));
                    SchemePerformance::where('scheme_performance_id',$status_change->scheme_performance_id)->update(array('status'=>3));
 
               }

               return ["response" => true, "matching_performance_ids_count" => $matching_performance_ids_count, "selected_performance_ids_count" => $selected_performance_ids_count];
          } else {
               return ["response" => false, "matching_performance_ids_count" => $matching_performance_ids_count, "selected_performance_ids_count" => $selected_performance_ids_count];
          }
     }

     public function status_not_duplicate(Request $request)
     {
          
          $status_change = CheckMatchingPerformance::find($request->id);
          
          $get_scheme_performance_id = CheckMatchingPerformance::where('scheme_performance_id', $request->matching_id)->first();
          $matching_performance_id_array = explode(",", $get_scheme_performance_id->matching_performance_id);
          $search_record = in_array($request->scheme_performance_id, $matching_performance_id_array);
          $new_probable_duplicate_array = explode(",", $get_scheme_performance_id->probable_duplicate);
          $new_not_duplicate_array = explode(",",$get_scheme_performance_id->not_duplicate);
          
          
          $new_matching_performance_id = explode(",",$get_scheme_performance_id->matching_performance_id);
          
         
          
          if ($search_record == 1) {
               $get_scheme_performance_id->not_duplicate = $request->scheme_performance_id;
               $key = array_search($request->scheme_performance_id, $new_probable_duplicate_array);
               unset($new_probable_duplicate_array[$key]);
               $get_scheme_performance_id->probable_duplicate = implode(",", $new_probable_duplicate_array);
               
               $get_scheme_performance_id->save();
              
              
               if((count($new_not_duplicate_array))== (count($new_matching_performance_id)))
               {
                    CheckMatchingPerformance::where('scheme_performance_id', $get_scheme_performance_id->scheme_performance_id)->update(array('status'=>1));
                    SchemePerformance::where('scheme_performance_id',$get_scheme_performance_id->scheme_performance_id)->update(array('status'=>0));
               }
               
          }
       
          $matching_id = $request->matching_id;
          $probable_duplicate_array = explode(",", $status_change->probable_duplicate);
          $not_duplicate_array = explode(",", $status_change->not_duplicate);
          $scheme_performance_id_array = explode(",",$status_change->matching_performance_id);
          
         
          
          if ($status_change->not_duplicate == "") {

               $status_change->not_duplicate =  $matching_id;

               $key = array_search($matching_id, $probable_duplicate_array);
               unset($probable_duplicate_array[$key]);
          } 
          else {

               $status_change->not_duplicate = $status_change->not_duplicate . "," . $matching_id;
               $key = array_search($matching_id, $probable_duplicate_array);
               unset($probable_duplicate_array[$key]);
          }

          $status_change->probable_duplicate = implode(",", $probable_duplicate_array);


          $matching_performance_ids_count = count(explode(",", $status_change->matching_performance_id));
          $selected_performance_ids_count = 0;
          if ($status_change->not_duplicate) {
               $selected_performance_ids_count += count(explode(",", $status_change->not_duplicate));
          }
          if ($status_change->duplicate) {
               $selected_performance_ids_count += count(explode(",", $status_change->duplicate));
          }
        

          if ($status_change->save()) {
               if((count($scheme_performance_id_array)) == (count($not_duplicate_array)))
               {
                    CheckMatchingPerformance::where('scheme_performance_id', $status_change->scheme_performance_id)->update(array('status'=>1));
                    SchemePerformance::where('scheme_performance_id',$status_change->scheme_performance_id)->update(array('status'=>0));
               }
              
               return ["response" => true, "matching_performance_ids_count" => $matching_performance_ids_count, "selected_performance_ids_count" => $selected_performance_ids_count];
          } else {
               return ["response" => false, "matching_performance_ids_count" => $matching_performance_ids_count, "selected_performance_ids_count" => $selected_performance_ids_count];
          }
     }
}