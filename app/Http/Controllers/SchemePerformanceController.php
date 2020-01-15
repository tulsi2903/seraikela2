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
use Auth;

class SchemePerformanceController extends Controller
{
    public function index(Request $request)
    {

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

        $scheme_datas = SchemeStructure::select('scheme_id', 'scheme_name', 'scheme_short_name')->orderBy('scheme_id', 'DESC')->get(); // only independent scheme (scheme_is == 1)
        $year_datas = Year::select('year_id', 'year_value')->orderBy('year_value', 'asc')->get();
        $block_datas = GeoStructure::select('geo_id', 'geo_name')->orderBy('geo_name', 'asc')->where('level_id', '=', '3')->get();

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
        $datas = GeoStructure::where('bl_id', $request->block_id)->get();
        return $datas;
    }

    public function get_all_datas_old(Request $request)
    {
        // received datas
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $panchayat_id = $request->panchayat_id;

        /*
        to send
        scheme_data
        scheme_asset_data
        scheme_performance_data
        add_new_input
        */
        $to_append_thead = '<tr>';
        $to_append_tbody = ''; // to show previous datas
        $to_append_row = '<tr>';

        $scheme_data = SchemeStructure::find($scheme_id);
        $scheme_asset_data = SchemeAsset::find($scheme_data->scheme_asset_id);

        // for attributes
        $attributes  = unserialize($scheme_asset_data->attribute);
        foreach ($attributes as $attribute) {
            $to_append_thead .= '<th>' . $attribute["name"] . '</th>';
            $to_append_row .= '<td><input type="text" name="' . $attribute['id'] . '[]" class="form-control" placeholder="' . $attribute['name'] . '"></td>';
        }

        // for gallery & coordinates
        $to_append_thead .= '<th>Others</th>';
        $to_append_row .= '<td><a href="javascript:void();"><i class="fas fa-plus"></i>Images</a>';
        // for coordinates
        if ($scheme_asset_data->geo_related == 1) {
            $to_append_row .= '<br/><a href="javascript:void();"><i class="fas fa-plus"></i>Coordinates</a>';
        }
        $to_append_row .= '</td>';

        $to_append_thead .= '<th>Status</th>';
        $to_append_row .= '<td>
                            <select name="status[]" class="form-control">
                                <option value="0">Ongoing</option>
                                <option value="1">Completed</option>
                            </select>
                        </td>';

        $to_append_thead .= '<th>Comments</th>';
        $to_append_row .= '<td><input type="text" name="comments[]" class="form-control" placeholder="comments"></td>';

        $to_append_thead .= '<th>Actions</th>';
        $to_append_row .= '<td><button type="button" class="btn btn-danger btn-xs" onclick="delete_row(this)"><i class="fas fa-trash-alt"></i></button></td>';

        $to_append_thead .= '</tr>';
        $to_append_row .= '</tr>';

        return ['to_append_thead' => $to_append_thead, 'to_append_row' => $to_append_row];
    }
    public function get_all_datas(Request $request)
    {
        // received datas
        $scheme_id = $request->scheme_id;

        $year_id = $request->year_id;
        $panchayat_id = $request->panchayat_id;
        $SchemePerformance_details = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->get()->toArray();
        // print_r($SchemePerformance_details);
        $to_append_tbody='';
        if (count($SchemePerformance_details != 0)) {
            foreach ($SchemePerformance_details as $key_SchemePerformance => $value_SchemePerformance) {
                $to_append_tbody.='<tr>';
                $SchemePerformance_attributes=unserialize($value_SchemePerformance['attribute']);
                // print_r($SchemePerformance_attributes);
                $scheme_data = SchemeStructure::find($value_SchemePerformance['scheme_id']);
                $scheme_asset_data = SchemeAsset::get();
                $to_append_tbody .= '<input type="hidden" name="scheme_performance_id[]" value="'.$value_SchemePerformance['scheme_performance_id'].'"><td> <select name="assest_name[]" class="form-control">';
                foreach ($scheme_asset_data as $key_asset => $value_assest) {
                    if($value_SchemePerformance['assest_name']==$value_assest["scheme_asset_id"])
                    $to_append_tbody .= "<option  value=\"" . $value_assest["scheme_asset_id"] . "\">" . $value_assest["scheme_asset_name"] . "</option>";
                    $to_append_tbody .= "<option  value=\"" . $value_assest["scheme_asset_id"] . "\">" . $value_assest["scheme_asset_name"] . "</option>";

                }
                
                $to_append_tbody .= '</select>';
                $attributes  = unserialize($scheme_data->attributes);
                foreach ($attributes as $key_att=>$attribute) {
                    $to_append_tbody .= '<td><input type="text" name="' . $attribute['id'] . '[]" class="form-control" value="'.$SchemePerformance_attributes[$key_att][$attribute['id']].'" placeholder="' . $attribute['name'] . '"></td>';
                }
                $to_append_tbody .= '<td><a href="javascript:void();"><i class="fas fa-plus"></i>Images</a>';
                $to_append_tbody .= '</td>';
                $to_append_tbody .= '<td>
                            <select name="status[]" class="form-control">
                                ';
                if($value_SchemePerformance['status']==0)
                $to_append_tbody.='<option value="0">Ongoing</option>';
                if($value_SchemePerformance['status']==1)
                $to_append_tbody.='<option value="1">Completed</option>';
                $to_append_tbody.='</select></td>';
                $to_append_tbody .= '<td><input type="text" name="comments[]" value="'.$value_SchemePerformance['comments'].'" class="form-control" placeholder="comments"></td>';
                $to_append_tbody .= '<td><button type="button" class="btn btn-danger btn-xs" onclick="delete_row(this)"><i class="fas fa-trash-alt"></i></button></td>';
                $to_append_tbody .= '</tr>';
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
        $to_append_thead .= '<th>Select Assest</th>';
        $to_append_row .= '<input type="hidden" name="scheme_performance_id[]" value="new_scheme_performance"> <td><select name="assest_name[]" class="form-control">';
        foreach ($scheme_asset_data as $key_asset => $value_assest) {
            $to_append_row .= "<option value=\"" . $value_assest["scheme_asset_id"] . "\">" . $value_assest["scheme_asset_name"] . "</option>";
        }
        $to_append_row .= '</select>';
        $attributes  = unserialize($scheme_data->attributes);
        // return $attributes;
        foreach ($attributes as $attribute) {
            $to_append_thead .= '<th>' . $attribute["name"] . '</th>';
            $to_append_row .= '<td><input type="text" name="' . $attribute['id'] . '[]" class="form-control" placeholder="' . $attribute['name'] . '"></td>';
        }
        // return $to_append_row;


        // for gallery & coordinates
        $to_append_thead .= '<th>Others</th>';
        $to_append_row .= '<td><a href="javascript:void();"><i class="fas fa-plus"></i>Images</a>';
        // for coordinates
        // if($scheme_data->geo_related==1){
        //     $to_append_row.='<br/><a href="javascript:void();"><i class="fas fa-plus"></i>Coordinates</a>';
        // }
        $to_append_row .= '</td>';

        $to_append_thead .= '<th>Status</th>';
        $to_append_row .= '<td>
                            <select name="status[]" class="form-control">
                                <option value="0">Ongoing</option>
                                <option value="1">Completed</option>
                            </select>
                        </td>';

        $to_append_thead .= '<th>Comments</th>';
        $to_append_row .= '<td><input type="text" name="comments[]" class="form-control" placeholder="comments"></td>';
        $to_append_thead .= '<th>Actions</th>';
        $to_append_row .= '<td><button type="button" class="btn btn-danger btn-xs" onclick="delete_row(this)"><i class="fas fa-trash-alt"></i></button></td>';

        $to_append_thead .= '</tr>';
        $to_append_row .= '</tr>';

        return ['to_append_thead' => $to_append_thead, 'to_append_row' => $to_append_row, 'to_append_tbody'=>$to_append_tbody];
    }
    public function store_old(Request $request)
    {
        // recieved datas
        return $request;
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $panchayat_id = $request->panchayat_id;
        $block_id = GeoStructure::where('geo_id', $panchayat_id)->first()->bl_id;
        $subdivision_id = GeoStructure::where('geo_id', $panchayat_id)->first()->sd_id;

        $scheme_data = SchemeStructure::where('scheme_id', $scheme_id)->first();
        $scheme_asset_data = SchemeAsset::where('scheme_asset_id', $scheme_data->scheme_asset_id)->first();
        $attributes = unserialize($scheme_asset_data->attribute);
        $attributes_ids =

            // to save
            $scheme_performance = new SchemePerformance;

        // loop
        for ($i = 0; $i < count($request->status); $i++) { }


        return $request;
    }
    public function store(Request $request)
    {
        // recieved datas
        // return $request;
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $panchayat_id = $request->panchayat_id;
        $block_id = GeoStructure::where('geo_id', $panchayat_id)->first()->bl_id;
        $subdivision_id = GeoStructure::where('geo_id', $panchayat_id)->first()->sd_id;
        $scheme_data = SchemeStructure::where('scheme_id', $scheme_id)->first();
        // $scheme_asset_data = SchemeAsset::where('scheme_asset_id', $scheme_data->scheme_asset_id)->first();
        $attributes = unserialize($scheme_data->attributes);
        // $attributes_ids = 
        $form_request_id = array();
        $form_attributes_data_array = array();
        foreach ($attributes as $key_id => $value_id) {
            foreach ($request->input($value_id['id']) as $key => $value) {
                $form_request_id[$key][$key_id][$value_id['id']] = $value;
            }
        }
        // echo "<pre>";
        $tmp_array=array();
        $delete_check_array=array();
        foreach ($form_request_id as $key_request => $value_request) {
            // $SchemePerformance_get=SchemePerformance::where('scheme_id',$scheme_id)->get('scheme_performance_id')->toArray();
            $SchemePerformance_get = SchemePerformance::where('scheme_id', $scheme_id)->where('year_id', $year_id)->where('panchayat_id', $panchayat_id)->get()->toArray();
            foreach($SchemePerformance_get as $tmp) {
                array_push($tmp_array, $tmp['scheme_performance_id']);
              }
            // if(in_array($request->scheme_performance_id[$key_request],$tmp_array))
            // {
            //     echo "in";
            //     echo "<br>";
            //     echo $request->scheme_performance_id[$key_request];
            // }
            // else
            // {
            //     echo "out";
            //     echo "<br>";
            //     echo $request->scheme_performance_id[$key_request];
            // }
            // return $SchemePerformance_get;
            if($request->scheme_performance_id[$key_request]!='new_scheme_performance')
            {
                if(in_array($request->scheme_performance_id[$key_request],$tmp_array))
                {
                    $delete_check_array[] =$request->scheme_performance_id[$key_request];
                    $scheme_performance =SchemePerformance::find($request->scheme_performance_id[$key_request]);
                    $scheme_performance->scheme_id = $scheme_id;
                    $scheme_performance->year_id = $year_id;
                    $scheme_performance->subdivision_id = $subdivision_id;
                    $scheme_performance->block_id = $block_id;
                    $scheme_performance->panchayat_id = $panchayat_id;
                    $scheme_performance->attribute = serialize($value_request) ?? "";
                    $scheme_performance->status = $request->status[$key_request];
                    $scheme_performance->assest_name = $request->assest_name[$key_request];
                    $scheme_performance->gallery = "ddd";
                    $scheme_performance->comments = $request->comments[$key_request] ?? "";
                    $scheme_performance->coordinates = "fdddgd";
                    $scheme_performance->created_by = Auth::user()->id;
                    $scheme_performance->updated_by = Auth::user()->id;
                    $scheme_performance->save();
                }
            }
            else
            {
                $scheme_performance = new SchemePerformance;
                $scheme_performance->scheme_id = $scheme_id;
                $scheme_performance->year_id = $year_id;
                $scheme_performance->subdivision_id = $subdivision_id;
                $scheme_performance->block_id = $block_id;
                $scheme_performance->panchayat_id = $panchayat_id;
                $scheme_performance->attribute = serialize($value_request) ?? "";
                $scheme_performance->status = $request->status[$key_request];
                $scheme_performance->assest_name = $request->assest_name[$key_request];
                $scheme_performance->gallery = "ddd";
                $scheme_performance->comments = $request->comments[$key_request] ?? "";
                $scheme_performance->coordinates = "fdddgd";
                $scheme_performance->created_by = Auth::user()->id;
                $scheme_performance->updated_by = Auth::user()->id;
                $scheme_performance->save();
            }
            // echo "<br>";
        }
        $diff_result = array_diff($tmp_array, $delete_check_array);
        foreach ($diff_result as $key => $value_diff) {
          $pcc_enitity_record = SchemePerformance::where('scheme_performance_id', $value_diff)->delete();
        }
        // exit;
        return redirect('scheme-performance');
    }
    public function viewimport(Request $request)
    {
        // return $request;
        if (!$request->scheme_id || !$request->year_id || !$request->block_id) {
            return redirect("scheme-performance");
        }

        // received datas
        $scheme_id = $request->scheme_id;
        $year_id = $request->year_id;
        $block_id = $request->block_id;

        return view('scheme-performance.importExcel')->with(compact('scheme_id', 'year_id', 'block_id'));
    }

    public function Import_from_Excel(Request $request)
    {
        $scheme_datas = SchemeStructure::where('scheme_id', $request->scheme_id)->first(); /* get scheme atributes */
        $unserialDatas = unserialize($scheme_datas->attributes);
        $tableHeadingsAndAtributes = array();
        $schemeAtributes = array();
        $tableHeadingsAndAtributes = array(
            'sno.',
            'block_name',
            'panchayat_name',
            'work_start_fin_year'
        );
        foreach ($unserialDatas as $key_un => $value_un) {
            array_push($tableHeadingsAndAtributes, strtolower(str_replace(" ", "_", $value_un['name'])));
            $schemeAtributes[$value_un['id']] =  strtolower(str_replace(" ", "_", $value_un['name']));
        }

        if ($_FILES['excelcsv']['tmp_name']) {
            $readExcel = \Excel::load($_FILES['excelcsv']['tmp_name'], function ($reader) { })->get()->toArray();
            $readExcelHeader = \Excel::load($_FILES['excelcsv']['tmp_name'])->get();
            $excelSheetHeadings = $readExcelHeader[0]->first()->keys()->toArray(); /* this is for excel sheet heading */
            sort($tableHeadingsAndAtributes);
            sort($excelSheetHeadings); 
            $unserializedAtributesData = array();

            /* validation for matching of headings */
            if ($tableHeadingsAndAtributes == $excelSheetHeadings) { 

                foreach ($readExcel[0] as $excel_key => $excel_value) { 

                    foreach ($excel_value as $key => $value) { 

                        foreach ($schemeAtributes as $attribute_key => $attribute_value) {

                            if ($attribute_value == $key) {
                                $unserializedAtributesData[$attribute_key] = $value;
                            }
                        }
                    }
                    $serializationAttributes[] = $unserializedAtributesData;
                    $unserializedAtributesData = [];
                }

                $filename = "Error Log.txt"; /* error file name */
                $myfile = fopen($filename, "w"); /* open error file name by using fopen function */

                foreach ($readExcel[0] as $key => $row) { /* Insert Data By using for each one by one */
                    $block_name =  ucwords($row['block_name']);
                    $panchayat_name =   ucwords($row['panchayat_name']);
                    $fetch_block_id = GeoStructure::where('geo_name', $block_name)->value('geo_id'); /* for block ID */
                    $fetch_panchayat_id = GeoStructure::where('geo_name', $panchayat_name)->value('geo_id'); /* for Panchayat ID */
                    $fetch_subdivision_id = GeoStructure::where('geo_id', $fetch_block_id)->value('parent_id'); /* for subdivision_id ID */
                    $fetch_year_id = Year::where('year_value', $row['work_start_fin_year'])->value('year_id'); /* for Year ID */

                    /* if those id avilable then insert data on the base */
                    if ($row['sno.'] != null && $fetch_block_id != null && $fetch_panchayat_id != null && $fetch_year_id != null && $fetch_subdivision_id != null) {
                        $scheme_performance = new SchemePerformance;
                        $scheme_performance->year_id = $fetch_year_id;
                        $scheme_performance->scheme_id = $request->scheme_id;
                        $scheme_performance->block_id = $fetch_block_id;
                        $scheme_performance->panchayat_id = $fetch_panchayat_id;
                        $scheme_performance->subdivision_id = $fetch_subdivision_id;
                        $scheme_performance->attribute = serialize($serializationAttributes[$key]);
                        $scheme_performance->status = $row['work_status'];
                        $scheme_performance->created_by = Session::get('user_id');
                        $scheme_performance->save();
                    } else {  /* Else find id and error write on the notepad */
                        if ($row['sno.'] != null) {
                            if ($fetch_block_id == null && $fetch_panchayat_id != null) {
                                $txt = " ON row sno. " . $row['sno.'] . " Block Not Found \n";
                                fwrite($myfile, $txt);
                            } elseif ($fetch_panchayat_id == null && $fetch_block_id != null) {
                                $txt = " ON row sno. " . $row['sno.'] . " Panchayat Not Found \n";
                                fwrite($myfile, $txt);
                            } elseif ($fetch_panchayat_id == null && $fetch_block_id == null) {
                                $txt = " ON row sno. " . $row['sno.'] . " Both Panchayat And Block Not Found \n";
                                fwrite($myfile, $txt);
                            } elseif ($fetch_subdivision_id == null || $fetch_year_id == null) {
                                $txt = " ON row sno. " . $row['sno.'] . " Something Error \n";
                                fwrite($myfile, $txt);
                            }
                        } else {
                            $txt = " Serial Number Not Available \n";
                            fwrite($myfile, $txt);
                        }
                    }
                }
        
                fclose($myfile); //close file

                if (file_get_contents($filename) == null) //if error file does not exit ant data then popup message success
                {
                    session()->put('alert-class', 'alert-success');
                    session()->put('alert-content', 'Scheme details has been saved');
                    return back();
                } else { //Else download the error notepad file
                    header("Cache-Control: public");
                    header("Content-Description: File Transfer");
                    header("Content-Length: " . filesize("$filename") . ";");
                    header("Content-Disposition: attachment; filename=$filename");
                    header("Content-Type: application/octet-stream; ");
                    header("Content-Transfer-Encoding: binary");
                    readfile($filename);
                    exit;
                }
            } else { //for error message
                session()->put('alert-class', 'alert-danger');
                session()->put('alert-content', 'Your Excel Format Missmatch From Our Format..Please Download Our Excel Format..');
                return back();
            }
        }
    }

    public function downloadFormat(Request $request)
    {
        # code...
        $scheme_datas = SchemeStructure::where('scheme_id', $request->scheme_id)->first();
        $unserialDatas = unserialize($scheme_datas->attributes);
        $data = array();
        $data = array(
            'SNo.',
            'Block Name',
            'Panchayat Name',
            'Work Start Fin Year'
        );
        foreach ($unserialDatas as $key_un => $value_un) {
            array_push($data, $value_un['name']);
        }

        \Excel::create('Scheme-Format', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Scheme-Format');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                // $sheet->freezePane('A3');
                // $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }

    // public function add(Request $request){
    //     $hidden_input_purpose = "add";
    //     $hidden_input_id= "NA";
    //     $bl_id = "";


    //     $schemes = SchemeStructure::orderBy('scheme_name','asc')->get();
    //     $panchayats = GeoStructure::orderBy('geo_name','asc')->get();
    //     $indicators = SchemeIndicator::orderBy('indicator_name','asc')->get();
    //     $years = Year::orderBy('year_value','asc')->get();
    //     // $groups = Group::orderBy('scheme_group_name','asc')->get();
    //     $blocks = GeoStructure::orderBy('geo_name','asc')->where('level_id','=','3')->get();
    //     $districts = GeoStructure::orderBy('geo_name','asc')->where('level_id','=','1')->get();
    //     $subdivisions = GeoStructure::orderBy('geo_name','asc')->where('level_id','=','2')->get();


    //     $data = new SchemePerformance;

    //     if(isset($request->purpose)&&isset($request->id)){
    //         $hidden_input_purpose=$request->purpose;
    //         $hidden_input_id=$request->id;
    //         $data = $data->find($request->id);

    //         if($data){
    //             $tmp = GeoStructure::select('geo_id')->whereIn('geo_id', GeoStructure::select('bl_id')->where('geo_id', $data->geo_id)->first())->first();
    //             $bl_id = $tmp->geo_id;

    //             $tmp_dist = GeoStructure::select('geo_id')->whereIn('geo_id',GeoStructure::select('dist_id')->where('geo_id',$data->geo_id)->first())->first();
    //             $dist_id =$tmp_dist->geo_id;

    //             $tmp_sd = GeoStructure::select('geo_id')->whereIn('geo_id',GeoStructure::select('sd_id')->where('geo_id',$data->geo_id)->first())->first();
    //             $sd_id =$tmp_sd->geo_id;

    //             $dist = GeoStructure::orderBy('geo_name','asc')->where('dist_id',$dist_id)->get();
    //             $sd = GeoStructure::orderBy('geo_name','asc')->where('sd_id',$sd_id)->get();



    //             $indicators = SchemeIndicator::orderBy('indicator_name','asc')->where('scheme_id',$data->scheme_id)->get();


    //             $panchayats = GeoStructure::orderBy('geo_name','asc')->where('bl_id', $bl_id)->get();
    //         }
    //     }

    //     return view('scheme-performance.add')->with(compact('hidden_input_purpose','hidden_input_id','data','bl_id','dist_id','sd_id','dist','sd','schemes','panchayats','indicators','years','groups','blocks','districts','subdivisions'));
    // }

    // public function data_geo_target(Request $request)
    // {
    //     $geo_target_data = SchemeGeoTarget::where('geo_id',$request->panchayat)->where('scheme_id',$request->scheme_name)->get();
    //     return ["geo_target_data"=>$geo_target_data];

    //    return view('scheme-performance.index');


    // }


    // public function get_subdivision_name(Request $request)
    // {
    // 	$data = GeoStructure::where('dist_id',$request->dist_id)->where('level_id','=','2')->get();
    // 	return["subdivision_data"=>$data,"id"=>$request->dist_id];

    // }
    // public function get_block_name(Request $request)
    // {
    // 	$data = GeoStructure::where('sd_id',$request->sd_id)->where('level_id','=','3')->get();
    // 	return["block_data"=>$data,"id"=>$request->sd_id];
    // }


    // public function get_indicator_name(Request $request)
    // {
    //     $data = SchemeIndicator::where('scheme_id',$request->scheme_id)->get();
    //     return ["scheme_indicator_data"=>$data];
    // }


    // public function get_target(Request $request)
    // {
    //     $tmp = SchemeGeoTarget::where('scheme_id',$request->scheme_id)->where('geo_id',$request->geo_id)->where('indicator_id',$request->indicator_id)->where('year_id', $request->year_id)->first();  
    //   $scheme_geo_target_id="";
    //   $pre_value="";
    //     if(count($tmp)>0)
    //     {

    //         $scheme_geo_target_id = $tmp->scheme_geo_target_id;

    //         $target=$tmp->target;

    //         $get_pre_value = SchemePerformance::where('scheme_geo_target_id',$tmp->scheme_geo_target_id)
    //                                            ->orderBy('scheme_performance_id', 'desc')
    //                                            ->first();

    //           if($get_pre_value)
    //           {
    //             $pre_value = $get_pre_value->current_value;
    //           }
    //           else
    //           {
    //             $pre_value = 0;
    //           }

    //     }
    //   else{
    //     $target = -1;
    //   }

    //     return ["target_get_data"=>$target,"id"=>$scheme_geo_target_id,"pre_value"=>$pre_value]; 
    // }

    // public function store(Request $request){
    //     // received datas
    //     $scheme_indicator_id = $request->scheme_sanction_id;
    //     $scheme_performance_id=[];
    //     $scheme_performance_id = $request->scheme_performance_id; // array type
    //     $completion_percentage=[];
    //     $completion_percentage=$request->completion_percentage;
    //     $status=[];
    //     $status=$request->status;

    //     $upload_path = "public/uploaded_documents/scheme_performance";

    //     for($i=0;$i<count($scheme_performance_id);$i++)
    //     {
    //         $scheme_performance_save = SchemePerformance2::find($scheme_performance_id[$i]);
    //         $scheme_performance_save->completion_percentage = $completion_percentage[$i];
    //         $scheme_performance_save->status = $status[$i];

    //         $scheme_performance_save->images = "";
    //         if($request->hasFile('images_'.$scheme_performance_id[$i]))
    //         {
    //             $image = $request->file('images_'.$scheme_performance_id[$i]);
    //             // $image_name = $scheme_performance_id[$i]."-".time().".".strtolower($images[$i]->getClientOriginalExtension());
    //             // $images[$i]->move($upload_path, $image_name);
    //             $image_name = $scheme_performance_id[$i]."-".time().".".strtolower($image->getClientOriginalExtension());
    //             $image->move($upload_path, $image_name);
    //             $scheme_performance_save->images = $upload_path."/".$image_name; 
    //         }

    //         $scheme_performance_save->save();
    //     }

    //     return ["response"=>"success","first_file"=>$request->hasFile('images_25')];
    // }

    // public function store(Request $request)
    // {
    //     $scheme_performance = new SchemePerformance;
    //     $scheme_performance->scheme_geo_target_id = $request->scheme_geo_target_id;
    //     $scheme_performance->pre_value = $request->pre_value;
    //     $scheme_performance->current_value = $request->current_value;

    //      $scheme_performance->attachment = "";



    //      $i = 0;
    //      if($request->hasFile('attachment'))
    //      {


    //         foreach($request->file('attachment') as $file){

    //             $imageName = time() . $i . '.' . $file->getClientOriginalExtension();

    //             // move the file to desired folder
    //             $file->move('public/uploaded_documents/', $imageName);

    //             // assign the location of folder to the model
    //             $scheme_performance->attachment.=":".$imageName;


    //             $i++;

    //         } 

    //         $scheme_performance->attachment = ltrim($scheme_performance->attachment,":");
    //     }
    //     $scheme_performance->created_by =1;
    //     $scheme_performance->updated_by =1;


    //    return $scheme_performance;

    //     if($scheme_performance->save()){
    //         session()->put('alert-class','alert-success');
    //         session()->put('alert-content','Scheme performance have been successfully submitted !');
    //     }
    //     return redirect('scheme-performance/add');
    // }

    // to send all datas from scheme_performance of scheme_sanction_id (scheme_geo_target)
    // public function get_scheme_performance_datas(Request $request){
    //     // $to_return
    //     $to_return = [];

    //     // received datas
    //     $scheme_sanction_id = $request->scheme_sanction_id;

    //     $scheme_geo_target_datas = SchemeGeoTarget2::where('scheme_sanction_id', $scheme_sanction_id)->get();
    //     if(count($scheme_geo_target_datas)>0){
    //         $unique_scheme_geo_target_ids = [];
    //         foreach($scheme_geo_target_datas as $scheme_geo_target_data){
    //             if(!in_array($scheme_geo_target_data->scheme_geo_target_id, $unique_scheme_geo_target_ids)){
    //                 array_push($unique_scheme_geo_target_ids, $scheme_geo_target_data->scheme_geo_target_id);
    //             }
    //         }

    //         $indicator_datas = SchemeIndicator::where('scheme_id', $scheme_geo_target_datas[0]->scheme_id)->get();

    //         // getting all rows/columns
    //         foreach($indicator_datas as $indicator_data)
    //         {
    //             $to_return_tmp = [];
    //             $found = false; //data found in geo target i.e. already assigned targets
    //             foreach($scheme_geo_target_datas as $scheme_geo_target_data){
    //                 if($indicator_data->indicator_id==$scheme_geo_target_data->indicator_id)
    //                 {
    //                     $to_return_tmp["indicator_id"] = $indicator_data->indicator_id;
    //                     $to_return_tmp["indicator_name"] = $indicator_data->indicator_name;
    //                     $to_return_tmp["geo_related"] = $scheme_geo_target_data->geo_related;
    //                     $to_return_tmp["target"] = $scheme_geo_target_data->target;
    //                     $to_return_tmp["indicator_datas"] = [];

    //                     // scheme_performance datas in ["indicator_datas] starts
    //                     $to_return_indicator_datas_tmp = [];
    //                     $scheme_performance_datas = SchemePerformance2::where('scheme_geo_target_id', $scheme_geo_target_data->scheme_geo_target_id)->get();
    //                     foreach($scheme_performance_datas as $scheme_performance_data){
    //                         $tmp_to_push["scheme_performance_id"] = $scheme_performance_data->scheme_performance_id;
    //                         $tmp_to_push["indicator_sanction_id"] = $scheme_performance_data->indicator_sanction_id;
    //                         $tmp_to_push["latitude"] = $scheme_performance_data->latitude;
    //                         $tmp_to_push["longitude"] = $scheme_performance_data->longitude;
    //                         $tmp_to_push["completion_percentage"] = $scheme_performance_data->completion_percentage;
    //                         $tmp_to_push["status"] = $scheme_performance_data->status;
    //                         $tmp_to_push["images"] = $scheme_performance_data->images;
    //                         $tmp_to_push["comments"] = $scheme_performance_data->comments;
    //                         array_push($to_return_indicator_datas_tmp, $tmp_to_push);
    //                     }
    //                     if($to_return_indicator_datas_tmp){
    //                         $to_return_tmp["indicator_datas"] = $to_return_indicator_datas_tmp;
    //                     }
    //                     // scheme_performance_datas ends

    //                     $found = true; // if data found in geo target i.e. already assigned targets
    //                     array_push($to_return, $to_return_tmp);
    //                 }
    //             }

    //             // no data data found in geo target i.e. already assigned targets
    //             if(!$found){
    //                 $to_return_tmp["indicator_id"] = $indicator_data->indicator_id;
    //                 $to_return_tmp["indicator_name"] = $indicator_data->indicator_name;
    //                 $to_return_tmp["geo_related"] = '0';
    //                 $to_return_tmp["target"] = '0';
    //                 $to_return_tmp["indicator_datas"] = [];
    //                 array_push($to_return, $to_return_tmp);
    //             }
    //         }
    //     }

    //     if(count($to_return)!=0){
    //         $response = "success";
    //     }
    //     else{
    //         $response = "no_data";
    //     }

    //     return ["response"=>$response, "data"=>$to_return];
    // }


}
