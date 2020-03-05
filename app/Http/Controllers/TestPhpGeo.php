<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use DB;
use App\SchemePerformance;

// for phpgeo
use Location\Coordinate;
use Location\Distance\Vincenty;
use Location\Distance\Haversine;
use Location\Bearing\BearingSpherical;
use Location\Bearing\BearingEllipsoidal;
use Location\Formatter\Coordinate\DecimalDegrees;
use Location\Polygon;

class TestPhpGeo extends Controller
{
    //
    public function index(){
        $distance_to_measure = 10;
        $performance_datas_selected = SchemePerformance::where('scheme_performance.scheme_performance_id', 119)
            ->first();
        $performance_datas_to_test = SchemePerformance::where('scheme_performance.scheme_performance_id', 120)
            ->first();
        $coordinates_selected = unserialize($performance_datas_selected->coordinates);
        $coordinates_to_test = unserialize($performance_datas_to_test->coordinates);
        // $coordinates_selected = [
        //     ["latitude"=>22.795197, "longitude"=>86.136507],
        //     ["latitude"=>22.797086, "longitude"=>86.137886]
        // ];
        // $coordinates_to_test = $coordinates_selected;
        // $coordinates_to_test = [
        //     ["latitude"=>22.806856, "longitude"=>86.197803],
        //     ["latitude"=>22.808607, "longitude"=>86.199841]
        // ];

        $percentage = [];
        for($test_case=1;$test_case<=2;$test_case++)
        {
            if($test_case==2){ // reverse testing
                $tmp_coordinates = $coordinates_selected;
                $coordinates_selected = $coordinates_to_test;
                $coordinates_to_test = $tmp_coordinates;
            }
            $left_right_coordinates = [];
            for($i=0;$i<count($coordinates_selected);$i++){
                if($i==0 || $i==(count($coordinates_selected)-1)){
                    if($i==0){ // first point
                        $coordinate_start = new Coordinate($coordinates_selected[$i]["latitude"], $coordinates_selected[$i]["longitude"]);
                        $coordinate_end = new Coordinate($coordinates_selected[$i+1]["latitude"], $coordinates_selected[$i+1]["longitude"]);
                        // to change start point, from distance_to_measure backward
                        $distance_tmp = $this->get_distance($coordinates_selected[$i]["latitude"], $coordinates_selected[$i]["longitude"], $coordinates_selected[$i+1]["latitude"], $coordinates_selected[$i+1]["longitude"]);
                        $bearing_angle_tmp = $this->get_angle($coordinates_selected[$i]["latitude"], $coordinates_selected[$i]["longitude"], $coordinates_selected[$i+1]["latitude"], $coordinates_selected[$i+1]["longitude"]);
                        $bearingSpherical = new BearingSpherical();
                        $destination_1 = $bearingSpherical->calculateDestination($coordinate_end, $bearing_angle_tmp, ($distance_tmp + $distance_to_measure));
                        $tmp_coordinates = explode(',', $destination_1->format(new DecimalDegrees(',',6)));
                        $coordinate_start = new Coordinate($tmp_coordinates[0], $tmp_coordinates[1]);
                    }
                    else // last point
                    {
                        $coordinate_start = new Coordinate($coordinates_selected[$i]["latitude"], $coordinates_selected[$i]["longitude"]);
                        $coordinate_end = new Coordinate($coordinates_selected[$i-1]["latitude"], $coordinates_selected[$i-1]["longitude"]);
                        // to change start point, from distance_to_measure backward
                        $distance_tmp = $this->get_distance($coordinates_selected[$i]["latitude"], $coordinates_selected[$i]["longitude"], $coordinates_selected[$i-1]["latitude"], $coordinates_selected[$i-1]["longitude"]);
                        $bearing_angle_tmp = $this->get_angle($coordinates_selected[$i]["latitude"], $coordinates_selected[$i]["longitude"], $coordinates_selected[$i-1]["latitude"], $coordinates_selected[$i-1]["longitude"]);
                        $bearingSpherical = new BearingSpherical();
                        $destination_1 = $bearingSpherical->calculateDestination($coordinate_end, $bearing_angle_tmp, ($distance_tmp + $distance_to_measure));
                        $tmp_coordinates = explode(',', $destination_1->format(new DecimalDegrees(',',6)));
                        $coordinate_start = new Coordinate($tmp_coordinates[0], $tmp_coordinates[1]);
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
                    $coordinate_start = new Coordinate($coordinates_selected[$i-1]["latitude"], $coordinates_selected[$i-1]["longitude"]);
                    $coordinate_center = new Coordinate($coordinates_selected[$i]["latitude"], $coordinates_selected[$i]["longitude"]);
                    $coordinate_end = new Coordinate($coordinates_selected[$i+1]["latitude"], $coordinates_selected[$i+1]["longitude"]);
                    $bearingCalculator = new BearingSpherical();
                    $bearing_angle_1 = $bearingCalculator->calculateBearing($coordinate_center, $coordinate_start);
                    $bearing_angle_2 = $bearingCalculator->calculateBearing($coordinate_center, $coordinate_end);

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

                    echo $bearing_angle_1.",".$bearing_angle_2."<br/><br/>";
                    
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
            echo "<pre>";
            // print_r($coordinates_polygon);
            $coordinates_polygon = [];
            for($i=0;$i<count($left_right_coordinates);$i++){
                if($i==0||$i==count($left_right_coordinates)-1)
                {
                    echo "{lat: ".implode(', lng: ',$left_right_coordinates[$i][0])."},\n";
                    echo "{lat: ".implode(', lng: ',$left_right_coordinates[$i][1])."},\n";
                    $coordinates_polygon[] = $left_right_coordinates[$i][0];
                    $coordinates_polygon[] = $left_right_coordinates[$i][1];
                }
                else{
                    echo "{lat: ".implode(', lng: ',$left_right_coordinates[$i][1])."},\n";
                    $coordinates_polygon[] = $left_right_coordinates[$i][1];
                }
            }
            for($j=(count($left_right_coordinates)-2);$j>0;$j--){
                echo "{lat: ".implode(', lng: ',$left_right_coordinates[$j][0])."},\n";
                $coordinates_polygon[] = $left_right_coordinates[$j][0];
            }
            // print_r($coordinates_polygon);



            // maming polygon
            $geofence = new Polygon();
            for($i=0;$i<count($coordinates_polygon);$i++){
                $geofence->addPoint(new Coordinate($coordinates_polygon[$i]["latitude"],$coordinates_polygon[$i]["longitude"]));
            }
            // testing inside or not
            $inside_index = 0;
            for($i=0;$i<count($coordinates_to_test);$i++){
                $insidePoint = new Coordinate($coordinates_to_test[$i]["latitude"], $coordinates_to_test[$i]["longitude"]);
                if($geofence->contains($insidePoint)){
                    echo $i."<br/>";
                    $inside_index += 1;
                }
            }
            // calculating percentage
            $percentage_tmp = ($inside_index / count($coordinates_to_test))*100;
            echo $percentage_tmp;
            $percentage[] = $percentage_tmp;
        }

        if($percentage[0]>$percentage[1]){
            echo $percentage[0];
        }
        else{
            echo $percentage[1];
        }


        // $coordinate1 = new Coordinate(22.799273, 86.192071); // Mauna Kea Summit
        // $coordinate2 = new Coordinate(22.813701, 86.202937); // Haleakala Summit
        // $coordinate3 = new Coordinate(22.820627, 86.196765); // Haleakala Summit
        // $bearingCalculator = new BearingSpherical();
        // $bearing_angle_1 = $bearingCalculator->calculateBearing($coordinate2, $coordinate1);
        // $bearing_angle_2 = $bearingCalculator->calculateBearing($coordinate2, $coordinate3);
        // echo $bearing_angle_1." - ".$bearing_angle_2."<br/>";
        // $angle_left = (($bearing_angle_2-$bearing_angle_1)/2)+$bearing_angle_1;
        // echo $angle_left."<br/>".(360-$angle_left);

        // // find left, right coordinates
        // $angle_left = $angle_right = 0;
        // // left
        // $angle_left = $bearing_angle - 90;
        // if($angle_left<0){
        //     $angle_left = 360+($angle_left);
        // }
        // // right
        // $angle_right = $bearing_angle + 90;
        // if($angle_right>360){
        //     $angle_right = $tmp-360;
        // }
        // echo $angle_left." - ".$angle_right."<br/>";

        // $BearingSpherical = new BearingSpherical();
        // $coordinates_left = $BearingSpherical->calculateDestination($coordinate1, $angle_left, 50);
        // $coordinates_right = $BearingSpherical->calculateDestination($coordinate1, $angle_right, 50);
        // echo "Spherical:   " . $coordinates_right->format(new DecimalDegrees(',',6)) . PHP_EOL;
        // exit();
    }

    public function get_distance($lat1, $long1, $lat2, $long2){
        $coordinate1 = new Coordinate($lat1, $long1);
        $coordinate2 = new Coordinate($lat2, $long2);
        return $coordinate1->getDistance($coordinate2, new Haversine());
    }

    public function get_angle($lat1, $long1, $lat2, $long2){
        $coordinate_start = new Coordinate($lat1, $long1);
        $coordinate_end = new Coordinate($lat2, $long2);
        $bearingCalculator = new BearingSpherical();
        return $bearingCalculator->calculateBearing($coordinate_end, $coordinate_start);
    }
}
