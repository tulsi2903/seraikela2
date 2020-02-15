<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Uom;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UoMSectionExport;
use PDF;
use App\UoM_Type;


class UomController extends Controller
{
    public function index(){
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod4"]["add"]&&!$desig_permissions["mod4"]["edit"]&&!$desig_permissions["mod4"]["view"]&&!$desig_permissions["mod4"]["del"]){
            return back();
        }
        $datas = Uom::leftjoin('uom_type','uom_type.uom_type_id','=','uom.uom_type_id')
                        ->select('uom_type.uom_type_name','uom.*')->orderBy('uom_id','desc')->get();
                        
        $uom_type =UoM_Type::orderBy('uom_type_id','asc')->get();
        return view('uom.index',compact('uom_type'))->with('datas', $datas);
    }

    
    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new Uom;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('uom.add')->with(compact('hidden_input_purpose','hidden_input_id','data'));
    }

    public function store(Request $request){
        $purpose="add";
        $uom = new Uom;

        if(isset($request->edit_id)){
            $uom = $uom->find($request->edit_id);
            if(count($uom)!=0){
                $purpose="edit";
            }
        }

        $uom->uom_name= $request->uom_name;
        $uom->uom_type_id= $request->uom_type_id;
        $uom->created_by = session()->get('user_id');
        $uom->updated_by = session()->get('user_id');



        if(Uom::where('uom_name',$request->uom_name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This UoM '.$request->uom_name.' already exist !');
        }
        else if($uom->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','UOM details have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('uom');
    }

    public function delete(Request $request){
        if(Uom::find($request->uom_id)){
            Uom::where('uom_id',$request->uom_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('uom');
    }

    
    public function exportExcelFunctiuonforuom()
    {
        $data = array(1 => array("Uom Detail Sheet"));
        $data[] = array('Sl. No.','Name','Date');

        $items = Uom::orderBy('uom_id','desc')->select('uom_id as slId', 'uom_name', 'created_at as datecreated')->get();
        foreach ($items as $key => $value) {
            $value->datecreated = date('d/m/Y',strtotime($value->datecreated));       
            $data[] = array(
                $key + 1,
                $value->uom_name,
                $value->datecreated,
            );
        }

        \Excel::create('Uom Detail-Sheet', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Uom Detail-Sheet');

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

    public function exportpdfFunctiuonforuom()
    {
       $uomdata =  Uom::orderBy('uom_id','desc')->get();
        foreach ($uomdata as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->created_at));
        }

        $doc_details = array(
            "title" => "UoM",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"3\" align=\"left\" ><b>UoM</b></th></tr>";
        

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
        foreach ($uomdata as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 559px;\" align=\"left\">" . $row->uom_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('Uom.pdf');
        exit;
    }
}
