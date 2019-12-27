<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Collection;
use PDF;
use App\Department;
use App\Asset;
use App\Organisation;
use DB;
use stdClass;

class AssetReviewSectionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function __construct($AssetReview)
    {
        $this->AssetReview = $AssetReview; 
    }
    public function collection()
    {
        $toReturn = array();
        $AllReviewData  = $this->AssetReview;
        foreach ($AllReviewData as $key => $value) {
            # code...
            $toReturn[$key-1] = $value;
        }
        unset($toReturn[-1]);
        $AssetsReview = new \Illuminate\Database\Eloquent\Collection([$toReturn]);
        return $AssetsReview;

        // foreach ($toReturn as $key => $value) {
        //     # code...
        //     $object = new stdClass();
        //     foreach ($value as $index => $data) {
        //         $object->$index = $data;
        //     }
        //     $ExportData[$key] = $object;
        // }
    }
    public function headings(): array
    {
        $AllReviewData  = $this->AssetReview;
        for($i = 0; $i < count($AllReviewData); $i++)
        {
            for($j = 0; $j < count($AllReviewData[0]); $j++)
            {
                if ($i == 0)
                {
                    if ($AllReviewData[$i][$j] == null)
                    {
                       $heading[$i][$j] =  'Name';
                        
                    }
                    else
                    {
                        $heading[$i][$j] = $AllReviewData[$i][$j];
                    }

                }
            }
        }
        return $heading;
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
                $event->writer->getProperties()->setTitle('Asset Review Sheet');
                $event->writer->getProperties()->setCreator('IT-Scient');

            },
        ];
    }
}
