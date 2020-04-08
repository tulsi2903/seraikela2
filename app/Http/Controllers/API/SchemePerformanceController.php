<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SchemeStructure;
use App\SchemePerformance;
use App\scheme_block_performance;
use App\Year;
use App\GeoStructure;
use App\Asset;
use App\SchemeAsset;
use Illuminate\Support\Facades\Auth;

class SchemePerformanceController extends Controller
{
    //
    public $successStatus = 200;

    // get scheme details
    public function get_schemes(Request $request){
        if($request->scheme_id){
            $datas = SchemeStructure::leftJoin('scheme_assets','scheme_assets.scheme_asset_id', '=', 'scheme_structure.scheme_asset_id')
                                    ->where('scheme_id', $request->scheme_id)
                                    ->select('scheme_structure.scheme_id','scheme_structure.scheme_short_name','scheme_structure.scheme_name','scheme_structure.scheme_is','scheme_structure.attributes','scheme_structure.scheme_asset_id','scheme_assets.scheme_asset_name')
                                    ->first();
            if($datas){
                $datas->attributes = unserialize($datas->attributes);
            }
        }
        else{
            $datas = SchemeStructure::leftJoin('scheme_assets','scheme_assets.scheme_asset_id', '=', 'scheme_structure.scheme_asset_id')
                                    ->where('status', 1)
                                    ->select('scheme_structure.scheme_id','scheme_structure.scheme_short_name','scheme_structure.scheme_name','scheme_structure.scheme_is','scheme_structure.attributes','scheme_structure.scheme_asset_id','scheme_assets.scheme_asset_name')
                                    ->get();
            if($datas){
                foreach($datas as $data){
                    $data->attributes = unserialize($data->attributes);
                }
            }
        }

        // return after validate
        if(count($datas)>0){
            return response()->json(['success' => $datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }

    // get scheme details
    public function get_scheme_attributes(Request $request){
        $attributes = [];
        if($request->scheme_id){
            $scheme_data_tmp = SchemeStructure::where('scheme_id', $request->scheme_id)
                                    ->select('scheme_structure.attributes')
                                    ->first();
            if($scheme_data_tmp){
                $attributes = unserialize($scheme_data_tmp->attributes);
                return response()->json(['success' => $attributes], $this->successStatus);
            }
            else{
                return response()->json(['error'=>'no_data_found'], 204);
            }
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }



    // for scheme
    public function get_scheme_asset(Request $request){
        if($request->scheme_asset_id){
            $datas = SchemeAsset::where('scheme_asset_id', $request->scheme_asset_id)->select('scheme_asset_id','scheme_asset_name')->first();
        }
        else{
            $datas = SchemeAsset::select('scheme_asset_id','scheme_asset_name')->get();
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
    public function get_scheme_performance_datas(Request $request){
        $response = "intialized";
        $response_code = 400; // bad request
        $error = false;
        $page = 1;
        $total_data = 0;

        $to_return  = []; // has to return

        // validation starts //
        if($request->year_id){
            $year_id = $request->year_id;
        }
        else{
            $response = "year_id_not_received";
            $error = true;
        }
        if($request->scheme_id){
            $scheme_id = $request->scheme_id;
        }
        else{
            $response = "scheme_id_not_received";
            $error = true;
        }
        if($request->panchayat_id){
            $panchayat_id = $request->panchayat_id;
        }
        else{
            $response = "panchayat_id_not_received";
            $error = true;
        }
        // validations ends //
        // page
        if($request->page){
            $page = (int)$request->page;
        }

        if(!$error)
        {
            $total_data = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->count();
            $scheme_performance_datas = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->skip(($page - 1) * 20)->take($page * 20)->get();
            $scheme_attributes = unserialize(SchemeStructure::where('scheme_id', $scheme_id)->first()->attributes);

            foreach($scheme_performance_datas as $scheme_performance_data){
                $to_return_tmp = [];

                //
                $to_return_tmp['id'] = $scheme_performance_data->scheme_performance_id;

                // for attributes starts
                $performance_attributes = [];
                $per_attr_tmps = unserialize($scheme_performance_data->attribute);
                $i=0;
                $attr_index = ["attr_one","attr_two","attr_three","attr_four","attr_five","attr_six","attr_seven"];
                foreach($per_attr_tmps as $per_attr_tmp) {
                    $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
                }
                foreach($scheme_attributes as $scheme_attribute){
                    if($performance_attributes[$scheme_attribute['id']]){
                        $to_return_tmp['data'][] = ["name"=>$scheme_attribute['name'], "key"=>$attr_index[$i], "value"=>$performance_attributes[$scheme_attribute['id']]];
                    }
                    else{
                        $to_return_tmp['data'][] = ["name"=>$scheme_attribute['name'], "key"=>$attr_index[$i], "value"=>""];
                    }
                    $i++;
                }
                // for attributes ends

                // for status
                if ($scheme_performance_data->status == 0) {
                    $to_return_tmp['data'][] = ["name"=>"status", "key"=>"status",  "value"=>"Inprogess"];
                } else if ($performance_data->status == 1) {
                    $to_return_tmp['data'][] = ["name"=>"status", "key"=>"status",  "value"=>"Completed"];
                } else if ($performance_data->status == 2) {
                    $to_return_tmp['data'][] = ["name"=>"status", "key"=>"status",  "value"=>"Sanctioned"];
                } else if ($performance_data->status == 3) {
                    $to_return_tmp['data'][] = ["name"=>"status", "key"=>"status",  "value"=>"Cancelled"];
                }
                else{ // ==4, and other
                    $to_return_tmp['data'][] = ["name"=>"status", "key"=>"status",  "value"=>"Open"];
                }

                // for comment
                $to_return_tmp['data'][] = ["name"=>"comment", "key"=>"comment", "value"=>$scheme_performance_data->comments];

                // for assets
                if($scheme_performance_data->scheme_asset_id) {
                    $to_return_tmp['data'][] = ["name"=>"asset", "key"=>"scheme_asset_id", "value"=>SchemeAsset::find($scheme_performance_data->scheme_asset_id)->scheme_asset_name];
                } 
                else {
                    $to_return_tmp['data'][] = ["name"=>"asset", "key"=>"scheme_asset_id", "value"=>SchemeAsset::find(SchemeStructure::where('scheme_id', $scheme_id)->first()->scheme_asset_id)->scheme_asset_name];
                }

                // for coordinates
                if(unserialize($scheme_performance_data->coordinates))
                {
                    $to_return_tmp['data'][] = ["name"=>"coordinates", "key"=>"coordinates",  "value"=>unserialize($scheme_performance_data->coordinates)];
                }
                else{
                    $to_return_tmp["data"][] = ["name"=>"coordinates", "key"=>"coordinates",  "value"=>[]];
                }

                // for gallery
                if(unserialize($scheme_performance_data->gallery))
                {
                    $gallery = [];
                    foreach(unserialize($scheme_performance_data->gallery) as $item){
                        $gallery[] = ["url"=>url('')."/".$item];
                    }
                    $to_return_tmp['data'][] = ["name"=>"gallery", "key"=>"gallery",  "value"=>$gallery];
                }
                else{
                    $to_return_tmp['data'][] = ["name"=>"gallery", "key"=>"gallery",  "value"=>[]];
                }

                // coonectivity details
                if(unserialize($scheme_performance_data->borders_connectivity))
                {
                    $connectivity = [];
                    foreach(unserialize($scheme_performance_data->borders_connectivity) as $item){
                        $connectivity[] = ["block_id"=>(int)$item["conn_block_id"], "panchayat_id"=>(int)$item["conn_panchayat_id"]];
                    }
                    $to_return_tmp['data'][] = ["name"=>"connectivity", "key"=>"connectivity", "value"=>$connectivity];
                }
                else{
                    $to_return_tmp['data'][] = $to_return_tmp['data'][] = ["name"=>"connectivity", "key"=>"connectivity", "value"=>[]];
                }


                // final appending to to_return
                $to_return[] = $to_return_tmp;
            }
        }


        // return after validate
        if(!$error){
            if(count($to_return)>0){
                $response = "success";
                $response_code = 200;
            }
            else{
                $response = "no_data";
                $response_code = 204;
            }
        }

        return response()->json(['response'=>$response, "total_data"=>$total_data, 'page'=>$page, 'performance_data'=>$to_return], $response_code);
    }

    public function get_scheme_performance_details(Request $request){
        $response = "intialized";
        $response_code = 400; // bad request
        $scheme_performance_id = null;
        $to_return = [];

        // validation
        if($request->id){
            $scheme_performance_id = (int)$request->id;
            if(SchemePerformance::find($scheme_performance_id)){
                $scheme_performance_data = SchemePerformance::find($scheme_performance_id);
                $to_return["id"] = $scheme_performance_data->scheme_performance_id;
                $to_return["scheme_id"] = $scheme_performance_data->scheme_id;
                $to_return["year_id"] = $scheme_performance_data->year_id;
                $to_return["block_id"] = $scheme_performance_data->subdivision_id;
                $to_return["panchayat_id"] = $scheme_performance_data->subdivision_id;
                $to_return["subdivision_id"] = $scheme_performance_data->subdivision_id;

                // for attributes
                $scheme_attributes = unserialize(SchemeStructure::where('scheme_id', $scheme_performance_data->scheme_id)->first()->attributes);
                $performance_attributes = [];
                $per_attr_tmps = unserialize($scheme_performance_data->attribute);
                foreach($per_attr_tmps as $per_attr_tmp) {
                    $performance_attributes[key($per_attr_tmp)] = $per_attr_tmp[key($per_attr_tmp)];
                }
                $i=0;
                $attr_index = ["attr_one","attr_two","attr_three","attr_four","attr_five","attr_six","attr_seven"];
                foreach($scheme_attributes as $scheme_attribute){
                    if($performance_attributes[$scheme_attribute['id']]){
                        $to_return[$attr_index[$i]] = $performance_attributes[$scheme_attribute['id']];
                    }
                    else{
                        $to_return[$attr_index[$i]] = "";
                    }
                    $i++;
                }
                for($j=$i;$j<7;$j++){
                    $to_return[$attr_index[$j]] = "";
                }
                // for attributes ends

                // for coordinates
                if(unserialize($scheme_performance_data->coordinates))
                {
                    $to_return["coordinates"] = unserialize($scheme_performance_data->coordinates);
                }
                else{
                    $to_return["coordinates"] = [];
                }

                // for gallery
                if(unserialize($scheme_performance_data->gallery))
                {
                    $gallery = [];
                    foreach(unserialize($scheme_performance_data->gallery) as $item){
                        $gallery[] = url('')."/".$item;
                    }
                    $to_return["gallery"] = $gallery;
                }
                else{
                    $to_return["gallery"] = [];
                }

                // coonectivity details
                if(unserialize($scheme_performance_data->borders_connectivity))
                {
                    $connectivity = [];
                    foreach(unserialize($scheme_performance_data->borders_connectivity) as $item){
                        $connectivity[] = ["block_id"=>(int)$item["conn_block_id"], "panchayat_id"=>(int)$item["conn_panchayat_id"]];
                    }
                    $to_return["connectivity"] = $connectivity;
                }
                else{
                    $to_return["connectivity"] = [];
                }

                // $to_return["id"] = $scheme_performance_data->block_id;
                // $to_return["id"] = $scheme_performance_data->panchayat_id;
                // $to_return["id"] = $scheme_performance_data->attribute = unserialize($scheme_performance_data->attribute);
                // $to_return["id"] = $scheme_performance_data->coordinates = unserialize($scheme_performance_data->coordinates);
                // $to_return["id"] = $scheme_performance_data->gallery = unserialize($scheme_performance_data->gallery);
                // $to_return["id"] = $scheme_performance_data->borders_connectivity = unserialize($scheme_performance_data->borders_connectivity);
                $response = "success";
                $response_code = 200; // bad request
            }
            else{
                $response = "no_data_found";
                $response_code = 204; 
            }
        }
        else{
            $response = "no_id_received";
        }
        return response()->json(['response'=>$response, 'details'=>$to_return], $response_code);
    }


    public function store_scheme_performance_datas(Request $request){
        $received_datas = $request;
        $saved_id = null;
        $data_saved = false; 
        $year_id = $received_datas->year_id;
        $scheme_id = $received_datas->scheme_id;
        $block_id = $received_datas->block_id;
        $panchayat_id = $received_datas->panchayat_id;
        $subdivision_id = GeoStructure::where('geo_id', $panchayat_id)->first()->sd_id;

        // for attributes
        $scheme_data = SchemeStructure::where('scheme_id', $scheme_id)->first();
        $scheme_attributes = unserialize($scheme_data->attributes);
        $attribute = []; // to store
        // algo works before: deprecated
        // foreach($scheme_attributes as $scheme_attribute){
        //     // $scheme_attribute["id"];
        //     if(isset($data[$scheme_attribute["id"]])){
        //         $attribute[] = [$scheme_attribute["id"]=>$data[$scheme_attribute["id"]]];
        //     }
        // }
        $i = 0;
        $attr_index = ["attr_one","attr_two","attr_three","attr_four","attr_five","attr_six","attr_seven"];
        foreach($scheme_attributes as $scheme_attribute){
            // $scheme_attribute["id"];
            $attribute[] = [$scheme_attribute["id"]=>$received_datas[$attr_index[$i]]];
            $i++;
        }
        $attribute = serialize($attribute);

        $scheme_asset_id = $received_datas->scheme_asset_id;
        $status = $received_datas->status;
        $comments = $received_datas->comments;
        $connectivity_status = null;
        
        
        // storing datas
        $scheme_performance = new SchemePerformance;
        if($request->id){
            if(SchemePerformance::find($request->id)){
                $scheme_performance = SchemePerformance::find($request->id);
            }
        }
        $scheme_performance->year_id = (int)$year_id;
        $scheme_performance->scheme_id = (int)$scheme_id;
        $scheme_performance->block_id = (int)$block_id;
        $scheme_performance->panchayat_id = (int)$panchayat_id;
        $scheme_performance->subdivision_id = (int)$subdivision_id;

        $scheme_performance->attribute = $attribute;
        $scheme_performance->coordinates = null;
        $scheme_performance->gallery = null;

        $scheme_performance->scheme_asset_id = (int)$scheme_asset_id;
        $scheme_performance->status = (int)$status;
        $scheme_performance->comments = $comments;
        $scheme_performance->connectivity_status = $connectivity_status ?? 0;
        $scheme_performance->created_by = Auth::user()->id;
        $scheme_performance->updated_by = Auth::user()->id;
        if($scheme_performance->save()){
            $saved_id = $scheme_performance->scheme_performance_id;
            $data_saved = true;
        }

        // for block count
        $SchemePerformance = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('block_id', $block_id)->get();
        $incomplete_count = $complete_count = $total_count = 0;
        foreach ($SchemePerformance as $key_performance => $value_performance) {
            if ($value_performance['status'] == 0) {
                $incomplete_count = $incomplete_count + 1;
            }
            if ($value_performance['status'] == 1) {
                $complete_count = $complete_count + 1;
            }
            $total_count = $total_count + 1;
        }
        $scheme_block_performance_details = scheme_block_performance::where('scheme_id', $scheme_id)->where('block_id', $block_id)->where('year_id', $year_id)->first();
        if ($scheme_block_performance_details != "") {
            scheme_block_performance::where('scheme_block_performance_id', $scheme_block_performance_details->scheme_block_performance_id)->update(array('total_count' => $total_count, 'completed_count' => $complete_count, 'incomplete_count' => $incomplete_count));
            // $scheme_block_performance_details;
        } else {
            $scheme_block_performance = new scheme_block_performance();
            $scheme_block_performance->year_id = $year_id;
            $scheme_block_performance->scheme_id = $scheme_id;
            $scheme_block_performance->block_id = $block_id;
            $scheme_block_performance->total_count = $total_count;
            $scheme_block_performance->completed_count = $complete_count;
            $scheme_block_performance->incomplete_count = $incomplete_count;
            $scheme_block_performance->created_by = Auth::user()->id;
            $scheme_block_performance->update_by = Auth::user()->id;
            $scheme_block_performance->save();
        }

        if($data_saved){
            return response()->json(['success'=>'data_saved', 'last_saved_id'=>$saved_id], 201);
        }
        else{
            return response()->json(['error'=>'no_data_found', 'last_saved_id'=>null], 400);
        }
    }

    public function store_scheme_performance_gallery(Request $request){
        $response = "intialized";
        $response_code = 400; // bad request
        $gallery = [];
        $gallery_response = [];
        $images_list = [];
        $scheme_performance_id = "";
        if($request->id){
            if(SchemePerformance::find($request->id))
            {
                $scheme_performance_id = $request->id;
                // to delete
                if($request->to_delete){
                    $response = "saved_successfully";
                    $response_code = 201;
                    $to_delete_arr = explode(',', $request->to_delete);
                    $orig_gallery = unserialize(SchemePerformance::find($scheme_performance_id)->gallery);
                    $to_update_gallery = [];
                    foreach($to_delete_arr as $item){
                        $item = str_replace(url('')."/", "", trim($item));
                        $images_list[] = $item;
                        if(in_array($item, $orig_gallery)){
                            array_splice($orig_gallery, array_search($item, $orig_gallery), 1);
                        }
                    }
                    SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["gallery"=>serialize($orig_gallery)]);
                }

                if($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $upload_directory = "public/uploaded_documents/scheme_performance/";
                        $images_tmp_name = "scheme_performance-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                        $file->move("public/uploaded_documents/scheme_performance/", $images_tmp_name);   // move the file to desired folder
                        $gallery[] =  $upload_directory . $images_tmp_name;    // array push
                    }
                    $gallery_pre = unserialize(SchemePerformance::find($scheme_performance_id)->gallery);
                    if(!$gallery_pre || count($gallery_pre)==0){
                        $gallery_pre = [];
                    }
                    SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update([
                        "gallery"=>serialize(
                            array_merge(
                                $gallery_pre, $gallery
                            )
                        )
                    ]);
                    $response_code = 201;
                    $response = "saved_successfully";
                }
                else{
                    if(!$request->to_delete){
                        $response = 'images_not_received';
                    }
                }

                // for gallery listing, if id exist
                $i=1;
                foreach(unserialize(SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->first()->gallery) as $item){
                    $gallery_response[] = ["url"=>url('') ."/". $item];
                    $i++;
                }

            }
            else{
                $response = 'no_performance_data_found';
            }
        }
        else{
            $response = "id_not_received";
        }
        

        return response()->json(['response'=>$response, 'images'=>$gallery_response], $response_code);
    }

    public function store_scheme_performance_coordinates(Request $request){
        $received_datas = json_decode($request->getContent());
        $response_coordinates = [];
        $response = "intialized";
        $response_code = 400; // bad request

        if($request->id){
            if(SchemePerformance::find($request->id))
            {
                $scheme_performance_id = $request->id;
                $coordinates_received = $request->coordinates;
                $coordinates = [];
                if(count($coordinates_received)>0)
                {
                    $to_save = true;
                    for($i=0;$i<count($coordinates_received);$i++){
                        if(isset($coordinates_received[$i]["latitude"]) && isset($coordinates_received[$i]["longitude"])){
                            $latitude = substr($coordinates_received[$i]["latitude"], 0, 9);
                            $coordinates_received[$i]["latitude"] = (string)$latitude; // for return, change it to string
                            $longitude = substr($coordinates_received[$i]["longitude"], 0, 9);
                            $coordinates_received[$i]["longitude"] = (string)$longitude; // for return, change it to string
                            $coordinates[] = ["latitude"=>$latitude, "longitude"=>$longitude]; // to save, append
                        }
                        else{
                            $to_save = false;
                        }
                    }

                    if($to_save){
                        SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["coordinates"=>serialize($coordinates)]);
                        $response_code = 201;
                        $response = "saved_successfully";
                    }
                    else{
                        $response = "lat_long_null";
                    }
                }
                else{
                    // $response = 'coordinates_format_error';
                    SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["coordinates"=>serialize($coordinates)]);
                    $response_code = 201;
                    $response = "saved_successfully";
                }

                // to send response, if id exists
                $response_coordinates = unserialize(SchemePerformance::find($request->id)->coordinates);
            }
            else{
                $response = 'no_performance_data_found';
            }
        }
        else{
            $response = "id_not_received";
        }

        if(count($response_coordinates)==0){
            // $response_coordinates = new Stdobj;
            $response_coordinates = [
                (object)["latitude"=> "","longitude"=> ""]
            ];
        }
        return response()->json(['response'=>$response, 'coordinates'=>$response_coordinates], $response_code);
    }

    public function store_scheme_performance_connectivity(Request $request){
        $received_datas = json_decode($request->getContent());
        $response_connectivity  = [];
        $response = "intialized";
        $response_code = 400; // bad request
        $connectivity = [];
        if($request->id){
            if(SchemePerformance::find($request->id))
            {
                $scheme_performance_id = $request->id;
                $connectivity_received = $request->connectivity;

                if(count($connectivity_received)>0)
                {   
                    $to_save = true;
                    for($i=0;$i<count($connectivity_received);$i++){
                        if(isset($connectivity_received[$i]["block_id"]) && isset($connectivity_received[$i]["panchayat_id"]))
                        { 
                            $connectivity[] = ["conn_block_id"=>$connectivity_received[$i]["block_id"],"conn_panchayat_id"=>$connectivity_received[$i]["panchayat_id"]];
                        }
                        else{
                            $to_save = false;
                        }
                    }

                    if($to_save){
                        SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["borders_connectivity"=>serialize($connectivity)]);
                        $response_code = 201;
                        $response = "saved_successfully";
                    }
                    else{
                        $response = "block_panchayat_null";
                    }
                }
                else{
                    // $response = 'no_connectivity_details_received';
                    SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["borders_connectivity"=>serialize($connectivity)]);
                    $response_code = 201;
                    $response = "saved_successfully";
                }


                // to send response, if id exists
                foreach(unserialize(SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->first()->borders_connectivity) as $item){
                    $response_connectivity[] = ["block_id"=>$item["conn_block_id"], "panchayat_id"=>$item["conn_panchayat_id"]];
                }
            }
            else{
                $response = 'no_performance_data_found';
            }
        }
        else{
            $response = "id_not_received";
        }

        if(count($response_connectivity)==0){
            // $response_coordinates = new Stdobj;
            $response_connectivity = [
                (object)["block_id"=> "","panchayat_id"=> ""]
            ];
        }

        return response()->json(['response'=>$response, 'connectivity'=>$response_connectivity], $response_code);
    }

    public function delete_scheme_performance(Request $request){
        $response = "intialized";
        $response_code = 400; // bad request

        if($request->id){
            $scheme_performance_id = (int)$request->id;
            if(SchemePerformance::find($scheme_performance_id)){
                $SchemePerformance_record = SchemePerformance::where('scheme_performance_id', $scheme_performance_id)->delete();
                $response = "deleted_successfully";
                $response_code = 201;
            }
            else{
                $response = "no_data_found";
                $response_code = 204;
            }
        }
        else{
            $response = "id_not_received";
        }

        return response()->json(['response'=>$response], $response_code);
    }
}
