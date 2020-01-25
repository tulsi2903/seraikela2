@extends('layout.layout')

@section('title', 'Resources')

@section('page-style')
<style>
    .logo-header .logo {
        color: #575962;
        opacity: 1;
        position: relative;
        height: 100%;
        margin-top: 1em;
    }

    .btn-toggle {
        color: #fff !important;
        margin-top: 1em;
    }
    .btn-toggle {
        color: #fff !important;
        margin-top: 1em;
    }
    #printable-info-details {
        visibility: hidden;
        height: 0px;
        /* position: fixed;
        left: 0;
        top: 20px;
        width: 100vw !important; */
    }
    
    @media print {
        #printable-area {
            margin-top: 250px !important;
        }
        .no-print,
        .no-print * {
            display: none !important;
        }
        #printable-info-details {
            visibility: visible;
            position: fixed;
        }
        #print-button,
        #print-button * {
            visibility: hidden;
        }
        .card-title-print-1 {
            visibility: visible !important;
            position: fixed;
            color: #147785;
            font-size: 30px;
            ;
            left: 0;
            top: 50px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .card-title-print-2 {
            visibility: visible !important;
            position: fixed;
            color: #147785;
            font-size: 30px;
            ;
            left: 0;
            top: 100px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .card-title-print-3 {
            visibility: visible !important;
            position: fixed;
            color: #147785;
            font-size: 30px;
            ;
            left: 0;
            top: 140px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .action-buttons {
            display: none;
        }
    }
    .logo-header .logo {
        color: #575962;
        opacity: 1;
        position: relative;
        height: 100%;
        margin-top: 1em;
    }
</style>
@endsection

@section('page-content')

<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>

<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">{{$phrase->resource}} </h4>
                <div class="card-tools">
                    <button type="button" data-toggle="tooltip" title="{{$phrase->send_email}}" class="btn btn-icon btn-round btn-success"  onclick="openmodel();" ><i class="fa fa-envelope" aria-hidden="true"></i></button>
                    <button type="button" data-toggle="tooltip" title="{{$phrase->print}}" class="btn btn-icon btn-round btn-default" onclick="printViewone();"><i class="fa fa-print" aria-hidden="true"></i></button>
                    <button type="button" target="_BLANK" data-toggle="tooltip" title="{{$phrase->export_pdf}}" onclick="exportSubmit('print_pdf');" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button>
                    <button type="button" data-toggle="tooltip" title="{{$phrase->export_excel}}" onclick="exportSubmit('excel_sheet');" class="btn btn-icon btn-round btn-success"><i class="fas fa-file-excel"></i></button>
                    <!-- <a href="#" data-toggle="tooltip" title="{{$phrase->send_email}}"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button></a> -->
                    <!-- <a href="#" data-toggle="tooltip" title="{{$phrase->print}}"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a> -->
                    <!-- <a href="{{url('asset/pdf/pdfURL')}}" target="_BLANK" data-toggle="tooltip" title="{{$phrase->export_pdf}}"><button type="button" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button></a>
                    <a href="{{url('asset/export/excelURL')}}" data-toggle="tooltip" title="{{$phrase->export_excel}}"><button type="button" class="btn btn-icon btn-round btn-primary"><i class="fas fa-file-excel"></i></button></a> -->
                    @if($desig_permissions["mod13"]["add"])
                    <a id="toggle1" onclick="resetAssetForm()" class="btn btn-secondary" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="show-toggle1" style="border: 1px solid #ababab; padding: 15px; margin-bottom: 25px; border-radius: 3px;background: linear-gradient(to top, rgb(255, 255, 255), rgb(233, 236, 255) 70%, rgb(235, 238, 255), rgb(228, 232, 255) 100%);">
            <form action="{{url('asset/store')}}" onsubmit="return false" method="POST" enctype="multipart/form-data" id="asset-form">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asset_name">{{$phrase->resource}} <span style="color:red;margin-left:5px;">*</span></label>
                            <input type="text" name="asset_name" id="asset_name" class="form-control" placeholder="{{$phrase->resource}}" value="" autocomplete="off">
                            <div class="invalid-feedback" id="asset_name_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="movable">{{$phrase->type}}<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="movable" id="movable" class="form-control">
                                <option value="">--Select--</option>
                                <option value="1">Movable</option>
                                <option value="0">Immovable</option>
                            </select>
                            <div class="invalid-feedback" id="movable_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dept_id">{{$phrase->department_name}}<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="dept_id" id="dept_id" class="form-control">
                                <option value="">--Select--</option>
                                @foreach( $departments as $department )
                                <option value="{{ $department->dept_id }}">
                                    {{ $department->dept_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="department_error_msg"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category">{{$phrase->catagory}}</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">--Select--</option>
                                @foreach( $categories as $category )
                                <option value="{{ $category->asset_cat_id }}">
                                    {{ $category->asset_cat_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="category_error_msg"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="subcategory">{{$phrase->sub_catagory}} </label>
                            <select name="subcategory" id="subcategory" class="form-control">
                                <option value="">--Select--</option>
                                @foreach( $sub_categories as $sub_category )
                                <option value="{{ $sub_category->asset_sub_id }}">
                                    {{ $sub_category->asset_sub_cat_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="subcategory_error_msg"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asset_icon">{{$phrase->icon}}</label>
                            <input type="file" name="asset_icon" id="asset_icon" class="form-control">
                            <div id="asset_icon_delete_div" style="padding:5px 0; display: none;">
                                <div>{{$phrase->icon}}</div>
                                <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                    <img src="" style="height:120px;">
                                    <span style="position:absolute;top:0;right:0; background: rgba(0,0,0,0.5); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick=""><i class="fas fa-trash"></i></span>
                                </div>
                            </div>
                            <input type="text" name="asset_icon_delete" id="asset_icon_delete" value="" hidden>
                            <div class="invalid-feedback" id="asset_icon_error_msg"></div>
                        </div>
                    </div>
               
              
                
                </div>
            <div class="row">
                   
                         <div class="col-md-12">
                            <div class="card-header">
                                <div class="card-head-row card-tools-still-right">
                                    <h4 class="card-title" style="margin-left: -20px;">{{$phrase->sub_catagory}} </h4>
                                    <div class="card-tools">
                                        <button type="button" onclick="append_table_data('add',null);" class="btn btn-secondary btn-sm btn-circle">{{$phrase->add}} <i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>  
                            <br>
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover" >
                                    <thead style="    background: #d6dcff;">
                                        <tr>
                                            <th>{{$phrase->name}} </th>
                                            <th>{{$phrase->type}} </th>
                                            <th style="padding: 5px;">{{$phrase->icon}}</th>
                                            <th></th>
                                            <th>{{$phrase->action}}</th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody id="append-name-child">
    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
              
              
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <hr />
                            <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="add" hidden>
                            <input type="text" name="hidden_input_id" id="hidden_input_id" value="NA" hidden>
                            <input type="text" name="deleted_asset_child_id" id="deleted_asset_child_id" value="" hidden>
                            <input type="text" name="delete_asset_icon_child_delete" id="delete_asset_icon_child_delete" value="" hidden>
                            <input type="text" name="asset_icon_child_delete" id="asset_icon_child_delete" value="" hidden>
                            <button type="button" class="btn btn-secondary" onclick="return submitForm()">{{$phrase->save}}&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                            &nbsp;&nbsp;<button type="button" class="btn btn-dark" onclick="hideForm()">{{$phrase->cancel}} &nbsp;&nbsp;<i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a class="btn btn-secondary" href="{{url('asset/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br> -->
                <div class="table-responsive table-hover table-sales">
                    <div id="printable-info-details">
                        <p class="card-title-print-1">Title: Resources </p>
                        <p class="card-title-print-2">Date & Time:
                            <?php  date_default_timezone_set('Asia/Kolkata'); $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                    </div>
                    <table class="table table-datatable" id="printable-area">
                        <thead style="background: #d6dcff;color: #000;">
                            <tr>
                                <th>#</th>
                                <th>{{$phrase->icon}}</th>
                                <th>{{$phrase->name}}</th>
                                <th>{{$phrase->type}}</th>
                                <th>{{$phrase->department_name}}</th>
                                @if($desig_permissions["mod13"]["del"] || $desig_permissions["mod13"]["edit"])
                                <th class="action-buttons">{{$phrase->action}}</th>
                                @endif
                            </tr>
                        </thead>
                        <?php $count=1; ?>
                        @if(isset($datas))
                        @foreach($datas as $data)
                        <tr>
                            <td width="40px;">{{$count++}}

                                <input type="text" value="{{$data->asset_id }}" name="asset_id_to_export[]" hidden >

                            </td>
                            <td>@if($data->asset_icon) <img src="{{$data->asset_icon}}" style="height: 50px;"> @endif</td>
                            <td>{{$data->asset_name}}</td>
                            <td>
                                <?php
                                if($data->movable == '1'){
                                    echo "Movable";
                                }
                                else{
                                    echo "Immovable";
                                }
                                ?>
                            </td>
                            <td>{{$data->dept_name}}</td>
                            @if($desig_permissions["mod13"]["del"] || $desig_permissions["mod13"]["edit"])
                            <td class="action-buttons">
                                @if($desig_permissions["mod13"]["del"])<a href="{{url('asset/delete')}}/{{$data->asset_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>@endif
                                &nbsp;&nbsp;@if($desig_permissions["mod13"]["edit"])<a href="javascirpt:void();" onclick="editAssetAjax('{{$data->asset_id}}')" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>@endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @endif
                        @if($count==1)
                        <tr>
                            <td colspan="8">
                                <center>No data to show</center>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
                <!-- export starts -->
                    <form action="{{url('asset/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data" id="export-form"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                    @csrf
                    <input type="text" name="asset_id"  hidden>
                    <input type="text" name="print"  hidden> <!-- hidden input for export (pdf/excel) -->
                    </form>
                <!-- export ends -->
</div>

<script>
    /* validation starts */
    // error variables as true = error occured
    var asset_name_error = true;
    var asset_icon_error = true;
    var movable_error = true;
    var department_error = true;
    var category_error = true;
    var subcategory_error = true;

    $(document).ready(function () {
        $("#asset_name").change(function () {
            asset_name_validate();
        });
        $("#asset_icon").change(function () {
            asset_icon_validate();
        });
        $("#movable").change(function () {
            movable_validate();
            get_category();
        });
        $("#dept_id").change(function () {
            department_validate();
        });
        $("#category").change(function () {
            category_validate();
            get_subcategory();
        });
        $("#subcategory").change(function () {
            subcategory_validate();
        });
    });


    //asset name validation
    function asset_name_validate() {
        var asset_name_val = $("#asset_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if (asset_name_val == "") {
            asset_name_error = true;
            $("#asset_name").addClass('is-invalid');
            $("#asset_name_error_msg").html("Asset Name should not be blank");
        } else if (!regAlphaNumericSpace.test(asset_name_val)) {
            asset_name_error = true;
            $("#asset_name").addClass('is-invalid');
            $("#asset_name_error_msg").html("Please enter valid asset");
        } else {
            asset_name_error = false;
            $("#asset_name").removeClass('is-invalid');
        }
    }

    //movable validation
    function movable_validate() {
        var movable_val = $("#movable").val();

        if (movable_val == "") {
            movable_error = true;
            $("#movable").addClass('is-invalid');
            $("#movable_error_msg").html("Type should not be blank");
        } else {
            movable_error = false;
            $("#movable").removeClass('is-invalid');
        }
    }

    // department name validation
    function department_validate() {
        var department_val = $("#dept_id").val();

        if (department_val == "") {
            department_error = true;
            $("#dept_id").addClass('is-invalid');
            $("#department_error_msg").html("Department Name should not be blank");
        } else {
            department_error = false;
            $("#dept_id").removeClass('is-invalid');
        }
    }

    //category validation
    function category_validate() {
        category_error = false;
    }

    //subcategory validation
    function subcategory_validate() {
        subcategory_error = false;
    }

    // asset_icon
    function asset_icon_validate() {
        var asset_icon_val = $("#asset_icon").val();
        var ext = asset_icon_val.substring(asset_icon_val.lastIndexOf('.') + 1);
        if (ext) // if selected
        {
            if (ext != "jpg" && ext != "jpeg" && ext != "png") {
                asset_icon_error = true;
                $("#asset_icon").addClass('is-invalid');
                $("#asset_icon_error_msg").html("Please select jpg/png image only");
            }
            else {
                asset_icon_error = false;
                $("#asset_icon").removeClass('is-invalid');
            }
        }
        else {
            asset_icon_error = false;
            $("#asset_icon").removeClass('is-invalid');
        }
    }

    // fetching category according to type i.e. movable/inmovable
    function get_category() {
        // resetting related fields fields
        $("#category").html('<option value="">--Select--</option>');
        $("#subcategory").html('<option value="">--Select--</option>');
        var movable_tmp = $("#movable").val(); // to ssend variable

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset/get-category')}}",
            data: { 'movable': movable_tmp },
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function (data) {
                $(".custom-loader").fadeIn(300);
            },
            error: function (xhr) {
                alert("error" + xhr.status + "," + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                console.log(data);
                for (var i = 0; i < data.category_data.length; i++) {
                    $("#category").append('<option value="' + data.category_data[i].asset_cat_id + '">' + data.category_data[i].asset_cat_name + '</option>');
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    // fetching sub category according to category
    function get_subcategory() {
        // resetting related fields
        $("#subcategory").html('<option value="">--Select--</option>');
        var asset_cat_id_tmp = $("#category").val(); // to send variable

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset/get-subcategory')}}",
            data: { 'asset_cat_id': asset_cat_id_tmp },
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function (data) {
                $(".custom-loader").fadeIn(300);
            },
            error: function (xhr) {
                alert("error" + xhr.status + "," + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                console.log(data);
                for (var i = 0; i < data.subcategory_data.length; i++) {
                    $("#subcategory").append('<option value="' + data.subcategory_data[i].asset_sub_id + '">' + data.subcategory_data[i].asset_sub_cat_name + '</option>');
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    function to_delete(image_path, e) {
        swal({
            title: 'Are you sure?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            buttons: {
                cancel: {
                    visible: true,
                    text: 'No, cancel!',
                    className: 'btn btn-danger'
                },
                confirm: {
                    text: 'Yes, delete it!',
                    className: 'btn btn-success'
                }
            }
        }).then((willDelete) => {
            if (willDelete) {
                $("#asset_icon_delete").val(image_path);
                $(e).closest("#asset_icon_delete_div").hide(200);
            }
        });
    }
    //child icon image delete
    function to_delete_child(image_path, e,resource_id) {
        swal({
            title: 'Are you sure?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            buttons: {
                cancel: {
                    visible: true,
                    text: 'No, cancel!',
                    className: 'btn btn-danger'
                },
                confirm: {
                    text: 'Yes, delete it!',
                    className: 'btn btn-success'
                }
            }
        }).then((willDelete) => {
            if (willDelete) {
                $("#asset_icon_child_delete").val($("#asset_icon_child_delete").val() + resource_id + ",");
                $("#delete_asset_icon_child_delete").val($("#delete_asset_icon_child_delete").val() + image_path + ",");
                $(e).closest("#asset_icon_delete_child_div").hide(200);
            }
        });
    }
    // final submission
    function submitForm() {
        asset_name_validate();
        asset_icon_validate();
        movable_validate();
        department_validate();
        category_validate();
        subcategory_validate();

        if (asset_name_error || asset_icon_error || movable_error || department_error || category_error || subcategory_error) {
            return false; // error occured
        }
        else {
            submitAssetAjax();
            return false; // proceed to submit form data
        }
    }

    function resetAssetForm() {
        document.getElementById("asset-form").reset();
        // document.getElementById("append-name-child").reset();
        $("#append-name-child").html("");
        $("#asset_icon_delete_div").hide(); // previous icon div
        $("#category").html("<option value=''>--Select--</option>"); //resetting category to null
        $("#subcategory").html("<option value=''>--Select--</option>"); // resetting subactegory to null
        $("#hidden_input_purpose").val("add"); // resetting hidden input purpose to add
        $("#hidden_input_id").val("NA"); // restting hidden input id to NA
    }

    function hideForm() {
        resetAssetForm(); // resetting form
        $("#show-toggle1").slideUp(150); // opening form div
    }
</script>

<script>
    function submitAssetAjax() {
        var formElement = $('#asset-form')[0];
        var form_data = new FormData(formElement);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset/store')}}",
            data: form_data,
            method: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function (data) {
                $(".custom-loader").fadeIn(300);
            },
            error: function (xhr) {
                // console.log(xhr);
                alert("error" + xhr.status + ", " + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                console.log(data);
                if (data.response == "success") {
                    resetAssetForm(); // resetting form
                    $("#toggle1").click(); // closing form div
                    swal("Success!", "New Resource has been added successfully.", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then((ok) => {
                        if (ok) {
                            document.location.reload();
                        }
                    });
                    setTimeout(function () { // reloading after successfully data saved
                        document.location.reload()
                    }, 3000);
                }
                else {
                    // error occured
                    swal("Error Occured!", data.response, {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });

                    // show individual error messages
                    if (data.asset_name_error) {
                        asset_name_error = true;
                        $("#asset_name").addClass('is-invalid');
                        $("#asset_name_error_msg").html(data.asset_name_error);
                    }
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    function editAssetAjax(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset/get-asset-details')}}",
            data: { 'asset_id': id },
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function (data) {
                $(".custom-loader").fadeIn(300);
            },
            error: function (xhr) {
                alert("error" + xhr.status + "," + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                console.log(data);
                // console.log(data.childs_parent[0].asset_name);
                // console.log(data.childs_parent[1].asset_name);

                if (data.response == "success") {
                    resetAssetForm(); // resetting form
                    $("#show-toggle1").slideDown(150); // opening form div
                    $("#hidden_input_purpose").val("edit"); // assigning as edit
                    $("#hidden_input_id").val(id); // assigning as edit id (asset_id)


                    /* appending form content */
                    $("#asset_name").val(data.asset_data.asset_name);
                    $("#dept_id").val(data.asset_data.dept_id);
                    $("#movable").val(data.asset_data.movable);
                    if (data.category_datas) {
                        $("#category").html('<option value="">--Select--</option>');
                        for (var i = 0; i < data.category_datas.length; i++) {
                            $("#category").append('<option value="' + data.category_datas[i].asset_cat_id + '">' + data.category_datas[i].asset_cat_name + '</option>');
                        }
                        setTimeout(function () { $("#category").val(data.asset_data.category_id || ""); }, 50);
                    }
                    else {
                        get_category(); // getting category data if no data from DB and type selected
                    }
                    // for subcategory
                    if (data.subcategory_datas) {
                        $("#subcategory").html('<option value="">--Select--</option>');
                        for (var i = 0; i < data.subcategory_datas.length; i++) {
                            $("#subcategory").append('<option value="' + data.subcategory_datas[i].asset_sub_id + '">' + data.subcategory_datas[i].asset_sub_cat_name + '</option>');
                        }
                        setTimeout(function () { $("#subcategory").val(data.asset_data.subcategory_id || ""); }, 50);
                    }
                    else {
                        get_subcategory(); // getting sub category data according to if no data from backend a category selected
                    }
                    if (data.asset_data.asset_icon) { // previous icon
                        $("#asset_icon_delete_div img").prop("src", '{{url("")}}/' + data.asset_data.asset_icon);
                        $("#asset_icon_delete_div span").attr("onclick", "to_delete('" + data.asset_data.asset_icon + "',this)");
                        $("#asset_icon_delete_div").show();
                    }

                    //for Child assets
                    $("#append-name-child").html("");//To remove the previous content
                    if (data.childs_parent.length != 0) {
                        for (var i = 0; i < data.childs_parent.length; i++) {
                            var to_append = `<tr>
                            <td><input type="text" class="form-control" name="child_name[]" value=\"`+ data.childs_parent[i].asset_name + `\" autocomplete="off"></td>
                            <td>
                                <select name="movable_child[]" id="movable_child" class="form-control" value=\"`+ data.childs_parent[i].movable + `\">
                                `
                            if (data.childs_parent[i].movable == 1) {
                                to_append += ` <option value="1">Movable</option>
                                        <option value="0">Immovable</option> `
                            }
                            else {
                                to_append += ` <option value="0">Immovable</option>
                                        <option value="1">Movable</option> `
                            }
                            to_append += ` </select>
                            </td>
                            <td><input type="file" name="child_asset_icon[]" class="form-control"></td> 
                            <td>`
                            if (data.childs_parent[i].asset_icon != "") {

                                to_append += `<div id="asset_icon_delete_child_div" style="padding:5px 0; ">
                                    <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                        <img src=`+ '{{url("")}}/' + data.childs_parent[i].asset_icon + ` style="height:55px;">
                                        <span onclick="to_delete_child('`+ data.childs_parent[i].asset_icon + `',this,'`+ data.childs_parent[i].asset_id + `')" style="position:absolute;top:0;right:0; background: rgba(0,0,0,0.5); cursor: pointer; padding: 3px 3px;" class="text-white" onclick=""><i class="fas fa-trash"></i></span>
                                    </div>
                                </div>`
                            }

                            to_append += ` 
                            </td>
                            <td><button type="button" class="btn btn-danger btn-xs delete-button-row-child" onclick="delete_child(`+ data.childs_parent[i].asset_id + `);"><i class="fas fa-trash-alt"></i></button>
                            <input type="text" name="asset_child_name_id[]" id="asset_child_name_id" value=\"`+ data.childs_parent[i].asset_id + `\" hidden></td>
                            </tr>`;

                            $("#append-name-child").append(to_append);
                            //onclick="to_delete('public/uploaded_documents/assets/assets-15783888493615.png',this)"
                            //onclick="to_delete('public/uploaded_documents/assets/assets-15783888493513.png)"
                            // $("#asset_icon_delete_child_div img").prop("src", '{{url("")}}/' + data.childs_parent[i].asset_icon);
                            // $("#asset_icon_delete_child_div span").attr("onclick", "to_delete('" + data.childs_parent[i].asset_icon + "',this)");
                            // $("#asset_icon_delete_child_div").show();
                        }
                    }
                    // scrolling to edit form
                    $('html, body').animate({
                        scrollTop: $("#show-toggle1").offset().top - 170
                    }, 500);
                }
                else { // data.response == "no_data"
                    swal("Error Occured!", "No such asset found!", {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                }

                $(".custom-loader").fadeOut(300);
            }
        });
    }
</script>

@endsection


<script>
    function printViewone() {
        window.print();
    }
</script>

<script>
    function exportSubmit(type)
    {
        $("input[name='print']").val(type);
        var values = $("input[name='asset_id_to_export[]']").map(function(){return $(this).val();}).get();
        $("input[name='asset_id']").val(values);
        document.getElementById('export-form').submit();
    }
</script>
<script>
function openmodel()
{
    // alert("afj;l");
    var search_element=$( "input[type=search]" ).val();
    $('#create-email').modal('show');
    $('#dept_search').val(search_element);
    // alert(search_element);
}

</script>


<div id="create-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">{{$phrase->send_email}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('send_mail')}}" method="post" id="FormValidation" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="card-body p-t-30" style="padding: 11px;">
                            <div class="form-group">
                                <input type="hidden" name="asset" value="asset">
                                <input type="hidden" name="data" value="{{$datas}}">
                                <input type="text" name="search_query" id="dept_search" hidden>
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div>
                            <div class="form-group">
                                <input type="text" name="to" class="form-control" placeholder="{{$phrase->to}}" required="">
                            </div>
                            <div class="form-group">
                                <input type="text" name="cc" class="form-control" placeholder="{{$phrase->cc}}" required="">
                            </div>

                            <div class="form-group">
                                <label for="subject" class="control-label">{{$phrase->subject}} <font color="red">*</font></label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="{{$phrase->subject}}" required="" aria-required="true">
                            </div>
                            <!-- <div class="form-group">
                                <label for="field-2" class="control-label">Message <font color="red">*</font></label>
                                <textarea class="wysihtml5 form-control article-ckeditor" required id="article-ckeditor"  placeholder="Message body" style="height: 100px" name="message" ></textarea>
                            </div> -->

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">{{$phrase->close}}</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">{{$phrase->send}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var append_i = 0;
    function append_table_data(type, data) {
        var to_append = `<tr>
                            <td><input type="text" class="form-control" name="child_name[]" autocomplete="off"></td>
                            <td>
                                <select name="movable_child[]" id="movable_child" class="form-control">
                                    <option value="1">Movable</option>
                                    <option value="0">Immovable</option>
                                </select>
                            </td>
                            <td><input type="file" name="child_asset_icon[]" class="form-control"></td> 
                            <td></td>
                            <td><button type="button" class="btn btn-danger btn-xs delete-button-row-child" onclick="delete_child();"><i class="fas fa-trash-alt"></i></button></td>
                        </tr>`;
        $("#append-name-child").append(to_append);
        append_i++;
    }

</script>
<script>
    // $(document).ready(function() {
    function delete_child(child_id) {
        // alert(child_id);
        $("#append-name-child").delegate(".delete-button-row-child", "click", function () {
            swal({
                title: 'Are you sure?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                buttons: {
                    cancel: {
                        visible: true,
                        text: 'No, cancel!',
                        className: 'btn btn-danger'
                    },
                    confirm: {
                        text: 'Yes, delete it!',
                        className: 'btn btn-success'
                    }
                }
            }).then((willDelete) => {
                if (willDelete) {
                    // alert(child_id);
                    if (child_id == null || child_id == 'undefined') {
                        $(this).closest("tr").remove();
                    }
                    else {
                        $("#deleted_asset_child_id").val($("#deleted_asset_child_id").val() + child_id + ",");
                        $(this).closest("tr").remove();
                    }

                }
            });
        });

    }



</script>