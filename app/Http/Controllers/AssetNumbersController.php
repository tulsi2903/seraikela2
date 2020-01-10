<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetNumbers;
use App\GeoStructure;
use App\Asset;
use App\Year;
use DB;
use App\AssetGeoLocation;
use App\AssetBlockCount;
use App\AssetGallery;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetNumberSectionExport;
use PDF;

class AssetNumbersController extends Controller
{

    public function index()
    {
        // getting rows, last updated
        $asset_ids = Asset::where('parent_id',-1)->get()->pluck('asset_id');
        $datas = AssetNumbers::select('geo_id', 'asset_id', 'year', DB::raw('MAX(updated_at) AS max_updated'), DB::raw('MAX(asset_numbers_id) as asset_numbers_id'))
                ->whereIn('asset_id', $asset_ids)
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
        // return $request;
        $hidden_input_purpose = "add";
        $hidden_input_id = "NA";

        $assets = Asset::orderBy('asset_id')->where('parent_id',-1)->get();
        $panchayats = GeoStructure::where('level_id', '4')->orderBy('geo_name')->get();
        $years = Year::orderBy('year_id')->get();


        $data = new AssetNumbers;

        if (isset($request->purpose) && isset($request->id)) {
            $hidden_input_purpose = $request->purpose;
            $hidden_input_id = $request->id;
            if ($data->find($request->id)) {
                $data = $data->find($request->id);
            }
        }

        return view('asset-numbers.add')->with(compact('hidden_input_purpose', 'hidden_input_id', 'data', 'assets', 'panchayats', 'years'));
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
        if ($current_value_data) {
            $current_value = $current_value_data->current_value;
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
        $asset_location = AssetGeoLocation::select('asset_geo_loc_id', 'location_name', 'latitude', 'longitude','asset_id')->where('geo_id', $request->geo_id)
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

    public function store(Request $request)
    {
        // return $request;
        $asset_number = new AssetNumbers;

        if ($request->hidden_input_purpose == "edit") {
            $asset_number = $asset_number->find($request->hidden_input_id);
        }

        $asset_number->year = $request->year;
        $asset_number->asset_id = $request->asset_id;
        $asset_number->geo_id = $request->geo_id;
        $asset_number->pre_value = $request->previous_value;
        $asset_number->current_value = $request->current_value;

        $asset_number->created_by = '1';
        $asset_number->updated_by = '1';


        if (isset($request->location_name)) {
            $location_name = $request->location_name;
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            for ($i = 0; $i < count($location_name); $i++) {
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
        } else if (isset($request->delete_asset_geo_loc_id)) {
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
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Asset details have been successfully submitted !');
            if ($request->hidden_input_purpose == "add") {
                session()->put('message',$asset_number->asset_numbers_id);
                
                // session()->put('alert-class', 'alert-success');
                // session()->put('alert-content', 'Asset details have been successfully submitted !');
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


        $data = array(1 => array("Asset Numbers Sheet"));
        $data[] = array('Sl. No.','Year','Asset','Block','Panchyat','Current Value','Date');       
     
        $items = DB::table('asset_numbers')
		->leftJoin('geo_structure', 'asset_numbers.geo_id', '=', 'geo_structure.geo_id')
		->leftJoin('asset', 'asset_numbers.asset_id', '=', 'asset.asset_id')
		->leftJoin('year', 'asset_numbers.year', '=', 'year.year_id')
		->select('asset_numbers.asset_numbers_id as slNo', 
                'year.year_value', 
                'asset.asset_name', 
                'geo_structure.bl_id as Block', 
                'geo_structure.geo_name', 
                'asset_numbers.current_value','asset_numbers.created_at as CreatedDate')
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
        \Excel::create('Asset_Numbers', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Asset Numbers Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
        return Excel::download(new AssetNumberSectionExport, 'Asset Number-Sheet.xls');
    }

    public function exportpdfFunctiuonforasset_Numbers()
    {
        $AssetNumberdata = AssetNumbers::select('geo_id', 'asset_id', 'year','created_at as date', DB::raw('MAX(updated_at) AS max_updated'), DB::raw('MAX(asset_numbers_id) as asset_numbers_id'))
                                ->groupBy('year', 'asset_id', 'geo_id','created_at')
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
            "title" => "Asset Number Data",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'L'
        );

        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"7\" align=\"left\" ><b>Asset Number</b></th></tr>";
        
        /* ========================================================================= */
        /*                Total width of the pdf table is 1017px                     */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl. No.</b></th>";
        $content .= "<th style=\"width: 250px;\" align=\"center\"><b>Year</b></th>";
        $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Asset</b></th>";
        $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Block</b></th>";
        $content .= "<th style=\"width: 160px;\" align=\"center\"><b>Panchyat</b></th>";
        $content .= "<th style=\"width: 140px;\" align=\"center\"><b>Current Value</b></th>";
        $content .= "<th style=\"width: 97px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";

        $content .= "<tbody>";
        foreach ($AssetNumberdata as $key => $row) {
            $index = $key+1;
            $content .= "<tr>";
            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"width: 250px;\" align=\"left\">" . $row->year_value . "</td>";
            $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->asset_name. "</td>";
            $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->block_name . "</td>";
            $content .= "<td style=\"width: 160px;\" align=\"left\">" . $row->panchayat_name."</td>";
            $content .= "<td style=\"width: 140px;\" align=\"right\">" . $row->current_value . "</td>";
            $content .= "<td style=\"width: 97px;\" align=\"right\">" . $row->date . "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        // print_r($content);exit;
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('AssetNumber.pdf');
        exit;
    }

    public function list_of_childs($child_id,$geo_child_id,$year_child_id,$hidden_input_id)
    {

        $childdatas = Asset::leftJoin('asset_numbers', 'asset.asset_id', '=', 'asset_numbers.asset_id')
        ->where('asset.parent_id',$child_id)
        ->select('asset.asset_id','asset.asset_name',
        'asset.movable',
        'asset.parent_id',
        'asset.dept_id',
        'asset.org_id',
        'asset_numbers.asset_numbers_id',
        'asset_numbers.geo_id',
        'asset_numbers.pre_value',
        'asset_numbers.current_value',
        'asset_numbers.year'
        )->get();

        // echo "<pre>";
        // print_r($childdatas);exit;
        return view('asset-numbers.child_resources_number')->with(compact('childdatas','geo_child_id','year_child_id','hidden_input_id'));

    }

    public function saveChilddata(Request $request)
    {
        // return $request;
        foreach ($request->child_asset_id as $key => $value) {
            # code...
            if($request->asset_numbers_child_id[$key] != null)
            {
                $AssetNumbers = AssetNumbers::find($request->asset_numbers_child_id[$key]);
                $AssetNumbers->pre_value = $request->current_value_child[$key];
                $AssetNumbers->current_value = ( $request->current_value_child[$key] != null) ? $request->current_value_child[$key] : 0;
                $AssetNumbers->save();
            }
            else
            {
                $asset_number = new AssetNumbers;
                $asset_number->year = $request->year_child_id;
                $asset_number->asset_id = $value;
                $asset_number->geo_id = $request->geo_child_id;
                $asset_number->pre_value = $request->previous_value_child[$key];
                $asset_number->current_value = ( $request->current_value_child[$key] != null) ? $request->current_value_child[$key] : 0;
        
                $asset_number->created_by = '1';
                $asset_number->updated_by = '1';
                $asset_number->save();

            }
        }
        session()->put('alert-class', 'alert-success');
        session()->put('alert-content', 'Child Resources details have been successfully submitted !');
        return redirect('asset-numbers/add?purpose=edit&id='.$request->main_asset_id);
    }
}
