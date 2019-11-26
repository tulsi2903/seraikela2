<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\SchemeType;

class SchemeTypeController extends Controller
{
    public function index()
    {
        $datas =SchemeType::orderBy('sch_type_id','desc')->get();
        return view('scheme-type.index')->with('datas',$datas);
    }
    
     public function add(Request $request)
     {
         $hidden_input_purpose = "add";
         $hidden_input_id= "NA";
         $data = new SchemeType;
         
         if(isset($request->purpose) && ($request->id)){
             $hidden_input_purpose = $request->purpose;
             $hidden_input_id = $request->id;
             $data = $data->find($request->id);
         }
         return view('scheme-type.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
     }
    public function store(Request $request)
    {
         //$response = "failed";
        $scheme_type = new SchemeType;

        if($request->hidden_input_purpose=="edit"){
            $scheme_type = $scheme_type->find($request->hidden_input_id);
        }

        $scheme_type->sch_type_name= $request->sch_type_name;
        
        $scheme_type->created_by = '1';
        $scheme_type->updated_by = '1';

        if($scheme_type->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme submitted successfully !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('scheme-type');
    }
     public function delete(Request $request){
        if(SchemeType::find($request->sch_type_id)){
            SchemeType::where('sch_type_id',$request->sch_type_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('scheme-type');
    }
}
