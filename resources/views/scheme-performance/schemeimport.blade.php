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
                    <!-- <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
                </div>
            </div>
        </div>
    </div>
        <div>
            <form action="{{url('scheme-performance/importtoExcel')}}" method="POST" enctype="multipart/form-data">
                @csrf
            <div class="row" style="padding: 2em;">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="scheme_id">Scheme<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="scheme_id" id="scheme_id" onchange="get_scheme_value(this);" class="form-control">
                            <option value="">---Select---</option>
                            @foreach($scheme_datas as $scheme )
                            <option value="{{ $scheme->scheme_id }}">({{$scheme->scheme_short_name}}) {{ $scheme->scheme_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="scheme_id_error_msg"></div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none;" id="import_section">
                <div class="col-md-3">
                    <!-- <div class="form-group"> -->
                        <!-- <a href="location.href='../scheme-performance/downloadFormat?scheme_id='+document.getElementById('scheme_id').value"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Download Format</a> -->
                        <button  class="btn btn-primary"  type="button" onclick="location.href='../scheme-performance/downloadFormat?scheme_id='+document.getElementById('scheme_id').value" style="float:right; background: #349601; color: white;" title="Download Excel Format"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Download Format</button>
                    <!-- </div> -->
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dept_name">{{$phrase->file_to_import}}<span style="color:red;margin-left:5px;">*</span></label>
                    <span>[Maximum no. of entries that can be imported at a time is 250]</span>
                    <input type="file" name="excelcsv" id="excelcsv" class="form-control" required>
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{$phrase->import}} </button>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div><!--end of card-->
                            





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
                    // console.log(data);
                    $("#to_append_tbody").html("");
                    $("#to_append_thead").html(data.to_append_thead);
                    $("#to_append_tbody").html(data.to_append_tbody);
                    $("#total_date_count").html(data.total_count_record);
                    // alert(data.length);
                    to_append_row = data.to_append_row;

                    $("#to_append_table").fadeIn(300);

                    $(".custom-loader").fadeOut(300);
                }
            });
            return true;
        }
    }

    // add new rows
   
    // delete rows (not working)
    </script>
<script>
    function get_scheme_value(e) {
        var scheme_id = $(e).val();
        if (scheme_id != "") {
            $("#import_section").fadeIn(300);
            // $("#id").css("display", "block");

        }
        // alert(scheme_id);
    }
</script>
@endsection