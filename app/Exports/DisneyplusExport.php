<?php

namespace App\Exports;

use App\Disneypluslist;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Department;
use App\Organisation;
use PDF;
use DB;




class DisneyplusExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $test = DB::table('department')
                ->join('organisation','department.org_id','=','organisation.id')
                ->select('department.dept_id','department.dept_name','organisation.org_name')->get();
         
        $items = DB::table('department')
                    ->join('organisation','department.org_id','=','organisation.id')
                    ->select('department.dept_id','department.dept_name','organisation.org_name')->get();
    
        foreach ($items as $key => $value) {
            
            $test[$key+1]=$items[$key];
                        
        }
        $test[0]=array('Department ID', 'Department Name', 'Organization Name');
        return $test;

    
    }
}
