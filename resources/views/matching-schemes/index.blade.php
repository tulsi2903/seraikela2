@extends('layout.layout') 

@section('title', 'Matching Schemes') 

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
    
    .notduplicate_record {
        display: none;
    }
    
    .duplicate_record {
        display: none;
    }

    #toggle_div table td{
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
    table tr td, table tr th, .table-striped td, .table-striped th{
        border: 1px solid rgb(165, 165, 165) !important;
        border-top: 1px solid rgb(165, 165, 165) !important;
        border-bottom: 1px solid rgb(165, 165, 165) !important;
    }
    .table.dataTable{
        border-collapse: collapse !important;
    }
    .dataTables_wrapper.container-fluid{
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .search-form{
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
            <h4 class="card-title">Matching Schemes Datas</h4>
            <div class="card-tools">
                <!-- <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="search-form">
            <h4 class="title-1">Search Duplicate Work Datas</h4>
            <form action="{{url('matching-schemes')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php
                                if(count($to_search_scheme_id)==1){
                                    $to_search_scheme_id = $to_search_scheme_id[0];
                                }
                                else{
                                    $to_search_scheme_id = 0;
                                }
                            ?>
                            <label for="scheme_id">{{$phrase->scheme}}<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="scheme_id" id="scheme_id" onchange="get_scheme_value(this);" class="form-control">
                                <option value="all">--All Schemes--</option>
                                @foreach($scheme_datas as $scheme )
                                    <option value="{{ $scheme->scheme_id }}" <?php if($to_search_scheme_id==$scheme->scheme_id){ echo "selected"; } ?>>({{$scheme->scheme_short_name}}) {{ $scheme->scheme_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="scheme_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php
                                if(count($to_search_year_id)==1){
                                    $to_search_year_id = $to_search_year_id[0];
                                }
                                else{
                                    $to_search_year_id = 0;
                                }
                            ?>
                            <label for="year_id">{{$phrase->year}}<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="year_id" id="year_id" class="form-control">
                                <option value="all">--All Years--</option>
                                @foreach($year_datas as $year_data )
                                    <option value="{{ $year_data->year_id }}" <?php if($to_search_year_id==$year_data->year_id){ echo "selected"; } ?>>{{ $year_data->year_value }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="year_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php
                                if(count($to_search_block_id)==1){
                                    $to_search_block_id = $to_search_block_id[0];
                                }
                                else{
                                    $to_search_block_id = 0;
                                }
                            ?>
                            <label for="block_id">{{$phrase->block}}<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="block_id" id="block_id" class="form-control">
                                <option value="all">--All Blocks--</option>
                                @foreach( $block_datas as $block_data )
                                    <option value="{{ $block_data->geo_id }}" <?php if($to_search_block_id==$block_data->geo_id){ echo "selected"; } ?>>{{ $block_data->geo_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="block_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php
                                if(count($to_search_panchayat_id)==1){
                                    $to_search_panchayat_id = $to_search_panchayat_id[0];
                                }
                                else{
                                    $to_search_panchayat_id = 0;
                                }
                            ?>
                            <label for="panchayat_id">{{$phrase->panchayat}} <span style="color:red;margin-left:5px;">*</span></label>
                            <select name="panchayat_id" id="panchayat_id" class="form-control">
                                <option value="all">--All Panchayats--</option>
                                @foreach($panchayat_datas as $panchayat_data)
                                    <option value="{{ $panchayat_data->geo_id }}" <?php if($to_search_panchayat_id==$panchayat_data->geo_id){ echo "selected"; } ?>>{{ $panchayat_data->geo_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div style="height: 30px;"></div>
                        <input type="text" name="search" value="yes" hidden>
                        <button type="submit" class="btn btn-secondary go-button" onclick="search()"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                    </div>
                </div>
                <!--end of row-->
            </form>
        </div>

        <div class="row">
            <div class="col-12">
                @if(count($datas)==0)
                    <div style="text-align: center;"><i class="fas fa-info-circle"></i> No work data to show, please refine your search queries</div>
                @else
                    <h4 class="title-1">Work Datas</h4>
                    <table class="display table table-datatable table-striped table-hover">
                        <thead>
                            <tr style="background: #d6dcff;color: #000;">
                                <th>#</th>
                                <th>Schemes</th>
                                <th>Year</th>
                                <th>Location</th>
                                <th>Asset</th>
                                <th>No of Matching Work Datas</th>
                                <th>Attributes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php $count=1; ?>
                        <tbody>
                            @foreach($datas as $data)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$data->scheme_name}}({{$data->scheme_short_name}})</td>
                                <td>{{$data->year_value}}</td>
                                <td>Block: {{$data->geo_name}}<br/>Panchayat: {{$data->panchayat_name}}</td>
                                <td>{{$data->scheme_asset_name}}</td>
                                <td>
                                    <?php $matching_array=explode(',',$data['matching_performance_id']);
                                        $matching_count=count($matching_array);
                                        echo $matching_count;
                                    ?>
                                </td>
                                <td>{!! $data->attribute !!}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-secondary" onclick="get_view_data({{$data->id}})" title="Click to view duplicate work data"><i class="fas fa-eye"></i>&nbsp;View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>
</div>

<form action="{{url('matching-scheme/assign-to')}}" id="duplicate-form" method="POST">
    <div class="modal fade" id="test-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header" style="margin-bottom: 20px;">
                    <h4 class="modal-title">Duplicate Work Datas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="display table table-striped table-hover">
                        <thead style="background: #9ec5ff;color: #000;">
                            <tr>
                                <th>#</th>
                                <th>Schemes</th>
                                <th>Year</th>
                                <th>Location</th>
                                <th>Asset</th>
                                <th>Attributes</th>
                                <th>Change Status</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody id="duplicate-form-tbody">
                            <!-- append details -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="text" name="id" id="to-update-data-id" value="" hidden>
                    <button type="button" class="btn btn-secondary waves-effect" onclick="reset_duplicate_form();">Cancel</button>
                    <button type="button" class="btn btn-info waves-effect waves-light" onclick="return saveData()">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>

    $(document).ready(function(){
        $("#block_id").change(function(){
            get_panchayat_datas();
        });
    });

    function get_panchayat_datas() {
        var block_id_tmp = $("#block_id").val();
        $("#panchayat_id").html('<option value="all">--All Panchayats--</option>');
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
                    for (var i = 0; i < data.length; i++) {
                        $("#panchayat_id").append('<option value="' + data[i].geo_id + '">' + data[i].geo_name + '</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }

    function get_view_data(id) {
        $("#test-modal").modal("hide"); // reset
        $("#duplicate-form-tbody").html(""); // reset

        $.ajax({
            url: "matching-scheme/get-all-matching-datas" + "?id=" + id,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function() {
                $(".custom-loader").fadeIn(150);
            },
            success: function(data) {
                // console.log(data);
                var append;
                for (var i = 0; i < data.matching_performance_datas.length; i++) {
                    append += `<tr data-type="`+data.matching_performance_datas[i].type+`">
                                <td>
                                    <input type="text" name="matching_performance_ids[]" value="`+data.matching_performance_datas[i].scheme_performance_id+`" hidden>
                                    `+(i+1)+`
                                </td>
                                <td>` + data.matching_performance_datas[i].scheme_short_name + `</td>
                                <td>` + data.matching_performance_datas[i].year_value + `</td>
                                <td>Block: ` + data.matching_performance_datas[i].geo_name + `<br/>Panchayat: ` + data.matching_performance_datas[i].panchayat_name + `</td>
                                <td>` + (data.matching_performance_datas[i].scheme_asset_name || "N/A")+ `</td>
                                <td>` + data.matching_performance_datas[i].attribute + `</td>
                                <td>`;

                                append+=`
                                    <select name="status[]" class="form-control">
                                        <option value="probable_duplicate"
                                        `;
                                        if(data.matching_performance_datas[i].type=="probable_duplicate"){
                                            append+=` selected`;
                                        }
                                        append+=`>Probable Duplicate</option>
                                        <option value="duplicate"
                                        `;
                                        if(data.matching_performance_datas[i].type=="duplicate"){
                                            append+=` selected`;
                                        }
                                        append+=`>Duplicate</option>
                                        <option value="not_duplicate"
                                        `;
                                        if(data.matching_performance_datas[i].type=="not_duplicate"){
                                            append+=` selected`;
                                        }
                                        append+=`>Not Duplicate</option>
                                    </select>
                                `;
                    
                    append += `</td>`;
                    append += `<td><input class="form-control" name="comment[]" value="`+(data.matching_performance_datas[i].comment || '')+`" placeholder="comment"></td>`;
                }
                $("#duplicate-form-tbody").append(append);
                $("#to-update-data-id").val(id);
                $("#test-modal").modal("show");
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    function reset_duplicate_form() {
        $("#test-modal").modal("hide");
        $("#duplicate-form-tbody").html("");
        $("#to-update-data-id").val("");
    }

    // to finally save datas, [duplicate, not duplicate etc]
    function saveData(){
        var formElement = $('#duplicate-form')[0];
        var form_data = new FormData(formElement);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('matching-scheme/assign-to')}}",
            data: form_data,
            method: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function (data) {
                $(".custom-loader").fadeIn(300);
            },
            error: function (xhr) {
                // console.log(xhr);
                alert("error" + xhr.status + ", " + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                console.log(data);
                if (data.response == "success") {
                    swal("Success!", "Successfully saved", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then((ok) => {
                        reset_duplicate_form(); // resetting form
                        if (ok) {
                            // document.location.reload();
                        }
                    });
                }
                else {
                    // error occured
                    swal("Something went wrong, please try again!", {
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

        return false;
    }
</script>
@endsection