<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Asset;
use App\Department;
use App\asset_cat;
use App\asset_subcat;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetSectionExport;
use App\Exports\AssetCatagorySectionExport;
use App\Exports\AssetSubCatagorySectionExport;
use PDF;
use Auth;


class AssetController extends Controller
{
    public function index()
    {
        $datas = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
            ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
            ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
            ->where('asset.parent_id',-1)
            ->select('asset.*', 'department.dept_name', 'asset_cat.asset_cat_name', 'asset_subcat.asset_sub_cat_name')
            ->orderBy('asset.asset_id', 'desc')
            ->get();

        $departments = Department::orderBy('dept_name')->get();

        $categories = asset_cat::orderBy('asset_cat_name')->get();

        $sub_categories = asset_subcat::orderBy('asset_sub_cat_name')->get();

        return view('asset.index')->with(compact('datas', 'departments', 'categories', 'sub_categories'));
    }


    public function add(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id = "NA";

        $departments = Department::orderBy('dept_name')->get();

        $categorys = asset_cat::orderBy('asset_cat_name')->get();

        $sub_categorys = asset_subcat::orderBy('asset_sub_cat_name')->get();

        $data = new Asset;

        if (isset($request->purpose) && isset($request->id)) {
            $hidden_input_purpose = $request->purpose;
            $hidden_input_id = $request->id;
            $data = $data->find($request->id);
        }

        return view('asset.add')->with(compact('hidden_input_purpose', 'hidden_input_id', 'data', 'departments', 'categorys', 'sub_categorys'));
    }

    public function store(Request $request)
    {
        // $toReturn = array();
        // $toReturn['check'] = $request->child_name;
        // $toReturn['type'] = $request->movable_child;
        // $toReturn['checkicon2'] = $request->file('asset_icon');
        // foreach ($request->child_name as $key => $value) {
        //     # code...
        //     $toReturn['check12'][] = $value;
        //     $toReturn['checkids'][] = $request->asset_child_name_id[$key];
        //     $toReturn['checkicon'][] = $request->hasFile('child_asset_icon.'.$key);
        // }
        // $toReturn['count'] = count($request->child_name);
        // // $toReturn['checkfile1'] = $request->hasFile('child_asset_icon.0');
        // $toReturn['sub'] = $request;
        // return $request;
        $toReturn["response"] = "Something went wrong! Please try again"; // response pre defined as error
        $asset = new Asset;

        if ($request->hidden_input_purpose == "edit") {
            $asset = $asset->find($request->hidden_input_id);
        }

        $asset->asset_name = $request->asset_name;
        if ($request->hasFile('asset_icon')) {
            $upload_directory = "public/uploaded_documents/assets/";
            $file = $request->file('asset_icon');
            $asset_icon_tmp_name = "assets-" . time() . rand(1000, 5000) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move($upload_directory, $asset_icon_tmp_name);   // move the file to desired folder

            // deleteprevious icon
            if ($request->hidden_input_purpose == "edit") {
                if (file_exists($asset->asset_icon)) {
                    unlink($asset->asset_icon);
                }
            }
            $asset->asset_icon = $upload_directory . $asset_icon_tmp_name;    // assign the location of folder to the model
        } else {
            if ($request->hidden_input_purpose == "add") {
                $asset->asset_icon = "";
            } else if ($request->hidden_input_purpose == "edit" && $request->asset_icon_delete) { // edit
                $asset->asset_icon = "";
            }
        }

        // to previous icon if delete clicked
        if ($request->asset_icon_delete) {
            if (file_exists($request->asset_icon_delete)) {
                unlink($request->asset_icon_delete);
            }
        }

        $asset->movable = $request->movable;
        $asset->dept_id = $request->dept_id;
        $asset->org_id = '1';
        $asset->category_id = $request->category;
        $asset->subcategory_id = $request->subcategory;
        $asset->created_by = '1';
        $asset->updated_by = '1';



        if (Asset::where('asset_name', $request->asset_name)->where('dept_id', $request->dept_id)->first() && $request->hidden_input_purpose != "edit") {
            // session()->put('alert-class','alert-danger');
            // session()->put('alert-content','This asset '.$request->asset_name.' already exist !');
            $toReturn["response"] = "This asset " . $request->asset_name . " already exists!";
            $toReturn["asset_name_error"] = "This asset name is already exist in selected department";
        } else if ($asset->save()) {
            // session()->put('alert-class','alert-success');
            // session()->put('alert-content','Asset details have been successfully submitted !');
            $toReturn["response"] = "success";
        } else {
            // session()->put('alert-class','alert-danger');
            // session()->put('alert-content','Something went wrong while adding new details !');
            $toReturn["response"] = "Something went wrong while adding new asset!";
        }
        // return redirect('asset');

        if($request->hidden_input_purpose == "add")//Add for child asset new entry for child
        {
            if (count($request->child_name) !=0 && $request->child_name != null) {
                foreach ($request->child_name as $key => $value) {
                    if ($value != null) {
                        $childasset = new Asset;
                        $childasset->asset_name = $value;
                        if ($request->hasFile('child_asset_icon.'.$key)) {

                            $upload_directory_chid = "public/uploaded_documents/assets/";
                            $file_child = $request->file('child_asset_icon.'.$key);
                            $asset_icon_tmp_name_child = "assets-" . time() . rand(1000, 5000) . '.' . strtolower($file_child->getClientOriginalExtension());
                            $file_child->move($upload_directory_chid, $asset_icon_tmp_name_child);   // move the file to desired folder
                
                            $childasset->asset_icon = $upload_directory_chid . $asset_icon_tmp_name_child;    // assign the location of folder to the mode
                        }
                        else{
                            $childasset->asset_icon = "";
                        }
                        $childasset->movable = $request->movable_child[$key];
                        $childasset->parent_id = $asset->asset_id;
                        $childasset->dept_id = $request->dept_id;
                        $childasset->org_id = '1';
                        $childasset->category_id = $request->category;
                        $childasset->subcategory_id = $request->subcategory;
                        $childasset->created_by = '1';
                        $childasset->updated_by = '1';
                        $childasset->save();
                    }
                }
            }
        }
        elseif($request->hidden_input_purpose == "edit") {
            if (count($request->child_name) !=0 && $request->child_name != null) {
                foreach ($request->child_name as $keyy => $value) {
                    if ($value != null) {
                        if($request->asset_child_name_id[$keyy] != null)
                        { //edit child asset if any changes
                            $editchildasset = Asset::find($request->asset_child_name_id[$keyy]);
                            $editchildasset->asset_name = $value;
                            if ($request->hasFile('child_asset_icon.'.$keyy)) {
                                $upload_directory_chid1 = "public/uploaded_documents/assets/";
                                $file_child1 = $request->file('child_asset_icon.'.$keyy);
                                $asset_icon_tmp_name_child1 = "assets-" . time() . rand(1000, 5000) . '.' . strtolower($file_child1->getClientOriginalExtension());
                                $file_child1->move($upload_directory_chid1, $asset_icon_tmp_name_child1);   // move the file to desired folder
                    
                                $editchildasset->asset_icon = $upload_directory_chid1 . $asset_icon_tmp_name_child1;    // assign the location of folder to the mode
                            }
                            $editchildasset->movable = $request->movable_child[$keyy];
                            $editchildasset->dept_id = $request->dept_id;
                            $editchildasset->category_id = $request->category;
                            $editchildasset->subcategory_id = $request->subcategory;
                            $editchildasset->save();
                        }
                        else{ //edit time new entry of child asset
                            $editnewchildasset = new Asset;
                            $editnewchildasset->asset_name = $value;
                            if ($request->hasFile('child_asset_icon.'.$keyy)) {
                                $upload_directory_chid2 = "public/uploaded_documents/assets/";
                                $file_child2 = $request->file('child_asset_icon.'.$keyy);
                                $asset_icon_tmp_name_child2 = "assets-" . time() . rand(1000, 5000) . '.' . strtolower($file_child2->getClientOriginalExtension());
                                $file_child2->move($upload_directory_chid2, $asset_icon_tmp_name_child2);   // move the file to desired folder
                    
                                $editnewchildasset->asset_icon = $upload_directory_chid2 . $asset_icon_tmp_name_child2;    // assign the location of folder to the mode
                            }
                            else{
                                $editnewchildasset->asset_icon = "";
                            }
                            $editnewchildasset->movable = $request->movable_child[$keyy];
                            $editnewchildasset->parent_id = $request->hidden_input_id;
                            $editnewchildasset->dept_id = $request->dept_id;
                            $editnewchildasset->org_id = '1';
                            $editnewchildasset->category_id = $request->category;
                            $editnewchildasset->subcategory_id = $request->subcategory;
                            $editnewchildasset->created_by = '1';
                            $editnewchildasset->updated_by = '1';
                            $editnewchildasset->save();
                        }
                    }
                }
            }

            if($request->deleted_asset_child_id){
                $deleted_asset_child_id = rtrim($request->deleted_asset_child_id, ",");
                $deleted_asset_child_id = explode(",", $deleted_asset_child_id);
                $to_delete_query = Asset::whereIn('asset_id', $deleted_asset_child_id)->delete();
            }
        }
        return $toReturn;
    }

    public function get_asset_details(Request $request)
    {
        
        $response = "no_data";

        if (isset($request->asset_id)) {
            if (Asset::find($request->asset_id)) {
                $data = Asset::find($request->asset_id);

                // to get category all rows (datas) by movable/inmovable stored in DB
                if (isset($data->movable)) {
                    $category_datas = asset_cat::select("asset_cat_id", "asset_cat_name")->where('movable', $data->movable)->get();
                }

                // to get sub category all rows (datas) by category selected/stored in DB
                if (isset($data->category_id)) {
                    $subcategory_datas = asset_subcat::select("asset_sub_id", "asset_sub_cat_name")->where('asset_cat_id', $data->category_id)->get();
                }

                if (isset($data->asset_id)) {
                    $childs_parent = Asset::where('parent_id', $data->asset_id)->get();
                }
                $response = "success";
            }
        }
        // return $childs_parent;
        return ["response" => $response, "asset_data" => $data, "category_datas" => $category_datas, "subcategory_datas" => $subcategory_datas, "childs_parent" => $childs_parent];
    }

    public function get_category(Request $request)
    {
        $data = asset_cat::where('movable', $request->movable)->get();
        return ["category_data" => $data];
    }

    public function get_subcategory(Request $request)
    {
        $data = asset_subcat::where('asset_cat_id', $request->asset_cat_id)->get();
        return ["subcategory_data" => $data];
    }

    public function delete(Request $request)
    {
        if (Asset::find($request->asset_id)) {

            Asset::where('parent_id', $request->asset_id)->delete();
            Asset::where('asset_id', $request->asset_id)->delete();
            // Todo*: also delete its original icon/ already existed icon
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Deleted successfully !');
        }

        return redirect('asset');
    }

    public function index_cat()
    {
        $datas = asset_cat::orderBy('asset_cat.asset_cat_id', 'desc')->get();

        return view('asset.category.index')->with('datas', $datas);
    }


    public function add_cat(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id = "NA";
        $data = new asset_cat;

        if (isset($request->purpose) && isset($request->id)) {
            $hidden_input_purpose = $request->purpose;
            $hidden_input_id = $request->id;
            $data = $data->find($request->id);
        }
        // return $data;
        return view('asset.category.add')->with(compact('hidden_input_purpose', 'hidden_input_id', 'data'));
    }

    public function store_cat(Request $request)
    {

        $asset_cat = new asset_cat;
        if ($request->hidden_input_purpose == "edit") {
            $asset_cat = $asset_cat->find($request->hidden_input_id);
        }
        $asset_cat->asset_cat_name = $request->asset_cat_name;
        if ($request->asset_cat_description == "") {
            $asset_cat->asset_cat_description = "";
        } else {
            $asset_cat->asset_cat_description = $request->asset_cat_description;
        }

        $asset_cat->movable = $request->movable;
        $asset_cat->created_by = '1';
        $asset_cat->updated_by = '1';
        $asset_cat->status = 1;
        $asset_cat->org_id = 1;
        if (asset_cat::where('asset_cat_name', $request->asset_cat_name)->first() && $request->hidden_input_purpose != "edit") {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'This asset ' . $request->asset_cat_name . ' already exist !');
        } else if ($asset_cat->save()) {
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Asset Category  details have been successfully submitted !');
        } else {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'Something went wrong while adding new details !');
        }
        return redirect('assetcat');
    }
    public function delete_cat(Request $request, $id = "")
    {
        // return $request->asset_cat_id;
        if (asset_cat::find($request->asset_cat_id)) {
            asset_cat::where('asset_cat_id', $request->asset_cat_id)->delete();
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Deleted successfully !');
        }
        return redirect('assetcat');
    }
    public function index_subcat()
    {

        $datas = asset_subcat::orderBy('asset_subcat.asset_sub_id', 'desc')
            ->get();

        return view('asset.subcategory.index')->with('datas', $datas);
    }
    public function add_subcat(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id = "NA";
        $data = new asset_subcat;
        $asset_cat = asset_cat::orderBy('asset_cat_id')->get();

        if (isset($request->purpose) && isset($request->id)) {
            $hidden_input_purpose = $request->purpose;
            $hidden_input_id = $request->id;
            $data = $data->find($request->id);
        }
        // return $data;
        return view('asset.subcategory.add')->with(compact('hidden_input_purpose', 'hidden_input_id', 'data', 'asset_cat'));
    }
    public function store_subcat(Request $request)
    {
        // return $request;
        $asset_subcat = new asset_subcat;
        if ($request->hidden_input_purpose == "edit") {
            $asset_subcat = $asset_subcat->find($request->hidden_input_id);
        }
        $asset_subcat->asset_cat_id = $request->asset_cat_id;
        $asset_subcat->asset_sub_cat_name = $request->asset_sub_cat_name;
        if ($request->asset_sub_cat_description == "") {
            $asset_subcat->asset_sub_cat_description = "";
        } else {
            $asset_subcat->asset_sub_cat_description = $request->asset_sub_cat_description;
        }


        $asset_subcat->created_by = '1';
        $asset_subcat->updated_by = '1';
        $asset_subcat->status = 1;
        $asset_subcat->org_id = 1;
        if (asset_subcat::where('asset_sub_cat_name', $request->asset_sub_cat_name)->where('asset_cat_id', $request->asset_cat_id)->first() && $request->hidden_input_purpose != "edit") {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'This asset ' . $request->asset_subcat_name . ' already exist !');
        } else if ($asset_subcat->save()) {
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Asset Sub Category  details have been successfully submitted !');
        } else {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'Something went wrong while adding new details !');
        }
        return redirect('asset_subcat');
    }

    public function delete_subcat(Request $request)
    {
        // return $request->asset_sub_id;
        if (asset_subcat::find($request->asset_sub_id)) {
            asset_subcat::where('asset_sub_id', $request->asset_sub_id)->delete();
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Deleted successfully !');
        }
        return redirect('asset_subcat');
    }

    public function exportExcelFunctiuonforasset()
    {
        $data = array(1 => array("Asset data  Sheet"));
        $data[] = array('Sl. No.', 'Name', 'Type', 'Department Name', 'Date');

        $items =  Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
            ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
            ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
            ->select('asset.asset_id as slId', 'asset.asset_name', 'asset.movable', 'department.dept_name', 'asset.created_at as createdDate')
            ->orderBy('asset.asset_id', 'desc')
            ->get();

        foreach ($items as $key => $value) {
            if ($value->movable == 1) {
                $value->movable = "Movable";
            } else {
                $value->movable = "Immovable";
            }
            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->asset_name,
                $value->movable,
                $value->dept_name,
                $value->createdDate,
            );
        }
        \Excel::create('Asset_data', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Asset data Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }

    public function exportpdfFunctiuonforasset()
    {
        // $Assetdata = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
        //                 ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
        //                 ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
        //                 ->select('asset.*', 'department.dept_name', 'asset_cat.asset_cat_name', 'asset_subcat.asset_sub_cat_name')
        //                 ->orderBy('asset.asset_id', 'desc')
        //                 ->get();
        $Assetdata = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
            ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
            ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
            ->select('asset.*', 'department.dept_name', 'asset_cat.asset_cat_name', 'asset_subcat.asset_sub_cat_name')
            ->orderBy('asset.asset_id', 'desc')
            ->get();

        foreach ($Assetdata as $key => $value) {
            $value->createdDate = date('d/m/Y', strtotime($value->created_at));
            if ($value->movable == 1) {
                $value->movable = "Movable";
            } else {
                $value->movable = "Immovable";
            }
        }

        $doc_details = array(
            "title" => "Assetdata",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'P'
        );

        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"left\" ><b>Department</b></th></tr>";


        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"width: 300px;\" align=\"center\"><b>Name</b></th>";
        $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Type</b></th>";
        $content .= "<th style=\"width: 179px;\" align=\"center\"><b>Department Name</b></th>";
        $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($Assetdata as $key => $row) {
            $index = $key + 1;
            $content .= "<tr>";
            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"width: 300px;\" align=\"left\">" . $row->asset_name . "</td>";
            $content .= "<td style=\"width: 90px;\" align=\"left\">" . $row->movable . "</td>";
            $content .= "<td style=\"width: 179px;\" align=\"left\">" . $row->dept_name . "</td>";
            $content .= "<td style=\"width: 90px;\" align=\"right\">" . $row->createdDate . "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('AssetData.pdf');
        exit;
    }


    //asset catagory rohit singh
    public function export_Excel_Asset_Category()
    {
        $data = array(1 => array("Asset Category Sheet"));
        $data[] = array('Sl. No.', 'Name', 'Category Description', 'Type', 'Date');

        $items = asset_cat::orderBy('asset_cat.asset_cat_id', 'desc')->select(
            'asset_cat_id',
            'asset_cat_name',
            'asset_cat_description',
            'movable',
            'created_at as createdDate'
        )->get();

        foreach ($items as $key => $value) {
            if ($value->movable == 1) {
                $value->movable = "Movable";
            } else {
                $value->movable = "Immovable";
            }
            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->asset_cat_name,
                $value->asset_cat_description,
                $value->movable,
                $value->createdDate,
            );
        }
        \Excel::create('Asset_Category', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Asset Category Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }

    public function export_PDF_Asset_Category()
    {
        $export_assest_catagory = asset_cat::orderBy('asset_cat.asset_cat_id', 'desc')->get();
        foreach ($export_assest_catagory as $key => $value) {
            $value->createdDate = date('d/m/Y', strtotime($value->created_at));
            if ($value->movable == '1') {
                $value->movable = "Movable";
            } else {
                $value->movable = "Immovable";
            }
        }

        $doc_details = array(
            "title" => "Asset Category",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'L'
        );

        date_default_timezone_set('Asia/Kolkata');
                      $currentDateTime = date('d-m-Y H:i:s'); 
                      $user_name=Auth::user()->first_name;
                      $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th colspan=\"5\" align=\"left\" ><b>Asset Category</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Asset Category
                      "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                      "</b>"."</p>";

        /* ========================================================================= */
        /*                Total width of the pdf table is 1017px                     */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"width: 500px;\" align=\"center\"><b>Name</b></th>";
        $content .= "<th style=\"width: 267px;\" align=\"center\"><b>Category Description</b></th>";
        $content .= "<th style=\"width: 100px;\" align=\"center\"><b>Type</b></th>";
        $content .= "<th style=\"width: 100px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($export_assest_catagory as $key => $row) {
            $index = $key + 1;
            $content .= "<tr>";
            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"width: 500px;\" align=\"left\">" . $row->asset_cat_name . "</td>";
            $content .= "<td style=\"width: 267px;\" align=\"left\">" . $row->asset_cat_description . "</td>";
            $content .= "<td style=\"width: 100px;\" align=\"left\">" . $row->movable . "</td>";
            $content .= "<td style=\"width: 100px;\" align=\"right\">" . $row->createdDate . "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('AssetCategory.pdf');
        exit;
    }

    //asset subcatagory rohit singh
    public function export_Excel_Asset_SubCategory()
    {
        $data = array(1 => array("Asset Sub Catagory-Sheet"));
        $data[] = array('Sl.No.', 'Sub Category Name', 'Sub Category Description', 'Category Name', 'Date');

        $items =  asset_subcat::leftjoin('asset_cat', 'asset_subcat.asset_cat_id', '=', 'asset_cat.asset_cat_id')
            ->select(
                'asset_subcat.asset_sub_id as slId',
                'asset_subcat.asset_sub_cat_name',
                'asset_subcat.asset_sub_cat_description',
                'asset_cat.asset_cat_name',
                'asset_subcat.created_at as createdDate'
            )->orderBy('asset_subcat.asset_sub_id', 'desc')->get();

        foreach ($items as $key => $value) {
            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
            $data[] = array(
                $key + 1,
                $value->asset_sub_cat_name,
                $value->asset_sub_cat_description,
                $value->asset_cat_name,
                $value->createdDate
            );
        }
        \Excel::create('AssetSubCatagory', function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('AssetSubCatagory-Sheet');

            // Chain the setters
            $excel->setCreator('Seraikela')->setCompany('Seraikela');

            $excel->sheet('Fees', function ($sheet) use ($data) {
                $sheet->freezePane('A3');
                $sheet->mergeCells('A1:I1');
                $sheet->fromArray($data, null, 'A1', true, false);
                $sheet->setColumnFormat(array('I1' => '@'));
            });
        })->download('xls');
    }

    public function export_PDF_Asset_SubCategory()
    {
        $export_assest_subcatagory = asset_subcat::leftjoin('asset_cat', 'asset_subcat.asset_cat_id', '=', 'asset_cat.asset_cat_id')
            ->select('asset_cat.*', 'asset_subcat.*')->orderBy('asset_subcat.asset_sub_id', 'desc')->get();

        foreach ($export_assest_subcatagory as $key => $value) {
            $value->createdDate = date('d/m/Y', strtotime($value->created_at));
        }

        $doc_details = array(
            "title" => "Asset Sub Category",
            "author" => 'IT-Scient',
            "topMarginValue" => 10,
            "mode" => 'L'
        );

        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('d-m-Y H:i:s'); 
        $user_name=Auth::user()->first_name;
        $user_last_name=Auth::user()->last_name;
        $pdfbuilder = new \PdfBuilder($doc_details);

        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
        $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"left\" ><b>Asset Sub Category</b></th></tr>";
        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Asset Sub Category
        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
        "</b>"."</p>";

        /* ========================================================================= */
        /*             Total width of the pdf table is 1017px lanscape               */
        /*             Total width of the pdf table is 709px portrait                */
        /* ========================================================================= */
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
        $content .= "<th style=\"width: 300px;\" align=\"center\"><b>Category Name</b></th>";
        $content .= "<th style=\"width: 370px;\" align=\"center\"><b>Sub Category Name</b></th>";
        $content .= "<th style=\"width: 207px;\" align=\"center\"><b>Sub Category Description </b></th>";
        $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Date</b></th>";
        $content .= "</tr>";
        $content .= "</thead>";
        $content .= "<tbody>";
        foreach ($export_assest_subcatagory as $key => $row) {
            $index = $key + 1;
            $content .= "<tr>";
            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
            $content .= "<td style=\"width: 300px;\" align=\"left\">" . $row->asset_cat_name . "</td>";
            $content .= "<td style=\"width: 370px;\" align=\"left\">" . $row->asset_sub_cat_name . "</td>";
            $content .= "<td style=\"width: 207px;\" align=\"left\">" . $row->asset_sub_cat_description . "</td>";
            $content .= "<td style=\"width: 90px;\" align=\"right\">" . $row->createdDate . "</td>";
            $content .= "</tr>";
        }
        $content .= "</tbody></table>";
        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
        $pdfbuilder->output('AssetSubCatagory.pdf');
        exit;
    }


    
    // abhishek  for sub cat 
      public function view_diffrent_formate(Request $request)
      {
        //   return $request;
          $department=array();
          if($request->print=="print_pdf")
          {
  
              if($request->asset_cat_id!="")
              {
  
                                    
                     $export_assest_catagory = asset_cat::whereIn('asset_cat_id',$request->asset_cat_id)->orderBy('asset_cat.asset_cat_id', 'desc')->get();
                        foreach ($export_assest_catagory as $key => $value) {
                            $value->createdDate = date('d/m/Y', strtotime($value->created_at));
                            if ($value->movable == '1') {
                                $value->movable = "Movable";
                            } else {
                                $value->movable = "Immovable";
                            }
                        }

                        $doc_details = array(
                            "title" => "Asset Category",
                            "author" => 'IT-Scient',
                            "topMarginValue" => 10,
                            "mode" => 'L'
                        );

                        date_default_timezone_set('Asia/Kolkata');
                                    $currentDateTime = date('d-m-Y H:i:s'); 
                                    $user_name=Auth::user()->first_name;
                                    $user_last_name=Auth::user()->last_name;
                        $pdfbuilder = new \PdfBuilder($doc_details);

                        $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
                        $content .= "<th colspan=\"5\" align=\"left\" ><b>Asset Category</b></th></tr>";
                        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Asset Category
                                    "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                                    "</b>"."</p>";

                        /* ========================================================================= */
                        /*                Total width of the pdf table is 1017px                     */
                        /* ========================================================================= */
                        $content .= "<thead>";
                        $content .= "<tr>";
                        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                        $content .= "<th style=\"width: 500px;\" align=\"center\"><b>Name</b></th>";
                        $content .= "<th style=\"width: 267px;\" align=\"center\"><b>Category Description</b></th>";
                        $content .= "<th style=\"width: 100px;\" align=\"center\"><b>Type</b></th>";
                        $content .= "<th style=\"width: 100px;\" align=\"center\"><b>Date</b></th>";
                        $content .= "</tr>";
                        $content .= "</thead>";
                        $content .= "<tbody>";
                        foreach ($export_assest_catagory as $key => $row) {
                            $index = $key + 1;
                            $content .= "<tr>";
                            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
                            $content .= "<td style=\"width: 500px;\" align=\"left\">" . $row->asset_cat_name . "</td>";
                            $content .= "<td style=\"width: 267px;\" align=\"left\">" . $row->asset_cat_description . "</td>";
                            $content .= "<td style=\"width: 100px;\" align=\"left\">" . $row->movable . "</td>";
                            $content .= "<td style=\"width: 100px;\" align=\"right\">" . $row->createdDate . "</td>";
                            $content .= "</tr>";
                        }
                        $content .= "</tbody></table>";
                        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                        $pdfbuilder->output('AssetCategory.pdf');
                        exit;




  
  
              }
              return $request;
          }
          elseif($request->print=="excel_sheet")
          {
  
              if($request->asset_cat_id!="")
              {
                
                        $data = array(1 => array("Resource Catagory-Sheet"));
                        $data[] = array('Sl.No.', 'Sub Category Name', 'Sub Category Description', 'Category Name', 'Date');

                        $items =  asset_subcat::leftjoin('asset_cat', 'asset_subcat.asset_cat_id', '=', 'asset_cat.asset_cat_id')
                            ->whereIn('asset_cat.asset_cat_id',$request->asset_cat_id)
                            ->select(
                                'asset_subcat.asset_sub_id as slId',
                                'asset_subcat.asset_sub_cat_name',
                                'asset_subcat.asset_sub_cat_description',
                                'asset_cat.asset_cat_name',
                                'asset_subcat.created_at as createdDate'
                            )->orderBy('asset_subcat.asset_sub_id', 'desc')
                            ->get();

                        foreach ($items as $key => $value) {
                            $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                            $data[] = array(
                                $key + 1,
                                $value->asset_sub_cat_name,
                                $value->asset_sub_cat_description,
                                $value->asset_cat_name,
                                $value->createdDate
                            );
                        }
                        \Excel::create('Resource Catagory-Sheet', function ($excel) use ($data) {

                            // Set the title
                            $excel->setTitle('Resource Catagory-Sheet');

                            // Chain the setters
                            $excel->setCreator('Seraikela')->setCompany('Seraikela');

                            $excel->sheet('Resource Catagory-Sheet', function ($sheet) use ($data) {
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
  
    //   for sub cat 
    public function view_diffrent_formate_sub_cat(Request $request)
    {
        // return $request;
        $department=array();
        if($request->print=="print_pdf")
        {

            if($request->asset_sub_id!="")
            {

                                  
                $export_assest_subcatagory = asset_subcat::leftjoin('asset_cat', 'asset_subcat.asset_cat_id', '=', 'asset_cat.asset_cat_id')
                ->whereIn('asset_subcat.asset_sub_id',$request->asset_sub_id)
                ->select('asset_cat.*', 'asset_subcat.*')->orderBy('asset_subcat.asset_sub_id', 'desc')->get();
    
            foreach ($export_assest_subcatagory as $key => $value) {
                $value->createdDate = date('d/m/Y', strtotime($value->created_at));
            }
    
            $doc_details = array(
                "title" => "Asset Sub Category",
                "author" => 'IT-Scient',
                "topMarginValue" => 10,
                "mode" => 'L'
            );
    
            date_default_timezone_set('Asia/Kolkata');
            $currentDateTime = date('d-m-Y H:i:s'); 
            $user_name=Auth::user()->first_name;
            $user_last_name=Auth::user()->last_name;
            $pdfbuilder = new \PdfBuilder($doc_details);
    
            $content = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"1\" ><tr>";
            $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"left\" ><b>Asset Sub Category</b></th></tr>";
            $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Asset Sub Category
            "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
            "</b>"."</p>";
    
            /* ========================================================================= */
            /*             Total width of the pdf table is 1017px lanscape               */
            /*             Total width of the pdf table is 709px portrait                */
            /* ========================================================================= */
            $content .= "<thead>";
            $content .= "<tr>";
            $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
            $content .= "<th style=\"width: 300px;\" align=\"center\"><b>Category Name</b></th>";
            $content .= "<th style=\"width: 370px;\" align=\"center\"><b>Sub Category Name</b></th>";
            $content .= "<th style=\"width: 207px;\" align=\"center\"><b>Sub Category Description </b></th>";
            $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Date</b></th>";
            $content .= "</tr>";
            $content .= "</thead>";
            $content .= "<tbody>";
            foreach ($export_assest_subcatagory as $key => $row) {
                $index = $key + 1;
                $content .= "<tr>";
                $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
                $content .= "<td style=\"width: 300px;\" align=\"left\">" . $row->asset_cat_name . "</td>";
                $content .= "<td style=\"width: 370px;\" align=\"left\">" . $row->asset_sub_cat_name . "</td>";
                $content .= "<td style=\"width: 207px;\" align=\"left\">" . $row->asset_sub_cat_description . "</td>";
                $content .= "<td style=\"width: 90px;\" align=\"right\">" . $row->createdDate . "</td>";
                $content .= "</tr>";
            }
            $content .= "</tbody></table>";
            $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
            $pdfbuilder->output('AssetSubCatagory.pdf');
            exit;





            }
            return $request;
        }
        elseif($request->print=="excel_sheet")
        {

            if($request->asset_sub_id!="")
            {
              
                $data = array(1 => array("Resource Sub Catagory-Sheet"));
                $data[] = array('Sl.No.', 'Sub Category Name', 'Sub Category Description', 'Category Name', 'Date');
        
                $items =  asset_subcat::leftjoin('asset_cat', 'asset_subcat.asset_cat_id', '=', 'asset_cat.asset_cat_id')
                    ->whereIn('asset_subcat.asset_sub_id',$request->asset_sub_id)
                    ->select(
                        'asset_subcat.asset_sub_id as slId',
                        'asset_subcat.asset_sub_cat_name',
                        'asset_subcat.asset_sub_cat_description',
                        'asset_cat.asset_cat_name',
                        'asset_subcat.created_at as createdDate'
                    )->orderBy('asset_subcat.asset_sub_id', 'desc')->get();
        
                foreach ($items as $key => $value) {
                    $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                    $data[] = array(
                        $key + 1,
                        $value->asset_sub_cat_name,
                        $value->asset_sub_cat_description,
                        $value->asset_cat_name,
                        $value->createdDate
                    );
                }
                \Excel::create('Resource Sub Catagory-Sheet', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Resource Sub Catagory-Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Resource Sub Catagory-Sheet', function ($sheet) use ($data) {
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

      // for Assert (Resources)
      public function view_diffrent_formate_for_resources(Request $request)
      {
        //  return $request;
        //  return "akf";
         $asset_id = explode(',',$request->asset_id); // array
          $department=array();
          if($request->print=="print_pdf")
          {
               
              if($request->asset_id!="")
              {
  
                    
                        $Assetdata = Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                        ->whereIn('asset.asset_id',$asset_id)
                        ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
                        ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
                        ->select('asset.*', 'department.dept_name', 'asset_cat.asset_cat_name', 'asset_subcat.asset_sub_cat_name')
                        ->orderBy('asset.asset_id', 'desc')
                        ->get();
                    
                        foreach ($Assetdata as $key => $value) {
                            $value->createdDate = date('d/m/Y', strtotime($value->created_at));
                            if ($value->movable == 1) {
                                $value->movable = "Movable";
                            } else {
                                $value->movable = "Immovable";
                            }
                        }
                
                        $doc_details = array(
                            "title" => "Assetdata",
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
                        $content .= "<th style='border: solid 1px #000000;' colspan=\"5\" align=\"left\" ><b>Department</b></th></tr>";
                        $content .= "<p style=\"border: solid 1px #000000;width: 50px;\" padding:\"100px;\">"."<b>"."<span>Title: </span>&nbsp;&nbsp;&nbsp;Department
                        "."<br>"."<span>Date & Time: </span>&nbsp;&nbsp;&nbsp;".$currentDateTime."<br>"."<span>User Name:</span>&nbsp;&nbsp;&nbsp;" . $user_name."&nbsp;".$user_last_name.
                        "</b>"."</p>";
                
                        /* ========================================================================= */
                        /*             Total width of the pdf table is 1017px lanscape               */
                        /*             Total width of the pdf table is 709px portrait                */
                        /* ========================================================================= */
                        $content .= "<thead>";
                        $content .= "<tr>";
                        $content .= "<th style=\"width: 50px;\" align=\"center\"><b>Sl.No.</b></th>";
                        $content .= "<th style=\"width: 300px;\" align=\"center\"><b>Name</b></th>";
                        $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Type</b></th>";
                        $content .= "<th style=\"width: 179px;\" align=\"center\"><b>Department Name</b></th>";
                        $content .= "<th style=\"width: 90px;\" align=\"center\"><b>Date</b></th>";
                        $content .= "</tr>";
                        $content .= "</thead>";
                        $content .= "<tbody>";
                        foreach ($Assetdata as $key => $row) {
                            $index = $key + 1;
                            $content .= "<tr>";
                            $content .= "<td style=\"width: 50px;\" align=\"right\">" . $index . "</td>";
                            $content .= "<td style=\"width: 300px;\" align=\"left\">" . $row->asset_name . "</td>";
                            $content .= "<td style=\"width: 90px;\" align=\"left\">" . $row->movable . "</td>";
                            $content .= "<td style=\"width: 179px;\" align=\"left\">" . $row->dept_name . "</td>";
                            $content .= "<td style=\"width: 90px;\" align=\"right\">" . $row->createdDate . "</td>";
                            $content .= "</tr>";
                        }
                        $content .= "</tbody></table>";
                        $pdfbuilder->table($content, array('border' => '1', 'align' => ''));
                        $pdfbuilder->output('AssetData.pdf');
                        exit;
 
                      
 
  
              }
             //  return $request;
          }
          elseif($request->print=="excel_sheet")
          {
                 // return $request;
              if($request->asset_id!="")
              {
  
                            
                $data = array(1 => array("Resources Data  Sheet"));
                $data[] = array('Sl. No.', 'Name', 'Type', 'Department Name', 'Date');
        
                $items =  Asset::leftJoin('department', 'asset.dept_id', '=', 'department.dept_id')
                    ->whereIn('asset.asset_id',$asset_id)
                    ->leftJoin('asset_cat', 'asset.category_id', '=', 'asset_cat.asset_cat_id')
                    ->leftJoin('asset_subcat', 'asset.subcategory_id', '=', 'asset_subcat.asset_sub_id')
                    ->select('asset.asset_id as slId', 'asset.asset_name', 'asset.movable', 'department.dept_name', 'asset.created_at as createdDate')
                    ->orderBy('asset.asset_id', 'desc')
                    ->get();
        
                foreach ($items as $key => $value) {
                    if ($value->movable == 1) {
                        $value->movable = "Movable";
                    } else {
                        $value->movable = "Immovable";
                    }
                    $value->createdDate = date('d/m/Y', strtotime($value->createdDate));
                    $data[] = array(
                        $key + 1,
                        $value->asset_name,
                        $value->movable,
                        $value->dept_name,
                        $value->createdDate,
                    );
                }
                \Excel::create('Resources', function ($excel) use ($data) {
        
                    // Set the title
                    $excel->setTitle('Resources data Sheet');
        
                    // Chain the setters
                    $excel->setCreator('Seraikela')->setCompany('Seraikela');
        
                    $excel->sheet('Resources', function ($sheet) use ($data) {
                        $sheet->freezePane('A3');
                        $sheet->mergeCells('A1:I1');
                        $sheet->fromArray($data, null, 'A1', true, false);
                        $sheet->setColumnFormat(array('I1' => '@'));
                    });
                })->download('xls');

  
              }
            //   return $request;
          }
      }


}
