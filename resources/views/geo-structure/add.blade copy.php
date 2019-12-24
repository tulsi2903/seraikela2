@extends('layout.layout')

@section('title', 'Geo Structure')

@section('page-content')
<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Geo Structure</h4>
                <div class="card-tools">
                    <a href="{{url('geo-structure')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">

        <form action="{{url('geo-structure/store')}}" method="POST" id="geo-structure-form">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Geo Name<span style="color:red;margin-left:5px;">*</span></label>
                        <input type="text" name="geo_name" id="geo_name" class="form-control" value="{{$data->geo_name}}">
                        <div class="invalid-feedback" id="geo_name_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="org_id">Organisation<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="org_id" id="org_id" class="form-control">
                            <option value="">-Select-</option>
                            @foreach($organisation_datas as $organisation_data)
                            <option value="{{$organisation_data->org_id}}" <?php if($data->org_id==$organisation_data->org_id){ echo "selected"; } ?>>{{$organisation_data->org_name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="org_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="level_id">Level<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="level_id" id="level_id" class="form-control">
                            <option value="">-Select-</option>
                            <option value="1" <?php if($data->level_id=='1'){ echo "selected"; } ?>>District</option>
                            <option value="2" <?php if($data->level_id=='2'){ echo "selected"; } ?>>Sub Division</option>
                            <option value="3" <?php if($data->level_id=='3'){ echo "selected"; } ?>>Block</option>
                            <option value="4" <?php if($data->level_id=='4'){ echo "selected"; } ?>>Panchayat</option>
                        </select>
                        <div class="invalid-feedback" id="level_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4" id="number_of_villages">
                    <div class="form-group">
                        <label for="name">Number Of Villages</label>
                        <input type="text" name="no_of_villages" id="no_of_villages" class="form-control" value="">
                        <div class="invalid-feedback" id="no_of_villages_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4" id="select-div-district">
                    <div class="form-group">
                        <label for="dist_id">District</label>
                        <select name="dist_id" id="dist_id" class="form-control">
                            <option value="">-Select-</option>
                            @foreach($geo_structure_datas as $geo_structure_data)
                            @if($geo_structure_data->level_id=='1'&&$data->geo_id!=$geo_structure_data->geo_id)
                            <option value="{{$geo_structure_data->geo_id}}" <?php if($data->dist_id==$geo_structure_data->geo_id){ echo "selected"; } ?>>{{$geo_structure_data->geo_name}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="dist_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4" id="select-div-sub-division">
                    <div class="form-group">
                        <label for="sd_id">Sub Division</label>
                        <select name="sd_id" id="sd_id" class="form-control">
                            <option value="">-Select-</option>
                            @foreach($geo_structure_datas as $geo_structure_data)
                            @if($geo_structure_data->level_id=='2'&&$data->geo_id!=$geo_structure_data->geo_id)
                            <option value="{{$geo_structure_data->geo_id}}" data-select-div-sub-division-option="{{$geo_structure_data->parent_id}}" <?php if($data->sd_id==$geo_structure_data->geo_id){ echo "selected"; } ?>>{{$geo_structure_data->geo_name}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="sd_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4" id="select-div-block">
                    <div class="form-group">
                        <label for="bl_id">Block</label>
                        <select name="bl_id" id="bl_id" class="form-control">
                            <option value="">-Select-</option>
                            @foreach($geo_structure_datas as $geo_structure_data)
                            @if($geo_structure_data->level_id=='3'&&$data->geo_id!=$geo_structure_data->geo_id)
                            <option value="{{$geo_structure_data->geo_id}}" data-select-div-block-option="{{$geo_structure_data->parent_id}}" <?php if($data->bl_id==$geo_structure_data->geo_id){ echo "selected"; } ?>>{{$geo_structure_data->geo_name}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="bl_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="officer_id">Officer<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="officer_id" id="officer_id" class="form-control">
                            <option value="">-Select-</option>
                            @foreach($user_datas as $user_data)
                            <option value="{{$user_data->id}}" <?php if($data->officer_id==$user_data->id){ echo "selected"; } ?>>{{$user_data->first_name}} {{$user_data->last_name}} ({{$user_data->desig_name}})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="officer_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top:1em;">
                    <div class="form-group">
                        <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                        <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                        <button type="button" class="btn btn-secondary" onclick="initialize()">Reset&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                    </div>
                </div>
        </form>
    </div>
</div>
<!--end of row-->
</div>




<script>
    // assigning level for validation purpose, level is set by changeSelect()
    var level = 0; // means no level selected
    /* validations */
    // intializing error variables
    var geo_name_error = true;
    var org_id_error = true;
    var level_id_error = true;
    var dist_id_error = true;
    var sd_id_error = true;
    var bl_id_error = true;
    var officer_id_error = true;



    $(document).ready(function () {
        $("#geo_name").change(function () {
            geo_name_validate();
        });
        $("#org_id").change(function () {
            org_id_validate();
        });
        $("#level_id").change(function () {
            level_id_validate();

        });
        $("#dist_id").change(function () {
            dist_id_validate();
        });
        $("#sd_id").change(function () {
            sd_id_validate();
        });
        $("#bl_id").change(function () {
            bl_id_validate();
        });
        $("#officer_id").change(function () {
            officer_id_validate();
        });

    });

    // geo name vallidation
    function geo_name_validate() {
        var geo_name_val = $("#geo_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if (geo_name_val == "") {
            geo_name_error = true;
            $("#geo_name").addClass('is-invalid');
            $("#geo_name_error_msg").html("Geo name should not be blank");
        }
        else if (!regAlphaNumericSpace.test(geo_name_val)) {
            geo_name_error = true;
            $("#geo_name").addClass('is-invalid');
            $("#geo_name_error_msg").html("Please enter valid geo name");
        }
        else {
            geo_name_error = false;
            $("#geo_name").removeClass('is-invalid');
        }
    }

    // org_id validation
    function org_id_validate() {
        var org_id_val = $("#org_id").val();
        if (org_id_val == "") {
            org_id_error = true;
            $("#org_id").addClass('is-invalid');
            $("#org_id_error_msg").html("Please select organisation");
        }
        else {
            org_id_error = false;
            $("#org_id").removeClass('is-invalid');
        }
    }

    // level_id validation
    function level_id_validate() {
        var level_id_val = $("#level_id").val();
        if (level_id_val == "") {
            level_id_error = true;
            $("#level_id").addClass('is-invalid');
            $("#level_id_error_msg").html("Please select level");
        }
        else {
            level_id_error = false;
            $("#level_id").removeClass('is-invalid');
        }
    }

    // dist_id validation
    function dist_id_validate() {
        var dist_id_val = $("#dist_id").val();
        if (level >= 2) {
            if (dist_id_val == "") {
                dist_id_error = true;
                $("#dist_id").addClass('is-invalid');
                $("#dist_id_error_msg").html("Please select district");
            }
            else {
                dist_id_error = false;
                $("#dist_id").removeClass('is-invalid');
            }
        }
        else {
            dist_id_error = false;
            $("#dist_id").removeClass('is-invalid');
        }
    }

    // sd_id validation
    function sd_id_validate() {
        var sd_id_val = $("#sd_id").val();
        if (level >= 3) {
            if (sd_id_val == "") {
                sd_id_error = true;
                $("#sd_id").addClass('is-invalid');
                $("#sd_id_error_msg").html("Please select sub division");
            }
            else {
                sd_id_error = false;
                $("#sd_id").removeClass('is-invalid');
            }
        }
        else {
            sd_id_error = false;
            $("#sd_id").removeClass('is-invalid');
        }
    }

    // bl_id validation
    function bl_id_validate() {
        var bl_id_val = $("#bl_id").val();
        if (level >= 4) {
            if (bl_id_val == "") {
                bl_id_error = true;
                $("#bl_id").addClass('is-invalid');
                $("#bl_id_error_msg").html("Please select block");
            }
            else {
                bl_id_error = false;
                $("#bl_id").removeClass('is-invalid');
            }
        }
        else {
            bl_id_error = false;
            $("#bl_id").removeClass('is-invalid');
        }
    }





    // officer_id validation
    function officer_id_validate() {
        var officer_id_val = $("#officer_id").val();
        if (officer_id_val == "") {
            officer_id_error = true;
            $("#officer_id").addClass('is-invalid');
            $("#officer_id_error_msg").html("Please assign officer");
        }
        else {
            officer_id_error = false;
            $("#officer_id").removeClass('is-invalid');
        }
    }


    // final submission
    function submitForm() {
        geo_name_validate();
        org_id_validate();
        level_id_validate();
        dist_id_validate();
        sd_id_validate();
        bl_id_validate();
        officer_id_validate();

        if (geo_name_error || org_id_error || level_id_error || dist_id_error || sd_id_error || bl_id_error || officer_id_error) { return false; } // error occured
        else { return true; } // proceed to submit form data
    }


    /* initiliazing and changing "select" option according to level */
    $(document).ready(function () {
        // intialize everything from beginning forms
        initialize();

        // changing "select" options according to level
        $("#level_id").change(function () {
            changeSelect();
        });
        $("#sd_id").change(function () {
            changeBlockSelect('na');
        });
        $("#dist_id").change(function () {
            changeSubDivisionSelect('na');
        });
    });

    function initialize() {
        document.getElementById('geo-structure-form').reset();
        $("input, textarea, select").removeClass('is-invalid');
        $("#select-div-district").hide(0);
        $("#select-div-sub-division").hide(0);
        $("#select-div-block").hide(0);
        changeSelect();
        changeBlockSelect('initiialize');
        changeSubDivisionSelect('initiialize');
    }

    function changeSelect() {
        $("#select-div-district").hide(0);
        $("#select-div-sub-division").hide(0);
        $("#select-div-block").hide(0);
        $('#number_of_villages').hide(0);
        level = $("#level_id").val();
        if (level == "1") {
            // 
        }
        else if (level == "2") {
            $("#select-div-district").fadeIn(300);
        }
        else if (level == "3") {
            $("#select-div-district").fadeIn(300);
            $("#select-div-sub-division").fadeIn(300);
        }
        else if (level == "4") {
            $("#select-div-district").fadeIn(300);
            $("#select-div-sub-division").fadeIn(300);
            $("#select-div-block").fadeIn(300);
            $('#number_of_villages').fadeIn(300);
        }
    }

    function changeBlockSelect(t) {
        // resetting, hiding
        if (t != 'initiialize') {
            $("#bl_id").val("");
        }
        $('#bl_id > option').not(":eq(0)").hide(300);
        // showing exact options
        var sd_id = $("#sd_id").val();
        $('#bl_id > [data-select-div-block-option="' + sd_id + '"]').show();
    }

    function changeSubDivisionSelect(t) {
        // resetting, hiding
        if (t != 'initiialize') {
            $("#sd_id").val("");
        }
        $('#sd_id > option').not(":eq(0)").hide(300);
        // showing exact options
        var dist_id = $("#dist_id").val();
        $('#sd_id > [data-select-div-sub-division-option="' + dist_id + '"]').show();
    }
</script>

@endsection