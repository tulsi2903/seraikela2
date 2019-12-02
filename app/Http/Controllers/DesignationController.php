<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use App\Organisation;

class DesignationController extends Controller
{
    //
    //
    public function index(){
        $datas = Designation::leftJoin('organisation', 'designation.org_id', '=', 'organisation.org_id')
            ->select('designation.*','organisation.org_name')
            ->orderBy('designation.desig_id','desc')
            ->get();
        return view('designation.index')->with('datas', $datas);
    }

    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";

        $organisation_datas = new Organisation;
        $organisation_datas = $organisation_datas->orderBy('org_name','asc')->get();

        $data = new Designation;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('designation.add')->with(compact('hidden_input_purpose','hidden_input_id','data','organisation_datas'));
    }

    public function store(Request $request){
        $purpose="add";
        $desig = new Designation;

        if(isset($request->edit_id)){
            $desig = $desig->find($request->edit_id);
            if(count($desig)!=0){
                $purpose="edit";
            }
        }

        $desig->name= $request->name;
        $desig->org_id = '1';
        $desig->created_by = '1';
        $desig->updated_by = '1';

        if(Designation::where('name', $request->name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This designation '.$request->name.' is already exist !');
        }
        else if($desig->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Designation details has been saved');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('designation');
    }

    public function delete(Request $request){
        if(Designation::find($request->desig_id)){
            Designation::where('desig_id',$request->desig_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }

        return redirect('designation');
    }
}
