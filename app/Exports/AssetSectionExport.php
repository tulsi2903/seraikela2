<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PDF;
use DB;
use App\Asset;
use App\Department;
use App\asset_cat;
use App\asset_subcat;

class AssetSectionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $AssetValue = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                        ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
                        ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
                        ->select('asset.asset_id as slId','asset.asset_name','asset.movable','department.dept_name','asset.created_at as createdDate')
                        ->orderBy('asset.asset_id', 'desc')
                        ->get();

        foreach ($AssetValue as $key => $value) {
            $value->slId = $key+1;
           
            if($value->movable == 1) {
                $value->movable = "Movable";
            }
            else {
                $value->movable = "Immovable";
            }
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
        }
        return $AssetValue;
    }
    public function headings(): array
    {
        return [
            'Sl. No.',
            'Name',
            'Type',
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
