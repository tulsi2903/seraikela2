<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UoM_Type;
use App\GeoStructure;


class UoMType_Controller extends Controller
{
    public function index(){

        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod22"]["add"]&&!$desig_permissions["mod22"]["edit"]&&!$desig_permissions["mod22"]["view"]&&!$desig_permissions["mod22"]["del"]){
            return back();
        }
        $datas = uom_type::orderBy('uom_type_id','desc')->get();
        return view('uom_type.index',compact('datas'));
    }


    public function store(Request $request){
        $purpose="add";
        $uom_type = new UoM_Type;

        if(isset($request->edit_id)){
            $uom_type = $uom_type->find($request->edit_id);
            if(count($uom_type)!=0){
                $purpose="edit";
            }
        }

        $uom_type->uom_type_name= $request->uom_type_name;
        $uom_type->created_by = session()->get('user_id');
        $uom_type->updated_by = session()->get('user_id');

        if(UoM_Type::where('uom_type_name',$request->uom_type_name)->first()){
            if($purpose=="edit"){//check here if already exist cany edit
                $tmp = UoM_Type::where('uom_type_name',$request->uom_type_name)->first();
                if($tmp->uom_type_id!=$request->edit_id){
                    session()->put('alert-class','alert-danger');
                    session()->put('alert-content','This UoM Type '.$request->uom_type_name.' already exist !');
                }
                else{
                    if($uom_type->save()){
                        session()->put('alert-class','alert-success');
                        if($purpose=="edit"){
                            session()->put('alert-content','UOM details edited successfully!');
                        }
                        else{
                            session()->put('alert-content','UOM details have been successfully submitted !');
                        }
                    }
                    else{
                        session()->put('alert-class','alert-danger');
                        session()->put('alert-content','Something went wrong while adding new details !');
                    }
                }
            }
            else{//for added new uom type check dublicate
                session()->put('alert-class','alert-danger');
                session()->put('alert-content','This UoM Type'.$request->uom_type_name.' already exist !');
            }
        }
        else if($uom_type->save()){
            session()->put('alert-class','alert-success');
            if($purpose=="edit"){
                session()->put('alert-content','UOM details edited successfully!');
            }
            else{
                session()->put('alert-content','UOM details have been successfully submitted !');
            }
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('uom_type');
    }

    public function delete(Request $request){
        if(UoM_Type::find($request->uom_type_id)){
            UoM_Type::where('uom_type_id',$request->uom_type_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('uom_type');
    }

    public function show_block()
    {
        $block_datas = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->where('level_id', '=', '3')->get();
        return view('test.index',compact('block_datas'));
       
    }
    public function show_panchayat_datas(Request $request)
    {
        $datas = GeoStructure::where('bl_id', $request->block_id)->get();
        return $datas;
    }























}
