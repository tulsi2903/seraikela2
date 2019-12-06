<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetBlockCount;
use App\AssetNumbers;
use App\AssetGeoLocation;
use App\Asset;
use App\Department;
use App\GeoStructure;
use App\Year;
use DB;

class AssetReviewController extends Controller
{
    //
    public function index(){
       $block_datas = GeoStructure::where('level_id','3')->get();
       $department_datas = Department::orderBy('dept_name')->get();
       $year_datas = Year::select('year_id','year_value')->get();
       return view('asset-review.index')->with(compact('block_datas','department_datas','year_datas'));
    }

    public function show(Request $request){
        $year = $request->year;
        $geo_id = explode(',',$request->geo_id);
        return view('asset-review.show')->with(compact('geo_id','year'));
    }

    // to get all views datas
    public function get_datas(Request $request){
        // initializing what to send back
        $tabular_view = []; // tabular data
        $chart_labels = []; // labels charts.js (block names)
        $chart_datasets = []; // for datasets charts.js [{label:'',data:[]},{label:'',data:[]}]
        $map_view_blocks = [];
        $map_view_assets = [];

        // received datas
        $review_for = $request->review_for;
        $geo_id = explode(",", $request->geo_id); // geo_id received as
        $panchayat_id = [];
        if(isset($request->panchayat_id)){
            $panchayat_id = explode(",", $request->panchayat_id); // panchayat_id received as
        }
        $no_of_blocks = count($geo_id);
        $dept_id = $request->dept_id;
        $year = $request->year_id;

        // step 1: get asset_block_count/asset_numbers rows according to panchayat_id, geo_id (block), dept_id, year_id
        if($review_for=="block"){ // block review
            $datas = AssetBlockCount::whereIn('asset_id', Asset::select('asset_id')->where('dept_id',$dept_id)->get())
                ->whereIn('geo_id', $geo_id)
                ->where('year', $year)
                ->get();
        }
        else{ // panchayat review
            $datas = AssetNumbers::whereIn('asset_id', Asset::select('asset_id')->where('dept_id',$dept_id)->get())
                ->whereIn('geo_id', $panchayat_id)
                ->where('year', $year)
                ->get();
            //** Important: have to count only last update of each combination
        }

        /* getting unique Asset Ids, map_view_assets*/
        $asset_unique_ids = []; // unique asset ids
        // 1- all unique ids assigned to punchayats 
        // foreach($datas as $data){
        //     if(!in_array($data->asset_id, $asset_unique_ids)){
        //         array_push($asset_unique_ids, $data->asset_id);
        //          // also add map_view_assets
        //     }
        // }
        // 2- all asset ids (on request department)
        $asset_tmps = Asset::where('dept_id', $dept_id)->get();
        foreach($asset_tmps as $asset_tmp){
            if(!in_array($asset_tmp->asset_id, $asset_unique_ids)){
                array_push($asset_unique_ids, $asset_tmp->asset_id);
                array_push($map_view_assets, ['id'=>$asset_tmp->asset_id,'name'=>$asset_tmp->asset_name]);
            }
        }

        // creating tabular data <thead>, assigning chart labels, assigning map view block names & id
        if($review_for=="block") // block review
        {
            $tabular_view_tmp=[''];
            for($i=0;$i<$no_of_blocks;$i++){
                $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$geo_id[$i])->first();
                array_push($tabular_view_tmp, $geo_name->geo_name);
                array_push($chart_labels, $geo_name->geo_name);
                array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
            }
            array_push($tabular_view,$tabular_view_tmp);

            foreach($asset_unique_ids as $asset_unique_id){
                $asset_name = Asset::select('asset_name')->where('asset_id',$asset_unique_id)->first();
                $tabular_view_tmp = [$asset_name->asset_name];
                $chart_datasets_tmp = [];
                $chart_datasets_tmp['label'] = $asset_name->asset_name;
                $chart_datasets_tmp['data'] = [];

                for($i=0;$i<$no_of_blocks;$i++){
                    $found = 0;
                    foreach($datas as $data)
                    {
                        if($data->asset_id==$asset_unique_id)
                        {
                            if($data->geo_id==$geo_id[$i]){
                                array_push($tabular_view_tmp,$data->count);
                                array_push($chart_datasets_tmp['data'], $data->count);
                                $found=1;
                            }
                        }
                    }

                    if($found==0){
                        array_push($tabular_view_tmp, '0');
                        array_push($chart_datasets_tmp['data'], 0.2);
                    }
                }
                array_push($tabular_view, $tabular_view_tmp);
                array_push($chart_datasets, ((object) $chart_datasets_tmp));
            }
        }
        else // panchayat review
        {
            $tabular_view_tmp=[''];
            for($i=0;$i<count($panchayat_id);$i++){
                $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$panchayat_id[$i])->first();
                array_push($tabular_view_tmp, $geo_name->geo_name);
                array_push($chart_labels, $geo_name->geo_name);
                array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
            }
            array_push($tabular_view,$tabular_view_tmp);

            foreach($asset_unique_ids as $asset_unique_id){
                $asset_name = Asset::select('asset_name')->where('asset_id',$asset_unique_id)->first();
                $tabular_view_tmp = [$asset_name->asset_name];
                $chart_datasets_tmp = [];
                $chart_datasets_tmp['label'] = $asset_name->asset_name;
                $chart_datasets_tmp['data'] = [];

                for($i=0;$i<count($panchayat_id);$i++){
                    $found = 0;
                    foreach($datas as $data)
                    {
                        if($data->asset_id==$asset_unique_id)
                        {
                            if($data->geo_id==$panchayat_id[$i]){
                                array_push($tabular_view_tmp,$data->current_value);
                                array_push($chart_datasets_tmp['data'], $data->current_value);
                                $found=1;
                            }
                        }
                    }

                    if($found==0){
                        array_push($tabular_view_tmp, '0');
                        array_push($chart_datasets_tmp['data'], 0.2);
                    }
                }
                array_push($tabular_view, $tabular_view_tmp);
                array_push($chart_datasets, ((object) $chart_datasets_tmp));
            }
        }


        if(count($asset_unique_ids)!=0){
            $response = "success"; // if no records found
        }
        else{
            $response = "no_data";
        }

        return ['review_for'=>$review_for, 'datas'=>$datas, 'response'=>$response, 'tabular_view'=>$tabular_view, 'chart_labels'=>$chart_labels, 'chart_datasets'=>$chart_datasets, 'map_view_blocks'=>$map_view_blocks, 'map_view_assets'=>$map_view_assets];
    }

    public function get_panchayat_data(Request $request){
        $data = [];
        $geo_id = explode(",", $request->geo_id); // geo_id received as

        for($i=0;$i<count($geo_id);$i++){
            $to_send = ["block_name"=>"","panchayat_data"=>""];
            $tmp = GeoStructure::select('geo_name')->where('geo_id', $geo_id[$i])
            ->first();
            $to_send["block_name"] = $tmp->geo_name;
            $tmp = GeoStructure::select('geo_id','geo_name')->where('bl_id', $geo_id[$i])
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

    public function get_map_data(Request $request){
        if($request->review_for=="block") // block review
        {
            $data = AssetGeoLocation::leftJoin('geo_structure', 'asset_geo_location.geo_id', '=', 'geo_structure.geo_id')
                ->select('asset_geo_location.*','geo_structure.geo_name')
                ->whereIn('asset_geo_location.geo_id', GeoStructure::select('geo_id')->where('bl_id',$request->geo_id)->get())
                ->where('asset_geo_location.asset_id', $request->asset_id)
                ->where('asset_geo_location.year',$request->year_id)
                ->get();
        }
        else{ // panchayat
            $data = AssetGeoLocation::leftJoin('geo_structure', 'asset_geo_location.geo_id', '=', 'geo_structure.geo_id')
                ->select('asset_geo_location.*','geo_structure.geo_name')
                ->where('asset_geo_location.geo_id', $request->geo_id)
                ->where('asset_geo_location.asset_id', $request->asset_id)
                ->where('asset_geo_location.year',$request->year_id)
                ->get();
        }
        
        if(count($data)>0){
            $response = "success";
        }
        else{
            $response = "no_data";
        }
        return ['review_for'=>$request->review_for,'map_data'=>$data,'response'=>$response];
    }
}


/*

    {block_name="", },{}

*/