<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GeoStructure;
use App\Level;
use App\Organisation;
use App\UserData;
use App\User;
use DB;
use Auth;

class GeoStructureController extends Controller
{
    //
    public function index(){
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod12"]["add"]&&!$desig_permissions["mod12"]["edit"]&&!$desig_permissions["mod12"]["view"]&&!$desig_permissions["mod12"]["del"]){
            return back();
        }
        $datas = GeoStructure::leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                                ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                                ->select('geo_structure.*','level.level_name','organisation.org_name')
                                ->orderBy('geo_structure.geo_id','desc')
                                ->get();
                                

        $get_blocks = GeoStructure::where('level_id','3')->get();

        // find parent details
        for($i=0;$i<count($datas);$i++){
            $parent_details = new GeoStructure;
            if($parent_details->find($datas[$i]->parent_id)){
                $parent_details = $parent_details->find($datas[$i]->parent_id);
                $datas[$i]->parent_name = $parent_details->geo_name;
                if($parent_details->level_id=="1"){ $datas[$i]->parent_level_name = "(District)"; }
                if($parent_details->level_id=="2"){ $datas[$i]->parent_level_name = "(Sub Division)"; }
                if($parent_details->level_id=="3"){ $datas[$i]->parent_level_name = "(Block)"; }
                if($parent_details->level_id=="4"){ $datas[$i]->parent_level_name = "(Panchayat)"; }
            }
            else{
                $datas[$i]->parent_name = 'NA';
                $datas[$i]->parent_level_name = "";
            }
        }

        return view('geo-structure.index')->with(compact('datas','get_blocks'));
    }

    public function add(Request $request){
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new GeoStructure;

        $organisation_datas = Organisation::orderBy('org_name','asc')->get();
        $geo_structure_datas = GeoStructure::select('geo_id','geo_name','level_id','parent_id')->get();
        $user_datas = User::leftJoin("designation","users.desig_id","=","designation.desig_id")->where('status',1)->select('users.*','designation.name as desig_name')->get();

        if(isset($request->purpose)&&isset($request->id)){
            $hidden_input_purpose=$request->purpose;
            $hidden_input_id=$request->id;
            $data = $data->find($request->id);
        }

        return view('geo-structure.add')->with(compact('hidden_input_purpose','hidden_input_id','data','geo_structure_datas','user_datas','organisation_datas'));
    }

    public function store(Request $request){
        // return $request;
        $geo_structure = new GeoStructure;

        if($request->hidden_input_purpose=="edit"){
            $geo_structure = $geo_structure->find($request->hidden_input_id);
        }

        $geo_structure->geo_name = $request->geo_name;
        $geo_structure->org_id = 1;
        $geo_structure->level_id = $request->level_id;
        $geo_structure->officer_id = $request->officer_id;

        // initializing initially
        $geo_structure->parent_id = '-1';
        $geo_structure->dist_id = '-1';
        $geo_structure->sd_id = '-1';
        $geo_structure->bl_id = '-1';

        if($request->level_id=="2"){
            $geo_structure->dist_id = $request->dist_id;
            $geo_structure->parent_id = $request->dist_id;
        }
        else if($request->level_id=="3"){
            $geo_structure->dist_id = $request->dist_id;
            $geo_structure->sd_id = $request->sd_id;
            $geo_structure->parent_id = $request->sd_id;
        }
        else if($request->level_id=="4"){
            $geo_structure->dist_id = $request->dist_id;
            $geo_structure->sd_id = $request->sd_id;
            $geo_structure->bl_id = $request->bl_id;
            $geo_structure->parent_id = $request->bl_id;
        }

        if($request->no_of_villages&&$request->level_id=="4")
        {
            $geo_structure->no_of_villages = $request->no_of_villages;
        }
        else
        {
            $geo_structure->no_of_villages = 0; 
        }

        $geo_structure->created_by = '1';
        $geo_structure->updated_by = '1';

        if(GeoStructure::where('geo_name',$request->geo_name)->first()&&$request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','This Geo data '.$request->geo_name.' already exist');
        }
        else if($geo_structure->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Geo Structure Details has been saved Successfully');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new Geo Structure');
        }

        return redirect('geo-structure');
    }

    public function get_block_data(Request $request){
        $datas = GeoStructure::where('level_id','3')->where('sd_id', $request->sd_id)->get();
        return $datas;
    }

    public function get_officer_data(Request $request){
        $datas = User::select('id','title','first_name','middle_name','last_name')->where('userRole', $request->level_id)->get();
        return $datas;
    }

    public function delete(Request $request){
        if(GeoStructure::find($request->geo_id)){
            GeoStructure::where('geo_id',$request->geo_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Data Deleted Successfully');
        }

        return redirect('geo-structure');
    }

    public function exportpdfFunctiuonforgeostructure()
    {
        # code...
        $GeoStructureData = GeoStructure::leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                                ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                                ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name','organisation.org_name','organisation.updated_at as parent_level_name')
                                ->orderBy('geo_structure.geo_id','desc')
                                ->get();
        
        foreach ($GeoStructureData as $key => $value) {
            $parent_details = GeoStructure::where('geo_id',$GeoStructureData[$key]->parent_id)->first();
            if($parent_details){
                $value->parent_name = $parent_details->geo_name;
                if($parent_details->level_id=="1")
                    { $value->parent_level_name = "(District)"; }
                if($parent_details->level_id=="2")
                    { $value->parent_level_name = "(Sub Division)"; }
                if($parent_details->level_id=="3")
                    { $value->parent_level_name = "(Block)"; }
                if($parent_details->level_id=="4")
                    { $value->parent_level_name = "(Panchayat)"; }
            }
            else{
                $value->parent_name = 'NA';
                $value->parent_level_name = "";
            }
        }

        $doc_details = array(
            "title" => "Geo Structure",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'L'
        );

        date_default_timezone_set('Asia/Kolkata');
                      $currentDateTime = date('d-m-Y H:i:s'); 
                      $user_name=Auth::user()->first_name;
                      $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"6\" align=\"left\" ><b>Geo Structure</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Type
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*                Total width of the pdf table is 1017px                     */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 428px;\" align=\"center\"><b>Name</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Level</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Villages</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 170px;\" align=\"center\"><b>Parent</b></th>";
        $content .= "<th style=\"border: solid 1px #000000;width: 169px;\" align=\"center\"><b>Organisation</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        
        // echo "<pre>";
        // print_r($GeoStructureData);exit;
        $content .= "<tbody>";
        foreach ($GeoStructureData as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 428px;\" align=\"left\">" . $row->geo_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->level_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->no_of_villages . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 170px;\" align=\"left\">" . $row->parent_name.$row->parent_level_name . "</td>";
            $content .= "<td style=\"border: solid 1px #000000;width: 169px;\" align=\"left\">" . $row->org_name . "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        // print_r($content);exit;
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('Geo Structure.pdf');
        exit;
    }

    public function exportExcelFunctiuonforgeostructure(){
        $data = array(1 => array("Geo Structure Sheet"));
        $data[] = array('Sl. No.','Name','Level','Village','Parent','Organisation','Date');

        $items = GeoStructure::
            leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
            ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
            ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name',
                    'organisation.org_name','organisation.updated_at as parent_level_name','geo_structure.created_at as createdDate')->orderBy('geo_structure.geo_id','desc')->get();    
                    
                    
            foreach ($items as $key => $value) {
                $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                $parent_details = GeoStructure::where('geo_id',$items[$key]->parent_id)->first();
                if($parent_details){
                    $value->parent_name = $parent_details->geo_name;
                    if($parent_details->level_id=="1")
                        { $value->parent_level_name = "(District)"; }
                    if($parent_details->level_id=="2")
                        { $value->parent_level_name = "(Sub Division)"; }
                    if($parent_details->level_id=="3")
                        { $value->parent_level_name = "(Block)"; }
                    if($parent_details->level_id=="4")
                        { $value->parent_level_name = "(Panchayat)"; }
                }
                else{
                    $value->parent_name = 'NA';
                    $value->parent_level_name = "";
                }
                $data[] = array(
                    $key + 1,
                    $value->geo_name,
                    $value->level_name,
                    $value->no_of_villages,
                    $value->parent_name.$value->parent_level_name,
                    $value->org_name,
                    $value->createdDate,

                );


        }
        \Excel::create('GeoStructure-Sheet', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Geo Structure Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');

    }


    
      // abhishek 
      public function view_diffrent_formate(Request $request)
      {
        //   return $request;
          $department=array();
          $type="";

          if($request->type=="pdf"){
            if($request->level_id=="1"){ $type="district"; }
            if($request->level_id=="2"){ $type="sub_division"; }
            if($request->level_id=="3"){ $type="block"; }
            if($request->level_id=="4"){ $type="panchayat"; }
          }
          else{ // excel
            if($request->level_id=="1"){ $type="districtexcel"; }
            if($request->level_id=="2"){ $type="sub_div_excel"; }
            if($request->level_id=="3"){ $type="block_excel"; }
            if($request->level_id=="4"){ $type="panchayat_excel"; }
          }

        //   return $type;

     
          if($type=="district")
          {
          
              if($request->district_geo_id!="")
              {
  
                  
                
                           
                            $GeoStructureData = GeoStructure::leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                                            ->whereIn('geo_structure.geo_id',$request->district_geo_id)
                                            ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                                            ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name','organisation.org_name','organisation.updated_at as parent_level_name')
                                            ->orderBy('geo_structure.geo_id','desc')
                                            ->get();
                                
                                foreach ($GeoStructureData as $key => $value) {
                                    $parent_details = GeoStructure::where('geo_id',$GeoStructureData[$key]->parent_id)->first();
                                    if($parent_details){
                                        $value->parent_name = $parent_details->geo_name;
                                        if($parent_details->level_id=="1")
                                            { $value->parent_level_name = "(District)"; }
                                        if($parent_details->level_id=="2")
                                            { $value->parent_level_name = "(Sub Division)"; }
                                        if($parent_details->level_id=="3")
                                            { $value->parent_level_name = "(Block)"; }
                                        if($parent_details->level_id=="4")
                                            { $value->parent_level_name = "(Panchayat)"; }
                                    }
                                    else{
                                        $value->parent_name = 'NA';
                                        $value->parent_level_name = "";
                                    }
                                }

                                $doc_details = array(
                                    "title" => "Geo Structure",
                                    "author" => 'IT-Scient',
                                    "topMarginValue" => 10,
                                    "mode" => 'L'
                                );

                                date_default_timezone_set('Asia/Kolkata');
                                            $currentDateTime = date('d-m-Y H:i:s'); 
                                            $user_name=Auth::user()->first_name;
                                            $user_last_name=Auth::user()->last_name;
                                $pdfbuilder = new \PdfBuilder($doc_details);

                                $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                                $content .= "<th style='border: solid 1px #000000;' colspan=\"6\" align=\"left\" ><b>Geo Structure</b></th></tr>";
                                $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Type
                                "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                                "</b>"."</p>";

                                /* ========================================================================= */
                                /*                Total width of the pdf table is 1017px                     */
                                /* ========================================================================= */
                                $content .= "<thead>";
                                $content .= "<tr>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 428px;\" align=\"center\"><b>Name</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Level</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Villages</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 170px;\" align=\"center\"><b>Parent</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 169px;\" align=\"center\"><b>Organisation</b></th>";
                                $content .= "</tr>";
                                $content .= "</thead>";
                                
                                // echo "<pre>";
                                // print_r($GeoStructureData);exit;
                                $content .= "<tbody>";
                                foreach ($GeoStructureData as $key => $row) {
                                    $index = $key+1;
                                    $content .= "<tr>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 428px;\" align=\"left\">" . $row->geo_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->no_of_villages . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 170px;\" align=\"left\">" . $row->parent_name.$row->parent_level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 169px;\" align=\"left\">" . $row->org_name . "</td>";
                                    $content .= "</tr>";
                                }
                                $content .= "</tbody></table>";
                                // print_r($content);exit;
                                $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                                $pdfbuilder->output('Geo Structure.pdf');
                                exit;




  
  
              }
              return $request;
          }
          elseif($type=="sub_division")
          {
          
              if($request->sub_div_geo_id!="")
              {
  
                            $GeoStructureData = GeoStructure::leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                                            ->whereIn('geo_structure.geo_id',$request->sub_div_geo_id)
                                            ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                                            ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name','organisation.org_name','organisation.updated_at as parent_level_name')
                                            ->orderBy('geo_structure.geo_id','desc')
                                            ->get();
                                
                                foreach ($GeoStructureData as $key => $value) {
                                    $parent_details = GeoStructure::where('geo_id',$GeoStructureData[$key]->parent_id)->first();
                                    if($parent_details){
                                        $value->parent_name = $parent_details->geo_name;
                                        if($parent_details->level_id=="1")
                                            { $value->parent_level_name = "(District)"; }
                                        if($parent_details->level_id=="2")
                                            { $value->parent_level_name = "(Sub Division)"; }
                                        if($parent_details->level_id=="3")
                                            { $value->parent_level_name = "(Block)"; }
                                        if($parent_details->level_id=="4")
                                            { $value->parent_level_name = "(Panchayat)"; }
                                    }
                                    else{
                                        $value->parent_name = 'NA';
                                        $value->parent_level_name = "";
                                    }
                                }

                                $doc_details = array(
                                    "title" => "Geo Structure",
                                    "author" => 'IT-Scient',
                                    "topMarginValue" => 10,
                                    "mode" => 'L'
                                );

                                date_default_timezone_set('Asia/Kolkata');
                                            $currentDateTime = date('d-m-Y H:i:s'); 
                                            $user_name=Auth::user()->first_name;
                                            $user_last_name=Auth::user()->last_name;
                                $pdfbuilder = new \PdfBuilder($doc_details);

                                $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                                $content .= "<th style='border: solid 1px #000000;' colspan=\"6\" align=\"left\" ><b>Geo Structure</b></th></tr>";
                                $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Type
                                "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                                "</b>"."</p>";

                                /* ========================================================================= */
                                /*                Total width of the pdf table is 1017px                     */
                                /* ========================================================================= */
                                $content .= "<thead>";
                                $content .= "<tr>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 428px;\" align=\"center\"><b>Name</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Level</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Villages</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 170px;\" align=\"center\"><b>Parent</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 169px;\" align=\"center\"><b>Organisation</b></th>";
                                $content .= "</tr>";
                                $content .= "</thead>";
                                
                                // echo "<pre>";
                                // print_r($GeoStructureData);exit;
                                $content .= "<tbody>";
                                foreach ($GeoStructureData as $key => $row) {
                                    $index = $key+1;
                                    $content .= "<tr>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 428px;\" align=\"left\">" . $row->geo_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->no_of_villages . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 170px;\" align=\"left\">" . $row->parent_name.$row->parent_level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 169px;\" align=\"left\">" . $row->org_name . "</td>";
                                    $content .= "</tr>";
                                }
                                $content .= "</tbody></table>";
                                // print_r($content);exit;
                                $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                                $pdfbuilder->output('Geo Structure.pdf');
                                exit;




  
  
              }
              return $request;
          }
          elseif($type=="block")
          {
          
              if($request->block_geo_id!="")
              {
  
                            $GeoStructureData = GeoStructure::leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                                            ->whereIn('geo_structure.geo_id',$request->block_geo_id)
                                            ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                                            ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name','organisation.org_name','organisation.updated_at as parent_level_name')
                                            ->orderBy('geo_structure.geo_id','desc')
                                            ->get();
                                
                                foreach ($GeoStructureData as $key => $value) {
                                    $parent_details = GeoStructure::where('geo_id',$GeoStructureData[$key]->parent_id)->first();
                                    if($parent_details){
                                        $value->parent_name = $parent_details->geo_name;
                                        if($parent_details->level_id=="1")
                                            { $value->parent_level_name = "(District)"; }
                                        if($parent_details->level_id=="2")
                                            { $value->parent_level_name = "(Sub Division)"; }
                                        if($parent_details->level_id=="3")
                                            { $value->parent_level_name = "(Block)"; }
                                        if($parent_details->level_id=="4")
                                            { $value->parent_level_name = "(Panchayat)"; }
                                    }
                                    else{
                                        $value->parent_name = 'NA';
                                        $value->parent_level_name = "";
                                    }
                                }

                                $doc_details = array(
                                    "title" => "Geo Structure",
                                    "author" => 'IT-Scient',
                                    "topMarginValue" => 10,
                                    "mode" => 'L'
                                );

                                date_default_timezone_set('Asia/Kolkata');
                                            $currentDateTime = date('d-m-Y H:i:s'); 
                                            $user_name=Auth::user()->first_name;
                                            $user_last_name=Auth::user()->last_name;
                                $pdfbuilder = new \PdfBuilder($doc_details);

                                $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                                $content .= "<th style='border: solid 1px #000000;' colspan=\"6\" align=\"left\" ><b>Geo Structure</b></th></tr>";
                                $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Type
                                "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                                "</b>"."</p>";

                                /* ========================================================================= */
                                /*                Total width of the pdf table is 1017px                     */
                                /* ========================================================================= */
                                $content .= "<thead>";
                                $content .= "<tr>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 428px;\" align=\"center\"><b>Name</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Level</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Villages</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 170px;\" align=\"center\"><b>Parent</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 169px;\" align=\"center\"><b>Organisation</b></th>";
                                $content .= "</tr>";
                                $content .= "</thead>";
                                
                                // echo "<pre>";
                                // print_r($GeoStructureData);exit;
                                $content .= "<tbody>";
                                foreach ($GeoStructureData as $key => $row) {
                                    $index = $key+1;
                                    $content .= "<tr>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 428px;\" align=\"left\">" . $row->geo_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->no_of_villages . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 170px;\" align=\"left\">" . $row->parent_name.$row->parent_level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 169px;\" align=\"left\">" . $row->org_name . "</td>";
                                    $content .= "</tr>";
                                }
                                $content .= "</tbody></table>";
                                // print_r($content);exit;
                                $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                                $pdfbuilder->output('Geo Structure.pdf');
                                exit;

              }
              return $request;
          }
          elseif($type=="panchayat")
          {
          
              if($request->payanchat_geo_id!="")
              {
  
                            $GeoStructureData = GeoStructure::leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                                            ->whereIn('geo_structure.geo_id',$request->payanchat_geo_id)
                                            ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                                            ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name','organisation.org_name','organisation.updated_at as parent_level_name')
                                            ->orderBy('geo_structure.geo_id','desc')
                                            ->get();
                                
                                foreach ($GeoStructureData as $key => $value) {
                                    $parent_details = GeoStructure::where('geo_id',$GeoStructureData[$key]->parent_id)->first();
                                    if($parent_details){
                                        $value->parent_name = $parent_details->geo_name;
                                        if($parent_details->level_id=="1")
                                            { $value->parent_level_name = "(District)"; }
                                        if($parent_details->level_id=="2")
                                            { $value->parent_level_name = "(Sub Division)"; }
                                        if($parent_details->level_id=="3")
                                            { $value->parent_level_name = "(Block)"; }
                                        if($parent_details->level_id=="4")
                                            { $value->parent_level_name = "(Panchayat)"; }
                                    }
                                    else{
                                        $value->parent_name = 'NA';
                                        $value->parent_level_name = "";
                                    }
                                }

                                $doc_details = array(
                                    "title" => "Geo Structure",
                                    "author" => 'IT-Scient',
                                    "topMarginValue" => 10,
                                    "mode" => 'L'
                                );

                                date_default_timezone_set('Asia/Kolkata');
                                            $currentDateTime = date('d-m-Y H:i:s'); 
                                            $user_name=Auth::user()->first_name;
                                            $user_last_name=Auth::user()->last_name;
                                $pdfbuilder = new \PdfBuilder($doc_details);

                                $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                                $content .= "<th style='border: solid 1px #000000;' colspan=\"6\" align=\"left\" ><b>Geo Structure</b></th></tr>";
                                $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Type
                                "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                                "</b>"."</p>";

                                /* ========================================================================= */
                                /*                Total width of the pdf table is 1017px                     */
                                /* ========================================================================= */
                                $content .= "<thead>";
                                $content .= "<tr>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 428px;\" align=\"center\"><b>Name</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Level</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 100px;\" align=\"center\"><b>Villages</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 170px;\" align=\"center\"><b>Parent</b></th>";
                                $content .= "<th style=\"border: solid 1px #000000;width: 169px;\" align=\"center\"><b>Organisation</b></th>";
                                $content .= "</tr>";
                                $content .= "</thead>";
                                
                                // echo "<pre>";
                                // print_r($GeoStructureData);exit;
                                $content .= "<tbody>";
                                foreach ($GeoStructureData as $key => $row) {
                                    $index = $key+1;
                                    $content .= "<tr>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 428px;\" align=\"left\">" . $row->geo_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"left\">" . $row->level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 100px;\" align=\"right\">" . $row->no_of_villages . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 170px;\" align=\"left\">" . $row->parent_name.$row->parent_level_name . "</td>";
                                    $content .= "<td style=\"border: solid 1px #000000;width: 169px;\" align=\"left\">" . $row->org_name . "</td>";
                                    $content .= "</tr>";
                                }
                                $content .= "</tbody></table>";
                                // print_r($content);exit;
                                $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                                $pdfbuilder->output('Geo Structure.pdf');
                                exit;

              }
              return $request;
          }
          elseif($type=="districtexcel")
          {
          
              if($request->district_geo_id!="")
              {
                
                $data = array(1 => array("Geo Structure Sheet"));
                $data[] = array('Sl. No.','Name','Level','Village','Parent','Organisation','Date');
        
                $items = GeoStructure::
                    leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                    ->whereIn('geo_structure.geo_id',$request->district_geo_id)
                    ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                    ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name',
                            'organisation.org_name','organisation.updated_at as parent_level_name','geo_structure.created_at as createdDate')->orderBy('geo_structure.geo_id','desc')->get();    
                            
                            
                    foreach ($items as $key => $value) {
                        $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                        $parent_details = GeoStructure::where('geo_id',$items[$key]->parent_id)->first();
                        if($parent_details){
                            $value->parent_name = $parent_details->geo_name;
                            if($parent_details->level_id=="1")
                                { $value->parent_level_name = "(District)"; }
                            if($parent_details->level_id=="2")
                                { $value->parent_level_name = "(Sub Division)"; }
                            if($parent_details->level_id=="3")
                                { $value->parent_level_name = "(Block)"; }
                            if($parent_details->level_id=="4")
                                { $value->parent_level_name = "(Panchayat)"; }
                        }
                        else{
                            $value->parent_name = 'NA';
                            $value->parent_level_name = "";
                        }
                        $data[] = array(
                            $key + 1,
                            $value->geo_name,
                            $value->level_name,
                            $value->no_of_villages,
                            $value->parent_name.$value->parent_level_name,
                            $value->org_name,
                            $value->createdDate,
        
                        );
        
        
                }
                \Excel::create('GeoStructure-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Geo Structure Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Geo Structure', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');
                           
              }
              return $request;
          }
          elseif($type=="sub_div_excel")
          {
          
              if($request->sub_div_geo_id!="")
              {
                
                $data = array(1 => array("Geo Structure Sheet"));
                $data[] = array('Sl. No.','Name','Level','Village','Parent','Organisation','Date');
        
                $items = GeoStructure::
                    leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                    ->whereIn('geo_structure.geo_id',$request->sub_div_geo_id)
                    ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                    ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name',
                            'organisation.org_name','organisation.updated_at as parent_level_name','geo_structure.created_at as createdDate')->orderBy('geo_structure.geo_id','desc')->get();    
                            
                            
                    foreach ($items as $key => $value) {
                        $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                        $parent_details = GeoStructure::where('geo_id',$items[$key]->parent_id)->first();
                        if($parent_details){
                            $value->parent_name = $parent_details->geo_name;
                            if($parent_details->level_id=="1")
                                { $value->parent_level_name = "(District)"; }
                            if($parent_details->level_id=="2")
                                { $value->parent_level_name = "(Sub Division)"; }
                            if($parent_details->level_id=="3")
                                { $value->parent_level_name = "(Block)"; }
                            if($parent_details->level_id=="4")
                                { $value->parent_level_name = "(Panchayat)"; }
                        }
                        else{
                            $value->parent_name = 'NA';
                            $value->parent_level_name = "";
                        }
                        $data[] = array(
                            $key + 1,
                            $value->geo_name,
                            $value->level_name,
                            $value->no_of_villages,
                            $value->parent_name.$value->parent_level_name,
                            $value->org_name,
                            $value->createdDate,
        
                        );
        
        
                }
                \Excel::create('GeoStructure-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Geo Structure Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Geo Structure', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');
                           
              }
              return $request;
          }
          elseif($type=="block_excel")
          {
          
              if($request->block_geo_id!="")
              {
                
                $data = array(1 => array("Geo Structure Sheet"));
                $data[] = array('Sl. No.','Name','Level','Village','Parent','Organisation','Date');
        
                $items = GeoStructure::
                    leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                    ->whereIn('geo_structure.geo_id',$request->block_geo_id)
                    ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                    ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name',
                            'organisation.org_name','organisation.updated_at as parent_level_name','geo_structure.created_at as createdDate')->orderBy('geo_structure.geo_id','desc')->get();    
                            
                            
                    foreach ($items as $key => $value) {
                        $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                        $parent_details = GeoStructure::where('geo_id',$items[$key]->parent_id)->first();
                        if($parent_details){
                            $value->parent_name = $parent_details->geo_name;
                            if($parent_details->level_id=="1")
                                { $value->parent_level_name = "(District)"; }
                            if($parent_details->level_id=="2")
                                { $value->parent_level_name = "(Sub Division)"; }
                            if($parent_details->level_id=="3")
                                { $value->parent_level_name = "(Block)"; }
                            if($parent_details->level_id=="4")
                                { $value->parent_level_name = "(Panchayat)"; }
                        }
                        else{
                            $value->parent_name = 'NA';
                            $value->parent_level_name = "";
                        }
                        $data[] = array(
                            $key + 1,
                            $value->geo_name,
                            $value->level_name,
                            $value->no_of_villages,
                            $value->parent_name.$value->parent_level_name,
                            $value->org_name,
                            $value->createdDate,
        
                        );
        
        
                }
                \Excel::create('GeoStructure-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Geo Structure Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Geo Structure', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');
                           
              }
              return $request;
          }
          elseif($type=="panchayat_excel")
          {
          
              if($request->payanchat_geo_id!="")
              {
                
                $data = array(1 => array("Geo Structure Sheet"));
                $data[] = array('Sl. No.','Name','Level','Village','Parent','Organisation','Date');
        
                $items = GeoStructure::
                    leftJoin('level', 'geo_structure.level_id', '=', 'level.level_id')
                    ->whereIn('geo_structure.geo_id',$request->payanchat_geo_id)
                    ->leftJoin('organisation','geo_structure.org_id','=','organisation.org_id')
                    ->select('geo_structure.*','level.level_name','level.parent_level_id as parent_name',
                            'organisation.org_name','organisation.updated_at as parent_level_name','geo_structure.created_at as createdDate')->orderBy('geo_structure.geo_id','desc')->get();    
                            
                            
                    foreach ($items as $key => $value) {
                        $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                        $parent_details = GeoStructure::where('geo_id',$items[$key]->parent_id)->first();
                        if($parent_details){
                            $value->parent_name = $parent_details->geo_name;
                            if($parent_details->level_id=="1")
                                { $value->parent_level_name = "(District)"; }
                            if($parent_details->level_id=="2")
                                { $value->parent_level_name = "(Sub Division)"; }
                            if($parent_details->level_id=="3")
                                { $value->parent_level_name = "(Block)"; }
                            if($parent_details->level_id=="4")
                                { $value->parent_level_name = "(Panchayat)"; }
                        }
                        else{
                            $value->parent_name = 'NA';
                            $value->parent_level_name = "";
                        }
                        $data[] = array(
                            $key + 1,
                            $value->geo_name,
                            $value->level_name,
                            $value->no_of_villages,
                            $value->parent_name.$value->parent_level_name,
                            $value->org_name,
                            $value->createdDate,
        
                        );
        
        
                }
                \Excel::create('GeoStructure-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Geo Structure Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Geo Structure', function ($sheet) use ($data) {
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
