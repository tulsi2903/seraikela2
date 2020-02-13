<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class SchemeReviewDuplicateDataCheckController extends Controller
{
    //
    public function index(){

        $year_datas = Year::select('year_id','year_value')->where('status', 1)->get();
        $scheme_datas = SchemeStructure::select('scheme_id','scheme_asset_id','scheme_name','scheme_is','scheme_short_name')->where('status', 1)->get();
        $scheme_asset_datas = SchemeAsset::select('scheme_asset_id','scheme_asset_name')->get();

        return view('scheme-review.duplicate-data-check')->with(compact('year_datas','scheme_datas','scheme_asset_datas'));
    }


    public function get_datas(Request $request){
        // $lat1=22.800099;
        // $long1=86.133800;
        // $lat2=22.800625;
        // $long2=86.133784;
        // // $angle = rad2deg(atan2($long2-$long1,$lat2-$lat1));
        // // return ["angle"=>$angle];
        // return ["angle"=>$this->get_angle_between_points($lat2, $long2, $lat1, $long1)];
        // return $request;
        // echo "<pre>";
        // print_r(SchemePerformance::select('scheme_performance_id','attribute')->get()->toArray());
        // exit();

        // unit related
        // 1 ft = 0.3048 m
        // 1 inch = 0.0254 m;
        $uom = $request->uom;
        $conversion_unit = 1; // pending: from uom table
        $distance_to_measure = $request->distance_to_measure * $conversion_unit;
        

        // to send back
        $duplicate_datas = [];
        /*
        [[object, object], [object, object]]
        */

        // getting specific values/ data to be used
        $scheme_ids_selected = [];
        $scheme_ids_to_test = [];
        $year_ids_selected = [];
        $year_ids_to_test = [];
        $scheme_asset_ids_selected = [];
        $scheme_asset_ids_to_test = [];
        $panchayat_ids_selected = [];
        $panchayat_ids_to_test = [];

        // for scheme_id
        $scheme_ids_to_test = SchemeStructure::get()->pluck('scheme_id');
        if($request->scheme_id){
            $scheme_ids_selected = [$request->scheme_id];
        }
        else{
            $scheme_ids_selected = $scheme_ids_to_test;
        }

        // year_id
        $year_ids_to_test = Year::get()->pluck('year_id');
        if($request->year_id){
            $year_ids_selected = [$request->year_id];
        }
        else{
            $year_ids_selected = $year_ids_to_test;
        }
        
        // scheme_asset_id
        $scheme_asset_id_selected = $request->scheme_asset_id;

        // panchayat id
        $panchayat_ids_to_test = GeoStructure::where('level_id',4)->get()->pluck('geo_id');
        if($request->panchayat_id){
            $panchayat_ids_selected = explode(",", $request->panchayat_id);
        }
        else{
            $panchayat_ids_selected = $panchayat_ids_to_test;
        }


        // get performance datas
        // $performance_datas_tmp = SchemePerformance::LeftJoin('year','scheme_performance.year_id','=','year.year_id')
        //                                         ->LeftJoin('scheme_structure','scheme_performance.scheme_id','=','scheme_structure.scheme_id')
        //                                         ->select('scheme_performance.*','year.year_value','scheme_structure.scheme_short_name','scheme_structure.attributes as scheme_attributes')
        //                                         ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
        //                                         ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected);
        //                                         // ->whereIn('scheme_performance_id', [101,124]);

        // $performance_datas = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
        //                     ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
        //                     ->select('scheme_performance.*', 'scheme_assets.scheme_asset_name', 'geo_structure.geo_name as panchayat_name')
        //                     ->where('panchayat_id', $panchayat_data->geo_id)
        //                     ->where('scheme_id', $scheme_data->scheme_id)
        //                     ->where('year_id', $year_id)
        //                     ->get();

        $performance_datas_selected = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
                                                        ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
                                                        ->LeftJoin('year','scheme_performance.year_id','=','year.year_id')
                                                        ->LeftJoin('scheme_structure','scheme_performance.scheme_id','=','scheme_structure.scheme_id')
                                                        ->select('scheme_performance.*','scheme_assets.scheme_asset_name','geo_structure.geo_name as panchayat_name','year.year_value','scheme_structure.scheme_short_name','scheme_structure.scheme_name','scheme_structure.scheme_is','scheme_structure.scheme_map_marker','scheme_structure.attributes as scheme_attributes')
                                                        ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
                                                        ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected)
                                                        ->where('scheme_performance.scheme_performance_id', 31)
                                                        ->get();
        $performance_datas_to_test = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
                                                        ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
                                                        ->LeftJoin('year','scheme_performance.year_id','=','year.year_id')
                                                        ->LeftJoin('scheme_structure','scheme_performance.scheme_id','=','scheme_structure.scheme_id')
                                                        ->select('scheme_performance.*','scheme_assets.scheme_asset_name','geo_structure.geo_name as panchayat_name','year.year_value','scheme_structure.scheme_short_name','scheme_structure.scheme_name','scheme_structure.scheme_is','scheme_structure.scheme_map_marker','scheme_structure.attributes as scheme_attributes')
                                                        ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
                                                        ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected)
                                                        ->where('scheme_performance.scheme_performance_id', 32)
                                                        ->get();

        // echo "<pre>";    
        // print_r($performance_datas_selected);
        // exit();
        /* 
        actual testing started
        */
        foreach($performance_datas_selected as $performance_data_selected){
            $datas_tmp = [];
            $found = false;
            $coordinates_selected = unserialize($performance_data_selected->coordinates);
            /* some details to show */
                if ($performance_data_selected->coordinates) {
                    $coordinates_multi = array();
                    $coordinates_details = unserialize($performance_data_selected->coordinates);
                    foreach ($coordinates_details as $key_coor => $value_coor) {
                        $coordinates_multi[$key_coor]['lat'] = (float) $value_coor['latitude'];
                        $coordinates_multi[$key_coor]['lng'] = (float) $value_coor['longitude'];
                        // print_r($value_coor);
                    }
                    $performance_data_selected->coordinates_details = $coordinates_multi;
                }

                // for status
                if ($performance_data_selected->status == 0) {
                    $performance_data_selected->status = "In progess";
                    $performance_data_selected->road_color = "#b32b2b";
                } else if ($performance_data_selected->status == 1) {
                    $performance_data_selected->status = "Completed";
                    $performance_data_selected->road_color = "#36cc5e";
                } else if ($performance_data_selected->status == 2) {
                    $performance_data_selected->status = "Sanctioned";
                    $performance_data_selected->road_color = "#121896";
                } else if ($performance_data_selected->status == 3) {
                    $performance_data_selected->status = "Cancelled";
                    $performance_data_selected->road_color = "#404040";
                }

                // for attributes
                $attributes = unserialize($performance_data_selected->scheme_attributes); // getting scheme attrubutes
                $performance_attributes = [];
                $per_attr_tmps = unserialize($performance_data_selected->attribute);
                foreach ($per_attr_tmps as $per_attr_tmp) {
                    $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
                }
                $tmp = [];
                foreach($attributes as $attribute){
                    array_push($tmp, [$attribute['name'], $performance_attributes[$attribute['id']]]);
                    // $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
                }
                $performance_data_selected->attribute_details = $tmp;


                // for scheme name
                $performance_data_selected->scheme_name = "(".$performance_data_selected->scheme_short_name.") ".$performance_data_selected->scheme_name;

                // for gallery
                $map_datas_tmp["gallery"] = unserialize($performance_data->gallery);

                // for block_name, panchayat_name
                $performance_data_selected->panchayat_name = $performance_data_selected->panchayat_name;
                $performance_data_selected->block_name = GeoStructure::where('geo_id', GeoStructure::find($performance_data_selected->panchayat_id)->bl_id)->first()->geo_name;
            
                // for map marker
                if ($performance_data_selected->scheme_is == 2) {
                    if ($performance_data_selected->scheme_asset_id != "") {
                        $SchemeAsset_deatails = SchemeAsset::where('scheme_asset_id', $performance_data_selected->scheme_asset_id)->first();
                        $performance_data_selected->scheme_map_marker = $SchemeAsset_deatails->mapmarkericon;
                    } else {
                        $performance_data_selected->scheme_map_marker = $performance_data_selected->scheme_map_marker;
                    }
                } else {
                    // for map marker
                    $performance_data_selected->scheme_map_marker = $performance_data_selected->scheme_map_marker;
                }
            /* some details to show */
            if(count($coordinates_selected) > 0)
            {
                foreach($performance_datas_to_test as $performance_data_to_test){
                    $coordinates_to_test = unserialize($performance_data_to_test->coordinates);
                    if(count($coordinates_to_test) > 0 && $performance_data_selected->scheme_performance_id!=$performance_data_to_test->scheme_performance_id)
                    {
                        /* some details to show */
                            if ($performance_data_to_test->coordinates) {
                                $coordinates_multi = array();
                                $coordinates_details = unserialize($performance_data_to_test->coordinates);
                                foreach ($coordinates_details as $key_coor => $value_coor) {
                                    $coordinates_multi[$key_coor]['lat'] = (float) $value_coor['latitude'];
                                    $coordinates_multi[$key_coor]['lng'] = (float) $value_coor['longitude'];
                                    // print_r($value_coor);
                                }
                                $performance_data_to_test->coordinates_details = $coordinates_multi;
                            }
                            
                            // for status
                            if ($performance_data_to_test->status == 0) {
                                $performance_data_to_test->status = "In progess";
                                $performance_data_to_test->road_color = "#b32b2b";
                            } else if ($performance_data_to_test->status == 1) {
                                $performance_data_to_test->status = "Completed";
                                $performance_data_to_test->road_color = "#36cc5e";
                            } else if ($performance_data_to_test->status == 2) {
                                $performance_data_to_test->status = "Sanctioned";
                                $performance_data_to_test->road_color = "#121896";
                            } else if ($performance_data_to_test->status == 3) {
                                $performance_data_to_test->status = "Cancelled";
                                $performance_data_to_test->road_color = "#404040";
                            }

                            // for attributes
                            $attributes = unserialize($performance_data_to_test->scheme_attributes); // getting scheme attrubutes
                            $performance_attributes = [];
                            $per_attr_tmps = unserialize($performance_data_to_test->attribute);
                            foreach ($per_attr_tmps as $per_attr_tmp) {
                                $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
                            }
                            $tmp = [];
                            foreach($attributes as $attribute){
                                array_push($tmp, [$attribute['name'], $performance_attributes[$attribute['id']]]);
                                // $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
                            }
                            $performance_data_to_test->attribute_details = $tmp;


                            // for scheme name
                            // $performance_data_to_test->scheme_name = "(".$performance_data_to_test->scheme_short_name.") ".$performance_data_to_test->scheme_name;
                            $performance_data_to_test->scheme_name = $performance_data_to_test->scheme_name;

                            // for gallery
                            $map_datas_tmp["gallery"] = unserialize($performance_data->gallery);

                            // for block_name, panchayat_name
                            $performance_data_to_test->panchayat_name = $performance_data_to_test->panchayat_name;
                            $performance_data_to_test->block_name = GeoStructure::where('geo_id', GeoStructure::find($performance_data_to_test->panchayat_id)->bl_id)->first()->geo_name;
                        
                            // for map marker
                            if ($performance_data_to_test->scheme_is == 2) {
                                if ($performance_data_to_test->scheme_asset_id != "") {
                                    $SchemeAsset_deatails = SchemeAsset::where('scheme_asset_id', $performance_data_to_test->scheme_asset_id)->first();
                                    $performance_data_to_test->scheme_map_marker = $SchemeAsset_deatails->mapmarkericon;
                                } else {
                                    $performance_data_to_test->scheme_map_marker = $performance_data_to_test->scheme_map_marker;
                                }
                            } else {
                                // for map marker
                                $performance_data_to_test->scheme_map_marker = $performance_data_to_test->scheme_map_marker;
                            }
                        /* some details to show */


                        // testing first & last point within a radius
                        // echo "<pre>";
                        // print_r($coordinates_selected);
                        // echo "\n";
                        // print_r($coordinates_to_test);
                        // exit;
                        if(count($coordinates_selected) == 1 || count($coordinates_to_test) == 1){
                            $distance = $this->get_distance($coordinates_selected[0]["latitude"], $coordinates_selected[0]["longitude"], $coordinates_to_test[0]["latitude"], $coordinates_to_test[0]["longitude"]);
                            if($distance<=$distance_to_measure){
                                array_push($datas_tmp, $performance_data_to_test);
                                $found = true;
                            }
                            else{
                                // no duplicate
                            }
                        }
                        else{
                            if(!$this->test_first_and_last_point($coordinates_selected, $coordinates_to_test, $distance_to_measure)){ // matched
                                echo "yes\n";
                                if($this->test_whole_direction($coordinates_selected, $coordinates_to_test)){
                                    if($this->test_all_coordinates($coordinates_selected, $coordinates_to_test, $distance_to_measure)){
                                        array_push($datas_tmp, $performance_data_to_test);
                                        $found = true;
                                    }
                                    else{
                                        // no duplicate
                                    }
                                }
                                else{
                                    // no duplicate
                                }
                            }
                            else{ // not matched
                                // testing if any point is withing a  distance (1KM)
                                if($this->test_distance_if_any($coordinates_selected, $coordinates_to_test, $distance_to_measure)){ // yes, inside
                                    if($this->direction_wise_check_duplicate($coordinates_selected, $coordinates_to_test)){ // this will test and prepare percentage for changes of same direction
                                        array_push($datas_tmp, $performance_data_to_test);
                                        $found = true;
                                    }
                                    else{
                                        // no duplicate
                                    }
                                }
                                else{
                                    // no duplicate
                                }
                            }
                        }
                    }
                }
            }

            if($found){
                array_push($duplicate_datas, array_merge([$performance_data_selected], $datas_tmp));
            }
        }

        if(count($duplicate_datas)>0){
            $response="success";
        }
        else{
            $response="no_data";
        }

        // echo "<pre>";
        // // print_r($duplicate_datas);
        // echo count($duplicate_datas)."\n";
        // // exit();
        // print_r($duplicate_datas);
        // exit();

        return ["duplicate_datas"=>$duplicate_datas, "response"=>$response, 'count'=>count($duplicate_datas)];
    }

    public function test_first_and_last_point($coordinates_selected_datas, $coordinates_to_test_datas, $distance_to_measure){
        // first-first
        $distance_1 = $this->get_distance($coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[0]["longitude"], $coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[0]["longitude"]);
        // first-last
        $distance_2 = $this->get_distance($coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[0]["longitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["latitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["longitude"]);
        // last-first
        $distance_3 = $this->get_distance($coordinates_selected_datas[count($coordinates_selected_datas) - 1]["latitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["longitude"], $coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[0]["longitude"]);
        // last-last
        $distance_4 = $this->get_distance($coordinates_selected_datas[count($coordinates_selected_datas) - 1]["latitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["longitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["latitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["longitude"]);
        
        $probable_index = 0;
        if($distance_1<=$distance_to_measure){ $probable_index+=1; }
        if($distance_2<=$distance_to_measure){ $probable_index+=1; }
        if($distance_3<=$distance_to_measure){ $probable_index+=1; }
        if($distance_4<=$distance_to_measure){ $probable_index+=1; }

        echo $distance_1." - ".$distance_2." - ".$distance_3." - ".$distance_4."\n";
        echo $probable_index."\n";
        
        if($probable_index>=2){
            return true;
        }

        return false;
    }

    public function test_all_coordinates($coordinates_selected_datas, $coordinates_to_test_datas, $distance_to_measure){
        $earth_radius = 6371;
        $total_test_count = 0;
        $total_test_match = 0;

        foreach($coordinates_selected_datas as $key_1=>$coordinates_selected_data){
            foreach($coordinates_to_test_datas as $key_2=>$coordinates_to_test_data){
                // assigning latitude longitude
                $latitude1 = $coordinates_selected_data["latitude"];
                $longitude1 = $coordinates_selected_data["longitude"];
                $latitude2 = $coordinates_to_test_data["latitude"];
                $longitude2 = $coordinates_to_test_data["longitude"];
                $d = $this->get_distance($latitude1, $longitude1, $latitude2, $longitude2);
                // appending in count/
                $total_test_count+=1;
                if($d<=$distance_to_measure){
                    $total_test_match+=1;
                    // echo $key_1." - ".$key_2.", Distance: ".$d."\n";
                }
            }
        }

        $sqrt = round(sqrt($total_test_count));
        // echo $total_test_match." - ".sqrt($total_test_count)."\n\n";
        // if((($total_test_match/$total_test_count)*100)>=50){
        if($total_test_match>=$sqrt){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_distance($latitude1, $longitude1, $latitude2, $longitude2){
        $earth_radius = 6371;
        // calculating distance
        $dLat = deg2rad($latitude2 - $latitude1);  
        $dLon = deg2rad($longitude2 - $longitude1);  

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
        $c = 2 * asin(sqrt($a));  
        $d = ($earth_radius * $c)*1000;  // in meters

        return $d;
    }

    public function test_distance_if_any($coordinates_selected_datas, $coordinates_to_test_datas, $distance_to_measure){
        $earth_radius = 6371;

        foreach($coordinates_selected_datas as $coordinates_selected_data){
            foreach($coordinates_to_test_datas as $coordinates_to_test_data){
                // assigning latitude longitude
                $latitude1 = $coordinates_selected_data["latitude"];
                $longitude1 = $coordinates_selected_data["longitude"];
                $latitude2 = $coordinates_to_test_data["latitude"];
                $longitude2 = $coordinates_to_test_data["longitude"];
                $d = $this->get_distance($latitude1, $longitude1, $latitude2, $longitude2);

                if($d<$distance_to_measure){
                    return true;
                }
            }
        }

        return false;
    }

    public function direction_wise_check_duplicate($coordinates_selected_datas, $coordinates_to_test_datas){
        // // level 1: test if initial point-to-final-point have same inclination (angle)
        // $angle_1 = $this->get_angle_between_points($coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[0]["longitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["latitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["longitude"]);
        // $angle_2 = $this->get_angle_between_points($coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[0]["longitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["latitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["longitude"]);
        // $angle_1 = round(abs($angle_1));
        // $angle_2 = round(abs($angle_2));
        // if($angle_1==$angle_2 || $angle_1>($angle_2-10) || $angle_2<($angle_2-10)){
        //     return true;
        // }
        // else{
        //     return false;
        // }
        
        // level 2: test no of coordinates
                    // same: analyse and test each inclination
                    // not same: analyse and test each inclination serial wise
        $angles_selected = [];
        $angles_to_test = [];
        for($i=0;$i<(count($coordinates_selected_datas)-1);$i++){
            // coordinates_selected_datas[i]
            // coordinates_selected_datas[i+1]
            $angle = round($this->get_angle_between_points($coordinates_selected_datas[$i]["latitude"], $coordinates_selected_datas[$i]["longitude"], $coordinates_selected_datas[$i+1]["latitude"], $coordinates_selected_datas[$i+1]["longitude"]));
            
            if($angle<0){ // negative
                $angle = 180 + $angle;
            }
            array_push($angles_selected, $angle);
        }
        for($i=0;$i<(count($coordinates_to_test_datas)-1);$i++){
            $angle = round($this->get_angle_between_points($coordinates_to_test_datas[$i]["latitude"], $coordinates_to_test_datas[$i]["longitude"], $coordinates_to_test_datas[$i+1]["latitude"], $coordinates_to_test_datas[$i+1]["longitude"]));
            
            if($angle<0){ // negative
                $angle = 180 + $angle;
            }
            array_push($angles_to_test, $angle);
        }

        // generating index for duplicacy
        $test = 0;
        $success = 0;
        if(count($angles_selected) == count($angles_to_test)){ // sam no of points/ angles
            for($i=0;$i<count($angles_selected);$i++){
                if($angles_selected[$i]>($angles_to_test[$i]-10) && $angles_selected[$i]<($angles_to_test[$i]+10)){
                    $success+=1;
                }
                $test+=1;
            }
            if($test==$success){
                return true;
            }
        }
        else{ // different no of points/ angles
            for($i=0;$i<count(angles_selected);$i++){
                if(array_key_exists($i, $angles_to_test)){
                    if($angles_selected[$i]>($angles_to_test[$i]-10) && $angles_selected[$i]<($angles_to_test[$i]+10)){
                        $success+=1;
                    }
                    $test+=1;
                }
            }
            if($test==$success){
                return true;
            }
        }
        // echo "<pre>";
        // print_r($angles_selected);
        // echo "\n";
        // print_r($angles_to_test);
        // echo "\n\n";
        // exit();
        return false;
    }

    public function test_whole_direction($coordinates_selected_datas, $coordinates_to_test_datas){
        $angle_1 = round($this->get_angle_between_points($coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[0]["longitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["latitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["longitude"]));
        $angle_2 = round($this->get_angle_between_points($coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[0]["longitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["latitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["longitude"]));
        
        if($angle_1<0){ // negative
            $angle_1 = 180 + $angle_1;
        }
        if($angle_2<0){ // negative
            $angle_2 = 180 + $angle_2;
        }

        // echo $angle_1." - ".$angle_2."\n";
        if($angle_1>($angle_2-10) && $angle_1<($angle_2+10)){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_angle_between_points($lat1, $long1, $lat2, $long2){
        $angle = rad2deg(atan2($long2-$long1, $lat2-$lat1));
        return $angle;
    } 

    /*$latitude1 = $coordinates_selected[0]["latitude"];
    $longitude1 = $coordinates_selected[0]["longitude"];
    $latitude2 = $coordinates_to_test[0]["latitude"];
    $longitude2 = $coordinates_to_test[0]["longitude"];
    $earth_radius = 6371;

    $dLat = deg2rad($latitude2 - $latitude1);  
    $dLon = deg2rad($longitude2 - $longitude1);  

    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
    $c = 2 * asin(sqrt($a));  
    $d = ($earth_radius * $c)*1000;  // in meters

    array_push($d_tmp, $d);

    if($d<=$distance_duplicate){
        return true;
    }
    else{
        return false;
    }*/


    /*
    // $latitude1 = $coordinates_selected[0]["latitude"];
        // $longitude1 = $coordinates_selected[0]["longitude"];
        // $latitude2 = $coordinates_selected[1]["latitude"];
        // $longitude2 = $coordinates_selected[1]["longitude"];
        array_push($coordinates, [
            "latitude"=>$latitude1,
            "longitude"=>$longitude1
        ]);

        // $dLat = deg2rad($latitude2 - $latitude1);  
        // $dLon = deg2rad($longitude2 - $longitude1);  

        // $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
        // $c = 2 * asin(sqrt($a));
        // $distance = ($earth_radius * $c)*1000;  // in meters

        $distance = sqrt( pow(($coordinates_selected[1]["latitude"] - $coordinates_selected[0]["latitude"]), 2) + pow(($coordinates_selected[1]["longitude"] - $coordinates_selected[1]["longitude"]), 2) );

        echo $distance."<br/>";
        $part = $distance/2;
        for($i=1;$i<2;$i++){
            echo ($part * $i)."<br/>";
            $t = ($part * $i)/$distance;
            echo $t."<br/>";
            $latitude_tmp = (1-$t)*($latitude1 + ($t*$latitude2));
            $longitude_tmp = (1-$t)*($longitude1 + ($t*$longitude2));
            array_push($coordinates, [
                "latitude"=>$latitude_tmp,
                "longitude"=>$longitude_tmp
            ]);
        }
        array_push($coordinates, [
            "latitude"=>$latitude2,
            "longitude"=>$longitude2
        ]);

        echo "<pre>";
        print_r($coordinates);
        exit();
    */

}
