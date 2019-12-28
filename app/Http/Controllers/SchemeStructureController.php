<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SchemeStructure;
use App\Department;
use App\SchemeType;
use App\Uom;
use App\SchemeIndicator;
use App\SchemeAsset;
use App\Group;

class SchemeStructureController extends Controller
{
    public function index(){
        $datas = SchemeStructure::leftJoin('department', 'scheme_structure.dept_id', '=', 'department.dept_id')
                    ->select('scheme_structure.*','department.dept_name')
                    ->orderBy('scheme_structure.scheme_id','desc')
                    ->get();

        // return $datas;
        return view('scheme-structure.index')->with('datas', $datas);
    }

    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";

        
        $scheme_types = SchemeType::orderBy('sch_type_name','asc')->get();
        $departments = Department::orderBy('dept_name')->get();
        $uoms = Uom::orderBy('uom_name','asc')->get();
        $scheme_asset_datas = SchemeAsset::select("scheme_asset_id","scheme_asset_name")->get();
        $scheme_group_datas = Group::select('scheme_group_id','scheme_group_name')->get();
       

        $data = new SchemeStructure;

        $indicator_datas = [];

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
            if($data){
                $indicator_datas = SchemeIndicator::where('scheme_id',$data->scheme_id)->get();
            }
        }

        return view('scheme-structure.add')->with(compact('hidden_input_purpose','hidden_input_id','data','indicator_datas','department_datas','scheme_types','departments','uoms','scheme_asset_datas','scheme_group_datas'));
    }

    public function view(Request $request){

        $request->scheme_id;
        $scheme_types = SchemeType::orderBy('sch_type_name','asc')->first();
        $departments = Department::orderBy('dept_name')->first();
        $uoms = Uom::orderBy('uom_name','asc')->first();
        $scheme_details = SchemeStructure::where('scheme_id',$request->scheme_id)->first();
        $asset_scheme = SchemeAsset::orderBy('scheme_asset_name')->first();
       

        if($scheme_details->is_active=='1')
        {
            $scheme_details->is_active = 'Yes';
        }
        else
        {
            $scheme_details->is_active = 'No';
        }
        
        $indicator_datas = SchemeIndicator::leftJoin('uom','scheme_indicator.uom','=','uom.uom_id')
                                            ->select('scheme_indicator.*','uom.uom_name')
                                            ->where('scheme_indicator.scheme_id',$request->scheme_id)->get();

        

        return view('scheme-structure.view')->with(compact('data','indicator_datas','department_datas','scheme_types','departments','uoms','scheme_details','asset_scheme'));
    }

    public function store(Request $request){
        $upload_directory = "public/uploaded_documents/schemes/";

        // return $request;
        $scheme_structure = new SchemeStructure;

        if($request->hidden_input_purpose=="edit"){
            $scheme_structure = $scheme_structure->find($request->hidden_input_id);
        }
        $scheme_structure->org_id = "1";
        $scheme_structure->scheme_related =$request->scheme_related;
        $scheme_structure->scheme_group_id = $request->scheme_group_id;
        $scheme_structure->scheme_name = $request->scheme_name;
        $scheme_structure->scheme_short_name = $request->scheme_short_name;
        $scheme_structure->scheme_asset_id = $request->scheme_asset_id;
        $scheme_structure->status = $request->status;
        $scheme_structure->dept_id = $request->dept_id;
        $scheme_structure->scheme_type_id = $request->scheme_type_id;
        $scheme_structure->description = $request->description;
        $scheme_structure->created_by = "1";
        $scheme_structure->updated_by = "1";
      
        // scheme attachment
        if($request->hasFile('attachment')){
            $file = $request->file('attachment');
            $attachment_tmp_name = "scheme-attachments-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $attachment_tmp_name);   // move the file to desired folder
            $scheme_structure->attachment = $upload_directory.$attachment_tmp_name;    // assign the location of folder to the model

            // deleteprevious attachment
            if($request->hidden_input_purpose=="edit")
            {
                if(file_exists($scheme_structure->attachment)){
                    unlink($scheme_structure->attachment);
                }
            }
            $scheme_structure->attachment = $upload_directory.$attachment_tmp_name;    // assign the location of folder to the model
        }

        else{
            if($request->hidden_input_purpose=="add"){
                $scheme_structure->attachment = "";
            }
            else if($request->hidden_input_purpose=="edit"&&$request->scheme_attachment_delete){ // edit
                $scheme_structure->attachment = "";
            }
        }

        // to previous attachment if delete clicked
        if($request->scheme_attachment_delete){
            if(file_exists($request->scheme_attachment_delete)){
                unlink($request->scheme_attachment_delete);
            }
        }

        // scheme logo
        if($request->hasFile('scheme_logo')){
            $file = $request->file('scheme_logo');
            $scheme_logo_tmp_name = "scheme-logo-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $scheme_logo_tmp_name); //  move file
            $scheme_structure->scheme_logo = $upload_directory.$scheme_logo_tmp_name; // assign

            //delete previous scheme logo
        if($request->hidden_input_purpose=="edit")
        {
            if(file_exists($scheme_structure->scheme_logo)){
                unlink($scheme_structure->scheme_logo);
            }
            $scheme_structure->scheme_logo = $upload_directory.$scheme_logo_tmp_name; 
        }

        }
        else{
            if($request->hidden_input_purpose=="add"){
                $scheme_structure->scheme_logo="";
            }
            else if($request->hidden_input_purpose=="edit" && $request->scheme_logo_delete){
                $scheme_structure->scheme_logo = "";

            }
        }

        // to previous scheme_logo if delete clicked
        if($request->scheme_logo_delete){
            if(file_exists($request->scheme_logo_delete)){
                unlink($request->scheme_logo_delete);
            }
        }
        
        // for scheme_map_maker
        if($request->hasFile('scheme_map_marker'))
        {
            $file = $request->file('scheme_map_marker');
            $scheme_map_marker_tmp_name = "scheme-map-marker-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $scheme_map_marker_tmp_name); //  move file
            $scheme_structure->scheme_map_marker = $upload_directory.$scheme_map_marker_tmp_name; // assign

            if($request->hidden_input_purpose=="edit")
            {
                if(file_exists($scheme_structure->scheme_map_marker)){
                    unlink($scheme_structure->scheme_map_marker);
                }
                $scheme_structure->scheme_map_marker = $upload_directory.$scheme_map_marker_tmp_name; 
            }
        }
        else{
            if($request->hidden_input_purpose=="add"){
                $scheme_structure->scheme_map_marker="";
            }
            else if($request->hidden_input_purpose=="edit" && $request->scheme_map_marker_delete){
                $scheme_structure->scheme_map_marker = "";

            }
        }

        // to previous scheme_map_marker if delete clicked
        if($request->scheme_map_marker_delete){
            if(file_exists($request->scheme_map_marker_delete)){
                unlink($request->scheme_map_marker_delete);
            }
        }

        
        // saving/response
        if(SchemeStructure::where('scheme_name',$request->scheme_name)->first()&&$request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This scheme '.$request->scheme_name.' already exist !');
        }
 
        else if($scheme_structure->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }
        
        return redirect('scheme-structure');
    }
   
    public function delete(Request $request){
        if(SchemeStructure::find($request->scheme_id)){
           SchemeStructure::where('scheme_id',$request->scheme_id)->delete();
          
           SchemeIndicator::where('scheme_id',$request->scheme_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('scheme-structure');
    }
}
