@extends('layout.layout')

@section('title', 'Define Schemes')

@section('page-style')
<style>

</style>
@endsection

@section('page-content')
<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Define Schemes</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-structure')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>
    <!-----------------------------------------start of Scheme Detail Form------------------------------------------>
    <div class="card-body">
        <form action="{{url('scheme-structure/store')}}" enctype="multipart/form-data" method="POST" id="define-scheme-form">
            @csrf()
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="scheme_name">Scheme Name<span style="color:red;margin-left:5px;">*</span></label>
                        <input type="text" name="scheme_name" id="scheme_name" class="form-control" value="{{$data->scheme_name}}" autocomplete="off" maxlength="300">
                        <div class="invalid-feedback" id="scheme_name_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="scheme_short_name">Short Name<span style="color:red;margin-left:5px;">*</span></label>
                        <input type="text" name="scheme_short_name" id="scheme_short_name" class="form-control" value="{{$data->scheme_short_name}}" autocomplete="off" maxlength="100">
                        <div class="invalid-feedback" id="scheme_short_name_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="scheme_type_id">Scheme Type<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="scheme_type_id" id="scheme_type_id" class="form-control">
                            <option value="">--Select--</option>
                            @foreach( $scheme_type_datas as $scheme_type )
                            <option value="{{ $scheme_type->sch_type_id }}" <?php if ($data->scheme_type_id == $scheme_type->sch_type_id) {
                                                                                        echo "selected";
                                                                                    } ?>>{{ $scheme_type->sch_type_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="scheme_type_id_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="dept_id">Department<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="dept_id" id="dept_id" class="form-control">
                            <option value="">--Select--</option>
                            @foreach( $department_datas as $department )
                            <option value="{{ $department->dept_id }}" <?php if ($data->dept_id == $department->dept_id) {
                                                                                    echo "selected";
                                                                                } ?>>{{ $department->dept_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="dept_id_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Status<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="status" id="status" class="form-control">
                            <option value="">--Select--</option>
                            <option value="1" <?php if ($data->status == '1') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                            <option value="0" <?php if ($data->status == '0') {
                                                            echo "selected";
                                                        } ?>>Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="status_error_msg"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" rows="15" name="description">{{$data->description}}</textarea>
                                <div class="invalid-feedback" id="description_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-12">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="attachment">Attachment</label>
                        <input type="file" class="form-control" name="attachment" id="attachment">
                        <div class="invalid-feedback" id="attachment_error_msg"></div>
                        @if($hidden_input_purpose=="edit" && $data->attachment)
                            <div id="scheme_attachment_delete_div" style="min-height: 132px; padding:10px; border:1px solid #c4c4c4; border-radius: 0 0 5px 5px; background: white;">
                                <div>Previous Attachment</div>
                                <div style="display: inline-block; position:relative; padding:8px; width: 100%; border:1px solid #c4c4c4; border-radius:3px;">
                                    <a href="{{url($data->attachment)}}" target="_blank">Click to view</a>
                                    <span style="position:absolute;top:0;right:0; background: rgba(202, 0, 0, 0.85); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick="to_delete_attachment('{{$data->attachment}}',this)"><i class="fas fa-trash"></i></span>
                                </div>
                            </div>
                        @endif
                        <input type="text" name="scheme_attachment_delete" id="scheme_attachment_delete" value="" hidden>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="scheme_logo">Scheme Logo</label>
                        <input type="file" name="scheme_logo" id="scheme_logo" class="form-control">
                        <div class="invalid-feedback" id="scheme_logo_error_msg"></div>
                        @if($hidden_input_purpose=="edit"&&$data->scheme_logo)
                            <div id="scheme_logo_delete_div" style="min-height: 132px; padding:10px; border:1px solid #c4c4c4; border-radius: 0 0 5px 5px; background: white;">
                                <div>Previous Icon</div>
                                <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                    <img src="{{url($data->scheme_logo)}}" style="height:80px;">
                                    <span style="position:absolute;top:0;right:0; background: rgba(202, 0, 0, 0.85); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick="to_delete_scheme_logo('{{$data->scheme_logo}}',this)"><i class="fas fa-trash"></i></span>
                                </div>
                            </div>
                        @endif
                        <input type="text" name="scheme_logo_delete" id="scheme_logo_delete" value="" hidden>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="scheme_map_marker">Map Marker Icon</label>
                        <input type="file" name="scheme_map_marker" id="scheme_map_marker" class="form-control">
                        <div class="invalid-feedback" id="scheme_map_marker_error_msg"></div>
                        @if($hidden_input_purpose=="edit"&&$data->scheme_map_marker)
                            <div id="scheme_map_marker_delete_div" style="min-height: 132px; padding:10px; border:1px solid #c4c4c4; border-radius: 0 0 5px 5px; background: white;">
                                <div>Previous Icon</div>
                                <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                    <img src="{{url($data->scheme_map_marker)}}" style="height:80px;">
                                    <span style="position:absolute;top:0;right:0; background: rgba(202, 0, 0, 0.85); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick="to_delete_map_marker('{{$data->scheme_map_marker}}',this)"><i class="fas fa-trash"></i></span>
                                </div>
                            </div>
                        @endif
                        <input type="text" name="scheme_map_marker_delete" id="scheme_map_marker_delete" value="" hidden>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" name="hidden_input_attachment" id="hidden_input_attachment" value="{{$data->attachment}}" hidden>
                        <input type="text" name="hidden_input_scheme_logo" id="hidden_input_scheme_logo" value="{{$data->scheme_logo}}" hidden>
                        <input type="text" name="hidden_input_map_marker" id="hidden_input_map_marker" value="{{$data->scheme_map_marker}}" hidden>
                        <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                        <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                        <button type="submit" class="btn btn-secondary" id="submit-button" onclick="return submitForm()">Save&nbsp;<i class="fas fa-check"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <!-----------------------------------------end of User Form------------------------------------------>
    </div>
</div>

<script>
    function to_delete_attachment(path, e){
        $("#scheme_attachment_delete").val(path);
        $(e).closest("#scheme_attachment_delete_div").fadeOut(300);
    }
    function to_delete_scheme_logo(path, e){
        $("#scheme_logo_delete").val(path);
        $(e).closest("#scheme_logo_delete_div").fadeOut(300);
    }
    function to_delete_map_marker(path, e){
        $("#scheme_map_marker_delete").val(path);
        $(e).closest("#scheme_map_marker_delete_div").fadeOut(300);
    }
</script>


<script>
    /* validation starts */
    // error variables as true = error occured
    var scheme_name_error = true;
    var scheme_short_name_error = true;
    var scheme_type_id_error = true;
    var dept_id_error = true;
    var status_error = true;
    var description_error = true;
    var attachment_error = true;
    var scheme_logo_error = true;
    var scheme_map_marker_error = true;

    $(document).ready(function () {
        $("#scheme_name").change(function () {
            scheme_name_validate();
        });
        $("#scheme_short_name").change(function () {
            scheme_short_name_validate();
        });
        $("#scheme_type_id").change(function () {
            scheme_type_id_validate();
        });
        $("#dept_id").change(function () {
            dept_id_validate();
        });
        $("#status").change(function () {
            status_validate();
        });
        $("#description").change(function () {
            description_validate();
        });
        $("#attachment").change(function () {
            attachment_validate();
        });
        $("#scheme_logo").change(function () {
            scheme_logo_validate();
        });
        $("#scheme_map_marker").change(function () {
            scheme_map_marker_validate();
        });
    });


    //scheme name validation
    function scheme_name_validate() {
        var scheme_name_val = $("#scheme_name").val();
        if (scheme_name_val == "") {
            scheme_name_error = true;
            $("#scheme_name").addClass('is-invalid');
            $("#scheme_name_error_msg").html("Scheme Name should not be blank");
        }
        else {
            scheme_name_error = false;
            $("#scheme_name").removeClass('is-invalid');
        }
    }

    //scheme short name validation
    function scheme_short_name_validate() {
        var scheme_short_name_val = $("#scheme_short_name").val();
        if (scheme_short_name_val == "") {
            scheme_short_name_error = true;
            $("#scheme_short_name").addClass('is-invalid');
            $("#scheme_short_name_error_msg").html("Scheme Short Name should not be blank");
        }
        else {
            scheme_short_name_error = false;
            $("#scheme_short_name").removeClass('is-invalid');
        }
    }

    // scheme type validation
    function scheme_type_id_validate() {
        var scheme_type_id_val = $("#scheme_type_id").val();

        if (scheme_type_id_val == "") {
            scheme_type_id_error = true;
            $("#scheme_type_id").addClass('is-invalid');
            $("#scheme_type_id_error_msg").html("Scheme Type should not be blank");
        }
        else {
            scheme_type_id_error = false;
            $("#scheme_type_id").removeClass('is-invalid');
        }
    }

    // department name validation
    function dept_id_validate() {
        var dept_id_val = $("#dept_id").val();
        if (dept_id_val == "") {
            dept_id_error = true;
            $("#dept_id").addClass('is-invalid');
            $("#dept_id_error_msg").html("Department Name should not be blank");
        } else {
            dept_id_error = false;
            $("#dept_id").removeClass('is-invalid');
        }
    }


    // status validation
    function status_validate() {
        var status_val = $("#status").val();
        if (status_val == "") {
            status_error = true;
            $("#status").addClass('is-invalid');
            $("#status_error_msg").html("Please select status");
        }
        else {
            status_error = false;
            $("#status").removeClass('is-invalid');
        }
    }

    //description validation
    function description_validate() {
        var description_val = $("#description").val();
        description_error = false;
    }

    // attachment validate
    function attachment_validate() {
        var attachment_val = $("#attachment").val();
        var ext = attachment_val.substring(attachment_val.lastIndexOf('.') + 1).toLowerCase();
        if (ext) // if selected
        {
            if (ext != "doc" && ext != "docx" && ext != "pdf" && ext != "ppt" && ext != "pptx" && ext != "xls" && ext != "xlsx") {
                attachment_error = true;
                $("#attachment").addClass('is-invalid');
                $("#attachment_error_msg").html("Please select PDF/DOC/PPT/XLS only");
            }
            else {
                attachment_error = false;
                $("#attachment").removeClass('is-invalid');
            }
        }
        else {
            attachment_error = false;
            $("#attachment").removeClass('is-invalid');
        }
    }


    // attachment validate
    function scheme_logo_validate() {
        var scheme_logo_val = $("#scheme_logo").val();
        var ext = scheme_logo_val.substring(scheme_logo_val.lastIndexOf('.') + 1).toLowerCase();
        if (ext) // if selected
        {
            if (ext != "jpg" && ext != "jpeg" && ext != "png") {
                scheme_logo_error = true;
                $("#scheme_logo").addClass('is-invalid');
                $("#scheme_logo_error_msg").html("Please select JPG/JPEG/PNG only");
            }
            else {
                scheme_logo_error = false;
                $("#scheme_logo").removeClass('is-invalid');
            }
        }
        else {
            scheme_logo_error = false;
            $("#scheme_logo").removeClass('is-invalid');
        }
    }


    // map marker validate
    function scheme_map_marker_validate() {
        var scheme_map_marker_val = $("#scheme_map_marker").val();
        var ext = scheme_map_marker_val.substring(scheme_map_marker_val.lastIndexOf('.') + 1).toLowerCase();
        if (ext) // if selected
        {
            if (ext != "jpg" && ext != "jpeg" && ext != "png") {
                scheme_map_marker_error = true;
                $("#scheme_map_marker").addClass('is-invalid');
                $("#scheme_map_marker_error_msg").html("Please select JPG/JPEG/PNG only");
            }
            else {
                scheme_map_marker_error = false;
                $("#scheme_map_marker").removeClass('is-invalid');
            }
        }
        else {
            scheme_map_marker_error = false;
            $("#scheme_map_marker").removeClass('is-invalid');
        }
    }


    // final submission
    function submitForm() {
        scheme_name_validate();
        scheme_short_name_validate();
        scheme_type_id_validate();
        dept_id_validate();
        status_validate();
        description_validate();
        attachment_validate();
        scheme_logo_validate();
        scheme_map_marker_validate();

        if (scheme_name_error || scheme_short_name_error || scheme_type_id_error || dept_id_error || status_error || description_error || attachment_error || scheme_logo_error || scheme_map_marker_error) {
            return false;
        } // error occured
        else {
            return true;
        } // proceed to submit form data
    }

    // // disabling submit on pressing enter
    // $("#define-scheme-form").bind("keydown", function (e) {
    //     if (e.keyCode === 13) return false;
    // });
</script>

@endsection