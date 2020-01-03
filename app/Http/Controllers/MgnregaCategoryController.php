<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MgnregaCategory;
use PDF;



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
    
    // export_excel_Function
    public function export_ExcelFunction(){

        $data = array(1 => array("Mgnrega Category"));
        $data[] = array('Sl. No.','Mgnrega Category Name','Date');

        $items = MgnregaCategory::orderBy('mgnrega_category_id','desc')->Select('mgnrega_category_name','created_at as createdDate')->get(); 
        foreach ($items as $key => $value) {      
            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->mgnrega_category_name,
                $value->createdDate,
            );

        }
        \Excel::create('MgnregaCategory-Sheet', function ($excel) use ($data) {
            // Set the title
            $excel->setTitle('MgnregaCategory');

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
        $mgnrega_pdf = MgnregaCategory::orderBy('mgnrega_category_id','desc','created_at')->get();

        foreach ($mgnrega_pdf as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->created_at));        
        }

      $doc_details = array(
          "title" => "MgnregaCategory",
          "author" => 'IT-Scient',
          "topMarginValue" => 10,
          "mode" => 'P'
      );

      $pdfbuilder = new \PdfBuilder($doc_details);
      $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
      $content .= "<th style='border: solid 1px #000000;' colspan=\"3\" align=\"left\" ><b>Mgnrega Category Details</b></th></tr>";
    
      /* ========================================================================= */
      /*             Total width of the pdf table is 1017px lanscape               */
      /*             Total width of the pdf table is 709px portrait                */
      /* ========================================================================= */
      $content .= "<thead>";
      $content .= "<tr>";
      $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
      $content .= "<th style=\"width: 559px;\" align=\"center\"><b>Mgnregs Name</b></th>";
      $content .= "<th style=\"width: 100px;\" align=\"center\"><b>Date</b></th>";
      $content .= "</tr>";
      $content .= "</thead>";
      $content .= "<tbody>";
      foreach ($mgnrega_pdf as $key => $row) {
          $index = $key+1;
          $content .= "<tr>";
          $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
          $content .= "<td style=\"width: 559px;\" align=\"left\">" . $row->mgnrega_category_name . "</td>";
          $content .= "<td style=\"width: 100px;\" align=\"right\">" . $row->createdDate. "</td>";
          $content .= "</tr>";
      }
      $content .= "</tbody></table>";
      $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
      $pdfbuilder->output('MgnregaCategory.pdf');
      exit;

     
    }




}
