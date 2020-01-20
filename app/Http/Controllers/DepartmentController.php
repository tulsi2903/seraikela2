<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Organisation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DisneyplusExport;
use DB;
use PDF;
use Session;
use Auth;

class DepartmentController extends Controller
{
    public function index(){
        $datas = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
            ->select('department.*','organisation.org_name')
            ->orderBy('department.dept_id','desc')
            ->get();

        return view('department.index')->with(compact('datas'));
    }

    // to open form (for add & edit) ---- (deprecated)
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";

        $organisation_datas = new Organisation;
        $organisation_datas = $organisation_datas->orderBy('org_name','asc')->get();
        
        $data = new Department;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }
        return view('department.add')->with(compact('hidden_input_purpose','hidden_input_id','data','organisation_datas'));
    }

    // to store in DB (add & edit)
    public function store(Request $request){
        $purpose="add";
        $dept = new Department;

        if(isset($request->edit_id)){
            $dept = $dept->find($request->edit_id);
            if(count($dept)!=0){
                $purpose="edit";
            }
        }

        if($request->hasFile('dept_icon')){
            $upload_directory = "public/uploaded_documents/department/";
            $file = $request->file('dept_icon');
            $dept_icon_tmp_name = "department-".time().rand(1000,5000).'.'.strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $dept_icon_tmp_name);   // move the file to desired folder

            // deleteprevious icon
            if(isset($request->edit_id))
            {
                if(file_exists($dept->dept_icon)){
                    unlink($dept->dept_icon);
                }
            }
            $dept->dept_icon = $upload_directory.$dept_icon_tmp_name;    // assign the location of folder to the model
        }


        $dept->dept_name= $request->dept_name;
        $dept->is_active = $request->is_active;
        $dept->org_id = '1';
        $dept->created_by = '1';
        $dept->updated_by = '1';

        if(Department::where('dept_name',$request->dept_name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This department '.$request->dept_name.' already exist !');
        }
        else if($dept->save()){ //$purpose == add & no duplicate entry || $purpose == edit
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Department details has been saved');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('department');
    }

    public function delete(Request $request){
        if(Department::find($request->dept_id)){
            Department::where('dept_id',$request->dept_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }
        return redirect('department');
    }

    public function exportExcelFunctiuon()
    {
        $data = array(1 => array("Department Detail Sheet"));
        $data[] = array('Sl. No.', 'Department Name', 'Organization Name', 'Status', 'Date');

        $items =  DB::table('department')
            ->join('organisation', 'department.org_id', '=', 'organisation.org_id')
            ->select('department.dept_id as slId', 'department.dept_name', 'organisation.org_name', 'department.is_active', 'department.created_at')->get();

        foreach ($items as $key => $value) {
            if ($value->is_active == 1) {
                $value->is_active = "Active";
            } else {
                $value->is_active = "Inactive";
            }
            $value->created_at = date('d/m/Y', strtotime($value->created_at));
            $data[] = array(
                $key+1,
                $value->dept_name,
                $value->org_name,
                $value->is_active,
                $value->created_at,
            );


        }
        \Excel::create('Department-Sheet', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Department-Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }

    
    public function export_PDF_Function()
    {
        $department =  DB::table('department')
            ->join('organisation','department.org_id','=','organisation.org_id')
            ->select('department.dept_id','department.dept_name','organisation.org_name','department.is_active','department.created_at')->get();
 
        foreach ($department as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->created_at));
            if($department[$key]->is_active == 1){
                $value->is_active= "Active";
            }
            else 
            {
                $value->is_active= "Inactive";
            }
        }

        $doc_details = array(
            "title" => "Department",
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
        $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"center\" ><b>District Scheme & Resource Management</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Department"."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";
        

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 385px;\" align=\"center\"><b>Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Org Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 75px;\" align=\"center\"><b>Status</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($department as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 385px;\" align=\"left\">" . $row->dept_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->org_name. "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 75px;\" align=\"left\">" . $row->is_active . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('Department.pdf');
        exit;
    }

    public function changeView()
    {
        # code...
        return view('department.ImportExcel');
    }
    public function importFromExcel(Request $request)
    {
        # code...

        /* $clean = preg_replace('/[^\w]/', '', $clean); // drop anything but ASCII */
        if ( $_FILES['excelcsv']['tmp_name'] ){
            $readExcel = \Excel::load($_FILES['excelcsv']['tmp_name'], function($reader) { })->get()->toArray();
            // $dataImport = array("ready"=>array(),"revise"=>array());
            foreach ($readExcel as $row){
               
                $importItem = array();
                if(isset($row['sl']) AND $row['sl'] != null){
                    $importItem['sl'] = $row['sl'];
                }
                if(isset($row['department_name']) AND $row['department_name'] != null){
                    $importItem['department_name'] = $row['department_name'];
                }
                if(isset($row['organization_name']) AND $row['organization_name'] != null){
                    $importItem['organization_name'] = $row['organization_name'];
                }
                if(isset($row['status']) AND $row['status'] != null){
                    $importItem['status'] = $row['status'];
                }
                if(isset($row['date']) AND $row['date'] != null){
                    $importItem['date'] = $row['date'];
                }
                $dataImport[]= $importItem;
            }
            $toReturn = array();
            $toReturn = $dataImport;
        }
        // echo "<pre>";
        // print_r($toReturn);exit;
        return view('department.reviewImport')->with(compact('toReturn'));
    }

    public function ImportreviewSave(Request $request)
    {
        # code...
        $totalLength = $request->slno;
        foreach ($totalLength as $key => $value) {
            $Department = Department::where('dept_name',$request->department_name[$key])->first();
            // echo"<pre>";print_r($Department);exit;
            if($Department->dept_name == $request->department_name[$key])
            {
                $Department_edit = Department::find($Department->dept_id);
                if($request->status[$key] == "Active")
                {
                    $Department_edit->is_active = 1;
                }
                else
                {
                    $Department_edit->is_active = 0;
                }
                $Department_edit->updated_by = Session::get('user_id');
                $Department_edit->org_id = 1;
                $Department_edit->save();
            }
            else
            {
                $Department = new Department;
                $Department->dept_name = $request->department_name[$key];
                if($request->status[$key] == "Active")
                {
                    $Department->is_active = 1;
                }
                else
                {
                    $Department->is_active = 0;
                }
                // $Department->created_at = date('Y-m-d');
                $Department->created_by = Session::get('user_id');
                $Department->org_id = 1;
                $Department->save();
            }
        }
        session()->put('alert-class','alert-success');
        session()->put('alert-content','Department Details has been Saved');
        return redirect('department');
    }
    public function view_diffrent_formate(Request $request)
    {

        $dept_id = explode(',',$request->department_id); // array
        // return $request;
        $department=array();
        if($request->print=="print_pdf")
        {

            if($request->department_id!="")
            {

               

                $department = Department::whereIn('dept_id',$dept_id)
                ->join('organisation','department.org_id','=','organisation.org_id')
                ->select('department.dept_id','department.dept_name','organisation.org_name','department.is_active','department.created_at')->get();

                foreach ($department as $key => $value) {
                    $value->createdDate = date('d/m/Y',strtotime($value->created_at));
                    if($department[$key]->is_active == 1){
                        $value->is_active= "Active";
                    }
                    else 
                    {
                        $value->is_active= "Inactive";
                    }
                }
        
                $doc_details = array(
                    "title" => "Department",
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
                $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"center\" ><b>District Scheme & Resource Management</b></th></tr>";
                $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Department"."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                "</b>"."</p>";
                
        
                /* ========================================================================= */
                /*             Total width of the pdf table is 1017px lanscape               */
                /*             Total width of the pdf table is 709px portrait                */
                /* ========================================================================= */
                $content .= "<thead>";
                $content .= "<tr>";
                $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                $content .= "<th style=\"border: solid 1px #000000;width: 385px;\" align=\"center\"><b>Name</b></th>";
                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Org Name</b></th>";
                $content .= "<th style=\"border: solid 1px #000000;width: 75px;\" align=\"center\"><b>Status</b></th>";
                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Date</b></th>";
                $content .= "</tr>";
                $content .= "</thead>";
                $content .= "<tbody>";
                foreach ($department as $key => $row) {
                    $index = $key+1;
                    $content .= "<tr>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 385px;\" align=\"left\">" . $row->dept_name . "</td>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->org_name. "</td>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 75px;\" align=\"left\">" . $row->is_active . "</td>";
                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->createdDate. "</td>";
                    $content .= "</tr>";
                }
                $content .= "</tbody></table>";
                $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                $pdfbuilder->output('Department.pdf');
                exit;
            }
            // return $Department;
        }
        elseif($request->print=="excel_sheet")
        {

            if($request->department_id!="")
            {

              
                $data = array(1 => array("Department Detail Sheet"));
                $data[] = array('Sl. No.', 'Department Name', 'Organization Name', 'Status', 'Date');
        
                $items =  DB::table('department')
                    ->whereIn('dept_id',$dept_id)
                    ->join('organisation', 'department.org_id', '=', 'organisation.org_id')
                    ->select('department.dept_id as slId', 'department.dept_name', 'organisation.org_name', 'department.is_active', 'department.created_at')->get();
        
                foreach ($items as $key => $value) {
                    if ($value->is_active == 1) {
                        $value->is_active = "Active";
                    } else {
                        $value->is_active = "Inactive";
                    }
                    $value->created_at = date('d/m/Y', strtotime($value->created_at));
                    $data[] = array(
                        $key+1,
                        $value->dept_name,
                        $value->org_name,
                        $value->is_active,
                        $value->created_at,
                    );
        
        
                }
                \Excel::create('Department-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Department-Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Department-Sheet', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');


            }
            // return $request;
        }





        // testing mail
        elseif($request->print=="mail_system")
        {

            if($request->department_id!="")
            {

                $data_for_mail= DB::table('department')->join('organisation','department.org_id','=','organisation.org_id')->whereIn('dept_id',$request->department_id)->select('department.*','organisation.org_name')->get();
                  
             
                // return view('department.index');
                // return view('department.index')->with('data_for_mail',$data_for_mail);
                // return $data_for_mail;
                
            }
           
        }

        // testing mail end



    }
}
