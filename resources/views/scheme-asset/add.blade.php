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
                <h4 class="card-title">Scheme Asset</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-asset')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card-body">
            <form action="{{url('scheme-asset/store')}}" method="POST" id="scheme-asset-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="scheme_asset_name">Name<span style="color:red;margin-left:5px;">*</span></label>
                            <input name="scheme_asset_name" id="scheme_asset_name" class="form-control" autocomplete="off" value="{{$data->scheme_asset_name}}">
                            <div class="invalid-feedback" id="scheme_asset_name_error_msg"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <div style="height:30px;"></div>
                            <label for="geo_related">Geo Related</label>&nbsp;&nbsp;
                            <input type="checkbox" name="geo_related" id="geo_related" value="1" <?php echo ($data[ 'geo_related']==1 ? 'checked' : '');?>>
                        </div>
                    </div>

                    <!-- <div class="col-md-2">
                        <div class="form-group" id="multiple_geo_tag">
                            <div style="height:30px;"></div>
                            <label for="multiple_geo_tags">Multiple Geo Tags</label>&nbsp;&nbsp;
                            <input type="checkbox" name="multiple_geo_tags" id="multiple_geo_tags" value="1" <?php echo ($data[ 'multiple_geo_tags']==1 ? 'checked' : '');?>>
                        </div>
                    </div> -->

                    <div class="col-md-3">
                        <div class="form-group" id="no_of_tag">
                            <label for="mapmarkericon">Map Marker Icon</label>
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
                            <!-- <span id="map_marker_error_msg"></span> -->
                            <div class="invalid-feedback" id="map_marker_error_msg" ></div>
                            <input type="text" name="scheme_assets_delete" id="scheme_assets_delete" value="" hidden>           
                        </div>
                    </div> 
                </div>
                <!--end of row-->

                <hr/>

                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Attributes</button>
                        <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                            <table class="table order-list" style="margin-top: 10px;">
                                <thead style="background: #cedcff">
                                    <tr>
                                        <th>Name<span style="color:red;margin-left:5px;">*</span></th>
                                        <!-- <th>UoM<span style="color:red;margin-left:5px;">*</span></th> -->
                                        <th width="130px;">Mandatory</th>
                                        <th width="130px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="append-name-uom">
                                    <!-- append attributes -->
                                    @if($data->attribute)
                                        <?php 
                                            $attributes  = unserialize($data->attribute);
                                            foreach($attributes as $attribute)
                                            { 
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="attribute_name[]" autocomplete="off" value="{{$attribute['name']}}">
                                                            <div class="invalid-feedback">Please enter valid name</div>
                                                        
                                                            <select name="attribute_uom[]" class="form-control" style="display: none;">
                                                                <option value="16" selected>---Select---</option>
                                                                @foreach($uom_datas as $uom_data )
                                                                    <option value="{{ $uom_data->uom_id }}" <?php if($uom_data->uom_id==$attribute['uom']){ echo "selected"; } ?> >{{ $uom_data->uom_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">UoM should not be blank</div>
                                                        </td> 
                                                        <td>
                                                            <input type="checkbox" name="attribute_mandatory[<?php echo $i; ?>]" value="1" <?php if($attribute['mandatory']==1){ echo "checked"; } ?>>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-xs delete-button-row"><i class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php
                                            $i++;
                                            }
                                        ?>
                                    @endif
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td><button type="button" onclick="append_table_data('add',null);" class="btn btn-secondary btn-sm btn-circle">Add <i class="fa fa-plus-circle" aria-hidden="true"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <br>

                <div class="form-group">
                    <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden >
                    <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                    <button type="submit" class="btn btn-primary" style="float:right;" onclick="return submitForm();">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                </div>
            </form>
        </div>
        
        <!--end of card body-->
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
        var attribute_name_error = true;
        var attribute_uom_error = true;
        var marker_icon_error=true;

        $(document).ready(function() {
            $("#scheme_asset_name").change(function() {
                scheme_asset_name_validate();
            });
            $("#mapmarkericon").change(function() {
                mapmarker_validate();
            });
        });

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

        function attribute_name_validate() {
            var attribute_name_val = $("input[name='attribute_name[]']");
            var regExp = new RegExp('^[a-zA-Z0-9/_ -]+$');

            console.log(attribute_name_val.length);
            for(i = 0;i < attribute_name_val.length;i++) {
                if(attribute_name_val[i].value == "") {
                    attribute_name_error = true;
                    $(attribute_name_val[i]).addClass('is-invalid');
                }
                else if(!regExp.test(attribute_name_val[i].value)){
                    attribute_name_error = true;
                    $(attribute_name_val[i]).addClass('is-invalid');
                }
                else {
                    attribute_name_error = false;
                    $(attribute_name_val[i]).removeClass('is-invalid');
                }
            }

            if (attribute_name_val.length == 0) {
                attribute_name_error = false;
            }
        }

        function attribute_uom_validate() {
            var attribute_uom_val = $("select[name='attribute_uom[]']");
            for (i = 0; i < attribute_uom_val.length; i++) {
                if (attribute_uom_val[i].value == "") {
                    attribute_uom_error = true;
                    $(attribute_uom_val[i]).addClass('is-invalid');
                }
                else {
                    attribute_uom_error = false;
                    $(attribute_uom_val[i]).removeClass('is-invalid');
                }
            }
            if (attribute_uom_val.length == 0) {
                attribute_uom_error = false;
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
    
        function submitForm() {
            scheme_asset_name_validate();
            attribute_name_validate();
            attribute_uom_validate();
            mapmarker_validate();
            if (scheme_asset_name_error || attribute_name_error || attribute_uom_error || marker_icon_error) {
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