<?php

namespace App\Exports;

use App\Disneypluslist;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use App\Department;
use App\Organisation;
use PDF;
use DB;




class DisneyplusExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $items =  DB::table('department')
                ->join('organisation','department.org_id','=','organisation.org_id')
                ->select('department.dept_id as slId', 'department.dept_name', 'organisation.org_name', 'department.is_active', 'department.created_at')->get();

        foreach ($items as $key => $value) {
            $value->slId = $key+1;
            if($value->is_active == 1) {
                $value->is_active = "Active";
            }
            else {
                $value->is_active = "Inactive";
            }
            $value->created_at = date('d/m/Y',strtotime($value->created_at));
        }
        
        return $items;
    }
    public function headings(): array
    {
        return [
            'Sl. No.',
            'Department Name',
            'Organization Name',
            'Active Status',
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
                $event->writer->getProperties()->setTitle('Departments Sheet');
                $event->writer->getProperties()->setCreator('IT-Scient');

            },
        ];
    }
    
}
