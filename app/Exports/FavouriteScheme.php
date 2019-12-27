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
use App\SchemeStructure;
use App\Fav_Scheme;



class FavouriteScheme implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $Scheme_excel = SchemeStructure::select('scheme_id as slId','scheme_name','scheme_short_name','created_at as createdDate')->get();

        for($i=0;$i<count($Scheme_excel);$i++)
        {
            $fav_scheme_tmp = Fav_Scheme::where('user_id',1)->where('scheme_id',$Scheme_excel[$i]->slId)->first();
            if($fav_scheme_tmp){
                $Scheme_excel[$i]->checked="Yes";
            }
            else{
                $Scheme_excel[$i]->checked="No";
            }
        }

        foreach ($Scheme_excel as $key => $value) {
            $value->slId = $key+1;
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
        }
     
        return $Scheme_excel;                      
    }
    public function headings(): array
    {
        return [
            'Sl.No.',
            'Scheme Name',
            'Short Name',
            'Date',
            'Check Favourite'
            
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
