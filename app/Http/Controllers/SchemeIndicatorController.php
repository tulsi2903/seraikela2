<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeIndicator;
use App\SchemeStructure;
use App\Uom;

class SchemeIndicatorController extends Controller
{
    public function index()
    {
    	 $datas = SchemeIndicator::leftJoin('scheme_structure', 'scheme_indicator.scheme_id', '=', 'scheme_structure.scheme_id')
		            ->select('scheme_indicator.*','uom.uom_name')
		            ->select('scheme_indicator.*','scheme_structure.scheme_name','scheme_structure.scheme_short_name')
		            ->orderBy('scheme_indicator.indicator_id','desc')
		            ->get();

    	return view('scheme-indicator.index')->with('datas', $datas);
    }
     public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";

        
        $scheme_structures = SchemeStructure::orderBy('scheme_name','asc')->get();

        $uoms = Uom::orderBy('uom_name','asc')->get();
      

        $data = new SchemeIndicator;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }
        return view('scheme-indicator.add')->with(compact('hidden_input_purpose','hidden_input_id','data','scheme_structures','uoms'));
    }
 public function store(Request $request){
        //$response = "failed";
        $scheme_indicator = new SchemeIndicator;

        if($request->hidden_input_purpose=="edit"){
            $scheme_indicator = $scheme_indicator->find($request->hidden_input_id);
        }
        
        $scheme_indicator->indicator_name = $request->indicator_name;
        $scheme_indicator->scheme_id = $request->scheme_name;
        $scheme_indicator->unit = $request->uom;
       
        $scheme_indicator->performance =  $request->performance;
       
       
        $scheme_indicator->created_by = '1';
        $scheme_indicator->updated_by = '1';
       
        if(SchemeIndicator::where('indicator_name',$request->indicator_name)->first()&&$request->hidden_input_purpose!="edit"){
         session()->put('alert-class','alert-danger');
         session()->put('alert-content','This indicator '.$request->indicator_name.' already exist');
        }
 
        else if($scheme_indicator->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Indicator details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }
        

        return redirect('scheme-indicator');
    }

    public function delete(Request $request){
        if(SchemeIndicator::find($request->indicator_id)){
            SchemeIndicator::where('indicator_id',$request->indicator_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('scheme-indicator');
    }
}
