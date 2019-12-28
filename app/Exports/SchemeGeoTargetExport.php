<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use DB;
use App\Fav_Dept;
use App\SchemeStructure;
use App\Asset;
use App\Fav_Define_Assets;
use App\SchemeGeoTarget;
use App\GeoStructure;




class SchemeGeoTargetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $SchemeGeoTarget_pdf = SchemeGeoTarget::leftJoin('scheme_structure', 'scheme_geo_target.scheme_id', '=', 'scheme_structure.scheme_id')
            ->leftJoin('geo_structure', 'scheme_geo_target.geo_id', '=', 'geo_structure.geo_id')
            ->leftJoin('scheme_indicator','scheme_geo_target.indicator_id','=','scheme_indicator.indicator_id')
            ->leftJoin('year','scheme_geo_target.year_id','=','year.year_id')
            ->leftJoin('scheme_group','scheme_geo_target.group_id','=','scheme_group.scheme_group_id')
            ->select('scheme_geo_target.scheme_geo_target_id as slId',
                    'scheme_structure.scheme_name as scheme_name',
                    'scheme_indicator.indicator_name',
                    'geo_structure.updated_at as bl_name',
                    'geo_structure.geo_name',
                    'geo_structure.level_id',
                    'scheme_group.scheme_group_name','geo_structure.parent_id',
                    'year.year_value','scheme_geo_target.created_at as createdDate')->orderBy('scheme_geo_target.scheme_geo_target_id','desc')->get();

                    foreach($SchemeGeoTarget_pdf as $key => $data){
                        $data->slId = $key+1;
                        // $data->bl_name;
                        $data->createdDate = date('d/m/Y',strtotime($data->createdDate));
                        if($data->level_id==4)
                        {
                            $tmp = GeoStructure::find($data->parent_id);
                            if($tmp->geo_name)
                            { 
                                $data->bl_name = $tmp->geo_name; 
                            }
                            else{
                            $data->bl_name = "NA";
                            }
                        }
                        else{
                            $data->bl_name = "NA";
                        }
                    }
                    // echo "<pre>";
                    // print_r($SchemeGeoTarget_pdf);
                    // exit;
             return $SchemeGeoTarget_pdf;
    }

    public function headings(): array
    {
        return [
            'Sl.No.',
            'Scheme',
            'Indicator',
            'Block Name',
            'Panchyat',
            'Asset Group Name',
            'Target',
            'Year',
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

