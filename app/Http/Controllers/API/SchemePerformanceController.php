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
    public function get_scheme_performabnce_datas(Request $request){
        $year_id = $request->year_id;
        $scheme_id = $request->scheme_id;
        $panchayat_id = $request->panchayat_id;

        $scheme_performance_datas = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->get();

        foreach($scheme_performance_datas as $scheme_performance_data){
            $scheme_performance_data->attribute = unserialize($scheme_performance_data->attribute);
            $scheme_performance_data->coordinates = unserialize($scheme_performance_data->coordinates);
            $scheme_performance_data->gallery = unserialize($scheme_performance_data->gallery);
            $scheme_performance_data->borders_connectivity = unserialize($scheme_performance_data->borders_connectivity);
        }


        // return after validate
        if(count($scheme_performance_datas)>0){
            return response()->json(['success' => $scheme_performance_datas], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'no_data_found'], 204);
        }
    }


    public function store_scheme_performance_datas(Request $request){
        $received_datas = $request;
        $saved_id = [];
        // return $received_datas["scheme_id"];
        // return [
        //     "scheme_id"=>1,
        //     "year_id"=>4,
        //     "block_id"=>9,
        //     "panchayat_id"=>128,
        //     "data"=>[
        //         [
        //             "5e205fbb46121"=> "value_1",
        //             "5e205fbb46125"=> "value_2",
        //             "latitude"=>[22.805908,22.804405],
        //             "longitude"=>[86.116202,86.119077],
        //             "gallery"=>["files","files"],
        //             "connectivity_status"=>["panchayat_id_1","panchayat_id_2"],
        //             "status"=>1
        //         ],
        //         [
        //             "5e205fbb46121"=> "value_1",
        //             "5e205fbb46125"=> "value_2",
        //             "latitude"=>[22.805908,22.804405],
        //             "longitude"=>[86.116202,86.119077],
        //             "gallery"=>["files","files"],
        //             "connectivity_status"=>["panchayat_id_1","panchayat_id_2"],
        //             "status"=>1
        //         ]
        //     ]
        // ];
        // getting datas
        /*
            year_id, block_id, panchayat_id, scheme_id, scheme_asset_id, attributes, coordinates, images 
        */
        $year_id = $received_datas->year_id;
        $scheme_id = $received_datas->scheme_id;
        $block_id = $received_datas->block_id;
        $panchayat_id = $received_datas->panchayat_id;
        $subdivision_id = GeoStructure::where('geo_id', $panchayat_id)->first()->sd_id;

        // for attributes
        $scheme_data = SchemeStructure::where('scheme_id', $scheme_id)->first();
        $scheme_attributes = unserialize($scheme_data->attributes);
        $datas = $received_datas["data"]; // all data
        $total_data = count($datas);
        $total_save_success = 0;
        foreach($datas as $data)
        {
            $attribute = []; // to store
            // algo works before: deprecated
            // foreach($scheme_attributes as $scheme_attribute){
            //     // $scheme_attribute["id"];
            //     if(isset($data[$scheme_attribute["id"]])){
            //         $attribute[] = [$scheme_attribute["id"]=>$data[$scheme_attribute["id"]]];
            //     }
            // }
            $i = 0;
            foreach($scheme_attributes as $scheme_attribute){
                // $scheme_attribute["id"];
                $attribute[] = [$scheme_attribute["id"]=>$data["attributes_value"][$i]];
                $i++;
            }
            $attribute = serialize($attribute);

            // for coordinates
            $coordinates = [];
            // for($i=0;$i<count($data["latitude"]);$i++){
            //     $coordinates[] = ["latitude"=>$data["latitude"][$i],"longitude"=>$data["longitude"][$i]];
            // }
            $coordinates = serialize($coordinates);

            // for gallery
            $gallery = [];
            // if($request->hasFile('gallery')) {
            //     foreach ($request->file('gallery') as $file) {
            //         $images_tmp_name = "scheme_performance-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
            //         $file->move("public/uploaded_documents/scheme_performance/", $images_tmp_name);   // move the file to desired folder
            //         $gallery[] =  $upload_directory . $images_tmp_name;    // array push
            //     }
            // }
            $gallery = serialize($gallery);    // serializing datas

            $scheme_asset_id = $data["scheme_asset_id"];
            $status = $data["status"];
            $comments = $data["comments"];
            $connectivity_status = null;
            
            
            // storing datas
            $scheme_performance = new SchemePerformance;
            $scheme_performance->year_id = $year_id;
            $scheme_performance->scheme_id = $scheme_id;
            $scheme_performance->block_id = $block_id;
            $scheme_performance->panchayat_id = $panchayat_id;
            $scheme_performance->subdivision_id = $subdivision_id;

            $scheme_performance->attribute = $attribute;
            $scheme_performance->coordinates = $coordinates;
            $scheme_performance->gallery = $gallery;

            $scheme_performance->scheme_asset_id = $scheme_asset_id;
            $scheme_performance->status = $status;
            $scheme_performance->comments = $comments;
            $scheme_performance->connectivity_status = $connectivity_status ?? 0;
            $scheme_performance->created_by = Auth::user()->id;
            $scheme_performance->updated_by = Auth::user()->id;
            if($scheme_performance->save()){
                $saved_id[] = ["id"=>$scheme_performance->scheme_performance_id];
                $total_save_success+=1;
            }
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

        if($total_data == $total_save_success){
            return response()->json(['success'=>'data_saved', 'last_saved_id'=>$saved_id], 201);
        }
        else{
            return response()->json(['error'=>'no_data_found', 'last_saved_id'=>null], 204);
        }
    }

    public function store_scheme_performance_gallery(Request $request){
        $gallery = [];
        $gallery_response = [];
        $scheme_performance_id = "";
        if($request->id){
            if(SchemePerformance::find($request->id))
            {
                $scheme_performance_id = $request->id;
                if($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $upload_directory = "public/uploaded_documents/scheme_performance/";
                        $images_tmp_name = "scheme_performance-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                        $file->move("public/uploaded_documents/scheme_performance/", $images_tmp_name);   // move the file to desired folder
                        $gallery[] =  $upload_directory . $images_tmp_name;    // array push
                        $gallery_response[] = url('') ."/". $upload_directory . $images_tmp_name;
                    }
                    SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["gallery"=>serialize($gallery)]);
                    return response()->json(['success'=>'saved_successfully', "images"=>$gallery_response], 200);
                }
                else{
                    return response()->json(['error'=>'failed'], 204);
                }
            }
            else{
                return response()->json(['success'=>'no_data_found'], 200);
            }
        }

        return response()->json(['success'=>'no_data_found'], 204);
    }

    public function store_scheme_performance_coordinates(Request $request){
        $coordinates = [];
        $coordinates_to_return = [];
        if($request->id){
            if(SchemePerformance::find($request->id))
            {
                $scheme_performance_id = $request->id;
                $coordinates_received = $request->coordinates;

                if(count($coordinates_received)>0)
                {
                    for($i=0;$i<count($coordinates_received);$i++){
                        $latitude = substr($coordinates_received[$i]["latitude"], 0, 9);
                        $longitude = substr($coordinates_received[$i]["longitude"], 0, 9);
                        $coordinates_to_return[] = ["latitude"=>(string)$latitude, "longitude"=>(string)$longitude];
                        $coordinates[] = ["latitude"=>$latitude, "longitude"=>$longitude];
                    }
                    SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["coordinates"=>serialize($coordinates)]);
                    return response()->json(['success'=>'saved_successfully', "coordinates"=>$coordinates_to_return], 200);
                }
                else{
                    return response()->json(['success'=>'no_coordinates_received'], 200);
                }
                
            }
            else{
                return response()->json(['success'=>'no_data_found'], 200);
            }
        }

        return response()->json(['success'=>'no_data_found'], 204);
    }

    public function store_scheme_performance_connectivity(Request $request){
        $connectivity = [];
        if($request->id){
            if(SchemePerformance::find($request->id))
            {
                $scheme_performance_id = $request->id;
                $connectivity_received = $request->connectivity;

                if(count($connectivity_received)>0)
                {   
                    for($i=0;$i<count($connectivity_received);$i++){
                        $connectivity[] = ["conn_block_id"=>$connectivity_received[$i]["block_id"],"conn_panchayat_id"=>$connectivity_received[$i]["panchayat_id"]];
                    }

                    SchemePerformance::where("scheme_performance_id", $scheme_performance_id)->update(["borders_connectivity"=>serialize($connectivity)]);
                    return response()->json(['success'=>'saved_successfully', "connectivity"=>$connectivity_received], 200);
                }
                else{
                    return response()->json(['success'=>'no_connectivity_received'], 200);
                }
                
            }
            else{
                return response()->json(['success'=>'no_data_found'], 200);
            }
        }

        return response()->json(['success'=>'no_data_found'], 204);
    }
}
