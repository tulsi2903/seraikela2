<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Asset;
use App\Department;
use App\asset_cat;
use App\asset_subcat;

class AssetController extends Controller
{
    public function index(){
       
        
         $datas = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                        ->leftJoin('asset_cat','asset.category_id','=','asset_cat.asset_cat_id')
                        ->leftJoin('asset_subcat','asset.subcategory_id','=','asset_subcat.asset_sub_id')
                        ->select('asset.*','department.dept_name','asset_cat.asset_cat_name','asset_subcat.asset_sub_cat_name')
                        ->orderBy('asset.asset_id','desc')
                        ->get();
        
        return view('asset.index')->with('datas', $datas);
    }
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        
        $departments = Department::orderBy('dept_name')->get();

        $categorys = asset_cat::orderBy('asset_cat_name')->get();

        $sub_categorys = asset_subcat::orderBy('asset_sub_cat_name')->get();
        
        $data = new Asset;

        if(isset($request->purpose) && isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('asset.add')->with(compact('hidden_input_purpose','hidden_input_id','data','departments','categorys','sub_categorys'));
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
        $asset->category_id = $request->category;
        $asset->subcategory_id = $request->subcategory;
        
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

    public function index_cat(){
        $datas = asset_cat::orderBy('asset_cat.asset_cat_id','desc')
        ->get();

        return view('asset.category.index')->with('datas', $datas);
}
public function add_cat(Request $request){
    $hidden_input_purpose = "add";
    $hidden_input_id= "NA";
    $data = new asset_cat;

    if(isset($request->purpose) && isset($request->id)){
        $hidden_input_purpose=$request->purpose;
        $hidden_input_id=$request->id;
        $data = $data->find($request->id);
    }
    // return $data;
    return view('asset.category.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
}

public function store_cat (Request $request){
    
    $asset_cat = new asset_cat;
    if($request->hidden_input_purpose=="edit"){
        $asset_cat = $asset_cat->find($request->hidden_input_id);
    }
    $asset_cat->asset_cat_name = $request->asset_cat_name;
    if($request->asset_cat_description=="")
    {
        $asset_cat->asset_cat_description = ""; 
    }
    else{
        $asset_cat->asset_cat_description=$request->asset_cat_description;
    }
   
    $asset_cat->movable = $request->movable;
    $asset_cat->created_by = '1';
    $asset_cat->updated_by = '1';
    $asset_cat->status=1;
    $asset_cat->org_id=1;
    if(asset_cat::where('asset_cat_name',$request->asset_cat_name)->first()&&$request->hidden_input_purpose!="edit"){
    session()->put('alert-class','alert-danger');
    session()->put('alert-content','This asset '.$request->asset_cat_name.' already exist !');
    }
    else if($asset_cat->save()){
        session()->put('alert-class','alert-success');
        session()->put('alert-content','Asset Category  details have been successfully submitted !');
    }
    else{
        session()->put('alert-class','alert-danger');
        session()->put('alert-content','Something went wrong while adding new details !');
    }
    return redirect('assetcat');
}
public function delete_cat(Request $request,$id=""){
    // return $request->asset_cat_id;
    if(asset_cat::find($request->asset_cat_id)){
        asset_cat::where('asset_cat_id',$request->asset_cat_id)->delete();
        session()->put('alert-class','alert-success');
        session()->put('alert-content','Deleted successfully !');
    }
    return redirect('assetcat');
}
public function index_subcat(){

    $datas = asset_subcat::orderBy('asset_subcat.asset_sub_id','desc')
    ->get();

    return view('asset.subcategory.index')->with('datas', $datas);
}
public function add_subcat(Request $request){
    $hidden_input_purpose = "add";
    $hidden_input_id= "NA";
    $data = new asset_subcat;
    $asset_cat = asset_cat::orderBy('asset_cat_id')->get();

    if(isset($request->purpose) && isset($request->id)){
        $hidden_input_purpose=$request->purpose;
        $hidden_input_id=$request->id;
        $data = $data->find($request->id);
    }
    // return $data;
    return view('asset.subcategory.add')->with(compact('hidden_input_purpose','hidden_input_id','data','asset_cat'));
}
public function store_subcat(Request $request){
    // return $request;
    $asset_subcat = new asset_subcat;
    if($request->hidden_input_purpose=="edit"){
        $asset_subcat = $asset_subcat->find($request->hidden_input_id);
    }
    $asset_subcat->asset_cat_id=$request->asset_cat_id;
    $asset_subcat->asset_sub_cat_name = $request->asset_sub_cat_name;
    if($request->asset_sub_cat_description=="")
    {
        $asset_subcat->asset_sub_cat_description="";
    }
    else{
        $asset_subcat->asset_sub_cat_description=$request->asset_sub_cat_description;
    }
    
   
    $asset_subcat->created_by = '1';
    $asset_subcat->updated_by = '1';
    $asset_subcat->status=1;
    $asset_subcat->org_id=1;
    if(asset_subcat::where('asset_sub_cat_name',$request->asset_sub_cat_name)->where('asset_cat_id',$request->asset_cat_id)->first()&&$request->hidden_input_purpose!="edit"){
    session()->put('alert-class','alert-danger');
    session()->put('alert-content','This asset '.$request->asset_subcat_name.' already exist !');
    }
    else if($asset_subcat->save()){
        session()->put('alert-class','alert-success');
        session()->put('alert-content','Asset Sub Category  details have been successfully submitted !');
    }
    else{
        session()->put('alert-class','alert-danger');
        session()->put('alert-content','Something went wrong while adding new details !');
    }
    return redirect('asset_subcat');
}

public function get_subcategory_name(Request $request)
    {
    	$data = asset_subcat::where('asset_cat_id',$request->asset_cat_id)->get();
    	return["subcategory_data"=>$data];

    }
public function delete_subcat(Request $request){
    // return $request->asset_sub_id;
    if(asset_subcat::find($request->asset_sub_id)){
        asset_subcat::where('asset_sub_id',$request->asset_sub_id)->delete();
        session()->put('alert-class','alert-success');
        session()->put('alert-content','Deleted successfully !');
    }
    return redirect('asset_subcat');
}
}
