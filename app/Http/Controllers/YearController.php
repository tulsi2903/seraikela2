<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Year;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YearSectionExport;
use PDF;

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
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('year');
    }

    public function delete(Request $request){
        if(Year::find($request->year_id)){
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
            $excel->setCreator('Paatham')->setCompany('Paatham');

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
        date_default_timezone_set('Asia/Kolkata');
        $yeardateTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs',compact('year','yeardateTime'));
        return $pdf->download('Year.pdf');
    }
}
