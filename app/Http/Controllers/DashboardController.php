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
use DB;
use App\Languages;

class DashboardController extends Controller
{
    // function to assign everything related to user logged in (after logged in user id redirected to this method, this method further redirected usaer to its dashboadr page)
    public function index()
    {
        // session store user details
        if(Auth::check()){
            session()->put('user_id', Auth::user()->id);
            session()->put('user_full_name', Auth::user()->first_name." ".Auth::user()->last_name); 
            session()->put('user_org_id', Auth::user()->org_id);
            session()->put('user_designation', Auth::user()->userRole);
            switch(Auth::user()->userRole)
            {
                case "1":
                    session()->put('user_designation_name', "Admin");
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
            $desig_permission_datas = DesignationPermission::leftjoin("module","desig_permission.mod_id","=","module.mod_id")->select("module.mod_id","module.mod_name","desig_permission.add","desig_permission.edit","desig_permission.view","desig_permission.del")->where("desig_id", session()->get('user_designation'))->get();
            $desig_permission = Array(); // ['module_id'=>[add, edit, view, delete], "module_id"=>[add, edit, view, delete]]
            foreach($desig_permission_datas as $data){
                $tmp = ["mod".$data->mod_id."" => ["add"=>$data->add, "edit"=>$data->edit, "view"=>$data->view, "del"=>$data->del]];
                $desig_permission = array_merge($desig_permission, $tmp);
            }
            // return $desig_permission["mod_1"];
            session()->put('desig_permission', $desig_permission);

            if(session()->exists('designation_permission_changes')){
                session()->forget('designation_permission_changes');
                return redirect('designation-permission');
            }

            return redirect(session()->get('dashboard_url')); // redirecting back after sucessfully 
        }
        else{ // redirect if not logged in
            session()->flush();
            return redirect()->route("login");
        }
    }

    // show dashboard contents
    public function dashboard(){
        // return session()->get('desig_permission');

        // TO DO:  Remove level_id  && org_id hardcoding
        $subdivision_count = GeoStructure::where('level_id','2')->where('org_id','1')->count();
        $block_count = GeoStructure::where('level_id','3')->where('org_id','1')->count();
        $panchayat_count =GeoStructure::where('level_id','4')->where('org_id','1')->count();
        $villages_count = GeoStructure::where('level_id','4')->where('org_id','1')->sum('no_of_villages');

        $scheme_count = SchemeStructure::count();

        $asset_count = Asset::where('org_id','1')->count();
        $get_schemes = SchemeStructure::where('org_id','1')->get();
        $departments = Department::where('org_id','1')->get();
        $health_scheme_count = SchemeStructure::where('dept_id','1')->count();
        $land_revenue_count = SchemeStructure::where('dept_id','2')->count();
        $welfare_count = SchemeStructure::where('dept_id','3')->count();
        $education_count = SchemeStructure::where('dept_id','4')->count();
        $land_acquisition_count = SchemeStructure::where('dept_id','5')->count();
        $election_count = SchemeStructure::where('dept_id','6')->count();
        $agriculture_count = SchemeStructure::where('dept_id','7')->count();
        $social_welfare_count = SchemeStructure::where('dept_id','8')->count();
        $drinking_water_and_sanitation_count = SchemeStructure::where('dept_id','9')->count();
        $social_security_scheme_count = SchemeStructure::where('dept_id','10')->count();

        return view('dashboard.dc_dashboard')->with(compact('subdivision_count','block_count','panchayat_count','asset_count','scheme_count','villages_count','get_schemes','departments','health_scheme_count','land_revenue_count','welfare_count','education_count','land_acquisition_count','election_count','agriculture_count','social_welfare_count','drinking_water_and_sanitation_count','social_security_scheme_count'));
    }

    public function get_department_wise_asset_data(){
        // department wise data
        $asset_department_wise = Asset::leftJoin("department","asset.dept_id","=","department.dept_id")
                        ->select('department.dept_name', 'asset.dept_id', DB::raw('count(*) as total'))
                        ->groupBy('department.dept_name', 'asset.dept_id')
                        ->get();

        return $asset_department_wise;
    }

    public function language_change($id = "")
    {
        // echo "heee";
        if($id==1)
        {
            Languages::where('id',$id)->update(array(
                'status' => 1
            ));
            Languages::where('id',$id+1)->update(array(
                'status' => 0
            ));
        }
        else{
            Languages::where('id',$id)->update(array(
                'status' => 1
            ));
            Languages::where('id',$id-1)->update(array(
                'status' => 0
            ));          
        }
    return redirect('/'); 
    }
}
