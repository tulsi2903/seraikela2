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
        $performance_datas_tmp = SchemePerformance::LeftJoin('year','scheme_performance.year_id','=','year.year_id')
                                                ->LeftJoin('scheme_structure','scheme_performance.scheme_id','=','scheme_structure.scheme_id')
                                                ->select('scheme_performance.*','year.year_value','scheme_structure.scheme_short_name','scheme_structure.attributes as scheme_attributes')
                                                ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
                                                ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected)
                                                ->whereIn('scheme_performance_id', [101,124]);
        $performance_datas_selected = $performance_datas_tmp->get();
        $performance_datas_to_test = $performance_datas_tmp->get();


        /* 
        actual testing started
        */
        foreach($performance_datas_selected as $performance_data_selected){
            $datas_tmp = [];
            $found = false;
            $coordinates_selected = unserialize($performance_data_selected->coordinates);
            /* some details to show */
                $performance_data_selected->coordinates_details = $coordinates_selected;
                if($performance_data_selected->status==1){
                    $performance_data_selected->status = "Completed";
                }
                else{
                    $performance_data_selected->status = "Incomplete";
                }
                // for attributes
                $attributes = unserialize($performance_data_selected->scheme_attributes); // getting attrubutes
                $performance_attributes = [];
                $per_attr_tmps = unserialize($performance_data_selected->attribute);
                foreach ($per_attr_tmps as $per_attr_tmp) {
                    $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
                }
                $tmp = '';
                foreach($attributes as $attribute){
                    $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
                }
                $performance_data_selected->attribute_details = $tmp;
            /* some details to show */
            if(count($coordinates_selected) > 0)
            {
                foreach($performance_datas_to_test as $performance_data_to_test){
                    $coordinates_to_test = unserialize($performance_data_to_test->coordinates);
                    if(count($coordinates_to_test) > 0 && $performance_data_selected->scheme_performance_id!=$performance_data_to_test->scheme_performance_id)
                    {
                        /* some details to show */
                            $performance_data_to_test->coordinates_details = $coordinates_to_test;
                            if($performance_data_to_test->status==1){
                                $performance_data_to_test->status = "Completed";
                            }
                            else{
                                $performance_data_to_test->status = "Incomplete";
                            }
                            // for attributes
                            $attributes = unserialize($performance_data_to_test->scheme_attributes); // getting attrubutes
                            $performance_attributes = [];
                            $per_attr_tmps = unserialize($performance_data_to_test->attribute);
                            foreach ($per_attr_tmps as $per_attr_tmp) {
                                $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
                            }
                            $tmp = '';
                            foreach($attributes as $attribute){
                                $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
                            }
                            $performance_data_to_test->attribute_details = $tmp;
                        /* some details to show */


                        // testing first & last point within a radius
                        if($this->test_first_and_last_point($coordinates_selected, $coordinates_to_test, $distance_to_measure)){ // matched
                            if($this->test_all_coordinates($coordinates_selected, $coordinates_to_test, $distance_to_measure)){
                                echo "yes\n";
                                array_push($datas_tmp, $performance_data_to_test);
                                $found = true;
                            }
                            else{
                                // no duplicate
                            }
                        }
                        else{ // not matched
                            // testing if any point is withing a  distance (1KM)
                            if($this->test_distance_if_any($coordinates_selected, $coordinates_to_test, 1000)){ // yes, inside
                                if(count($coordinates_selected)>1 && count($coordinates_to_test)>1) // if more then one coordinates has
                                {
                                    if($this->direction_wise_check_duplicate($coordinates_selected, $coordinates_to_test)){ // this will test and prepare percentage for changes of same direction
                                        
                                    }
                                    else{
                                        // no duplicate
                                    }
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
        // print_r($duplicate_datas);
        // exit();

        return ["duplicate_datas"=>$duplicate_datas, "response"=>$response, 'count'=>count($duplicate_datas)];
    }

    public function test_first_and_last_point($coordinates_selected_datas, $coordinates_to_test_datas, $distance_to_measure){
        // first test
        if(count($coordinates_selected_datas) == 1 || count($coordinates_to_test_datas) == 1){
            $distance = $this->get_distance($coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[0]["longitude"], $coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[0]["longitude"]);
            if($distance<=$distance_to_measure){
                return true;
            }
        }
        else{
            $distance_1 = $this->get_distance($coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[0]["longitude"], $coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[0]["longitude"]);
            $distance_2 = $this->get_distance($coordinates_selected_datas[count($coordinates_selected_datas) - 1]["latitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["longitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["latitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["longitude"]);
            if($distance_1<=$distance_to_measure && $distance_2<=$distance_to_measure){
                // echo $distance_1." - ".$distance_2."\n";
                return true;
            }
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
                    echo $key_1." - ".$key_2.", Distance: ".$d."\n";;
                }
            }
        }

        $sqrt = round(sqrt($total_test_count));
        echo $total_test_match." - ".sqrt($total_test_count)."\n\n";
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
        // $angle = rad2deg(atan2(long2-long1, lat2-lat1));

        $chances = 0;

        // level 1: test if initial point-to-final-point have same inclination (angle)
        $angle_1 = $this->get_angle_between_points($coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[0]["latitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["latitude"], $coordinates_selected_datas[count($coordinates_selected_datas) - 1]["longitude"]);
        $angle_2 = $this->get_angle_between_points($coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[0]["latitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["latitude"], $coordinates_to_test_datas[count($coordinates_to_test_datas) - 1]["longitude"]);
        $angle_1 = round(abs($angle_1));
        $angle_2 = round(abs($angle_2));
        if($angle_1==$angle2 || $angle_1>($angle_2-10) || $angle_2<($angle_2-10)){
            return true;
        }
        else{
            return false;
        }
        
        // level 2: test no of coordinates
                    // same: analyse and test each inclination
                    // not same: analyse and test each inclination serial wise
    }

    public function get_angle_between_points($lat1, $long1, $lat2, $long2){
        $angle = rad2deg(atan2($long2-$long1, $lat2-$lat));
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
