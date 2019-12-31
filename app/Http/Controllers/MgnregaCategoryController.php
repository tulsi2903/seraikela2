<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MgnregaCategory;



class MgnregaCategoryController extends Controller
{
  
    public function index(){
        $datas = MgnregaCategory::orderBy('mgnrega_category_id','desc')->get();
        return view('mnrega.index')->with('datas', $datas);
    }


    public function store(Request $request){
        $purpose="add";
        $Mnrega = new MgnregaCategory;

        if(isset($request->edit_id)){
            $Mnrega = $Mnrega->find($request->edit_id);
            if(count($Mnrega)!=0){
                $purpose="edit";
            }
        }

        $Mnrega->mgnrega_category_name= $request->mgnrega_category_name;
        $Mnrega->created_by = '1';
        $Mnrega->updated_by = '1';


        if(MgnregaCategory::where('mgnrega_category_name',$request->mgnrega_category_name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This '.$request->mgnrega_category_name.' already exist !');
        }
        else if($Mnrega->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Added details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }
        return redirect('mgnrega');
    }

    public function delete(Request $request){
        if(MgnregaCategory::find($request->mgnrega_category_id)){
            MgnregaCategory::where('mgnrega_category_id',$request->mgnrega_category_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('mgnrega');
    }




}
