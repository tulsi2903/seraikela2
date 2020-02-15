<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\SchemeType;
use Auth;

class SchemeTypeController extends Controller
{
    public function index()
    {
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod6"]["add"]&&!$desig_permissions["mod6"]["edit"]&&!$desig_permissions["mod6"]["view"]&&!$desig_permissions["mod6"]["del"]){
            return back();
        }
        $datas =SchemeType::orderBy('sch_type_id','desc')->get();
        return view('scheme-type.index')->with('datas',$datas);
    }
    
     public function add(Request $request)
     {
         $hidden_input_purpose = "add";
         $hidden_input_id= "NA";
         $data = new SchemeType;
         
         if(isset($request->purpose) && ($request->id)){
             $hidden_input_purpose = $request->purpose;
             $hidden_input_id = $request->id;
             $data = $data->find($request->id);
         }
         return view('scheme-type.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
     }
    public function store(Request $request)
    {
         //$response = "failed";
        $scheme_type = new SchemeType;

        if($request->hidden_input_purpose=="edit"){
            $scheme_type = $scheme_type->find($request->hidden_input_id);
        }

        $scheme_type->sch_type_name= $request->sch_type_name;
        
        $scheme_type->created_by = '1';
        $scheme_type->updated_by = '1';

        if($scheme_type->save()){
            session()->put('alert-class','alert-success');
            if($request->hidden_input_purpose=="edit"){
                session()->put('alert-content','Scheme Type edited successfully !');
            }
            else{
                session()->put('alert-content','Scheme Type added successfully !');
            }
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new Scheme Type');
        }

        return redirect('scheme-type');
    }
     public function delete(Request $request){
        if(SchemeType::find($request->sch_type_id)){
            SchemeType::where('sch_type_id',$request->sch_type_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme Type Details Deleted successfully !');
        }

        return redirect('scheme-type');
    }
    
    public function export_Excel_SchemeType(){
        {
            $data = array(1 => array("Scheme Type Sheet"));
            $data[] = array('Sl. No.','Scheme Type Name','Date');
    
            $items = SchemeType::orderBy('sch_type_id','desc')->select('sch_type_name','created_at as createdDate')->get(); 
            foreach ($items as $key => $value) {
                $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
                $data[] = array(
                    $key + 1,
                    $value->sch_type_name,
                    $value->createdDate,
                );
            }
            \Excel::create('Scheme Type Sheet', function ($excel) use ($data) {
    
                // Set the title
                $excel->setTitle('Scheme Type Sheet');
    
                // Chain the setters
                $excel->setCreator('Seraikela')->setCompany('Seraikela');
    
                $excel->sheet('Scheme Type', function ($sheet) use ($data) {
                    $sheet->freezePane('A3');
                    $sheet->mergeCells('A1:I1');
                    $sheet->fromArray($data, null, 'A1', true, false);
                    $sheet->setColumnFormat(array('I1' => '@'));
                });
            })->download('xls');
        }
    }
    public function export_PDF_SchemeType(){

        $SchemeType = SchemeType::orderBy('sch_type_id','desc')->select('sch_type_name','created_at as createdDate')->get(); 

        foreach ($SchemeType as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
        }

        $doc_details = array(
            "title" => "Scheme Type",
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
        $content .= "<th style='border: solid 1px #000000;' colspan=\"3\" align=\"left\" ><b>Scheme Type</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Type
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*                Total width of the pdf table is 1017px                     */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 559px;\" align=\"center\"><b>Scheme Type Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($SchemeType as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 559px;\" align=\"left\">" . $row->sch_type_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('SchemeType.pdf');
        exit;
    }


      // abhishek 
      public function view_diffrent_formate(Request $request)
      {
          
          $department=array();
          if($request->print=="print_pdf")
          {
  
              if($request->scheme_type_id!="")
              {
  
                      
                      $SchemeType = SchemeType::whereIn('sch_type_id',$request->scheme_type_id)->orderBy('sch_type_id','desc')->select('sch_type_name','created_at as createdDate')->get(); 

                      foreach ($SchemeType as $key => $value) {
                          $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
                      }
              
                      $doc_details = array(
                          "title" => "Scheme Type",
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
                      $content .= "<th style='border: solid 1px #000000;' colspan=\"3\" align=\"left\" ><b>Scheme Type</b></th></tr>";
                      $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Type
                      "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                      "</b>"."</p>";
              
                      /* ========================================================================= */
                      /*                Total width of the pdf table is 1017px                     */
                      /* ========================================================================= */
                      $content .= "<thead>";
                      $content .= "<tr>";
                      $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                      $content .= "<th style=\"border: solid 1px #000000;width: 559px;\" align=\"center\"><b>Scheme Type Name</b></th>";
                      $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Date</b></th>";
                      $content .= "</tr>";
                      $content .= "</thead>";
                      $content .= "<tbody>";
                      foreach ($SchemeType as $key => $row) {
                          $index = $key+1;
                          $content .= "<tr>";
                          $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                          $content .= "<td style=\"border: solid 1px #000000;width: 559px;\" align=\"left\">" . $row->sch_type_name . "</td>";
                          $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->createdDate. "</td>";
                          $content .= "</tr>";
                      }
                      $content .= "</tbody></table>";
                      $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                      $pdfbuilder->output('SchemeType.pdf');
                      exit;





  
  
              }
              return $request;
          }
          elseif($request->print=="excel_sheet")
          {
  
              if($request->scheme_type_id!="")
              {
  
                $data = array(1 => array("Scheme Type Sheet"));
                $data[] = array('Sl. No.','Scheme Type Name','Date');
        
                $items = SchemeType::whereIn('sch_type_id',$request->scheme_type_id)->orderBy('sch_type_id','desc')->select('sch_type_name','created_at as createdDate')->get(); 
                foreach ($items as $key => $value) {
                    $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
                    $data[] = array(
                        $key + 1,
                        $value->sch_type_name,
                        $value->createdDate,
                    );
                }
                \Excel::create('Scheme Type Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Scheme Type Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Scheme Type', function ($sheet) use ($data) {
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
