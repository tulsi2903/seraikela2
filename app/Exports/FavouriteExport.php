<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use DB;
use App\Department;
use App\Organisation;
use App\Fav_Dept;




class FavouriteExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    $departmentexcel = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
        ->select('department.dept_id as slId','department.dept_name','department.created_at as createdDate')->where('department.is_active',1)
        ->orderBy('department.dept_id','asc')
        ->get();

        for($i=0;$i<count($departmentexcel);$i++){
            $fav_dept_tmp = Fav_Dept::select('favourite_department_id')->where('user_id',1)->where('dept_id',$departmentexcel[$i]->dept_id)->first();

            if($fav_dept_tmp){
                $departmentexcel[$i]->checked=1;
            }
            else{
                $departmentexcel[$i]->checked=0;
            }
        }

        foreach ($departmentexcel as $key => $value) {
            // if($value->is_active=="1"){
            //     $value->is_active=="yahoo";
            // }
            // else{
            //     $value->is_active="noeees";
            // }
            $value->slId = $key+1;
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
        }
        return $departmentexcel;                      
    }
    public function headings(): array
    {
        return [
            'Sl.No.',
            'Check Favourite',
            'Department Name',
            'Date'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                // All headers - set font size to 14
                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(13);

                // Apply array of styles to B2:G8 cell range
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ]
                    ]
                ];
                // $event->sheet->getDelegate()->getStyle('B2:G8')->applyFromArray($styleArray);

                // Set first row to height 20
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

                // Set A1:D4 range to wrap text in cells
                // $event->sheet->getDelegate()->getStyle('A1:D4')
                //     ->getAlignment()->setWrapText(true);
            },
            // Handle by a closure.
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setTitle('Asset Sheet');
                $event->writer->getProperties()->setCreator('IT-Scient');

            },
        ];
    }
}
