<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeAsset;
use App\Uom;


class Scheme_Asset_Controller extends Controller
{

    public function index()
    {
        
       
        $datas = SchemeAsset::orderBy('scheme_asset_id','desc')->get();
      
        return view('scheme-asset.index',compact('datas'));
        
    }
    public function add(Request $request){
        
        $hidden_input_purpose = "add";
        $hidden_input_id= "NA";
        $data = new SchemeAsset;
        
        $uom_data = Uom::orderBy('uom_id','desc')->select('uom_name','uom_type')->get();
        // $uom_datas = Uom::select('uom_id','uom_name')->get();

        if(isset($request->purpose)&&isset($request->id)){
            $data = $data->find($request->id);
            if($data){
                $hidden_input_purpose=$request->purpose;
                $hidden_input_id=$request->id;
            }
        }
       
        return view('scheme-asset.add')->with(compact('uom_datas','hidden_input_purpose','hidden_input_id','data','uom_data'));
    }

   
    public function store(Request $request)
    {
        $scheme_asset = new SchemeAsset;

        if($request->hidden_input_purpose=="edit"){
            $scheme_asset = $scheme_asset->find($request->hidden_input_id);
        }

        $scheme_asset->scheme_asset_name = $request->scheme_asset_name;
        $scheme_asset->geo_related = $request->geo_related;
        $scheme_asset->radius = $request->radius;
        $scheme_asset->uom_type_id = $request->uom_type_id;

        if ($request->hasFile('mapmarkericon')) {
            $upload_directory = "public/uploaded_documents/scheme_assets/mapmarker/";
            $file = $request->file('mapmarkericon');
            $mapmarker_temp = "mapmarker-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $mapmarker_temp);   // move the file to desired folder

            // deleteprevious icon
            if ($request->hidden_input_purpose == "edit") {
                if (file_exists($scheme_asset->mapmarkericon)) {
                    unlink($scheme_asset->mapmarkericon);
                }
            }
             $scheme_asset->mapmarkericon = $upload_directory . $mapmarker_temp;    // assign the location of folder to the model
        } 
        else {
            if ($request->hidden_input_purpose == "add") {
                $scheme_asset->mapmarkericon = "";
            } else if ($request->hidden_input_purpose == "edit" && $request->scheme_assets_delete) { // edit
                $scheme_asset->mapmarkericon = "";
            }
        }
        //
        // // to previous attachment if delete clicked
        if($request->scheme_assets_delete){
            if(file_exists($request->scheme_assets_delete)){
                unlink($request->scheme_assets_delete);
            }
        }
        
        if($request->geo_related!="")
        {
            $scheme_asset->multiple_geo_tags = $request->multiple_geo_tags;
        }
        else
        {
            $scheme_asset->multiple_geo_tags = null;
        }
       
        $scheme_asset->created_by = Auth::user()->id;
        $scheme_asset->updated_by = Auth::user()->id;

        $attribute = [];
        for($i=0;$i<count($request->attribute_name);$i++)
        {
            $tmp = ["id"=>uniqid(), "name"=>$request->attribute_name[$i], "uom"=>$request->attribute_uom[$i]];
            if($request->attribute_mandatory[$i]){
                $tmp["mandatory"] = $request->attribute_mandatory[$i];
            }
            else{
                $tmp["mandatory"] = '0';
            }
            // $tmp = [uniqid()=>["name"=>$request->attribute_name[$i], "uom"=>$request->attribute_uom[$i]]];
            array_push($attribute, $tmp);
        }
        $scheme_asset->attribute=serialize($attribute);
      
        // echo "<pre>";
        // print_r($request->toArray());
        // print_r($attribute);
        // exit;

        if(SchemeAsset::where('scheme_asset_name',$request->scheme_asset_name)->first() && $request->hidden_input_purpose!="edit"){
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Scheme Asset '.$request->scheme_asset_name.' already exist !');
        }
        else if($scheme_asset->save()){
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Scheme Asset have been successfully submitted !');
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','Something went wrong while adding new details !');
        }

        return redirect('scheme-asset');
    }

    public function view(Request $request)
    {
        if(SchemeAsset::find($request->scheme_asset_id)){
            $data =  SchemeAsset::where('scheme_asset_id',$request->scheme_asset_id)->first();
        }
        else{
            session()->put('alert-class','alert-danger');
            session()->put('alert-content','No such scheme asset exists!');
            return redirect('scheme-asset');
        }
        $uom_datas = Uom::select('uom_id','uom_name')->get();
       
        return view('scheme-asset.view')->with(compact('data','uom_datas'));
    }



    public function delete(Request $request)
    {
        if(SchemeAsset::find($request->scheme_asset_id)){
            SchemeAsset::where('scheme_asset_id',$request->scheme_asset_id)->delete();
            session()->put('alert-class','alert-success');
            session()->put('alert-content','Deleted successfully !');
        }
        return redirect('scheme-asset');    
    }
    
         // abhishek 
         public function view_diffrent_formate(Request $request)
         {
            // return $request;
            // return "akf";
            $scheme_asset_id = explode(',',$request->scheme_asset_id); // array
             $department=array();
             if($request->print=="print_pdf")
             {
                  
                 if($request->scheme_asset_id!="")
                 {
     
                       
                            $scheme_structure =  SchemeAsset::whereIn('scheme_asset_id',$scheme_asset_id)->get();
                            // return $scheme_structure;
    
                            // $year =  Year::whereIn('year_id',$year_id)->orderBy('year_id','desc')->get();

                            foreach ($scheme_structure as $key => $value) {
                                // $value->createdDate = date('d/m/Y',strtotime($value->created_at));
                                if($value->geo_related == 1){
                                    $value->geo_related= "yes";
                                }
                                else 
                                {
                                    $value->geo_related= "no";
                                }
                            }

                            foreach ($scheme_structure as $key => $value) {
                                
                                if($value->multiple_geo_tags == 1){
                                    $value->multiple_geo_tags= "yes";
                                }
                                else 
                                {
                                    $value->multiple_geo_tags= "no";
                                }
                            }
    
                            $doc_details = array(
                                "title" => "Scheme",
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
                            $content .= "<th style='border: solid 1px #000000;' colspan=\"4\" align=\"left\" ><b>Scheme Asset Details</b></th></tr>";
                            $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Scheme Asset Details
                            "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                            "</b>"."</p>";
    
                            /* ========================================================================= */
                            /*             Total width of the pdf table is 1017px lanscape               */
                            /*             Total width of the pdf table is 709px portrait                */
                            /* ========================================================================= */
                            $content .= "<thead>";
                            $content .= "<tr>";
                            $content .= "<th style=\"border: solid 1px #000000;width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                            $content .= "<th style=\"border: solid 1px #000000;width: 429px;\" align=\"center\"><b>Scheme Asset Name</b></th>";
                            $content .= "<th style=\"border: solid 1px #000000;width: 140px;\" align=\"center\"><b>Geo Related</b></th>";
                            $content .= "<th style=\"border: solid 1px #000000;width: 90px;\" align=\"center\"><b>Multi Geo Related</b></th>";
                            $content .= "</tr>";
                            $content .= "</thead>";
                            $content .= "<tbody>";
                            foreach ($scheme_structure as $key => $row) {
                                $index = $key+1;
                                $content .= "<tr>";
                                $content .= "<td style=\"border: solid 1px #000000;width: 50px;\" align=\"right\">" . $index . "</td>";
                                $content .= "<td style=\"border: solid 1px #000000;width: 429px;\" align=\"left\">" . $row->scheme_asset_name . "</td>";
                                $content .= "<td style=\"border: solid 1px #000000;width: 110px;\" align=\"left\">" . $row->geo_related . "</td>";
                                $content .= "<td style=\"border: solid 1px #000000;width: 120px;\" align=\"left\">" . $row->multiple_geo_tags . "</td>";
                                
                                $content .= "</tr>";
                            }
                            $content .= "</tbody></table>";
                            $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                            $pdfbuilder->output('Year.pdf');
                            exit;
    
    
                         
    
     
                 }
                //  return $request;
             }
             elseif($request->print=="excel_sheet")
             {
                    // return $request;
                 if($request->scheme_asset_id!="")
                 {
     
                    $data = array(1 => array("Year-Sheet"));
                    $data[] = array( 'Sl. No.','Scheme Asset Name','Geo Related','Multi Geo Related');
            
                    $scheme_structure =  SchemeAsset::whereIn('scheme_asset_id',$scheme_asset_id)->get();
                    // return $scheme_structure;
                    // $yearValue = Year::whereIn('year_id', $year_id)->orderBy('year_id','desc')->select('year_id as slId', 'year_value', 'status', 'created_at as createdDate')->get();
            
                    foreach ($scheme_structure as $key => $value) {
                        if($value->geo_related == 1){
                            $value->geo_related= "yes";
                        }
                        else 
                        {
                            $value->geo_related= "no";
                        }

                        if($value->multiple_geo_tags == 1){
                            $value->multiple_geo_tags= "yes";
                        }
                        else 
                        {
                            $value->multiple_geo_tags= "no";
                        }
                        $value->createdDate = date('d/m/Y',strtotime($value->createdDate));
                        $data[] = array(
                            $key + 1,
                            $value->scheme_asset_name,
                            $value->geo_related,
                            $value->multiple_geo_tags,
                        );
                    }
                    
                    \Excel::create('Scheme Asset', function ($excel) use ($data) {
            
                        // Set the title
                        $excel->setTitle('Scheme Asset');
            
                        // Chain the setters
                        $excel->setCreator('Scheme Asset')->setCompany('Scheme Asset');
            
                        $excel->sheet('Scheme Asset', function ($sheet) use ($data) {
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
