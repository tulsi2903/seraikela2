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



class FavController extends Controller
{
    public function index(){
           //Department FAV CODE FOR FAV OR NOT FAV    
            $datas_dept = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
                ->select('department.*','organisation.org_name')->where('department.is_active',1)
                ->orderBy('department.dept_id','asc')->get();
 
            for($i=0;$i<count($datas_dept);$i++){
                $fav_dept_tmp = Fav_Dept::select('favourite_department_id')->where('user_id',1)->where('dept_id',$datas_dept[$i]->dept_id)->first();

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
                $fav_scheme_tmp = Fav_Scheme::select('favourite_scheme_id')->where('user_id',1)->where('scheme_id',$datas_scheme[$i]->scheme_id)->first();
                if($fav_scheme_tmp){
                    $datas_scheme[$i]->checked=1;
                }
                else{
                    $datas_scheme[$i]->checked=0;
                }
            }


            //block fav code or not fav
            $datas_block = GeoStructure::select('geo_id','geo_name')->where('level_id','3')
                        ->orderBy('geo_structure.geo_id','asc')->get();
            
            for($i=0;$i<count($datas_block);$i++){
                $fav_block_tmp = Fav_Block::select('favourite_block_id')->where('user_id',1)->where('block_id',$datas_block[$i]->geo_id)->first();
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
                $fav_panchayat_tmp = Fav_Panchayat::select('favourite_panchayat_id')->where('user_id',1)->where('panchayat_id',$datas_panchayat[$i]->geo_id)->first();
                if($fav_panchayat_tmp){
                    $datas_panchayat[$i]->checked=1;
                }
                else{
                    $datas_panchayat[$i]->checked=0;
                }

            }
        return view('favourite.fav_all',compact('datas_dept','datas_scheme','datas_block','datas_panchayat'));//->with('datas_dept', $datas_dept);

    }


    public function add_fav_departs(Request $request)
    {
        if(($request->dept_id)!=0)
        {           
            // delete previous entries
            $delete_query = Fav_Dept::where('user_id',1)->delete();
            
            $count_id = $request->dept_id;
            foreach ($count_id as $department_id) 
            {                       
                $fav_department= new Fav_Dept();
                $fav_department->dept_id = $department_id;              
                $fav_department->user_id =1;
                $fav_department->org_id = 1;
                $fav_department->created_by =1;
                $fav_department->updated_by = 1;
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
            $delete_query = Fav_Scheme::where('user_id',1)->delete();

            $count_id = $request->scheme_id;           
            foreach ($count_id as $scheme_id) {        
                $fav_scheme= new Fav_Scheme();
                $fav_scheme->scheme_id = $scheme_id; 
                $fav_scheme->user_id =1;
                $fav_scheme->org_id = 1;
                $fav_scheme->created_by =1;
                $fav_scheme->updated_by = 1;
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
            $delete_query = Fav_Block::where('user_id',1)->delete();

            $count_id = $request->block_id;           
            foreach ($count_id as $block_id) {        
                $fav_block= new Fav_Block();
                $fav_block->block_id = $block_id; 
                $fav_block->user_id =1;
                $fav_block->org_id = 1;
                $fav_block->created_by =1;
                $fav_block->updated_by = 1;
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
             $delete_query = Fav_Panchayat::where('user_id',1)->delete(); 

            $count_id = $request->panchayat_id;           
            foreach ($count_id as $panchayat_id) {        
                $fav_block= new Fav_Panchayat();
                $fav_block->panchayat_id = $panchayat_id; 
                $fav_block->user_id =1;
                $fav_block->org_id = 1;
                $fav_block->created_by =1;
                $fav_block->updated_by = 1;
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




/** Here the export section started  */
//Asset Department excel section by rohit singh

    public function export_Excel_Department()
    {
        return Excel::download(new FavouriteExport, 'FavouriteDepartment-Sheet.xls');
    }
    //Asset Department pdf sectionrohit singh
    public function export_PDF_Department()
    {
        $departmentpdf = Department::leftJoin('organisation', 'department.org_id', '=', 'organisation.org_id')
            ->select('department.*','organisation.org_name')->where('department.is_active',1)
            ->orderBy('department.dept_id','asc')
            ->get();

        for($i=0;$i<count($departmentpdf);$i++){
            $fav_dept_tmp = Fav_Dept::select('favourite_department_id')->where('user_id',1)->where('dept_id',$departmentpdf[$i]->dept_id)->first();

            if($fav_dept_tmp){
                $departmentpdf[$i]->checked=1;
            }
            else{
                $departmentpdf[$i]->checked=0;
            }
        }
        date_default_timezone_set('Asia/Kolkata');
        $DeprtmentTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs',compact('departmentpdf','DeprtmentTime'));
        return $pdf->download('favouriteDeprtment.pdf');
    }

    //Scheme_Excel  section rohit singh
    public function export_Scheme_Excel_Department()
    {
        return Excel::download(new FavouriteScheme, 'FavouriteScheme-Sheet.xls');
    }
    //Scheme pdf section rohit singh
    public function export_Scheme_PDF_Department()
    {
        $Scheme_pdf = SchemeStructure::select('scheme_id','scheme_name','scheme_short_name')->get();
        for($i=0;$i<count($Scheme_pdf);$i++)
        {
            $fav_scheme_tmp = Fav_Scheme::select('favourite_scheme_id')->where('user_id',1)->where('scheme_id',$Scheme_pdf[$i]->scheme_id)->first();
            if($fav_scheme_tmp){
                $Scheme_pdf[$i]->checked=1;
            }
            else{
                $Scheme_pdf[$i]->checked=0;
            }
        }

        date_default_timezone_set('Asia/Kolkata');
        $SchemeTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs',compact('Scheme_pdf','SchemeTime'));
        return $pdf->download('favouriteScheme.pdf');
    }


    //Block_Excel  section by  rohit singh
    public function export_Block_Excel_Department()
    {
        return Excel::download(new FavouriteBlock, 'FavouriteBlock-Sheet.xls');
    }
    //Block_pdf section  by rohit singh
    public function export_Block_PDF_Department()
    {
        $block_pdf = GeoStructure::select('geo_id','geo_name')->where('level_id','3')
                        ->orderBy('geo_structure.geo_id','asc')->get();

        for($i=0;$i<count($block_pdf);$i++)
        {
            $fav_block_tmp = Fav_Block::select('favourite_block_id')->where('user_id',1)->where('block_id',$block_pdf[$i]->geo_id)->first();
            if($fav_block_tmp){
                $block_pdf[$i]->checked=1;
            }
            else{
                $block_pdf[$i]->checked=0;
            }
        }

        date_default_timezone_set('Asia/Kolkata');
        $BlockTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs',compact('block_pdf','BlockTime'));
        return $pdf->download('favouriteBlock.pdf');
    }

        //Panchayat_Excel   section by  rohit singh
        public function export_Panchayat_Excel_Department()
        {
            return Excel::download(new FavouritePanchayat, 'FavouritePanchayat-Sheet.xls');
        }
        //Panchayat_ pdf section  by rohit singh
        public function export_Panchayat_PDF_Department()
        {
            $panchayat_pdf = GeoStructure::select('geo_id','geo_name')->where('level_id','4')
                        ->orderBy('geo_structure.geo_id','asc')->get();

                for($i=0;$i<count($panchayat_pdf);$i++)
                {
                    $fav_panchayat_tmp = Fav_Panchayat::select('favourite_panchayat_id')->where('user_id',1)->where('panchayat_id',$panchayat_pdf[$i]->geo_id)->first();
                    if($fav_panchayat_tmp){
                        $panchayat_pdf[$i]->checked=1;
                    }
                    else{
                        $panchayat_pdf[$i]->checked=0;
                    }

                }
    
            date_default_timezone_set('Asia/Kolkata');
            $PanchayatTime = date('d-m-Y H:i A');
            $pdf = PDF::loadView('department/Createpdfs',compact('panchayat_pdf','PanchayatTime'));
            return $pdf->download('favouritePanchayat.pdf');
        }
    


    











}
