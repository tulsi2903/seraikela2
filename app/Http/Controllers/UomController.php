<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Uom;

class UomController extends Controller
{
    public function index(){
        $datas = Uom::orderBy('uom_id','desc')->get();
        return view('uom.index')->with('datas', $datas);
    }


    
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new Uom;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('uom.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
    }

    public function store(Request $request){
        $purpose="add";
        $uom = new Uom;

        if(isset($request->edit_id)){
            $uom = $uom->find($request->edit_id);
            if(count($uom)!=0){
                $purpose="edit";
            }
        }

        $uom->uom_name= $request->uom_name;
        $uom->created_by = '1';
        $uom->updated_by = '1';


        if(Uom::where('uom_name',$request->uom_name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This UoM '.$request->uom_name.' already exist !');
        }
        else if($uom->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','UOM details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('uom');
    }

    public function delete(Request $request){
        if(Uom::find($request->uom_id)){
            Uom::where('uom_id',$request->uom_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('uom');
    }
}
