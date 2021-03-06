<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetNumbers;
use App\GeoStructure;
use App\Asset;
use App\Year;
use App\User;
use DB;
use App\AssetGeoLocation;
use App\AssetBlockCount;
use App\AssetGallery;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetNumberSectionExport;
use PDF;
use Session;
use Auth;

class AssetNumbersController extends Controller
{

    public function index()
    {
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod14"]["add"]&&!$desig_permissions["mod14"]["edit"]&&!$desig_permissions["mod14"]["view"]&&!$desig_permissions["mod14"]["del"]){
            return back();
        }

        $geo_ids = [];
        if (session()->get('user_designation') == 1) // dc
        {
            $geo_ids = GeoStructure::where('level_id', 4)->pluck('geo_id'); // panchayat_ids
        } 
        else if (session()->get('user_designation') == 2) { // sdo
            $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($subdivision_id_tmp){
                $geo_ids = GeoStructure::where('sd_id', $subdivision_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_id'); // panchayat_ids
            }
        } 
        else if (session()->get('user_designation') == 3) { // bdo
            $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($block_id_tmp)
            {
                $geo_ids = GeoStructure::where('bl_id', $block_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_id'); // decide rows (panchayat)
            }
        } 
        else if (session()->get('user_designation') == 4) { //po
            $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($panchayat_id_tmp)
            {
                $geo_ids = GeoStructure::where('geo_id', $panchayat_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_id'); // decide rows (panchayat)
            }
        }

        // getting rows, last updated
        $asset_ids = Asset::where('parent_id', -1)->get()->pluck('asset_id');
        $datas = AssetNumbers::select('geo_id', 'asset_id', 'year', DB::raw('MAX(updated_at) AS max_updated'), DB::raw('MAX(asset_numbers_id) as asset_numbers_id'))
            ->whereIn('asset_id', $asset_ids)
            ->whereIn('geo_id', $geo_ids)
            ->groupBy('year', 'asset_id', 'geo_id')
            ->get();

        // assigning other values according to its id(asset_numbers_id (primary_key))
        foreach ($datas as $data) {
            if (Asset::find($data->asset_id)) {
                $data->asset_name = Asset::find($data->asset_id)->asset_name;
            }
            if (GeoStructure::find($data->geo_id)) {
                $panchayat_data_tmp = GeoStructure::find($data->geo_id);
                $data->panchayat_name = $panchayat_data_tmp->geo_name;
                $block_data_tmp = GeoStructure::find($panchayat_data_tmp->bl_id);
                $data->block_name = $block_data_tmp->geo_name;
            }
            if (Year::find($data->year)) {
                $data->year_value = Year::find($data->year)->year_value;
            }
            $tmp = AssetNumbers::select('asset_numbers_id', 'pre_value', 'current_value')->where('asset_numbers_id', $data->asset_numbers_id)->first();
            if (count($tmp) > 0) {
                $data->pre_value = $tmp->pre_value;
                $data->current_value = $tmp->current_value;
                $data->asset_numbers_id = $tmp->asset_numbers_id;
            }
        }
        
        // echo "<pre>";
        // print_r($datas);exit;
        return view('asset-numbers.index')->with('datas', $datas);
    }
    public function add(Request $request)
    {
        $geo_ids = [];
        if (session()->get('user_designation') == 1) // dc
        {
            $geo_ids = GeoStructure::where('level_id', 3)->pluck('geo_id'); // panchayat_ids
        } 
        else if (session()->get('user_designation') == 2) { // sdo
            $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->where('level_id', '2')->first();
            if($subdivision_id_tmp){
                $geo_ids = GeoStructure::where('sd_id', $subdivision_id_tmp->geo_id)->pluck('geo_id'); // panchayat_ids
            }
        } 
        else if (session()->get('user_designation') == 3) { // bdo
            $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            // return $block_id_tmp;
            if($block_id_tmp){
                $geo_ids = GeoStructure::where('officer_id', Auth::user()->id)->pluck('geo_id'); // decide rows (panchayat)
            }
        } 
        else if (session()->get('user_designation') == 4) { //po
            $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($panchayat_id_tmp){
                $geo_ids = GeoStructure::where('geo_id', $panchayat_id_tmp->bl_id)->pluck('geo_id'); // decide rows (panchayat)
            }
        }
        // return $request;
        $hidden_input_purpose = "add";
        $hidden_input_id = "NA";

        $assetgeoid = AssetNumbers::where('asset_numbers_id',$request->id)->value('geo_id');
        $fetchpanchayatid = GeoStructure::where('geo_id',$assetgeoid)->value('bl_id');

        $assets = Asset::orderBy('asset_id')->where('parent_id', -1)->get();
        if (session()->get('user_designation') == 4) { //po
            $panchayats = GeoStructure::where('level_id', '4')->where('bl_id',$fetchpanchayatid)->where('officer_id', Auth::user()->id)->orderBy('geo_name')->get();
        }
        else {
            $panchayats = GeoStructure::where('level_id', '4')->where('bl_id',$fetchpanchayatid)->orderBy('geo_name')->get();
        }
        $years = Year::orderBy('year_id')->where('status',1)->get();
        $block_datas = GeoStructure::select('geo_id', 'geo_name')->whereIn('geo_id', $geo_ids)->orderBy('geo_name', 'asc')->where('level_id', '=', '3')->get();

        $data = new AssetNumbers;

        if (isset($request->purpose) && isset($request->id)) {
            $hidden_input_purpose = $request->purpose;
            $hidden_input_id = $request->id;
            if ($data->find($request->id)) {
                $data = $data->find($request->id);
            }
        }
        if (GeoStructure::find($data->geo_id)) {
            $panchayat_data_tmp = GeoStructure::find($data->geo_id);
            $block_data_tmp = GeoStructure::find($panchayat_data_tmp->bl_id);
            $data->block_name = $block_data_tmp->geo_id;
        }
        
        return view('asset-numbers.add')->with(compact('hidden_input_purpose', 'hidden_input_id', 'data', 'assets', 'panchayats', 'years','block_datas'));
    }

    public function view(Request $request)
    {
        // receiving datas
        $asset_numbers_id = $request->asset_numbers_id;

        $asset_numbers = AssetNumbers::leftJoin('asset', 'asset_numbers.asset_id', '=', 'asset.asset_id')
            ->leftJoin('year', 'asset_numbers.year', '=', 'year.year_id')
            ->leftJoin('geo_structure', 'asset_numbers.geo_id', '=', 'geo_structure.geo_id')

            ->select('asset_numbers.*', 'asset.asset_name', 'year.year_value', 'geo_structure.geo_name')
            ->where('asset_numbers.asset_numbers_id', $asset_numbers_id)->first();
        // getting block  name
        $asset_numbers->block_name = GeoStructure::find(GeoStructure::find($asset_numbers->geo_id)->bl_id)->geo_name;

        // $asset_locations = AssetNumbers::leftJoin('asset_geo_location','asset_numbers.geo_id','=','asset_geo_location.geo_id')
        //                                 ->select('asset_numbers.*','asset_geo_location.location_name','asset_geo_location.latitude','asset_geo_location.longitude')
        //                                 ->where('asset_numbers.asset_numbers_id',$asset_numbers_id)->get();
        //**Important: asset location query must be rewritten
        $asset_locations = AssetGeoLocation::where('asset_id', $asset_numbers->asset_id)
            ->where('geo_id', $asset_numbers->geo_id)
            ->where('year', $asset_numbers->year)
            ->get();


        $images = unserialize(AssetGallery::where('geo_id', $asset_numbers->geo_id)
            ->where('asset_id', $asset_numbers->asset_id)
            ->where('year_id', $asset_numbers->year)
            ->first()->images);

        return view('asset-numbers.view')->with(compact('asset_numbers', 'asset_locations', 'images'));
    }

    public function current_value(Request $request)
    {
        $current_value_data = AssetNumbers::where('geo_id', $request->geo_id)
            ->where('asset_id', $request->asset_id)
            ->where('year', $request->year)
            ->orderBy('asset_numbers_id', 'desc')
            ->first();
        $current_value = "";
        // $pre_value = "";
        if ($current_value_data) {
            $current_value = $current_value_data->current_value;
            // $pre_value = $current_value_data->pre_value;
        }

        $tmp = Asset::where('asset_id', $request->asset_id)->first();
        if (Asset::where('asset_id', $request->asset_id)->get()) {
            if ($tmp->movable == '1') {
                $movable = "yes";
            } else {
                $movable = "no";
            }
        } else {
            $movable = "NA";
        }

        // getting previus asset_location
        $asset_location = AssetGeoLocation::select('asset_geo_loc_id', 'location_name', 'latitude', 'longitude', 'asset_id')->where('geo_id', $request->geo_id)
            ->where('asset_id', $request->asset_id)
            ->where('year', $request->year)
            ->orderBy('asset_geo_loc_id', 'desc')
            ->get();

        // for images/gallery
        $images = unserialize(AssetGallery::where('geo_id', $request->geo_id)
            ->where('asset_id', $request->asset_id)
            ->where('year_id', $request->year)
            ->first()->images);

        return ['current_value' => $current_value, 'movable' => $movable, 'asset_location' => $asset_location, 'images' => $images];
    }

    public function get_panchayat_datas(Request $request)
    {
        if (session()->get('user_designation') == 4) { //po
            $datas = GeoStructure::where('bl_id', $request->block_id)->where('officer_id', Auth::user()->id)->get();
        }
        else {
            $datas = GeoStructure::where('bl_id', $request->block_id)->get();
        }
        return $datas;
    }

    public function store(Request $request)
    {
        // echo count($request->delete_asset_geo_loc_id);
        // return $request;
        $asset_number = new AssetNumbers;

        if ($request->hidden_input_purpose == "edit") {
            $asset_number = $asset_number->find($request->hidden_input_id);
        }

        $asset_number->year = $request->year;
        $asset_number->asset_id = $request->asset_id;
        $asset_number->geo_id = $request->geo_id;
        if ($asset_number->current_value != 0) {
            $asset_number->pre_value = $request->current_value;
        } else {
            $asset_number->pre_value = $request->previous_value;
        }
        $asset_number->current_value = $request->current_value;

        $asset_number->updated_by = '1';
        $asset_number->created_by = '1';


        if (isset($request->location_name)) {
            $location_name = $request->location_name;
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            for ($i = 0; $i < count($location_name); $i++) {
                if ($request->edit_asset_geo_loc_id[$i] != null) { /*For edit geo location */
                    # code...
                    $asset_location_for_geo = AssetGeoLocation::find($request->edit_asset_geo_loc_id[$i]);
                    $asset_location_for_geo->location_name = $location_name[$i];
                    if (isset($latitude[$i]) && isset($longitude[$i])) {
                        $asset_location_for_geo->latitude = $latitude[$i];
                        $asset_location_for_geo->longitude = $longitude[$i];
                    } else {
                        $asset_location_for_geo->latitude = "";
                        $asset_location_for_geo->longitude = "";
                    }
                    $asset_location_for_geo->save();
                } else {
                    # code...
                    $asset_geo_location = new AssetGeoLocation;
                    $asset_geo_location->year = $request->year;
                    $asset_geo_location->asset_id = $request->asset_id;
                    $asset_geo_location->geo_id = $request->geo_id; // panchayat
    
                    $asset_geo_location->location_name = $location_name[$i];
                    if (isset($latitude[$i]) && isset($longitude[$i])) {
                        $asset_geo_location->latitude = $latitude[$i];
                        $asset_geo_location->longitude = $longitude[$i];
                    } else {
                        $asset_geo_location->latitude = "";
                        $asset_geo_location->longitude = "";
                    }
    
                    $asset_geo_location->created_by = '1';
                    $asset_geo_location->updated_by = '1';
    
                    $asset_geo_location->save();
                }
                
            }
        } 
         if (isset($request->delete_asset_geo_loc_id)) {
            $asset_geo_loc_id = $request->delete_asset_geo_loc_id;
            for ($i = 0; $i < count($asset_geo_loc_id); $i++) {
                if (AssetGeoLocation::find($asset_geo_loc_id[$i])) {
                    AssetGeoLocation::where('asset_geo_loc_id', $asset_geo_loc_id[$i])->delete();
                }
            }
        }


        // delete previous image
        $asset_gallery_check = AssetGallery::select("asset_gallery_id", "images")->where('asset_id', $request->asset_id)->where('geo_id', $request->geo_id)->where('year_id', $request->year)->first();
        if ($request->hidden_input_purpose == "edit") {
            if ($request->images_delete) {
                $to_delete_image_arr = explode(",", $request->images_delete);
                for ($i = 0; $i < count($to_delete_image_arr); $i++) {
                    if (file_exists($to_delete_image_arr[$i])) {
                        unlink($to_delete_image_arr[$i]);
                    }
                }

                if ($asset_gallery_check) {
                    $asset_gallery_update = AssetGallery::find($asset_gallery_check->asset_gallery_id);
                    $asset_gallery_update->images = serialize(array_values(array_diff(unserialize($asset_gallery_update->images), $to_delete_image_arr))); //array_diff remove matching elements from 1, array_values changes index starting from 0
                    $asset_gallery_update->save();
                }
            }
        }
        // for asset_gallery images
        if ($request->hasFile('images')) {
            $upload_directory = "public/uploaded_documents/assets-gallery/";
            if ($asset_gallery_check) {
                $asset_gallery_update = AssetGallery::find($asset_gallery_check->asset_gallery_id);
                $previous_images_array = unserialize($asset_gallery_update->images);

                foreach ($request->file('images') as $file) {
                    // $file = $request->file('images');
                    // $file => is each files $images
                    $images_tmp_name = "assets-gallery-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move($upload_directory, $images_tmp_name);   // move the file to desired folder
                    array_push($previous_images_array, $upload_directory . $images_tmp_name);    // appending location of image in previous image array for further insertion into database
                }

                $asset_gallery_update->images = serialize($previous_images_array);    // assign the location of folder to the model
                $asset_gallery_update->save();
            } else { // save in new row
                $asset_gallery_save = new AssetGallery;
                $asset_gallery_save->asset_id = $request->asset_id;
                $asset_gallery_save->geo_id = $request->geo_id;
                $asset_gallery_save->year_id = $request->year;
                $asset_gallery_save->org_id = "1";
                $asset_gallery_save->created_by = "1";
                $asset_gallery_save->updated_by = "1";

                $previous_images_array = [];
                foreach ($request->file('images') as $file) {
                    // $file = $request->file('images');
                    // $file => is each files $images
                    $images_tmp_name = "assets-gallery-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move($upload_directory, $images_tmp_name);   // move the file to desired folder
                    array_push($previous_images_array, $upload_directory . $images_tmp_name);    // appending location of image in previous image array for further insertion into database
                }

                $asset_gallery_save->images = serialize($previous_images_array);    // assign the location of folder to the model
                $asset_gallery_save->save();
            }
        }



        if ($asset_number->save()) {

            if ($request->hidden_input_purpose == "edit" && count($request->delete_asset_geo_loc_id) != 0) {
                $edit_asset_value = AssetNumbers::find($asset_number->asset_numbers_id);
                $edit_asset_value->current_value = $request->previous_value - count($request->delete_asset_geo_loc_id);//2-1=1//2=1
                $edit_asset_value->pre_value = $edit_asset_value->current_value;
                $edit_asset_value->save();
                if (($request->previous_value - count($request->delete_asset_geo_loc_id)) == 0) {
                    # code...
                    AssetNumbers::where('asset_numbers_id', $edit_asset_value->asset_numbers_id)->delete();
                }
                if(isset($request->delete_asset_geo_loc_id))
                {
                    for ($i = 0; $i < count($request->delete_asset_geo_loc_id); $i++) {
                        AssetNumbers::where('asset_geo_loc_id', $request->delete_asset_geo_loc_id[$i])->delete();
                        
                    }
                }
            }
            if ($request->hidden_input_purpose == "add") {
                $checkStatus = Asset::where('asset_id', $request->asset_id)->value('movable');
            }
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Resource Number details have been successfully submitted !');
            if ($request->hidden_input_purpose == "add" && $checkStatus == 0) {
                session()->put('message', $asset_number->asset_numbers_id);
            }
        } else {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'Something went wrong while adding new details !');
        }


        // block count
        $block_data = GeoStructure::select('bl_id', 'geo_name')->where('geo_id', $request->geo_id)->first();
        $update_block_count = AssetBlockCount::where('year', $request->year)
            ->where('asset_id', $request->asset_id)
            ->where('geo_id', $block_data->bl_id)
            ->first();
        $no_update_block = count($update_block_count);

        if ($no_update_block == 0) {
            $update_block_count_new = new AssetBlockCount;
            $update_block_count_new->year = $request->year;
            $update_block_count_new->asset_id = $request->asset_id;
            $update_block_count_new->geo_id = (GeoStructure::select('bl_id')->where('geo_id', $request->geo_id)->first())->bl_id;
            $update_block_count_new->count = $request->current_value;
            $update_block_count_new->created_by = '1';
            $update_block_count_new->updated_by = '1';
            $update_block_count_new->org_id = '1';

            $update_block_count_new->save();
        } else {
            $to_update_count = 0;
            if ($request->current_value > $request->previous_value) {
                $to_update_count = $update_block_count->count + ($request->current_value - $request->previous_value);
            } else {
                $to_update_count = $update_block_count->count - ($request->previous_value - $request->current_value);
            }
            $update_block_count->count = $to_update_count;
            $update_block_count->save();
        }



        return redirect('asset-numbers');
    }




    public function delete(Request $request)
    {

        // $block_delete =AssetBlockCount::where('year',$request->year)
        //                      ->where('asset_id',$request->asset_id)
        //                      ->where(GeoStructure::select('bl_id')->where('geo_id',$request->geo_id)->first())->bl_id;
        //                      ->first();
        // $asset_numbers_delete = AssetNumbers::where('year',$request->year)
        //                          ->where('asset_id',$request->asset_id)
        //                          ->where('geo_id',$request->geo_id)
        //                          ->first();
        // $asset_geo_location_delete = AssetGeoLocation::where('year',$request->year)
        //                              ->where('asset_id',$request->asset_id)
        //                              ->where('geo_id',$request->geo_id)
        //                              ->first();
        // return $block_delete;

        // if(AssetNumbers::find($request->asset_numbers_id)) && (AssetGeoLocation::find($request->asset_geo_location_id)) && (AssetBlockCount::find($request->asset_block_count)){
        //     AssetNumbers::where('asset_numbers_id',$request->asset_numbers_id)->delete();
        //     AssetGeoLocation::where(('year',$request->year),('asset_id',$request->asset_id))::select('bl_id')->where('geo_id',$request->geo_id)->first())->bl_id->delete();

        //     session()->put('alert-class','alert-success');
        //     session()->put('alert-content','Deleted successfully');
        // }

        // return redirect('asset-numbers');
    }

    public function exportExcelFunctiuonforasset_Numbers()
    {
        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect(); //important as the existing connection if any would be in strict mode


        $data = array(1 => array("Resource Numbers Sheet"));
        $data[] = array('Sl. No.', 'Year', 'Resource', 'Block', 'Panchyat', 'Current Value', 'Date');

        $items = DB::table('asset_numbers')
            ->leftJoin('geo_structure', 'asset_numbers.geo_id', '=', 'geo_structure.geo_id')
            ->leftJoin('asset', 'asset_numbers.asset_id', '=', 'asset.asset_id')
            ->leftJoin('year', 'asset_numbers.year', '=', 'year.year_id')
            ->select(
                'asset_numbers.asset_numbers_id as slNo',
                'year.year_value',
                'asset.asset_name',
                'geo_structure.bl_id as Block',
                'geo_structure.geo_name',
                'asset_numbers.current_value',
                'asset_numbers.created_at as CreatedDate'
            )
            ->groupBy('asset_numbers.year', 'asset_numbers.asset_id', 'asset_numbers.geo_id')->get();

        //now changing back the strict ON
        config()->set('database.connections.mysql.strict', true);
        \DB::reconnect();


        foreach ($items as $key => $value) {
            $value->CreatedDate = date('d/m/Y', strtotime($value->CreatedDate));
            $block_data_tmp = GeoStructure::find($value->Block);
            $value->Block = $block_data_tmp->geo_name;

            $data[] = array(
                $key + 1,
                $value->year_value,
                $value->asset_name,
                $value->Block,
                $value->geo_name,
                $value->current_value,
                $value->CreatedDate
            );
        }
        \Excel::create('Resource_Numbers', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Resource Numbers Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
        return Excel::download(new AssetNumberSectionExport, 'Resource Number-Sheet.xls');
    }

    public function exportpdfFunctiuonforasset_Numbers()
    {
        $AssetNumberdata = AssetNumbers::select('geo_id', 'asset_id', 'year', 'created_at as date', DB::raw('MAX(updated_at) AS max_updated'), DB::raw('MAX(asset_numbers_id) as asset_numbers_id'))
            ->groupBy('year', 'asset_id', 'geo_id', 'created_at')
            // ->format("Y-m-d")
            ->get();

        foreach ($AssetNumberdata as $key => $value) {
            if (Asset::find($value->asset_id)) {
                $value->asset_name = Asset::find($value->asset_id)->asset_name;
            }
            if (GeoStructure::find($value->geo_id)) {
                $panchayat_data_tmp = GeoStructure::find($value->geo_id);
                $value->panchayat_name = $panchayat_data_tmp->geo_name;
                $block_data_tmp = GeoStructure::find($panchayat_data_tmp->bl_id);
                $value->block_name = $block_data_tmp->geo_name;
            }
            if (Year::find($value->year)) {
                $value->year_value = Year::find($value->year)->year_value;
            }
            $tmp = AssetNumbers::select('asset_numbers_id', 'pre_value', 'current_value')->where('asset_numbers_id', $value->asset_numbers_id)->first();
            if (count($tmp) > 0) {
                $value->pre_value = $tmp->pre_value;
                $value->current_value = $tmp->current_value;
                $value->asset_numbers_id = $tmp->asset_numbers_id;
            }
            $value->date = date("d/m/Y", strtotime($value->date));
        }

        $doc_details = array(
            "title" => "Resource Number Data",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'L'
        );

        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"7\" align=\"left\" ><b>Resource Number</b></th></tr>";
        /* ========================================================================= */
        /*                Total width of the pdf table is 1017px                     */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl. No.</b></th>";
        $content .= "<th style=\"width: 250px;\" align=\"center\"><b>Year</b></th>";
        $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Resource</b></th>";
        $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Block</b></th>";
        $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Panchyat</b></th>";
        $content .= "<th style=\"width: 140px;\" align=\"center\"><b>Current Value</b></th>";
        $content .= "<th style=\"width: 97px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";

        $content .= "<tbody>";
        foreach ($AssetNumberdata as $key => $row) {
            $index = $key + 1;
            $content .= "<tr>";
            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"width: 250px;\" align=\"left\">" . $row->year_value . "</td>";
            $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->asset_name . "</td>";
            $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->block_name . "</td>";
            $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->panchayat_name . "</td>";
            $content .= "<td style=\"width: 140px;\" align=\"right\">" . $row->current_value . "</td>";
            $content .= "<td style=\"width: 97px;\" align=\"right\">" . $row->date . "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        // print_r($content);exit;
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('ResourceNumber.pdf');
        exit;
    }

    public function list_of_childs($child_id, $geo_child_id, $year_child_id, $hidden_input_id, $geo_location_id)
    {

        $childdatas = Asset::leftJoin('asset_numbers', 'asset.asset_id', '=', 'asset_numbers.asset_id')
            ->where('asset.parent_id', $child_id)
            ->where('asset_numbers.asset_geo_loc_id', $geo_location_id)
            ->select(
                'asset.asset_id',
                'asset.asset_name',
                'asset.movable',
                'asset.parent_id',
                'asset.dept_id',
                'asset.org_id',
                'asset_numbers.asset_numbers_id',
                'asset_numbers.asset_id as assetId',
                'asset_numbers.geo_id',
                'asset_numbers.pre_value',
                'asset_numbers.current_value',
                'asset_numbers.asset_geo_loc_id',
                'asset_numbers.year'
            )->get();

        $childdatasValue = Asset::where('parent_id', $child_id)->get();

        // // echo $geo_location_id;
        // echo "<pre>";
        // // print_r($childdatasValue);
        // print_r($childdatas);
        // exit;
        return view('asset-numbers.child_resources_number')->with(compact('childdatas', 'geo_child_id', 'year_child_id', 'hidden_input_id', 'geo_location_id', 'childdatasValue'));
    }

    public function saveChilddata(Request $request)
    {
        // return $request;
        foreach ($request->child_asset_id as $key => $value) {
            # code...
            $check = AssetNumbers::find($request->asset_numbers_child_id[$key])->asset_geo_loc_id;

            // if(null != null $$ 28 == null)
            // if(23 != null $$ 28 == 28)

            if ($request->asset_numbers_child_id[$key] != null && $request->geo_location_id == $check) {
                $AssetNumbers = AssetNumbers::find($request->asset_numbers_child_id[$key]);
                // $AssetNumbers->pre_value = $request->current_value_child[$key];
                // $AssetNumbers->current_value = ( $request->current_value_child[$key] != null) ? $request->current_value_child[$key] : 0;

                if ($request->current_value_child[$key] != 0) {
                    $AssetNumbers->pre_value = $request->current_value_child[$key];
                } else {
                    $AssetNumbers->pre_value = $request->previous_value_child[$key];
                }
                $AssetNumbers->current_value = $request->current_value_child[$key];
                $AssetNumbers->save();
            } else {
                $asset_number = new AssetNumbers;
                $asset_number->year = $request->year_child_id;
                $asset_number->asset_id = $value;
                $asset_number->asset_geo_loc_id = $request->geo_location_id;
                $asset_number->geo_id = $request->geo_child_id;
                // $asset_number->pre_value = $request->previous_value_child[$key];
                // $asset_number->current_value = ( $request->current_value_child[$key] != null) ? $request->current_value_child[$key] : 0;
                if ($request->current_value_child[$key] != 0) {
                    $asset_number->pre_value = $request->current_value_child[$key];
                } else {
                    $asset_number->pre_value = $request->previous_value_child[$key];
                }
                $asset_number->current_value = $request->current_value_child[$key];

                $asset_number->created_by = '1';
                $asset_number->updated_by = '1';
                $asset_number->save();
            }
        }
        session()->put('alert-class', 'alert-success');
        session()->put('alert-content', 'Sub Resources details have been successfully submitted !');
        return redirect('asset-numbers/add?purpose=edit&id=' . $request->main_asset_id);
    }

    public function list_of_imagedata($loc_id, $asset_id, $year_id, $geo_id, $hidden_input_id)
    {
        # code...
        $toReturn = array();
        $toReturn['asset_gallery'] = AssetGallery::where('asset_id', $asset_id)->where('asset_geo_loc_id', $loc_id)->where('geo_id', $geo_id)->where('year_id', $year_id)->first();
        $toReturn['asset_location_images'] = unserialize(AssetGallery::where('asset_id', $asset_id)->where('asset_geo_loc_id', $loc_id)->where('geo_id', $geo_id)->where('year_id', $year_id)->value('images'));
        // echo "<pre>";
        $toReturn['loc_id'] = $loc_id;
        $toReturn['asset_id'] = $asset_id;
        $toReturn['year_id'] = $year_id;
        $toReturn['geo_id'] = $geo_id;
        $toReturn['hidden_input_id'] = $hidden_input_id;
        return $toReturn;
        // echo ($asset_gallery);
        // exit;
    }

    public function saveImagesforLoacation(Request $request)
    {
        # code...
        // return $request;
        $previous_images_array = array();
        $upload_directory = "public/uploaded_documents/assets-gallery/";
        if ($request->asset_gallery_id != null) {
            # code...
            $asset_gallery_edit = AssetGallery::find($request->asset_gallery_id);
            $previous_images_array = unserialize($asset_gallery_edit->images);
            if ($request->hasFile('galleryFile')) {
                foreach ($request->file('galleryFile') as $file) {
                    // $file = $request->file('images');
                    // $file => is each files $images
                    $images_tmp_name = "assets-gallery-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move($upload_directory, $images_tmp_name);   // move the file to desired folder
                    array_push($previous_images_array, $upload_directory . $images_tmp_name);    // appending location of image in previous image array for further insertion into database
                }
            }

            $asset_gallery_edit->images = serialize($previous_images_array);
            $asset_gallery_edit->save();

            $asset_gallery_check = AssetGallery::select("asset_gallery_id", "images")->where('asset_gallery_id', $request->asset_gallery_id)->first();
            if ($asset_gallery_check) {
                if ($request->gallery_images_delete) {
                    $to_delete_image_arr = explode(",", $request->gallery_images_delete);
                    for ($i = 0; $i < count($to_delete_image_arr); $i++) {
                        if (file_exists($to_delete_image_arr[$i])) {
                            unlink($to_delete_image_arr[$i]);
                        }
                    }

                    if ($asset_gallery_check) {
                        $asset_gallery_update = AssetGallery::find($asset_gallery_check->asset_gallery_id);
                        $asset_gallery_update->images = serialize(array_values(array_diff(unserialize($asset_gallery_update->images), $to_delete_image_arr))); //array_diff remove matching elements from 1, array_values changes index starting from 0
                        $asset_gallery_update->save();
                    }
                }
            }
        } else {
            # code...
            $asset_gallery = new AssetGallery;
            $asset_gallery->asset_id = $request->image_asset_id;
            $asset_gallery->asset_geo_loc_id = $request->geo_location_image_id;
            $asset_gallery->geo_id = $request->geo_image_id;
            $asset_gallery->year_id = $request->year_image_id;
            $asset_gallery->org_id = 1;
            if ($request->hasFile('galleryFile')) {

                foreach ($request->file('galleryFile') as $file) {
                    $images_tmp_name = "assets-gallery-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move($upload_directory, $images_tmp_name);   // move the file to desired folder
                    array_push($previous_images_array, $upload_directory . $images_tmp_name);    // appending location of image in previous image array for further insertion into database
                }
                $asset_gallery->images = serialize($previous_images_array);    // assign the location of folder to the model
            }

            $asset_gallery->created_by = '1';
            $asset_gallery->updated_by = '1';
            $asset_gallery->save();
        }

        session()->put('alert-class', 'alert-success');
        session()->put('alert-content', 'Location Gallery have been successfully submitted !');
        return redirect('asset-numbers/add?purpose=edit&id=' . $request->asset_number_image_id);
    }

    public function downloadFormat()
    {
        # code...
        $data[] = array('SNo.', 'Year', 'Resource Name', 'Panchayat Name', 'Current Value');

        \Excel::create('Resource Number-Format', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Resource Number-Format');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Resource Number-Format', function ($sheet) use ($data) {
                // $sheet->freezePane('A3');
                // $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }
    public function downloadFormatwithLocation()
    {
        # code...
        $data[] = array('SNo.', 'Year', 'Resource Name','Main Resource SNo','Count', 'Panchayat Name', 'Location/Landmark', 'Latitude', 'Longitude');

        \Excel::create('Resource_Number_Location-Format', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Resource Number-Format');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Resource Number-Format', function ($sheet) use ($data) {
                // $sheet->freezePane('A3');
                // $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }

    public function changeViewforimport()
    {
        $desig_permissions = session()->get('desig_permission');
        if(!$desig_permissions["mod24"]["add"]&&!$desig_permissions["mod24"]["edit"]&&!$desig_permissions["mod24"]["view"]&&!$desig_permissions["mod24"]["del"]){
            return back();
        }
        # code...
        return view('asset-numbers.importExcel');
    }

    public function saveimporttoExcel(Request $request)
    {
        $geo_names = array();
        if (session()->get('user_designation') == 1) // dc
        {
            $geo_names = GeoStructure::where('level_id', 4)->pluck('geo_name'); // panchayat_ids
        } 
        else if (session()->get('user_designation') == 2) { // sdo
            $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($subdivision_id_tmp){
                $geo_names = GeoStructure::where('sd_id', $subdivision_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_name'); // panchayat_ids
            }
        } 
        else if (session()->get('user_designation') == 3) { // bdo
            $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($block_id_tmp)
            {
                $geo_names = GeoStructure::where('bl_id', $block_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_name'); // decide rows (panchayat)
            }
        } 
        else if (session()->get('user_designation') == 4) { //po
            $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if($panchayat_id_tmp)
            {
                $geo_names = GeoStructure::where('geo_id', $panchayat_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_name'); // decide rows (panchayat)
            }
        }
        
        //  echo "<pre>";
        //     // // // echo $excelArray[1]['main_resource_sno'];
        //    print_r($geo_names);
        //             // print_r($tableHeadingsAndAtributes);
        //             exit;

        $geo_names = (array)$geo_names;
        $geo_names_array=array();
        foreach($geo_names as $key_geo=> $value_geo)
        {
            $geo_names_array=$value_geo;
        }
            
        if ($_FILES['excel_for_asset_number']['tmp_name']) {
            $readExcel = \Excel::load($_FILES['excel_for_asset_number']['tmp_name'], function ($reader) { 

            })->get()->toArray();
            $readExcelHeader = \Excel::load($_FILES['excel_for_asset_number']['tmp_name'])->get();
            if(count($readExcelHeader) != 0){
                $excelSheetHeadings = $readExcelHeader->first()->keys()->toArray(); /* this is for excel sheet heading */
            }
            // $childdatasValue = Asset::where('parent_id', 6)->get();
            // echo "<pre>";
            // // // echo $readExcel[1]['main_resource_sno'];
           // print_r($excelSheetHeadings1);
                    // print_r($tableHeadingsAndAtributes);
                    // exit;
                $excelArray=array();
            foreach ($readExcel as $key_a => $row_a) {
                if($row_a['sno.']!="")
                {
                $si_no_arary[]= $row_a['sno.'];
                $excelArray[]=$row_a;
                }
            }
            // return $excelArray;
            $tableHeadingsAndAtributes = array('sno.', 'year', 'resource_name', 'panchayat_name', 'current_value',0);
            $tableHeadingsAndAtributes_location = array('sno.', 'year', 'resource_name','main_resource_sno','count', 'panchayat_name', 'locationlandmark', 'latitude', 'longitude');
            // return count($si_no_arary);
            try {
            
                if(count($excelArray) != 0)
                {
                    $excelSheetHeadings1 = $excelSheetHeadings;
                    sort($tableHeadingsAndAtributes);
                    sort($tableHeadingsAndAtributes_location);
                    sort($excelSheetHeadings1);
                    
                    if ($tableHeadingsAndAtributes == $excelSheetHeadings1 || $tableHeadingsAndAtributes_location == $excelSheetHeadings1) { /* Check for missmatch headings*/
                        if(count($excelArray)<=250)
                        { 
                            $getUserName = User::where('id',Session::get('user_id'))->first();
                            date_default_timezone_set('Asia/Kolkata');
                            $filename = "ResourceNumber-ErrorLog-".Session::get('user_id').".txt"; /* error file name */
                            $myfile = fopen($filename, "w"); /* open error file name by using fopen function */
                            $noOfSuccess = 0;
                            $noOfFails = 0;
                            $ErrorTxt = "";
                            // echo "<pre>";    
                            // print_r($geo_names);
                            foreach ($excelArray as $key => $row) { /* Insert Data By using for each one by one */

                                $panchayat_name = trim(ucwords($row['panchayat_name'])," ");

                                $asset_name = trim(ucwords($row['resource_name'])," ");

                                $year_value = ucwords(preg_replace('/[^0-9-]/', '', $row['year'])); //replace ASCII form year

                                $fetch_panchayat_id = GeoStructure::where('geo_name', $panchayat_name)->where('level_id','4')->value('geo_id'); /* for Panchayat ID */
                                $fetch_asset_id = Asset::where('asset_name', $asset_name)->value('asset_id'); /* for asset ID */ 
                                $fetch_year_id = Year::where('year_value', $year_value)->value('year_id'); /* for Year ID */
                                $fetch_asset_number_edit = AssetNumbers::where('asset_id', $fetch_asset_id)->where('geo_id', $fetch_panchayat_id)->where('year',$year_value)->first();
                                $fetch_asset_loc_edit = AssetGeoLocation::where('asset_id', $fetch_asset_id)->where('geo_id', $fetch_panchayat_id)->where('location_name', $row['locationlandmark'])->where('year', $fetch_year_id)->first();
                                
                                if ($row['sno.'] != null && $fetch_panchayat_id != null && $fetch_year_id != null && $fetch_asset_id != null && in_array($panchayat_name,$geo_names_array)) {
                                    
                                    /* Condition for Add And edit Location Latitude And longitude */
                                    if($excelSheetHeadings[6] == "locationlandmark" || $excelSheetHeadings[7] == "latitude" || $excelSheetHeadings[8] == "longitude"){
                                        if ($row['main_resource_sno'] == null && $row['count'] == null && $row['locationlandmark'] != null) {
                                            // echo "1";
                                            // echo "<br>";
                                            $noOfSuccess++;
                                            if($fetch_asset_number_edit->asset_numbers_id != null)
                                            {
                                                $AssetNumbers = AssetNumbers::find($fetch_asset_number_edit->asset_numbers_id);
                                                $AssetNumbers->pre_value = $fetch_asset_number_edit->current_value;
                                                if ($fetch_asset_loc_edit == null) {
                                                    $AssetNumbers->current_value = $fetch_asset_number_edit->current_value + 1;
                                                }
                                                $AssetNumbers->updated_by = Session::get('user_id');
                                                $AssetNumbers->save();
                                            }
                                            else {
                                                $asset_number = new AssetNumbers;
                                                $asset_number->year = $fetch_year_id;
                                                $asset_number->asset_id = $fetch_asset_id;
                                                $asset_number->geo_id = $fetch_panchayat_id;
                                                $asset_number->pre_value = 0;
                                                $asset_number->current_value = 1;
                                
                                                $asset_number->created_by = Session::get('user_id');
                                                
                                                $asset_number->save();
                                            }
                                            if ($fetch_asset_loc_edit->asset_geo_loc_id != null) {
                                                $ASSET_geo_LOC_location = AssetGeoLocation::find($fetch_asset_loc_edit->asset_geo_loc_id);
                                                if (isset($row['locationlandmark'])) {
                                                    $ASSET_geo_LOC_location->location_name = $row['locationlandmark'];
                                                } else {
                                                    $ASSET_geo_LOC_location->location_name = "";
                                                }
        
                                                if (isset($row['latitude']) && isset($row['longitude'])) {
                                                    $ASSET_geo_LOC_location->latitude = $row['latitude'];
                                                    $ASSET_geo_LOC_location->longitude = $row['longitude'];
                                                } else {
                                                    $ASSET_geo_LOC_location->latitude = "";
                                                    $ASSET_geo_LOC_location->longitude = "";
                                                }
                                                $ASSET_geo_LOC_location->updated_by = Session::get('user_id');
                                                $ASSET_geo_LOC_location->save();
                                            } else {
                                                $asset_geo_location = new AssetGeoLocation;
                                                $asset_geo_location->year = $fetch_year_id;
                                                $asset_geo_location->asset_id = $fetch_asset_id;
                                                $asset_geo_location->geo_id = $fetch_panchayat_id; // panchayat
                                                if (isset($row['locationlandmark'])) {
                                                    $asset_geo_location->location_name = $row['locationlandmark'];
                                                } else {
                                                    $asset_geo_location->location_name = "";
                                                }
        
                                                if (isset($row['latitude']) && isset($row['longitude'])) {
                                                    $asset_geo_location->latitude = $row['latitude'];
                                                    $asset_geo_location->longitude = $row['longitude'];
                                                } else {
                                                    $asset_geo_location->latitude = "";
                                                    $asset_geo_location->longitude = "";
                                                }
                                
                                                $asset_geo_location->created_by = Session::get('user_id');
                                                $asset_geo_location->updated_by = '1';
                                                $asset_geo_location->save();
                                            }
                                            
                                        } elseif ($row['locationlandmark'] == null && $row['main_resource_sno'] == null && $row['count'] == null) {
                                            $noOfFails++;
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Please Fill Landmark/Location \n";
                                        }

                                        if (($row['main_resource_sno'] != null || $row['count'] != null) && (gettype($row['count']) ===  "double") && (in_array($row['main_resource_sno'],$si_no_arary))) {
                                            // echo "2";
                                            // echo "<br>";
                                            $noOfSuccess++;
                                            $aseetNo = $row['main_resource_sno'];
                                            
                                            $fetch_panchayat_id1 = GeoStructure::where('geo_name', $excelArray[$aseetNo - 1]['panchayat_name'])->where('level_id', '4')->value('geo_id'); /* for Panchayat ID */
                                            $fetch_asset_id1 = Asset::where('asset_name', $excelArray[$aseetNo - 1]['resource_name'])->value('asset_id'); /* for asset ID */
                                            $fetch_year_id1 = Year::where('year_value', $excelArray[$aseetNo - 1]['year'])->value('year_id'); /* for Year ID */
                                            $fetch_asset_loc_id = AssetGeoLocation::where('year', $fetch_year_id1)
                                                    ->where('asset_id', $fetch_asset_id1)
                                                    ->where('geo_id', $fetch_panchayat_id1)
                                                    ->where('location_name', $excelArray[$aseetNo - 1]['locationlandmark'])
                                                    ->first();
                                            $childdatasValue = Asset::where('parent_id', $fetch_asset_id1)->get();
                                            $fetch_asset_number_edit_child = AssetNumbers::where('asset_id', $fetch_asset_id)->where('asset_geo_loc_id',$fetch_asset_loc_id->asset_geo_loc_id)->where('geo_id', $fetch_panchayat_id)->first();
                                            foreach ($childdatasValue as $kee => $value1) {
                                                
                                                if($fetch_asset_number_edit_child->asset_numbers_id != null)
                                                {
                                                    $AssetNumbers = AssetNumbers::find($fetch_asset_number_edit_child->asset_numbers_id);
                                                    // $AssetNumbers->pre_value = $fetch_asset_number_edit_child->current_value;
                                                    // if ($fetch_asset_id == $childdatasValue[$kee]->asset_id) {
                                                    //     $AssetNumbers->current_value = $row['count'];
                                                    // } else {
                                                    //     $AssetNumbers->current_value = 0;
                                                    // }
                                                    // $AssetNumbers->updated_by = Session::get('user_id');
                                                    $AssetNumbers->save();
                                                }
                                                else {
                                                    $asset_number = new AssetNumbers;
                                                    $asset_number->year = $fetch_year_id;
                                                    $asset_number->asset_id = $childdatasValue[$kee]->asset_id;
                                                    $asset_number->geo_id = $fetch_panchayat_id;
                                                    if ($fetch_asset_loc_id != null) {
                                                        $asset_number->asset_geo_loc_id = $fetch_asset_loc_id->asset_geo_loc_id;
                                                    } 
                                                    
                                                    $asset_number->pre_value = 0;
                                                    if ($fetch_asset_id == $childdatasValue[$kee]->asset_id) {
                                                        $asset_number->current_value = $row['count'];
                                                    } else {
                                                        $asset_number->current_value = 0;
                                                    }
                                                    
                                    
                                                    $asset_number->created_by = Session::get('user_id');
                                                    
                                                    $asset_number->save();
                                                }
                                            }
                                            if($fetch_asset_number_edit_child->asset_numbers_id != null)
                                            {
                                                $AssetNumbers1 = AssetNumbers::find($fetch_asset_number_edit_child->asset_numbers_id);
                                                $AssetNumbers1->pre_value = $fetch_asset_number_edit_child->current_value;
                                                $AssetNumbers1->current_value = $row['count'];
                                                $AssetNumbers1->updated_by = Session::get('user_id');
                                                $AssetNumbers1->save();
                                            }
                                        } elseif ((gettype($row['count']) !==  "double") && ($row['main_resource_sno'] != null || $row['count'] != null)) {
                                            $noOfFails++;
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Count is Not Numeric \n";
                                        }
                                        elseif ((in_array($row['main_resource_sno'],$si_no_arary) == false) && ($row['main_resource_sno'] != null || $row['count'] != null)) {
                                            $noOfFails++;
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . "Main Resource Serial Number Not Found \n";
                                        }
                                    }
                                    else {
                                        $noOfSuccess++;
                                        if($fetch_asset_number_edit->asset_numbers_id != null)
                                        {
                                            $AssetNumbers = AssetNumbers::find($fetch_asset_number_edit->asset_numbers_id);
                                            $AssetNumbers->pre_value = $fetch_asset_number_edit->current_value;
                                            $AssetNumbers->current_value = $row['current_value'];
                                            $AssetNumbers->updated_by = Session::get('user_id');
                                            $AssetNumbers->save();
                                        }
                                        else {
                                            $asset_number = new AssetNumbers;
                                            $asset_number->year = $fetch_year_id;
                                            $asset_number->asset_id = $fetch_asset_id;
                                            $asset_number->geo_id = $fetch_panchayat_id;
                                            $asset_number->pre_value = 0;
                                            $asset_number->current_value = $row['current_value'];
                            
                                            $asset_number->created_by = Session::get('user_id');
                                            
                                            $asset_number->save();
                                        }
                                    }
                                }else {  
                                    /* Else find id and error write on the notepad */
                                    $noOfFails++;
                                    if ($row['sno.'] != null) {
                                        if ($fetch_asset_id == null && $fetch_panchayat_id != null && $fetch_year_id != null) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Resources Not Found \n";
                                        } 
                                        if ($fetch_panchayat_id == null && $fetch_asset_id != null && $fetch_year_id != null) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Panchayat Not Found \n";
                                        } 
                                        if ($fetch_year_id == null && $fetch_panchayat_id != null && $fetch_asset_id != null) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Year Not Found \n";
                                        } 
                                        if ($fetch_year_id == null && $fetch_panchayat_id == null && $fetch_asset_id != null) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Year And Panchayat Not Found \n";
                                        } 
                                        if ($fetch_year_id != null && $fetch_panchayat_id == null && $fetch_asset_id == null) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Resources And Panchayat Not Found \n";
                                        } 
                                        if ($fetch_year_id == null && $fetch_panchayat_id != null && $fetch_asset_id == null) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Resources And Year Not Found \n";
                                        } 
                                        if ($fetch_year_id == null && $fetch_panchayat_id == null && $fetch_asset_id == null) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Year Resources And Panchayat Not Found \n";
                                        }
                                        if (in_array($panchayat_name,$geo_names_array) == false) {
                                            $ErrorTxt .= " On SNo. " . $row['sno.'] . " Wrong panchayat Name \n";
                                        }
                                    } 
                                    // echo $ErrorTxt;
                                }
                            }

                            $txt = "District Resource and Scheme Management\n";
                            $txt .= "----------------------------------------------------------------------------------------------------------------------------------\n";
                            $txt .= "DATE: ". date('d/m/Y h:i A')."\n";
                            $txt .= "TOTAL RECORD COUNT: ". count($excelArray)."\n";
                            $txt .= "TOTAL SUCCESS COUNT: ".$noOfSuccess."\n";
                            $txt .= "TOTAL FAIL COUNT: ".$noOfFails."\n";
                            $txt .= "USER NAME: ". $getUserName->first_name." ". $getUserName->middle_name." ". $getUserName->last_name." \n";
                            $txt .= "----------------------------------------------------------------------------------------------------------------------------------\n";
                            if ($noOfFails == 0) {
                                $txt .= "No Error Found";
                            } else {
                                $txt .= $ErrorTxt;
                            }
                            
                            fwrite($myfile, $txt);
                            // exit;

                            fclose($myfile); //close file

                            if (file_get_contents($filename) == null) //if error file does not exit ant data then popup message success
                            {
                                session()->put('alert-class', 'alert-success');
                                session()->put('alert-content', 'Resources Numbers details has been saved');
                                return back();
                            } else { //Else download the error notepad file
                                header("Cache-Control: public");
                                header("Content-Description: File Transfer");
                                header("Content-Length: " . filesize("$filename") . ";");
                                header("Content-Disposition: attachment; filename=$filename");
                                header("Content-Type: application/octet-stream; ");
                                header("Content-Transfer-Encoding: binary");
                                // readfile($filename);
                                // exit();
                                $this->saveFile($filename,file_get_contents($filename));
                                session()->put('alert-class', 'alert-success');
                                session()->put('alert-content', 'Resources Numbers details has been saved');
                                session()->put('to-download', 'yes');
                                session()->put('currentdate', date('d/m/Y h:i A'));
                                session()->put('totalCount', count($excelArray));
                                session()->put('totalsuccess', $noOfSuccess);
                                session()->put('totalfail', $noOfFails);
                                return back();

                            }
                            
                        }
                        else
                        {
                            session()->put('alert-class', 'alert-danger');
                            session()->put('alert-content', 'You Have Excited Maximum No. of Row at a Time');
                            return back();
                        }
                    } else { //for error message
                        session()->put('alert-class', 'alert-danger');
                        session()->put('alert-content', 'Your Excel Format Missmatch From Our Format..Please Download Our Excel Format..');
                        return back();
                    }
                }
                else
                {
                    session()->put('alert-class', 'alert-danger');
                    session()->put('alert-content', 'Please Fill At Least One Row in Excel Sheet');
                    return back();
                }

            } catch (\Exception $e) {
                return $e->getMessage();
            }
            
        }
    }

    public function saveFile($filename,$filecontent){
        if (strlen($filename)>0){
            $folderPath = 'public/uploaded_documents/error_log';
            if (!file_exists($folderPath)) {
                mkdir($folderPath);
            }
            $file = @fopen($folderPath . DIRECTORY_SEPARATOR . $filename,"w");
            if ($file != false){
                fwrite($file,$filecontent);
                fclose($file);
                return 1;
            }
            return -2;
        }
        return -1;
    }

    public function error_log_download()
    {
        # code...
        $filename = "ResourceNumber-ErrorLog-".Session::get('user_id').".txt";
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize("$filename") . ";");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/octet-stream; ");
        header("Content-Transfer-Encoding: binary");
        readfile($filename);
        exit();
    }

    // abhishek 
    public function view_diffrent_formate(Request $request)
    {
        // return $request;
        // return "akf";
        $asset_numbers_id = explode(',', $request->asset_numbers_id); // array
        $department = array();
        if ($request->print == "print_pdf") {

            if ($request->asset_numbers_id != "") {


                $AssetNumberdata = AssetNumbers::whereIn('asset_numbers_id', $asset_numbers_id)->select('geo_id', 'asset_id', 'year', 'created_at as date', DB::raw('MAX(updated_at) AS max_updated'), DB::raw('MAX(asset_numbers_id) as asset_numbers_id'))
                    ->groupBy('year', 'asset_id', 'geo_id', 'created_at')
                    // ->format("Y-m-d")
                    ->get();

                foreach ($AssetNumberdata as $key => $value) {
                    if (Asset::find($value->asset_id)) {
                        $value->asset_name = Asset::find($value->asset_id)->asset_name;
                    }
                    if (GeoStructure::find($value->geo_id)) {
                        $panchayat_data_tmp = GeoStructure::find($value->geo_id);
                        $value->panchayat_name = $panchayat_data_tmp->geo_name;
                        $block_data_tmp = GeoStructure::find($panchayat_data_tmp->bl_id);
                        $value->block_name = $block_data_tmp->geo_name;
                    }
                    if (Year::find($value->year)) {
                        $value->year_value = Year::find($value->year)->year_value;
                    }
                    $tmp = AssetNumbers::select('asset_numbers_id', 'pre_value', 'current_value')->where('asset_numbers_id', $value->asset_numbers_id)->first();
                    if (count($tmp) > 0) {
                        $value->pre_value = $tmp->pre_value;
                        $value->current_value = $tmp->current_value;
                        $value->asset_numbers_id = $tmp->asset_numbers_id;
                    }
                    $value->date = date("d/m/Y", strtotime($value->date));
                }

                $doc_details = array(
                    "title" => "Resource Number Data",
                    "author" => 'IT-Scient',
                    "topMarginValue" => 10,
                    "mode" => 'L'
                );

                date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('d-m-Y H:i:s');
                $user_name = Auth::user()->first_name;
                $user_last_name = Auth::user()->last_name;
                $pdfbuilder = new \PdfBuilder($doc_details);

                $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                $content .= "<th style='border: solid 1px #000000;' colspan=\"7\" align=\"left\" ><b>Resources Number</b></th></tr>";
                $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">" . "<b>" . "<span>Title: </span>&nbsp;&nbsp;&nbsp;Resources Number Details
        " . "<br>" . "<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;" . $currentDateTime . "<br>" . "<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name . "&nbsp;" . $user_last_name .
                    "</b>" . "</p>";

                /* ========================================================================= */
                /*                Total width of the pdf table is 1017px                     */
                /* ========================================================================= */
                $content .= "<thead>";
                $content .= "<tr>";
                $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl. No.</b></th>";
                $content .= "<th style=\"width: 250px;\" align=\"center\"><b>Year</b></th>";
                $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Resource</b></th>";
                $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Block</b></th>";
                $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Panchyat</b></th>";
                $content .= "<th style=\"width: 140px;\" align=\"center\"><b>Current Value</b></th>";
                $content .= "<th style=\"width: 97px;\" align=\"center\"><b>Date</b></th>";
                $content .= "</tr>";
                $content .= "</thead>";

                $content .= "<tbody>";
                foreach ($AssetNumberdata as $key => $row) {
                    $index = $key + 1;
                    $content .= "<tr>";
                    $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
                    $content .= "<td style=\"width: 250px;\" align=\"left\">" . $row->year_value . "</td>";
                    $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->asset_name . "</td>";
                    $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->block_name . "</td>";
                    $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->panchayat_name . "</td>";
                    $content .= "<td style=\"width: 140px;\" align=\"right\">" . $row->current_value . "</td>";
                    $content .= "<td style=\"width: 97px;\" align=\"right\">" . $row->date . "</td>";
                    $content .= "</tr>";
                }
                $content .= "</tbody></table>";
                // print_r($content);exit;
                $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                $pdfbuilder->output('ResourceNumber.pdf');
                exit;
            }
            //  return $request;
        } elseif ($request->print == "excel_sheet") {
            // return $request;
            if ($request->asset_numbers_id != "") {

                config()->set('database.connections.mysql.strict', false);
                \DB::reconnect(); //important as the existing connection if any would be in strict mode


                $data = array(1 => array("Resources Number Details"));
                $data[] = array('Sl. No.', 'Year', 'Resource', 'Block', 'Panchyat', 'Current Value', 'Date');

                $items = DB::table('asset_numbers')
                    ->whereIn('asset_numbers_id', $asset_numbers_id)
                    ->leftJoin('geo_structure', 'asset_numbers.geo_id', '=', 'geo_structure.geo_id')
                    ->leftJoin('asset', 'asset_numbers.asset_id', '=', 'asset.asset_id')
                    ->leftJoin('year', 'asset_numbers.year', '=', 'year.year_id')
                    ->select(
                        'asset_numbers.asset_numbers_id as slNo',
                        'year.year_value',
                        'asset.asset_name',
                        'geo_structure.bl_id as Block',
                        'geo_structure.geo_name',
                        'asset_numbers.current_value',
                        'asset_numbers.created_at as CreatedDate'
                    )
                    ->groupBy('asset_numbers.year', 'asset_numbers.asset_id', 'asset_numbers.geo_id')->get();

                //now changing back the strict ON
                config()->set('database.connections.mysql.strict', true);
                \DB::reconnect();


                foreach ($items as $key => $value) {
                    $value->CreatedDate = date('d/m/Y', strtotime($value->CreatedDate));
                    $block_data_tmp = GeoStructure::find($value->Block);
                    $value->Block = $block_data_tmp->geo_name;

                    $data[] = array(
                        $key + 1,
                        $value->year_value,
                        $value->asset_name,
                        $value->Block,
                        $value->geo_name,
                        $value->current_value,
                        $value->CreatedDate
                    );
                }
                \Excel::create('Resources Number Details', function ($excel) use ($data) {

                    // Set the title
                    $excel->setTitle('Resources Number Details');

                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');

                    $excel->sheet('Resources Number Details', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');
                return Excel::download(new AssetNumberSectionExport, 'Resource Number-Sheet.xls');
            }
            return $request;
        }
    }

}
