<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DesignationPermission;
use App\Designation;
use App\Module;

class DesignationPermissionController extends Controller
{
    public function index()
   {
     $datas = DesignationPermission::orderBy('desig_permission_id','desc')->get();

    $datas = DesignationPermission::leftJoin('designation', 'desig_permission.desig_id', '=', 'designation.desig_id')
              ->leftJoin('module', 'desig_permission.mod_id', '=', 'module.mod_id')
              ->select("desig_permission.*","designation.name","module.mod_name")
              ->get();

    return view('designation-permission.index')->with('datas', $datas);
    
   }
   public function add(Request $request)
     {
         $hidden_input_purpose = "add";
         $hidden_input_id= "NA";

         

         $data = new DesignationPermission;
         $designations = Designation::orderBy('name')->get();
         $module_names = Module::orderBy('mod_name')->get();

         
         if(isset($request->purpose) && ($request->id)){
             $hidden_input_purpose = $request->purpose;
             $hidden_input_id = $request->id;
             $data = $data->find($request->id);
         }
         return view('designation-permission.add')->with(compact('hidden_input_purpose','hidden_input_id','data','designations','module_names'));
     }
     public function store(Request $request){
        $designation_permission = new DesignationPermission;

        if($request->hidden_input_purpose=="edit"){
            $designation_permission = $designation_permission->find($request->hidden_input_id);
        }

        $designation_permission->mod_id = $request->mod_id;
        $designation_permission->desig_id = $request->desig_id;
        
        if( $request->add=="")
        { $designation_permission->add = '0'; }
        else{ $designation_permission->add = $request->add;}
       if($request->edit =="")
       {$designation_permission->edit='0';}
       else{$designation_permission->edit = $request->edit;}
        if($request->view==""){$designation_permission->view = '0'; }
       else{$designation_permission->view = $request->view;} 
       if($request->delete=="")
       {$designation_permission->del='0';} 
       else{$designation_permission->del = $request->delete;}
        
        $designation_permission->created_by = '1';
        $designation_permission->updated_by = '1';

      
        if($designation_permission->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Designation permission have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('designation-permission');
    }

    public function delete(Request $request){
        if(DesignationPermission::find($request->desig_permission_id)){
            DesignationPermission::where('desig_permission_id',$request->desig_permission_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }

        return redirect('designation-permission');
    }

}
