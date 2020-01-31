<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use App\GeoStructure;
use App\Year;
use App\SchemeStructure;
use App\SchemeIndicator;
use App\SchemeGeoTarget;
use App\SchemeGeoLocation;
use App\SchemePerformance;
use App\SchemeAsset;
use App\Group;
use DB;

class SchemeReviewController extends Controller
{
    //
    public function index(Request $request)
    {
        $year_datas = Year::select('year_id', 'year_value')->where('status', 1)->get();
        $scheme_datas = SchemeStructure::select('scheme_id', 'scheme_asset_id', 'scheme_name', 'scheme_is', 'scheme_short_name')->where('status', 1)->get();
        $scheme_asset_datas = SchemeAsset::select('scheme_asset_id', 'scheme_asset_name')->get();

        $data["review_for"] = null;
        $data["scheme_id"] = null;
        $data["geo_id"] = null;
        $data["year_id"] = null;

        if ($request->initiate) {
            $initiate = $request->initiate;
            if ($initiate == "initiate") {
                $data["review_for"] = $request->review_for;
                $data["scheme_id"] = $request->scheme;
                $data["geo_id"] = $request->geo;
                $data["year_id"] = $request->year;
                if (!$data["review_for"] || !$data["scheme_id"] || !$data["geo_id"] || !$data["year_id"]) {
                    $initiate = "no";
                } else {
                    $initiate = "initiate";
                }
            } else {
                $initiate = "no";
            }
        } else {
            $initiate = "no";
        }

        return view('scheme-review.index')->with(compact('year_datas', 'scheme_datas', 'scheme_asset_datas', 'initiate', 'data'));
    }

    public function get_panchayat_data(Request $request)
    {
        $data = [];
        $selected_blocks = explode(",", $request->selected_blocks); // geo_id received as

        for ($i = 0; $i < count($selected_blocks); $i++) {
            $to_send = ["block_name" => "", "panchayat_data" => ""];
            $tmp = GeoStructure::select('geo_name')->where('geo_id', $selected_blocks[$i])
                ->first();
            $to_send["block_name"] = $tmp->geo_name;
            $tmp = GeoStructure::select('geo_id', 'geo_name')->where('bl_id', $selected_blocks[$i])
                ->get();
            $to_send["panchayat_data"] = $tmp;
            array_push($data, $to_send);
        }

        if (count($data) != 0) {
            $response = "success"; // if no records found
        } else {
            $response = "no_data";
        }

        return ['data' => $data, 'response' => $response];
    }

    // public function get_datas(Request $request){
    //     // initializing what to send back
    //     $geo_related = 0;
    //     $all_view_details ="";
    //     $tabular_view = []; // tabular data
    //     $tabular_view_blocks = [];
    //     $chart_labels = []; // labels charts.js (block names)
    //     $chart_datasets = []; // for datasets charts.js [{label:'',data:[]},{label:'',data:[]}]
    //     $map_view_blocks = [];
    //     $map_view_indicators = [];

    //     // received datas (scheme, group are all depending to review_type(front end))
    //     $review_for = $request->review_for; // block, panchayat
    //     $scheme_id = '';
    //     $group_id = '';
    //     // for scheme
    //     if($request->scheme_id){
    //         $scheme_id = "".$request->scheme_id."";
    //     }
    //     // for group
    //     if($request->group_id){
    //         $group_id = "".$request->group_id."";
    //     }
    //     $selected_blocks = explode(",", $request->selected_blocks); // geo_id received as
    //     $panchayat_id = [];
    //     if(isset($request->panchayat_id)){
    //         $panchayat_id = explode(",", $request->panchayat_id); // panchayat_id received as
    //     }
    //     $no_of_blocks = count($selected_blocks);
    //     $year = $request->year_id;


    //     // getting datas from geo target, that gives performance report
    //     if($scheme_id&&$group_id){
    //         $scheme_geo_target_datas = SchemeGeoTarget::whereIn('geo_id', $panchayat_id)
    //             ->where('year_id', $year)
    //             ->where('scheme_id', $scheme_id)
    //             ->where('group_id', $group_id)
    //             ->get();
    //         $indicator_tmps = SchemeIndicator::where('scheme_id', $scheme_id)->get();
    //     }
    //     else if($scheme_id){
    //         $scheme_geo_target_datas = SchemeGeoTarget::whereIn('geo_id', $panchayat_id)
    //             ->where('year_id', $year)
    //             ->where('scheme_id', $scheme_id)
    //             ->get();
    //         $indicator_tmps = SchemeIndicator::where('scheme_id', $scheme_id)->get();
    //     }
    //     else if($group_id){
    //         $scheme_geo_target_datas = SchemeGeoTarget::whereIn('geo_id', $panchayat_id)
    //             ->where('year_id', $year)
    //             ->where('group_id', $group_id)
    //             ->get();

    //         $scheme_id_tmp_array = [];
    //         foreach($scheme_geo_target_datas as $scheme_geo_target_data_tmp){
    //             if(!in_array($scheme_geo_target_data_tmp->scheme_id, $scheme_id_tmp_array)){
    //                 array_push($scheme_id_tmp_array, $scheme_geo_target_data_tmp->scheme_id);
    //             }
    //         }
    //         $indicator_tmps = SchemeIndicator::whereIn('scheme_id', $scheme_id_tmp_array)->get();
    //     }
    //     /* getting unique indicator Ids, map_view_indicators*/
    //     $indicator_unique_ids = []; // unique asset ids
    //     foreach($indicator_tmps as $indicator_tmp){
    //         if(!in_array($indicator_tmp->indicator_id, $indicator_unique_ids)){
    //             array_push($indicator_unique_ids, $indicator_tmp->indicator_id);
    //             array_push($map_view_indicators, ['id'=>$indicator_tmp->indicator_id,'name'=>$indicator_tmp->indicator_name]);
    //         }
    //     }

    //     // getting datas accxording to review for
    //     if($review_for=="block") // block review
    //     {

    //     }
    //     else{ // panchayat review

    //         // // getting first row, i.e. panchayat names, block names 
    //         // $tabular_view_tmp=[''];
    //         // for($i=0;$i<count($panchayat_id);$i++){
    //         //     $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$panchayat_id[$i])->first();
    //         //     array_push($tabular_view_tmp, $geo_name->geo_name);
    //         //     array_push($chart_labels, $geo_name->geo_name);
    //         //     array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
    //         // }
    //         // array_push($tabular_view,$tabular_view_tmp);

    //         // // getting other rows, datas
    //         // foreach($indicator_unique_ids as $indicator_unique_id){
    //         //     $indicator_name = SchemeIndicator::select('indicator_name')->where('indicator_id',$indicator_unique_id)->first();
    //         //     $tabular_view_tmp = [$indicator_name->indicator_name];
    //         //     $chart_datasets_tmp = [];
    //         //     $chart_datasets_tmp['label'] = $indicator_name->indicator_name;
    //         //     $chart_datasets_tmp['data'] = [];

    //         //     for($i=0;$i<count($panchayat_id);$i++){
    //         //         // for getting performance (last entry)
    //         //         $found = 0;
    //         //         foreach($scheme_geo_target_datas as $scheme_geo_target_data)
    //         //         {
    //         //             if($scheme_geo_target_data->indicator_id==$indicator_unique_id&&$scheme_geo_target_data->geo_id==$panchayat_id[$i])
    //         //             {
    //         //                 $scheme_review_performance_data = SchemePerformance::select()->where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)
    //         //                                ->orderBy('scheme_performance_id', 'desc')
    //         //                                ->first();
    //         //                 if($scheme_review_performance_data)
    //         //                 {
    //         //                     array_push($tabular_view_tmp, $scheme_review_performance_data->current_value);
    //         //                     array_push($chart_datasets_tmp['data'], $scheme_review_performance_data->current_value);
    //         //                     $found=1;
    //         //                 }
    //         //             }
    //         //         }

    //         //         if($found==0){
    //         //             array_push($tabular_view_tmp, '0');
    //         //             array_push($chart_datasets_tmp['data'], 0.5);
    //         //         }

    //         //     }
    //         //     array_push($tabular_view, $tabular_view_tmp);
    //         //     array_push($chart_datasets, ((object) $chart_datasets_tmp));
    //         // }

    //         // getting first row, i.e. panchayat names, block names 
    //         $tabular_view_tmp=['','']; // [blank for panchayat name & block name]
    //         for($i=0;$i<count($indicator_unique_ids);$i++){
    //             $indicator_name = SchemeIndicator::select('indicator_id','indicator_name')->where('indicator_id',$indicator_unique_ids[$i])->first();
    //             array_push($tabular_view_tmp, $indicator_name->indicator_name);
    //         }
    //         array_push($tabular_view,$tabular_view_tmp);
    //         for($i=0;$i<count($panchayat_id);$i++){
    //             $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$panchayat_id[$i])->first();
    //             array_push($tabular_view_tmp, $geo_name->geo_name);
    //             array_push($chart_labels, $geo_name->geo_name);
    //             array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
    //         }

    //         // getting datas for tabular view
    //         foreach($panchayat_id as $panchayat_id_row){
    //             // get panchayat name
    //             $panchayat_name = GeoStructure::select('geo_name','bl_id')->where('geo_id', $panchayat_id_row)->first();
    //             $tabular_view_tmp = [$panchayat_name->geo_name];
    //             // get block name
    //             $block_name = GeoStructure::select('geo_name')->where('geo_id', $panchayat_name->bl_id)->first();
    //             array_push($tabular_view_tmp, $block_name->geo_name);
    //             if(!in_array($block_name->geo_name, $tabular_view_blocks)){
    //                 array_push($tabular_view_blocks, $block_name->geo_name);
    //             }

    //             for($i=0;$i<count($indicator_unique_ids);$i++){
    //                 // for getting performance (last entry)
    //                 $found = 0;
    //                 foreach($scheme_geo_target_datas as $scheme_geo_target_data)
    //                 {
    //                     if($scheme_geo_target_data->indicator_id==$indicator_unique_ids[$i]&&$scheme_geo_target_data->geo_id==$panchayat_id_row)
    //                     {
    //                         $scheme_review_performance_data = SchemePerformance::select()->where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)
    //                                        ->orderBy('scheme_performance_id', 'desc')
    //                                        ->first();
    //                         if($scheme_review_performance_data)
    //                         {
    //                             array_push($tabular_view_tmp, $scheme_review_performance_data->current_value);
    //                             $found=1;
    //                         }
    //                     }
    //                 }

    //                 if($found==0){
    //                     array_push($tabular_view_tmp, 0);
    //                 }

    //             }
    //             array_push($tabular_view, $tabular_view_tmp);
    //         }

    //         // getting datas for charts & map
    //         foreach($indicator_unique_ids as $indicator_unique_id){
    //             $indicator_name = SchemeIndicator::select('indicator_name')->where('indicator_id',$indicator_unique_id)->first();
    //             $chart_datasets_tmp = [];
    //             $chart_datasets_tmp['label'] = $indicator_name->indicator_name;
    //             $chart_datasets_tmp['data'] = [];

    //             for($i=0;$i<count($panchayat_id);$i++){
    //                 // for getting performance (last entry)
    //                 $found = 0;
    //                 foreach($scheme_geo_target_datas as $scheme_geo_target_data)
    //                 {
    //                     if($scheme_geo_target_data->indicator_id==$indicator_unique_id&&$scheme_geo_target_data->geo_id==$panchayat_id[$i])
    //                     {
    //                         $scheme_review_performance_data = SchemePerformance::select()->where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)
    //                                        ->orderBy('scheme_performance_id', 'desc')
    //                                        ->first();
    //                         if($scheme_review_performance_data)
    //                         {
    //                             array_push($chart_datasets_tmp['data'], $scheme_review_performance_data->current_value);
    //                             $found=1;
    //                         }
    //                     }
    //                 }

    //                 if($found==0){
    //                     array_push($chart_datasets_tmp['data'], 0.5);
    //                 }

    //             }
    //             array_push($chart_datasets, ((object) $chart_datasets_tmp));
    //         }
    //     }


    //     // getting $geo_related data from scheme_structure
    //     $scheme_structure_tmp = SchemeStructure::where("scheme_id", $scheme_id)->first();
    //     if($scheme_structure_tmp){
    //         $geo_related = $scheme_structure_tmp->geo_related;
    //     }


    //     if(count($indicator_unique_ids)!=0){
    //         $response = "success"; // if no records found
    //     }
    //     else{
    //         $response = "no_data";
    //     }

    //     return ['review_for'=>$review_for, 'geo_related'=>$geo_related, 'scheme_geo_target_datas'=>$scheme_geo_target_datas, 'response'=>$response, 'tabular_view'=>$tabular_view, 'tabular_view_blocks'=>$tabular_view_blocks, 'chart_labels'=>$chart_labels, 'chart_datasets'=>$chart_datasets, 'map_view_blocks'=>$map_view_blocks, 'map_view_indicators'=>$map_view_indicators];
    // }

    /* to test new design */
    public function get_tabular_view_datas(Request $request)
    {
        // initializing what to send back
        $tabular_view = [];
        $map_datas = [];

        /* received datas */
        $review_for = $request->review_for;
        $geo_id = explode(",", $request->geo_id); // geo_id/block_id/panchayat_id received as
        if (!$request->scheme_id) {
            $scheme_datas = SchemeStructure::where('status', 1)->get();
        } else {
            $scheme_datas = SchemeStructure::where('scheme_id', $request->scheme_id)->get();
        }
        $year_id = $request->year_id;
        $scheme_asset_id = $request->scheme_asset_id;


        // assigning block datas
        if ($review_for == "block") {
            $block_datas = GeoStructure::select('geo_id as block_id', 'geo_name as block_name')->whereIn('geo_id', $geo_id)->get();
        }
        if ($review_for == "panchayat") {
            $bl_id_tmp = GeoStructure::select('bl_id')->whereIn('geo_id', $geo_id)->distinct()->get()->pluck('bl_id');
            // return $bl_id_tmp;
            $block_datas = GeoStructure::select('geo_id as block_id', 'geo_name as block_name')->whereIn('geo_id', $bl_id_tmp)->get();
        }

        foreach ($block_datas as $block_data) {
            $tabular_view_block_wise = []; // to append (block wise)

            if ($review_for == "block") {
                $panchayat_datas = GeoStructure::where('bl_id', $block_data->block_id)->get();
            } else { // review_for panchayat
                // select only those panchayat which are selected in map
                $panchayat_datas = GeoStructure::whereIn('geo_id', $geo_id)->where('bl_id', $block_data->block_id)->get();
            }

            /* for heading starts */
            $tmp_1 = [""];
            $tmp_2 = [""]; // for complete, incomplete, total
            foreach ($scheme_datas as $scheme_data) {
                array_push($tmp_1, $scheme_data->scheme_short_name . ":" . $scheme_data->scheme_logo);
                array_push($tmp_2, "Sanctioned", "Completed", "Inprogress");
            }
            array_push($tabular_view_block_wise, $tmp_1);
            array_push($tabular_view_block_wise, $tmp_2);
            /* for heading ends */

            /* getting all performance datas starts: for specific scheme and block wise & respective panchayat wise */
            foreach ($panchayat_datas as $panchayat_data) {
                $scheme_wise_tabular_datas = [];
                foreach ($scheme_datas as $scheme_data) {
                    // retriving rows from performance table/DB
                    $performance_datas = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
                        ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
                        ->select('scheme_performance.*', 'scheme_assets.scheme_asset_name', 'geo_structure.geo_name as panchayat_name')
                        ->where('panchayat_id', $panchayat_data->geo_id)
                        ->where('scheme_id', $scheme_data->scheme_id)
                        ->where('year_id', $year_id)
                        // ->whereIn('scheme_performance_id', [107,122])
                        ->get();
                    if ($scheme_asset_id) { // if scheme asset selected
                        $performance_datas = $performance_datas->where('scheme_asset_id', $scheme_asset_id);
                    }


                    /******* map starts *******/
                    foreach ($performance_datas as $performance_data) {
                        $map_datas_tmp = [];
                        $map_datas_tmp["attributes"] = [];

                        // getting all attributes value in single dimentional associative array (before it is multidimnentional assiciative array)
                        // $scheme_data = SchemeStructure::find($scheme_id); // getting scheme details (attributes, id etc)
                        $attributes = unserialize($scheme_data->attributes); // getting attrubutes
                        $tabular_data_tmp = array();
                        $performance_attributes = [];
                        $per_attr_tmps = unserialize($performance_data->attribute);
                        foreach ($per_attr_tmps as $per_attr_tmp) {
                            $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
                        }

                        // for attributes
                        foreach($attributes as $attribute){
                            // print_r($attribute);
                            // print_r($performance_attributes);
                            // exit();
                            // return $attribute['id'];
                            // return $performance_attributes[$attribute['id']];
                            // return $attribute['name'];
                            array_push($tabular_data_tmp, $performance_attributes[$attribute['id']]);
                            array_push($map_datas_tmp["attributes"], [$attribute['name'], $performance_attributes[$attribute['id']]]);
                        }

                        // for assets
                        if ($performance_data->scheme_asset_name) {
                            array_push($tabular_data_tmp, $performance_data->scheme_asset_name);
                            $map_datas_tmp["asset_name"] = $performance_data->scheme_asset_name;
                        } else {
                            if ($scheme_data->scheme_asset_id) {
                                array_push($tabular_data_tmp, SchemeAsset::find($scheme_data->scheme_asset_id)->scheme_asset_name);
                                $map_datas_tmp["asset_name"] = SchemeAsset::find($scheme_data->scheme_asset_id)->scheme_asset_name;
                            } else {
                                $map_datas_tmp["asset_name"] = "";
                            }
                        }
                        // return $tabular_data_tmp;
                        // for status
                        if ($performance_data->status == 0) {
                            array_push($tabular_data_tmp, "Incomplete");
                            $map_datas_tmp["status"] = "In progess";
                            $map_datas_tmp["road_color"] = "#3232FF";
                            if ($performance_data->connectivity_status == 1) {
                                $map_datas_tmp["road_color"] = "#43F40B";
                            }
                        } else if ($performance_data->status == 1) {
                            array_push($tabular_data_tmp, "Completed");
                            $map_datas_tmp["status"] = "Completed";
                            $map_datas_tmp["road_color"] = "#3c3c3c";
                            if ($performance_data->connectivity_status == 1) {
                                $map_datas_tmp["road_color"] = "#43F40B";
                            }
                        } else if ($performance_data->status == 2) {
                            array_push($tabular_data_tmp, "Sanctioned");
                            $map_datas_tmp["status"] = "Sanctioned";
                            $map_datas_tmp["road_color"] = "#ed3900";
                            if ($performance_data->connectivity_status == 1) {
                                $map_datas_tmp["road_color"] = "#43F40B";
                            }
                        } else if ($performance_data->status == 3) {
                            array_push($tabular_data_tmp, "Cancel");
                            $map_datas_tmp["status"] = "Cancel";
                            $map_datas_tmp["road_color"] = "#FF0000";
                            if ($performance_data->connectivity_status == 1) {
                                $map_datas_tmp["road_color"] = "#43F40B";
                            }
                        }



                        // for coordinates
                        if ($performance_data->coordinates) {
                            $coordinates_tmp = unserialize($performance_data->coordinates);
                            $map_datas_tmp["latitude"] = $coordinates_tmp[0]["latitude"];
                            $map_datas_tmp["longitude"] = $coordinates_tmp[0]["longitude"];
                        }


                        if ($performance_data->coordinates) {
                            $coordinates_multi = array();
                            $coordinates_details = unserialize($performance_data->coordinates);
                            foreach ($coordinates_details as $key_coor => $value_coor) {
                                $coordinates_multi[$key_coor]['lat'] = (float) $value_coor['latitude'];
                                $coordinates_multi[$key_coor]['lng'] = (float) $value_coor['longitude'];
                                // print_r($value_coor);
                            }
                            $map_datas_tmp["coordinates_details"] = $coordinates_multi;
                            // print_r($map_datas_tmp["coordinates_details"]);
                        }

                        // exit;
                        // for gallery
                        $map_datas_tmp["gallery"] = unserialize($performance_data->gallery);

                        // for block_name, panchayat_name
                        $map_datas_tmp["panchayat_name"] = $performance_data->panchayat_name;
                        $map_datas_tmp["block_name"] = GeoStructure::where('geo_id', GeoStructure::find($performance_data->panchayat_id)->bl_id)->first()->geo_name;
                        if ($scheme_data->scheme_is == 2) {
                            if ($performance_data->scheme_asset_id != "") {
                                $SchemeAsset_deatails = SchemeAsset::where('scheme_asset_id', $performance_data->scheme_asset_id)->first();
                                $map_datas_tmp["scheme_map_marker"] = $SchemeAsset_deatails->mapmarkericon;
                            } else {
                                $map_datas_tmp["scheme_map_marker"] = $scheme_data->scheme_map_marker;
                            }
                        } else {
                            // for map marker
                            $map_datas_tmp["scheme_map_marker"] = $scheme_data->scheme_map_marker;
                        }
                        // final push, if coordinates available
                        if ($performance_data->coordinates) {
                            array_push($map_datas, $map_datas_tmp);
                        }
                    }

                    /******** map ends ********/

                    $total_count_tmp = "<a href='javascript:void();' onclick=\"getAllDatasIndividually('" . $panchayat_data->geo_id . "', '" . $panchayat_data->geo_name . "', '" . $scheme_data->scheme_id . "')\">" . $performance_datas->count() . "</a>";
                    array_push($scheme_wise_tabular_datas, $total_count_tmp, $performance_datas->where('status', '1')->count(), $performance_datas->where('status', '')->count());


                    // // assigning datas to variable that has to be returned (tmp)
                    // $tabular_view_tmp = [$panchayat_data->geo_id.":".$panchayat_data->geo_name, $performance_datas->where('status','0')->count(), $performance_datas->where('status','1')->count(), $performance_datas->count()];
                    // // appedning datas to array to be send (tmp)
                    // array_push($tabular_view_block_wise, $tabular_view_tmp);
                }

                // assigning datas to variable that has to be returned (tmp)
                $tabular_view_tmp = array_merge([$panchayat_data->geo_name], $scheme_wise_tabular_datas);
                // $tabular_view_tmp = [$panchayat_data->geo_id.":".$panchayat_data->geo_name, $performance_datas->where('status','0')->count(), $performance_datas->where('status','1')->count(), $performance_datas->count()];
                // appedning datas to array to be send (tmp)
                array_push($tabular_view_block_wise, $tabular_view_tmp);
            }
            /* getting all datas ends */

            // appending datas (block wise) to final return valible (array)
            array_push($tabular_view, ["block_name" => $block_data->block_name, "performance_datas" => $tabular_view_block_wise]);
        }

        if (count($tabular_view) != 0) {
            $response = "success"; // if no records found
        } else {
            $response = "no_data";
        }

        return ["response" => $response, "tabular_view" => $tabular_view, "map_datas" => $map_datas];
    }
    /* to test */

    // public function get_tabular_view_datas(Request $request){
    //     // initializing what to send back
    //     $tabular_view = [];
    //     $map_datas=[];

    //     /* received datas */
    //     $review_for = $request->review_for;
    //     $geo_id = explode(",", $request->geo_id); // geo_id/block_id/panchayat_id received as
    //     $scheme_id = $request->scheme_id;
    //     $year_id = $request->year_id;
    //     $scheme_asset_id = $request->scheme_asset_id;

    //     // assigning block datas
    //     $block_datas; // block_id, block_name
    //     if($review_for=="block"){
    //         $block_datas = GeoStructure::select('geo_id as block_id','geo_name as block_name')->whereIn('geo_id', $geo_id)->get();
    //     }
    //     if($review_for=="panchayat"){
    //         $bl_id_tmp = GeoStructure::select('bl_id')->whereIn('geo_id', $geo_id)->distinct()->get()->pluck('bl_id');
    //         // return $bl_id_tmp;
    //         $block_datas = GeoStructure::select('geo_id as block_id','geo_name as block_name')->whereIn('geo_id', $bl_id_tmp)->get();
    //     }

    //     foreach($block_datas as $block_data){
    //         $tabular_view_block_wise = []; // to append (block wise)

    //         if($review_for=="block"){
    //             $panchayat_datas = GeoStructure::where('bl_id',$block_data->block_id)->get();
    //         }
    //         else{ // review_for panchayat
    //             // select only those panchayat which are selected in map
    //             $panchayat_datas = GeoStructure::whereIn('geo_id', $geo_id)->where('bl_id', $block_data->block_id)->get();
    //         }

    //         /* for <th> i.e. heading */
    //         $tabular_view_block_wise = [["","Incomplete","Completed","Total"]];

    //         /* getting all performance datas starts: for specific scheme and block wise & respective panchayat wise */
    //         foreach($panchayat_datas as $panchayat_data){
    //             // retriving rows from performance table/DB
    //             $performance_datas = SchemePerformance::LeftJoin('scheme_assets','scheme_performance.scheme_asset_id','=','scheme_assets.scheme_asset_id')
    //                                                     ->LeftJoin('geo_structure','scheme_performance.panchayat_id','=','geo_structure.geo_id')
    //                                                     ->select('scheme_performance.*','scheme_assets.scheme_asset_name','geo_structure.geo_name as panchayat_name')
    //                                                     ->where('panchayat_id', $panchayat_data->geo_id)
    //                                                     ->where('scheme_id', $scheme_id)
    //                                                     ->where('year_id', $year_id)
    //                                                     ->get();
    //             if($scheme_asset_id){ // if scheme asset selected
    //                 $performance_datas = $performance_datas->where('scheme_asset_id',$scheme_asset_id);
    //             }


    //             /*******  *******/
    //             foreach($performance_datas as $performance_data)
    //             {   
    //                 $map_datas_tmp = [];
    //                 $map_datas_tmp["attributes"]=[];

    //                 // getting all attributes value in single dimentional associative array (before it is multidimnentional assiciative array)
    //                 $scheme_data = SchemeStructure::find($scheme_id); // getting scheme details (attributes, id etc)
    //                 $attributes = unserialize($scheme_data->attributes); // getting attrubutes

    //                 $performance_attributes = [];
    //                 $per_attr_tmps = unserialize($performance_data->attribute);
    //                 foreach($per_attr_tmps as $per_attr_tmp){
    //                     $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
    //                 }

    //                 // for attributes
    //                 foreach($attributes as $attribute){
    //                     array_push($tabular_data_tmp, $performance_attributes[$attribute[id]]);
    //                     array_push($map_datas_tmp["attributes"], [$attribute[name], $performance_attributes[$attribute[id]]]);
    //                 }

    //                 // for assets
    //                 if($performance_data->scheme_asset_name){
    //                     array_push($tabular_data_tmp, $performance_data->scheme_asset_name);
    //                     $map_datas_tmp["asset_name"] = $performance_data->scheme_asset_name;
    //                 }
    //                 else{
    //                     array_push($tabular_data_tmp, SchemeAsset::find($scheme_data->scheme_asset_id)->scheme_asset_name);
    //                     $map_datas_tmp["asset_name"] = SchemeAsset::find($scheme_data->scheme_asset_id)->scheme_asset_name;
    //                 }

    //                 // for status
    //                 if($performance_data->status==0){
    //                     array_push($tabular_data_tmp, "Incomplete");
    //                     $map_datas_tmp["status"] = "Incomplete";
    //                 }
    //                 else if($performance_data->status==1){
    //                     array_push($tabular_data_tmp, "Completed");
    //                     $map_datas_tmp["status"] = "Completed";
    //                 }

    //                 // for coordinates
    //                 if($performance_data->coordinates){
    //                     $coordinates_tmp = unserialize($performance_data->coordinates);
    //                     $map_datas_tmp["latitude"] = $coordinates_tmp[0]["latitude"];
    //                     $map_datas_tmp["longitude"] = $coordinates_tmp[0]["longitude"];
    //                 }

    //                 // for gallery
    //                 $map_datas_tmp["gallery"] = unserialize($performance_data->gallery);

    //                 // for block_name, panchayat_name
    //                 $map_datas_tmp["panchayat_name"] = $performance_data->panchayat_name;
    //                 $map_datas_tmp["block_name"] = GeoStructure::where('geo_id', GeoStructure::find($performance_data->panchayat_id)->bl_id)->first()->geo_name;

    //                 // for map marker
    //                 $map_datas_tmp["scheme_map_marker"] = $scheme_data->scheme_map_marker;

    //                 // final push, if coordinates available
    //                 if($performance_data->coordinates){ 
    //                     array_push($map_datas, $map_datas_tmp);
    //                 }
    //             }
    //             /********  ********/


    //             // assigning datas to variable that has to be returned (tmp)
    //             $tabular_view_tmp = [$panchayat_data->geo_id.":".$panchayat_data->geo_name, $performance_datas->where('status','0')->count(), $performance_datas->where('status','1')->count(), $performance_datas->count()];
    //             // appedning datas to array to be send (tmp)
    //             array_push($tabular_view_block_wise, $tabular_view_tmp);
    //         }
    //         /* getting all datas ends */

    //         // appending datas (block wise) to final return valible (array)
    //         array_push($tabular_view, ["block_name"=>$block_data->block_name, "performance_datas"=>$tabular_view_block_wise]);
    //     }

    //     if(count($tabular_view)!=0){
    //         $response = "success"; // if no records found
    //     }
    //     else{
    //         $response = "no_data";
    //     }

    //     return ["response"=>$response, "tabular_view"=>$tabular_view, "map_datas"=>$map_datas];
    // }

    public function get_all_performance_datas_individuallly(Request $request)
    {
        // initializing what to send back
        $tabular_view = [];
        $map_datas = [];

        /* received datas */
        $geo_id = $request->geo_id; // single panchayat
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $scheme_asset_id = $request->scheme_asset_id;

        // block name
        $block_name_tmp = GeoStructure::where('geo_id', GeoStructure::find($geo_id)->bl_id)->first()->geo_name;

        $scheme_data = SchemeStructure::find($scheme_id); // getting scheme details (attributes, id etc)
        $attributes = unserialize($scheme_data->attributes); // getting attrubutes

        // for thead
        $tabular_data_tmp = [];
        foreach ($attributes as $attribute) {
            array_push($tabular_data_tmp, $attribute['name']);
        }
        array_push($tabular_data_tmp, "Asset", "Status");
        array_push($tabular_view, $tabular_data_tmp);

        // for body
        $performance_datas = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
            ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
            ->select('scheme_performance.*', 'scheme_assets.scheme_asset_name', 'geo_structure.geo_name as panchayat_name')
            ->where('panchayat_id', $geo_id)
            ->where('scheme_id', $scheme_id)
            ->where('year_id', $year_id)
            ->limit(250)
            ->get();
        if ($scheme_asset_id) { // if scheme asset selected
            $performance_datas = $performance_datas->where('scheme_asset_id', $scheme_asset_id);
        }
        foreach ($performance_datas as $performance_data) {
            $tabular_data_tmp = [];
            $map_datas_tmp = [];
            $map_datas_tmp["attributes"] = [];

            // getting all attributes value in single dimentional associative array (before it is multidimnentional assiciative array)
            $performance_attributes = [];
            $per_attr_tmps = unserialize($performance_data->attribute);
            foreach ($per_attr_tmps as $per_attr_tmp) {
                $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
            }

            // for attributes
            foreach ($attributes as $attribute) {
                array_push($tabular_data_tmp, $performance_attributes[$attribute['id']]);
                array_push($map_datas_tmp["attributes"], [$attribute['name'], $performance_attributes[$attribute['id']]]);
            }
            // for assets
            if ($performance_data->scheme_asset_name) {
                array_push($tabular_data_tmp, $performance_data->scheme_asset_name);
                $map_datas_tmp["asset_name"] = $performance_data->scheme_asset_name;
            } else {
                array_push($tabular_data_tmp, SchemeAsset::find($scheme_data->scheme_asset_id)->scheme_asset_name);
                $map_datas_tmp["asset_name"] = SchemeAsset::find($scheme_data->scheme_asset_id)->scheme_asset_name;
            }
            // for status
            if ($performance_data->status == 0) {
                array_push($tabular_data_tmp, "Incomplete");
                $map_datas_tmp["status"] = "Incomplete";
            } else if ($performance_data->status == 1) {
                array_push($tabular_data_tmp, "Completed");
                $map_datas_tmp["status"] = "Completed";
            }

            // for coordinates
            if ($performance_data->coordinates) {
                $coordinates_tmp = unserialize($performance_data->coordinates);
                $map_datas_tmp["latitude"] = $coordinates_tmp[0]["latitude"];
                $map_datas_tmp["longitude"] = $coordinates_tmp[0]["longitude"];
            }

            // for gallery
            $map_datas_tmp["gallery"] = unserialize($performance_data->gallery);

            // for block_name, panchayat_name
            $map_datas_tmp["panchayat_name"] = $performance_data->panchayat_name;
            $map_datas_tmp["block_name"] = GeoStructure::where('geo_id', GeoStructure::find($performance_data->panchayat_id)->bl_id)->first()->geo_name;

            // for map marker
            $map_datas_tmp["scheme_map_marker"] = $scheme_data->scheme_map_marker;

            // final push
            array_push($tabular_view, $tabular_data_tmp);
            // final push, if coordinates available
            if ($performance_data->coordinates) {
                array_push($map_datas, $map_datas_tmp);
            }
        }

        if (count($tabular_view) != 0) {
            $response = "success"; // if no records found
        } else {
            $response = "no_data";
        }
        // return $map_datas;
        return ["response" => $response, "block_name" => $block_name_tmp, "tabular_view" => $tabular_view, "map_datas" => $map_datas];
    }
}
