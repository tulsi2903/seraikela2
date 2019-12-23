<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Asset;
use App\Department;
use App\asset_cat;
use App\asset_subcat;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetSectionExport;
use PDF;

class AssetController extends Controller
{
    public function index(){
       
        
         $datas = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                        ->leftJoin('asset_cat','asset.category_id','=','asset_cat.asset_cat_id')
                        ->leftJoin('asset_subcat','asset.subcategory_id','=','asset_subcat.asset_sub_id')
                        ->select('asset.*','department.dept_name','asset_cat.asset_cat_name','asset_subcat.asset_sub_cat_name')
                        ->orderBy('asset.asset_id','desc')
                        ->get();

        $departments = Department::orderBy('dept_name')->get();

        $categories = asset_cat::orderBy('asset_cat_name')->get();

        $sub_categories = asset_subcat::orderBy('asset_sub_cat_name')->get();
        
        return view('asset.index')->with(compact('datas','departments','categories','sub_categories'));
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
        $toReturn["response"] = "Something went wrong! Please try again"; // response pre defined as error
        $asset = new Asset;

        if($request->hidden_input_purpose=="edit"){
            $asset = $asset->find($request->hidden_input_id);
        }

        $asset->asset_name = $request->asset_name;
        
        if($request->hasFile('asset_icon')){
            $upload_directory = "public/uploaded_documents/assets/";
            $file = $request->file('asset_icon');
            $asset_icon_tmp_name = "assets-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $asset_icon_tmp_name);   // move the file to desired folder

            // deleteprevious icon
            if($request->hidden_input_purpose=="edit")
            {
                if(file_exists($asset->asset_icon)){
                    unlink($asset->asset_icon);
                }
            }
            $asset->asset_icon = $upload_directory.$asset_icon_tmp_name;    // assign the location of folder to the model
        }
        else{
            if($request->hidden_input_purpose=="add"){
                $asset->asset_icon = "";
            }
            else if($request->hidden_input_purpose=="edit"&&$request->asset_icon_delete){ // edit
                $asset->asset_icon = "";
            }
        }

        // to previous icon if delete clicked
        if($request->asset_icon_delete){
            if(file_exists($request->asset_icon_delete)){
                unlink($request->asset_icon_delete);
            }
        }

        $asset->movable = $request->movable;
        $asset->dept_id = $request->dept_id;
        $asset->org_id = '1';
        $asset->category_id = $request->category;
        $asset->subcategory_id = $request->subcategory;
        
        $asset->created_by = '1';
        $asset->updated_by = '1';

   

        if(Asset::where('asset_name',$request->asset_name)->where('dept_id',$request->dept_id)->first()&&$request->hidden_input_purpose!="edit"){
            // session()->put('alert-class','alert-danger');
            // session()->put('alert-content','This asset '.$request->asset_name.' already exist !');
            $toReturn["response"] = "This asset ".$request->asset_name." already exists!";
            $toReturn["asset_name_error"] = "This asset name is already exist in selected department"; 
        }
        else if($asset->save()){
            // session()->put('alert-class','alert-success');
            // session()->put('alert-content','Asset details have been successfully submitted !');
            $toReturn["response"] = "success";
        }
        else{
            // session()->put('alert-class','alert-danger');
            // session()->put('alert-content','Something went wrong while adding new details !');
            $toReturn["response"] = "Something went wrong while adding new asset!";
        }
        

        // return redirect('asset');
        return $toReturn;
    }

    public function get_asset_details(Request $request){
        $response = "no_data";

        if(isset($request->asset_id)){
            if(Asset::find($request->asset_id)){
                $data = Asset::find($request->asset_id);

                // to get category all rows (datas) by movable/inmovable stored in DB
                if(isset($data->movable)){
                    $category_datas = asset_cat::select("asset_cat_id","asset_cat_name")->where('movable', $data->movable)->get();
                }
                
                // to get sub category all rows (datas) by category selected/stored in DB
                if(isset($data->category_id)){
                    $subcategory_datas = asset_subcat::select("asset_sub_id","asset_sub_cat_name")->where('asset_cat_id', $data->category_id)->get();
                }

                $response = "success";
            }
        }

        return ["response"=>$response, "asset_data"=>$data, "category_datas"=>$category_datas, "subcategory_datas"=>$subcategory_datas];
    }

    public function get_category(Request $request)
    {
        $data = asset_cat::where('movable',$request->movable)->get();
        return["category_data"=>$data];

    }
    
    public function get_subcategory(Request $request)
    {
        $data = asset_subcat::where('asset_cat_id',$request->asset_cat_id)->get();
        return["subcategory_data"=>$data];

    }


    public function delete(Request $request){
        if(Asset::find($request->asset_id)){
            Asset::where('asset_id',$request->asset_id)->delete();
            // Todo*: also delete its original icon/ already existed icon
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

public function delete_subcat(Request $request){
    // return $request->asset_sub_id;
    if(asset_subcat::find($request->asset_sub_id)){
        asset_subcat::where('asset_sub_id',$request->asset_sub_id)->delete();
        session()->put('alert-class','alert-success');
        session()->put('alert-content','Deleted successfully !');
    }
    return redirect('asset_subcat');
}

public function exportExcelFunctiuonforasset()
{
    return Excel::download(new AssetSectionExport, 'Assetdata-Sheet.xls');
}

public function exportpdfFunctiuonforasset()
{
    $Assetdata = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                    ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
                    ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
                    ->select('asset.*', 'department.dept_name', 'asset_cat.asset_cat_name', 'asset_subcat.asset_sub_cat_name')
                    ->orderBy('asset.asset_id', 'desc')
                    ->get();
    date_default_timezone_set('Asia/Kolkata');
    $AssetdateTime = date('d-m-Y H:i A');
    $pdf = PDF::loadView('department/Createpdfs',compact('Assetdata','AssetdateTime'));
    return $pdf->download('Assetdata.pdf');
}
}
