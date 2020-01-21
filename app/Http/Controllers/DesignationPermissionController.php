<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DesignationPermission;
use App\Designation;
use App\Module;

class DesignationPermissionController extends Controller
{
    public function index()
    {
        $datas = DesignationPermission::leftJoin('designation', 'desig_permission.desig_id', '=', 'designation.desig_id')
            ->leftJoin('module', 'desig_permission.mod_id', '=', 'module.mod_id')
            ->select("desig_permission.*", "designation.name", "module.mod_name")
            ->get();

        $designation_permission_datas = DesignationPermission::get();
        $designation_datas = Designation::select('desig_id', 'name')->get();
        $module_datas = Module::orderBy("mod_name", "asc")->get();

        $to_return_designation = array();
        $to_return = array();

        foreach ($designation_datas as $designation_data) {
            $to_return_tmp = array();

            array_push($to_return_designation, ((object) ['desig_id' => $designation_data->desig_id, 'name' => $designation_data->name]));

            for ($i = 0; $i < count($module_datas); $i++) {
                $tmp = array();

                if (DesignationPermission::where('mod_id', $module_datas[$i]->mod_id)->where('desig_id', $designation_data->desig_id)->first()) {
                    $desig_per_tmp = DesignationPermission::where('mod_id', $module_datas[$i]->mod_id)->where('desig_id', $designation_data->desig_id)->first();
                    $tmp = ["desig_id" => $designation_data->desig_id, 'name' => $designation_data->name, "module_id" => $module_datas[$i]->mod_id, "module" => $module_datas[$i]->mod_name, "add" => $desig_per_tmp->add, "edit" => $desig_per_tmp->edit, "view" => $desig_per_tmp->view, "del" => $desig_per_tmp->del];
                } else {
                    $tmp = ["desig_id" => $designation_data->desig_id, 'name' => $designation_data->name, "module_id" => $module_datas[$i]->mod_id, "module" => $module_datas[$i]->mod_name, "add" => 0, "edit" => 0, "view" => 0, "del" => 0];
                }

                array_push($to_return_tmp, ((object) $tmp));
            }

            array_push($to_return, $to_return_tmp);
        }
        // return $to_return;
        return view('designation-permission.index')->with(compact('datas', 'to_return', 'to_return_designation'));
    }

    public function add(Request $request)
    {
        $hidden_input_purpose = "add";
        $hidden_input_id = "NA";



        $data = new DesignationPermission;
        $designations = Designation::orderBy('name')->get();
        $module_names = Module::orderBy('mod_name')->get();


        if (isset($request->purpose) && ($request->id)) {
            $hidden_input_purpose = $request->purpose;
            $hidden_input_id = $request->id;
            $data = $data->find($request->id);
        }
        return view('designation-permission.add')->with(compact('hidden_input_purpose', 'hidden_input_id', 'data', 'designations', 'module_names'));
    }
    public function store(Request $request)
    {
        $designation_permission = new DesignationPermission;

        if ($request->hidden_input_purpose == "edit") {
            $designation_permission = $designation_permission->find($request->hidden_input_id);
        }

        $designation_permission->mod_id = $request->mod_id;
        $designation_permission->desig_id = $request->desig_id;

        if ($request->add == "") {
            $designation_permission->add = '0';
        } else {
            $designation_permission->add = $request->add;
        }
        if ($request->edit == "") {
            $designation_permission->edit = '0';
        } else {
            $designation_permission->edit = $request->edit;
        }
        if ($request->view == "") {
            $designation_permission->view = '0';
        } else {
            $designation_permission->view = $request->view;
        }
        if ($request->delete == "") {
            $designation_permission->del = '0';
        } else {
            $designation_permission->del = $request->delete;
        }

        $designation_permission->created_by = '1';
        $designation_permission->updated_by = '1';


        if ($designation_permission->save()) {
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Designation Permission have been Saved Successfully !');
        } else {
            session()->put('alert-class', 'alert-danger');
            session()->put('alert-content', 'Something went wrong while adding new Designation Permission');
        }

        return redirect('designation-permission');
    }


    public function save_permissions(Request $request)
    {
        /*
        *
        add[] => containing module id which are selected
        edit[] => containing module id which are selected
        view[] => containing module id which are selected
        del[] => containing module id which are selected
        *
        */

        // delete all entries before according to designation id
        $designation_permission_delete = DesignationPermission::where('desig_id', $request->desig_id)->delete();

        // for add permissions
        for ($i = 0; $i < count($request->add); $i++) {
            $designation_permission_save = new DesignationPermission;
            $designation_permission_save->desig_id = $request->desig_id;
            $designation_permission_save->created_by = '1';
            $designation_permission_save->updated_by = '1';

            $desig_permission_id = DesignationPermission::where('desig_id', $request->desig_id)->where('mod_id', $request->add[$i])->first();
            if ($desig_permission_id) { // if data already found with combination of designation id and module id
                $desig_permission_update = DesignationPermission::find($desig_permission_id->desig_permission_id);
                $desig_permission_update->add = '1';
                $desig_permission_update->save();
            } else { // else: no previous entries found
                $designation_permission_save->mod_id = $request->add[$i]; // assigning module id
                $designation_permission_save->add = '1';
                $designation_permission_save->edit = '0';
                $designation_permission_save->view = '0';
                $designation_permission_save->del = '0';
                $designation_permission_save->save();
            }
        }

        // for edit permissions
        for ($i = 0; $i < count($request->edit); $i++) {
            $designation_permission_save = new DesignationPermission;
            $designation_permission_save->desig_id = $request->desig_id;
            $designation_permission_save->created_by = '1';
            $designation_permission_save->updated_by = '1';

            $desig_permission_id = DesignationPermission::where('desig_id', $request->desig_id)->where('mod_id', $request->edit[$i])->first();
            if ($desig_permission_id) { // if data already found with combination of designation id and module id
                $desig_permission_update = DesignationPermission::find($desig_permission_id->desig_permission_id);
                $desig_permission_update->edit = '1';
                $desig_permission_update->save();
            } else { // else: no previous entries found
                $designation_permission_save->mod_id = $request->edit[$i]; // assigning module id
                $designation_permission_save->add = '0';
                $designation_permission_save->edit = '1';
                $designation_permission_save->view = '0';
                $designation_permission_save->del = '0';
                $designation_permission_save->save();
            }
        }

        // for view permissions
        for ($i = 0; $i < count($request->view); $i++) {
            $designation_permission_save = new DesignationPermission;
            $designation_permission_save->desig_id = $request->desig_id;
            $designation_permission_save->created_by = '1';
            $designation_permission_save->updated_by = '1';

            $desig_permission_id = DesignationPermission::where('desig_id', $request->desig_id)->where('mod_id', $request->view[$i])->first();
            if ($desig_permission_id) { // if data already found with combination of designation id and module id
                $desig_permission_update = DesignationPermission::find($desig_permission_id->desig_permission_id);
                $desig_permission_update->view = '1';
                $desig_permission_update->save();
            } else { // else: no previous entries found
                $designation_permission_save->mod_id = $request->view[$i]; // assigning module id
                $designation_permission_save->add = '0';
                $designation_permission_save->edit = '0';
                $designation_permission_save->view = '1';
                $designation_permission_save->del = '0';
                $designation_permission_save->save();
            }
        }


        // for del permissions
        for ($i = 0; $i < count($request->del); $i++) {
            $designation_permission_save = new DesignationPermission;
            $designation_permission_save->desig_id = $request->desig_id;
            $designation_permission_save->created_by = '1';
            $designation_permission_save->updated_by = '1';

            $desig_permission_id = DesignationPermission::where('desig_id', $request->desig_id)->where('mod_id', $request->del[$i])->first();
            if ($desig_permission_id) { // if data already found with combination of designation id and module id
                $desig_permission_update = DesignationPermission::find($desig_permission_id->desig_permission_id);
                $desig_permission_update->del = '1';
                $desig_permission_update->save();
            } else { // else: no previous entries found
                $designation_permission_save->mod_id = $request->del[$i]; // assigning module id
                $designation_permission_save->add = '0';
                $designation_permission_save->edit = '0';
                $designation_permission_save->view = '0';
                $designation_permission_save->del = '1';
                $designation_permission_save->save();
            }
        }


        session()->put('alert-class', 'alert-success');
        session()->put('alert-content', 'Designation permission have been successfully saved');

        session()->put('designation_permission_changes', "yes"); // so dashboard remeber and redirect to designation permission page
        return redirect('/');
    }

    public function delete(Request $request)
    {
        if (DesignationPermission::find($request->desig_permission_id)) {
            DesignationPermission::where('desig_permission_id', $request->desig_permission_id)->delete();
            session()->put('alert-class', 'alert-success');
            session()->put('alert-content', 'Designation Permission Details Deleted successfully');
        }
        return redirect('designation-permission');
    }
    public function export_to_Excel()
    {

    }


}
