@extends('layout.layout') @section('title', 'View Matching Schemes') @section('page-style')
<style>
.table{
    display: none;
}
#toggle_div{
    display: none;
}
</style>
@endsection @section('page-content')
<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">View Matching Schemes</h4>
                <div class="card-tools">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card-body">
            <div class="search-block">
                <form action="{{url('matching-schemes/view-searched-results')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group">
                            <label for="matching_schemes">Matching Schemes(Duplicate/Non Duplicate)<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="matching_schemes" id="matching_schemes" class="form-control">
                                <option value="">---Select---</option>

                                <option value="0">Duplicate Matching Schemes</option>
                                <option value="1">Non Duplicate Matching Schemes</option>

                            </select>

                        </div>
                    </div>

                    <div class="row" style="display:none;" id="form-data">

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
                                <label for="scheme_id">{{$phrase->scheme}}<span style="color:red;margin-left:5px;">*</span></label>
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
                                <label for="panchayat_id">{{$phrase->panchayat}}</label>
                                <select name="panchayat_id" id="panchayat_id" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                                <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div style="height:30px;"></div>
                                <button type="button" class="btn btn-secondary" onclick="view_matching_datas();"><i class="fas fa-search"></i>&nbsp;&nbsp;{{$phrase->search}}</button>
                            </div>
                        </div>
                        <div id="table-data">
                            <table class="table table-striped" id="get-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Year</th>
                                        <th scope="col">Block</th>
                                        <th scope="col">Panchayat</th>
                                        <th scope="col">Schemes</th>
                                        <th scope="col">Assests</th>
                                        <th scope="col">No of matching columns</th>
                                        <th scope="col">Attributes</th>

                                        <!-- <th scope="col">Status Is</th> -->
                                        <!-- <th>Date</th> -->
                                        <th scope="col">Action</th>
                                        <!-- <th>Cancel</th> -->
                                    </tr>
                                </thead>
                                <tbody id="append-matching-datas">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>

            </div>
            <hr/>

<div id="toggle_div">
    <form action="{{url('scheme_performance/status_update')}}" name="myForm" id="duplicate-form" method="POST">
        @csrf
        <div class="modal-body">
            <div class="row" style="padding:2em;margin-top: -3em;">
                <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Year</th>
                            <th>Block</th>
                            <th>Panchayat</th>
                            <th>Schemes</th>
                            <th>Assests</th>
                            <th>Attributes</th>

                            <th>Status Is</th>
                            <!-- <th>Date</th> -->
                            <th>Comment</th>
                            <!-- <th>Cancel</th> -->
                        </tr>
                    </thead>
                    <tbody id="dublicate_data">
                        <!-- append details -->
                    </tbody>

                </table>

            </div>
        </div>

        <div class="modal-footer">
            <!-- <input type="text" name="count_value" id="count_value" hidden> -->
            <!-- <input type="text" name="hidden_input_for_scheme_performance_id" id="hidden_input_for_scheme_performance_id"> -->
            <input type="text" name="hidden_input_for_inprogress" id="hidden_input_for_inprogress" value="" hidden>
            <input type="text" name="hidden_input_for_revert" id="hidden_input_for_revert" value="" hidden>

            <button type="button" class="btn btn-secondary waves-effect" onclick="return hide_div();">Cancel</button>
            <!-- <button type="submit" class="btn btn-info waves-effect waves-light" onclick="return before_save({{$data->id}})">Save</button> -->
            <!-- <button type="button" class="btn btn-info waves-effect waves-light" onclick="return validateForm()">Save</button> -->

        </div>
    </form>
</div>

        </div>
    </div>

</div>


<script>
    function view_matching_datas() {
        $("#get-table").show();
        var year_id = $("#year_id").val();
        $("#append-matching-datas").html("");
        var scheme_id = $("#scheme_id").val();
        var block_id = $("#block_id").val();
        var panchayat_id = $("#panchayat_id").val();
        var matching_schemes = $("#matching_schemes").val();
        $.ajax({
            url: "{{url('fetch/matching-schemes/details')}}",
            method: "GET",
            data: {
                'year_id': year_id,
                'scheme_id': scheme_id,
                'block_id': block_id,
                'panchayat_id': panchayat_id,
                'matching_schemes': matching_schemes
            },
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function() {
                
            },
            success: function(data) {

                console.log(data);
                $("#table-data").show();
                var to_append = "";
                var s_no = 0;
                for (var i = 0; i < data.Matching.length; i++) {
                    s_no++;
                    var str = data.Matching[i].matching_performance_id;
                    var count_matching = str.split(",");

                    to_append += `<tr><td>` + s_no + `</td><td>` + data.Matching[i].year_value + `</td>
                    <td>` + data.Matching[i].block_name + `</td>
                    <td>` + data.Matching[i].panchayat_name + `</td>
                    <td>` + data.Matching[i].scheme_name + `</td>
                    <td>` + data.Matching[i].scheme_asset_name + `</td>
                    <td>` + count_matching.length + `</td><td>` + data.Matching[i].attribute + `</td>
                    <td><i class="fa fa-eye" aria-hidden="true" onclick="get_view_data(` + data.Matching[i].chck_matching_performance_id + `)";></i></td></tr>`;

                }
                $("#append-matching-datas").append(to_append);
            }
        });
    }
</script>
<script>
    // variable to use globally
    var selected_inprogress = new Array;
    var selected_revert = new Array;
    var total_duplicate_record = 0;
    //
    function get_view_data(id) {
        //  alert(id);

        $("#toggle_div").slideDown(300);
        $.ajax({
            url: "fetch/matching-schemes/details" + "/" + id,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function() {
                $("#dublicate_data").html("");
                $("#hidden_input_for_inprogress").val("");
                $("#hidden_input_for_revert").val("");

                selected_inprogress = [];
                selected_revert = [];
                total_duplicate_record = 0;
            },
            success: function(data) {
                console.log(data.append_comment);
                total_duplicate_record = parseInt(data.tmp_matching);
                var append;
                var s_no = 0;
                for (var i = 0; i < data.tmp_matching; i++) {
                    s_no++;
                    append += `<tr><td><input type="text" name="get_scheme_performance_id" value="` + data.scheme_performance_id_to_append + `" hidden>` + s_no + `</td><td>` + data.Matching[i].year_value + `</td><td>` + data.Matching[i].geo_name + `</td>
                            <td>` + data.Matching[i].panchayat_name + `</td><td>` + data.Matching[i].scheme_short_name + `</td><td>` + data.Matching[i].scheme_asset_name + `</td>
                            <td>` + data.Matching[i].attribute + `</td>
                            <td>
                            <input type="text" name="matching_id" value="` + id + `" hidden>
                            <input type="text" name="scheme_performance_id[]" value="` + data.Matching[i].scheme_performance_id + `" hidden>`;
                    // console.log(data.Matching[i].type);
                    if (data.Matching[i].type == "not_duplicate") {
                        append += `<span class="">This particular record is not duplicate</span><i class="fa fa-undo" aria-hidden="true" style="color:blue;></i>`;
                    } else if (data.Matching[i].type == "duplicate") {
                        append += `<span class="" style="color:red;">This particular record is duplicate</span><i class="fa fa-undo" aria-hidden="true" style="color:blue;></i>`;
                    } else {
                        append += `<button type="button" class="btn btn-primary inprogress"  onclick="inprogress_request(` + data.Matching[i].scheme_performance_id + `,this)">Not Duplicate</button><span class="notduplicate_record">This particular record is not duplicate</span>
                        <button type="button" class="btn btn-primary revert" onclick="revert_request(` + data.Matching[i].scheme_performance_id + `,this)">Duplicate</button><span style="color:red" class="duplicate_record">This particular record is duplicate</span>`;
                    }

                    // append += `</td><td>` + data.Matching[i].updated_at + `</td>`;
                    if (data.append_comment[i] != null) {
                        append += `<td><textarea class="form-control" name="comment[]">` + data.append_comment[i] + `</textarea></td>`;
                    } else {
                        append += `<td><textarea class="form-control" name="comment[]"></textarea></td>`;
                    }
                    // append +=`<td><a href="#"><i class="fa fa-undo" aria-hidden="true"></i></a></td></tr>`;
                }
                $("#dublicate_data").append(append);
            }
        });
    }

    function hide_div() {
        $("#toggle_div").slideUp(300);
    }

    function revert_request(id, e) {
        if (!selected_revert.includes(id)) {
            selected_revert.push(id);
            $("#hidden_input_for_revert").val(selected_revert);
            var tr = $(e).closest("tr");
            $(tr).find(".duplicate_record").show();
            $(tr).find(".inprogress").hide();
            $(tr).find(".revert").hide();
        }
    }

    function inprogress_request(id, e) {
        if (!selected_inprogress.includes(id)) {
            selected_inprogress.push(id);
            $("#hidden_input_for_inprogress").val(selected_inprogress);
            var tr = $(e).closest("tr");
            $(tr).find(".notduplicate_record").show();
            $(tr).find(".inprogress").hide();
            $(tr).find(".revert").hide();
        }
    }
</script>
<script>
    // defining error = true as default
    var scheme_id_error = true;
    var year_id_error = true;
    var block_id_error = true;
    var panchayat_id_error = true;
    $(document).ready(function() {
        $("#scheme_id").change(function() {
            scheme_id_validate();
        })
        $("#year_id").change(function() {
            year_id_validate();
        });
        $("#block_id").change(function() {
            block_id_validate();
            get_panchayat_datas();
        });
        $("#panchayat_id").change(function() {
            panchayat_id_validate();
        });

    });

    function scheme_id_validate() {
        var scheme_id_val = $("#scheme_id").val();
        if (scheme_id_val == "") {
            scheme_id_error = true;
            $("#scheme_id").addClass('is-invalid');
            $("#scheme_id_error_msg").html("Please select a scheme");
        } else {
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
        } else {
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
        } else {
            block_id_error = false;
            $("#block_id").removeClass('is-invalid');
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
                data: {
                    'block_id': block_id_tmp
                },
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function(data) {
                    $(".custom-loader").fadeIn(300);
                },
                error: function(xhr) {
                    alert("error" + xhr.status + "," + xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function(data) {
                    $("#panchayat_id").html('<option value="">--Select--</option>');
                    for (var i = 0; i < data.length; i++) {
                        $("#panchayat_id").append('<option value="' + data[i].geo_id + '">' + data[i].geo_name + '</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }
</script>
<script>
    $("#matching_schemes").change(function() {
        $("#form-data").show();
        $("#year_id").val("");
        $("#scheme_id").val("");
        $("#block_id").val("");
        $("#panchayat_id").val("");
    });
</script>

@endsection