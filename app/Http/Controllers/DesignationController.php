<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use App\Organisation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DesignationSectionExport;
use PDF;

class DesignationController extends Controller
{
    //
    //
    public function index(){
        $datas = Designation::leftJoin('organisation', 'designation.org_id', '=', 'organisation.org_id')
            ->select('designation.*','organisation.org_name')
            ->orderBy('designation.desig_id','desc')
            ->get();
        return view('designation.index')->with('datas', $datas);
    }

    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";

        $organisation_datas = new Organisation;
        $organisation_datas = $organisation_datas->orderBy('org_name','asc')->get();

        $data = new Designation;

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('designation.add')->with(compact('hidden_input_purpose','hidden_input_id','data','organisation_datas'));
    }

    public function store(Request $request){
        $purpose="add";
        $desig = new Designation;

        if(isset($request->edit_id)){
            $desig = $desig->find($request->edit_id);
            if(count($desig)!=0){
                $purpose="edit";
            }
        }

        $desig->name= $request->name;
        $desig->org_id = '1';
        $desig->created_by = '1';
        $desig->updated_by = '1';

        if(Designation::where('name', $request->name)->first()&&$purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This designation '.$request->name.' is already exist !');
        }
        else if($desig->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Designation details has been saved');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('designation');
    }

    public function delete(Request $request){
        if(Designation::find($request->desig_id)){
            Designation::where('desig_id',$request->desig_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully');
        }

        return redirect('designation');
    }

    public function exportExcelFunctiuonforDesignation()
    {

        $data = array(1 => array("Designation Detail Sheet"));
        $data[] = array('Sl. No.','Name','Organisation Name','Date');

        $items = Designation::leftJoin('organisation', 'designation.org_id', '=', 'organisation.org_id')
                ->select('designation.desig_id as slId','designation.name','organisation.org_name','designation.created_at as createdDate')
                ->orderBy('designation.desig_id','desc')
                ->get();

        foreach ($items as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->name,
                $value->org_name,
                $value->createdDate,
            );
        }
        \Excel::create('Designation sheet ', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Designation-Sheet');

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

    public function exportpdfFunctiuonforDesignation()
    {
        // $Designationdata = Designation::leftJoin('organisation', 'designation.org_id', '=', 'organisation.org_id')
        //                         ->select('designation.*','organisation.org_name')
        //                         ->orderBy('designation.desig_id','desc')
        //                         ->get();
        // date_default_timezone_set('Asia/Kolkata');
        // $DesignationdateTime = date('d-m-Y H:i A');
        // $pdf = PDF::loadView('department/Createpdfs',compact('Designationdata','DesignationdateTime'));
        // return $pdf->download('Designation.pdf');

        $Designationdata = Designation::leftJoin('organisation', 'designation.org_id', '=', 'organisation.org_id')
                                ->select('designation.*','organisation.org_name')
                                ->orderBy('designation.desig_id','desc')
                                ->get();
        foreach ($Designationdata as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->created_at));
        }

        $doc_details = array(
            "title" => "Designation",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Designation</b></th></tr>";
        

         /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 429px;\" align=\"center\"><b>Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 140px;\" align=\"center\"><b>Organisation Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($Designationdata as $key => $row) {            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 429px;\" align=\"left\">" . $row->name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 140px;\" align=\"left\">" . $row->org_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"center\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('Designation.pdf');
        exit;


    }
}
