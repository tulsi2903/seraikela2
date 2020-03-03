@extends('layout.layout')

@section('title', 'Scheme Asset') 

@section('page-style')
<style>

</style>
@endsection

@section('page-content')
<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">{{$phrase->scheme_assets}}</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-asset')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;{{$phrase->back}}</a>
                </div>
            </div>
        </div>
    </div>

   
    <div class="card-body">        
        <form action="{{url('scheme-asset/store')}}" method="post" id="scheme-asset-form" enctype="multipart/form-data"> 
        @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="scheme_asset_name">{{$phrase->name}}<span style="color:red;margin-left:5px;">*</span></label>
                        <input name="scheme_asset_name" id="scheme_asset_name" class="form-control" autocomplete="off" value="{{$data->scheme_asset_name}}">
                        <div class="invalid-feedback" id="scheme_asset_name_error_msg"></div>
                    </div>
                    <div class="form-group" style="margin-top: 2.5em;">
                        <label for="geo_related">{{$phrase->geo_related}}</label>&nbsp;&nbsp;
                        <input type="checkbox" name="geo_related" id="geo_related" value="1" <?php echo ($data[ 'geo_related']==1 ? 'checked' : '');?>>
                    </div>
                    <hr/>
                    <h4>Measurement Details</h4>
                    <p>Will use to determinate duplicate work data</p>
                    <div style="padding:15px; border: 1px solid #c4c4c4; border-radius: 3px; margin-bottom: 15px;">
                        <div class="form-group">
                            <label for="uom_type_id">Measurement Type<font style="color:red;">*</font></label>                                     
                                <select name="uom_type_id" id="uom_type_id" class="form-control form-control">
                                    <option value="">--Select--</option>
                                    @foreach($uom_type_datas as $uom_type_data)
                                        <option value="{{$uom_type_data->uom_type_id}}" <?php if ($data->uom_type_id == $uom_type_data->uom_type_id) { echo "selected"; } ?>>{{$uom_type_data->uom_type_name}}</option>
                                    @endforeach                               
                                </select>
                            <div class="invalid-feedback" id="uom_type_id_error_msg"></div>
                        </div>
                        <div class="form-group">
                            <label for="radius">{{$phrase->radius}}<font style="color:red;">*</font></label>
                            <!-- <input name="radius" id="radius" class="form-control" autocomplete="off" value="{{$data->radius}}">
                            <div class="invalid-feedback" id="radius_error_msg"></div> -->
                            <div class="input-group">
                                <input name="radius" id="radius" class="form-control" value="{{$data->radius}}" maxlength="10" autocomplete="off">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="background: #212f51;color: white;" id="radius-append">
                                        <?php
                                        if ($data->uom_type_id == 1){
                                            echo "meter";
                                        }
                                        else if($data->uom_type_id == 2){
                                            echo "litre";
                                        }
                                        else{
                                            echo "unit";
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="radius_error_msg"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="no_of_tag">
                        <label for="mapmarkericon">{{$phrase->map_marker_icon}}</label>
                        <input type="file" name="mapmarkericon" id="mapmarkericon" class="form-control" autocomplete="off" value="{{$data->mapmarkericon}}">
                        @if($hidden_input_purpose=="edit"&&$data->mapmarkericon)
                        <div id="scheme_assets_delete_icon" style="min-height: 132px; padding:10px; border:1px solid #c4c4c4; border-radius: 0 0 5px 5px; background: white;">
                            <div>Previous Icon</div>
                            <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                <img src="{{url($data->mapmarkericon)}}" style="height:80px;">
                                <span style="position:absolute;top:0;right:0; background: rgba(202, 0, 0, 0.85); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick="to_delete_map_marker('{{$data->mapmarkericon}}',this)"><i class="fas fa-trash"></i></span>
                            </div>
                        </div>
                        @endif
                        <div class="invalid-feedback" id="map_marker_error_msg" ></div>
                        <input type="text" name="scheme_assets_delete" id="scheme_assets_delete" value="" hidden>           
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden="">
                        <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden="">
                        <button type="submit" class="btn btn-primary" style="float:left;" onclick="return submitForm();">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                    </div> 
                </div>
            </div>
        </form>
            <!-- <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="scheme_asset_name">{{$phrase->name}}<span style="color:red;margin-left:5px;">*</span></label>
                        <input name="scheme_asset_name" id="scheme_asset_name" class="form-control" autocomplete="off" value="{{$data->scheme_asset_name}}">
                        <div class="invalid-feedback" id="scheme_asset_name_error_msg"></div>
                    </div>
                </div>                               
                <div class="col-md-4">              
                    <div class="form-group" style="margin-top: 2.5em;">
                        <label for="geo_related">{{$phrase->geo_related}}</label>&nbsp;&nbsp;
                        <input type="checkbox" name="geo_related" id="geo_related" value="1" <?php echo ($data[ 'geo_related']==1 ? 'checked' : '');?>>
                    </div>
                </div>
                <div class="col-md-4"></div>     
                    
                <div class="col-md-4">            
                    <div class="form-group" id="no_of_tag">
                        <label for="mapmarkericon">{{$phrase->map_marker_icon}}</label>
                        <input type="file" name="mapmarkericon" id="mapmarkericon" class="form-control" autocomplete="off" value="{{$data->mapmarkericon}}">
                        @if($hidden_input_purpose=="edit"&&$data->mapmarkericon)
                        <div id="scheme_assets_delete_icon" style="min-height: 132px; padding:10px; border:1px solid #c4c4c4; border-radius: 0 0 5px 5px; background: white;">
                            <div>Previous Icon</div>
                            <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                <img src="{{url($data->mapmarkericon)}}" style="height:80px;">
                                <span style="position:absolute;top:0;right:0; background: rgba(202, 0, 0, 0.85); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick="to_delete_map_marker('{{$data->mapmarkericon}}',this)"><i class="fas fa-trash"></i></span>
                            </div>
                        </div>
                        @endif
                        <div class="invalid-feedback" id="map_marker_error_msg" ></div>
                        <input type="text" name="scheme_assets_delete" id="scheme_assets_delete" value="" hidden>           
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="radius">{{$phrase->radius}}<font style="color:red;">*</font></label>
                        <input name="radius" id="radius" class="form-control" autocomplete="off" value="{{$data->radius}}">
                        <div class="invalid-feedback" id="radius_error_msg"></div>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="uom_type_id">{{$phrase->uom}}<font style="color:red;">*</font></label>                                     
                            <select name="uom_type_id" id="uom_type" class="form-control form-control">
                                <option value="">--Select--</option>
                                @foreach($uom_datas as $uom_data_show)
                                <option value="{{$uom_data_show->uom_id}}" <?php if ($data->uom_type_id == $uom_data_show->uom_id) { echo "selected"; } ?>>{{$uom_data_show->uom_name}}</option>
                                @endforeach                               
                            </select>
                        <div class="invalid-feedback" id="uom_type_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden="">
                        <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden="">
                        <button type="submit" class="btn btn-primary" style="float:left;" onclick="return submitForm();">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                    </div>                    
                </div>  
            </div>end of row -->
    </div>  
</div>

    <script>
        var append_i = 0;
        function append_table_data(type, data){
            var to_append = `<tr>
                            <td>
                                <input type="text" class="form-control" name="attribute_name[]" autocomplete="off">
                                <div class="invalid-feedback">Please enter valid name</div>
                            
                                <select name="attribute_uom[]" class="form-control" style="display: none;">
                                    <option value="16" selected>---Select---</option>
                                    @foreach($uom_datas as $uom_data )
                                        <option value="{{ $uom_data->uom_id }}">{{ $uom_data->uom_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">UoM should not be blank</div>
                            </td> 
                            <td>
                                <input type="checkbox" name="attribute_mandatory[`+append_i+`]" value="1">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs delete-button-row"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>`;
            $("#append-name-uom").append(to_append);
            append_i++;
        }
        $(document).ready(function() {
            $("#append-name-uom").delegate(".delete-button-row", "click", function() {
                swal({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
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
                        $(this).closest("tr").remove();
                    }
                });
            });
        });
    </script>

    <!-- for validation -->
    <script>
        // resetting
        $(document).ready(function() {
            $("#scheme-asset-form").delegate("input, select, texarea", "keydown change", function(){
                $(this).removeClass("is-invalid");
            });
        });

        var scheme_asset_name_error = true;
        var marker_icon_error=true;
        var uom_type_id_error=true;
        var radius_error = true;

        $(document).ready(function() {
            $("#scheme_asset_name").change(function() {
                scheme_asset_name_validate();
            });
            $("#mapmarkericon").change(function() {
                mapmarker_validate();            
            });
            $("#uom_type_id").change(function(){
                edit_radius_unit();
                uom_type_id_validate();
            });
            $("#radius").change(function(){
                radius_validate();
            });
            $(document).on("keyup", "#radius", function(){
                $(this).val($(this).val().replace(/[^0-9.]+/g, ""));
            });
        });

        // to _append radius unit
        function edit_radius_unit(){
            var tmp_uom_type = $("#uom_type_id").val();
            if(tmp_uom_type==1){
                $("#radius-append").html("meter");
            }
            else if(tmp_uom_type==2){
                $("#radius-append").html("litre");
            }
            else{
                $("#radius-append").html("unit");
            }
        }

        function radius_validate(){
            var radius_val = $("#radius").val();
            var regNumericSpace = new RegExp('^[0-9.]*$');
            if (radius_val == "") {
                radius_error = true;
                $("#radius").addClass('is-invalid');
                $("#radius_error_msg").html("Radius should not be blank");
            }
            else if(radius_val.split('.').length>=3){
                radius_error=true;
                $("#radius").addClass('is-invalid');
                $("#radius_error_msg").html("Please enter valid radius");
            }
            else{
                radius_error = false;
                $("#radius").removeClass('is-invalid');
            }
        }

        function scheme_asset_name_validate() {
            var scheme_asset_name_val = $("#scheme_asset_name").val();
            var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9/_ -]+$');
            if (scheme_asset_name_val == "") {
                scheme_asset_name_error = true;
                $("#scheme_asset_name").addClass('is-invalid');
                $("#scheme_asset_name_error_msg").html("Scheme Asset Name should not be blank");
            }
            else if (!regAlphaNumericSpace.test(scheme_asset_name_val)) {
                scheme_asset_name_error = true;
                $("#scheme_asset_name").addClass('is-invalid');
                $("#scheme_asset_name_error_msg").html("Please enter valid name");
            }
            else {
                scheme_asset_name_error = false;
                $("#scheme_asset_name").removeClass('is-invalid');
            }
        }

        // map marker validate
        function mapmarker_validate() {
            var map_marker_val = $("#mapmarkericon").val();
            var ext = map_marker_val.substring(map_marker_val.lastIndexOf('.') + 1).toLowerCase();
            if (ext) // if selected
            {
                if (ext != "jpg" && ext != "jpeg" && ext != "png") {
                    // alert(ext);
                    marker_icon_error = true;
                    $("#mapmarkericon").addClass('is-invalid');
                    $("#map_marker_error_msg").html("Please select JPG/JPEG/PNG only");
                } else {
                    marker_icon_error = false;
                    $("#mapmarkericon").removeClass('is-invalid');
                }
            } else {
                marker_icon_error = false;
                $("#mapmarkericon").removeClass('is-invalid');
            }
        }

        function uom_type_id_validate() {
            var uom_type_val = $("#uom_type_id").val();
            if(uom_type_val==""){
                uom_type_id_error=true;
                $("#uom_type_id").addClass('is-invalid');
                $("#uom_type_id_error_msg").html("Measurement type should not be blank");
            }
            else {
                uom_type_id_error = false;
                $("#uom_type_id").removeClass('is-invalid');
            }      
        }
        function submitForm() {
            scheme_asset_name_validate();
            mapmarker_validate();
            uom_type_id_validate();
            radius_validate();

            if (scheme_asset_name_error || marker_icon_error || uom_type_id_error || radius_error) {
                return false;
            } // error occured
            else {
                return true;
            } // proceed to submit form data

        }
    </script>
    <script>
        function to_delete_map_marker(path, e) {
            $("#scheme_assets_delete").val(path);
            $(e).closest("#scheme_assets_delete_icon").fadeOut(300);
        }
    </script>
@endsection