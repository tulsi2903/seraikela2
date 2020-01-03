@extends('layout.layout')

@section('title', 'Asset')

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
</style>
@endsection

@section('page-content')

<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>

<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Asset</h4>
                <div class="card-tools">
                    <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                    <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                    <a href="{{url('asset/pdf/pdfURL')}}" target="_BLANK" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button></a>
                    <a href="{{url('asset/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary"><i class="fas fa-file-excel"></i></button></a>
                    @if($desig_permissions["asset"]["add"])
                        <a id="toggle1" onclick="resetAssetForm()" class="btn btn-secondary" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    @endif    
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="show-toggle1" style="border: 1px solid #ababab; padding: 15px; margin-bottom: 25px; border-radius: 3px;">
            <form action="{{url('asset/store')}}" onsubmit="return false" method="POST" enctype="multipart/form-data" id="asset-form">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asset_name">Asset Name<span style="color:red;margin-left:5px;">*</span></label>
                            <input type="text" name="asset_name" id="asset_name" class="form-control" value="{{$data->asset_name}}" autocomplete="off">
                            <div class="invalid-feedback" id="asset_name_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="movable">Type<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="movable" id="movable" class="form-control">
                                <option value="">--Select--</option>
                                <option value="1" <?php if($data->movable=='1'){ echo "selected"; } ?>>Movable</option>
                                <option value="0" <?php if($data->movable=='0'){ echo "selected"; } ?>>Immovable</option>
                            </select>
                            <div class="invalid-feedback" id="movable_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dept_id">Department Name<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="dept_id" id="dept_id" class="form-control">
                                <option value="">--Select--</option>
                                @foreach( $departments as $department )
                                <option value="{{ $department->dept_id }}" <?php if($data->dept_id==$department->dept_id){ echo "selected"; } ?>>
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
                            <label for="category">Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">--Select--</option>
                                @foreach( $categories as $category )
                                <option value="{{ $category->asset_cat_id }}" <?php if($data->category_id==$category->asset_cat_id){ echo "selected"; } ?>>
                                    {{ $category->asset_cat_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="category_error_msg"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="subcategory">Sub Category</label>
                            <select name="subcategory" id="subcategory" class="form-control">
                                <option value="">--Select--</option>
                                @foreach( $sub_categories as $sub_category )
                                <option value="{{ $sub_category->asset_sub_id }}" <?php if($data->subcategory_id==$sub_category->asset_sub_id){ echo "selected"; } ?>>
                                    {{ $sub_category->asset_sub_cat_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="subcategory_error_msg"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asset_icon">Asset Icon</label>
                            <input type="file" name="asset_icon" id="asset_icon" class="form-control">
                            <div id="asset_icon_delete_div" style="padding:5px 0; display: none;">
                                <div>Previous Icon</div>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <hr/>
                            <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="add" hidden>
                            <input type="text" name="hidden_input_id" id="hidden_input_id" value="NA" hidden>
                            <button type="button" class="btn btn-secondary" onclick="return submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                            &nbsp;&nbsp;<button type="button" class="btn btn-dark" onclick="hideForm()">Cancel&nbsp;&nbsp;<i class="fas fa-times"></i></button>
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
                    <table class="table table-datatable" id="printable-area">
                        <thead style="background: #d6dcff;color: #000;">
                            <tr>
                                <th>#</th>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Department Name</th>
                                <th class="action-buttons">Action</th>
                            </tr>
                        </thead>
                        <?php $count=1; ?>
                        @if(isset($datas))
                        @foreach($datas as $data)
                        <tr>
                            <td width="40px;">{{$count++}}</td>
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
                            <td class="action-buttons">
                                @if($desig_permissions["asset"]["del"])<a href="{{url('asset/delete')}}/{{$data->asset_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>@endif
                                &nbsp;&nbsp;@if($desig_permissions["asset"]["edit"])<a href="javascirpt:void();" onclick="editAssetAjax('{{$data->asset_id}}')" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>@endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @if($count==1)
                        <tr>
                            <td colspan="8">
                                <center>No data to shown</center>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
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
    function get_category(){
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

    function to_delete(image_path, e){
        swal({
            title: 'Are you sure?',
            // text: "You won't be able to revert this!",
            type: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : 'No, cancel!',
                    className: 'btn btn-danger'
                },
                confirm: {
                    text : 'Yes, delete it!',
                    className : 'btn btn-success'
                }
            }
        }).then((willDelete) => {
            if (willDelete) {
                $("#asset_icon_delete").val(image_path);
                $(e).closest("#asset_icon_delete_div").hide(200);
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

    function resetAssetForm(){
        document.getElementById("asset-form").reset();
        $("#asset_icon_delete_div").hide(); // previous icon div
        $("#category").html("<option value=''>--Select--</option>"); //resetting category to null
        $("#subcategory").html("<option value=''>--Select--</option>"); // resetting subactegory to null
        $("#hidden_input_purpose").val("add"); // resetting hidden input purpose to add
        $("#hidden_input_id").val("NA"); // restting hidden input id to NA
    }

    function hideForm(){
        resetAssetForm(); // resetting form
        $("#show-toggle1").slideUp(150); // opening form div
    }
</script>

<script>
    function submitAssetAjax(){
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
                alert("error" + xhr.status + ", " + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                console.log(data);
                if(data.response=="success"){
                    resetAssetForm(); // resetting form
                    $("#toggle1").click(); // closing form div
                    swal("Success!", "New asset has been added successfully.", {
                        icon : "success",
                        buttons: {
                            confirm: {
                                className : 'btn btn-success'
                            }
                        },
                    }).then((ok) => {
                        if (ok) {
                            document.location.reload();
                        }
                    });
                    setTimeout(function() { // reloading after successfully data saved
                            document.location.reload()
                    }, 3000);
                }
                else{
                    // error occured
                    swal("Error Occured!", data.response, {
                        icon : "error",
                        buttons: {
                            confirm: {
                                className : 'btn btn-danger'
                            }
                        },
                    });

                    // show individual error messages
                    if(data.asset_name_error){
                        asset_name_error = true;
                        $("#asset_name").addClass('is-invalid');
                        $("#asset_name_error_msg").html(data.asset_name_error);
                    }
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    function editAssetAjax(id){
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

                if(data.response=="success"){
                    resetAssetForm(); // resetting form
                    $("#show-toggle1").slideDown(150); // opening form div
                    $("#hidden_input_purpose").val("edit"); // assigning as edit
                    $("#hidden_input_id").val(id); // assigning as edit id (asset_id)


                    /* appending form content */
                    $("#asset_name").val(data.asset_data.asset_name);
                    $("#dept_id").val(data.asset_data.dept_id);
                    $("#movable").val(data.asset_data.movable);
                    if(data.category_datas){
                        $("#category").html('<option value="">--Select--</option>');
                        for (var i = 0; i < data.category_datas.length; i++) {
                            $("#category").append('<option value="' + data.category_datas[i].asset_cat_id + '">' + data.category_datas[i].asset_cat_name + '</option>');
                        }
                        $("#category").val(data.asset_data.category_id || "");
                    }
                    else{
                        get_category(); // getting category data if no data from DB and type selected
                    }
                    // for subcategory
                    if(data.subcategory_datas){
                        $("#subcategory").html('<option value="">--Select--</option>');
                        for (var i = 0; i < data.subcategory_datas.length; i++) {
                            $("#subcategory").append('<option value="' + data.subcategory_datas[i].asset_sub_id + '">' + data.subcategory_datas[i].asset_sub_cat_name + '</option>');
                        }
                        $("#subcategory").val(data.asset_data.subcategory_id || "");
                    }
                    else{
                        get_subcategory(); // getting sub category data according to if no data from backend a category selected
                    }
                    if(data.asset_data.asset_icon){ // previous icon
                        $("#asset_icon_delete_div img").prop("src", '{{url("")}}/'+data.asset_data.asset_icon);
                        $("#asset_icon_delete_div span").attr("onclick", "to_delete('"+data.asset_data.asset_icon+"',this)");
                        $("#asset_icon_delete_div").show();
                    }

                    // scrolling to edit form
                    $('html, body').animate({
                        scrollTop: $("#show-toggle1").offset().top - 170
                    }, 500);
                }
                else{ // data.response == "no_data"
                    swal("Error Occured!", "No such asset found!", {
                        icon : "error",
                        buttons: {
                            confirm: {
                                className : 'btn btn-danger'
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

<div id="create-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">Send Email</h4>
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
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div> 
                            <div class="form-group">  
                                <input type="text" name="to" class="form-control" placeholder="To" required="">
                            </div>
                            <div class="form-group">                           
                                <input type="text" name="cc" class="form-control" placeholder="CC" required="">
                            </div>
                           
                            <div class="form-group">
                                <label for="subject" class="control-label">Subject <font color="red">*</font></label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject"  required=""  aria-required="true">
                            </div>
                            <!-- <div class="form-group">
                                <label for="field-2" class="control-label">Message <font color="red">*</font></label>
                                <textarea class="wysihtml5 form-control article-ckeditor" required id="article-ckeditor"  placeholder="Message body" style="height: 100px" name="message" ></textarea>
                            </div> -->
                           
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>