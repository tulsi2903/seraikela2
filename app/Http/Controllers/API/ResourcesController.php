<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 

use App\Asset;
use App\Year;
use App\AssetNumbers;
use App\GeoStructure;
use App\AssetGeoLocation;
use App\AssetBlockCount;
use App\AssetGallery;

class ResourcesController extends Controller
{
    //
    public $successStatus = 200;

    // for resources
    public function get_resources(Request $request){
        if($request->resources_id){
            $datas = Asset::where('asset_id', $request->resources_id)->select('asset_id as resources_id', 'asset_name as resources_name', 'movable', 'parent_id as parent')->first();
            $datas->sub_resources = [];
            $sub_resources_datas = Asset::where('parent_id', $datas->resources_id)->select('asset_id as resources_id', 'asset_name as resources_name', 'movable')->get();
            $datas->sub_resources = $sub_resources_datas;
        }
        else{
            $datas = Asset::select('asset_id as resources_id', 'asset_name as resources_name', 'movable')->where('parent_id', -1)->get();
            foreach($datas as $data){
                $data->sub_resources = [];
                $sub_resources_datas = Asset::where('parent_id', $data->resources_id)->select('asset_id as resources_id', 'asset_name as resources_name', 'movable')->get();
                $data->sub_resources = $sub_resources_datas;
            }
        }

        // // return after validate
        if(count($datas)>0){
            return response()->json(['success' => $datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }

    //
    public function get_resources_count(Request $request){
        $response_message = ""; // response message
        $response_status = true; // true ok, false something went wrong // initially its true means no error, so if any one of the error events occurred it will changes to false
        $datas_to_send = [];

        // received id's
        if($request->block_id){
            $block_id = $request->block_id;
        }
        else{
            $response_status = false;
            $response_message = "Please select block!";
        }
        
        if($request->panchayat_id){
            $panchayat_id = $request->panchayat_id;
        }
        else{
            $response_status = false;
            $response_message = "Please select panchayat!";
        }

        if($request->resources_id)
        {
            $asset_id = $request->resources_id;
        }
        else{
            $response_status = false;
            $response_message = "Please select resources!";
        }

        if($request->year_id)
        {
            $year_id = $request->year_id;
        }
        else{
            $response_status = false;
            $response_message = "Please select year!";
        }

        if($response_status)
        {
            // current value
            $asset_numbers_data = AssetNumbers::where('geo_id', $panchayat_id)
                                ->where('asset_id', $asset_id)
                                ->where('year', $year_id)
                                ->orderBy('asset_numbers_id', 'desc')
                                ->first();
            if($asset_numbers_data){
                // $datas_to_send["resource_numbers_id"] = (Int)$asset_numbers_data->asset_numbers_id;
                $datas_to_send["current_value"] = (Int)$asset_numbers_data->current_value;
            }
            else{
                // $datas_to_send["resource_numbers_id"] = null;
                $datas_to_send["current_value"] = 0;
            }
            // for asset
            $datas_to_send["movable"] = (Int)Asset::where('asset_id', $asset_id)->first()->movable;

            // getting previus asset_location
            $asset_locations = AssetGeoLocation::select('asset_geo_loc_id', 'location_name', 'latitude', 'longitude', 'asset_id')
                            ->where('geo_id', $panchayat_id)
                            ->where('asset_id', $asset_id)
                            ->where('year', $year_id)
                            ->orderBy('asset_geo_loc_id', 'desc')
                            ->get();
            foreach($asset_locations as $asset_location){
                // for get gallery images
                $asset_location->images = [];
                $img_array = [];
                if($images_check = AssetGallery::where('asset_geo_loc_id', $asset_location->asset_geo_loc_id)->first()){
                    if(unserialize($images_check->images)){
                        $images_array = unserialize($images_check->images);
                        foreach($images_array as $img){
                            $img_array[] = url('')."/".$img;
                        }
                        $asset_location->images = $img_array;
                    }
                }
                
                // for child resources counts
                $child_asset_datas_tmp = AssetNumbers::where('asset_geo_loc_id', $asset_location->asset_geo_loc_id)->get();
                $child_asset_datas_arr = [];
                foreach($child_asset_datas_tmp as $child_asset_data_tmp){
                    $child_asset_datas_arr[] = ["sub_resource_id"=>$child_asset_data_tmp->asset_id, "sub_resource_name"=>Asset::where('asset_id', $child_asset_data_tmp->asset_id)->first()->asset_name, "current_value"=>$child_asset_data_tmp->current_value];
                }
                $asset_location->sub_resources_data = $child_asset_datas_arr;
            }
            $datas_to_send["resources_locations"] = $asset_locations;
        }

        // return after validate
        return response()->json(['status' => $response_status, "message"=> $response_message, 'data'=>$datas_to_send], 200);
    }

    // individual details according to asset_geo_loc_id
    public function get_single_details(Request $request)
    {
        $response_message = ""; // response message
        $response_status = true; // true ok, false something went wrong // initially its true means no error, so if any one of the error events occurred it will changes to false
        $datas_to_send = [];

        // received id's
        if($request->asset_geo_loc_id){
            $asset_geo_loc_id = $request->asset_geo_loc_id;
        }
        else{
            $response_status = false;
            $response_message = "No id selected!";
        }

        if($response_status)
        {
            // getting previus asset_location
            $asset_location = AssetGeoLocation::select('asset_geo_loc_id', 'location_name', 'latitude', 'longitude', 'asset_id')
            ->where('asset_geo_loc_id', $asset_geo_loc_id)
            ->first();

            if($asset_location)
            {
                $asset_location->images = [];
                $img_array = [];
                if($images_check = AssetGallery::where('asset_geo_loc_id', $asset_location->asset_geo_loc_id)->first()){
                    if(unserialize($images_check->images)){
                        $images_array = unserialize($images_check->images);
                        foreach($images_array as $img){
                            $img_array[] = url('')."/".$img;
                        }
                        $asset_location->images = $img_array;
                    }
                }
                
                // for child resources counts
                $child_asset_datas_tmp = AssetNumbers::where('asset_geo_loc_id', $asset_location->asset_geo_loc_id)->get();
                $child_asset_datas_arr = [];
                foreach($child_asset_datas_tmp as $child_asset_data_tmp){
                    $child_asset_datas_arr[] = ["sub_resource_id"=>$child_asset_data_tmp->asset_id, "sub_resource_name"=>Asset::where('asset_id', $child_asset_data_tmp->asset_id)->first()->asset_name, "current_value"=>$child_asset_data_tmp->current_value];
                }
                $asset_location->sub_resources_data = $child_asset_datas_arr;

                $datas_to_send[] = $asset_location;
            }
        }

        // return after validate
        return response()->json(['status' => $response_status, "message"=> $response_message, 'data'=>$datas_to_send], 200);
    }

    function store_resources_numbers(Request $request){
        $response_message = ""; // response message
        $response_status = true; // true ok, false something went wrong // initially its true means no error, so if any one of the error events occurred it will changes to false
        $datas_to_send = [];
        $action = "add";

        // validation add/edit
        if(!$request->action){
            $response_message = "Please provide action!";
            $response_status = false;
        }
        else{
            if($request->action=="add"){
                $action = "add";
            }
            else if($request->action=="edit"){
                $action="edit";
                if(!AssetGeoLocation::find($request->asset_geo_loc_id))
                {
                    $response_message = "Sorry you can't edit this data!";
                    $response_status = false;
                }
            }
            else{
                $response_message = "Please provide action add/edit only!";
                $response_status = false;
            }
        }

        if($request->year_id){
            $year_id = $request->year_id;
        }
        else{
            $response_message = "Please select year!";
            $response_status = false;
        }

        if($request->block_id){
            $block_id = $request->block_id;
        }
        else{
            $response_message = "Please select block!";
            $response_status = false;
        }

        if($request->resources_id){
            $asset_id = $request->resources_id;
        }
        else{
            $response_message = "Please select respurces!";
            $response_status = false;
        }

        if($request->panchayat_id){
            $panchayat_id = $request->panchayat_id;
        }
        else{
            $response_message = "Please select panchayat!";
            $response_status = false;
        }

        if($response_status) //means no error
        {
            //1.  storing asset numbers with current value increment if add, for edit just edit that peticular column
            $asset_numbers_save = new AssetNumbers;
            if($action=="add")
            {
                if($value_find = AssetNumbers::where('asset_id', $asset_id)->where('geo_id', $panchayat_id)->where('year', $year_id)->first()){ // for add, if previous data is available
                    $asset_numbers_save = $asset_numbers_save->find($value_find->asset_numbers_id);
                    $asset_numbers_save->pre_value = $value_find->current_value;
                    $asset_numbers_save->current_value = $value_find->current_value+1;
                    $asset_numbers_save->updated_by = Auth::user()->id;
                    $asset_numbers_save->save();
                }
                else{ // for new fresh add
                    $asset_numbers_save->asset_id = $asset_id;
                    $asset_numbers_save->geo_id = $panchayat_id;
                    $asset_numbers_save->pre_value = 0;
                    $asset_numbers_save->current_value = 1;
                    $asset_numbers_save->year = $year_id;
                    $asset_numbers_save->org_id = Auth::user()->org_id;
                    $asset_numbers_save->created_by = Auth::user()->id;
                    $asset_numbers_save->updated_by = Auth::user()->id;
                    $asset_numbers_save->save();
                }
            }


            //2.  asset location (it will help you to store data in asset_numbers for sub resopurces & in gallery)
            $asset_geo_location_save = new AssetGeoLocation;
            if($action=="edit"){
                $asset_geo_location_save = $asset_geo_location_save->find($request->asset_geo_loc_id); // its always true, because we test this condition earlier
                $asset_geo_location_save->location_name = $request->landmark; // mat be null for movable resources
                $asset_geo_location_save->latitude = $request->latitude; // mat be null for movable resources
                $asset_geo_location_save->longitude = $request->longitude; // mat be null for movable resources
                $asset_geo_location_save->updated_by = Auth::user()->id;
            }
            else{
                $asset_geo_location_save->asset_id = $asset_id;
                $asset_geo_location_save->geo_id = $panchayat_id;
                $asset_geo_location_save->location_name = $request->landmark; // mat be null for movable resources
                $asset_geo_location_save->latitude = $request->latitude; // mat be null for movable resources
                $asset_geo_location_save->longitude = $request->longitude; // mat be null for movable resources
                $asset_geo_location_save->year = $year_id;
                $asset_geo_location_save->org_id = Auth::user()->org_id;
                $asset_geo_location_save->created_by = Auth::user()->id;
                $asset_geo_location_save->updated_by = Auth::user()->id;
            }
            $asset_geo_location_save->save();


            //3. Use loc_id to values of sub resources numbers in asset numbers table
            if($asset_geo_location_save && count($request->sub_resources)>0)
            {
                foreach($request->sub_resources as $sub_resource){
                    if($sub_resource["value"]!=0)
                    {
                        $asset_numbers_sub_resources_save = new AssetNumbers;
                        if($value_find = AssetNumbers::where('asset_geo_loc_id', $asset_geo_location_save->asset_geo_loc_id)->where('asset_id',$sub_resource["id"])->where('geo_id', $panchayat_id)->where('year', $year_id)->first()){ // true of already data during add (true/false), during edit (true)
                            $asset_numbers_sub_resources_save = $asset_numbers_sub_resources_save->find($value_find->asset_numbers_id);
                            $asset_numbers_sub_resources_save->pre_value = $asset_numbers_sub_resources_save->current_value;
                            $asset_numbers_sub_resources_save->current_value = $sub_resource["value"];
                            $asset_numbers_sub_resources_save->updated_by = Auth::user()->id;
                            $asset_numbers_sub_resources_save->save();
                        }
                        else{
                            $asset_numbers_sub_resources_save->asset_id = $sub_resource["id"];
                            $asset_numbers_sub_resources_save->geo_id = $panchayat_id;
                            $asset_numbers_sub_resources_save->asset_geo_loc_id = $asset_geo_location_save->asset_geo_loc_id;
                            $asset_numbers_sub_resources_save->pre_value = 0;
                            $asset_numbers_sub_resources_save->current_value = $sub_resource["value"];
                            $asset_numbers_sub_resources_save->year = $year_id;
                            $asset_numbers_sub_resources_save->org_id = Auth::user()->org_id;
                            $asset_numbers_sub_resources_save->created_by = Auth::user()->id;
                            $asset_numbers_sub_resources_save->updated_by = Auth::user()->id;
                            $asset_numbers_sub_resources_save->save();
                        }
                    }
                }
            }

            $response_status = true;
            $response_message = "Successfully saved!";
            $datas_to_send["asset_geo_loc_id"] = $asset_geo_location_save->asset_geo_loc_id;

            //4. for gallery: will have another API

            /* old codes */
            // enter data in asset_geo_location
            // $pre_value = $request->pre_value;
            // $current_value = $request->current_value;

            // $landmark = $request->landmark; // array if more than 1 entries (details, geo loc)
            // $latitude = $request->latitude; // array if more than 1 entries (details, geo loc)
            // $longitude = $request->longitude; // array if more than 1 entries (details, geo loc)
            // $gallery = $request->gallery; // multidimentional array if more than 1 entries (details, geo loc)


            // // storing data to asset numbers
            // $asset_numbers_save = new AssetNumbers;
            // $asset_numbers_save->asset_id = $asset_id;
            // $asset_numbers_save->geo_id = $panchayat_id;
            // $asset_numbers_save->pre_value = $pre_value;
            // $asset_numbers_save->current_value = $current_value;
            // $asset_numbers_save->year = $year_id;
            // $asset_numbers_save->org_id = Auth::user()->org_id;
            // $asset_numbers_save->created_by = Auth::user()->id;
            // $asset_numbers_save->updated_by = Auth::user()->id;
            // $asset_numbers_save->save();

            // // storing datas to asset geo location
            // for($i=0;$i<count($latitude);$i++){
            //     $asset_geo_location_save = new AssetGeoLocation;
            //     $asset_geo_location_save->asset_id = $asset_id;
            //     $asset_geo_location_save->geo_id = $panchayat_id;
            //     $asset_geo_location_save->location_name = $landmark[$i];
            //     $asset_geo_location_save->latitude = $latitude[$i];
            //     $asset_geo_location_save->longitude = $longitude[$i];
            //     $asset_geo_location_save->year = $year_id;
            //     $asset_geo_location_save->org_id = Auth::user()->org_id;
            //     $asset_geo_location_save->created_by = Auth::user()->id;
            //     $asset_geo_location_save->updated_by = Auth::user()->id;
            //     $asset_geo_location_save->save();
            // }
        }

        // return after validate
        return response()->json(['status' => $response_status, "message"=> $response_message, 'data'=>$datas_to_send], 200);
    }

    public function store_resources_images(Request $request){
        $response_message = ""; // response message
        $response_status = true; // true ok, false something went wrong // initially its true means no error, so if any one of the error events occurred it will changes to false
        $datas_to_send = [];
        $upload_directory = "public/uploaded_documents/assets-gallery/";

        if($request->asset_geo_loc_id){
            $asset_geo_loc_id = $request->asset_geo_loc_id;
            if($asset_geo_location = AssetGeoLocation::find($asset_geo_loc_id))
            {
                
            }
            else{
                $response_status = false;
                $response_message = "Sorry you can't edit this data!";
            }
        }
        else{
            $response_status = false;
            $response_message = "Something went wrong! Please provide all data.";
        }

        if($response_status){
            // for delete
            if($request->to_delete){
                if($check = AssetGallery::where('asset_geo_loc_id', $asset_geo_location->asset_geo_loc_id)->first()) // if already exist
                {
                    $asset_gallery_save = new AssetGallery;
                    $asset_gallery_save = $asset_gallery_save->find($check->asset_gallery_id);
                    $orig_gallery = unserialize($asset_gallery_save->images);
                    $to_delete_array = explode(",", $request->to_delete);
                    foreach($to_delete_array as $item){
                        $item = str_replace(url('')."/", "", trim($item));
                        if(in_array($item, $orig_gallery)){
                            array_splice($orig_gallery, array_search($item, $orig_gallery), 1);
                        }
                    }
                    $asset_gallery_save->images = serialize($orig_gallery);
                    $asset_gallery_save->save();
                }
            }


            // save to asset_gallery on behalf of asset_geo_loc_id
            if ($request->hasFile('images')) {
                $asset_gallery_save = new AssetGallery;
                $previous_images_array = []; // initially blank array for gallery
                if($check = AssetGallery::where('asset_geo_loc_id', $asset_geo_location->asset_geo_loc_id)->first()) // if already exist
                {
                    $asset_gallery_save = $asset_gallery_save->find($check->asset_gallery_id);
                    $previous_images_array = unserialize($check->images);
                }
                else{ // new data
                    $asset_gallery_save->asset_id = $asset_geo_location->asset_id;
                    $asset_gallery_save->asset_geo_loc_id = $asset_geo_location->asset_geo_loc_id;
                    $asset_gallery_save->geo_id = $asset_geo_location->geo_id;
                    $asset_gallery_save->year_id = $asset_geo_location->year;
                    $asset_gallery_save->org_id = Auth::user()->org_id;
                    $asset_gallery_save->created_by = Auth::user()->id;
                }
                $asset_gallery_save->updated_by = Auth::user()->id;
                
                foreach ($request->file('images') as $file) {
                    // $file = $request->file('images');
                    // $file => is each files $images
                    $images_tmp_name = "assets-gallery-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move($upload_directory, $images_tmp_name);   // move the file to desired folder
                    array_push($previous_images_array, $upload_directory . $images_tmp_name);    // appending location of image in previous image array for further insertion into database
                }

                $asset_gallery_save->images = serialize($previous_images_array);    // assign the location of folder to the model
                $asset_gallery_save->save();

                $response_status = true;
                $response_message = "Images uploaded successfully!";
            }

            $response_status = true;
            $response_message = "Images updated successfully!";
            if($check = AssetGallery::where('asset_geo_loc_id', $asset_geo_location->asset_geo_loc_id)->first()){
                $previous_images_array = unserialize($check->images);
                foreach($previous_images_array as &$value){
                    $value = url('')."/".$value;
                }
                $datas_to_send["images"] = $previous_images_array;
            }
        }

        return response()->json(['status' => $response_status, "message"=> $response_message, 'data'=>$datas_to_send], 200);
    }
}
