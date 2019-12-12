<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SchemeStructure;
use App\Department;
use App\SchemeType;
use App\Uom;
use App\SchemeIndicator;

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

        return view('scheme-structure.add_2')->with(compact('hidden_input_purpose','hidden_input_id','data','indicator_datas','department_datas','scheme_types','departments','uoms'));
    }

    public function view(Request $request){

        $request->scheme_id;
        $scheme_types = SchemeType::orderBy('sch_type_name','asc')->first();
         $departments = Department::orderBy('dept_name')->first();
          $uoms = Uom::orderBy('uom_name','asc')->first();
        $scheme_details = SchemeStructure::where('scheme_id',$request->scheme_id)->first();
       

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

        

        return view('scheme-structure.view')->with(compact('data','indicator_datas','department_datas','scheme_types','departments','uoms','scheme_details'));
    }

    public function store(Request $request){
        //$response = "failed";
        // return $request;
        $scheme_structure = new SchemeStructure;

        if($request->hidden_input_purpose=="edit"){
            $scheme_structure = $scheme_structure->find($request->hidden_input_id);

        }
        $scheme_structure->org_id = 1;
        $scheme_structure->scheme_name =$request->scheme_name;
        $scheme_structure->scheme_short_name = $request->scheme_short_name;
        $scheme_structure->is_active = $request->is_active;
        $scheme_structure->scheme_link_id = '1';
        $scheme_structure->dept_id =  $request->dept_id;
        $scheme_structure->scheme_type_id = $request->scheme_type_id;
       
        // $scheme_structure->independent = $request->independent;
     
     //    $scheme_structure->planned_sd = date("Y-m-d",strtotime($request->planned_sd));
     // if($request->planned_sd== null){ $scheme_structure->planned_sd = "";}
     //    $scheme_structure->planned_ed = date("Y-m-d",strtotime($request->planned_ed));
     // if($request->planned_ed== null){ $scheme_structure->planned_ed = "";}
     //    $scheme_structure->actual_sd = date("Y-m-d",strtotime($request->actual_sd));
     // if($request->actual_sd== null){ $scheme_structure->actual_sd = "";}
     //    $scheme_structure->actual_ed = date("Y-m-d",strtotime($request->actual_ed));
     // if($request->actual_ed== null){ $scheme_structure->actual_ed = "";}
        $scheme_structure->description = $request->description;
        if($request->description==""){ $scheme_structure->description = ""; }
      
      
        $scheme_structure->attachment = "";
         $i = 0;
         if($request->hasFile('attachment'))
         {

            foreach($request->file('attachment') as $file){

                $imageName =$file->getClientOriginalName(). $i . '.' . $file->getClientOriginalExtension();

                // move the file to desired folder
                $file->move('public/uploaded_documents/', $imageName);

                // assign the location of folder to the model
                $scheme_structure->attachment.=":".$imageName;


                $i++;

            } 
            
            $scheme_structure->attachment = ltrim($scheme_structure->attachment,":");
        }

        // for scheme_logo
        $scheme_structure->scheme_logo = "";
        if($request->hasFile('scheme_logo'))
        {
            //
            $file = $request->file('scheme_logo');

            $scheme_structure->scheme_logo = $file->getClientOriginalName();
            $destinationPath = 'public/images';
            $file->move($destinationPath, $file->getClientOriginalName()); 


        }
        else{
            if($request->hidden_input_purpose=="edit")
            {
                $scheme_structure->scheme_logo = $request->hidden_input_scheme_logo;
            }
            else if($request->hidden_input_purpose=="add")
            {
                // error has to return
            }
        }

        // for scheme_map_maker
        $scheme_structure->scheme_map_marker = "";
        if($request->hasFile('scheme_map_marker'))
        {
            //
            $file = $request->file('scheme_map_marker');

            $scheme_structure->scheme_map_marker = $file->getClientOriginalName();
            $destinationPath = 'public/images';
            $file->move($destinationPath, $file->getClientOriginalName()); 
        }
        else{
            if($request->hidden_input_purpose=="edit")
            {
                $scheme_structure->scheme_map_marker = $request->hidden_input_map_marker;
            }
            else if($request->hidden_input_purpose=="add")
            {
                // error has to return
            }
        }
        
               
        $scheme_structure->created_by = '1';
        $scheme_structure->updated_by = '1';

        
       
        if(SchemeStructure::where('scheme_name',$request->scheme_name)->first()&&$request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This scheme '.$request->scheme_name.' already exist !');
        }
 
        else if($scheme_structure->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme details have been successfully submitted !');

            
            // for inserting data in the scheme_indicator table   
            if(isset($request->indicator_name)){

                // delete existing data if edit is called
                if($request->hidden_input_purpose=="edit"){
                    $delete_indicator_query = SchemeIndicator::where('scheme_id', $request->hidden_input_id)->delete(); 
                }

                $indicator_name=$request->indicator_name;
                foreach($request->indicator_name as $key_indi=>$value){
                    $scheme_indicator = new SchemeIndicator;
                    $scheme_indicator->indicator_name = @$request->indicator_name[$key_indi];
                    $scheme_indicator->uom = @$request->uom[$key_indi];
                    $scheme_indicator->performance = @$request->performance[$key_indi] ?? 0;                
                    $scheme_indicator->scheme_id= $scheme_structure->scheme_id;
                    $scheme_indicator->created_by = '1';
                    $scheme_indicator->updated_by = '1';
                    $scheme_indicator->save();
                }
            }
               
               
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
