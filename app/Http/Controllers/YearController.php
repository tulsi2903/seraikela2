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
        $purpose="add";
        $year = new Year;

        if(isset($request->edit_id)){
            $year = $year->find($request->edit_id);
            if(count($year)!=0){
                $purpose="edit";
            }
        }

        $year->year_value= $request->from_value."-".$request->to_value;
        $year->status = $request->status;
        $year->created_by = '1';
        $year->updated_by = '1';

        
        if(Year::where('year_value',$year->year_value)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This year '.$year->year_value.' already exist !');
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
