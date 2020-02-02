<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeoStructure;
use App\Asset;
use App\SchemeStructure;
use App\Department;
use App\DesignationPermission;
use App\SchemePerformance;
use App\Fav_Scheme;
use App\Fav_Block;
use App\Year;
use App\scheme_block_performance;
use DB;
use App\Languages;

class DashboardController extends Controller
{
    // function to assign everything related to user logged in (after logged in user id redirected to this method, this method further redirected usaer to its dashboadr page)
    public function index()
    {
        


         // session store user details
        if (Auth::check()) {
            if(Auth::user()->status==0)
            {
                session()->flush();
                return redirect()->back();
            }
            session()->put('user_id', Auth::user()->id);
            session()->put('user_full_name', Auth::user()->title." ".Auth::user()->first_name . " " . Auth::user()->last_name);
            session()->put('user_org_id', Auth::user()->org_id);
            session()->put('user_designation', Auth::user()->userRole);
            switch (Auth::user()->userRole) {
                case "1":
                    session()->put('user_designation_name', "I.A.S");
                    session()->put('dashboard_title', "My District");
                    session()->put('dashboard_url', "my-district");
                    break;
                case "2":
                    session()->put('user_designation_name', "SDO");
                    session()->put('dashboard_title', "My SubDivision");
                    session()->put('dashboard_url', "my-subdivision");
                    break;
                case "3":
                    session()->put('user_designation_name', "BDO");
                    session()->put('dashboard_title', "My Block");
                    session()->put('dashboard_url', "my-block");
                    break;
                case "4":
                    session()->put('user_designation_name', "PO");
                    session()->put('dashboard_title', "My Panchayat");
                    session()->put('dashboard_url', "my-panchayat");
                    break;
            }

            // assigning designation permission
            $desig_permission_datas = DesignationPermission::leftjoin("module", "desig_permission.mod_id", "=", "module.mod_id")->select("module.mod_id", "module.mod_name", "desig_permission.add", "desig_permission.edit", "desig_permission.view", "desig_permission.del")->where("desig_id", session()->get('user_designation'))->get();
            $desig_permission = array(); // ['module_id'=>[add, edit, view, delete], "module_id"=>[add, edit, view, delete]]
            foreach ($desig_permission_datas as $data) {
                $tmp = ["mod" . $data->mod_id . "" => ["add" => $data->add, "edit" => $data->edit, "view" => $data->view, "del" => $data->del]];
                $desig_permission = array_merge($desig_permission, $tmp);
            }
            // return $desig_permission["mod_1"];
            session()->put('desig_permission', $desig_permission);

            if (session()->exists('designation_permission_changes')) {
                session()->forget('designation_permission_changes');
                return redirect('designation-permission');
            }

            return redirect(session()->get('dashboard_url')); // redirecting back after sucessfully 
        } else { // redirect if not logged in
            session()->flush();
            return redirect()->route("login");
        }
    }

    // show dashboard contents
    public function dashboard()
    {
        // return session()->get('desig_permission');
 
        $transdate = date('m-d-Y');
        $month = date('m', strtotime($transdate));
        if($month>3)
        {
           
            $new_year=date("Y",strtotime("+1 year"));
            $current_year=date('Y');
         $year_date=$current_year."-".$new_year;
         $year_details = Year::where('year_value',$year_date)->first();
         if($year_details!="")
         {
         $year_id=$year_details->year_id;
         }
         else
         {
             $unfound_date=$year_date;
            $previous_year=date("Y",strtotime("-1 year"));
            $current_year=date('Y');
            $year_date=$previous_year."-".$current_year;
            $year_details = Year::where('year_value',$year_date)->first();
            $year_id=$year_details->year_id;
            $message_year=$unfound_date."This Year range  is Not Avaliable In This Systems Please Add Year.  Current data is Showing Of ".$year_date." year";
            session()->put('message_year',$message_year);
         }
        }
        else
        {
            // $current_year=date("Y",strtotime("-1 year"));
            $previous_year=date("Y",strtotime("-1 year"));
            $current_year=date('Y');
            $year_date=$previous_year."-".$current_year;
            $year_details = Year::where('year_value',$year_date)->first();
            $year_id=$year_details->year_id;

            // echo $year_date;
        }
        // echo $year_id;
        // exit;
        // TO DO:  Remove level_id  && org_id hardcoding
        $subdivision_count = GeoStructure::where('level_id', '2')->where('org_id', '1')->count();
        $block_count = GeoStructure::where('level_id', '3')->where('org_id', '1')->count();
        $block_details = GeoStructure::where('level_id', '3')->where('org_id', '1')->get();

        $panchayat_count = GeoStructure::where('level_id', '4')->where('org_id', '1')->count();
        $villages_count = GeoStructure::where('level_id', '4')->where('org_id', '1')->sum('no_of_villages');

        $scheme_count = SchemeStructure::count();

        $asset_count = Asset::where('org_id', '1')->count();
        $get_schemes = SchemeStructure::where('org_id', '1')->get();
        $departments = Department::where('org_id', '1')->where('is_active',1)->get();
        $health_scheme_count = SchemeStructure::where('dept_id', '1')->count();
        $land_revenue_count = SchemeStructure::where('dept_id', '2')->count();
        $welfare_count = SchemeStructure::where('dept_id', '3')->count();
        $education_count = SchemeStructure::where('dept_id', '4')->count();
        $land_acquisition_count = SchemeStructure::where('dept_id', '5')->count();
        $election_count = SchemeStructure::where('dept_id', '6')->count();
        $agriculture_count = SchemeStructure::where('dept_id', '7')->count();
        $social_welfare_count = SchemeStructure::where('dept_id', '9')->count();
        $drinking_water_and_sanitation_count = SchemeStructure::where('dept_id', '10')->count();
        $social_security_scheme_count = SchemeStructure::where('dept_id', '11')->count();
        $scheme_performance_details = SchemePerformance::get()->toArray();
        $year_details = Year::get()->toArray();


        /** dashboard scheme performance **/
        $dashboard_scheme_performance_has_datas = "success";
        //=> to decide rows
        $geo_ids = [];
        if (session()->get('user_designation') == 1) // dc
        {
            $geo_ids = GeoStructure::where('level_id', 3)->pluck('geo_id'); // decide rows
        } else if (session()->get('user_designation') == 2) { // sdo
            // get block id from geo structure where officer id is assigned
            // then get all panchayat od that block
            $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($subdivision_id_tmp){
                $geo_ids = GeoStructure::where('sd_id', $subdivision_id_tmp->geo_id)->where('level_id', '3')->pluck('geo_id'); // decide rows (blocks)
            }
        } else if (session()->get('user_designation') == 3) { // bdo
            // get block id from geo structure where officer id is assigned
            // then get all panchayat od that block
            $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($block_id_tmp)
            {
                $geo_ids = GeoStructure::where('bl_id', $block_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_id'); // decide rows (panchayat)
            }
        } else if (session()->get('user_designation') == 4) { //po
            $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($panchayat_id_tmp)
            {
                $geo_ids = GeoStructure::where('geo_id', $panchayat_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_id'); // decide rows (panchayat)
            }
        } else {
            $dashboard_scheme_performance_has_datas = "no_data";
        }
        // no datas
        if (count($geo_ids) == 0) {
            $dashboard_scheme_performance_has_datas = "You are not assigned to any ";
            if (session()->get('user_designation') == 1) {
                $dashboard_scheme_performance_has_datas .= "district";
            }
            if (session()->get('user_designation') == 2) {
                $dashboard_scheme_performance_has_datas .= "subdivision";
            }
            if (session()->get('user_designation') == 3) {
                $dashboard_scheme_performance_has_datas .= "block";
            }
            if (session()->get('user_designation') == 4) {
                $dashboard_scheme_performance_has_datas .= "panchayat";
            }
        }
        //=> to decvide column
        $scheme_ids = Fav_Scheme::where('user_id', session()->get('user_id'))->pluck('scheme_id'); // decide columns
        // $scheme_ids = SchemeStructure::get()->pluck(scheme_id); // decide columns
        if (count($scheme_ids) == 0) {
            $dashboard_scheme_performance_has_datas = "No favourite scheme selected";
        }
        //=>to pass datas
        $performance_table_heading_1 = [""];
        $performance_table_heading_2 = [""];
        $performance_table_datas = [];

        if($dashboard_scheme_performance_has_datas) {
            //=> for headings
            foreach ($scheme_ids as $key=>$scheme_id) {
                $scheme_data = SchemeStructure::find($scheme_id);
                if($scheme_data){
                    array_push($performance_table_heading_1, $scheme_data->scheme_short_name ."::". $scheme_data->scheme_logo);
                    array_push($performance_table_heading_2, "Sanctioned", "Completed", "Inprogress");
                }
                else{
                    unset($scheme_ids[$key]);
                }
            }
            //=> for datas
            foreach ($geo_ids as $geo_id) {
                $performance_table_datas_tmp = [];

                $tmp = GeoStructure::where('geo_id', $geo_id)->first()->geo_name;
                array_push($performance_table_datas_tmp, $tmp);

                foreach ($scheme_ids as $scheme_id) {
                    if (session()->get('user_designation') == 1) // dc
                    {
                        $performance_data = scheme_block_performance::where('block_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id',$year_id)
                            ->first();
                        if ($performance_data) {
                            $per = (($performance_data->completed_count) / ($performance_data->total_count)) * 100;
                            $per = round($per);
                        } else {
                            $performance_data = new scheme_block_performance();
                            $performance_data->incomplete_count = 0;
                            $performance_data->completed_count = 0;
                            $performance_data->total_count = 0;
                            $per = 0;
                        }
                        array_push($performance_table_datas_tmp, "<a href='scheme-review?review_for=block&scheme=" . $scheme_id . "&geo=" . $geo_id . "&year=".$year_id."&initiate=initiate'>" . $performance_data->total_count . "</a>:" . $per, $performance_data->completed_count . ":" . $per, $performance_data->incomplete_count . ":" . $per);
                    } else if (session()->get('user_designation') == 2) { // sdo
                        $performance_data = scheme_block_performance::where('block_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id',$year_id)

                            ->first();
                        if ($performance_data) {
                            $per = (($performance_data->completed_count) / ($performance_data->total_count)) * 100;
                            $per = round($per);
                        } else {
                            $performance_data->incomplete_count = 0;
                            $performance_data->completed_count = 0;
                            $performance_data->total_count = 0;
                            $per = 0;
                        }
                        array_push($performance_table_datas_tmp, "<a href='scheme-review?review_for=block&scheme=" . $scheme_id . "&geo=" . $geo_id . "&year=".$year_id."&initiate=initiate'>" . $performance_data->total_count . "</a>:" . $per, $performance_data->completed_count . ":" . $per, $performance_data->incomplete_count . ":" . $per);
                        // array_push($performance_table_datas_tmp, $performance_data->incomplete_count . ":" . $per, $performance_data->completed_count . ":" . $per, $performance_data->total_count . ":" . $per);
                    } else if (session()->get('user_designation') == 3) { // panchayat
                        $performance_datas = SchemePerformance::where('panchayat_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id',$year_id)

                            ->get();

                        if ($performance_datas) {
                            $per = (($performance_datas->where('status', '1')->count()) / ($performance_datas->count())) * 100;
                            $per = round($per);
                            $incomplete_count =  $performance_datas->where('status', '0')->count();
                            $completed_count = $performance_datas->where('status', '1')->count();
                            $total_count = $performance_datas->count();
                        } else {
                            $incomplete_count =  0;
                            $completed_count = 0;
                            $total_count = 0;
                            $per = 0;
                        }
                        // array_push($performance_table_datas_tmp, $incomplete_count.":".$per, $completed_count.":".$per, "<a href='scheme-review?review_for=block&scheme=".$scheme_id."&geo=".$geo_id."&year=4&initiate=initiate'>".$total_count."</a>:".$per);
                        array_push($performance_table_datas_tmp,$total_count . ":" . $per, $completed_count . ":" . $per, $incomplete_count . ":" . $per );
                    } else if (session()->get('user_designation') == 4) { // po
                        $performance_datas = SchemePerformance::where('panchayat_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id',$year_date)
                            ->get();

                        if ($performance_datas) {
                            $per = (($performance_datas->where('status', '1')->count()) / ($performance_datas->count())) * 100;
                            $per = round($per);
                            $incomplete_count =  $performance_datas->where('status', '0')->count();
                            $completed_count = $performance_datas->where('status', '1')->count();
                            $total_count = $performance_datas->count();
                        } else {
                            $incomplete_count =  0;
                            $completed_count = 0;
                            $total_count = 0;
                            $per = 0;
                        }
                        array_push($performance_table_datas_tmp, $total_count . ":" . $per, $completed_count . ":" . $per, $incomplete_count . ":" . $per);
                    }
                }
                array_push($performance_table_datas, $performance_table_datas_tmp);
            }
        }
        /** for dc dashboard ends **/
        // return $performance_table_heading_1;
        return view('dashboard.dc_dashboard')->with(compact('subdivision_count', 'year_id','block_count', 'panchayat_count', 'asset_count', 'scheme_count', 'block_details', 'scheme_performance_details', 'villages_count', 'get_schemes', 'departments', 'health_scheme_count', 'year_details', 'land_revenue_count', 'welfare_count', 'education_count', 'land_acquisition_count', 'election_count', 'agriculture_count', 'social_welfare_count', 'drinking_water_and_sanitation_count', 'social_security_scheme_count','dashboard_scheme_performance_has_datas', 'performance_table_heading_1', 'performance_table_heading_2', 'performance_table_datas'));
    }

    public function get_department_wise_asset_data()
    {
        // department wise data
        $asset_department_wise = Asset::leftJoin("department", "asset.dept_id", "=", "department.dept_id")
            ->select('department.dept_name', 'asset.dept_id', DB::raw('count(*) as total'))
            ->groupBy('department.dept_name', 'asset.dept_id')
            ->get();

        return $asset_department_wise;
    }

    public function language_change($id = "")
    {
        // echo "heee";
        if ($id == 1) {
            Languages::where('id', $id)->update(array(
                'status' => 1
            ));
            Languages::where('id', $id + 1)->update(array(
                'status' => 0
            ));
        } else {
            Languages::where('id', $id)->update(array(
                'status' => 1
            ));
            Languages::where('id', $id - 1)->update(array(
                'status' => 0
            ));
        }
        return back();
    }
    public function scheme_performance_for_dashborad($year = "")
    {
        // return $year;
        /** dashboard scheme performance **/
        $dashboard_scheme_performance_has_datas = "success";
        //=> to decide rows
        $geo_ids = [];
        if (session()->get('user_designation') == 1) // dc
        {
            $geo_ids = GeoStructure::where('level_id', 3)->pluck('geo_id'); // decide rows
        } else if (session()->get('user_designation') == 2) { // sdo
            // get block id from geo structure where officer id is assigned
            // then get all panchayat od that block
            $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first()->geo_id;
            $geo_ids = GeoStructure::where('sd_id', $subdivision_id_tmp)->where('level_id', '3')->pluck('geo_id'); // decide rows (blocks)
        } else if (session()->get('user_designation') == 3) { // bdo
            // get block id from geo structure where officer id is assigned
            // then get all panchayat od that block
            $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first()->geo_id;
            $geo_ids = GeoStructure::where('bl_id', $block_id_tmp)->where('level_id', '4')->pluck('geo_id'); // decide rows (panchayat)
        } else if (session()->get('user_designation') == 4) { //po
            $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first()->geo_id;
            $geo_ids = GeoStructure::where('geo_id', $panchayat_id_tmp)->where('level_id', '4')->pluck('geo_id'); // decide rows (panchayat)
        } else {
            $dashboard_scheme_performance_has_datas = "no_data";
        }
        // no datas
        if (count($geo_ids) == 0) {
            $dashboard_scheme_performance_has_datas = "You are not assigned to any ";
            if (session()->get('user_designation') == 1) {
                $dashboard_scheme_performance_has_datas .= "district";
            }
            if (session()->get('user_designation') == 2) {
                $dashboard_scheme_performance_has_datas .= "subdivision";
            }
            if (session()->get('user_designation') == 3) {
                $dashboard_scheme_performance_has_datas .= "block";
            }
            if (session()->get('user_designation') == 4) {
                $dashboard_scheme_performance_has_datas .= "panchayat";
            }
        }
        //=> to decvide column
        $scheme_ids = Fav_Scheme::where('user_id', session()->get('user_id'))->pluck('scheme_id'); // decide columns
        // $scheme_ids = SchemeStructure::get()->pluck(scheme_id); // decide columns
        if (count($scheme_ids) == 0) {
            $dashboard_scheme_performance_has_datas = "No favourite scheme selected";
        }
        //=>to pass datas
        $performance_table_heading_1 = [""];
        $performance_table_heading_2 = [""];
        $performance_table_datas = [];

        if ($dashboard_scheme_performance_has_datas) {
            //=> for headings
            foreach ($scheme_ids as $key=>$scheme_id) {
                $scheme_data = SchemeStructure::find($scheme_id);
                if($scheme_data){
                    array_push($performance_table_heading_1, $scheme_data->scheme_short_name ."::". $scheme_data->scheme_logo);
                    array_push($performance_table_heading_2, "Sanctioned", "Completed", "Inprogress");
                }
                else{
                    unset($scheme_ids[$key]);
                }
            }
            //=> for datas
            foreach ($geo_ids as $geo_id) {
                $performance_table_datas_tmp = [];

                $tmp = GeoStructure::where('geo_id', $geo_id)->first()->geo_name;
                array_push($performance_table_datas_tmp, $tmp);

                foreach ($scheme_ids as $scheme_id) {
                    if (session()->get('user_designation') == 1) // dc
                    {
                        $performance_data = scheme_block_performance::where('block_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id', $year)
                            ->first();
                        if ($performance_data) {
                            $per = (($performance_data->completed_count) / ($performance_data->total_count)) * 100;
                            $per = round($per);
                        } else {
                            $performance_data = new scheme_block_performance();
                            $performance_data->incomplete_count = 0;
                            $performance_data->completed_count = 0;
                            $performance_data->total_count = 0;
                            $per = 0;
                        }
                        array_push($performance_table_datas_tmp, "<a href='scheme-review?review_for=block&scheme=" . $scheme_id . "&geo=" . $geo_id . "&year=".$year."&initiate=initiate'>" . $performance_data->total_count . "</a>:" . $per, $performance_data->completed_count . ":" . $per, $performance_data->incomplete_count . ":" . $per);

                        // array_push($performance_table_datas_tmp, $performance_data->incomplete_count . ":" . $per, $performance_data->completed_count . ":" . $per, "<a href='scheme-review?review_for=block&scheme=" . $scheme_id . "&geo=" . $geo_id . "&year=".$year."&initiate=initiate'>" . $performance_data->total_count . "</a>:" . $per);
                    } else if (session()->get('user_designation') == 2) { // sdo
                        $performance_data = scheme_block_performance::where('block_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id', $year)

                            ->first();
                        if ($performance_data) {
                            $per = (($performance_data->completed_count) / ($performance_data->total_count)) * 100;
                            $per = round($per);
                        } else {
                            $performance_data->incomplete_count = 0;
                            $performance_data->completed_count = 0;
                            $performance_data->total_count = 0;
                            $per = 0;
                        }
                        array_push($performance_table_datas_tmp, "<a href='scheme-review?review_for=block&scheme=" . $scheme_id . "&geo=" . $geo_id . "&year=".$year."&initiate=initiate'>" . $performance_data->total_count . "</a>:" . $per, $performance_data->completed_count . ":" . $per, $performance_data->incomplete_count . ":" . $per);

                        // array_push($performance_table_datas_tmp, $performance_data->incomplete_count . ":" . $per, $performance_data->completed_count . ":" . $per, $performance_data->total_count . ":" . $per);
                    } else if (session()->get('user_designation') == 3) { // panchayat
                        $performance_datas = SchemePerformance::where('panchayat_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id', $year)

                            ->get();

                        if ($performance_datas) {
                            $per = (($performance_datas->where('status', '1')->count()) / ($performance_datas->count())) * 100;
                            $per = round($per);
                            $incomplete_count =  $performance_datas->where('status', '0')->count();
                            $completed_count = $performance_datas->where('status', '1')->count();
                            $total_count = $performance_datas->count();
                        } else {
                            $incomplete_count =  0;
                            $completed_count = 0;
                            $total_count = 0;
                            $per = 0;
                        }
                        // array_push($performance_table_datas_tmp, $incomplete_count.":".$per, $completed_count.":".$per, "<a href='scheme-review?review_for=block&scheme=".$scheme_id."&geo=".$geo_id."&year=4&initiate=initiate'>".$total_count."</a>:".$per);
                        array_push($performance_table_datas_tmp,$total_count . ":" . $per, $completed_count . ":" . $per, $incomplete_count . ":" . $per );
                    } else if (session()->get('user_designation') == 4) { // bdo
                        $performance_datas = SchemePerformance::where('panchayat_id', $geo_id)
                            ->where('scheme_id', $scheme_id)
                            ->where('year_id', $year)

                            ->get();

                        if ($performance_datas) {
                            $per = (($performance_datas->where('status', '1')->count()) / ($performance_datas->count())) * 100;
                            $per = round($per);
                            $incomplete_count =  $performance_datas->where('status', '0')->count();
                            $completed_count = $performance_datas->where('status', '1')->count();
                            $total_count = $performance_datas->count();
                        } else {
                            $incomplete_count =  0;
                            $completed_count = 0;
                            $total_count = 0;
                            $per = 0;
                        }
                        array_push($performance_table_datas_tmp, $total_count . ":" . $per, $completed_count . ":" . $per, $incomplete_count . ":" . $per);
                    }
                }
                array_push($performance_table_datas, $performance_table_datas_tmp);
            }
            return ['dashboard_scheme_performance_has_datas' => $dashboard_scheme_performance_has_datas, 'performance_table_heading_1' => $performance_table_heading_1, 'performance_table_heading_2' => $performance_table_heading_2, 'performance_table_datas' => $performance_table_datas];
        }
        /** for dc dashboard ends **/
    }

    public function get_block_performance_percentage_data(Request $request){
        $to_send = [];
    
        $geo_ids = GeoStructure::where('level_id', 3)->get()->pluck('geo_id');
    
        foreach($geo_ids as $geo_id){
            // $performance_data = scheme_block_performance::where('block_id', $geo_id)
            //                                         ->where('scheme_id', 117)
            //                                         ->first();
            $performance_data = scheme_block_performance::where('block_id', $geo_id)
                                                    ->where('scheme_id', 2)
                                                    ->first();
    
            if($performance_data){
                $per = (($performance_data->completed_count) / ($performance_data->total_count))*100;
                $per = round($per);
            }
            else{
                $per = 0;
            }
    
            $to_send_tmp["geo_id"] = $geo_id;
            $to_send_tmp["percentage"] = $per;
    
            array_push($to_send, $to_send_tmp);
        }
        
        return $to_send;
    }
}
