<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetBlockCount;
use App\AssetNumbers;
use App\AssetGeoLocation;
use App\Asset;
use App\Department;
use App\GeoStructure;
use App\Year;
use App\AssetGallery;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetReviewSectionExport;
use PDF;
use Mail;

class AssetReviewController extends Controller
{
    //
    public function index(){
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod19"]["add"]&&!$desig_permissions["mod19"]["edit"]&&!$desig_permissions["mod19"]["view"]&&!$desig_permissions["mod19"]["del"]){
            return back();
        }
       $block_datas = GeoStructure::where('level_id','3')->get();
       $department_datas = Department::orderBy('dept_name')->get();
       $year_datas = Year::select('year_id','year_value')->where('status', 1)->get();
       // return view('asset-review.index')->with(compact('block_datas','department_datas','year_datas'));
       return view('asset-review.index')->with(compact('block_datas','department_datas','year_datas'));
    }

    public function show(Request $request){
        $year = $request->year;
        $geo_id = explode(',',$request->geo_id);
        return view('asset-review.show')->with(compact('geo_id','year'));
    }

    // to get all views datas
    public function get_datas(Request $request){
        // initializing what to send back
        $tabular_view = []; // tabular data
        $chart_labels = []; // labels charts.js (block names)
        $chart_datasets = []; // for datasets charts.js [{label:'',data:[]},{label:'',data:[]}]
        $map_view_blocks = [];
        $map_view_assets = [];
        $gallery_images = [];

        // received datas
        $review_for = $request->review_for;
        $geo_id = explode(",", $request->geo_id); // geo_id/block_id/panchayat_id received as
        $no_of_blocks = count($geo_id);
        $dept_id = $request->dept_id;
        $year = $request->year_id;

        // step 1: get asset_block_count/asset_numbers rows according to panchayat_id, geo_id (block), dept_id, year_id
        if($review_for=="block"){ // block review
            $datas = AssetBlockCount::whereIn('asset_id', Asset::select('asset_id')->where('dept_id',$dept_id)->get())
                ->whereIn('geo_id', $geo_id)
                ->where('year', $year)
                ->get();
        }
        else{ // panchayat review
            $get_asset_numbers_id_tmp = AssetNumbers::select('geo_id', 'asset_id', 'year', DB::raw('MAX(updated_at) AS max_updated'), DB::raw('MAX(asset_numbers_id) as asset_numbers_id'))
                ->whereIn('asset_id', Asset::select('asset_id')->where('dept_id',$dept_id)->get())
                ->whereIn('geo_id', $geo_id)
                ->where('year', $year)
                ->groupBy('year','asset_id','geo_id')
                ->get();

            // $datas = AssetNumbers::whereIn('asset_id', Asset::select('asset_id')->where('dept_id',$dept_id)->get())
            //     ->whereIn('geo_id', $panchayat_id)
            //     ->where('year', $year)
            //     ->get();
            $datas = AssetNumbers::whereIn('asset_numbers_id', $get_asset_numbers_id_tmp->pluck('asset_numbers_id'))
                ->get();
            //** Important: have to count only last update of each combination
        }

        /* getting unique Asset Ids, map_view_assets*/
        $asset_unique_ids = []; // unique asset ids
        // 1- all unique ids assigned to punchayats 
        // foreach($datas as $data){
        //     if(!in_array($data->asset_id, $asset_unique_ids)){
        //         array_push($asset_unique_ids, $data->asset_id);
        //          // also add map_view_assets
        //     }
        // }
        // 2- all asset ids (on request department)
        $asset_tmps = Asset::where('dept_id', $dept_id)->get();
        foreach($asset_tmps as $asset_tmp){
            if(!in_array($asset_tmp->asset_id, $asset_unique_ids)){
                array_push($asset_unique_ids, $asset_tmp->asset_id);
                array_push($map_view_assets, ['id'=>$asset_tmp->asset_id,'name'=>$asset_tmp->asset_name]);
            }
        }

        // creating tabular data <thead>, assigning chart labels, assigning map view block names & id
        if($review_for=="block") // block review
        {
            $tabular_view_tmp=[''];
            for($i=0;$i<$no_of_blocks;$i++){
                $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$geo_id[$i])->first();
                array_push($tabular_view_tmp, $geo_name->geo_name);
                array_push($chart_labels, $geo_name->geo_name);
                array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
            }
            array_push($tabular_view,$tabular_view_tmp);

            foreach($asset_unique_ids as $asset_unique_id){
                $asset_name = Asset::select('asset_name')->where('asset_id',$asset_unique_id)->first();
                $tabular_view_tmp = [$asset_name->asset_name];
                $chart_datasets_tmp = [];
                $chart_datasets_tmp['label'] = $asset_name->asset_name;
                $chart_datasets_tmp['data'] = [];

                for($i=0;$i<$no_of_blocks;$i++){
                    $found = 0;
                    foreach($datas as $data)
                    {
                        if($data->asset_id==$asset_unique_id)
                        {
                            if($data->geo_id==$geo_id[$i]){
                                array_push($tabular_view_tmp,$data->count);
                                array_push($chart_datasets_tmp['data'], $data->count);
                                /****** for gallery images: starts *****/
                                // for panchayat name
                                $get_panchayat_ids = GeoStructure::where("bl_id", $data->geo_id)->pluck('geo_id');
                                $asset_gallery_rows = AssetGallery::whereIn('geo_id', $get_panchayat_ids)
                                            ->where('asset_id', $data->asset_id)
                                            ->where('year_id', $data->year)
                                            ->get();

                                $get_block_name = GeoStructure::where("geo_id", $data->geo_id)->pluck("geo_name");
                                foreach($asset_gallery_rows as $asset_gallery_row)
                                {
                                    $get_panchayat_name = GeoStructure::where("geo_id", $asset_gallery_row->geo_id)->pluck('geo_name');
                                    $gallery_images_tmp = ["block_name"=>$get_block_name, "panchayat_name"=>$get_panchayat_name, "asset_name"=>$asset_name->asset_name, "images"=>unserialize($asset_gallery_row->images)];
                                    array_push($gallery_images, $gallery_images_tmp); // merging previous stored gallery_images to current gallery images
                                }
                                /****** for gallery images: ends *****/
                                $found=1;
                            }
                        }
                    }

                    if($found==0){
                        array_push($tabular_view_tmp, '0');
                        array_push($chart_datasets_tmp['data'], 0.2);
                    }
                }
                array_push($tabular_view, $tabular_view_tmp);
                array_push($chart_datasets, ((object) $chart_datasets_tmp));
            }
        }
        else // panchayat review
        {
            $tabular_view_tmp=[''];
            for($i=0;$i<count($geo_id);$i++){
                $geo_name = GeoStructure::select('geo_id','geo_name')->where('geo_id',$geo_id[$i])->first();
                array_push($tabular_view_tmp, $geo_name->geo_name);
                array_push($chart_labels, $geo_name->geo_name);
                array_push($map_view_blocks, ['id'=>$geo_name->geo_id,'name'=>$geo_name->geo_name]);
            }
            array_push($tabular_view,$tabular_view_tmp);

            foreach($asset_unique_ids as $asset_unique_id){
                $asset_name = Asset::select('asset_name')->where('asset_id',$asset_unique_id)->first();
                $tabular_view_tmp = [$asset_name->asset_name];
                $chart_datasets_tmp = [];
                $chart_datasets_tmp['label'] = $asset_name->asset_name;
                $chart_datasets_tmp['data'] = [];

                for($i=0;$i<count($geo_id);$i++){
                    $found = 0;
                    foreach($datas as $data)
                    {
                        if($data->asset_id==$asset_unique_id)
                        {
                            if($data->geo_id==$geo_id[$i]){
                                array_push($tabular_view_tmp, $data->current_value);
                                array_push($chart_datasets_tmp['data'], $data->current_value);
                                /****** for gallery images: starts *****/
                                $asset_gallery_row = AssetGallery::where('geo_id', $data->geo_id)
                                            ->where('asset_id', $data->asset_id)
                                            ->where('year_id', $data->year)
                                            ->first();
                                // for panchayat name
                                $get_panchayat_name = GeoStructure::where("geo_id", $asset_gallery_row->geo_id)->first();
                                $get_block_name = GeoStructure::where("geo_id", $get_panchayat_name->bl_id)->pluck("geo_name");
                                $gallery_images_tmp = ["block_name"=>$get_block_name, "panchayat_name"=>$get_panchayat_name->geo_name, "asset_name"=>$asset_name->asset_name, "images"=>unserialize($asset_gallery_row->images)];
                                // old query
                                // $gallery_images_tmp = unserialize(AssetGallery::where('geo_id', $data->geo_id)
                                //             ->where('asset_id', $data->asset_id)
                                //             ->where('year_id', $data->year)
                                //             ->first()->images);
                                array_push($gallery_images, $gallery_images_tmp); // merging previous stored gallery_images to current gallery images
                                /****** for gallery images: ends *****/
                                $found=1;
                            }
                        }
                    }

                    if($found==0){
                        array_push($tabular_view_tmp, '0');
                        array_push($chart_datasets_tmp['data'], 0.2);
                    }
                }
                array_push($tabular_view, $tabular_view_tmp);
                array_push($chart_datasets, ((object) $chart_datasets_tmp));
            }
        }


        if(count($asset_unique_ids)!=0){
            $response = "success"; // if no records found
        }
        else{
            $response = "no_data";
        }
        // echo "<pre>";
        // print_r($gallery_images);
        // exit;
        return ['review_for'=>$review_for, 'datas'=>$datas, 'response'=>$response, 'tabular_view'=>$tabular_view, 'chart_labels'=>$chart_labels, 'chart_datasets'=>$chart_datasets, 'map_view_blocks'=>$map_view_blocks, 'map_view_assets'=>$map_view_assets, 'gallery_images'=>$gallery_images];
    }


    public function get_assets_datas(Request $request){
        $dept_id = $request->dept_id;
        $asset_datas = Asset::select('asset_id','asset_name')->where('dept_id', $dept_id)->get();
        return $asset_datas;
    }

    public function get_tabular_view_datas(Request $request){
        // initializing what to send back
        $tabular_view = [];
        $map_datas=[];

        // received datas
        $review_for = $request->review_for;
        $geo_id = explode(",", $request->geo_id); // geo_id/block_id/panchayat_id received as
        $dept_id = $request->dept_id;
        if($request->asset_id){
            $asset_id = [$request->asset_id];
            $asset_unique_ids = [$request->asset_id];
        }
        else{
            $asset_id = Asset::where('dept_id',$dept_id)->get()->pluck('asset_id');
            $asset_unique_ids = Asset::where('dept_id', $dept_id)->get()->pluck('asset_id');
        }
        $year = $request->year_id;

        // assigning block datas
        $block_datas; // block_id, block_name
        if($review_for=="block"){
            $block_datas = GeoStructure::select('geo_id as block_id','geo_name as block_name')->whereIn('geo_id', $geo_id)->get();
        }
        if($review_for=="panchayat"){
            $bl_id_tmp = GeoStructure::select('bl_id')->whereIn('geo_id', $geo_id)->distinct()->get()->pluck('bl_id');
            // return $bl_id_tmp;
            $block_datas = GeoStructure::select('geo_id as block_id','geo_name as block_name')->whereIn('geo_id', $bl_id_tmp)->get();
        }

        foreach($block_datas as $block_data){
            $tabular_view_block_wise = [];
            
            if($review_for=="block"){
                $panchayat_datas = GeoStructure::where('bl_id',$block_data->block_id)->get();
            }
            else{ // review_for panchayat
                // select only those panchayat which are selected in map
                $panchayat_datas = GeoStructure::whereIn('geo_id', $geo_id)->where('bl_id', $block_data->block_id)->get();
            }
            $panchayat_ids = $panchayat_datas->pluck('geo_id'); // getting only ids
            // for panchayat names (for <th> i.e. heading)
            $tmp = $panchayat_datas->pluck('geo_name')->toArray();
            array_unshift($tmp, "");
            array_push($tabular_view_block_wise, $tmp);

            $get_asset_numbers_id_tmp = AssetNumbers::select('geo_id', 'asset_id', 'year', DB::raw('MAX(updated_at) AS max_updated'), DB::raw('MAX(asset_numbers_id) as asset_numbers_id'))
                ->whereIn('asset_id', $asset_id)
                ->whereIn('geo_id', $panchayat_ids)
                ->where('year', $year)
                ->groupBy('year','asset_id','geo_id')
                ->get();
            $datas = AssetNumbers::whereIn('asset_numbers_id', $get_asset_numbers_id_tmp->pluck('asset_numbers_id'))
                ->get();

            foreach($asset_unique_ids as $asset_unique_id){
                $asset_name = Asset::select('asset_name')->where('asset_id',$asset_unique_id)->first();
                $tabular_view_tmp = [$asset_name->asset_name];

                for($i=0;$i<count($panchayat_ids);$i++){
                    $found = 0;
                    foreach($datas as $data)
                    {
                        if($data->asset_id==$asset_unique_id)
                        {
                            if($data->geo_id==$panchayat_ids[$i]){
                                array_push($tabular_view_tmp, $data->current_value);
                                array_push($chart_datasets_tmp['data'], $data->current_value);
                                $found=1;
                            }
                        }
                    }

                    if($found==0){
                        array_push($tabular_view_tmp, '0');
                    }
                }
                array_push($tabular_view_block_wise, $tabular_view_tmp);
            }

            /**for map datas **/
            if($request->asset_id){
                $asset_parent_id_test = Asset::select('parent_id')->where('asset_id', $request->asset_id)->first()->parent_id;
            }
            else{
                $asset_parent_id_test = -1;
            }

            if($asset_parent_id_test==-1){
                $map_datas_tmps = AssetGeoLocation::leftJoin('geo_structure', 'asset_geo_location.geo_id', '=', 'geo_structure.geo_id')
                    ->leftJoin('asset', 'asset_geo_location.asset_id', '=', 'asset.asset_id')
                    ->select('asset_geo_location.asset_geo_loc_id','asset_geo_location.asset_id','asset_geo_location.geo_id','asset_geo_location.location_name','asset_geo_location.latitude','asset_geo_location.longitude','asset.asset_name','asset.asset_icon','geo_structure.geo_name')
                    ->whereIn('asset_geo_location.geo_id', $panchayat_ids)
                    ->whereIn('asset_geo_location.asset_id', $asset_unique_ids)
                    ->where('asset_geo_location.year', $year)
                    ->get();

                foreach($map_datas_tmps as $map_datas_tmp){
                    $asset_numbers_child = AssetNumbers::leftJoin('asset', 'asset_numbers.asset_id', '=', 'asset.asset_id')
                        ->select('asset_numbers.current_value','asset.asset_name')
                        ->where('asset_numbers.asset_geo_loc_id', $map_datas_tmp->asset_geo_loc_id)
                        ->where('asset_numbers.current_value','>', 0)
                        ->get();
                    $map_datas_tmp->child = $asset_numbers_child;

                    $asset_numbers_current_value = AssetNumbers::where('geo_id',$map_datas_tmp->geo_id)
                        ->where('asset_id',$map_datas_tmp->asset_id)
                        ->where('year',$year)
                        ->first()->current_value;
                    $map_datas_tmp->current_value = $asset_numbers_current_value;


                    array_push($map_datas, $map_datas_tmp);
                }
            }
            else{
                $asset_numbers_childs = AssetNumbers::leftJoin('asset', 'asset_numbers.asset_id', '=', 'asset.asset_id')
                                    ->leftJoin('asset_geo_location', 'asset_numbers.asset_geo_loc_id', '=', 'asset_geo_location.asset_geo_loc_id')
                                    ->leftJoin('geo_structure', 'asset_numbers.geo_id', '=', 'geo_structure.geo_id')
                                    ->select('asset_numbers.current_value','asset.asset_name','asset.asset_icon','asset_geo_location.latitude','asset_geo_location.longitude','asset_geo_location.location_name','geo_structure.geo_name')
                                    ->where('asset_numbers.asset_id', $request->asset_id)
                                    ->whereIn('asset_numbers.geo_id', $panchayat_ids)
                                    ->where('asset_numbers.year', $year)
                                    ->where('asset_numbers.current_value','>', 0)
                                    ->get();

                foreach($asset_numbers_childs as $asset_numbers_child){
                    array_push($map_datas, $asset_numbers_child);
                }
                                   
            }
            /** for map datas **/

            array_push($tabular_view, ["block_name"=>$block_data->block_name, "count_datas"=>$tabular_view_block_wise]);

        }


        if(count($asset_unique_ids)!=0){
            $response = "success"; // if no records found
        }
        else{
            $response = "no_data";
        }

        // return $map_datas;

        return ['map_datas'=>$map_datas, 'review_for'=>$review_for, 'response'=>$response, 'tabular_view'=>$tabular_view, "asset_id"=>$asset_id, "uniwue"=>$asset_unique_ids];
    }

    public function get_panchayat_data(Request $request){
        $data = [];
        $geo_id = explode(",", $request->geo_id); // geo_id received as

        for($i=0;$i<count($geo_id);$i++){
            $to_send = ["block_name"=>"","panchayat_data"=>""];
            $tmp = GeoStructure::select('geo_name')->where('geo_id', $geo_id[$i])
            ->first();
            $to_send["block_name"] = $tmp->geo_name;
            $tmp = GeoStructure::select('geo_id','geo_name')->where('bl_id', $geo_id[$i])
            ->get();
            $to_send["panchayat_data"] = $tmp;
            array_push($data, $to_send);
        }

        if(count($data)!=0){
            $response = "success"; // if no records found
        }
        else{
            $response = "no_data";
        }
        
        return ['data'=>$data, 'response'=>$response];
    }

    public function get_map_data(Request $request){
        if($request->review_for=="block") // block review
        {
            $data = AssetGeoLocation::leftJoin('geo_structure', 'asset_geo_location.geo_id', '=', 'geo_structure.geo_id')
                ->select('asset_geo_location.*','geo_structure.geo_name')
                ->whereIn('asset_geo_location.geo_id', GeoStructure::select('geo_id')->where('bl_id',$request->geo_id)->get())
                ->where('asset_geo_location.asset_id', $request->asset_id)
                ->where('asset_geo_location.year',$request->year_id)
                ->get();
        }
        else{ // panchayat
            $data = AssetGeoLocation::leftJoin('geo_structure', 'asset_geo_location.geo_id', '=', 'geo_structure.geo_id')
                ->select('asset_geo_location.*','geo_structure.geo_name')
                ->where('asset_geo_location.geo_id', $request->geo_id)
                ->where('asset_geo_location.asset_id', $request->asset_id)
                ->where('asset_geo_location.year',$request->year_id)
                ->get();
        }

        // for icon
        $asset_tmp = Asset::where("asset_id", $request->asset_id)->first();
        if($asset_tmp){
            $icon  = $asset_tmp->asset_icon;
        }
        else{
            $icon = "";
        }
        
        if(count($data)>0){
            $response = "success";
        }
        else{
            $response = "no_data";
        }
        return ['review_for'=>$request->review_for,'map_data'=>$data,'response'=>$response,"icon"=>$icon];
    }

    public function export_to_Excel(Request $request)
    {
        $AssetReview = json_decode($request->datas);
        return Excel::download(new AssetReviewSectionExport($AssetReview), 'Asset Review-Sheet.xls');
    }

    public function export_pdf(Request $request){
        $AssetReviewdata = json_decode($request->datas);
        date_default_timezone_set('Asia/Kolkata');
        $AssetReviewdateTime = date('d-m-Y H:i A');
        $pdf = PDF::loadView('department/Createpdfs', compact('AssetReviewdata', 'AssetReviewdateTime'));
        return $pdf->download('Asset Review.pdf');
    }

    public function export_any(Request $request){
        ini_set('memory_limit', '-1');
        $review_datas = json_decode($request->to_export_datas); // recieved
        $year_count = json_decode($request->to_export_datas)->year_count; // received

        $export_datas = []; // use to export
        $export_datas_pdf = ""; // use to export
        $sheet_titles =[]; // use to export/ sheet title

        foreach($review_datas as $review_data){
            $block_wise_data = [["Resources Review", "Date: ".date("d-m-Y H:s A")],[]];
            $sheet_titles[] = $review_data->block_name;
            $export_datas_pdf .= "<tr><td colspan=\"".count($review_data->count_datas[0])."\"></td></tr>";
            $export_datas_pdf .= "<tr><td colspan=\"".count($review_data->count_datas[0])."\"></td></tr>";
            $export_datas_pdf .= "<tr><td style=\"text-align: center;font-size: 150%;\" colspan=\"".count($review_data->count_datas[0])."\"><b>Block: ".$review_data->block_name."</b></td></tr>";
            
            for($i=0;$i<count($review_data->count_datas);$i++){
                $data_tmp=[];
                $export_datas_pdf .= "<tr>";
               
                for($j=0;$j<count($review_data->count_datas[$i]);$j++)
                {
                    array_push($data_tmp, $review_data->count_datas[$i][$j]);
                    $export_datas_pdf .= "<td>".$review_data->count_datas[$i][$j]."</td>";
                }

                $block_wise_data[] = $data_tmp;
                $export_datas_pdf .= "</tr>";
            }

            $export_datas[] = $block_wise_data;
        }

        if($request->type=="excel"){
            \Excel::create('Resources-Review-Data', function ($excel) use ($export_datas, $sheet_titles, $i) {
                for($i=0;$i<count($sheet_titles);$i++){
                    $excel->sheet($sheet_titles[$i], function ($sheet) use ($export_datas, $i) {
                        $sheet->fromArray($export_datas[$i], null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                }
            })->download('xls');
        }
        else if($request->type=="pdf"){
            $doc_details = array(
                "title" => "Designation",
                "author" => 'IT-Scient',
                "topMarginValue" => 10,
                "mode" => 'L'
            );

            $pdfbuilder = new \PdfBuilder($doc_details);

            $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
            $content .= "<th style='border: solid 1px #000000;' colspan=\"".count(($review_datas[0]->count_datas[0]))."\" align=\"left\" ><b>Resources Review Export</b></th></tr>";
            $content .= "<tr><th style='border: solid 1px #000000;' colspan=\"".count(($review_datas[0]->count_datas[0]))."\" align=\"left\" >Date: ".date("d-m-Y H:s A")."</th></tr>";
            $content .= "<tbody>";
            $content .= $export_datas_pdf;
            $content .= "</tbody>";
            $content .= "</table>";
            $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
            $pdfbuilder->output('Resources-Review-Data.pdf');
            exit;
        }
    }

    public function send_email(Request $request){
        $review_datas = json_decode($request->to_export_datas); // recieved



        $email_from = $request->from;
        $email_to = $request->to;
        $email_cc = $request->cc;
        $send_subject = $request->subject;

        $user = array('email_from' => $email_from, 'email_to' => $email_to, 'cc' => $email_cc, 'subject' => $send_subject);

        // return view("mail.resources-review")->with(compact('review_datas'));

        Mail::send('mail.resources-review', ['review_datas' => $review_datas], function ($message) use ($user) {
            $email_to = explode(',', $user['email_to']);
            foreach ($email_to as $key => $value) {
                $message->to($email_to[$key]);
            }

            if (@$user['cc']) {
                $email_cc = explode(',', $user['cc']);
                foreach ($email_cc as $key => $value) {
                    $message->cc($email_cc[$key]);
                }
            }

            // $message->attachData($pdf->output(), "department.pdf");
            $message->subject($user['subject']);
            $message->from('dsrm.skla@gmail.com', 'DSRM Mailer');
            echo "Email sent successfully";
        });

    }
}


/*

    {block_name="", },{}

*/