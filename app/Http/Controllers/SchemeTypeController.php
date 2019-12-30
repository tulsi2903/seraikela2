<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\SchemeType;

class SchemeTypeController extends Controller
{
    public function index()
    {
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
            session()->put('alert-content','Scheme submitted successfully !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details');
        }

        return redirect('scheme-type');
    }
     public function delete(Request $request){
        if(SchemeType::find($request->sch_type_id)){
            SchemeType::where('sch_type_id',$request->sch_type_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
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
    
                $excel->sheet('Fees', function ($sheet) use ($data) {
                    $sheet->freezePane('A3');
                    $sheet->mergeCells('A1:I1');
                    $sheet->fromArray($data, null, 'A1', true, false);
                    $sheet->setColumnFormat(array('I1' => '@'));
                });
            })->download('xls');
        }
    }



}
