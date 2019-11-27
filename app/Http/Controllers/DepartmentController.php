<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Organisation;


class DepartmentController extends Controller
{
    //
    public function index(){
        $datas = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
            ->select('department.*','organisation.org_name')
            ->orderBy('department.dept_id','desc')
            ->get();

        return view('department.index')->with('datas', $datas);
    }

    // to open form (for add & edit)
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";

        $organisation_datas = new Organisation;
        $organisation_datas = $organisation_datas->orderBy('org_name','asc')->get();
        
        $data = new Department;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('department.add')->with(compact('hidden_input_purpose','hidden_input_id','data','organisation_datas'));
    }

    // to store in DB (add & edit)
    public function store(Request $request){
        $dept = new Department;

        if($request->hidden_input_purpose=="edit"){
            $dept = $dept->find($request->hidden_input_id);
        }

        $dept->dept_name= $request->dept_name;
        $dept->is_active = $request->is_active;
        $dept->org_id = $request->org_id;
        $dept->created_by = '1';
        $dept->updated_by = '1';

        if(Department::where('dept_name',$request->dept_name)->first()&&$request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This department '.$request->dept_name.' already exist !');
        }
        else if($dept->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Department details has been saved');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('department');
    }

    public function delete(Request $request){
        if(Department::find($request->dept_id)){
            Department::where('dept_id',$request->dept_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }

        return redirect('department');
    }
}
