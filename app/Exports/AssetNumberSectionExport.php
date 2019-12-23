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
use App\AssetNumbers;
use App\GeoStructure;
use App\Asset;
use App\Year;
use App\AssetGeoLocation;
use App\AssetBlockCount;
use App\AssetGallery;

class AssetNumberSectionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect(); //important as the existing connection if any would be in strict mode
        
     
        $ExportData = DB::table('asset_numbers')
		->leftJoin('geo_structure', 'asset_numbers.geo_id', '=', 'geo_structure.geo_id')
		->leftJoin('asset', 'asset_numbers.asset_id', '=', 'asset.asset_id')
		->leftJoin('year', 'asset_numbers.year', '=', 'year.year_id')
		->select('asset_numbers.asset_numbers_id as slNo', 
			'year.year_value', 
			'asset.asset_name', 
			'geo_structure.bl_id as Block', 
			'geo_structure.geo_name', 
			'asset_numbers.current_value')
        ->groupBy('asset_numbers.year', 'asset_numbers.asset_id', 'asset_numbers.geo_id')
        ->get();

           //now changing back the strict ON
           config()->set('database.connections.mysql.strict', true);
           \DB::reconnect();
        foreach ($ExportData as $key => $value) {
            $value->slNo = $key+1;
            $block_data_tmp = GeoStructure::find($value->Block);
            $value->Block = $block_data_tmp->geo_name;
        }
        return $ExportData;
    }
    public function headings(): array
    {
        return [
            'Sl. No.',
            'Year',
            'Asset',
            'Block',
            'Panchyat',
            'Current Value'
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
                $event->writer->getProperties()->setTitle('Asset Number Sheet');
                $event->writer->getProperties()->setCreator('IT-Scient');

            },
        ];
    }
}
