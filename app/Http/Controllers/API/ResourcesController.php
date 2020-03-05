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
            $datas = Asset::select('asset_id as resources_id', 'asset_name as resources_name', 'movable')->get();
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
        $to_send_datas = [];

        // received id's
        $block_id = $request->block_id;
        $panchayat_id = $request->panchayat_id;
        $asset_id = $request->resources_id;
        $year_id = $request->year_id;

        // current value
        $asset_numbers_data = AssetNumbers::where('geo_id', $panchayat_id)
                            ->where('asset_id', $asset_id)
                            ->where('year', $year_id)
                            ->orderBy('asset_numbers_id', 'desc')
                            ->first();
        if($asset_numbers_data){
            $to_send_datas["asset_numbers_id"] = (Int)$asset_numbers_data->asset_numbers_id;
            $to_send_datas["current_value"] = (Int)$asset_numbers_data->current_value;
        }
        else{
            $to_send_datas["asset_numbers_id"] = null;
            $to_send_datas["current_value"] = 0;
        }
        // for asset
        $to_send_datas["movable"] = (Int)Asset::where('asset_id', $asset_id)->first()->movable;

        // getting previus asset_location
        $asset_locations = AssetGeoLocation::select('asset_geo_loc_id', 'location_name', 'latitude', 'longitude', 'asset_id')
                        ->where('geo_id', $panchayat_id)
                        ->where('asset_id', $asset_id)
                        ->where('year', $year_id)
                        ->orderBy('asset_geo_loc_id', 'desc')
                        ->get();
        foreach($asset_locations as $asset_location){
            // for get gallery images
            $asset_location->images = unserialize(AssetGallery::where('asset_geo_loc_id', $asset_location->asset_geo_loc_id)
                    ->first()->images);
            
            // for child resources counts
            $child_asset_datas_tmp = AssetNumbers::select('asset_numbers_id', 'current_value')->where('asset_geo_loc_id', $asset_location->asset_geo_loc_id)
                                                    ->get();
            $child_asset_datas_arr = [];
            foreach($child_asset_datas_tmp as $child_asset_data_tmp){
                $child_asset_datas_arr[] = ["asset_numbers_id"=>$child_asset_data_tmp->asset_numbers_id, "current_value"=>$child_asset_data_tmp->current_value];
            }
            $asset_location->child_asset_datas = $child_asset_datas_arr;
        }
        $to_send_datas["resources_locations"] = $asset_locations;

        // return after validate
        if($to_send_datas["current_value"]>0){
            return response()->json(['success' => $to_send_datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }

    function store_resources_numbers(Request $request){
        $year_id = $request->year_id;
        $asset_id = $request->resources_id;
        $block_id = $request->block_id;
        $panchayat_id = $request->panchayat_id;

        $pre_value = $request->pre_value;
        $current_value = $request->current_value;

        $landmark = $request->landmark; // array if more than 1 entries (details, geo loc)
        $latitude = $request->latitude; // array if more than 1 entries (details, geo loc)
        $longitude = $request->longitude; // array if more than 1 entries (details, geo loc)
        $gallery = $request->gallery; // multidimentional array if more than 1 entries (details, geo loc)


        // storing data to asset numbers
        $asset_numbers_save = new AssetNumbers;
        $asset_numbers_save->asset_id = $asset_id;
        $asset_numbers_save->geo_id = $panchayat_id;
        $asset_numbers_save->pre_value = $pre_value;
        $asset_numbers_save->current_value = $current_value;
        $asset_numbers_save->year = $year_id;
        $asset_numbers_save->org_id = Auth::user()->org_id;
        $asset_numbers_save->created_by = Auth::user()->id;
        $asset_numbers_save->updated_by = Auth::user()->id;
        $asset_numbers_save->save();

        // storing datas to asset geo location
        for($i=0;$i<count($latitude);$i++){
            $asset_geo_location_save = new AssetGeoLocation;
            $asset_geo_location_save->asset_id = $asset_id;
            $asset_geo_location_save->geo_id = $panchayat_id;
            $asset_geo_location_save->location_name = $landmark[$i];
            $asset_geo_location_save->latitude = $latitude[$i];
            $asset_geo_location_save->longitude = $longitude[$i];
            $asset_geo_location_save->year = $year_id;
            $asset_geo_location_save->org_id = Auth::user()->org_id;
            $asset_geo_location_save->created_by = Auth::user()->id;
            $asset_geo_location_save->updated_by = Auth::user()->id;
            $asset_geo_location_save->save();
        }

        // return after validate
        return response()->json(['success' => "data_saved"], $this->successStatus);
    }
}
