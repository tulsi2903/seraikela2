<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PDF;
use App\Designation;
use App\Organisation;
use DB;
use App\User;

class UsersSectionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $UsersValue = DB::table('users')->leftjoin('designation','users.desig_id','designation.desig_id')
                                ->select('users.id as slId',
                                'users.title as titlename',
                                // 'users.first_name as first_name',
                                // 'users.middle_name as middle_name',
                                // 'users.last_name as last_name',
                                'users.email as email',
                                'users.username as username',
                                'designation.name as desig_name',
                                'users.address as address',
                                'users.mobile as mobile',
                                'users.status as status')
                                ->get();

        foreach ($UsersValue as $key => $value) {
            $value->titlename = $value->titlename.' '.DB::table('users')->where('id',$value->slId)->value('first_name').' '.DB::table('users')->where('id',$value->slId)->value('middle_name').' '.DB::table('users')->where('id',$value->slId)->value('last_name');
            $value->slId = $key+1;
           
            if($value->status == 1) {
                $value->status = "Active";
            }
            else {
                $value->status = "Inactive";
            }
        }
        return $UsersValue;
    }
    public function headings(): array
    {
        return [
            'Sl. No.',
            'Name',
            'Email',
            'User Name',
            'Designation',
            'Address',
            'Mobile Number',
            'Status'
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
                $event->writer->getProperties()->setTitle('Users Sheet');
                $event->writer->getProperties()->setCreator('IT-Scient');

            },
        ];
    }
}
