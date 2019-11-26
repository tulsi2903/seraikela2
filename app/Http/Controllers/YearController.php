<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Year;

class YearController extends Controller
{
     public function index(){
        $datas = Year::orderBy('year_id','desc')->get();
        return view('year.index')->with('datas', $datas);
    }
    
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new Year;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
            $data->from = explode('-',$data->year_value)[0];
             $data->to = explode('-',$data->year_value)[1];
        }

       return view('year.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
    }

    public function store(Request $request){
            
        //$response = "failed";
        $year = new Year;

        if($request->hidden_input_purpose=="edit"){
            $year = $year->find($request->hidden_input_id);
        }

        $year->year_value= $request->from_value."-".$request->to_value;
        $year->status = $request->status;
        $year->created_by = '1';
        $year->updated_by = '1';

        
        if(Year::where('year_value',$year->year_value)->first()&&$request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This year '.$request->from_value."-".$request->to_value.' already exist !');
        }
        else if($year->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Year details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('year');
    }

    public function delete(Request $request){
        if(Year::find($request->year_id)){
            Year::where('year_id',$request->year_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('year');
    }
}
