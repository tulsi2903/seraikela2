<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeoStructure;
use App\Asset;
use App\SchemeStructure;
use App\Department;

class DashboardController extends Controller
{
    public function index()
    {
         // TO DO:  Remove level_id  && org_id hardcoding
         $subdivision_count = GeoStructure::where('level_id','2')->where('org_id','1')->count();
        
          $block_count = GeoStructure::where('level_id','3')->where('org_id','1')->count();
 
         $panchayat_count =GeoStructure::where('level_id','4')->where('org_id','1')->count();

         $asset_count = Asset::where('org_id','1')->count();

         $villages_count = GeoStructure::where('level_id','4')->where('org_id','1')->sum('no_of_villages');

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


 
        return view('dashboard.dc_dashboard')->with(compact('subdivision_count','block_count','panchayat_count','asset_count','villages_count','get_schemes','departments','health_scheme_count','land_revenue_count','welfare_count','education_count','land_acquisition_count','election_count','agriculture_count','social_welfare_count','drinking_water_and_sanitation_count','social_security_scheme_count'));
    }

}