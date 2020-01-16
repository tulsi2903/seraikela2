@extends('layout.layout')

@section('title', 'Scheme Performance')

@section('page-style')
<style>

</style>
@endsection

@section('page-content')
<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Scheme Performance</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card-body">
            <div class="search-block">
                <form action="{{url('scheme-performance/add-datas')}}" method="GET" onsubmit="return false;">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="scheme_id">Scheme<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="scheme_id" id="scheme_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($scheme_datas as $scheme )
                                    <option value="{{ $scheme->scheme_id }}">({{$scheme->scheme_short_name}}) {{ $scheme->scheme_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="scheme_id_error_msg"></div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="year_id">Year<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="year_id" id="year_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($year_datas as $year_data )
                                    <option value="{{ $year_data->year_id }}">{{ $year_data->year_value }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="year_id_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="block_id">Block<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="block_id" id="block_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $block_datas as $block_data )
                                    <option value="{{ $block_data->geo_id }}">{{ $block_data->geo_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="block_id_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="panchayat_id">Panchayat</label>
                                <select name="panchayat_id" id="panchayat_id" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                                <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div style="height:30px;"></div>
                                <button type="button" class="btn btn-secondary go-button" onclick="go()"><i class="fas fa-search"></i>&nbsp;&nbsp;Go</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <hr />
            <div class="enter-datas-block">
                <button type="button" class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Enter Datas</button>
                <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                    <div style="padding: 15px 0; overflow: hidden; color: black;">
                        <div style="display: inline-block; float: left; font-size: 16px;">
                            <b>Data Saved:</b> 0
                        </div>
                        <span style="display: none;" id="excelformat">
                            <!-- <a href="\{{url('scheme-performance/downloadFormat')}}?scheme_id={{$scheme->scheme_id}}&year_id={{$year_data->year_id}}&block_id={{$block_data->geo_id}}" class="btn" style="float:right; background: #349601; color: white;" title="Download Excel Format"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Import Format</a>
                            <a href="{{url('scheme-performance/viewimport')}}?scheme_id={{$scheme->scheme_id}}&year_id={{$year_data->year_id}}&block_id={{$block_data->geo_id}}" class="btn" style="float:right; background: #349601; color: white;margin-right: 10px;" ><i class="fas fa-file-import"></i>&nbsp;&nbsp;Import</a> -->
                            <button type="submit" class="btn btn-primary" onclick="location.href='\ scheme-performance/downloadFormat?scheme_id='+ document.getElementById('scheme_id').value+'&year_id={{$year_data->year_id}}&block_id={{$block_data->geo_id}}'" style="float:right; background: #349601; color: white;" title="Download Excel Format"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Excel Format</button>
                            <button type="submit" class="btn btn-primary" onclick="location.href='\ scheme-performance/viewimport?scheme_id='+ document.getElementById('scheme_id').value+'&year_id={{$year_data->year_id}}&block_id={{$block_data->geo_id}}'" style="float:right; background: #349601; color: white;;margin-right: 10px;" title="Import Excel"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Import</button>
                        </span>
                    </div>
                    <div id="to_append_table" style="display: none;">
                        <form action="{{url('scheme-performance/store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <table class="table">
                                <thead id="to_append_thead" style="background: #cedcff">

                                </thead>
                                <tbody id="to_append_tbody">
                                    <!-- append details -->
                                </tbody>
                            </table>
                            <div style="text-align: right;">
                                <button type="button" class="btn btn-secondary btn-sm btn-circle" onclick="appendRow()">Add&nbsp;&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                            </div>
                            <hr />
                            <!-- hidden inputs -->
                            <input type="hidden" name="scheme_id" id="scheme_id_hidden">
                            <input type="hidden" name="year_id" id="year_id_hidden">
                            <input type="hidden" name="panchayat_id" id="panchayat_id_hidden">
                            <input type="text" name="to_delete" id="to_delete" >
                            <!-- hidden inputs -->
                            <button type="submit" class="btn btn-secondary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<div id="create-gallery" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">Gallery</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('scheme_performance/galleryFile_update')}}" method="post" id="FormsaveImagesforLoacation" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="card-body p-t-30" style="padding: 11px;">
                            <div class="form-group">
                                <input type="file" name="galleryFile[]" class="form-control" multiple>
                            </div>
                        </div>
                        <div id="show_image_for_location">
                            <!-- append images -->
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" name="scheme_performance_id" id="scheme_performance_id" > <!--  scheme_performance_id -->
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div id="create-coordinates" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">Coordinates</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('scheme_performance/coordinatesupdate')}}" method="post" id="FormsaveImagesforLoacation" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="card-body p-t-30" style="padding: 11px;">
                            <div class="form-group">
                                <input type="text" name="coordinates_lang_value" id="coordinates_lang_value" placeholder="Longitude" class="form-control" Required >
                                <input type="text" name="coordinates_lat_value" id="coordinates_lat_value" placeholder="Latitude" class="form-control" Required >
                                
                            </div>
                        </div>
                        <div id="cordinates_details">
                            <!-- append images -->
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" name="scheme_performance_id" id="scheme_performance_id_for_coordinates" > <!--  scheme_performance_id -->
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // to append row
    var to_append_row = "";

    // defining error = true as default
    var scheme_id_error = true;
    var year_id_error = true;
    var block_id_error = true;
    var panchayat_id_error = true;


    $(document).ready(function () {
        $("#scheme_id").change(function () {
            scheme_id_validate();
        })
        $("#year_id").change(function () {
            year_id_validate();
        });
        $("#block_id").change(function () {
            block_id_validate();
            get_panchayat_datas();
        });
        $("#panchayat_id").change(function () {
            panchayat_id_validate();
        });

        // for restting details
        $("#scheme_id, #year_id, #block_id, #panchayat_id").change(function () {
            resetEnterDatasBlock();
        });
    });

    function scheme_id_validate() {
        var scheme_id_val = $("#scheme_id").val();
        if (scheme_id_val == "") {
            scheme_id_error = true;
            $("#scheme_id").addClass('is-invalid');
            $("#scheme_id_error_msg").html("Please select a scheme");
        }
        else {
            scheme_id_error = false;
            $("#scheme_id").removeClass('is-invalid');
        }
    }

    function year_id_validate() {
        var year_id_val = $("#year_id").val();
        if (year_id_val == "") {
            year_id_error = true;
            $("#year_id").addClass('is-invalid');
            $("#year_id_error_msg").html("Please select a year");
        }
        else {
            year_id_error = false;
            $("#year_id").removeClass('is-invalid');
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

    function panchayat_id_validate() {
        var panchayat_id_val = $("#panchayat_id").val();
        if (panchayat_id_val == "") {
            panchayat_id_error = true;
            $("#panchayat_id").addClass('is-invalid');
            $("#panchayat_id_error_msg").html("Please select block");
        }
        else {
            panchayat_id_error = false;
            $("#panchayat_id").removeClass('is-invalid');
        }
    }


    function get_panchayat_datas() {
        var block_id_tmp = $("#block_id").val();
        $("#panchayat_id").html('<option value="">--Select--</option>');
        if (block_id_tmp) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('scheme-performance/get-panchayat-datas')}}",
                data: { 'block_id': block_id_tmp },
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
                    $("#panchayat_id").html('<option value="">--Select--</option>');
                    for (var i = 0; i < data.length; i++) {
                        $("#panchayat_id").append('<option value="' + data[i].geo_id + '">' + data[i].geo_name + '</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }

    function go() {
        scheme_id_validate();
        year_id_validate();
        block_id_validate();
        panchayat_id_validate();

        if (scheme_id_error || year_id_error || block_id_error || panchayat_id_error) {
            return false; // error occured
        }
        else { // no error occured
            /*
            -> ajax: get performance datas along with add-more button form inputs 7 and display them
            */
            // downloadformat
            // importexcel
            // excelformat
            $("#excelformat").show();
            var scheme_id_tmp = $("#scheme_id").val();
            var year_id_tmp = $("#year_id").val();
            var panchayat_id_tmp = $("#panchayat_id").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('scheme-performance/get-all-datas')}}",
                data: { 'scheme_id': scheme_id_tmp, 'year_id': year_id_tmp, 'panchayat_id': panchayat_id_tmp },
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function (data) {
                    /* to save performance datas */
                    $("#scheme_id_hidden").val(scheme_id_tmp);
                    $("#year_id_hidden").val(year_id_tmp);
                    $("#panchayat_id_hidden").val(panchayat_id_tmp);

                    $(".custom-loader").fadeIn(300);
                },
                error: function (xhr) {
                    alert("error" + xhr.status + "," + xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function (data) {
                    console.log(data);
                    $("#to_append_tbody").html("");
                    $("#to_append_thead").html(data.to_append_thead);
                    $("#to_append_tbody").html(data.to_append_tbody);
                    to_append_row = data.to_append_row;

                    $("#to_append_table").fadeIn(300);

                    $(".custom-loader").fadeOut(300);
                }
            });
            return true;
        }
    }

    // add new rows
    function appendRow() {
        $("#to_append_tbody").append(to_append_row);
    }

    // delete rows (not working)
    function delete_row(e,id) {
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
            if(id){
                    $("#to_delete").val($("#to_delete").val()+id+",");
                }
            if (willDelete) {
                $(e).closest("tr").remove();
            }
        });
    }


    function resetEnterDatasBlock() {
        $("#to_append_table").fadeOut(300);
        $("#to_append_thead").html();
        $("#to_append_tbody").html();
        $("#excelformat").hide();
    }
</script>
<script>
    function update_image(id) {
        var scheme_performance=$('#scheme_performance_id').val(id);
        $('#create-gallery').modal('show');
        $.ajax({
                url: "{{url('scheme-performance/get-gallery/')}}"+"/"+id,
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
                    $("#show_image_for_location").html(""); // append=html
                    // for(var i=0;i>data.length; i++)
                    // $("#panchayat_id").html('<option value="">--Select--</option>');
                    // for (var i = 0; i < data.length; i++) {
                    //     $("#panchayat_id").append('<option value="' + data[i].geo_id + '">' + data[i].geo_name + '</option>');
                    // }
                    if (data.gallery.length>0) {
                        var to_append = `<div class="row">
                                <div class="col-12">
                                    <div><label>Previous Images</label></div>
                                     <div class="form-group">
                                   `;
                        for (var i = 0; i < data.gallery.length; i++) {
                            to_append += `<div class="images-delete-block" style="margin-right:5px; display:inline-block; position:relative; padding:3px;border:1px solid #c4c4c4;border-radious:3px;">
                                <img src="{{url('`+ data.gallery[i] + `')}}" style="height:90px; min-height:90px; min-width:80px;">
                                <span style="position:absolute; top:3px; left:3px; border-radius: 3px; background: rgba(0,0,0,0.5); cursor: pointer; padding: 3px 6px;" class="text-white" onclick="to_delete('`+ data.gallery[i] + `',this)"><i class="fas fa-trash" style="text-shadow: 0px 0px 2px black;"></i></span>
                            </div>`;
                        }

                        to_append += `</div>
                            </div>
                        <input type="text" class="form-control" name="gallery_images_delete" id="gallery_images_delete" value="" hidden> 
                        </div>`;

                        $("#show_image_for_location").html(to_append); // append=html
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });

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
</script>
<script>
function coordinates_details(id)
{
    var scheme_performance=$('#scheme_performance_id_for_coordinates').val(id);
        $('#create-coordinates').modal('show');
        $.ajax({
                url: "{{url('scheme-performance/get-coordinates/')}}"+"/"+id,
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
                    // console.log(data.coordinates.latitude);
                    $("#coordinates_lang_value").val("");
                        $("#coordinates_lat_value").val("");
                    if(data.coordinates!="")
                    {
                        $("#coordinates_lang_value").val(data.coordinates.longitude);
                        $("#coordinates_lat_value").val(data.coordinates.latitude);
                        
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
}
</script>


@endsection