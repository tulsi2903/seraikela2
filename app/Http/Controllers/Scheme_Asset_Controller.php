<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeAsset;




class Scheme_Asset_Controller extends Controller
{

    public function index()
    {
        $datas = SchemeAsset::orderBy('scheme_assets_id','desc')->get();
     
        return view('scheme-asset.index',compact('datas'));

    }
    // public function add(){
        
    // }
    public function store(Request $request)
    {
        $purpose="add";
        $scheme_asset = new SchemeAsset;

        if(isset($request->edit_id)){
            $scheme_asset = $scheme_asset->find($request->edit_id);
            if(count($scheme_asset)!=0){
                $purpose="edit";
            }
        }

        $scheme_asset->scheme_assets_name= $request->scheme_asset;
        $scheme_asset->created_by = '1';
        $scheme_asset->updated_by = '1';


        if(SchemeAsset::where('scheme_assets_name',$request->scheme_asset)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','SchemeAsset'.$request->scheme_assets_name.' already exist !');
        }
        else if($scheme_asset->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','SchemeAsset have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('scheme-asset');
    }



    public function delete(Request $request)
    {
        if(SchemeAsset::find($request->scheme_assets_id)){
            SchemeAsset::where('scheme_assets_id',$request->scheme_assets_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('scheme-asset');    
    }
    


}
