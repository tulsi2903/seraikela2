<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Year;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YearSectionExport;
use PDF;
use Auth;

class YearController extends Controller
{
     public function index(){
        $datas = Year::orderBy('year_id','desc')->get();
        return view('year.index')->with('datas', $datas);
    }
    
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new Year;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
            $data->from = explode('-',$data->year_value)[0];
             $data->to = explode('-',$data->year_value)[1];
        }

       return view('year.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
    }

    public function store(Request $request){
        $purpose="add";
        $year = new Year;

        if(isset($request->edit_id)){
            $year = $year->find($request->edit_id);
            if(count($year)!=0){
                $purpose="edit";
            }
        }

        $year->year_value= $request->from_value."-".$request->to_value;
        $year->status = $request->status;
        $year->created_by = '1';
        $year->updated_by = '1';

        
        if(Year::where('year_value',$year->year_value)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This year '.$year->year_value.' already exist !');
        }
        else if($year->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Year details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new Year!');
        }

        return redirect('year');
    }

    public function delete(Request $request){
        if(Year::find($request->year_id))
        {
            Year::where('year_id',$request->year_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }

        return redirect('year');
    }

    public function exportExcelFunctiuonforyear()
    {
        // return Excel::download(new YearSectionExport, 'Year-Sheet.xls');


        $data = array(1 => array("Year-Sheet"));
        $data[] = array( 'Sl. No.','Year','Status','Date');


        $yearValue = Year::orderBy('year_id','desc')->select('year_id as slId', 'year_value', 'status', 'created_at as createdDate')->get();

        foreach ($yearValue as $key => $value) {
            if($value->status == 1) {
                $value->status = "Active";
            }
            else {
                $value->status = "Inactive";
            }
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->year_value,
                $value->status,
                $value->createdDate,
            );
        }
        
        \Excel::create('Year-Sheet', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Year-Sheet');

            // Chain the setters
            $excel->setCreator('Year')->setCompany('Year');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');


    }

    public function exportpdfFunctiuonforyear()
    {
        $year =  Year::orderBy('year_id','desc')->get();
        foreach ($year as $key => $value) {
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
            "title" => "Year",
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
        $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Year Details</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Year Details
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 429px;\" align=\"center\"><b>Year</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 140px;\" align=\"center\"><b>Status</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($year as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 429px;\" align=\"left\">" . $row->year_value . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 140px;\" align=\"left\">" . $row->status . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('Year.pdf');
        exit;
    }


     // abhishek 
     public function view_diffrent_formate(Request $request)
     {
  
        $year_id = explode(',',$request->year_id); // array
        // return "akf";
         $department=array();
         if($request->print=="print_pdf")
         {
              
             if($request->year_id!="")
             {
 
                   
                        $year =  Year::whereIn('year_id',$year_id)->orderBy('year_id','desc')->get();
                        foreach ($year as $key => $value) {
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
                            "title" => "Year",
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
                        $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Year Details</b></th></tr>";
                        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Year Details
                        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                        "</b>"."</p>";

                        /* ========================================================================= */
                        /*             Total width of the pdf table is 1017px lanscape               */
                        /*             Total width of the pdf table is 709px portrait                */
                        /* ========================================================================= */
                        $content .= "<thead>";
                        $content .= "<tr>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 429px;\" align=\"center\"><b>Year</b></th>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 140px;\" align=\"center\"><b>Status</b></th>";
                        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
                        $content .= "</tr>";
                        $content .= "</thead>";
                        $content .= "<tbody>";
                        foreach ($year as $key => $row) {
                            $index = $key+1;
                            $content .= "<tr>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 429px;\" align=\"left\">" . $row->year_value . "</td>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 140px;\" align=\"left\">" . $row->status . "</td>";
                            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
                            $content .= "</tr>";
                        }
                        $content .= "</tbody></table>";
                        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                        $pdfbuilder->output('Year.pdf');
                        exit;

 
             }
             return $request;
         }
         elseif($request->print=="excel_sheet")
         {
 
             if($request->year_id!="")
             {
 
                $data = array(1 => array("Year-Sheet"));
                $data[] = array( 'Sl. No.','Year','Status','Date');
        
        
                $yearValue = Year::whereIn('year_id', $year_id)->orderBy('year_id','desc')->select('year_id as slId', 'year_value', 'status', 'created_at as createdDate')->get();
        
                foreach ($yearValue as $key => $value) {
                    if($value->status == 1) {
                        $value->status = "Active";
                    }
                    else {
                        $value->status = "Inactive";
                    }
                    $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
                    $data[] = array(
                        $key + 1,
                        $value->year_value,
                        $value->status,
                        $value->createdDate,
                    );
                }
                
                \Excel::create('Year-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Year-Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Paatham')->setCompany('Paatham');
        
                    $excel->sheet('Year-Sheet', function ($sheet) use ($data) {
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
