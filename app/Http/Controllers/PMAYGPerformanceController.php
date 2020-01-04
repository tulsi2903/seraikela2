<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PMAYGPerformance;
use App\GeoStructure;
use App\Year;
use App\PMAYGTarget;

class PMAYGPerformanceController extends Controller
{
    public function index()
    {
        $datas = PMAYGPerformance::leftJoin('year', 'pmayg_performance.year_id', '=', 'year.year_id')
                    ->select('pmayg_performance.*','year.year_value')
                    ->orderBy('pmayg_performance_id','desc')
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
            
        return view('scheme-performance.pmayg.index',compact('datas'));
    }

    public function add(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new PMAYGPerformance;


        if(isset($request->purpose)&&isset($request->id)){
            
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
            // if($data){
            //     $pmayg_target = PMAYGPerformance::where('pmayg_performance_id',$data->pmayg_performance_id)->get();
            // }
        }
       

        $subdivision_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','2')->get();
        $year_datas = Year::select('year_id','year_value')->orderBy('year_value','asc')->get();
      
        $block_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','3')->get();
        $panchayat_datas = GeoStructure::select('geo_id','geo_name')->orderBy('geo_name','asc')->where('level_id','=','4')->get();

       
        return view('scheme-performance.pmayg.add')->with(compact('hidden_input_id','hidden_input_purpose','data','subdivision_datas','year_datas','block_datas','panchayat_datas'));
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
    public function store(Request $request)
    {

        $pmayg_performance = new PMAYGPerformance;
        if ($request->hidden_input_purpose == "edit") {
            $pmayg_performance = $pmayg_performance->find($request->hidden_input_id);
        }


        if(isset($request->registration_no)){
            $registration_no = $request->registration_no;
            for($i=0; $i < count($registration_no); $i++){
                $pmayg_performance = new PMAYGPerformance;
                $pmayg_performance->year_id = $request->year_id;
                $pmayg_performance->subdivision_id = $request->subdivision_id;
                $pmayg_performance->block_id = $request->block_id;
                $pmayg_performance->panchayat_id = $request->panchayat_id;
                $pmayg_performance->registration_no = $request->registration_no[$i];
                $pmayg_performance->sanction_no = $request->sanction_no[$i];
                $pmayg_performance->latitude = $request->latitude[$i];
                $pmayg_performance->longitude = $request->longitude[$i];
                $pmayg_performance->sanction_amount = $request->sanction_amount[$i];
                $pmayg_performance->installment_paid = $request->installment_paid[$i];
                $pmayg_performance->house_status = $request->house_status[$i];
                $pmayg_performance->amount_released = $request->amount_released[$i];
                $pmayg_performance->created_by = '1';
                $pmayg_performance->updated_by = '1';
                $pmayg_performance->save();
                  
            }
        }
    //  return $pmayg_performance;

        // if(isset($request->registration_no)){
        //     $registration_no = $request->registration_no;
        //     for($i=0; $i < count($registration_no); $i++){
        //         $pmayg_performance =PMAYGPerformance::find($request->pmayg_performance_id);
        //         $pmayg_performance->year_id = $request->year_id;
        //         $pmayg_performance->subdivision_id = $request->subdivision_id;
        //         $pmayg_performance->block_id = $request->block_id;
        //         $pmayg_performance->panchayat_id = $request->panchayat_id;
        //         $pmayg_performance->registration_no = $request->registration_no[$i];
        //         $pmayg_performance->sanction_no = $request->sanction_no[$i];
        //         $pmayg_performance->latitude = $request->latitude[$i];
        //         $pmayg_performance->longitude = $request->longitude[$i];
        //         $pmayg_performance->sanction_amount = $request->sanction_amount[$i];
        //         $pmayg_performance->installment_paid = $request->installment_paid[$i];
        //         $pmayg_performance->house_status = $request->house_status[$i];
        //         $pmayg_performance->amount_released = $request->amount_released[$i];
        //         $pmayg_performance->created_by = '1';
        //         $pmayg_performance->updated_by = '1';
        //         $pmayg_performance->save();
                   
        //     }
        // }

        if($pmayg_performance){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme Performance have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }
       
        // return $pmayg_performance;

        

       return redirect('scheme-performance/pmayg');
    }

    public function view()
    {
      
        return view('scheme-performance.pmayg.view')->with(compact('datas'));
    }

    

}
