<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\SchemeStructure;
use App\GeoStructure;
use App\Department;
use App\SchemeType;
use App\Uom;
use App\SchemeIndicator;
use App\SchemePerformance;
use App\SchemeAsset;
use App\Group;
use PDF;
use DB;
use App\Exports\DefineSchemes;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Null_;

class SchemeStructureController extends Controller
{
    public function index()
    {
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod15"]["add"]&&!$desig_permissions["mod15"]["edit"]&&!$desig_permissions["mod15"]["view"]&&!$desig_permissions["mod15"]["del"]){
            return back();
        }

        $datas = SchemeStructure::leftJoin('department', 'scheme_structure.dept_id', '=', 'department.dept_id')
            ->select('scheme_structure.*', 'department.dept_name')
            ->orderBy('scheme_structure.scheme_id', 'desc')
            ->get();
        // return $datas;
        return view('scheme-structure.index')->with('datas', $datas);
    }

    public function add(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id = "NA";
        $scheme_type_datas = SchemeType::orderBy('sch_type_name', 'asc')->get();
        $department_datas = Department::orderBy('dept_name')->where('is_active',1)->get();
        $scheme_group_datas = Group::get();
        $block_datas = GeoStructure::where('level_id', 3)->get();
        $scheme_asset_datas = SchemeAsset::get();
        $data = new SchemeStructure;

        if (isset($request->purpose) && isset($request->id)) {
            $hidden_input_purpose = $request->purpose;
            $hidden_input_id = $request->id;
            $data = $data->find($request->id);
        }
        return view('scheme-structure.add')->with(compact('hidden_input_purpose', 'hidden_input_id', 'data', 'department_datas', 'scheme_type_datas', 'departments', 'scheme_group_datas', 'block_datas', 'scheme_asset_datas'));
    }

    public function view(Request $request)
    {
        $data = SchemeStructure::leftJoin('department', 'scheme_structure.dept_id', '=', 'department.dept_id')
            ->leftJoin('scheme_type', 'scheme_type.sch_type_id', '=', 'scheme_structure.scheme_type_id')
            ->leftJoin('scheme_assets', 'scheme_assets.scheme_asset_id', '=', 'scheme_structure.scheme_asset_id')
            ->select('scheme_structure.*', 'department.dept_name', "scheme_type.sch_type_name", "scheme_assets.scheme_asset_name")
            ->where('scheme_id', $request->scheme_id)
            ->first();

        return view('scheme-structure.view')->with(compact('data'));
    }

    public function get_panchayat_datas(Request $request)
    {
        $block_id = $request->block_id;
        $panchayat_datas = GeoStructure::select('geo_id', 'geo_name')->where('bl_id', $block_id)->get();
        return $panchayat_datas;
    }

    public function get_attributes_details(Request $request)
    { // according to SchemeAsset selected
        $scheme_asset_datas = SchemeAsset::where('scheme_asset_id', $request->scheme_asset_id)->first();
        $to_send = '<label>Attributes</label><table class="table" style="margin-top: 10px;">'; // to send data/ tr/ td, entire table rows
        $scheme_is = $request->scheme_is; // receiving data 1= independent, 2 = under a group
        $attribute =  unserialize($scheme_asset_datas->attribute);

        // defining thead
        if ($scheme_is == "1") { // independent, only one "th" needed
            $to_send .= '<thead style="background: #cedcff">
                <tr>
                    <th>Name</th>
                </tr>
            </thead>';
        } else { // two "th" needed
            $to_send .= '<thead style="background: #cedcff">
                <tr>
                    <th>Name</th>
                    <th>Enter Details</th>
                </tr>
            </thead>';
        }


        // replacing UOM name, id, etc for attrbutes]
        $to_send .= '<tbody>';
        foreach ($attribute as $value) {
            $to_send .= '<tr><td>' . $value['name'] . '</td>';
            $uom = Uom::where('uom_id', $value['uom'])->first()->uom_name;

            if ($scheme_is == "2") // if group, else no need of second "td"
            {
                if ($uom == "number") {
                    $to_send .= '<td><input type="text" class="form-control" name="' . strtolower(preg_replace('/\s+/', '', $key)) . '"></td></tr>';
                } else if ($uom == "boolean") {
                    $to_send .= '<td>
                            <select class="form-control" name="' . strtolower(preg_replace('/\s+/', '', $key)) . '">
                                <option value="">--Select--</option>
                                <option value="1">Ongoing</option>
                                <option value="2">Completed</option>
                            </select>
                            </td>
                        </tr>';
                }
            }
        }
        $to_send .= '</tbody></table>';

        return ["to_append" => $to_send];
    }
    public function store(Request $request)
    {
        // return $request;
        $upload_directory = "public/uploaded_documents/schemes/"; // directory to upload docs/icons etc related to scheme
        $scheme_structure = new SchemeStructure;
        // for performance data if sche,e comes under a group
        $scheme_performance = new SchemePerformance;
        if ($request->hidden_input_purpose == "edit") {
            $scheme_structure = $scheme_structure->find($request->hidden_input_id);
        }
        $attribute_name_array = $request->attribute_name;
        $attribute_id_array = $request->attribute_id;
        $serialize_attribute = "";
        // $attribute_pre_data=unserialize($scheme_structure->attributes);
        if ($request->attribute_name != "") {
            $attribute = [];
            foreach ($attribute_name_array as $key_name => $value_name) {
                if ($attribute_id_array[$key_name] == "new_id") {
                    $tmp = ["id" => uniqid(), "name" => $value_name];
                    array_push($attribute, $tmp);
                } else {
                    $tmp = ["id" => $attribute_id_array[$key_name], "name" => $value_name];
                    array_push($attribute, $tmp);
                }
            }
            $serialize_attribute = serialize($attribute);
        }
        $scheme_structure->org_id = "1";
        $scheme_structure->scheme_name = $request->scheme_name;
        $scheme_structure->scheme_short_name = $request->scheme_short_name;
        if($request->scheme_is == "1")
        {
        $scheme_structure->scheme_asset_id = $request->scheme_asset_id;
        } // for attributes/ report generation etc
        $scheme_structure->scheme_is = $request->scheme_is; // 1=independent, 2=under a group
        // initialize values if scheme_as==2 (under a group) else saved as null
        // if ($request->scheme_is == "2") {

            $scheme_structure->attributes = $serialize_attribute ?? "";
        // }
        $scheme_structure->status = $request->status;
        if ($request->spans_across_borders) {
            $scheme_structure->spans_across_borders = $request->spans_across_borders;
        } else {
            $scheme_structure->spans_across_borders = 0;
        }
        
        $scheme_structure->dept_id = $request->dept_id;
        $scheme_structure->scheme_type_id = $request->scheme_type_id;
        $scheme_structure->description = $request->description;
        $scheme_structure->created_by = Auth::user()->id;
        $scheme_structure->updated_by = Auth::user()->id;

        // scheme attachment
        if ($request->hasFile('attachment')) {
            // delete previous attachment
            if ($request->hidden_input_purpose == "edit") {
                if (file_exists($scheme_structure->attachment)) {
                    unlink($scheme_structure->attachment);
                }
            }
            $file = $request->file('attachment');
            $attachment_tmp_name = "scheme-attachments-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $attachment_tmp_name);   // move the file to desired folder
            $scheme_structure->attachment = $upload_directory . $attachment_tmp_name;    // assign the location of folder to the model
        } else {
            if ($request->hidden_input_purpose == "add") {
                $scheme_structure->attachment = "";
            } else if ($request->hidden_input_purpose == "edit" && $request->scheme_attachment_delete) { // edit
                $scheme_structure->attachment = "";
            }
        }
        // to previous attachment if delete clicked
        if ($request->scheme_attachment_delete) {
            if (file_exists($request->scheme_attachment_delete)) {
                unlink($request->scheme_attachment_delete);
            }
        }

        // scheme logo
        if ($request->hasFile('scheme_logo')) {
            //delete previous scheme logo
            if ($request->hidden_input_purpose == "edit") {
                if (file_exists($scheme_structure->scheme_logo)) {
                    unlink($scheme_structure->scheme_logo);
                }
            }

            $file = $request->file('scheme_logo');
            $scheme_logo_tmp_name = "scheme-logo-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $scheme_logo_tmp_name); //  move file
            $scheme_structure->scheme_logo = $upload_directory . $scheme_logo_tmp_name; // assign
        } else {
            if ($request->hidden_input_purpose == "add") {
                $scheme_structure->scheme_logo = "";
            } else if ($request->hidden_input_purpose == "edit" && $request->scheme_logo_delete) {
                $scheme_structure->scheme_logo = "";
            }
        }
        // to previous scheme_logo if delete clicked
        if ($request->scheme_logo_delete) {
            if (file_exists($request->scheme_logo_delete)) {
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
        if (SchemeStructure::where('scheme_name', $request->scheme_name)->first() && $request->hidden_input_purpose != "edit") {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'This scheme ' . $request->scheme_name . ' already exist !');
        } else if ($scheme_structure->save()) {
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Scheme details have been successfully submitted !');
            if ($request->scheme_is == "2") {
                // $scheme_performance->save();
            }
        } else {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'Something went wrong while adding new details !');
        }

        return redirect('scheme-structure');
    }

    public function delete(Request $request)
    {
        if (SchemeStructure::find($request->scheme_id)) {
            $scheme_structure = SchemeStructure::find($request->scheme_id);
            if (file_exists($scheme_structure->attachment)) {
                unlink($scheme_structure->attachment);
            }
            if (file_exists($scheme_structure->scheme_logo)) {
                unlink($scheme_structure->scheme_logo);
            }
            if (file_exists($scheme_structure->scheme_map_marker)) {
                unlink($scheme_structure->scheme_map_marker);
            }
            SchemeStructure::where('scheme_id', $request->scheme_id)->delete();
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Deleted successfully !');
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
            ->select('scheme_structure.*', 'department.dept_name')
            ->orderBy('scheme_structure.scheme_id', 'desc')->get();

        date_default_timezone_set('Asia/Kolkata');
        $SchemeStructureTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs', compact('SchemeStructure_pdf', 'SchemeStructureTime'));
        return $pdf->download('SchemeStructure.pdf');
    }

     // abhishek 
     public function view_diffrent_formate(Request $request)
     {
        // return $request;
        $scheme_id = explode(',',$request->scheme_id); // array
        // return "akf";
         $department=array();
         if($request->print=="print_pdf")
         {
              
             if($request->scheme_id!="")
             {
 
                   
                        $scheme_structure =  SchemeStructure::whereIn('scheme_id',$scheme_id)->get();
                        // return $scheme_structure;

                        // $year =  Year::whereIn('year_id',$year_id)->orderBy('year_id','desc')->get();
                        foreach ($scheme_structure as $key => $value) {
                            $value->createdDate = date('d/m/Y',strtotime($value->created_at));
                            if($value->status == 1){
                                $value->status= "Active";
                            }
                            else 
                            {
                                $value->status= "Inactive";
                            }
                        }

                        $doc_details = array(
                            "title" => "Scheme",
                            "author" => 'IT-Scient',
                            "topMarginValue" => 10,
                            "mode" => 'P'
                        );

                        date_default_timezone_set('Asia/Kolkata');
                        $currentDateTime = date('d-m-Y H:i:s'); 
                        $user_name=Auth::user()->first_name;
                        $user_last_name=Auth::user()->last_name;
                        $pdfbuilder = new \PdfBuilder($doc_details);
                        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                        $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Scheme Details</b></th></tr>";
                        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Details
                        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                        "</b>"."</p>";

                        /* ========================================================================= */
                        /*             Total width of the pdf table is 1017px lanscape               */
                        /*             Total width of the pdf table is 709px portrait                */
                        /* ========================================================================= */
                        $content .= "<thead>";
                        $content .= "<tr>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 429px;\" align=\"center\"><b>Scheme Name</b></th>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 140px;\" align=\"center\"><b>Status</b></th>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
                        $content .= "</tr>";
                        $content .= "</thead>";
                        $content .= "<tbody>";
                        foreach ($scheme_structure as $key => $row) {
                            $index = $key+1;
                            $content .= "<tr>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 429px;\" align=\"left\">" . $row->scheme_name . "</td>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 140px;\" align=\"left\">" . $row->status . "</td>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->created_at. "</td>";
                            $content .= "</tr>";
                        }
                        $content .= "</tbody></table>";
                        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                        $pdfbuilder->output('Year.pdf');
                        exit;


                     

 
             }
            //  return $request;
         }
         elseif($request->print=="excel_sheet")
         {
                // return $request;
             if($request->scheme_id!="")
             {
 
                $data = array(1 => array("Year-Sheet"));
                $data[] = array( 'Sl. No.','Scheme Name','Status','Date');
        
                $scheme_structure =  SchemeStructure::whereIn('scheme_id',$scheme_id)->get();
                // return $scheme_structure;
                // $yearValue = Year::whereIn('year_id', $year_id)->orderBy('year_id','desc')->select('year_id as slId', 'year_value', 'status', 'created_at as createdDate')->get();
        
                foreach ($scheme_structure as $key => $value) {
                    if($value->status == 1) {
                        $value->status = "Active";
                    }
                    else {
                        $value->status = "Inactive";
                    }
                    $value->createdDate = date('d/m/Y',strtotime($value->created_at));
                    $data[] = array(
                        $key + 1,
                        $value->scheme_name,
                        $value->status,
                        $value->createdDate,
                    );
                }
                
                \Excel::create('Scheme', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Scheme');
        
                    // Chain the setters
                    $excel->setCreator('Scheme')->setCompany('Scheme');
        
                    $excel->sheet('Scheme', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');
        
 
             }
             return $request;
         }
     }


}
