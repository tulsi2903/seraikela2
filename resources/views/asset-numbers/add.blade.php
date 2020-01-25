@extends('layout.layout')

@section('title', 'Resources Number')

@section('page-content')
<style>
    .modal-content {
    position: relative;
    display: -webkit-box;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    / background-color: #fff; /
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: .3rem;
    outline: 0;
    background: linear-gradient(to bottom, #a5baef, #ffffff 70%, #ffffff, #ffffff 100%);
}
.modal-header {
    display: -webkit-box;
    display: flex;
    -webkit-box-align: start;
    align-items: flex-start;
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px dashed #000;
    border-top-left-radius: .3rem;
    border-top-right-radius: .3rem;
}
.modal-footer {
    display: -webkit-box;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: end;
    justify-content: flex-end;
    padding: 1rem;
    border-bottom: 1px dashed #999999;
    border-bottom-right-radius: .3rem;
    border-bottom-left-radius: .3rem;
    margin-top: -24px;
}

</style>

<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">{{$phrase->resource_number}}</h4>
                <div class="card-tools">
                    <a href="{{url('asset-numbers')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;{{$phrase->back}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form action="{{url('asset-numbers/store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="year">{{$phrase->year}}<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="year" id="year" class="form-control">
                            <option value="">---Select---</option>
                            @foreach($years as $year)
                            <option value="{{ $year->year_id}}" <?php if($data->year==$year->year_id){ echo "selected"; } ?>>{{ $year->year_value}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="year_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asset_id">{{$phrase->resource}}<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="asset_id" id="asset_id" class="form-control">
                            <option value="">---Select---</option>
                            @foreach( $assets as $asset )
                            <option value="{{ $asset->asset_id }}" <?php if($data->asset_id==$asset->asset_id){ echo "selected"; } ?>>{{ $asset->asset_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="asset_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="block_id">{{$phrase->block}}<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="block_id" id="block_id" class="form-control">
                            <option value="">---Select---</option>
                            @foreach( $block_datas as $block_data )
                            <option value="{{ $block_data->geo_id }}" <?php if($data->block_name==$block_data->geo_id){ echo "selected"; } ?> >{{ $block_data->geo_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="block_id_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="geo_id">{{$phrase->panchayat}}<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="geo_id" id="geo_id" class="form-control">
                            @if($hidden_input_purpose == 'edit')
                                @foreach($panchayats as $panchayat)
                                <option value="{{ $panchayat->geo_id }}" <?php if($data->geo_id==$panchayat->geo_id){ echo "selected"; } ?> >{{ $panchayat->geo_name }}</option>
                                @endforeach
                            @else
                                <option value="">---Select---</option>
                            @endif
                           
                        </select>
                        <div class="invalid-feedback" id="geo_id_error_msg"></div>

                    </div>
                </div>
            </div>
            <!-- <div class="form-group next-button">
                        <button type="button" class="btn btn-primary btn-secondary" onclick="next()" id="btn_hide">Next&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></button>
                    </div> -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group" id="previous_value_hide" style="display:none;">
                        <label for="previous_value">{{$phrase->previous_value}}</label>
                        <input type="text" name="previous_value" id="previous_value" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" id="current_value_hide" style="display:none;">
                        <label for="current_value">{{$phrase->current_value}}</label>
                        <input type="text" name="current_value" id="current_value" value="{{$data->current_value}}" class="form-control" autocomplete="off">
                        <div class="invalid-feedback" id="current_value_error_msg"></div>
                    </div>
                </div>
            </div>

            <div class="form-group" style="display:none;" id="append-location">
                <label>{{$phrase->resource_locations}}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label id="show_location_error"></label>
                <div class="table-responsive table-hover table-sales">
                    <table class="table">
                        <thead style="background: #d6dcff;color: #000;">
                            <tr>
                                <th>{{$phrase->selectToDelete}}</th>
                                <th>{{$phrase->location_Landmark}}</th>
                                <th>{{$phrase->latitude}}</th>
                                <th>{{$phrase->longitude}}</th>
                                <th style="text-align: center;" id="actionHide">{{$phrase->action}}</th>
                            </tr>
                        </thead>
                        <tbody id="append-location-delete">

                        </tbody>
                        <tbody id="append-location-new">

                        </tbody>
                    </table>
                </div>
                <div class="invalid-feedback" id="asset_location_error_msg"></div>
                <!-- longitude / latitude form -->
            </div>


            <!-- <div id="images-block" style="display:none;padding:15px 10px;">
                        <span class="btn"  style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-images"></i>&nbsp;&nbsp;Gallery</span>
                        <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Select Image(s)</label>
                                        <input type="file" name="images[]" id="images" class="form-control" placholder="Select image(s)" multiple>
                                        <div class="invalid-feedback" id="images_error_msg"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="images-block-gallery">
                                append images
                            </div>
                        </div>
                    </div> -->

            <div class="form-group" id="submit-buttons" style="margin-top:1em;">
                <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                <input type="text" name="hidden_input_id" id="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                <button type="submit" class="btn btn-primary" onclick=" return submitSave()"><span id="save-button-text">{{$phrase->next}}</span>&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></button>
            </div>
        </form>
    </div>
    <!-- for insert image loacation -->
    <div id="create-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top: 11em;">
                <div class="modal-header" style="border-top: 2px solid #5269a3">
                    <h4 class="modal-title mt-0" style="font-family: 'Bree Serif', serif;color:#000;">Gallery</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('asset-numbers/saveImagesforLoacation')}}" method="post" id="FormsaveImagesforLoacation" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="card-body p-t-30" style="padding: 11px;">
                                <div class="form-group">
                                    <input type="file" name="galleryFile[]" class="form-control" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="show_image_for_location" style="padding: 2em;">
                                <!-- append images -->
                            </div>
                        </div>
                    </div>

                    <input type="text" class="form-control" name="geo_image_id" id="geo_image_id" value="" hidden> <!--  geo_id -->
                    <input type="text" class="form-control" name="year_image_id" id="year_image_id" value="" hidden> <!--  year_id -->
                    <input type="text" class="form-control" name="image_asset_id" id="image_asset_id" value="" hidden> <!--  asset_id -->
                    <input type="text" class="form-control" name="geo_location_image_id" id="geo_location_image_id" value="" hidden> <!--  geo_location_id -->
                    <input type="text" class="form-control" name="asset_number_image_id" id="asset_number_image_id" value="" hidden> <!--  asset_number_id -->
                    <input type="text" class="form-control" name="asset_gallery_id" id="asset_gallery_id" value="" hidden> <!--  asset_number_id -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">{{$phrase->cancel}}</button>
                        <button type="submit" class="btn btn-info waves-effect waves-light">{{$phrase->save}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- for insert image loacation -->

    <script>
        /* validation starts */
        // error variables as true = error occured
        var year_error = true;
        var asset_id_error = true;
        var geo_id_error = true;
        var block_id_error = true;
        var images_error = true;

        $(document).ready(function () {
            $("#year").change(function () {
                year_validate();
                resetAll();
            });
            $("#asset_id").change(function () {
                asset_id_validate();
                resetAll();
            });
            $("#block_id").change(function(){
                block_id_validate();
                get_panchayat_datas();
                resetAll();
            });
            $("#geo_id").change(function () {
                geo_id_validate();
                resetAll();
            });

           
            // for images
            $("#images").change(function () {
                images_validate();
            });

        });

        //year validation
        function year_validate() {
            var year_val = $("#year").val();

            if (year_val == "") {
                year_error = true;
                $("#year").addClass('is-invalid');
                $("#year_error_msg").html("Year should not be blank");
            }

            else {
                year_error = false;
                $("#year").removeClass('is-invalid');
            }
        }

        //asset_id validation
        function asset_id_validate() {
            var asset_id_val = $("#asset_id").val();

            if (asset_id_val == "") {
                asset_id_error = true;
                $("#asset_id").addClass('is-invalid');
                $("#asset_id_error_msg").html("Asset Name should not be blank");
            }

            else {
                asset_id_error = false;
                $("#asset_id").removeClass('is-invalid');
            }
        }

        function block_id_validate() {
            var block_id_val = $("#block_id").val();
            if (block_id_val == "") {
                block_id_error = true;
                $("#block_id").addClass('is-invalid');
                $("#block_id_error_msg").html("Please select block");
            }
            else {
                block_id_error = false;
                $("#block_id").removeClass('is-invalid');
            }
        }
        //geo_id validation
        function geo_id_validate() {
            var geo_id_val = $("#geo_id").val();
            if (geo_id_val == "") {
                geo_id_error = true;
                $("#geo_id").addClass('is-invalid');
                $("#geo_id_error_msg").html("Panchayat should not be blank");
            }
            else {
                geo_id_error = false;
                $("#geo_id").removeClass('is-invalid');

            }
        }

        // for gallery section
        function images_validate() {
            var images_ext_error_tmp = false;
            var images_val = document.getElementById("images");
            if (images_val != null) {
                for (var i = 0; i < images_val.files.length; ++i) {
                    var ext = images_val.files[i].name.substring(images_val.files[i].name.lastIndexOf('.') + 1);
                    if (ext) // if selected
                    {
                        if (ext != "jpg" && ext != "jpeg" && ext != "png") {
                            images_ext_error_tmp = true;
                        }
                    }
                }
            }

            if (images_ext_error_tmp) {
                images_error = true;
                $("#images").addClass('is-invalid');
                $("#images_error_msg").html("Please select jpg/png image only");
            }
            else {
                images_error = false;
                $("#images").removeClass('is-invalid');
            }
            // var ext = asset_icon_val.substring(asset_icon_val.lastIndexOf('.') + 1);
            // if(ext) // if selected
            // {
            //     if(ext !="jpg" && ext!="jpeg" && ext!="png")
            //     {
            //         asset_icon_error = true;
            //         $("#asset_icon").addClass('is-invalid');
            //         $("#asset_icon_error_msg").html("Please select jpg/png image only");
            //     }
            //     else
            //     {
            //         asset_icon_error = false;
            //         $("#asset_icon").removeClass('is-invalid');
            //     }
            // }
            // else{
            //     asset_icon_error = false;
            //     $("#asset_icon").removeClass('is-invalid');
            // }
        }
    </script>

    <script>
        var movable = "no";
        var asset_location = [];
        var images = [];
        var step = 1; // 1 = initial inputs, 2 = after ajax call (pre current assigned), 3 = after location form load

        function ajaxFunc() { // getting current value with geo locations & movable=yes/no
            var year_tmp = $("#year").val();
            var asset_id_tmp = $("#asset_id").val();
            var geo_id_tmp = $("#geo_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('asset-numbers/current_value')}}",
                data: { 'year': year_tmp, 'asset_id': asset_id_tmp, 'geo_id': geo_id_tmp },
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function (data) {
                    $(".custom-loader").fadeIn(300);
                },
                error: function (xhr) {
                    $(".custom-loader").fadeOut(300);
                    alert("error" + xhr.status + ", " + xhr.statusText);
                },
                success: function (data) {
                    console.log(data);

                    $("#previous_value").val('0');
                    $("#current_value").val('0');
                    movable = "no";
                    asset_location = [];
                    images = [];
                    step = 2;

                    // assign movable = yes/no
                    if (data.movable == "no") {
                        movable = data.movable;
                    }
                    else {
                        movable = "yes";
                    }
                    // assign previous value
                    if (data.current_value) {
                        $("#previous_value").val(data.current_value);
                        $("#current_value").val(data.current_value);
                    }
                    // asset_location (previous entries to delete by user when current is less than pre)
                    if (data.asset_location) {
                        asset_location = data.asset_location;
                        appendLocation();
                    }
                    // images, if previous images/galleryhas been found
                    if (data.images) {
                        images = data.images;
                        appendImages();
                    }

                    // show previous/current value inputs
                    $("#previous_value_hide").show(300);
                    $("#current_value_hide").show(300);
                    $("#images-block").show(300);

                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    </script>

    <script>
        var loc_details = `<td><input type="text" name="location_name[]" id="location_name" class="form-control" autocomplete="off"></td><td><input type="text" name="latitude[]" id="latitude" class="form-control" autocomplete="off"></td><td><input type="text" name="longitude[]" id="longitude" class="form-control" autocomplete="off"></td>`;


        $(document).ready(function () {
            // detecting change in current value
            $("#current_value").change(function () {
                appendLocation();
            });
        });

        function appendLocation() {
            $geo_child_id = document.getElementById("geo_id").value;
            $year_child_id = document.getElementById("year").value;
            $asset_child_id = document.getElementById("asset_id").value;
            $hidden_input_id = document.getElementById("hidden_input_id").value;
            $("#append-location tbody").html("");
            $("#show_location_error").html("");
            $("#actionHide").hide();
            step = 3; // assigning steps
            $("#save-button-text").html("Save");
            var diff = parseFloat($("#current_value").val()) - parseFloat($("#previous_value").val()); //0-2 = -2
            // alert(diff);
            if (movable == "no") {
                // show location inputs
                if (diff > 0) {
                    
                    for (var i = 0; i < diff; i++) {
                        $("#append-location #append-location-new").append("<tr><td>" + (i + 1) + "</td><td><input type='text' name='edit_asset_geo_loc_id[]' value='' hidden><input type='text' name='location_name[]' class='form-control' autocomplete='off'></td><td><input type='text' name='latitude[]' class='form-control' autocomplete='off'></td><td><input type='text' name='longitude[]' class='form-control' autocomplete='off'></td></tr>");
                    }
                    $("#append-location #append-location-new").show(300);
                }
                else {
                    $("#append-location #append-location-new").hide(300);
                }
                $("#image_asset_id").val($asset_child_id);
                $("#year_image_id").val($year_child_id);
                $("#geo_image_id").val($geo_child_id);
                $("#asset_number_image_id").val($hidden_input_id);
                // show previous locations
                if (asset_location.length > 0) {
                    $("#actionHide").show();
                    // to show previous location for delete
                    for (var i = 0; i < asset_location.length; i++) {


                        // 
                        $("#append-location #append-location-delete").append("<tr>" +
                            "<td><input type='checkbox' name='delete_asset_geo_loc_id[]' value='" + asset_location[i].asset_geo_loc_id + "'></td><td><input type='text' name='edit_asset_geo_loc_id[]' value='" + asset_location[i].asset_geo_loc_id + "' hidden><input type='text' class='form-control' name='location_name[]' value='" + asset_location[i].location_name + "'></td><td><input type='text' class='form-control' name='latitude[]' value='" + asset_location[i].latitude + "'></td><td><input type='text' class='form-control' name='longitude[]' value='" + asset_location[i].longitude + "'></td>"
                            + "<td style='text-align: center;'><a class='btn btn-secondary btn-sm' title='Add Sub Resource Number' href=" + '{{url("asset_number/list_of_childs")}}/' + $asset_child_id + '/' + $geo_child_id + '/' + $year_child_id + '/' + $hidden_input_id + '/' + asset_location[i].asset_geo_loc_id + "><i class='fa fa-plus'></i></a>" +
                            "&nbsp;&nbsp;<a class='btn btn-secondary btn-sm'  title='Add Gallery' onclick='assetGeoLocIdFetch\(" + asset_location[i].asset_geo_loc_id + "," + $asset_child_id + "," + $year_child_id + "," + $geo_child_id + "," + $hidden_input_id + ")\' href='javascript:void();'><i class='fas fa-images'></i></a></td></tr>");
                    }
                    $("#append-location #append-location-delete").show(300);
                }
                else {
                    $("#append-location #append-location-delete").show(300);
                }
                // defining first row
                // alert($("#current_value").val());
                if (diff < 0) {
                    $("#show_location_error").prepend("<span style='color:red;'>Select any <b>" + (Math.abs(diff)) + "</b> location(s) to Delete</span>");
                }
                else {
                    if (asset_location.length > 0) { $("#show_location_error").prepend(""); }
                    else { $("#show_location_error").prepend(""); }
                }

                // show entire table
                $("#append-location").show(300);
            }
            else {

                $("#append-location tbody").html("");
            }

            $("#submit-buttons").show(300);
        }

        function assetGeoLocIdFetch(loc_id, asset_id, year_id, geo_id, hidden_input_id) {

            $.ajax({
                url: "{{url('asset_number/list_of_imagedata/')}}" + '/' + loc_id + '/' + asset_id + '/' + year_id + '/' + geo_id + '/' + hidden_input_id,
                method: "GET",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    console.log(data);
                    $("#asset_gallery_id").val("");
                    $("#show_image_for_location").html(""); // append=html
                    $('#create-email').modal();

                    $("#image_asset_id").val(data.asset_id);
                    $("#year_image_id").val(data.year_id);
                    $("#geo_image_id").val(data.geo_id);
                    $("#asset_number_image_id").val(data.hidden_input_id);
                    $("#geo_location_image_id").val(data.loc_id);


                    $("#asset_gallery_id").val(data.asset_gallery.asset_gallery_id);


                    if (data.asset_location_images.length > 0) {
                        var to_append = `<div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                    <div><label>Previous Images</label></div>
                                    `;

                        for (var i = 0; i < data.asset_location_images.length; i++) {
                            to_append += `<div class="images-delete-block" style="margin-right:5px; display:inline-block; position:relative; padding:3px;border:1px solid #c4c4c4;border-radious:3px;">
                                <img src="{{url('`+ data.asset_location_images[i] + `')}}" style="height:90px; min-height:90px; min-width:80px;">
                                <span style="position:absolute; top:3px; left:3px; border-radius: 3px; background: rgba(0,0,0,0.5); cursor: pointer; padding: 3px 6px;" class="text-white" onclick="to_delete('`+ data.asset_location_images[i] + `',this)"><i class="fas fa-trash" style="text-shadow: 0px 0px 2px black;"></i></span>
                            </div>`;
                        }

                        to_append += `</div>
                            </div>
                        <input type="text" class="form-control" name="gallery_images_delete" id="gallery_images_delete" value="" hidden> 
                        </div>`;

                        $("#show_image_for_location").html(to_append); // append=html
                    }

                }
            });
        }

    </script>

    <script>
        // final submit
        function submitSave() {
            if (step == 1) { // next
                year_validate();
                asset_id_validate();
                block_id_validate();
                geo_id_validate();
                $("#loc").show();
                if (year_error || asset_id_error || geo_id_error||block_id_error) {

                }
                else {
                    ajaxFunc();
                }
                return false;
            }
            else if (step == 2) {
                return false;
            }
            else { // step == 3
                error = true; // validate location_name (if diff>)
                // validate no of location selected for delete (else diff<)
                var diff = parseFloat(parseFloat($("#current_value").val()) - parseFloat($("#previous_value").val()));
                if (diff > 0) {
                    var location_name = document.getElementsByName('location_name[]');
                    var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
                    var tmp = false; // error
                    for (var i = 0; i < location_name.length; i++) {
                        if (location_name[i].value == "") {
                            tmp = true;
                        }
                    }

                    if (tmp) {
                        error = true;
                        $("#asset_location_error_msg").html("Please enter landmark/location");
                        $("#asset_location_error_msg").show();
                    }
                    else {
                        error = false;
                        $("#asset_location_error_msg").hide();
                    }
                }//closing of if
                if (diff < 0) {
                    var delete_asset_geo_loc_id = document.getElementsByName('delete_asset_geo_loc_id[]');
                    var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
                    var tmp = false; // error
                    var count = 0; // no of selected
                    for (var i = 0; i < delete_asset_geo_loc_id.length; i++) {
                        if (delete_asset_geo_loc_id[i].checked) {
                            count++;
                        }
                    }

                    if (Math.abs(diff) != count) {
                        tmp = true;
                    }

                    if (tmp) {
                        error = true;
                        $("#asset_location_error_msg").html("Please select " + Math.abs(diff) + " location(s) you want to delete");
                        $("#asset_location_error_msg").show();
                    }
                    else {
                        error = false;
                        $("#asset_location_error_msg").hide();
                    }
                }
                if (diff == 0) { // no change in numbers but may be change in images, so no error occurred
                    error = false;
                }

                // validating images
                images_validate();

                // final return
                if (error || images_error) {
                    return false;
                }
                else {
                    return true;
                }
            }//closing of else
        }

        // to reset everything if geo_id, year or asset_id changes
        /* for step = 1  i.e. (if step == 2)
        current value = null
        previous_value_hide  = hide
        current_value_hide = hide
        append_location = hide
        save-button-text = next
        */
        function resetAll() {
            if (step == 2 || step == 3) {
                $("#previous_value_hide").hide(300);
                $("#current_value_hide").hide(300);
                $("#append-location").hide(300);
                $("#save-button-text").html("Next");
                $("#current_value").val("");
                $("#images-block").hide(300);
                $("#images-block-gallery").html();
                step = 1;
            }
        }
    </script>


    <script>
        // for edit initialization
        $(document).ready(function () {
            var purpose = $("#hidden_input_purpose").val();

            if (purpose == "edit") {
                submitSave();
            }
        });

        function appendImages() {
            // if(images.length > 0){
            //     var to_append = `<div class="row">
            //                         <div class="col-12">
            //                             <div class="form-group">
            //                             <div><label>Previous Images</label></div>
            //                             `;

            //     for(var i=0;i<images.length;i++){
            //         to_append+=`<div class="images-delete-block" style="margin-right:5px; display:inline-block; position:relative; padding:3px;border:1px solid #c4c4c4;border-radious:3px;">
            //                         <img src="{{url('`+images[i]+`')}}" style="height:150px; min-height:150px; min-width:80px;">
            //                         <span style="position:absolute; top:3px; left:3px; border-radius: 3px; background: rgba(0,0,0,0.5); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick="to_delete('`+images[i]+`',this)"><i class="fas fa-trash" style="text-shadow: 0px 0px 2px black;"></i></span>
            //                     </div>`;
            //     }

            //     to_append+=`</div>
            //                     </div>
            //                 <input type="text" class="form-control" name="images_delete" id="images_delete" value="" hidden> 
            //             </div>`;

            //     $("#images-block-gallery").html(to_append); // append=html
            // }
        }

        var images_delete_val = new Array(); // array_stored fto append in hidden input for delete purpose
        function to_delete(image_path, e) {
            swal({
                title: 'Are you sure?',
                // text: "You won't be able to revert this!",
                type: 'warning',
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
                    images_delete_val.push(image_path);
                    $("#gallery_images_delete").val(images_delete_val);
                    $(e).closest(".images-delete-block").fadeOut(500);
                }
            });
        }
    
        function get_panchayat_datas(){
        var block_id_tmp = $("#block_id").val();
        $("#geo_id").html('<option value="">--Select--</option>');
        if(block_id_tmp)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('asset_number/get-panchayat-datas')}}",
                data: {'block_id':block_id_tmp},
                method:"GET",
                contentType:'application/json',
                dataType:"json",
                beforeSend: function(data){
                    $(".custom-loader").fadeIn(300);
                },
                error:function(xhr){
                    alert("error"+xhr.status+","+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success:function(data){
                    $("#geo_id").html('<option value="">--Select--</option>');
                    for(var i=0;i<data.length;i++){
                        $("#geo_id").append('<option value="'+data[i].geo_id+'" >'+data[i].geo_name+'</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }
    </script>

    @endsection