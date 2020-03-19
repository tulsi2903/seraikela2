@extends('layout.layout')

@section('title', 'Scheme Performance')

@section('page-style')
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
        /* background-color: #fff; */
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

    .search-block{
        margin: -20px -20px 20px -20px;
        padding: 15px 20px 15px 20px;
        border-bottom: 1px solid rgb(209, 209, 209);
        background:rgb(242, 241, 253);
    }
    h4.title-1{
        color: black;
        font-weight: bold;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('page-content')
<div class="card">
    <div class="card-header">
        <div class="card-head-row card-tools-still-right" style="background:#fff;">
            <h4 class="card-title">{{$phrase->scheme_performance}}</h4>
            <div class="card-tools">

                <!-- <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
            </div>
        </div>
    </div>


    <div class="card-body">
        <div class="search-block">
            <form action="{{url('scheme-performance/add-datas')}}" method="GET" onsubmit="return false;">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="scheme_id">{{$phrase->scheme}}<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="scheme_id" id="scheme_id" onchange="get_scheme_value(this);" class="form-control">
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
                            <label for="year_id">{{$phrase->year}}<span style="color:red;margin-left:5px;">*</span></label>
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
                            <label for="block_id">{{$phrase->block}}<span style="color:red;margin-left:5px;">*</span></label>
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
                            <label for="panchayat_id">{{$phrase->panchayat}} <span style="color:red;margin-left:5px;">*</span></label>
                            <select name="panchayat_id" id="panchayat_id" class="form-control">
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div style="height: 30px;"></div>
                        <button type="button" class="btn btn-secondary go-button" onclick="go()"><i class="fas fa-search"></i>&nbsp;&nbsp;Go</button>
                    </div>
                </div>
                <!--end of row-->
            </form>
        </div>


        <div class="enter-datas-block" id="to_append_table" style="display: none;">
            <!-- <button type="button" class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Entered Datas</button> -->
            <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin: 0 -10px 0 -10px;">
                <h4 class="title-1">Scheme Work Data</h4>
                <div>
                    <form action="{{url('scheme-performance/store')}}" id="savedataonschemepertable" method="POST" enctype="multipart/form-data" autocomplete="off" onsubmit="return check_performamance_status();">
                        @csrf
                        <table class="table">
                            <thead id="to_append_thead" style="background: #cedcff">
                            </thead>
                            <tbody id="to_append_tbody">
                                <!-- append details -->
                            </tbody>
                        </table>
                        <div style="text-align: right;">
                            <button type="button" class="btn btn-secondary btn-sm btn-circle" onclick="appendRow()">{{$phrase->add}}&nbsp;&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                        </div>
                        <hr />
                        <!-- hidden inputs -->
                        <input type="hidden" name="scheme_id" id="scheme_id_hidden">
                        <input type="hidden" name="year_id" id="year_id_hidden">
                        <input type="hidden" name="panchayat_id" id="panchayat_id_hidden">
                        <input type="hidden" name="to_delete" id="to_delete">
                        <!-- hidden inputs -->
                        <button type="button" class="btn btn-secondary" onclick="submitdatafromajaxonscheme()"><i class="fas fa-check"></i>&nbsp;&nbsp;{{$phrase->save}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





<div id="create-gallery" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top: 11em;">
            <div class="modal-header" style="border-top: 2px solid #5269a3">
                <h4 class="modal-title mt-0" style="font-family: 'Bree Serif', serif;color:#000;">Gallery</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('scheme_performance/galleryFile_update')}}" method="post" id="FormsavegalleryforLoacation" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="card-body p-t-30" style="padding: 11px;">
                            <div class="form-group">
                                <input type="file" name="galleryFile[]" accept="image/gif,image/jpeg,image/jpg" id="galleryFile" class="form-control" required multiple>
                            </div>
                        </div>
                        <p class="invalid-feedback" id="galleryFile_error_msg"></p>

                        <div id="show_image_for_location" >
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control" name="scheme_performance_id" id="scheme_performance_id"> <!--  scheme_performance_id -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info waves-effect waves-light" onclick="return submitgalleryAjax()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div id="create-coordinates" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-top: 11em;">
            <div class="modal-header" style="border-top: 2px solid #5269a3">
                <h4 class="modal-title mt-0" style="font-family: 'Bree Serif', serif;color:#000;">Coordinates</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <form action="{{url('scheme_performance/coordinatesupdate')}}" method="post" id="FormsaveImagescoordinatesLoacation" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row" style="padding:2em;    margin-top: -3em;">
                        <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                            <thead>
                                <tr>
                                    <th>SI.No</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="append_coordinate_section">
                                <!-- append details -->
                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        <div style="color:red;" id="error_msg"></div>
                                        <div style="text-align: right;">
                                            <button type="button" class="btn btn-secondary btn-sm btn-circle" onclick="appendcoordinates()">Add&nbsp;&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="cordinates_details">
                            <!-- append images -->
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" name="scheme_performance_id" id="scheme_performance_id_for_coordinates"> <!--  scheme_performance_id -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button type="button" id="coordinate_save" onclick="submitcoordinateAjax();" class="btn btn-info waves-effect waves-light">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="create-connectivity" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-top: 11em;">
            <div class="modal-header" style="border-top: 2px solid #5269a3">
                <h4 class="modal-title mt-0" style="font-family: 'Bree Serif', serif;color:#000;">Connectivity Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('scheme_performance/savebl_pl_connectivity')}}" method="post" id="Formsaveborderconnectivity" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row" style="padding:2em;    margin-top: -3em;">
                        <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                            <thead>
                                <tr>
                                    <th>SI.No</th>
                                    <th>Block</th>
                                    <th>Panchayat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="append_connectivity_section">
                                <!-- append details -->
                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        <div style="text-align: right;">
                                            <button type="button" class="btn btn-secondary btn-sm btn-circle" onclick="appendconnectivity()">Add&nbsp;&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="connectivity_details">
                            <!-- append images -->
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" name="scheme_performance_id_connectivity" id="scheme_performance_id_for_connectivity"> <!--  scheme_performance_id -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button type="button" id="connectivity_save" onclick="submitborderconnectivityAjax();" class="btn btn-info waves-effect waves-light">Save</button>
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
            $("#panchayat_id_error_msg").html("Please select Panchayat");
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
            // $("#excelformat").show();
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
                    // console.log(data);
                    $("#to_append_tbody").html("");
                    $("#to_append_thead").html(data.to_append_thead);
                    $("#to_append_tbody").html(data.to_append_tbody);
                    // alert(data.length);
                    to_append_row = data.to_append_row;
                    setTimeout(function () { checkStatus(); }, 3000);
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
        scheme_performance_connectivity_index_update();
    }

    // delete rows (not working)
    function delete_row(e, id) {
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
            if (id) {
                $("#to_delete").val($("#to_delete").val() + id + ",");
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
        $("#galleryFile").val("");

        var scheme_performance = $('#scheme_performance_id').val(id);
        $('#create-gallery').modal('show');
        $.ajax({
            url: "{{url('scheme-performance/get-gallery/')}}" + "/" + id,
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
                if (data.gallery.length > 0) {
                    var to_append = `<div class="row">
                                <div class="col-12">
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
    var append_no;
    var to_append;
    function appendcoordinates() {
        append_no++;
        to_append = `<tr>
            <td>  <span class="index_no">`+ append_no + `</span></td>
                <td> <input type="text" name="coordinates_lat_value[]" maxlength="13"   placeholder="Latitude" class="form-control" required></td>
                    <td><input type="text" name="coordinates_lang_value[]" maxlength="13" placeholder="Longitude" class="form-control" required></td>
                    <td><button type="button" class="btn btn-danger btn-xs" onclick="delete_lat_lon(this)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $("#append_coordinate_section").append(to_append);
        sl_no_append();
    }

    function sl_no_append() {
        var trs = $("#append_coordinate_section tr");
        for (var i = 0; i < trs.length; i++) {
            var first_td = $(trs[i]).find("td")[0];
            $(trs[i]).find(first_td).html(i + 1);
        }
        var trs1 = $("#append_connectivity_section tr");
        for (var i = 0; i < trs1.length; i++) {
            var first_td = $(trs1[i]).find("td")[0];
            $(trs1[i]).find(first_td).html(i + 1);
        }
    }
    function delete_lat_lon(e) {
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
                $(e).closest("tr").remove();
                append_no--;
                sl_no_append();
            }
        });
    }
    function coordinates_details(id) {
        $("#error_msg").html("");
        var scheme_performance = $('#scheme_performance_id_for_coordinates').val(id);
        $('#create-coordinates').modal('show');
        $("#append_coordinate_section").html("");
        append_no = 0;
        $.ajax({
            url: "{{url('scheme-performance/get-coordinates/')}}" + "/" + id,
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
                // console.log(data.coordinates);
                $("#append_coordinate_section").html("");
                if (data.coordinates.length > 0) {
                    append_no = 1;
                    var to_append;
                    for (i = 0; i < data.coordinates.length; i++) {
                        append_no = i + 1;
                        // alert(append_no);
                        to_append += `<tr>
                                <td><span>`+ append_no + `</span></td>
                                <td> <input type="text" name="coordinates_lat_value[]" value="`+ data.coordinates[i].latitude + `" placeholder="Latitude" class="form-control" Required >        
                                </td>
                                <td><input type="text" name="coordinates_lang_value[]" value="`+ data.coordinates[i].longitude + `"  placeholder="Longitude" class="form-control" Required >
                                </td>
                                
                                <td><button type="button" class="btn btn-danger btn-xs" onclick="delete_lat_lon(this)"><i class="fas fa-trash-alt"></i></button></td>
                            </tr>`;
                    }
                    $("#append_coordinate_section").html(to_append);
                    sl_no_append();
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }
</script>
<script>
    function submitcoordinateAjax() {
        var formElement = $('#FormsaveImagescoordinatesLoacation')[0];
        var form_data = new FormData(formElement);
        var to_submit = true;

        var tds = $("#create-coordinates").find("input");

        for (var i = 0; i < tds.length; i++) {
            if ($(tds[i]).val() == "") {
                to_submit = false;
                $("#error_msg").html("Co-ordinates cannot be kept blank");

            }

        }

        // return false;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



        if (to_submit) {

            $.ajax({
                url: "{{url('scheme_performance/coordinatesupdate')}}",
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

                    if (data.message == "success") {
                        $("#error_msg").html("");
                        swal("Success!", "New latitudes longitudes  has been added successfully.", {
                            icon: "success",
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            },
                        }).then((ok) => {
                            $('#create-coordinates').modal('hide');
                        });
                    }

                    else {
                        // error occured
                        swal("Error Occured!", data.message, {
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


    }
</script>
<script>

    function checkStatusOld(e, id) {
        var status_id = $(e).val();
        if (status_id == 2) {
            var result = "true";
            /**** 
                uncomment ajax to check duplicacy
            ***/
            $.ajax({
                url: "{{url('scheme-performance/check_matching_erformance/')}}" + "/" + id + "/" + result,
                method: "get",
                success: function (data) {
                    console.log(data);
                    if (data.message == "data_found") {
                        $(e).val("4");
                        swal({
                            title: 'You Cannot Do Sanction Beacause We Found Probable Duplicate',
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'Ok',
                                    className: 'btn btn-success'
                                }
                            }
                        }).then((willDelete) => {
                            // $(e).val("0");

                        });
                    }
                }
            });
        }
        if (status_id == 1) {
            var status_readonly = $(e).closest('tr').find(".status_readonly");
            for (i = 0; i < status_readonly.length; i++) {
                $(status_readonly[i]).prop('readonly', true);
            }
        }
        if (status_id == 3) {
            var status_readonly = $(e).closest('tr').find(".status_readonly");
            for (i = 0; i < status_readonly.length; i++) {
                $(status_readonly[i]).prop('readonly', true);
            }
        }

    }


    function checkStatus() {
        var trs = $("#to_append_tbody tr");
        for (var i = 0; i < trs.length; i++) {
            var status_id = $(trs[i]).find("select[name='status[]']").val();
            if (status_id == 1) {
                $(trs[i]).find(".status_readonly").prop('readonly', true);
            }
            if (status_id == 3) {
                $(trs[i]).find(".status_readonly").prop('readonly', true);
            }

            // $(trs[i]).find(first_td).html(i + 1);
        }
    }

</script>
<script>
    function submitgalleryAjax() {
        var formElement = $('#FormsavegalleryforLoacation')[0];
        var form_data = new FormData(formElement);
        var gallery_element=$("#galleryFile").val();
        if(gallery_element!="")
        {
            $("#galleryFile").removeClass('is-invalid');
                $("#galleryFile_error_msg").html("");
        }
        else
        {
        $("#galleryFile").addClass('is-invalid');
        $("#galleryFile_error_msg").html("Please Upload Image");
            return false
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: "{{url('scheme_performance/galleryFile_update')}}",
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
                if (data.message == "success") {
                    swal("Success!", "Location Gallery have been successfully submitted !", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then((ok) => {
                        $('#create-gallery').modal('hide');
                    });
                }
                else {
                    // error occured
                    swal("Error Occured!", data.message, {
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
<script>
    function get_scheme_value(e) {
        var scheme_id = $(e).val();
        if (scheme_id != "") {
            $("#import_section").css('display', 'block');
            // $("#id").css("display", "block");

        }
        // alert(scheme_id);
    }
</script>
<script>
    function check_performamance_status() {
        // alert("dfdf");
        return true;
    }
</script>

<script>

    function submitdatafromajaxonscheme() {
        var formElement = $('#savedataonschemepertable')[0];
        var form_data = new FormData(formElement);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('scheme-performance/store')}}",
            data: form_data,
            method: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function (data) {

                $(".custom-loader").fadeIn(300);
            },
            error: function (xhr) {
                alert("error" + xhr.status + "," + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                // console.log(data);
                if (data.message == "success") {
                    swal({
                        title: 'Do You Want To Enter Further Performance Data(s) Connectivity Details?',
                        icon: 'success',
                        buttons: {
                            cancel: {
                                visible: true,
                                text: 'No',
                                className: 'btn btn-danger'
                            },
                            confirm: {
                                text: 'Yes',
                                className: 'btn btn-success'
                            }
                        }
                    }).then((willDelete) => {
                        if (willDelete) {
                            go();
                        }
                        else {
                            window.location = 'scheme-performance';
                        }
                    });
                } else if (data.message == "error") {
                    swal({
                        title: 'New performance data(s) has been saved successfully!',
                        icon: 'success',
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then((willDelete) => {
                        if (willDelete) {
                            window.location = 'scheme-performance';
                        }
                    });
                }
                $(".custom-loader").fadeOut(300);
            }
        });
        // return true;
    }
    function get_panchayat_datas_for_borders(id, e) {
        $(e).closest('tr').find("select[name='panchayay_connectivity[]']").html("");
        if (id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('scheme-performance/get-panchayat-datas-for-borders')}}",
                data: { 'block_id': id },
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {

                    var panchayat_val = $(e).closest('tr').find("select[name='panchayay_connectivity[]']");
                    for (var i = 0; i < data.length; i++) {
                        $(panchayat_val).append('<option value="' + data[i].geo_id + '">' + data[i].geo_name + '</option>');
                    }
                }
            });
        }
    }
    function appendconnectivity() {
        append_no++;
        $.ajax({
            url: "{{url('scheme-performance/getblock_datafor_borders')}}",
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            success: function (data) {
                to_append = `<tr>
                    <td>  <span class="index_no">`+ append_no + `</span></td>
                        <td> <select name="block_connectivity[]"  onchange='get_panchayat_datas_for_borders(this.value,this)' class="form-control">
                                <option value="">---Select---</option>`;
                for (i = 0; i < data.block_datas.length; i++) {
                    to_append += `<option value="` + data.block_datas[i].geo_id + `">` + data.block_datas[i].geo_name + `</option>`
                }
                to_append += ` </select>          
                        </td>
                        <td><select name="panchayay_connectivity[]"  class="form-control">
                                <option value="">--Select--</option>
                            </select>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-xs" onclick="delete_lat_lon(this)"><i class="fas fa-trash-alt"></i></button></td>
                            </tr>`;
                $("#append_connectivity_section").append(to_append);
                sl_no_append();
            }
        });
    }
    function border_connectivity_details(scheme_id) {

        $('#create-connectivity').modal('show');
        $("#scheme_performance_id_for_connectivity").val(scheme_id);
        $("#append_connectivity_section").html("");
        append_no = 0;
        $.ajax({
            url: "{{url('scheme-performance/get-connectivity/')}}" + "/" + scheme_id,
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
                // console.log(data);
                $("#append_connectivity_section").html("");
                if (data.connectivity.length > 0) {
                    append_no = 1;
                    var to_append;
                    for (i = 0; i < data.connectivity.length; i++) {
                        append_no = i + 1;
                        to_append += `<tr>
                        <td><span>`+ append_no + `</span></td>
                        <td> <select name="block_connectivity[]"  onchange='get_panchayat_datas_for_borders(this.value,this)' class="form-control">
                                <option value="">---Select---</option>`;
                        for (j = 0; j < data.block_datas.length; j++) {
                            if (data.block_datas[j].geo_id == data.connectivity[i].conn_block_id)
                                to_append += `<option value="` + data.block_datas[j].geo_id + `"  selected="selected">` + data.block_datas[j].geo_name + `</option>`
                            else
                                to_append += `<option value="` + data.block_datas[j].geo_id + `" >` + data.block_datas[j].geo_name + `</option>`
                        }
                        to_append += ` </select>    
                        </td>
                        <td> <select name="panchayay_connectivity[]" class="form-control">
                            <option value="">--Select--</option>`;
                        for (j = 0; j < data.panchayat_datas[i].length; j++) {
                            if (data.panchayat_datas[i][j].geo_id == data.connectivity[i].conn_panchayat_id)
                                to_append += `<option value="` + data.panchayat_datas[i][j].geo_id + `"  selected="selected">` + data.panchayat_datas[i][j].geo_name + `</option>`
                            else
                                to_append += `<option value="` + data.panchayat_datas[i][j].geo_id + `" >` + data.panchayat_datas[i][j].geo_name + `</option>`
                        }
                        to_append += `  </select>
                        </td>
                        
                        <td><button type="button" class="btn btn-danger btn-xs" onclick="delete_lat_lon(this)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
                    }
                    $("#append_connectivity_section").html(to_append);
                    sl_no_append();
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    function submitborderconnectivityAjax() {
        var formElement = $('#Formsaveborderconnectivity')[0];
        var form_data = new FormData(formElement);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('scheme_performance/savebl_pl_connectivity')}}",
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
                if (data.message == "success") {
                    swal("Success!", "New Border Connectivity  has been added successfully.", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then((ok) => {
                        $('#create-connectivity').modal('hide');
                    });
                }
                else {
                    // error occured
                    swal("Error Occured!", "Duplicate Entry of Panchayat", {
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
    function showbutton(id_status, e) {
        var check_ststus = $(e).closest('tr').find("input[name='connectivity_details[]']").is(":checked");

        if (check_ststus == 1) {
            $(e).closest('tr').find(".showconnectivity").css('display', 'block');
        }
        else {
            $(e).closest('tr').find(".showconnectivity").css('display', 'none');
        }
    }

    function scheme_performance_connectivity_index_update() {
        var trs = $("#to_append_tbody tr");
        for (var i = 0; i < trs.length; i++) {
            var connectivity_checkbox = $(trs[i]).find("input[name='connectivity_details[]']")[0];
            $(trs[i]).find(connectivity_checkbox).val("x" + i + "");
        }
    }
</script>
@endsection