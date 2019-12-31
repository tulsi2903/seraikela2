<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Uom;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UoMSectionExport;
use PDF;



class UomController extends Controller
{
    public function index(){
        $datas = Uom::orderBy('uom_id','desc')->get();
        return view('uom.index')->with('datas', $datas);
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
        $uom->created_by = '1';
        $uom->updated_by = '1';


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
        date_default_timezone_set('Asia/Kolkata');
        $UoMdateTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs',compact('uomdata','UoMdateTime'));
        return $pdf->download('UoM.pdf');
    }
}
