<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeAsset;
use App\Uom;


class Scheme_Asset_Controller extends Controller
{

    public function index()
    {
        $datas = SchemeAsset::orderBy('scheme_asset_id','desc')->get();
     
        return view('scheme-asset.index',compact('datas'));

    }
    public function add(Request $request){

        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new SchemeAsset;

        $uom_datas = Uom::select('uom_id','uom_name')->get();

        if(isset($request->purpose)&&isset($request->id)){
            $data = $data->find($request->id);
            if($data){
                $hidden_input_purpose=$request->purpose;
                $hidden_input_id=$request->id;
            }
        }
       
        return view('scheme-asset.add')->with(compact('uom_datas','hidden_input_purpose','hidden_input_id','data'));
    }

   
    public function store(Request $request)
    {
        $scheme_asset = new SchemeAsset;

        if($request->hidden_input_purpose=="edit"){
            $scheme_asset = $scheme_asset->find($request->hidden_input_id);
        }

        $scheme_asset->scheme_asset_name = $request->scheme_asset_name;
        $scheme_asset->geo_related = $request->geo_related;
        
        if($request->geo_related!="")
        {
            $scheme_asset->multiple_geo_tags = $request->multiple_geo_tags;
            if($request->multiple_geo_tags!="")
            {   
                $scheme_asset->no_of_tags = $request->no_of_tags;
            }
            else
            {   
                $scheme_asset->no_of_tags = null;
            }
        }
        else{
            $scheme_asset->multiple_geo_tags = null;
            $scheme_asset->no_of_tags = null;
        }
       
        $scheme_asset->created_by = Auth::user()->id;
        $scheme_asset->updated_by = Auth::user()->id;

        $attribute = [];
        for($i=0;$i<count($request->attribute_name);$i++)
        {
            $tmp = ["id"=>uniqid(), "name"=>$request->attribute_name[$i], "uom"=>$request->attribute_uom[$i]];
            if($request->attribute_mandatory[$i]){
                $tmp["mandatory"] = $request->attribute_mandatory[$i];
            }
            else{
                $tmp["mandatory"] = '0';
            }
            // $tmp = [uniqid()=>["name"=>$request->attribute_name[$i], "uom"=>$request->attribute_uom[$i]]];
            array_push($attribute, $tmp);
        }
        $scheme_asset->attribute=serialize($attribute);
      
        // echo "<pre>";
        // print_r($request->toArray());
        // print_r($attribute);
        // exit;

        if(SchemeAsset::where('scheme_asset_name',$request->scheme_asset_name)->first() && $request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Scheme Asset '.$request->scheme_asset_name.' already exist !');
        }
        else if($scheme_asset->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme Asset have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('scheme-asset');
    }

    public function view(Request $request)
    {
        if(SchemeAsset::find($request->scheme_asset_id)){
            $data =  SchemeAsset::where('scheme_asset_id',$request->scheme_asset_id)->first();
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','No such scheme asset exists!');
            return redirect('scheme-asset');
        }
        $uom_datas = Uom::select('uom_id','uom_name')->get();
       
        return view('scheme-asset.view')->with(compact('data','uom_datas'));
    }



    public function delete(Request $request)
    {
        if(SchemeAsset::find($request->scheme_asset_id)){
            SchemeAsset::where('scheme_asset_id',$request->scheme_asset_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('scheme-asset');    
    }
    


}
