<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use App\Organisation;
use App\Fav_Dept;
use App\GeoStructure;
use App\SchemeStructure;
use App\Fav_Scheme;
use App\Fav_Block;
use App\Fav_Panchayat;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FavouriteExport;
use App\Exports\FavouriteScheme;
use App\Exports\FavouriteBlock;
use App\Exports\FavouritePanchayat;
use PDF;
use DB;
use App\Asset;
use App\Fav_Define_Assets;
use App\Exports\FavouriteAssets;
USE Auth;



class FavController extends Controller
{
    public function index()
    {
           //Department FAV CODE 
            $datas_dept = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
                ->select('department.*','organisation.org_name')->where('department.is_active',1)
                ->orderBy('department.dept_id','asc')->get();
 
            for($i=0;$i<count($datas_dept);$i++){
                $fav_dept_tmp = Fav_Dept::select('favourite_department_id')->where('user_id', session()->get('user_id'))->where('dept_id',$datas_dept[$i]->dept_id)->first();

                if($fav_dept_tmp){
                    $datas_dept[$i]->checked=1;
                }
                else{
                    $datas_dept[$i]->checked=0;
                }
            }

            //Scheme fav code or not fav
            $datas_scheme = SchemeStructure::select('scheme_id','scheme_name','scheme_short_name')->get();
            for($i=0;$i<count($datas_scheme);$i++)
            {
                $fav_scheme_tmp = Fav_Scheme::select('favourite_scheme_id')->where('user_id', session()->get('user_id'))->where('scheme_id',$datas_scheme[$i]->scheme_id)->first();
                if($fav_scheme_tmp){
                    $datas_scheme[$i]->checked=1;
                }
                else{
                    $datas_scheme[$i]->checked=0;
                }
            }


            //block fav code 
            $datas_block = GeoStructure::select('geo_id','geo_name')->where('level_id','3')
                        ->orderBy('geo_structure.geo_id','asc')->get();
            
            for($i=0;$i<count($datas_block);$i++){
                $fav_block_tmp = Fav_Block::select('favourite_block_id')->where('user_id', session()->get('user_id'))->where('block_id',$datas_block[$i]->geo_id)->first();
                if($fav_block_tmp){
                    $datas_block[$i]->checked=1;
                }
                else{
                    $datas_block[$i]->checked=0;
                }
            }

            //fav panchayat fav  or not
            $datas_panchayat = GeoStructure::select('geo_id','geo_name')->where('level_id','4')
                                ->orderBy('geo_structure.geo_id','asc')->get();
            for($i=0;$i<count($datas_panchayat);$i++){
                $fav_panchayat_tmp = Fav_Panchayat::select('favourite_panchayat_id')->where('user_id', session()->get('user_id'))->where('panchayat_id',$datas_panchayat[$i]->geo_id)->first();
                if($fav_panchayat_tmp){
                    $datas_panchayat[$i]->checked=1;
                }
                else{
                    $datas_panchayat[$i]->checked=0;
                }
            }

            //fav Assets     
            $datas_define_asset = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                                ->select('asset.*','department.dept_name')
                                ->orderBy('asset.asset_id','asc')->get();
            
            for($i=0;$i<count($datas_define_asset);$i++)
            {
                $fav_define_tmp = Fav_Define_Assets::select('favourite_asset_id')->where('user_id', session()->get('user_id'))->where('asset_id',$datas_define_asset[$i]->asset_id)->first();

                if($fav_define_tmp){
                    $datas_define_asset[$i]->checked=1;
                }
                else{
                    $datas_define_asset[$i]->checked=0;
                }
            }

        return view('favourite.fav_all',compact('datas_dept','datas_scheme','datas_block','datas_panchayat','datas_define_asset'));//->with('datas_dept', $datas_dept);

    }


    public function add_fav_departs(Request $request)
    {
        if(($request->dept_id)!=0)
        {           
            // delete previous entries
            $delete_query = Fav_Dept::where('user_id', session()->get('user_id'))->delete();
            
            $count_id = $request->dept_id;
            foreach ($count_id as $department_id) 
            {                       
                $fav_department= new Fav_Dept();
                $fav_department->dept_id = $department_id;              
                $fav_department->user_id = session()->get('user_id');
                $fav_department->org_id = session()->get('user_org_id');
                $fav_department->created_by = session()->get('user_id');
                $fav_department->updated_by = session()->get('user_id');
                $fav_department->save();                
            }
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Your Favourite Department is Inserted');
            return redirect('favourites');
        }
        else
        {
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Select Atleast one Favourite Department!');
            return redirect('favourites');
        }        
    }


    public function add_fav_scheme(Request $request){
        if(($request->scheme_id)!=0){ 
            
            // delete previous entries
            $delete_query = Fav_Scheme::where('user_id', session()->get('user_id'))->delete();

            $count_id = $request->scheme_id;           
            foreach ($count_id as $scheme_id) {        
                $fav_scheme= new Fav_Scheme();
                $fav_scheme->scheme_id = $scheme_id; 
                $fav_scheme->user_id = session()->get('user_id');
                $fav_scheme->org_id = session()->get('user_org_id');
                $fav_scheme->created_by = session()->get('user_id');
                $fav_scheme->updated_by = session()->get('user_id');
                $fav_scheme->save();                
            }
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Your Favourite Scheme is Inserted');
            return redirect('favourites');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Select Atleast one Favourite Scheme!');
            return redirect('favourites');
        }      
    }


    public function add_fav_block(Request $request){
        if(($request->block_id)!=0){   

            // delete previous entries
            $delete_query = Fav_Block::where('user_id', session()->get('user_id'))->delete();

            $count_id = $request->block_id;           
            foreach ($count_id as $block_id) {        
                $fav_block= new Fav_Block();
                $fav_block->block_id = $block_id; 
                $fav_block->user_id = session()->get('user_id');
                $fav_block->org_id = session()->get('user_org_id');
                $fav_block->created_by = session()->get('user_id');
                $fav_block->updated_by = session()->get('user_id');
                $fav_block->save();                
            }
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Your Favourite Block is Inserted');
            return redirect('favourites');   
        } 
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Select Atleast one Favourite Block!');
            return redirect('favourites');
        }   
        
    }

    
    public function add_fav_panchayat(Request $request){
        if(($request->panchayat_id)!=0){

             // delete previous entries
            $delete_query = Fav_Panchayat::where('user_id', session()->get('user_id'))->delete(); 

            $count_id = $request->panchayat_id;           
            foreach ($count_id as $panchayat_id) {        
                $fav_block= new Fav_Panchayat();
                $fav_block->panchayat_id = $panchayat_id; 
                $fav_block->user_id = session()->get('user_id');
                $fav_block->org_id = session()->get('user_org_id');
                $fav_block->created_by = session()->get('user_id');
                $fav_block->updated_by = session()->get('user_id');
                $fav_block->save();                
            }
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Your Favourite Panchayat is Inserted.');
            return redirect('favourites');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Select Atleast one Favourite Panchayat!');
            return redirect('favourites');
        }     
    }

    public function add_fav_define_asset(Request $request)
    {
        if(($request->asset_id)!=0)
        {            
            // delete previous entries
            $delete_query = Fav_Define_Assets::where('user_id', session()->get('user_id'))->delete();

            $count_id = $request->asset_id; 
            foreach ($count_id as $asset_id) {     
                $fav_define_asset= new Fav_Define_Assets();
                $fav_define_asset->asset_id = $asset_id;              
                $fav_define_asset->user_id = session()->get('user_id');
                $fav_define_asset->org_id = session()->get('user_org_id');
                $fav_define_asset->created_by = session()->get('user_id');
                $fav_define_asset->updated_by = session()->get('user_id');
                $fav_define_asset->save();
            }
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Your Favourite Assets is Inserted');
            return redirect('favourites');
        } 
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Select Atleast one Favourite Assets!');
            return redirect('favourites');

        }  

    }


/** Here the export section started  */
//Asset Department excel section by rohit singh

    public function export_Excel_Department()
    {
            $data = array(1 => array("Favourite Department-Sheet"));
            $data[] = array('Sl.No.','Department Name','Date','Check Favourite');
    
            $items = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
            ->select('department.dept_id as slId','department.dept_name','department.updated_at as checked','department.created_at as createdDate')->where('department.is_active',1)
            ->orderBy('department.dept_id','asc')
            ->get();
    
            foreach ($items as $key => $value) {
                    $fav_dept_tmp = Fav_Dept::where('user_id', session()->get('user_id'))->where('dept_id',$items[$key]->slId)->first();
                    if($fav_dept_tmp!=""){
                        $value->checked="Yes";
                    }
                    else{
                        $value->checked="No";
                    }
                $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                $data[] = array(
                    $key + 1,
                    $value->dept_name,
                    $value->createdDate,
                    $value->checked,
                );
     
            }
            \Excel::create('Favourite Department', function ($excel) use ($data) {
    
                // Set the title
                $excel->setTitle('Favourite Department-Sheet');
    
                // Chain the setters
                $excel->setCreator('Seraikela')->setCompany('Seraikela');
    
                $excel->sheet('Favourite Department-Sheet', function ($sheet) use ($data) {
                    $sheet->freezePane('A3');
                    $sheet->mergeCells('A1:I1');
                    $sheet->fromArray($data, null, 'A1', true, false);
                    $sheet->setColumnFormat(array('I1' => '@'));
                });
            })->download('xls');
    }


    //Asset Department pdf sectionrohit singh
    public function export_PDF_Department()
    {
        $departmentpdf = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
                            ->select('department.*','organisation.org_name')->where('department.is_active',1)
                            ->orderBy('department.dept_id','asc')
                            ->get();                 
          foreach ($departmentpdf as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->created_at));
            $fav_dept_tmp = Fav_Dept::select('favourite_department_id')->where('user_id', session()->get('user_id'))->where('dept_id',$departmentpdf[$key]->dept_id)->first();
                if($fav_dept_tmp){
                    $departmentpdf[$key]->checked="Yes";
                }
                else{
                    $departmentpdf[$key]->checked="No";
                }
            
        }

        $doc_details = array(
            "title" => "Department Favourite",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        date_default_timezone_set('Asia/Kolkata');
                            $currentDateTime = date('d-m-Y H:i:s'); 
                            $user_name=Auth::user()->first_name;
                            $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);
        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Department Favourite Details</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Department Favourite Details
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 140px;\" align=\"center\"><b>Check Favourite</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 429px;\" align=\"center\"><b>Department Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($departmentpdf as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 140px;\" align=\"left\">" . $row->checked . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 429px;\" align=\"left\">" . $row->dept_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('DepartmentFav.pdf');
        exit;
    }

    //Scheme_Excel  section rohit singh
    public function export_Scheme_Excel_Department()
    {
        $data = array(1 => array("Favourite Scheme Sheet"));
        $data[] = array('Sl.No.','Scheme Name','Short Name','Date','Check Favourite');

        $items = SchemeStructure::select('scheme_id as slId','scheme_name','scheme_short_name','created_at as createdDate')->get();

        foreach ($items as $key => $value) {
            $fav_scheme_tmp = Fav_Scheme::where('user_id', session()->get('user_id'))->where('scheme_id',$items[$key]->slId)->first();
                if($fav_scheme_tmp!=""){
                    $value->checked="Yes";
                }
                else{
                    $value->checked="No";
                }
            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->scheme_name,
                $value->scheme_short_name,
                $value->createdDate,
                $value->checked,
            );
 
        }
        \Excel::create('Favourite Scheme-Sheet', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Favourite Scheme-Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Favourite Scheme-Sheet', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }


    //Scheme pdf section rohit singh
    public function export_Scheme_PDF_Department()
    {
        $Scheme_pdf = SchemeStructure::select('scheme_id','scheme_name','scheme_short_name','created_at as createdDate')->get();
        foreach ($Scheme_pdf as $key => $value) {
            $fav_scheme_tmp = Fav_Scheme::select('favourite_scheme_id')->where('user_id', session()->get('user_id'))->where('scheme_id',$Scheme_pdf[$key]->scheme_id)->first();
            if($fav_scheme_tmp){
                $Scheme_pdf[$key]->checked="Yes";
            }
            else{
                $Scheme_pdf[$key]->checked="No";
            }
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
        }
        // echo"<pre>";
        // print_r($Scheme_pdf);
        // exit;

        $doc_details = array(
            "title" => "Scheme Structure Favourite",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('d-m-Y H:i:s'); 
        $user_name=Auth::user()->first_name;
        $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);
        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"left\" ><b>Scheme Structure Favourite Details</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Structure Favourite Details
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 169px;\" align=\"center\"><b>Check Favourite</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 300px;\" align=\"center\"><b>Scheme Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Short Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($Scheme_pdf as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 169px;\" align=\"left\">" . $row->checked . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 300px;\" align=\"left\">" . $row->scheme_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->scheme_short_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('SchemeFavourite.pdf');
        exit;
    }


    //Block_Excel  section by  rohit singh
    public function export_Block_Excel_Department()
    {
            $data = array(1 => array("Favourite Block-Sheet"));
            $data[] = array('Sl.No.','Block Name','Date','Check Favourite');
    
            $items = GeoStructure::select('geo_id as slId','geo_name','created_at as createdDate')->where('level_id','3')
                ->orderBy('geo_structure.geo_id','asc')->get();
    
            foreach ($items as $key => $value) 
            {
                $fav_block_tmp = Fav_Block::select('favourite_block_id')->where('user_id', session()->get('user_id'))->where('block_id',$items[$key]->slId)->first();
                    if($fav_block_tmp!=""){
                        $value->checked="Yes";
                    }
                    else{
                        $value->checked="No";
                    }
                $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                $data[] = array(
                    $key + 1,
                    $value->geo_name,
                    $value->createdDate,
                    $value->checked,
                );
            }

            \Excel::create('Favourite Block Sheet', function ($excel) use ($data) {
    
                // Set the title
                $excel->setTitle('Favourite Block-Sheet');
    
                // Chain the setters
                $excel->setCreator('Seraikela')->setCompany('Seraikela');
    
                $excel->sheet('Favourite Block Sheet', function ($sheet) use ($data) {
                    $sheet->freezePane('A3');
                    $sheet->mergeCells('A1:I1');
                    $sheet->fromArray($data, null, 'A1', true, false);
                    $sheet->setColumnFormat(array('I1' => '@'));
                });
            })->download('xls');       
    }      

    
        
    //Block_pdf section  by rohit singh
    public function export_Block_PDF_Department()
    {               
        $block_pdf = GeoStructure::select('geo_id','geo_name','created_at as createdDate')->where('level_id','3')->orderBy('geo_structure.geo_id','asc')->get();
          foreach ($block_pdf as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
            $fav_block_tmp = Fav_Block::select('favourite_block_id')->where('user_id', session()->get('user_id'))->where('block_id',$block_pdf[$key]->geo_id)->first();
                if($fav_block_tmp){
                    $block_pdf[$key]->checked="Yes";
                }
                else{
                    $block_pdf[$key]->checked="No";
                }          
        }

        $doc_details = array(
            "title" => "Favourite Block",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('d-m-Y H:i:s'); 
        $user_name=Auth::user()->first_name;
        $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);
        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Favourite Block Details</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Favourite Block Details
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"width: 140px;\" align=\"center\"><b>Check Favourite</b></th>";
        $content .= "<th style=\"width: 429px;\" align=\"center\"><b>Block Name</b></th>";
        $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($block_pdf as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"width: 140px;\" align=\"left\">" . $row->checked . "</td>";
            $content .= "<td style=\"width: 429px;\" align=\"left\">" . $row->geo_name . "</td>";
            $content .= "<td style=\"width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('BlockFavourite.pdf');
        exit;
    }


    //Panchayat_Excel  section by  rohit singh
    public function export_Panchayat_Excel_Department()
    {
        $data = array(1 => array("Favourite Panchayat-Sheet"));
        $data[] = array('Sl.No.','Panchayat Name','Date','Check Favourite');

        $items = GeoStructure::select('geo_id as slId','geo_name','created_at as createdDate' )->where('level_id','4')
        ->orderBy('geo_structure.geo_id','asc')->get();
       
        foreach ($items as $key => $value) 
        {
            $fav_panchayat_tmp = Fav_Panchayat::where('user_id', session()->get('user_id'))->where('panchayat_id',$items[$key]->slId)->first();
                if($fav_panchayat_tmp!=""){
                    $value->checked="Yes";
                }
                else{
                    $value->checked="No";
                }
            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->geo_name,
                $value->createdDate,
                $value->checked,
            );
        }

        \Excel::create('Favourite Panchayat-Sheet', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Favourite Panchayat-Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Favourite Panchayat-Sheet', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }


    //Panchayat_ pdf section  by rohit singh
    public function export_Panchayat_PDF_Department()
    {
        $panchayat_pdf = GeoStructure::select('geo_id','geo_name','created_at as createdDate')->where('level_id','4')->orderBy('geo_structure.geo_id','asc')->get();
        foreach ($panchayat_pdf as $key => $value) {
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
            $fav_panchayat_tmp = Fav_Panchayat::select('favourite_panchayat_id')->where('user_id', session()->get('user_id'))->where('panchayat_id',$panchayat_pdf[$key]->geo_id)->first();          
              if($fav_panchayat_tmp){
                  $panchayat_pdf[$key]->checked="Yes";
              }
              else{
                  $panchayat_pdf[$key]->checked="No";
              }          
        }

      $doc_details = array(
          "title" => "Favourite Panchayat",
          "author" => 'IT-Scient',
          "topMarginValue" => 10,
          "mode" => 'P'
      );

      date_default_timezone_set('Asia/Kolkata');
                            $currentDateTime = date('d-m-Y H:i:s'); 
                            $user_name=Auth::user()->first_name;
                            $user_last_name=Auth::user()->last_name;
      $pdfbuilder = new \PdfBuilder($doc_details);
      $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
      $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Favourite Panchayat Details</b></th></tr>";
      $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Favourite Panchayat Details
      "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
      "</b>"."</p>";

      /* ========================================================================= */
      /*             Total width of the pdf table is 1017px lanscape               */
      /*             Total width of the pdf table is 709px portrait                */
      /* ========================================================================= */
      $content .= "<thead>";
      $content .= "<tr>";
      $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
      $content .= "<th style=\"width: 140px;\" align=\"center\"><b>Check Favourite</b></th>";
      $content .= "<th style=\"width: 429px;\" align=\"center\"><b>Panchayat Name</b></th>";
      $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Date</b></th>";
      $content .= "</tr>";
      $content .= "</thead>";
      $content .= "<tbody>";
      foreach ($panchayat_pdf as $key => $row) {
          $index = $key+1;
          $content .= "<tr>";
          $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
          $content .= "<td style=\"width: 140px;\" align=\"left\">" . $row->checked . "</td>";
          $content .= "<td style=\"width: 429px;\" align=\"left\">" . $row->geo_name . "</td>";
          $content .= "<td style=\"width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
          $content .= "</tr>";
      }
      $content .= "</tbody></table>";
      $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
      $pdfbuilder->output('PanchayatFavourite.pdf');
      exit;
    }


    //DefineAsset_Excel   section by  rohit singh
     public function export_DefineAsset_Excel_Department()
     {
         $data = array(1 => array("Favourite Resource-Sheet"));
         $data[] = array('Sl.No.','Asset Name','Department Name','Date','Check Favourite');
 
         $items = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                        ->select('asset.asset_id as slId','asset.asset_name as asset_name','department.dept_name','asset.created_at as createdDate')
                        ->orderBy('asset.asset_id','asc')->get();
        
         foreach ($items as $key => $value) 
         {
            $fav_define_tmp = Fav_Define_Assets::where('user_id', session()->get('user_id'))->where('asset_id',$items[$key]->slId)->first();
                 if($fav_define_tmp!=""){
                     $value->checked="Yes";
                 }
                 else{
                     $value->checked="No";
                 }
             $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
             $data[] = array(
                 $key + 1,
                 $value->asset_name,
                 $value->dept_name,
                 $value->createdDate,
                 $value->checked,
                );
         }
 
         \Excel::create('Favourite Resource-Sheet', function ($excel) use ($data) {
 
             // Set the title
             $excel->setTitle('FavouriteAssets-Sheet');
 
             // Chain the setters
             $excel->setCreator('Seraikela')->setCompany('Seraikela');
 
             $excel->sheet('Favourite Resource-Sheet', function ($sheet) use ($data) {
                 $sheet->freezePane('A3');
                 $sheet->mergeCells('A1:I1');
                 $sheet->fromArray($data, null, 'A1', true, false);
                 $sheet->setColumnFormat(array('I1' => '@'));
             });
         })->download('xls');
     }



     //DefineAsset_ pdf section  by rohit singh
    public function export_DefineAsset_PDF_Department()
    {
        $asset_pdf = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                    ->select('asset.*','department.dept_name')->orderBy('asset.asset_id','asc')->get();
        foreach ($asset_pdf as $key => $value) {
            $fav_define_tmp = Fav_Define_Assets::select('favourite_asset_id')->where('user_id', session()->get('user_id'))->where('asset_id',$asset_pdf[$key]->asset_id)->first();
            if($fav_define_tmp){
                $asset_pdf[$key]->checked="Yes";
            }
            else{
                $asset_pdf[$key]->checked="No";
            }
            $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
        }

        $doc_details = array(
            "title" => "Resource Favourite",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        date_default_timezone_set('Asia/Kolkata');
                            $currentDateTime = date('d-m-Y H:i:s'); 
                            $user_name=Auth::user()->first_name;
                            $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);
        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"left\" ><b>Resource Favourite Details</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Resource Favourite Details
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 149px;\" align=\"center\"><b>Check Favourite</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 220px;\" align=\"center\"><b>Asset Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 200px;\" align=\"center\"><b>Department Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($asset_pdf as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 149px;\" align=\"left\">" . $row->checked . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 220px;\" align=\"left\">" . $row->asset_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 200px;\" align=\"left\">" . $row->dept_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('AssetsFavourite.pdf');
        exit;
    }
    

        // abhishek 
        public function view_diffrent_formate(Request $request)
        {
        //    return $request;
        //    return "akf";
           $geo_id = explode(',',$request->geo_id); // array
            $department=array();
            if($request->print=="print_pdf")
            {
                 
                if($request->geo_id!="")
                {
    
                      
                     
                    $block_pdf = GeoStructure::whereIn('geo_id',$geo_id)->select('geo_id','geo_name','created_at as createdDate')->where('level_id','3')->orderBy('geo_structure.geo_id','asc')->get();
                    foreach ($block_pdf as $key => $value) {
                      $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
                      $fav_block_tmp = Fav_Block::select('favourite_block_id')->where('user_id', session()->get('user_id'))->where('block_id',$block_pdf[$key]->geo_id)->first();
                          if($fav_block_tmp){
                              $block_pdf[$key]->checked="Yes";
                          }
                          else{
                              $block_pdf[$key]->checked="No";
                          }          
                  }
          
                  $doc_details = array(
                      "title" => "Favourite Block",
                      "author" => 'IT-Scient',
                      "topMarginValue" => 10,
                      "mode" => 'P'
                  );
          
                  date_default_timezone_set('Asia/Kolkata');
                  $currentDateTime = date('d-m-Y H:i:s'); 
                  $user_name=Auth::user()->first_name;
                  $user_last_name=Auth::user()->last_name;
                  $pdfbuilder = new \PdfBuilder($doc_details);
                  $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                  $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Favourite Block Details</b></th></tr>";
                  $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Favourite Block Details
                  "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                  "</b>"."</p>";
          
                  /* ========================================================================= */
                  /*             Total width of the pdf table is 1017px lanscape               */
                  /*             Total width of the pdf table is 709px portrait                */
                  /* ========================================================================= */
                  $content .= "<thead>";
                  $content .= "<tr>";
                  $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                  $content .= "<th style=\"width: 140px;\" align=\"center\"><b>Check Favourite</b></th>";
                  $content .= "<th style=\"width: 429px;\" align=\"center\"><b>Block Name</b></th>";
                  $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Date</b></th>";
                  $content .= "</tr>";
                  $content .= "</thead>";
                  $content .= "<tbody>";
                  foreach ($block_pdf as $key => $row) {
                      $index = $key+1;
                      $content .= "<tr>";
                      $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
                      $content .= "<td style=\"width: 140px;\" align=\"left\">" . $row->checked . "</td>";
                      $content .= "<td style=\"width: 429px;\" align=\"left\">" . $row->geo_name . "</td>";
                      $content .= "<td style=\"width: 90px;\" align=\"right\">" . $row->createdDate. "</td>";
                      $content .= "</tr>";
                  }
                  $content .= "</tbody></table>";
                  $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                  $pdfbuilder->output('BlockFavourite.pdf');
                  exit;
   
                        
   
    
                }
               //  return $request;
            }
            elseif($request->print=="excel_sheet")
            {
                   // return $request;
                if($request->geo_id!="")
                {
    
                    $data = array(1 => array("Favourite Block-Sheet"));
                    $data[] = array('Sl.No.','Block Name','Date','Check Favourite');
            
                    $items = GeoStructure::whereIn('geo_id',$geo_id)->select('geo_id as slId','geo_name','created_at as createdDate')->where('level_id','3')
                        ->orderBy('geo_structure.geo_id','asc')->get();
            
                    foreach ($items as $key => $value) 
                    {
                        $fav_block_tmp = Fav_Block::select('favourite_block_id')->where('user_id', session()->get('user_id'))->where('block_id',$items[$key]->slId)->first();
                            if($fav_block_tmp!=""){
                                $value->checked="Yes";
                            }
                            else{
                                $value->checked="No";
                            }
                        $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                        $data[] = array(
                            $key + 1,
                            $value->geo_name,
                            $value->createdDate,
                            $value->checked,
                        );
                    }
        
                    \Excel::create('Favourite Block Sheet', function ($excel) use ($data) {
            
                        // Set the title
                        $excel->setTitle('Favourite Block-Sheet');
            
                        // Chain the setters
                        $excel->setCreator('Seraikela')->setCompany('Seraikela');
            
                        $excel->sheet('Favourite Block Sheet', function ($sheet) use ($data) {
                            $sheet->freezePane('A3');
                            $sheet->mergeCells('A1:I1');
                            $sheet->fromArray($data, null, 'A1', true, false);
                            $sheet->setColumnFormat(array('I1' => '@'));
                        });
                    })->download('xls');  
           
    
                }
                return $request;
            }
        }

}
