<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;

class ModuleController extends Controller
{

   public function index()
   {
    $datas = Module::orderBy('mod_id','desc')->get();
    return view('module.index')->with('datas', $datas);
    
   }
   public function add(Request $request)
     {
         $hidden_input_purpose = "add";
         $hidden_input_id= "NA";
         $data = new Module;
         
         if(isset($request->purpose) && ($request->id)){
             $hidden_input_purpose = $request->purpose;
             $hidden_input_id = $request->id;
             $data = $data->find($request->id);
         }
         return view('module.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
     }
     public function store(Request $request){
        $module = new Module;

        if($request->hidden_input_purpose=="edit"){
            $module = $module->find($request->hidden_input_id);
        }

        $module->mod_name= $request->module_name;
        
        $module->created_by = '1';
        $module->updated_by = '1';

        if($module->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','A new module details have been successfully submitted');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('module');
    }

    public function delete(Request $request){
        if(Module::find($request->mod_id)){
            Module::where('mod_id',$request->mod_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }

        return redirect('module');
    }

}
