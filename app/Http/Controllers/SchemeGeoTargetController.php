<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeGeoTarget;
use App\GeoStructure;
use App\SchemeIndicator;
use App\Year;
use App\SchemeStructure;
use App\Group;

class SchemeGeoTargetController extends Controller
{
    public function index()
    {
        $datas = SchemeGeoTarget::leftJoin('scheme_structure', 'scheme_geo_target.scheme_id', '=', 'scheme_structure.scheme_id')
                    ->leftJoin('geo_structure', 'scheme_geo_target.geo_id', '=', 'geo_structure.geo_id')
                    ->leftJoin('scheme_indicator','scheme_geo_target.indicator_id','=','scheme_indicator.indicator_id')
                    ->leftJoin('year','scheme_geo_target.year_id','=','year.year_id')
                    ->leftJoin('scheme_group','scheme_geo_target.group_id','=','scheme_group.scheme_group_id')
                    ->select('scheme_geo_target.*','scheme_structure.scheme_name','scheme_structure.scheme_short_name','geo_structure.geo_name','scheme_indicator.indicator_name','year.year_value','scheme_group.scheme_group_name')
                    ->orderBy('scheme_geo_target.scheme_geo_target_id','desc')
                    ->get();

        return view('scheme-geo-target.index')->with('datas',$datas);
    }
     public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";

        
        $scheme_structures = SchemeStructure::orderBy('scheme_name','asc')->get();
        $panchayats = GeoStructure::orderBy('geo_name','asc')->get();
        $indicators = SchemeIndicator::orderBy('indicator_name','asc')->get();
        $years = Year::orderBy('year_value','asc')->get();
        $groups = Group::orderBy('scheme_group_name','asc')->get();
      

        $data = new SchemeGeoTarget;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }
        return view('scheme-geo-target.add')->with(compact('hidden_input_purpose','hidden_input_id','data','scheme_structures','panchayats','indicators','years','groups'));
    }
    public function store(Request $request)
    {
         $scheme_geo_target = new SchemeGeoTarget;

        if($request->hidden_input_purpose=="edit"){
            $scheme_geo_target = $scheme_geo_target->find($request->hidden_input_id);
        }
        
        $scheme_geo_target->scheme_id = $request->scheme_name;
        $scheme_geo_target->geo_id = $request->panchayat;
        $scheme_geo_target->indicator_id = $request->indicator;
       
        $scheme_geo_target->target =  $request->target;
       $scheme_geo_target->year_id = $request->year;
      
       if(!isset($request->scheme_group_name))
       {
        $scheme_geo_target->group_id = '0';
       }
       else
       {
       $scheme_geo_target->group_id = $request->scheme_group_name;
       }
       
        $scheme_geo_target->created_by = '1';
        $scheme_geo_target->updated_by = '1';
       
        if(SchemeGeoTarget::where('geo_id',$request->geo_id)->first()&&$request->hidden_input_purpose!="edit"){
         session()->put('alert-class','alert-danger');
         session()->put('alert-content','This panchayat '.$request->geo_name.' already exist');
        }
 
        else if($scheme_geo_target->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Panchayat target have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }
        

        return redirect('scheme-geo-target');
    }
    public function get_indicator_name(Request $request)
    {
        // $request->scheme_id;
        $data = SchemeIndicator::where('scheme_id',$request->scheme_id)->get();
        $independent = SchemeStructure::select('independent')->where('scheme_id',$request->scheme_id)->first();
        $independent = $independent->independent;
        return ["scheme_indicator_data"=>$data,"independent"=>$independent];
    }
    public function delete(Request $request)
    {
         if(SchemeGeoTarget::find($request->scheme_geo_target_id)){
            SchemeGeoTarget::where('scheme_geo_target_id',$request->scheme_geo_target_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('scheme-geo-target');
    }
}
