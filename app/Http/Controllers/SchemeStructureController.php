<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SchemeStructure;
use App\GeoStructure;
use App\Department;
use App\SchemeType;
use App\Uom;
use App\SchemeIndicator;
use App\SchemeAsset;
use App\Group;
use PDF;
use DB;
use App\Exports\DefineSchemes;
use Maatwebsite\Excel\Facades\Excel;


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

        $scheme_type_datas = SchemeType::orderBy('sch_type_name','asc')->get();
        $department_datas = Department::orderBy('dept_name')->get();
        $scheme_group_datas = Group::get();
        $block_datas = GeoStructure::where('level_id', 3)->get();
        $scheme_asset_datas = SchemeAsset::get();
       
        $data = new SchemeStructure;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('scheme-structure.add')->with(compact('hidden_input_purpose','hidden_input_id','data','department_datas','scheme_type_datas','departments','scheme_group_datas','block_datas','scheme_asset_datas'));
    }

    public function view(Request $request){
        $data = SchemeStructure::leftJoin('department','scheme_structure.dept_id','=','department.dept_id')
                                        ->leftJoin('scheme_type','scheme_type.sch_type_id','=','scheme_structure.scheme_type_id')
                                        ->select('scheme_structure.*','department.dept_name',"scheme_type.sch_type_name")
                                        ->where('scheme_id',$request->scheme_id)
                                        ->first();

        return view('scheme-structure.view')->with(compact('data'));
    }

    public function get_panchayat_datas(Request $request){
        $block_id = $request->block_id;

        $panchayat_datas = GeoStructure::select('geo_id', 'geo_name')->where('bl_id', $block_id)->get();
        return $panchayat_datas;
    }

    public function store(Request $request){
        $upload_directory = "public/uploaded_documents/schemes/"; // directory to upload docs/icons etc related to scheme

        // return $request;
        $scheme_structure = new SchemeStructure;

        if($request->hidden_input_purpose=="edit"){
            $scheme_structure = $scheme_structure->find($request->hidden_input_id);
        }
        $scheme_structure->org_id = "1";
        $scheme_structure->scheme_name = $request->scheme_name;
        $scheme_structure->scheme_short_name = $request->scheme_short_name;
        $scheme_structure->status = $request->status;
        $scheme_structure->dept_id = $request->dept_id;
        $scheme_structure->scheme_type_id = $request->scheme_type_id;
        $scheme_structure->description = $request->description;
        $scheme_structure->created_by = "1";
        $scheme_structure->updated_by = "1";
      
        // scheme attachment
        if($request->hasFile('attachment')){
            // delete previous attachment
            if($request->hidden_input_purpose=="edit")
            {
                if(file_exists($scheme_structure->attachment)){
                    unlink($scheme_structure->attachment);
                }
            }

            $file = $request->file('attachment');
            $attachment_tmp_name = "scheme-attachments-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $attachment_tmp_name);   // move the file to desired folder
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
            //delete previous scheme logo
            if($request->hidden_input_purpose=="edit")
            {
                if(file_exists($scheme_structure->scheme_logo)){
                    unlink($scheme_structure->scheme_logo);
                }
            }

            $file = $request->file('scheme_logo');
            $scheme_logo_tmp_name = "scheme-logo-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $scheme_logo_tmp_name); //  move file
            $scheme_structure->scheme_logo = $upload_directory.$scheme_logo_tmp_name; // assign
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
            if($request->hidden_input_purpose=="edit")
            {
                if(file_exists($scheme_structure->scheme_map_marker)){
                    unlink($scheme_structure->scheme_map_marker);
                }
            }

            $file = $request->file('scheme_map_marker');
            $scheme_map_marker_tmp_name = "scheme-map-marker-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $scheme_map_marker_tmp_name); //  move file
            $scheme_structure->scheme_map_marker = $upload_directory.$scheme_map_marker_tmp_name; // assign
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
            $scheme_structure = SchemeStructure::find($request->scheme_id);
            if(file_exists($scheme_structure->attachment)){
                unlink($scheme_structure->attachment);
            }
            if(file_exists($scheme_structure->scheme_logo)){
                unlink($scheme_structure->scheme_logo);
            }
            if(file_exists($scheme_structure->scheme_map_marker)){
                unlink($scheme_structure->scheme_map_marker);
            }
            SchemeStructure::where('scheme_id',$request->scheme_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('scheme-structure');
    }

        // export to pdf and excel
        public function exportExcel_Scheme_structure()
        {
    
            return Excel::download(new DefineSchemes, 'Define_Schemes-Sheet.xls');
    
        }
        public function exportPDF_Scheme_structure()
        {
            $SchemeStructure_pdf = SchemeStructure::leftJoin('department', 'scheme_structure.dept_id', '=', 'department.dept_id')
                        ->select('scheme_structure.*','department.dept_name')
                        ->orderBy('scheme_structure.scheme_id','desc')->get();
          
             date_default_timezone_set('Asia/Kolkata');
             $SchemeStructureTime = date('d-m-Y H:i A');
             $pdf = PDF::loadView('department/Createpdfs',compact('SchemeStructure_pdf','SchemeStructureTime'));
             return $pdf->download('SchemeStructure.pdf');
    
        }
    
}
