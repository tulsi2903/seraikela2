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
use App\Group;
use DB;

class SchemeReviewController extends Controller
{
    //
    public function index(Request $request){
        $review_type = "scheme";  // scheme, group
        if(isset($request->review_type)){
            if($request->review_type=="scheme"||$request->review_type=="group"){
                $review_type = $request->review_type;
            }
        }
        $block_datas = GeoStructure::where('level_id','3')->get();
        $department_datas = Department::orderBy('dept_name')->get();
        $year_datas = Year::select('year_id','year_value')->get();
        $scheme_datas = SchemeStructure::select('scheme_id','scheme_name','scheme_short_name')->get();
        $scheme_group_datas = Group::select('scheme_group_id','scheme_group_name')->get();
        return view('scheme-review.index')->with(compact('review_type', 'block_datas','year_datas','department_datas','scheme_datas','scheme_group_datas'));
    }

    public function get_panchayat_data(Request $request){
        $data = [];
        $selected_blocks = explode(",", $request->selected_blocks); // geo_id received as

        for($i=0;$i<count($selected_blocks);$i++){
            $to_send = ["block_name"=>"","panchayat_data"=>""];
            $tmp = GeoStructure::select('geo_name')->where('geo_id', $selected_blocks[$i])
            ->first();
            $to_send["block_name"] = $tmp->geo_name;
            $tmp = GeoStructure::select('geo_id','geo_name')->where('bl_id', $selected_blocks[$i])
            ->get();
            $to_send["panchayat_data"] = $tmp;
            array_push($data, $to_send);
        }

        if(count($data)!=0){
            $response = "success"; // if no records found
        }
        else{
            $response = "no_data";
        }
        
        return ['data'=>$data, 'response'=>$response];
    }

    public function get_datas(Request $request){
        // initializing what to send back
        $geo_related = 0;
        $all_view_details ="";
        $tabular_view = []; // tabular data
        $tabular_view_blocks = [];
        $chart_labels = []; // labels charts.js (block names)
        $chart_datasets = []; // for datasets charts.js [{label:'',data:[]},{label:'',data:[]}]
        $map_view_blocks = [];
        $map_view_indicators = [];

        // received datas (scheme, group are all depending to review_type(front end))
        $review_for = $request->review_for; // block, panchayat
        $scheme_id = '';
        $group_id = '';
        // for scheme
        if($request->scheme_id){
            $scheme_id = "".$request->scheme_id."";
        }
        // for group
        if($request->group_id){
            $group_id = "".$request->group_id."";
        }
        $selected_blocks = explode(",", $request->selected_blocks); // geo_id received as
        $panchayat_id = [];
        if(isset($request->panchayat_id)){
            $panchayat_id = explode(",", $request->panchayat_id); // panchayat_id received as
        }
        $no_of_blocks = count($selected_blocks);
        $year = $request->year_id;


        // getting datas from geo target, that gives performance report
        if($scheme_id&&$group_id){
            $scheme_geo_target_datas = SchemeGeoTarget::whereIn('geo_id', $panchayat_id)
                ->where('year_id', $year)
                ->where('scheme_id', $scheme_id)
                ->where('group_id', $group_id)
                ->get();
            $indicator_tmps = SchemeIndicator::where('scheme_id', $scheme_id)->get();
        }
        else if($scheme_id){
            $scheme_geo_target_datas = SchemeGeoTarget::whereIn('geo_id', $panchayat_id)
                ->where('year_id', $year)
                ->where('scheme_id', $scheme_id)
                ->get();
            $indicator_tmps = SchemeIndicator::where('scheme_id', $scheme_id)->get();
        }
        else if($group_id){
            $scheme_geo_target_datas = SchemeGeoTarget::whereIn('geo_id', $panchayat_id)
                ->where('year_id', $year)
                ->where('group_id', $group_id)
                ->get();

            $scheme_id_tmp_array = [];
            foreach($scheme_geo_target_datas as $scheme_geo_target_data_tmp){
                if(!in_array($scheme_geo_target_data_tmp->scheme_id, $scheme_id_tmp_array)){
                    array_push($scheme_id_tmp_array, $scheme_geo_target_data_tmp->scheme_id);
                }
            }
            $indicator_tmps = SchemeIndicator::whereIn('scheme_id', $scheme_id_tmp_array)->get();
        }
        /* getting unique indicator Ids, map_view_indicators*/
        $indicator_unique_ids = []; // unique asset ids
        foreach($indicator_tmps as $indicator_tmp){
            if(!in_array($indicator_tmp->indicator_id, $indicator_unique_ids)){
                array_push($indicator_unique_ids, $indicator_tmp->indicator_id);
                array_push($map_view_indicators, ['id'=>$indicator_tmp->indicator_id,'name'=>$indicator_tmp->indicator_name]);
            }
        }

        // getting datas accxording to review for
        if($review_for=="block") // block review
        {
            
        }
        else{ // panchayat review
            
            // // getting first row, i.e. panchayat names, block names 
            // $tabular_view_tmp=[''];
            // for($i=0;$i<count($panchayat_id);$i++){
            //     $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$panchayat_id[$i])->first();
            //     array_push($tabular_view_tmp, $geo_name->geo_name);
            //     array_push($chart_labels, $geo_name->geo_name);
            //     array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
            // }
            // array_push($tabular_view,$tabular_view_tmp);

            // // getting other rows, datas
            // foreach($indicator_unique_ids as $indicator_unique_id){
            //     $indicator_name = SchemeIndicator::select('indicator_name')->where('indicator_id',$indicator_unique_id)->first();
            //     $tabular_view_tmp = [$indicator_name->indicator_name];
            //     $chart_datasets_tmp = [];
            //     $chart_datasets_tmp['label'] = $indicator_name->indicator_name;
            //     $chart_datasets_tmp['data'] = [];

            //     for($i=0;$i<count($panchayat_id);$i++){
            //         // for getting performance (last entry)
            //         $found = 0;
            //         foreach($scheme_geo_target_datas as $scheme_geo_target_data)
            //         {
            //             if($scheme_geo_target_data->indicator_id==$indicator_unique_id&&$scheme_geo_target_data->geo_id==$panchayat_id[$i])
            //             {
            //                 $scheme_review_performance_data = SchemePerformance::select()->where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)
            //                                ->orderBy('scheme_performance_id', 'desc')
            //                                ->first();
            //                 if($scheme_review_performance_data)
            //                 {
            //                     array_push($tabular_view_tmp, $scheme_review_performance_data->current_value);
            //                     array_push($chart_datasets_tmp['data'], $scheme_review_performance_data->current_value);
            //                     $found=1;
            //                 }
            //             }
            //         }

            //         if($found==0){
            //             array_push($tabular_view_tmp, '0');
            //             array_push($chart_datasets_tmp['data'], 0.5);
            //         }

            //     }
            //     array_push($tabular_view, $tabular_view_tmp);
            //     array_push($chart_datasets, ((object) $chart_datasets_tmp));
            // }

            // getting first row, i.e. panchayat names, block names 
            $tabular_view_tmp=['','']; // [blank for panchayat name & block name]
            for($i=0;$i<count($indicator_unique_ids);$i++){
                $indicator_name = SchemeIndicator::select('indicator_id','indicator_name')->where('indicator_id',$indicator_unique_ids[$i])->first();
                array_push($tabular_view_tmp, $indicator_name->indicator_name);
            }
            array_push($tabular_view,$tabular_view_tmp);
            for($i=0;$i<count($panchayat_id);$i++){
                $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$panchayat_id[$i])->first();
                array_push($tabular_view_tmp, $geo_name->geo_name);
                array_push($chart_labels, $geo_name->geo_name);
                array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
            }

            // getting datas for tabular view
            foreach($panchayat_id as $panchayat_id_row){
                // get panchayat name
                $panchayat_name = GeoStructure::select('geo_name','bl_id')->where('geo_id', $panchayat_id_row)->first();
                $tabular_view_tmp = [$panchayat_name->geo_name];
                // get block name
                $block_name = GeoStructure::select('geo_name')->where('geo_id', $panchayat_name->bl_id)->first();
                array_push($tabular_view_tmp, $block_name->geo_name);
                if(!in_array($block_name->geo_name, $tabular_view_blocks)){
                    array_push($tabular_view_blocks, $block_name->geo_name);
                }

                for($i=0;$i<count($indicator_unique_ids);$i++){
                    // for getting performance (last entry)
                    $found = 0;
                    foreach($scheme_geo_target_datas as $scheme_geo_target_data)
                    {
                        if($scheme_geo_target_data->indicator_id==$indicator_unique_ids[$i]&&$scheme_geo_target_data->geo_id==$panchayat_id_row)
                        {
                            $scheme_review_performance_data = SchemePerformance::select()->where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)
                                           ->orderBy('scheme_performance_id', 'desc')
                                           ->first();
                            if($scheme_review_performance_data)
                            {
                                array_push($tabular_view_tmp, $scheme_review_performance_data->current_value);
                                $found=1;
                            }
                        }
                    }

                    if($found==0){
                        array_push($tabular_view_tmp, 0);
                    }

                }
                array_push($tabular_view, $tabular_view_tmp);
            }

            // getting datas for charts & map
            foreach($indicator_unique_ids as $indicator_unique_id){
                $indicator_name = SchemeIndicator::select('indicator_name')->where('indicator_id',$indicator_unique_id)->first();
                $chart_datasets_tmp = [];
                $chart_datasets_tmp['label'] = $indicator_name->indicator_name;
                $chart_datasets_tmp['data'] = [];

                for($i=0;$i<count($panchayat_id);$i++){
                    // for getting performance (last entry)
                    $found = 0;
                    foreach($scheme_geo_target_datas as $scheme_geo_target_data)
                    {
                        if($scheme_geo_target_data->indicator_id==$indicator_unique_id&&$scheme_geo_target_data->geo_id==$panchayat_id[$i])
                        {
                            $scheme_review_performance_data = SchemePerformance::select()->where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)
                                           ->orderBy('scheme_performance_id', 'desc')
                                           ->first();
                            if($scheme_review_performance_data)
                            {
                                array_push($chart_datasets_tmp['data'], $scheme_review_performance_data->current_value);
                                $found=1;
                            }
                        }
                    }

                    if($found==0){
                        array_push($chart_datasets_tmp['data'], 0.5);
                    }

                }
                array_push($chart_datasets, ((object) $chart_datasets_tmp));
            }
        }


        // getting $geo_related data from scheme_structure
        $scheme_structure_tmp = SchemeStructure::where("scheme_id", $scheme_id)->first();
        if($scheme_structure_tmp){
            $geo_related = $scheme_structure_tmp->geo_related;
        }


        if(count($indicator_unique_ids)!=0){
            $response = "success"; // if no records found
        }
        else{
            $response = "no_data";
        }

        return ['review_for'=>$review_for, 'geo_related'=>$geo_related, 'scheme_geo_target_datas'=>$scheme_geo_target_datas, 'response'=>$response, 'tabular_view'=>$tabular_view, 'tabular_view_blocks'=>$tabular_view_blocks, 'chart_labels'=>$chart_labels, 'chart_datasets'=>$chart_datasets, 'map_view_blocks'=>$map_view_blocks, 'map_view_indicators'=>$map_view_indicators];
    }


    public function get_map_data(Request $request){
        /*
        recieved datas: review_for, geo_id (panchayat), year_id, scheme_id, indicator_id, 
        */
        $scheme_geo_location = [];

        if($request->review_for=="block") // block review
        {
            
        }
        else{ // panchayat review
            $scheme_geo_target_data = SchemeGeoTarget::where('scheme_id',$request->scheme_id)
                                    ->where('indicator_id', $request->indicator_id)
                                    ->where('year_id', $request->year_id)
                                    ->where('geo_id', $request->geo_id)
                                    ->first();
            if($scheme_geo_target_data)
            {
                $scheme_geo_location = SchemeGeoLocation::select('location_address','latitude','longitude')->where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)
                                    ->get();
            }
        }
        
        if(count($scheme_geo_location)>0){
            $response = "success";
        }
        else{
            $response = "no_data";
        }
        return ['review_for'=>$request->review_for,'map_data'=>$scheme_geo_location,'response'=>$response];
    }
}
