@extends('layout.layout')

@section('title', 'Define Schemes')

@section('page-style')
<style>
    .independent-scheme-blocks, .group-scheme-blocks{
        display: none;
    }
    #define-scheme-form-inner{
        display: none;
    }
    #attributes-block{
        display: none;
    }
</style>
@endsection

@section('page-content')
<div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="float:left;"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;Define Scheme</div>
            </div><br><br>
            <!-----------------------------------------start of Scheme Detail Form------------------------------------------>
            <div class="card-body" style="margin-top: -57px;">
                <form action="{{url('scheme-structure/store')}}" enctype="multipart/form-data" method="POST" id="define-scheme-form">
                    @csrf()
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input type="radio" name="scheme_related" id="scheme_related_1" value="1">&nbsp;&nbsp;<label for="scheme_related_1">Independent Scheme</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <!-- <input type="radio" name="scheme_related" id="scheme_related_2" value="2">&nbsp;&nbsp;<label for="scheme_related_2">Group</label> -->
                            </div>
                        </div>
                    </div>

                    <div id="define-scheme-form-inner">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="scheme_name">Scheme Name<span style="color:red;margin-left:5px;">*</span></label>
                                    <input type="text" name="scheme_name" id="scheme_name" class="form-control" value="{{$data->scheme_name}}" autocomplete="off">
                                    <div class="invalid-feedback" id="scheme_name_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="scheme_short_name">Short Name<span style="color:red;margin-left:5px;">*</span></label>
                                    <input type="text" name="scheme_short_name" id="scheme_short_name" class="form-control" value="{{$data->scheme_short_name}}" autocomplete="off">
                                    <div class="invalid-feedback" id="scheme_short_name_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="scheme_type_id">Scheme Type<span style="color:red;margin-left:5px;">*</span></label>
                                    <select name="scheme_type_id" id="scheme_type_id" class="form-control">
                                        <option value="">---Select---</option>
                                        @foreach( $scheme_types as $scheme_type )
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
                                    <label for="scheme_asset_id">Scheme Assset</label>
                                    <select name="scheme_asset_id" id="scheme_asset_id" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach($scheme_asset_datas as $scheme_asset_data)
                                            <option value="{{$scheme_asset_data->scheme_asset_id}}">{{$scheme_asset_data->scheme_asset_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="scheme_asset_id_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-2 group-scheme-blocks">
                                <div class="form-group">
                                    <label>Scheme Group</label>
                                    <select name="scheme_group_id" id="scheme_group_id" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach($scheme_group_datas as $scheme_group_data)
                                            <option value="{{$scheme_group_data->scheme_group_id}}">{{$scheme_group_data->scheme_group_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="scheme_group_id_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="dept_id">Department<span style="color:red;margin-left:5px;">*</span></label>
                                    <select name="dept_id" id="dept_id" class="form-control">
                                        <option value="">---Select---</option>
                                        @foreach( $departments as $department )
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
                                        <option value="">---Select---</option>
                                        <option value="1" <?php if ($data->is_active == '1') {
                                                                echo "selected";
                                                            } ?>>Active</option>
                                        <option value="0" <?php if ($data->is_active == '0') {
                                                                echo "selected";
                                                            } ?>>Inactive</option>
                                    </select>
                                    <div class="invalid-feedback" id="status_error_msg"></div>
                                </div>
                            </div>
                        </div>
                        <!--end of row-->
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description">{{$data->description}}</textarea>
                                    <div class="invalid-feedback" id="description_error_msg"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="attachment">Attachment</label>
                                    <div class="input-icon">
                                        <input type="file" class="form-control" name="attachment" id="attachment" placeholder="Download Document" multiple="multiple">
                                        <span class="input-icon-addon">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="attachment_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="scheme_logo">Scheme Logo</label>
                                    <div class="input-icon">
                                        <input type="file" class="form-control" name="scheme_logo" id="scheme_logo" placeholder="Scheme Logo" accept="image/*">
                                        <span class="input-icon-addon">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="scheme_logo_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="scheme_map_marker">Map Marker Icon</label>
                                    <div class="input-icon">
                                        <input type="file" class="form-control" name="scheme_map_marker" id="scheme_map_marker" placeholder="Map Marker Icon" accept="image/*">
                                        <span class="input-icon-addon">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="scheme_map_marker_error_msg"></div>
                                </div>
                            </div>
                        </div>
                        <div id="attributes-block">
                            <div class="col-md-11">
                                <span class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-sort-amount-up"></i> &nbsp;Attributes</span>
                                <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                                    <table class=" table order-list" style="margin-top: 10px;">
                                        <thead style="background: #cedcff">
                                            <tr>
                                                <th>Name</th>
                                                <th>Unit</th>
                                                <th>Performance</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attributes-block-append">
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="4"><button type="button" class="btn btn-primary btn-round btn-xs" value="Add Row" id="addrow" style="float: right;"><i class="fa fa-plus"></i></button> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <input type="text" name="hidden_input_attachment" id="hidden_input_attachment" value="{{$data->attachment}}" hidden>
                            <input type="text" name="hidden_input_scheme_logo" id="hidden_input_scheme_logo" value="{{$data->scheme_logo}}" hidden>
                            <input type="text" name="hidden_input_map_marker" id="hidden_input_map_marker" value="{{$data->scheme_map_marker}}" hidden>
                            <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                            <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                            <button class="btn btn-secondary" id="submit-button" onclick="return submitForm()">Save&nbsp;<i class="fas fa-check"></i></button>
                        </div>
                    </div>
                </form>
                <!-----------------------------------------end of User Form------------------------------------------>
            </div>
        </div>
    </div>
</div>


<script>
    /*
    to intialize forms block (independent, group) according to scheme_type selected
    */
    var scheme_related = "";
    $(document).ready(function(){
        $("input[name='scheme_related']").change(function(){
            define_scheme_form_reset();
            var scheme_related  = $("input[name='scheme_related']:checked").val();
            $("#define-scheme-form-inner").fadeIn(200);
            if(scheme_related == "1"){
                $(".independent-scheme-blocks").fadeIn(200);
                $("#submit-button").html("Save&nbsp;<i class='fas fa-check'></i>");
            }
            else if(scheme_related == "2"){
                $(".group-scheme-blocks").fadeIn(200);
                $("#submit-button").html("Next&nbsp;<i class='fas fa-arrow-right'></i>");
            }
        });
    });

    // reset form
    function define_scheme_form_reset(){
        $("#define-scheme-form-inner").hide();
        $(".independent-scheme-blocks").hide();
        $(".group-scheme-blocks").hide();
    }
</script>

<script>
    
</script>


<script>
    /* validation starts */
    // error variables as true = error occured
    var scheme_related  = true;
    var scheme_name_error = true;
    var scheme_short_name_error = true;
    var scheme_type_id_error = true;
    var scheme_asset_id_error = true;
    var scheme_group_id_error = true;
    var dept_id_error = true;
    var status_error = true;
    var description_error = true;
    var attachment_error = true;
    var scheme_logo_error = true;
    var scheme_map_marker_error = true;

    $(document).ready(function() {
        // $("#scheme_name").change(function() {
        //     scheme_name_validate();
        // });
        // $("#scheme_short_name").change(function() {
        //     scheme_short_name_validate();
        // });
        // $("#scheme_type_id").change(function() {
        //     scheme_type_id_validate();
        // });
        // $("#scheme_asset_id").change(function() {
        //     scheme_asset_id_validate();
        // });
        // $("#scheme_type_id").change(function() {
        //     scheme_type_id_validate();
        // });
        // $("#scheme_group_id").change(function() {
        //     scheme_group_id_validate();
        // });
        // $("#dept_id").change(function() {
        //     dept_id_validate();
        // });
        // $("#status").change(function() {
        //     status_validate();
        // });
        // $("#description").change(function() {
        //     description_validate();
        // });
        // $("#attachment").change(function() {
        //     attachment_validate();
        // });
        // $("#scheme_logo").change(function() {
        //     scheme_logo_validate();
        // });
        // $("#scheme_map_marker").change(function() {
        //     scheme_map_marker_validate();
        // });
    });


    // //scheme name validation
    // function scheme_name_validate() {
    //     var scheme_name_val = $("#scheme_name").val();
    //     var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
    //     if (scheme_name_val == "") {
    //         scheme_name_error = true;
    //         $("#scheme_name").addClass('is-invalid');
    //         $("#scheme_name_error_msg").html("Scheme Name should not be blank");
    //     } else if (!regAlphaNumericSpace.test(scheme_name_val)) {
    //         scheme_name_error = true;
    //         $("#scheme_name").addClass('is-invalid');
    //         $("#scheme_name_error_msg").html("Please enter valid scheme");
    //     } else {
    //         scheme_name_error = false;
    //         $("#scheme_name").removeClass('is-invalid');
    //     }
    // }

    // //scheme short name validation
    // function scheme_short_name_validate() {
    //     var scheme_short_name_val = $("#scheme_short_name").val();
    //     var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
    //     if (scheme_short_name_val == "") {
    //         scheme_short_name_error = true;
    //         $("#scheme_short_name").addClass('is-invalid');
    //         $("#scheme_short_name_error_msg").html("Scheme Short Name should not be blank");
    //     } else if (!regAlphaNumericSpace.test(scheme_short_name_val)) {
    //         scheme_short_name_error = true;
    //         $("#scheme_short_name").addClass('is-invalid');
    //         $("#scheme_short_name_error_msg").html("Please enter valid short name");
    //     } else {
    //         scheme_short_name_error = false;
    //         $("#scheme_short_name").removeClass('is-invalid');
    //     }
    // }

    // //is-active validation
    // function is_active_validate() {
    //     var is_active_val = $("#is_active").val();

    //     if (is_active_val == "") {
    //         is_active_error = true;
    //         $("#is_active").addClass('is-invalid');
    //         $("#is_active_error_msg").html("Status should not be blank");
    //     } else {
    //         is_active_error = false;
    //         $("#is_active").removeClass('is-invalid');
    //     }
    // }



    // // department name validation
    // function department_name_validate() {
    //     var department_name_val = $("#dept_id").val();


    //     if (department_name_val == "") {
    //         department_name_error = true;
    //         $("#dept_id").addClass('is-invalid');
    //         $("#department_name_error_msg").html("Department Name should not be blank");
    //     } else {
    //         department_name_error = false;
    //         $("#dept_id").removeClass('is-invalid');
    //     }
    // }

    // // scheme type validation
    // function scheme_type_validate() {
    //     var scheme_type_val = $("#scheme_type_id").val();


    //     if (scheme_type_val == "") {
    //         scheme_type_error = true;
    //         $("#scheme_type_id").addClass('is-invalid');
    //         $("#scheme_type_error_msg").html("Scheme Type should not be blank");
    //     } else {
    //         scheme_type_error = false;
    //         $("#scheme_type_id").removeClass('is-invalid');
    //     }
    // }

    // // independent validation
    // function independent_validate() {
    //     var independent_val = $("#independent").val();


    //     if (independent_val == "") {
    //         independent_error_msg = true;
    //         $("#independent").addClass('is-invalid');
    //         $("#independent_error_msg").html("Independent should not be blank");
    //     } else {
    //         independent_error_msg = false;
    //         $("#independent").removeClass('is-invalid');
    //     }
    // }

    // //indicator name validation
    // function indicator_name_validate() {
    //     var indicator_name_val = $("#indicator_name").val();
    //     var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9_]+$');
    //     if (indicator_name_val == "") {
    //         indicator_name_error = true;
    //         $("#indicator_name").addClass('is-invalid');
    //         $("#indicator_name_error_msg").html("Indicator Name should not be blank");
    //     } else if (!regAlphaNumericSpace.test(indicator_name_val)) {
    //         indicator_name_error = true;
    //         $("#indicator_name").addClass('is-invalid');
    //         $("#indicator_name_error_msg").html("Please enter valid Indicator Name");
    //     } else {
    //         indicator_name_error = false;
    //         $("#indicator_name").removeClass('is-invalid');
    //     }
    // }

    // //uom validate
    // function uom_validate() {
    //     var uom_val = $("#uom").val();
    //     if (uom_val == "") {
    //         uom_error = true;
    //         $("#uom").addClass('is-invalid');
    //         $("#uom_error_msg").html("Unit should not be blank");
    //     } else {
    //         uom_error = false;
    //         $("#uom").removeClass('is-invalid');
    //     }
    // }

    // //performance validate
    // function performance_validate() {
    //     var performance_val = $("#performance").val();
    //     if (performance_val == "") {
    //         performance_error = true;
    //         $("#performance").addClass('is-invalid');
    //         $("#performance_error_msg").html("Performance should not be blank");
    //     } else {
    //         performance_error = false;
    //         $("#performance").removeClass('is-invalid');
    //     }
    // }

    
    // function get_attributes(){
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         url: "{{url('scheme-structure/get-attributes')}}",
    //         data: form_data,
    //         method: "GET",
    //         contentType: 'application/json',
    //         dataType: "json",
    //         processData: false,
    //         beforeSend: function(data){
    //             $(".custom-loader").fadeIn(300);
    //         },
    //         error: function(xhr){
    //             alert("error"+xhr.status+", "+xhr.statusText);
    //             $(".custom-loader").fadeOut(300);
    //         },
    //         success: function (data){
    //             console.log(data);
    //             if(data.response=="success"){
    //                 reset_target_block();
    //                 swal("Success!", "Scheme target datas has been saved", {
    //                     icon : "success",
    //                     buttons: {
    //                         confirm: {
    //                             className : 'btn btn-success'
    //                         }
    //                     },
    //                 });
    //                 setTimeout(function() {
    //                         document.location.reload()
    //                 }, 3000);
    //             }
    //             else{
    //                 // error occured
    //             }
    //             $(".custom-loader").fadeOut(300);
    //         }
    //     });
    // }

    // next function
    function next(){
        if(scheme_related=="1"){ // independent scheme
            submitForm(); // for final submission
        }
        else{ // scheme_related=="2" i.e. group scheme
            // 1. call ajax for attributes
            // get_attributes();

            // 2. change scheme_related=2, and change submit button
            $("#submit-button").html("Save&nbsp;<i class='fas fa-check'></i>");
        }
    }

    // final submission
    function submitForm() {
        // scheme_name_validate();
        // scheme_short_name_validate();
        // is_active_validate();
        // department_name_validate();
        // scheme_type_validate();
        // independent_validate();
        // indicator_name_validate();
        // uom_validate();
        // performance_validate();

        // if (scheme_name_error || scheme_short_name_error || is_active_error || department_name_error || scheme_type_error || independent_error_msg || indicator_name_error || uom_error || performance_error) {
        //     return false;
        // } // error occured
        // else {
        //     return true;
        // } // proceed to submit form data
        return true;
    }
</script>

@endsection