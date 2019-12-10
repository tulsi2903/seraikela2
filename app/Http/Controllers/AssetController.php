<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Asset;
use App\Department;

class AssetController extends Controller
{
    public function index(){
       
        
         $datas = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                  ->select("asset.*","department.dept_name")
                  ->orderBy('asset.asset_id','desc')
                  ->get();
        
        return view('asset.index')->with('datas', $datas);
    }
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        
        $departments = Department::orderBy('dept_name')->get();
        
        $data = new Asset;

        if(isset($request->purpose) && isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('asset.add')->with(compact('hidden_input_purpose','hidden_input_id','data','departments'));
    }

    public function store(Request $request){
        //$response = "failed";
        $asset = new Asset;

        if($request->hidden_input_purpose=="edit"){
            $asset = $asset->find($request->hidden_input_id);
        }

        $asset->asset_name = $request->asset_name;
        $asset->movable = $request->movable;
        $asset->dept_id = $request->dept_id;
        $asset->org_id = 1;
        
        $asset->created_by = '1';
        $asset->updated_by = '1';
       
        if(Asset::where('asset_name',$request->asset_name)->first()&&$request->hidden_input_purpose!="edit"){
         session()->put('alert-class','alert-danger');
         session()->put('alert-content','This asset '.$request->asset_name.' already exist !');
        }
 
        else if($asset->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Asset details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }
        

        return redirect('asset');
    }

    public function delete(Request $request){
        if(Asset::find($request->asset_id)){
            Asset::where('asset_id',$request->asset_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('asset');
    }
}
