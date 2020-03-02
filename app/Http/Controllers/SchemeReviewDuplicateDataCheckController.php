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
use App\CheckMatchingPerformance;
use App\Group;
use App\Uom;
use DB;
use Auth;

// for phpgeo
use Location\Coordinate;
use Location\Distance\Vincenty;
use Location\Distance\Haversine;
use Location\Bearing\BearingSpherical;
use Location\Bearing\BearingEllipsoidal;
use Location\Formatter\Coordinate\DecimalDegrees;
use Location\Polygon;

class SchemeReviewDuplicateDataCheckController extends Controller
{
    // https://phpgeo.marcusjaschen.de/Installation.html
    // https://github.com/anthonymartin/GeoLocation-PHP
    // https://github.com/thephpleague/geotools/blob/master/README.md
    //
    public function index()
    {
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod23"]["add"]&&!$desig_permissions["mod23"]["edit"]&&!$desig_permissions["mod23"]["view"]&&!$desig_permissions["mod23"]["del"]){
            return back();
        }
        $year_datas = Year::select('year_id', 'year_value')->where('status', 1)->get();
        $scheme_datas = SchemeStructure::select('scheme_id', 'scheme_asset_id', 'scheme_name', 'scheme_is', 'scheme_short_name')->where('status', 1)->get();
        $scheme_asset_datas = SchemeAsset::select('scheme_asset_id', 'scheme_asset_name')->get();
        $uom = Uom::where('uom_type_id', 1)->select('uom_id','uom_name')->get();

        return view('scheme-review.duplicate-data-check')->with(compact('year_datas', 'scheme_datas', 'scheme_asset_datas', 'uom'));
    }


    // get datas for duplicate review
    public function get_datas(Request $request)
    {
        // unit related
        // 1 ft = 0.3048 m
        // 1 inch = 0.0254 m;
        $uom = $request->uom;
        $conversion_unit = Uom::find($uom)->conversion_unit; // pending: from uom table
        $distance_to_measure = (float)($request->distance_to_measure * $conversion_unit);

        // to send back
        $duplicate_datas = [];

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
        if ($request->scheme_id) {
            $scheme_ids_selected = [$request->scheme_id];
        } else {
            $scheme_ids_selected = $scheme_ids_to_test;
        }

        // year_id
        $year_ids_to_test = Year::get()->pluck('year_id');
        if ($request->year_id) {
            $year_ids_selected = [$request->year_id];
        } else {
            $year_ids_selected = $year_ids_to_test;
        }

        // scheme_asset_id
        $scheme_asset_id_selected = $request->scheme_asset_id;

        // panchayat id
        $panchayat_ids_to_test = GeoStructure::where('level_id', 4)->get()->pluck('geo_id');
        if ($request->panchayat_id) {
            $panchayat_ids_selected = explode(",", $request->panchayat_id);
        } else {
            $panchayat_ids_selected = $panchayat_ids_to_test;
        }

        $performance_datas_selected = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
            ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
            ->LeftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
            ->LeftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
            ->select('scheme_performance.*', 'scheme_assets.scheme_asset_name', 'geo_structure.geo_name as panchayat_name', 'year.year_value', 'scheme_structure.scheme_short_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_is', 'scheme_structure.scheme_map_marker', 'scheme_structure.attributes as scheme_attributes')
            ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
            ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected)
            ->whereIn('scheme_performance.scheme_id', $scheme_ids_selected)
            ->whereIn('scheme_performance.year_id', $year_ids_selected)
            // ->where('scheme_performance.scheme_performance_id', 40)
            ->get();
        $performance_datas_to_test = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
            ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
            ->LeftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
            ->LeftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
            ->select('scheme_performance.*', 'scheme_assets.scheme_asset_name', 'geo_structure.geo_name as panchayat_name', 'year.year_value', 'scheme_structure.scheme_short_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_is', 'scheme_structure.scheme_map_marker', 'scheme_structure.attributes as scheme_attributes')
            ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
            ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected)
            // ->where('scheme_performance.scheme_performance_id', 41)
            ->get();

        /* 
        actual testing started
        */
        foreach ($performance_datas_selected as $performance_data_selected) {
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
            foreach ($attributes as $attribute) {
                array_push($tmp, [$attribute['name'], $performance_attributes[$attribute['id']]]);
                // $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
            }
            $performance_data_selected->attribute_details = $tmp;


            // for scheme name
            $performance_data_selected->scheme_name = "(" . $performance_data_selected->scheme_short_name . ") " . $performance_data_selected->scheme_name;

            // for gallery
            $map_datas_tmp["gallery"] = unserialize($performance_data_selected->gallery);

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
            if (count($coordinates_selected) > 0) {
                foreach ($performance_datas_to_test as $performance_data_to_test) {
                    $coordinates_to_test = unserialize($performance_data_to_test->coordinates);
                    if (count($coordinates_to_test) > 0 && $performance_data_selected->scheme_performance_id != $performance_data_to_test->scheme_performance_id) {
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
                        foreach ($attributes as $attribute) {
                            array_push($tmp, [$attribute['name'], $performance_attributes[$attribute['id']]]);
                            // $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
                        }
                        $performance_data_to_test->attribute_details = $tmp;


                        // for scheme name
                        // $performance_data_to_test->scheme_name = "(".$performance_data_to_test->scheme_short_name.") ".$performance_data_to_test->scheme_name;
                        $performance_data_to_test->scheme_name = $performance_data_to_test->scheme_name;

                        // for gallery
                        $map_datas_tmp["gallery"] = unserialize($performance_data_selected->gallery);

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
                        /*** some details to show ***/

                        /* Testing duplicacy */
                        if (count($coordinates_selected) == 1 || count($coordinates_to_test) == 1) {
                            $distance = $this->get_distance($coordinates_selected[0]["latitude"], $coordinates_selected[0]["longitude"], $coordinates_to_test[0]["latitude"], $coordinates_to_test[0]["longitude"]);
                            if ($distance <= $distance_to_measure) {
                                array_push($datas_tmp, $performance_data_to_test);
                                $found = true;
                            } else {
                                // no duplicate
                            }
                        } 
                        else {
                            if ($this->test_distance_if_any($coordinates_selected, $coordinates_to_test, 1000)) { // to find data that are close (1KM)
                                $percentage = $this->check_duplicacy_by_polygon($coordinates_selected, $coordinates_to_test, $distance_to_measure); // will return chances in percentage
                                if($percentage>80){ // greater than 80% is assigned as duplicate
                                    array_push($datas_tmp, $performance_data_to_test);
                                    $found = true;
                                }
                            }
                            else{
                                // dont test further, testing coordinate is far than 1KM
                            }
                        }
                    }
                }
            }

            if ($found) {
                array_push($duplicate_datas, array_merge([$performance_data_selected], $datas_tmp));
            }
        }

        if (count($duplicate_datas) > 0) {
            $response = "success";
        } else {
            $response = "no_data";
        }

        return ["duplicate_datas" => $duplicate_datas, "response" => $response, 'count' => count($duplicate_datas)];
    }

    public function check_duplicacy_by_polygon($coordinates_selected_datas, $coordinates_to_test_datas, $distance_to_measure){
        // data received
        // $coordinates_selected_datas;
        // $coordinates_to_test_datas;

        $left_right_coordinates = [];
        for($i=0;$i<count($coordinates_selected_datas);$i++){
            if($i==0 || $i==(count($coordinates_selected_datas)-1)){
                if($i==0){
                    $coordinate_start = new Coordinate($coordinates_selected_datas[$i]["latitude"], $coordinates_selected_datas[$i]["longitude"]);
                    $coordinate_end = new Coordinate($coordinates_selected_datas[$i+1]["latitude"], $coordinates_selected_datas[$i+1]["longitude"]);
                }
                else
                {
                    $coordinate_start = new Coordinate($coordinates_selected_datas[$i]["latitude"], $coordinates_selected_datas[$i]["longitude"]);
                    $coordinate_end = new Coordinate($coordinates_selected_datas[$i-1]["latitude"], $coordinates_selected_datas[$i-1]["longitude"]);
                }

                $bearingCalculator = new BearingSpherical();
                $bearing_angle = $bearingCalculator->calculateBearing($coordinate_start, $coordinate_end);

                // find left, right coordinates
                $angle_left = $angle_right = 0;
                // left
                $angle_left = $bearing_angle - 90;
                if($angle_left<0){
                    $angle_left = 360+($angle_left);
                }
                // right
                $angle_right = $bearing_angle + 90;
                if($angle_right>360){
                    $angle_right = $tmp-360;
                }

                $BearingSpherical = new BearingSpherical();
                $coordinates_left = $BearingSpherical->calculateDestination($coordinate_start, $angle_left, $distance_to_measure);
                $coordinates_right = $BearingSpherical->calculateDestination($coordinate_start, $angle_right, $distance_to_measure);
                $tmp_left = explode(',', $coordinates_left->format(new DecimalDegrees(',',6)));
                $tmp_right = explode(',', $coordinates_right->format(new DecimalDegrees(',',6)));
                $left_right_coordinates[] = [
                    ["latitude"=>$tmp_left[0],"longitude"=>$tmp_left[1]],
                    ["latitude"=>$tmp_right[0],"longitude"=>$tmp_right[1]]
                ];
            }
            else{
                $coordinate_start = new Coordinate($coordinates_selected_datas[$i-1]["latitude"], $coordinates_selected_datas[$i-1]["longitude"]);
                $coordinate_center = new Coordinate($coordinates_selected_datas[$i]["latitude"], $coordinates_selected_datas[$i]["longitude"]);
                $coordinate_end = new Coordinate($coordinates_selected_datas[$i+1]["latitude"], $coordinates_selected_datas[$i+1]["longitude"]);
                $bearingCalculator = new BearingSpherical();
                $bearing_angle_1 = $bearingCalculator->calculateBearing($coordinate_center, $coordinate_start);
                $bearing_angle_2 = $bearingCalculator->calculateBearing($coordinate_center, $coordinate_end);

                // find left, right coordinates
                if($bearing_angle_1>$bearing_angle_2){
                    $angle_right = (($bearing_angle_1-$bearing_angle_2)/2)+$bearing_angle_2;
                    if($angle_right>180){
                        $angle_left = $angle_right - 180;
                    }
                    else{
                        $angle_left = $angle_right + 180;
                    }
                }
                else{
                    $angle_left = (($bearing_angle_2-$bearing_angle_1)/2)+$bearing_angle_1;
                    if($angle_left>180){
                        $angle_right = $angle_left - 180;
                    }
                    else{
                        $angle_right = $angle_left + 180;
                    }
                }
                
                $BearingSpherical = new BearingSpherical();
                $coordinates_left = $BearingSpherical->calculateDestination($coordinate_center, $angle_left, $distance_to_measure);
                $coordinates_right = $BearingSpherical->calculateDestination($coordinate_center, $angle_right, $distance_to_measure);
                $tmp_left = explode(',', $coordinates_left->format(new DecimalDegrees(',',6)));
                $tmp_right = explode(',', $coordinates_right->format(new DecimalDegrees(',',6)));
                $left_right_coordinates[] = [
                    ["latitude"=>$tmp_left[0],"longitude"=>$tmp_left[1]],
                    ["latitude"=>$tmp_right[0],"longitude"=>$tmp_right[1]]
                ];
            }
        }
        // print_r($coordinates_polygon);
        $coordinates_polygon = [];
        for($i=0;$i<count($left_right_coordinates);$i++){
            if($i==0||$i==count($left_right_coordinates)-1)
            {
                // echo "{lat: ".implode(', lng: ',$left_right_coordinates[$i][0])."},\n";
                // echo "{lat: ".implode(', lng: ',$left_right_coordinates[$i][1])."},\n";
                $coordinates_polygon[] = $left_right_coordinates[$i][0];
                $coordinates_polygon[] = $left_right_coordinates[$i][1];
            }
            else{
                // echo "{lat: ".implode(', lng: ',$left_right_coordinates[$i][1])."},\n";
                $coordinates_polygon[] = $left_right_coordinates[$i][1];
            }
        }
        for($j=(count($left_right_coordinates)-2);$j>0;$j--){
            // echo "{lat: ".implode(', lng: ',$left_right_coordinates[$j][0])."},\n";
            $coordinates_polygon[] = $left_right_coordinates[$j][0];
        }



        // draw polygon
        $geofence = new Polygon();
        for($i=0;$i<count($coordinates_polygon);$i++){
            $geofence->addPoint(new Coordinate($coordinates_polygon[$i]["latitude"],$coordinates_polygon[$i]["longitude"]));
        }
        // testing inside or not
        $inside_index = 0;
        for($i=0;$i<count($coordinates_to_test_datas);$i++){
            $insidePoint = new Coordinate($coordinates_to_test_datas[$i]["latitude"], $coordinates_to_test_datas[$i]["longitude"]);
            if($geofence->contains($insidePoint)){
                $inside_index += 1;
            }
        }
        // calculating percentage
        $percentage = ($inside_index / count($coordinates_to_test_datas))*100;
        return $percentage;
    }

    // test if any point is within 1KM (1000m) from selected
    public function test_distance_if_any($coordinates_selected_datas, $coordinates_to_test_datas, $distance_to_measure)
    {
        // $distance_to_measure (1KM i.e. 1000)
        foreach ($coordinates_selected_datas as $coordinates_selected_data) {
            $coordinate1 = new Coordinate($coordinates_selected_data["latitude"], $coordinates_selected_data["longitude"]);
            foreach ($coordinates_to_test_datas as $coordinates_to_test_data) {
                $coordinate2 = new Coordinate($coordinates_to_test_data["latitude"], $coordinates_to_test_data["longitude"]);
                $d = $coordinate1->getDistance($coordinate2, new Haversine());
                if ($d < $distance_to_measure) {
                    return true;
                }
            }
        }

        return false;
    }

    // get distance from 1 point to another
    public function get_distance($lat1, $long1, $lat2, $long2){
        $coordinate1 = new Coordinate($lat1, $long1);
        $coordinate2 = new Coordinate($lat2, $long2);
        return $coordinate1->getDistance($coordinate2, new Haversine());
    }

    public function get_duplicate_scheme_perfomamce($id,$uom,$distance_to_measure)
    {
        
        // $uom = $request->uom;
        $conversion_unit = 1; // pending: from uom table
        $distance_to_measure = $distance_to_measure * $conversion_unit;


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
        $scheme_perfomance_details=SchemePerformance::where('scheme_performance_id',$id)->first();
        // for scheme_id
        // return $scheme_perfomance_details;
        // $scheme_ids_to_test = SchemeStructure::get()->pluck('scheme_id');
        if ($scheme_perfomance_details->scheme_id) {
            $scheme_ids_selected = [$scheme_perfomance_details->scheme_id];
        } else {
            $scheme_ids_selected = $scheme_ids_to_test;
        }

        // year_id
        $year_ids_to_test = Year::get()->pluck('year_id');
        if ($scheme_perfomance_details->year_id) {
            $year_ids_selected = [$scheme_perfomance_details->year_id];
        } else {
            $year_ids_selected = $year_ids_to_test;
        }

        // scheme_asset_id
        $scheme_asset_id_selected = $scheme_perfomance_details->scheme_asset_id;
        // panchayat id
        $panchayat_ids_to_test = GeoStructure::where('level_id', 4)->get()->pluck('geo_id');
        if ($scheme_perfomance_details->panchayat_id) {
            $panchayat_ids_selected = explode(",", $scheme_perfomance_details->panchayat_id);
        } else {
            $panchayat_ids_selected = $panchayat_ids_to_test;
        }

        $performance_datas_selected = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
            ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
            ->LeftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
            ->LeftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
            ->select('scheme_performance.*', 'scheme_assets.scheme_asset_name', 'geo_structure.geo_name as panchayat_name', 'year.year_value', 'scheme_structure.scheme_short_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_is', 'scheme_structure.scheme_map_marker', 'scheme_structure.attributes as scheme_attributes')
            ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
            ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected)
            ->where('scheme_performance.scheme_performance_id',$id)
            ->get();
        $performance_datas_to_test = SchemePerformance::LeftJoin('scheme_assets', 'scheme_performance.scheme_asset_id', '=', 'scheme_assets.scheme_asset_id')
            ->LeftJoin('geo_structure', 'scheme_performance.panchayat_id', '=', 'geo_structure.geo_id')
            ->LeftJoin('year', 'scheme_performance.year_id', '=', 'year.year_id')
            ->LeftJoin('scheme_structure', 'scheme_performance.scheme_id', '=', 'scheme_structure.scheme_id')
            ->select('scheme_performance.*', 'scheme_assets.scheme_asset_name', 'geo_structure.geo_name as panchayat_name', 'year.year_value', 'scheme_structure.scheme_short_name', 'scheme_structure.scheme_name', 'scheme_structure.scheme_is', 'scheme_structure.scheme_map_marker', 'scheme_structure.attributes as scheme_attributes')
            ->whereIn('scheme_performance.panchayat_id', $panchayat_ids_selected)
            ->where('scheme_performance.scheme_asset_id', $scheme_asset_id_selected)
            // ->where('scheme_performance.scheme_performance_id', 32)
            ->get();

        // echo "<pre>";    
        // print_r($performance_datas_selected);
        // exit();
        /* 
        actual testing started
        */
        foreach ($performance_datas_selected as $performance_data_selected) {
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
            foreach ($attributes as $attribute) {
                array_push($tmp, [$attribute['name'], $performance_attributes[$attribute['id']]]);
                // $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
            }
            $performance_data_selected->attribute_details = $tmp;


            // for scheme name
            $performance_data_selected->scheme_name = "(" . $performance_data_selected->scheme_short_name . ") " . $performance_data_selected->scheme_name;

            // for gallery
            $map_datas_tmp["gallery"] = unserialize($performance_data_selected->gallery);

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
            if (count($coordinates_selected) > 0) {
                foreach ($performance_datas_to_test as $performance_data_to_test) {
                    $coordinates_to_test = unserialize($performance_data_to_test->coordinates);
                    if (count($coordinates_to_test) > 0 && $performance_data_selected->scheme_performance_id != $performance_data_to_test->scheme_performance_id) {
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
                        foreach ($attributes as $attribute) {
                            array_push($tmp, [$attribute['name'], $performance_attributes[$attribute['id']]]);
                            // $tmp .= ''.$attribute['name'].': '.$performance_attributes[$attribute['id']].'<br/>';
                        }
                        $performance_data_to_test->attribute_details = $tmp;


                        // for scheme name
                        // $performance_data_to_test->scheme_name = "(".$performance_data_to_test->scheme_short_name.") ".$performance_data_to_test->scheme_name;
                        $performance_data_to_test->scheme_name = $performance_data_to_test->scheme_name;

                        // for gallery
                        $map_datas_tmp["gallery"] = unserialize($performance_data_selected->gallery);

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

                        /* Testing duplicacy */
                        if (count($coordinates_selected) == 1 || count($coordinates_to_test) == 1) {
                            $distance = $this->get_distance($coordinates_selected[0]["latitude"], $coordinates_selected[0]["longitude"], $coordinates_to_test[0]["latitude"], $coordinates_to_test[0]["longitude"]);
                            if ($distance <= $distance_to_measure) {
                                array_push($datas_tmp, $performance_data_to_test);
                                $found = true;
                            } else {
                                // no duplicate
                            }
                        } 
                        else {
                            if ($this->test_distance_if_any($coordinates_selected, $coordinates_to_test, 1000)) { // to find data that are close (1KM)
                                $percentage = $this->check_duplicacy_by_polygon($coordinates_selected, $coordinates_to_test, $distance_to_measure); // will return chances in percentage
                                if($percentage>80){ // greater than 80% is assigned as duplicate
                                    array_push($datas_tmp, $performance_data_to_test);
                                    $found = true;
                                }
                            }
                            else{
                                // dont test further, testing coordinate is far than 1KM
                            }
                        }
                    }
                }
            }

            if ($found) {
                array_push($duplicate_datas, array_merge([$performance_data_selected], $datas_tmp));
            }
        }

        if (count($duplicate_datas) > 0) {
            $response = "success";
        } else {
            $response = "no_data";
        }

        // echo "<pre>";
        // // print_r($duplicate_datas);
        // echo count($duplicate_datas)."\n";
        // // exit();
        // print_r($duplicate_datas);
        // exit();

        return ["duplicate_datas" => $duplicate_datas, "response" => $response, 'count' => count($duplicate_datas)];
    }

    public function insert_mathcingperformance($id = "", $result = "")
    {
        $uom=1;
        $distance_to_measure=10;
        $duplicate_datas_record=$this->get_duplicate_scheme_perfomamce($id,$uom,$distance_to_measure);
        // echo "<pre>";
        // print_r($duplicate_datas_record['response']);
        // exit; 
        $temp_matching_performance_id=array();
        if($duplicate_datas_record['response']=="success")
        {
            foreach($duplicate_datas_record['duplicate_datas'][0] as $key_duplicate=>$value_duplicate)
            {
                $temp_matching_performance_id[]=$value_duplicate['scheme_performance_id'];
            }
            // print_r($temp_matching_performance_id);
            if(count($temp_matching_performance_id)>1)
            {
            $matching_performance_id=array_slice($temp_matching_performance_id,1);
            // print_r($matching_performance_id);
            // exit;
            if ($result == "true") {
                $SchemePerformance_deatails = SchemePerformance::where('scheme_performance_id', $id)->first();
                $CheckMatchingPerformance = new CheckMatchingPerformance();
                $CheckMatchingPerformance->scheme_performance_id = $id;
                $CheckMatchingPerformance->matching_performance_id =implode(",",$matching_performance_id) ?? "";
                $CheckMatchingPerformance->probable_duplicate=implode(",",$matching_performance_id)??"";
                $CheckMatchingPerformance->status = $SchemePerformance_deatails->status;
                $CheckMatchingPerformance->created_by = Auth::user()->id;
                $CheckMatchingPerformance->updated_by = Auth::user()->id;
                $CheckMatchingPerformance->save();
            }
            return ["CheckMatchingPerformance" => $CheckMatchingPerformance,"message"=>"data Found"];
            }
        }
        return ["CheckMatchingPerformance" => "No Found","message"=>"data Not Found"];
    }
}
