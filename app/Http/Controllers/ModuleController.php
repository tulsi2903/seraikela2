<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ModuleSectionExport;
use PDF;

class ModuleController extends Controller
{

   public function index()
   {
        $datas = Module::orderBy('mod_name','ASC')->get();
        return view('module.index')->with('datas', $datas);
   }

   public function add(Request $request)
     {
         $hidden_input_purpose = "add";
         $hidden_input_id= "NA";
         $data = new Module;
         
         if(isset($request->purpose) && ($request->id)){
             $hidden_input_purpose = $request->purpose;
             $hidden_input_id = $request->id;
             $data = $data->find($request->id);
         }
         return view('module.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
     }



     public function store(Request $request){
        $purpose="add";
        $module = new Module;

        if(isset($request->edit_id)){
            $module = $module->find($request->edit_id);
            if(count($module)!=0){
                $purpose="edit";
            }
        }

        $module->mod_name= strtolower($request->module_name);
        $module->created_by = '1';
        $module->updated_by = '1';

        if(Module::where('mod_name', $request->module_name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This module '.$request->module_name.' already exist !');
        }
        else if($module->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','A new module details have been successfully submitted');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('module');
    }

    public function delete(Request $request){
        if(Module::find($request->mod_id)){
            Module::where('mod_id',$request->mod_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }

        return redirect('module');
    }

    public function exportExcelFunctiuonformodule()
    {
        $data = array(1 => array("Module Sheet"));
        $data[] = array('Sl. No.','Module Name','Date');

        $items =  Module::orderBy('mod_id','desc')->select('mod_id', 'mod_name', 'created_at as createdDate')->get();

        foreach ($items as $key => $value) {

            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->mod_name,
                $value->createdDate,
            );
        }
        \Excel::create('Module-Sheet', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Module-Sheet');

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

    public function exportpdfFunctiuonformodule()
    {
        $Moduledata =  Module::orderBy('mod_id','desc')->get();
        foreach ($Moduledata as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->created_at));
        }

        $doc_details = array(
            "title" => "Module",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"3\" align=\"left\" ><b>Module</b></th></tr>";
        

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 559px;\" align=\"center\"><b>Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($Moduledata as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 559px;\" align=\"left\">" . $row->mod_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('Module.pdf');
        exit;


















    
    
    
    
    
    
    
    }

}
