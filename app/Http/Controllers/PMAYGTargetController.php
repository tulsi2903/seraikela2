<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PMAYGTarget;
use App\GeoStructure;
use App\Year;
use App\Group;

class PMAYGTargetController extends Controller
{
    public function index()
    {
        $datas = PMAYGTarget::leftJoin('year', 'pmayg_target.year_id', '=', 'year.year_id')
                            ->select('pmayg_target.*','year.year_value')
                            ->orderBy('pmayg_target_id','desc')
                            ->get();
        
        foreach($datas as $data)
        {
            // block data
            $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->block_id)->first();
            $data->block_name = $tmp->geo_name;

            //panchayat data
            $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->panchayat_id)->first();
            $data->panchayat_name = $tmp->geo_name;

            //subdivision data
            $tmp = GeoStructure::select('geo_name')->where('geo_id','=',$data->subdivision_id)->first();
            $data->subdivision_name = $tmp->geo_name;
        }

        return view('scheme-geo-target.pmayg.index',compact('datas'));
    }
    public function add(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new PMAYGTarget;


        if(isset($request->purpose)&&isset($request->id)){
            
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
            if($data){
                $pmayg_target = PMAYGTarget::where('pmayg_target_id',$data->pmayg_target_id)->get();
            }
        }
       

        $subdivision_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','2')->get();
        $year_datas = Year::select('year_id','year_value')->orderBy('year_value','asc')->get();
        $group_datas = Group::select('scheme_group_id','scheme_group_name')->orderBy('scheme_group_name','asc')->get();
        $block_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','3')->get();
        $panchayat_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','4')->get();

       
        return view('scheme-geo-target.pmayg.add')->with(compact('hidden_input_id','hidden_input_purpose','data','pmayg_target','subdivision_datas','year_datas','group_datas','block_datas','panchayat_datas'));
    }
    public function get_panchayat_name(Request $request)
    {
          $data = GeoStructure::where('bl_id', $request->block_id)->where('level_id','4')->get();
          return $data;
    }
    
    public function get_block_name(Request $request)
    {
    	$data = GeoStructure::where('sd_id',$request->subdivision_id)->where('level_id','3')->get();
    	return $data;
    }

    public function store(Request $request)
    {
        $pmayg_target = new PMAYGTarget;

        if($request->hidden_input_purpose=="edit"){
            $pmayg_target = $pmayg_target->find($request->hidden_input_id);
        }
       
        
        $pmayg_target->year_id = $request->year_id;
        $pmayg_target->subdivision_id = $request->subdivision_id;
        $pmayg_target->block_id = $request->block_id;
        $pmayg_target->panchayat_id = $request->panchayat_id;
        $pmayg_target->target = $request->target;

        $pmayg_target->created_by = '1';
        $pmayg_target->updated_by = '1';
        

        if($pmayg_target->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Target have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

       return redirect('scheme-geo-target/pmayg');

    }

    public function get_updated_datas(Request $request)
    {
        $data =array();
        $data = PMAYGTarget::where('year_id',$request->year_id)->where('panchayat_id',$request->panchayat_id)->first();
        if($data)
        {
           return $data;
        }
        else
        {
            $temp_array=array();
            $temp_array['target']="";
            return $temp_array;
          
        }
        
       
        
    }

    public function delete(Request $request)
    {
        if(PMAYGTarget::find($request->pmayg_target_id)){
            PMAYGTarget::where('pmayg_target_id',$request->pmayg_target_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('scheme-geo-target/pmayg');    
    }
}
