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
                                    <option value="{{ $year_data->year_id }}" >{{ $year_data->year_value }}</option>
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
                                    <option value="{{ $block_data->geo_id }}" >{{ $block_data->geo_name }}</option>
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
            <hr/>
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
                            <button type="submit" class="btn btn-primary" onclick="location.href='\ scheme-performance/downloadFormat?scheme_id='+ document.getElementById('scheme_id').value+'&year_id={{$year_data->year_id}}&block_id={{$block_data->geo_id}}'"  style="float:right; background: #349601; color: white;" title="Download Excel Format"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Import Format</button>
                            <button type="submit" class="btn btn-primary" onclick="location.href='\ scheme-performance/viewimport?scheme_id='+ document.getElementById('scheme_id').value+'&year_id={{$year_data->year_id}}&block_id={{$block_data->geo_id}}'"  style="float:right; background: #349601; color: white;;margin-right: 10px;" title="Import Excel"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Import</button>
                        </span>
                    </div>
                    <div id="to_append_table" style="display: none;">
                        <form action="{{url('scheme-performance/store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <table class="table">
                                <thead  id="to_append_thead" style="background: #cedcff">
                                    
                                </thead>
                                <tbody id="to_append_tbody">
                                    <!-- append details -->
                                </tbody>
                            </table>
                            <div style="text-align: right;">
                                <button type="button" class="btn btn-secondary btn-sm btn-circle" onclick="appendRow()">Add&nbsp;&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                            </div>
                            <hr/>
                            <!-- hidden inputs -->
                            <input type="text" name="scheme_id" id="scheme_id_hidden">
                            <input type="text" name="year_id" id="year_id_hidden">
                            <input type="text" name="panchayat_id" id="panchayat_id_hidden">
                            <!-- hidden inputs -->
                            <button type="submit" class="btn btn-secondary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                        </form>
                    </div>
                </div>
            </div>
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

    
    $(document).ready(function(){
        $("#scheme_id").change(function(){
            scheme_id_validate();
        })
        $("#year_id").change(function(){
            year_id_validate();
        });
        $("#block_id").change(function(){
            block_id_validate();
            get_panchayat_datas();
        });
        $("#panchayat_id").change(function(){
            panchayat_id_validate();
        });

        // for restting details
        $("#scheme_id, #year_id, #block_id, #panchayat_id").change(function(){
            resetEnterDatasBlock();
        });
    });

    function scheme_id_validate(){
        var scheme_id_val = $("#scheme_id").val();
        if(scheme_id_val==""){
            scheme_id_error = true;
            $("#scheme_id").addClass('is-invalid');
            $("#scheme_id_error_msg").html("Please select a scheme");
        }
        else{
            scheme_id_error = false;
            $("#scheme_id").removeClass('is-invalid');
        }
    }

    function year_id_validate(){
        var year_id_val = $("#year_id").val();
        if(year_id_val==""){
            year_id_error = true;
            $("#year_id").addClass('is-invalid');
            $("#year_id_error_msg").html("Please select a year");
        }
        else{
            year_id_error = false;
            $("#year_id").removeClass('is-invalid');
        }
    }

    function block_id_validate(){
        var block_id_val = $("#block_id").val();
        if(block_id_val==""){
            block_id_error = true;
            $("#block_id").addClass('is-invalid');
            $("#block_id_error_msg").html("Please select block");
        }
        else{
            block_id_error = false;
            $("#block_id").removeClass('is-invalid');
        }
    }

    function panchayat_id_validate(){
        var panchayat_id_val = $("#panchayat_id").val();
        if(panchayat_id_val==""){
            panchayat_id_error = true;
            $("#panchayat_id").addClass('is-invalid');
            $("#panchayat_id_error_msg").html("Please select block");
        }
        else{
            panchayat_id_error = false;
            $("#panchayat_id").removeClass('is-invalid');
        }
    }


    function get_panchayat_datas(){
        var block_id_tmp = $("#block_id").val();
        $("#panchayat_id").html('<option value="">--Select--</option>');
        if(block_id_tmp)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-performance/get-panchayat-datas')}}",
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
                    $("#panchayat_id").html('<option value="">--Select--</option>');
                    for(var i=0;i<data.length;i++){
                        $("#panchayat_id").append('<option value="'+data[i].geo_id+'">'+data[i].geo_name+'</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }

    function go(){
        scheme_id_validate();
        year_id_validate();
        block_id_validate();
        panchayat_id_validate();
        
        if(scheme_id_error||year_id_error||block_id_error||panchayat_id_error){
            return false; // error occured
        }
        else{ // no error occured
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
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-performance/get-all-datas')}}",
                data: {'scheme_id': scheme_id_tmp, 'year_id': year_id_tmp, 'panchayat_id': panchayat_id_tmp},
                method:"GET",
                contentType:'application/json',
                dataType:"json",
                beforeSend: function(data){
                    /* to save performance datas */
                    $("#scheme_id_hidden").val(scheme_id_tmp);
                    $("#year_id_hidden").val(year_id_tmp);
                    $("#panchayat_id_hidden").val(panchayat_id_tmp);

                    $(".custom-loader").fadeIn(300);
                },
                error:function(xhr){
                    alert("error"+xhr.status+","+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success:function(data){
                    console.log(data);

                    $("#to_append_thead").html(data.to_append_thead);
                    to_append_row = data.to_append_row;

                    $("#to_append_table").fadeIn(300);

                    $(".custom-loader").fadeOut(300);
                }
            });
            return true;
        }
    }

    // add new rows
    function appendRow(){
        $("#to_append_tbody").append(to_append_row);
    }

    // delete rows (not working)
    function delete_row(){
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
    }


    function resetEnterDatasBlock(){
        $("#to_append_table").fadeOut(300);
        $("#to_append_thead").html();
        $("#to_append_tbody").html();
        $("#excelformat").hide();
    }
</script>


@endsection
