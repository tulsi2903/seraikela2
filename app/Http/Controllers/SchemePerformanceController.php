<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SchemeGeoTarget;
use App\GeoStructure;
use App\SchemeIndicator;
use App\Year;
use App\SchemeStructure;
use App\Group;
use App\SchemePerformance;

class SchemePerformanceController extends Controller
{
    public function index()
    {

    	 $datas = SchemePerformance::leftJoin('scheme_structure', 'scheme_geo_target.scheme_id', '=', 'scheme_structure.scheme_id')
                    ->leftJoin('scheme_geo_target','scheme-performance.scheme_geo_target_id','=','scheme_geo_target.scheme_geo_target_id')
                    ->leftJoin('geo_structure', 'scheme_geo_target.geo_id', '=', 'geo_structure.geo_id')
                    ->leftJoin('scheme_indicator','scheme_geo_target.indicator_id','=','scheme_indicator.indicator_id')
                    ->leftJoin('year','scheme_geo_target.year_id','=','year.year_id')
                    ->leftJoin('scheme_group','scheme_geo_target.group_id','=','scheme_group.scheme_group_id')
                    ->select('scheme_geo_target.*','scheme_structure.scheme_name','scheme_structure.scheme_short_name','geo_structure.geo_name','geo_structure.level_id','geo_structure.parent_id','scheme_indicator.indicator_name','year.year_value','scheme_group.scheme_group_name')
                    ->orderBy('scheme_geo_target.scheme_geo_target_id','desc')
                    ->get();

                     $i=0;
        foreach($datas as $data){
            if($data->level_id==4){
                $tmp = GeoStructure::find($data->parent_id);
                if($tmp->geo_name)
                { 
                    $datas[$i]->bl_name = $tmp->geo_name; 
                }
                else{
                $datas[$i]->bl_name = "NA";
                }
                }
                else{
                    $datas[$i]->bl_name = "NA";
                }
                $i++;
        }

    	return view('scheme-performance.index')->with('datas',$datas);
    }

     public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $bl_id = "";

        
        $schemes = SchemeStructure::orderBy('scheme_name','asc')->get();
        $panchayats = GeoStructure::orderBy('geo_name','asc')->get();
        $indicators = SchemeIndicator::orderBy('indicator_name','asc')->get();
        $years = Year::orderBy('year_value','asc')->get();
        // $groups = Group::orderBy('scheme_group_name','asc')->get();
        $blocks = GeoStructure::orderBy('geo_name','asc')->where('level_id','=','3')->get();
        $districts = GeoStructure::orderBy('geo_name','asc')->where('level_id','=','1')->get();
        $subdivisions = GeoStructure::orderBy('geo_name','asc')->where('level_id','=','2')->get();
      

        $data = new SchemePerformance;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);

            if($data){
                $tmp = GeoStructure::select('geo_id')->whereIn('geo_id', GeoStructure::select('bl_id')->where('geo_id', $data->geo_id)->first())->first();
                $bl_id = $tmp->geo_id;

                $tmp_dist = GeoStructure::select('geo_id')->whereIn('geo_id',GeoStructure::select('dist_id')->where('geo_id',$data->geo_id)->first())->first();
                $dist_id =$tmp_dist->geo_id;

                $tmp_sd = GeoStructure::select('geo_id')->whereIn('geo_id',GeoStructure::select('sd_id')->where('geo_id',$data->geo_id)->first())->first();
                $sd_id =$tmp_sd->geo_id;

                $dist = GeoStructure::orderBy('geo_name','asc')->where('dist_id',$dist_id)->get();
                $sd = GeoStructure::orderBy('geo_name','asc')->where('sd_id',$sd_id)->get();

               

                $indicators = SchemeIndicator::orderBy('indicator_name','asc')->where('scheme_id',$data->scheme_id)->get();
                

                $panchayats = GeoStructure::orderBy('geo_name','asc')->where('bl_id', $bl_id)->get();
            }
        }

        return view('scheme-performance.add')->with(compact('hidden_input_purpose','hidden_input_id','data','bl_id','dist_id','sd_id','dist','sd','schemes','panchayats','indicators','years','groups','blocks','districts','subdivisions'));
    }
    
    public function data_geo_target(Request $request)
    {
        $geo_target_data = SchemeGeoTarget::where('geo_id',$request->panchayat)->where('scheme_id',$request->scheme_name)->get();
        return ["geo_target_data"=>$geo_target_data];

       return view('scheme-performance.index');

                          
    }
    
   
    public function get_panchayat_name(Request $request)
    {
          $data = GeoStructure::where('bl_id', $request->bl_id)->get();
          return["panchayat_data"=>$data, "id"=>$request->bl_id];
    }
    public function get_subdivision_name(Request $request)
    {
    	$data = GeoStructure::where('dist_id',$request->dist_id)->where('level_id','=','2')->get();
    	return["subdivision_data"=>$data,"id"=>$request->dist_id];

    }
    public function get_block_name(Request $request)
    {
    	$data = GeoStructure::where('sd_id',$request->sd_id)->where('level_id','=','3')->get();
    	return["block_data"=>$data,"id"=>$request->sd_id];
    }


    public function get_indicator_name(Request $request)
    {
        $data = SchemeIndicator::where('scheme_id',$request->scheme_id)->get();
        return ["scheme_indicator_data"=>$data];
    }


     public function get_target(Request $request)
    {
      $tmp = SchemeGeoTarget::where('scheme_id',$request->scheme_id)->where('geo_id',$request->geo_id)->where('indicator_id',$request->indicator_id)->where('year_id', $request->year_id)->first();  
      $scheme_geo_target_id="";
      $pre_value="";
        if(count($tmp)>0)
        {

            $scheme_geo_target_id = $tmp->scheme_geo_target_id;

            $target=$tmp->target;

            $get_pre_value = SchemePerformance::where('scheme_geo_target_id',$tmp->scheme_geo_target_id)
                                               ->orderBy('scheme_performance_id', 'desc')
                                               ->first();

              if($get_pre_value)
              {
                $pre_value = $get_pre_value->current_value;
              }
              else
              {
                $pre_value = 0;
              }
            
        }
      else{
        $target = -1;
      }

        return ["target_data"=>$target,"id"=>$scheme_geo_target_id,"pre_value"=>$pre_value];


      

       // return ["target_data"=>$target,"id"=>$tmp->scheme_geo_target_id,"pre_value"=>$pre_value];
      // //return ["target_data"=>$target,"id"=>$tmp->scheme_geo_target_id];
      // return 1;
    }

    public function store(Request $request)
    {
        $scheme_performance = new SchemePerformance;
        $scheme_performance->scheme_geo_target_id = $request->scheme_geo_target_id;
        $scheme_performance->pre_value = $request->pre_value;
        $scheme_performance->current_value = $request->current_value;

         $scheme_performance->attachment = "";

      

        //  $i = 0;
        //  if($request->hasFile('attachment'))
        //  {

        //     foreach($request->file('attachment') as $file){

        //         $imageName = time() . $i . '.' . $file->getClientOriginalExtension();

        //         // move the file to desired folder
        //         $file->move('public/uploaded_documents/', $imageName);

        //         // assign the location of folder to the model
        //         $scheme_performance->attachment.=":".$imageName;


        //         $i++;

        //     } 
            
        //     $scheme_performance->attachment = ltrim($scheme_performance->attachment,":");
        // }
        $scheme_performance->created_by =1;
        $scheme_performance->updated_by =1;
       

        if($scheme_performance->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme performance have been successfully submitted !');
        }
        return redirect('scheme-performance/add');
    }

   
}
