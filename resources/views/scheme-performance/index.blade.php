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
            <form action="{{url('scheme-performance/add-datas')}}" method="GET">
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
                                <option value="{{ $year_data->year_id }}" <?php if($data->year_id == $year_data->year_id) echo"selected"; ?>>{{ $year_data->year_value }}</option>
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
                                <option value="{{ $block_data->geo_id }}" <?php if($data->block_id == $block_data->geo_id ) echo"selected" ?>>{{ $block_data->geo_name }}</option>
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
                            <button type="submit" class="btn btn-secondary go-button" onclick="return submitForm();"><i class="fas fa-search"></i>&nbsp;&nbsp;Go</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

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

    function submitForm(){
        scheme_id_validate();
        year_id_validate();
        block_id_validate();
        panchayat_id_validate();

        if(scheme_id_error||year_id_error||block_id_error||panchayat_id_error){
            return false;
        }
        else{
            return true;
        }
    }

</script>

@endsection
