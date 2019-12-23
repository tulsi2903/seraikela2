<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Organisation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DisneyplusExport;
use DB;
use PDF;

class DepartmentController extends Controller
{
    public function index(){
        $datas = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
            ->select('department.*','organisation.org_name')
            ->orderBy('department.dept_id','desc')
            ->get();

        return view('department.index')->with(compact('datas'));
    }

    // to open form (for add & edit) ---- (deprecated)
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
        $purpose="add";
        $dept = new Department;

        if(isset($request->edit_id)){
            $dept = $dept->find($request->edit_id);
            if(count($dept)!=0){
                $purpose="edit";
            }
        }

        $dept->dept_name= $request->dept_name;
        $dept->is_active = $request->is_active;
        $dept->dept_icon = $request->dept_icon;
        $dept->org_id = '1';
        $dept->created_by = '1';
        $dept->updated_by = '1';

        if(Department::where('dept_name',$request->dept_name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This department '.$request->dept_name.' already exist !');
        }
        else if($dept->save()){ //$purpose == add & no duplicate entry || $purpose == edit
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

    public function exportExcelFunctiuon()
    {
        return Excel::download(new DisneyplusExport, 'Departments-Sheet.xls');
    }
    
    public function exportpdfFunctiuon()
    {
        $department =  DB::table('department')
            ->join('organisation','department.org_id','=','organisation.org_id')
            ->select('department.dept_id','department.dept_name','organisation.org_name','department.is_active','department.created_at')->get();
        $pdf = PDF::loadView('department/Createpdfs',compact('department'));
        return $pdf->download('Department.pdf');
    }
}
