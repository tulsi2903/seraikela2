<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SchemeGeoTarget;
use App\GeoStructure;
use App\SchemeIndicator;
use App\Year;
use App\SchemeStructure;
use App\Group;
use App\SchemePerformance;
use App\SchemeGeoTarget2;
use App\SchemePerformance2;
use App\SchemeAsset;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\Print_;
use Session;
use App\scheme_block_performance;
use Auth;

class SchemePerformanceController extends Controller
{
    public function index(Request $request)
    {

        $geo_ids = [];
        if (session()->get('user_designation') == 1) // dc
        {
            $geo_ids = GeoStructure::where('level_id', 3)->pluck('geo_id'); // panchayat_ids
        } else if (session()->get('user_designation') == 2) { // sdo
            $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->where('level_id', '2')->first();
            if ($subdivision_id_tmp) {
                $geo_ids = GeoStructure::where('sd_id', $subdivision_id_tmp->geo_id)->pluck('geo_id'); // panchayat_ids
            }
        } else if (session()->get('user_designation') == 3) { // bdo
            $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            // return $block_id_tmp;
            if ($block_id_tmp) {
                $geo_ids = GeoStructure::where('officer_id', Auth::user()->id)->pluck('geo_id'); // decide rows (panchayat)
            }
        } else if (session()->get('user_designation') == 4) { //po
            $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if ($panchayat_id_tmp) {
                $geo_ids = GeoStructure::where('geo_id', $panchayat_id_tmp->bl_id)->pluck('geo_id'); // decide rows (panchayat)
            }
        }

        // $datas = SchemePerformance::leftJoin('scheme_structure', 'scheme_geo_target.scheme_id', '=', 'scheme_structure.scheme_id')
        //             ->leftJoin('scheme_geo_target','scheme-performance.scheme_geo_target_id','=','scheme_geo_target.scheme_geo_target_id')
        //             ->leftJoin('geo_structure', 'scheme_geo_target.geo_id', '=', 'geo_structure.geo_id')
        //             ->leftJoin('scheme_indicator','scheme_geo_target.indicator_id','=','scheme_indicator.indicator_id')
        //             ->leftJoin('year','scheme_geo_target.year_id','=','year.year_id')
        //             ->leftJoin('scheme_group','scheme_geo_target.group_id','=','scheme_group.scheme_group_id')
        //             ->select('scheme_geo_target.*','scheme_structure.scheme_name','scheme_structure.scheme_short_name','geo_structure.geo_name','geo_structure.level_id','geo_structure.parent_id','scheme_indicator.indicator_name','year.year_value','scheme_group.scheme_group_name')
        //             ->orderBy('scheme_geo_target.scheme_geo_target_id','desc')
        //             ->get();

        //              $i=0;
        // foreach($datas as $data){
        //     if($data->level_id==4){
        //         $tmp = GeoStructure::find($data->parent_id);
        //         if($tmp->geo_name)
        //         { 
        //             $datas[$i]->bl_name = $tmp->geo_name; 
        //         }
        //         else{
        //         $datas[$i]->bl_name = "NA";
        //         }
        //         }
        //         else{
        //             $datas[$i]->bl_name = "NA";
        //         }
        //         $i++;
        // }

        // recieving data
        // if($request->id)
        // {
        //     $scheme_geo_target_data = SchemeGeoTarget::find($request->id);
        //     $scheme_data = SchemeStructure::find($scheme_geo_target_data->scheme_id);
        //     $scheme_asset_data = SchemeAsset::find($scheme_data->scheme_asset_id);
        //     $panchayat_data = GeoStructure::find($scheme_geo_target_data->panchayat_id);
        //     $block_data = GeoStructure::find($panchayat_data->bl_id);
        //     $scheme_performance_datas = SchemePerformance::where('scheme_id',$scheme_geo_target_data->scheme_id)
        //                                                     ->where('year_id',$scheme_geo_target_data->year_id)
        //                                                     ->where('panchayat_id',$scheme_geo_target_data->panchayat_id)
        //                                                     ->get();
        // }
        // else{
        //     return redirect('scheme-geo-target');
        // }

        $scheme_datas = SchemeStructure::select('scheme_id', 'scheme_name', 'scheme_short_name')->where('status', 1)->orderBy('scheme_id', 'DESC')->get(); // only independent scheme (scheme_is == 1)
        $year_datas = Year::select('year_id', 'year_value')->where('status', 1)->orderBy('year_value', 'asc')->get();
        $block_datas = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->whereIn('geo_id', $geo_ids)->where('level_id', '=', '3')->get();

        return view('scheme-performance.index')->with(compact('scheme_datas', 'year_datas', 'block_datas'));
    }


    public function add_datas(Request $request)
    {
        if (!$request->scheme_id || !$request->year_id || !$request->panchayat_id || !$request->block_id) {
            return redirect("scheme-performance");
        }

        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $block_id = $request->block_id; // geo_id in geo_structure & level = 3
        $panchayat_id = $request->panchayat_id; // geo_id in geo_structure & level = 4

        // retrieving data to send frontend
        $scheme_data = SchemeStructure::find($scheme_id);
        $year_data = Year::find($year_id);
        $scheme_asset_data = SchemeAsset::find($scheme_data->scheme_asset_id);
        $panchayat_data = GeoStructure::find($panchayat_id);
        $block_data = GeoStructure::find($block_id);
        $scheme_performance_datas = SchemePerformance::where('scheme_id', $scheme_id)
            ->where('year_id', $year_id)
            ->where('panchayat_id', $panchayat_id)
            ->get();

        // echo "<pre>";
        // print_r(unserialize($scheme_asset_data->attribute));
        // exit;

        return view('scheme-performance.add-datas')->with(compact('scheme_data', 'year_data', 'scheme_asset_data', 'panchayat_data', 'block_data', 'scheme_performance_data'));
    }

    public function get_panchayat_datas(Request $request)
    {
        if (session()->get('user_designation') == 4) { //po
            $datas = GeoStructure::where('bl_id', $request->block_id)->where('officer_id', Auth::user()->id)->get();
        } else {
            $datas = GeoStructure::where('bl_id', $request->block_id)->get();
        }
        return $datas;
    }

    public function get_all_datas(Request $request)
    {
        // received datas
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $panchayat_id = $request->panchayat_id;
        $SchemePerformance_details = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->get()->toArray();
        // print_r($SchemePerformance_details);
        $to_append_tbody = '';
        $total_count_record = "";
        $checkboxIdentifier = 0;
        if (count($SchemePerformance_details != 0)) {
            foreach ($SchemePerformance_details as $key_SchemePerformance => $value_SchemePerformance) {

                $to_append_tbody .= '<tr>';
                $SchemePerformance_attributes = unserialize($value_SchemePerformance['attribute']);
                // print_r($SchemePerformance_attributes);
                $scheme_data = SchemeStructure::find($value_SchemePerformance['scheme_id']);
                $scheme_asset_data = SchemeAsset::get();
                $to_append_tbody .= '<input type="hidden" name="scheme_performance_id[]" value="' . $value_SchemePerformance['scheme_performance_id'] . '">';
                if ($scheme_data->scheme_is == 2) {
                    $to_append_tbody .= '<td> <select name="assest_name[]" class="form-control status_readonly" required>';
                    if ($value_SchemePerformance['status'] == 1 || $value_SchemePerformance['status'] == 3) {
                        if ($value_SchemePerformance['scheme_asset_id'] != "") {
                        foreach ($scheme_asset_data as $key_asset => $value_assest) {
                            if ($value_SchemePerformance['scheme_asset_id'] == $value_assest["scheme_asset_id"]) {
                                $to_append_tbody .= "<option  value=\"" . $value_assest["scheme_asset_id"] . "\" selected>" . $value_assest["scheme_asset_name"] . "  </option>";
                            }
                            // $to_append_tbody .= "<option  value=\"" . $value_assest["scheme_asset_id"] . "\">" . $value_assest["scheme_asset_name"] . "</option>";
                        }
                    }
                    }
                    else
                    {
                    if ($value_SchemePerformance['scheme_asset_id'] != "") {
                        foreach ($scheme_asset_data as $key_asset => $value_assest) {
                            if ($value_SchemePerformance['scheme_asset_id'] == $value_assest["scheme_asset_id"]) {
                                $to_append_tbody .= "<option  value=\"" . $value_assest["scheme_asset_id"] . "\" selected>" . $value_assest["scheme_asset_name"] . "  </option>";
                            }
                            $to_append_tbody .= "<option  value=\"" . $value_assest["scheme_asset_id"] . "\">" . $value_assest["scheme_asset_name"] . "</option>";
                        }
                    } else {
                        $to_append_tbody .= "<option  value=''>--Select--</option>";
                        foreach ($scheme_asset_data as $key_asset => $value_assest) {
                            $to_append_tbody .= "<option  value=\"" . $value_assest["scheme_asset_id"] . "\">" . $value_assest["scheme_asset_name"] . "</option>";
                        }
                    }
                    }
                    $to_append_tbody .= '</select>';
                }
                
                $attributes  = unserialize($scheme_data->attributes);
                foreach ($attributes as $key_att => $attribute) {
                    $to_append_tbody .= '<td><input type="text" name="' . $attribute['id'] . '[]" class=" status_readonly form-control" value="' . @$SchemePerformance_attributes[$key_att][$attribute['id']] . '" placeholder="' . $attribute['name'] . '"></td>';
                }
                // echo $value_SchemePerformance['status'];
                $to_append_tbody .= '<td><select name="status[]"  onchange="checkStatusOld(this,' . $value_SchemePerformance['scheme_performance_id'] . ')"  class="form-control status_readonly">';
                if ($value_SchemePerformance['status'] != "") {
                    if ($value_SchemePerformance['status'] == 0) {
                        $to_append_tbody .= '<option value="1">Completed</option>';
                        $to_append_tbody .= '<option value="0" selected>Inprogress</option>';
                    }
                    if ($value_SchemePerformance['status'] == 1) {
                        $to_append_tbody .= '<option value="1" selected >Completed</option>';
                    }
                    if ($value_SchemePerformance['status'] == 2) {
                        $to_append_tbody .= '<option value="2" selected >Sanctioned</option>';
                        $to_append_tbody .= '<option value="0">Inprogress</option>';
                        $to_append_tbody .= '<option value="3">Cancel</option>';
                    }
                    if ($value_SchemePerformance['status'] == 3) {
                        $to_append_tbody .= '<option value="3" selected>Cancel</option>';
                    }
                } else {
                    $to_append_tbody .= '<option value="2" selected >Sanctioned</option>';
                }
                $to_append_tbody .= '</select></td>';
                 /*Spans Across Borders */
                 if($scheme_data->spans_across_borders==1)
                 {
                     $to_append_tbody .='<td> 
                                     <input type="checkbox" name="connectivity_details[]" value="x'.$checkboxIdentifier.'" onclick="showbutton(this.value,this)" '. ($value_SchemePerformance["connectivity_status"]==1 ? "checked" : "") .'>
                                     </td>';
                 }
                 /* End Spans Across Borders */
                $to_append_tbody .= '<td><input type="text" name="comments[]" value="' . $value_SchemePerformance['comments'] . '" class="form-control status_readonly" placeholder="comments"></td>';
                // for gallery & coordinates
                if ($value_SchemePerformance['status'] == 3) {
                    $to_append_tbody .= '<td><i class="fas fa-plus"></i>Images';
                    $to_append_tbody .= '<br/><i class="fas fa-plus"></i>Coordinates';
                    // $to_append_tbody .= '</td>';
                } else {
                    $to_append_tbody .= '<td><a  onclick="update_image(' . $value_SchemePerformance['scheme_performance_id'] . ');" href="javascript:void()"> <i class="fas fa-plus"></i>Images</a>';
                    // $to_append_tbody.='<td><button type="button" class="btn btn-danger btn-xs" onclick="delete_row(this)"><i class="fas fa-trash-alt"></i></button></td>';
                    $to_append_tbody .= '<br/><a  onclick="coordinates_details(' . $value_SchemePerformance['scheme_performance_id'] . ');" href="javascript:void();"><i class="fas fa-plus"></i>Coordinates</a>';
                    // $to_append_tbody .= '</td>';
                }
                /*Spans Across Borders */
                if($value_SchemePerformance["connectivity_status"] == 0)
                $to_append_tbody .= '<br/><a  onclick="border_connectivity_details(' . $value_SchemePerformance['scheme_performance_id'] . ');" href="javascript:void();" style="display:none" class="showconnectivity"><i class="fas fa-plus"></i>Connectivity</a>';
                else
                $to_append_tbody .= '<br/><a  onclick="border_connectivity_details(' . $value_SchemePerformance['scheme_performance_id'] . ');" href="javascript:void();" class="showconnectivity"><i class="fas fa-plus"></i>Connectivity</a>';
                /* End Spans Across Borders */
                $to_append_tbody .= '</td>';
                $to_append_tbody .= '<td><button type="button" class="btn btn-danger btn-xs" onclick="delete_row(this,' . $value_SchemePerformance['scheme_performance_id'] . ')"><i class="fas fa-trash-alt"></i></button></td>';
                $to_append_tbody .= '</tr>';
                $total_count_record = $key_SchemePerformance + 1;
                $checkboxIdentifier++;
            }
        }
        // exit;
        /*
        to send
        scheme_data
        scheme_asset_data
        scheme_performance_data
        add_new_input
        */
        $to_append_thead = '<tr>';
        $to_append_row = '<tr>';

        $scheme_data = SchemeStructure::find($scheme_id);
        $scheme_asset_data = SchemeAsset::get();

        // for attributes
        if ($scheme_data->scheme_is == 2) {
            $to_append_thead .= '<th>Select Assest</th>';
        }
        $to_append_row .= '<input type="hidden" name="scheme_performance_id[]" value="new_scheme_performance"> ';

        if ($scheme_data->scheme_is == 2) {
            $to_append_row .= '<td><select name="assest_name[]" class="form-control" required>';
            $to_append_row .= "<option  value=''>--Select--</option>";
            foreach ($scheme_asset_data as $key_asset => $value_assest) {
                $to_append_row .= "<option value=\"" . $value_assest["scheme_asset_id"] . "\">" . $value_assest["scheme_asset_name"] . "</option>";
            }
            $to_append_row .= '</select>';
        }
        $attributes  = unserialize($scheme_data->attributes);
        // return $attributes;
        foreach ($attributes as $attribute) {
            $to_append_thead .= '<th>' . $attribute["name"] . '</th>';
            $to_append_row .= '<td><input type="text" name="' . $attribute['id'] . '[]" class="form-control" placeholder="' . $attribute['name'] . '"></td>';
        }
        // return $to_append_row;




        $to_append_thead .= '<th>Status</th>';
        $to_append_row .= '<td>
                            <select name="status[]" class="form-control" required>
                            <option value="" >--Select--</option>
                            <option value="2" selected>Sanctioned</option>
                            </select>
                        </td>';

        /*Spans Across Borders */
        if($scheme_data->spans_across_borders==1)
        {
            $to_append_thead .= '<th>Connectivity Details</th>';
            $to_append_row .='<td> <input type="checkbox" name="connectivity_details[]" value="x'.$checkboxIdentifier.'"></td>';
            
            // $to_append_row .='<td></td>';
        }
        /*Spans Across Borders */

        $to_append_thead .= '<th>Comments</th>';
        $to_append_row .= '<td><input type="text" name="comments[]" class="form-control" placeholder="comments"></td>';
        $to_append_thead .= '<th>Others</th>';
        // for gallery & coordinates
        $to_append_row .= '<td><i class="fas fa-plus"></i>Images';
        // for coordinates
        // if($scheme_data->geo_related==1){
        $to_append_row .= '<br/><i class="fas fa-plus"></i>Coordinates';
        // }
        $to_append_row .= '</td>';

        $to_append_thead .= '<th>Actions</th>';
        $to_append_row .= '<td><button type="button" class="btn btn-danger btn-xs" onclick="delete_row(this)"><i class="fas fa-trash-alt"></i></button></td>';
        $to_append_thead .= '</tr>';
        $to_append_row .= '</tr>';

        return ['to_append_thead' => $to_append_thead, 'to_append_row' => $to_append_row, 'to_append_tbody' => $to_append_tbody, 'total_count_record' => $total_count_record];
    }
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->connectivity_details);
        // return $request;
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $panchayat_id = $request->panchayat_id;
        $block_id = GeoStructure::where('geo_id', $panchayat_id)->first()->bl_id;
        $subdivision_id = GeoStructure::where('geo_id', $panchayat_id)->first()->sd_id;
        $scheme_data = SchemeStructure::where('scheme_id', $scheme_id)->first();
        $attributes = unserialize($scheme_data->attributes);
        $form_request_id = array();
        $form_attributes_data_array = array();
        // return $attributes;
        foreach ($attributes as $key_id => $value_id) {
            if ($request->input($value_id['id']) != "") {
                foreach ($request->input($value_id['id']) as $key => $value) {
                    $form_request_id[$key][$key_id][$value_id['id']] = $value;
                }
            }
        }
        $tmp_array = array();
        $delete_check_array = array();
        $countTempData = 0;
        foreach ($form_request_id as $key_request => $value_request) {
            $SchemePerformance_get = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->get()->toArray();
            foreach ($SchemePerformance_get as $tmp) {
                array_push($tmp_array, $tmp['scheme_performance_id']);
            }
            if ($request->scheme_performance_id[$key_request] != 'new_scheme_performance') {
                if (in_array($request->scheme_performance_id[$key_request], $tmp_array)) {
                    $delete_check_array[] = $request->scheme_performance_id[$key_request];
                    $scheme_performance = SchemePerformance::find($request->scheme_performance_id[$key_request]);
                    $scheme_performance->scheme_id = $scheme_id;
                    $scheme_performance->year_id = $year_id;
                    $scheme_performance->subdivision_id = $subdivision_id;
                    $scheme_performance->block_id = $block_id;
                     /*  Spans Across Borders */
                    if(in_array('x'.$key_request, $request->connectivity_details)){
                        $scheme_performance->connectivity_status = 1;
                    } else {
                        $scheme_performance->connectivity_status = 0;
                        $scheme_performance->borders_connectivity = null;
                    }
                    /* End Spans Across Borders */
                    $scheme_performance->panchayat_id = $panchayat_id;
                    $scheme_performance->attribute = serialize($value_request) ?? "";
                    $scheme_performance->status = $request->status[$key_request];
                    $scheme_performance->scheme_asset_id = $request->assest_name[$key_request] ?? $scheme_data->scheme_asset_id;
                    $scheme_performance->comments = $request->comments[$key_request] ?? "";
                    $scheme_performance->created_by = Auth::user()->id;
                    $scheme_performance->updated_by = Auth::user()->id;
                    $scheme_performance->save();
                }
            } else {
                $countTempData++;
                $scheme_performance = new SchemePerformance;
                $scheme_performance->scheme_id = $scheme_id;
                $scheme_performance->year_id = $year_id;
                $scheme_performance->subdivision_id = $subdivision_id;
                $scheme_performance->block_id = $block_id;
                $scheme_performance->panchayat_id = $panchayat_id;
                $scheme_performance->attribute = serialize($value_request) ?? "";
                $scheme_performance->status = $request->status[$key_request];
                /* Spans Across Borders */
                if(in_array('x'.$key_request, $request->connectivity_details)){
                    $scheme_performance->connectivity_status = 1;
                } else {
                    $scheme_performance->connectivity_status = 0;
                }
                /* End Spans Across Borders */
                $scheme_performance->scheme_asset_id = $request->assest_name[$key_request] ?? $scheme_data->scheme_asset_id;
                $scheme_performance->comments = $request->comments[$key_request] ?? "";
                $scheme_performance->created_by = Auth::user()->id;
                $scheme_performance->updated_by = Auth::user()->id;
                $scheme_performance->save();
            }
        }
        if ($request->to_delete != "") {
            $for_delete_record = explode(',', rtrim($request->to_delete, ','));
            $SchemePerformance_record = SchemePerformance::whereIn('scheme_performance_id', $for_delete_record)->delete();
        }
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
            // return $scheme_block_performance;
        }
        // return $scheme_block_performance_details;
        
        if ($countTempData == 0) {
            return ["message" => "error"];
        } else {
            return ["message" => "success"];
        }
        
        session()->put('alert-class', 'alert-success');
        session()->put('alert-content', 'New performance data(s) has been saved successfully!');
        // return back();
        // exit;
    }

    public function viewimport(Request $request)
    {
        if (!$request->scheme_id) {
            return redirect("scheme-performance");
        }

        $scheme_id = $request->scheme_id;
        return view('scheme-performance.importExcel')->with(compact('scheme_id'));
    }

    public function Import_from_Excel(Request $request)
    {
        $geo_names = array();
        if (session()->get('user_designation') == 1) // dc
        {
            $geo_names = GeoStructure::where('level_id', 4)->pluck('geo_name'); // panchayat_ids
            $geo_block_names =  GeoStructure::where('level_id', 3)->pluck('geo_name');
        } else if (session()->get('user_designation') == 2) { // sdo
            $subdivision_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if ($subdivision_id_tmp) {
                $geo_names = GeoStructure::where('sd_id', $subdivision_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_name'); // panchayat_ids
            }
            $subdivision_id_tmp_block = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if ($subdivision_id_tmp_block) {
                $geo_block_names = GeoStructure::where('sd_id', $subdivision_id_tmp_block->geo_id)->where('level_id', '3')->pluck('geo_name'); // panchayat_ids
            }
        } else if (session()->get('user_designation') == 3) { // bdo
            $block_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if ($block_id_tmp) {
                $geo_names = GeoStructure::where('bl_id', $block_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_name'); // decide rows (panchayat)
            }
            $geo_block_names = GeoStructure::where('officer_id', Auth::user()->id)->pluck('geo_name');
        } else if (session()->get('user_designation') == 4) { //po
            $panchayat_id_tmp = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if ($panchayat_id_tmp) {
                $geo_names = GeoStructure::where('geo_id', $panchayat_id_tmp->geo_id)->where('level_id', '4')->pluck('geo_name'); // decide rows (panchayat)
            }
            $panchayat_id_tmp_block = GeoStructure::where('officer_id', Auth::user()->id)->first();
            if ($panchayat_id_tmp_block) {
                $geo_block_names = GeoStructure::where('geo_id', $panchayat_id_tmp_block->bl_id)->pluck('geo_name'); // decide rows (panchayat)
            }
        }

        $geo_names = (array) $geo_names;
        $geo_block_names = (array) $geo_block_names;
        $geo_names_array = array();
        $geo_block_names_array = array();
        foreach ($geo_names as $key_geo => $value_geo) {
            $geo_names_array = $value_geo;
        }
        foreach ($geo_block_names as $key_block => $value_block) {
            $geo_block_names_array = $value_block;
        }
        echo "<pre>";
        print_r($geo_block_names_array);
        exit;

        $scheme_datas = SchemeStructure::where('scheme_id', $request->scheme_id)->first(); /* get scheme atributes */
        $unserialDatas = unserialize($scheme_datas->attributes);
        $tableHeadingsAndAtributes = array();
        $schemeAtributes = array();
        $tableHeadingsAndAtributes = array(
            'sno.',
            'block_name',
            'panchayat_name',
            'work_start_fin_year',
            'status'
        );
        foreach ($unserialDatas as $key_un => $value_un) {
            array_push($tableHeadingsAndAtributes, strtolower(str_replace(" ", "_", $value_un['name'])));
            $schemeAtributes[$value_un['id']] =  strtolower(str_replace(" ", "_", $value_un['name']));
        }
        // array_push($tableHeadingsAndAtributes, 0);
        if ($_FILES['excelcsv']['tmp_name']) {
            $readExcel = \Excel::selectSheets('Scheme-Format')->load($_FILES['excelcsv']['tmp_name'], function ($reader) { })->get()->toArray();
            $readExcelHeader = \Excel::selectSheets('Scheme-Format')->load($_FILES['excelcsv']['tmp_name'])->get();
            if (count($readExcelHeader) != 0) {
                $excelSheetHeadings = $readExcelHeader->first()->keys()->toArray(); /* this is for excel sheet heading */
            }

            if (count($readExcel) != 0) {
                if (count($readExcel) <= 250) {
                    sort($tableHeadingsAndAtributes);
                    sort($excelSheetHeadings);
                    $unserializedAtributesData = array();
                    // print_r($excelSheetHeadings);
                    // echo "<br>";
                    date_default_timezone_set('Asia/Kolkata');

                    // print_r($tableHeadingsAndAtributes);
                    // exit;
                    /* validation for matching of headings */
                    if ($tableHeadingsAndAtributes == $excelSheetHeadings) { /* Check for missmatch headings*/

                        foreach ($readExcel as $excel_key => $excel_value) {
                            foreach ($excel_value as $key => $value) {
                                foreach ($schemeAtributes as $attribute_key => $attribute_value) {

                                    if ($attribute_value === $key) {
                                        $unserializedAtributesData[$excel_key][][$attribute_key] = $value;
                                    }
                                }
                            }
                            // $serializationAttributes[] = $unserializedAtributesData;
                            // $unserializedAtributesData = [];
                        }

                        $filename = "SchemePerformance-errorLog" . session()->get('user_id') . ".txt";   /* error file name */
                        // session()->put('filename',$filename);
                        $myfile = fopen($filename, "w"); /* open error file name by using fopen function */
                        $noOfSuccess = 0;
                        $noOfFails = 0;
                        $ErrorTxt = "";
                        foreach ($readExcel as $key => $row) { /* Insert Data By using for each one by one */
                            $block_name =  ucwords($row['block_name']);
                            $panchayat_name =   ucwords($row['panchayat_name']);
                            $status =   ucwords($row['status']);

                            $fetch_block_id = GeoStructure::where('geo_name', $block_name)->where('level_id', '3')->value('geo_id'); /* for block ID */
                            $fetch_panchayat_id = GeoStructure::where('geo_name', $panchayat_name)->where('level_id', '4')->value('geo_id'); /* for Panchayat ID */
                            $fetch_subdivision_id = GeoStructure::where('geo_id', $fetch_block_id)->value('sd_id'); /* for subdivision_id ID */
                            $fetch_year_id = Year::where('year_value', $row['work_start_fin_year'])->value('year_id'); /* for Year ID */

                            /* if those id avilable then insert data on the base */
                            if ($row['sno.'] != null && $fetch_block_id != null && $fetch_panchayat_id != null && $fetch_year_id != null && $fetch_subdivision_id != null && in_array($panchayat_name, $geo_names_array) && in_array($block_name, $geo_block_names_array)) {
                                $noOfSuccess++;
                                $flag = 0;
                                $scheme_performance_id = "";
                                $scheme_performance_details = SchemePerformance::get()->toArray();
                                foreach ($scheme_performance_details as $key_edit => $value_edit) {
                                    $add_atributes = serialize($unserializedAtributesData[$key]);
                                    // echo $key_edit;
                                    if ($value_edit['attribute'] == $add_atributes) {
                                        $flag = 1;
                                        $scheme_performance_id = $value_edit['scheme_performance_id'];
                                    }
                                }
                                if ($flag == 1) {
                                    $edit_scheme_performance = SchemePerformance::where('scheme_performance_id', $scheme_performance_id)->update(array('status' => 1));
                                } else {
                                    $scheme_performance = new SchemePerformance;
                                    $scheme_performance->year_id = $fetch_year_id;
                                    $scheme_performance->scheme_id = $request->scheme_id;
                                    $scheme_performance->block_id = $fetch_block_id;
                                    $scheme_performance->panchayat_id = $fetch_panchayat_id;
                                    $scheme_performance->subdivision_id = $fetch_subdivision_id;
                                    $scheme_performance->attribute = serialize($unserializedAtributesData[$key]);
                                    if (strtolower($status) == strtolower("Completed")) {
                                        $scheme_performance->status = 1;
                                    } elseif (strtolower($status) == strtolower("inprogress")) {
                                        $scheme_performance->status = 0;
                                    } elseif (strtolower($status) != strtolower("inprogress") && strtolower($status) != strtolower("Completed")) {
                                        $scheme_performance->status = "";
                                    }
                                    $scheme_performance->created_by = Session::get('user_id');
                                    $scheme_performance->save();
                                }
                            } else {  /* Else find id and error write on the notepad */
                                $noOfFails++;
                                if ($row['sno.'] != null) {
                                    if ($fetch_block_id == null && $fetch_panchayat_id != null) {
                                        $ErrorTxt .= " ON row sno. " . $row['sno.'] . " Block Not Found \n";
                                    }
                                    if ($fetch_panchayat_id == null && $fetch_block_id != null) {
                                        $ErrorTxt .= " ON row sno. " . $row['sno.'] . " Panchayat Not Found \n";
                                    }
                                    if ($fetch_panchayat_id == null && $fetch_block_id == null) {
                                        $ErrorTxt .= " ON row sno. " . $row['sno.'] . " Both Panchayat And Block Not Found \n";
                                    }
                                    if ($fetch_subdivision_id == null || $fetch_year_id == null) {
                                        $ErrorTxt .= " ON row sno. " . $row['sno.'] . "Both Subdivision And Year Not Found \n";
                                    }
                                    if (in_array($panchayat_name, $geo_names_array) == false) {
                                        $ErrorTxt .= " On SNo. " . $row['sno.'] . " Wrong panchayat Name \n";
                                    }
                                    if (in_array($block_name, $geo_block_names_array) == false) {
                                        $ErrorTxt .= " On SNo. " . $row['sno.'] . " Wrong block Name \n";
                                    }
                                }
                            }
                        }
                        $SchemePerformance_forblock = SchemePerformance::get();
                        foreach ($SchemePerformance_forblock as $key_get => $value_get) {
                            $SchemePerformance = SchemePerformance::where('scheme_id', $value_get['scheme_id'])->where('year_id', $value_get['year_id'])->where('block_id', $value_get['block_id'])->get();
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
                            $scheme_block_performance_details = scheme_block_performance::where('scheme_id', $value_get['scheme_id'])->where('block_id', $value_get['block_id'])->where('year_id', $value_get['year_id'])->first();
                            if ($scheme_block_performance_details != "") {
                                scheme_block_performance::where('scheme_block_performance_id', $scheme_block_performance_details->scheme_block_performance_id)->update(array('total_count' => $total_count, 'completed_count' => $complete_count, 'incomplete_count' => $incomplete_count));
                                // $scheme_block_performance_details;
                            } else {
                                $scheme_block_performance = new scheme_block_performance();
                                $scheme_block_performance->year_id = $value_get['year_id'];
                                $scheme_block_performance->scheme_id = $value_get['scheme_id'];
                                $scheme_block_performance->block_id = $value_get['block_id'];
                                $scheme_block_performance->total_count = $total_count;
                                $scheme_block_performance->completed_count = $complete_count;
                                $scheme_block_performance->incomplete_count = $incomplete_count;
                                $scheme_block_performance->created_by = Auth::user()->id;
                                $scheme_block_performance->update_by = Auth::user()->id;
                                $scheme_block_performance->save();
                                // return $scheme_block_performance;
                            }
                        }

                        $txt = "District Resource and Scheme Management\n";
                        $txt .= "----------------------------------------------------------------------------------------------------------------------------------\n";
                        $txt .= "DATE: " . date('d/m/Y h:i A') . "\n";
                        $txt .= "TOTAL RECORD COUNT: " . count($readExcel) . "\n";
                        $txt .= "TOTAL SUCCESS COUNT: " . $noOfSuccess . "\n";
                        $txt .= "TOTAL FAIL COUNT: " . $noOfFails . "\n";
                        // $txt .= "USER NAME: ". $getUserName->first_name." ". $getUserName->middle_name." ". $getUserName->last_name." \n";
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
                            session()->put('alert-content', 'Scheme details has been saved');
                            return back();
                        } else { //Else download the error notepad file
                            // header("Cache-Control: public");
                            // header("Content-Description: File Transfer");
                            // header("Content-Length: " . filesize("$filename") . ";");
                            // header("Content-Disposition: attachment; filename=$filename");
                            // header("Content-Type: application/octet-stream; ");
                            // header("Content-Transfer-Encoding: binary");
                            $this->saveFile($filename, file_get_contents($filename));
                            session()->put('currentdate', date('d/m/Y h:i:sa'));
                            session()->put('totalCount', count($readExcel));
                            session()->put('totalsuccess', $noOfSuccess);
                            session()->put('totalfail', $noOfFails);
                            session()->put('scheme_name', $scheme_datas['scheme_name']);

                            // readfile($filename);
                            session()->put('alert-class', 'alert-success');
                            // session()->put('alert-content', 'Scheme details has been saved');
                            session()->put('to-download', 'yes');
                            // session()->put('text',$check);
                            return redirect('import/scheme');
                        }
                    } else { //for error message
                        session()->put('alert-class', 'alert-danger');
                        session()->put('alert-content', 'Your Excel Format Missmatch From Our Format..Please Download Our Excel Format..');
                        return back();
                    }
                } else {
                    session()->put('alert-class', 'alert-danger');
                    session()->put('alert-content', 'You Have Excited Maximum No. of Row at a Time');
                    return back();
                }
            } else {
                session()->put('alert-class', 'alert-danger');
                session()->put('alert-content', 'Please Fill At Least One Row in Excel Sheet');
                return back();
            }
        }
    }
    public function saveFile($filename, $filecontent)
    {
        if (strlen($filename) > 0) {
            $folderPath = 'public/uploaded_documents/error_log';
            if (!file_exists($folderPath)) {
                mkdir($folderPath);
            }
            $file = @fopen($folderPath . DIRECTORY_SEPARATOR . $filename, "w");
            if ($file != false) {
                fwrite($file, $filecontent);
                fclose($file);
                return 1;
            }
            return -2;
        }
        return -1;
    }

    public function download_error_log()
    {
        # code...
        $filename = "SchemePerformance-errorLog" . session()->get('user_id') . ".txt";   /* error file name */

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize("$filename") . ";");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/octet-stream; ");
        header("Content-Transfer-Encoding: binary");
        readfile($filename);
        exit;
    }
    public function downloadFormat(Request $request)
    {
        # code...
        $scheme_datas = SchemeStructure::where('scheme_id', $request->scheme_id)->first();
        $unserialDatas = unserialize($scheme_datas->attributes);
        // $data = array();
        $data = array(
            'SNo.',
            'Block Name',
            'Panchayat Name',
            'Work Start Fin Year'
        );
        foreach ($unserialDatas as $key_un => $value_un) {
            array_push($data, $value_un['name']);
        }
        array_push($data, 'Status');
        $asset_data = SchemeAsset::get();
        \Excel::create($scheme_datas->scheme_short_name . ' Scheme-Format-' . date("d-m-Y"), function ($excel) use ($asset_data, $data) {

            // Set the title
            $excel->setTitle('Scheme-Format');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Scheme-Format', function ($sheet) use ($data) {
                // $sheet->freezePane('A3');
                // $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                // $sheet->setColumnFormat(array('I1' => '@'));
            });

            $excel->sheet('Second sheet', function ($sheet)  use ($asset_data) {
                foreach ($asset_data as $key_asset => $value_asset) {
                    $sheet->row($key_asset, $value_asset['scheme_asset_name']);
                }
                #
                // $sheet->fromArray($asset_data, null, 'A1', false, false);
            });
        })->download('xls');
    }


    public function saveImagesofscheme_performance(Request $request)
    {
        # code...
        // return $request;
        // return $request;
        $previous_images_array = array();
        $upload_directory = "public/uploaded_documents/scheme_performance/";
        if ($request->scheme_performance_id != null) {
            $SchemePerformance_edit = SchemePerformance::find($request->scheme_performance_id);
            if ($SchemePerformance_edit->gallery != "") {
                $previous_images_array = unserialize($SchemePerformance_edit->gallery);
            }
            if ($request->hasFile('galleryFile')) {
                foreach ($request->file('galleryFile') as $file) {
                    $images_tmp_name = "scheme_performance-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move($upload_directory, $images_tmp_name);   // move the file to desired folder
                    array_push($previous_images_array, $upload_directory . $images_tmp_name);    // appending location of image in previous image array for further insertion into database
                }
                $SchemePerformance_edit->gallery = serialize($previous_images_array);    // assign the location of folder to the model
            }
            $SchemePerformance_edit->save(); // Sava the Gallery Image
            # code...
            $schemeperformance_gallery = SchemePerformance::select("scheme_performance_id", "gallery")->where('scheme_performance_id', $request->scheme_performance_id)->first();
            if ($schemeperformance_gallery) {
                if ($request->gallery_images_delete) {
                    $to_delete_image_arr = explode(",", $request->gallery_images_delete);
                    for ($i = 0; $i < count($to_delete_image_arr); $i++) {
                        if (file_exists($to_delete_image_arr[$i])) {
                            unlink($to_delete_image_arr[$i]);
                        }
                    }
                    if ($schemeperformance_gallery) {
                        $scheme_per_gallery_delete = SchemePerformance::find($schemeperformance_gallery->scheme_performance_id);
                        $scheme_per_gallery_delete->gallery = serialize(array_values(array_diff(unserialize($scheme_per_gallery_delete->gallery), $to_delete_image_arr))); //array_diff remove matching elements from 1, array_values changes index starting from 0
                        $scheme_per_gallery_delete->save(); // Saving New gallery Image
                    }
                }
            }
        }
        // }
        return ["message" => "success"];
    }
    public function get_gallery_image($id = "")
    {
        // geting The Gallery Image
        $SchemePerformance_fetch_gallery = SchemePerformance::where('scheme_performance_id', $id)->first('gallery');
        $gallery_details = array();
        if ($SchemePerformance_fetch_gallery->gallery != "") {
            $gallery_details = unserialize($SchemePerformance_fetch_gallery->gallery);
            return ["gallery" => $gallery_details];
        }
        return ["gallery" => $gallery_details];
    }
    public function save_coordinate(Request $request)
    {
        if ($request->coordinates_lat_value != "" && $request->coordinates_lang_value != "") {
            $SchemePerformance_alldetails = SchemePerformance::get('coordinates')->toArray();
            if ($SchemePerformance_alldetails != "") {
                foreach ($SchemePerformance_alldetails as $key_scheme => $value_scheme) {
                    // print_r($value_scheme['coordinates']);
                    $coordinate = array();
                    foreach ($request->coordinates_lat_value as $key_coordinates => $value_coordinates) {
                        $coordinate[] = array('latitude' => $request->coordinates_lat_value[$key_coordinates], 'longitude' => $request->coordinates_lang_value[$key_coordinates]);
                    }
                    // return $coordinate;
                    if ($value_scheme['coordinates'] != serialize($coordinate)) {
                        // print_r($value_scheme['coordinates']);
                        if ($request->scheme_performance_id != null) {
                            $SchemePerformance_edit = SchemePerformance::find($request->scheme_performance_id);
                            $SchemePerformance_edit->coordinates = serialize($coordinate);
                            $SchemePerformance_edit->save();
                        }
                    } else {
                        return ["message" => "Scheme latitudes longitudes You Have  Already entered"];
                    }
                }
            }
        } else {
            return ["message" => "Plz enter  Scheme latitudes longitudes Value"];
        }
        return ["message" => "success"];
    }
    public function get_coordinates_details($id = "")
    {
        $SchemePerformance_fetch_coordinates = SchemePerformance::where('scheme_performance_id', $id)->first('coordinates');
        $coordinates_details = array();
        if ($SchemePerformance_fetch_coordinates->coordinates != "") {
            $coordinates_details = unserialize($SchemePerformance_fetch_coordinates->coordinates);
        }
        return ["coordinates" => $coordinates_details];
    }

    public function view_import_forscheme()
    {
        $scheme_datas = SchemeStructure::select('scheme_id', 'scheme_name', 'scheme_short_name')->orderBy('scheme_id', 'DESC')->get(); // only independent scheme (scheme_is == 1)
        $year_datas = Year::select('year_id', 'year_value')->orderBy('year_value', 'asc')->get();
        $block_datas = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->where('level_id', '=', '3')->get();

        return view('scheme-performance.schemeimport')->with(compact('scheme_datas', 'year_datas', 'block_datas'));
    }

    /*  Spans Across Borders */
    public function getblock_datafor_borders()
    {
        $block_datas = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->where('level_id', '=', '3')->get();
        return ["block_datas" => $block_datas];
    }
    public function getpanchayat_datafor_borders(Request $request)
    {
        $datas = GeoStructure::where('bl_id', $request->block_id)->get();
        return $datas;
    }
    public function get_connectivity_details($scheme_id)
    {
        $block_datas = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->where('level_id', '=', '3')->get();
        $SchemePerformance_connectivity = SchemePerformance::where('scheme_performance_id', $scheme_id)->first();
        $connectivity_details = array();
        if ($SchemePerformance_connectivity->borders_connectivity != "") {
            $connectivity_details = unserialize($SchemePerformance_connectivity->borders_connectivity);
        }
        if ($connectivity_details) {
            foreach ($connectivity_details as $key => $value) {
                $panchayat_datas[] = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->where('bl_id',$value['conn_block_id'])->get();
            }
        }
        return ["block_datas" => $block_datas,"scheme_id" => $scheme_id,"connectivity" => $connectivity_details,"panchayat_datas" => $panchayat_datas];
    }

    public function savebl_pl_connectivity(Request $request)
    {
        // return $request;
        $countval = 0;
        if(count(array_unique($request->panchayay_connectivity)) != count($request->panchayay_connectivity))
        {
            $countval++;
        }
        else {
            $countval;
        }
        $connectivity = array();
        if($countval == 0)
        {
            foreach ($request->block_connectivity as $key_connectivity => $value_connectivity) {
                $connectivity[] = array('conn_block_id' => $request->block_connectivity[$key_connectivity], 'conn_panchayat_id' => $request->panchayay_connectivity[$key_connectivity]);
            }
            if ($connectivity) {
                if ($request->scheme_performance_id_connectivity != null) {
                    $SchemePerformance_edit = SchemePerformance::find($request->scheme_performance_id_connectivity);
                    $SchemePerformance_edit->borders_connectivity = serialize($connectivity);
                    $SchemePerformance_edit->save();
                }
            }
            return ["message" => "success"];
        }
        else {
            return ["message" => "error"];
        }
    }
    /* End Spans Across Borders */
}
