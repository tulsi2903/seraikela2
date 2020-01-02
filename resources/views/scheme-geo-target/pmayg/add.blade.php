@extends('layout.layout')

@section('title', 'Scheme Geo Target')

@section('page_style')
    <style>

    </style>
@endsection

@section('page-content')
<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Scheme Geo Target(PMAYG)</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-geo-target/pmayg')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card-body">
            <form action="{{url('scheme-geo-target/pmayg/store')}}" method="POST" id="scheme-pmay-target-form">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="year_id">Year<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="year_id" id="year_id" class="form-control" onchange="get_updated_datas(this)">
                                <option value="">---Select---</option>
                                @foreach($year_datas as $year_data )
                                <option value="{{ $year_data->year_id }}"<?php if($data->year_id == $year_data->year_id) echo"selected"; ?>>{{ $year_data->year_value }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="year_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="subdivision_id">Subdivision<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="subdivision_id" id="subdivision_id" class="form-control">
                                <option value="">---Select---</option>
                                @foreach( $subdivision_datas as $subdivision_data )
                                <option value="{{ $subdivision_data->geo_id }}"<?php if($data->subdivision_id == $subdivision_data->geo_id) echo"selected"?>>{{ $subdivision_data->geo_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="subdivision_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="block_id">Block<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="block_id" id="block_id" class="form-control">
                                <option value="">---Select---</option>
                                @foreach( $block_datas as $block_data )
                                <option value="{{ $block_data->geo_id }}"<?php if($data->block_id == $block_data->geo_id ) echo"selected" ?>>{{ $block_data->geo_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="block_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="panchayat_id">Panchayat<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="panchayat_id" id="panchayat_id" class="form-control" onchange="get_updated_datas(this)">
                                <option value="">---Select---</option>
                                @foreach( $panchayat_datas as $panchayat_data )
                                <option value="{{ $panchayat_data->geo_id }}"<?php if($data->panchayat_id == $panchayat_data->geo_id ) echo"selected"?>>{{ $panchayat_data->geo_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="target">Target<span style="color:red;margin-left:5px;">*</span></label>
                            <input type="text" class="form-control" name="target" id="target" autocomplete="off" value="{{$data->target}}">
                            <div class="invalid-feedback" id="target_error_msg"></div>
                        </div>
                    </div>
                </div>
                <!--end of row-->
                <hr/>
                <div class="form-group">
                    <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                    <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                    <button type="submit" class="btn btn-primary" onclick="return submitForm();">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                </div>

            </form>
        </div>
        <!--end of card body-->
    </div>
</div>
 
<script>
var year_error = true;
var subdivision_error = true;
var block_error = true;
var panchayat_error = true;
var target_error = true;

$(document).ready(function(){
    $("#year_id").change(function(){
        year_validate();
    });
    $("#subdivision_id").change(function(){
        get_block_datas();
       
    });
    $("#block_id").change(function(){
        get_panchayat_datas();
        
    });
    $("#panchayat_id").change(function(){
        panchayat_validate();
    });
    $("#target").change(function(){
        target_validate();
    })
});

function get_block_datas(){
    subdivision_validate();
    var subdivision_id_tmp = $("#subdivision_id").val();
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:"{{url('scheme-geo-target/pmayg/get-block-datas')}}",
        data: {'subdivision_id':subdivision_id_tmp},
        method:"GET",
        contentType:'application/json',
        dataType:"json",
        beforeSend:function(data){
            $(".custom-loader").fadeIn(300);
        },
        error:function(xhr){
            alert("error"+xhr.status+","+xhr.statusText);
            $(".custom-loader").fadeOut(300);
        },
        success:function(data){
            console.log(data);
            $("#block_id").html('<option value="">-Select-</option>');
            for(var i=0;i<data.length;i++){
                $("#block_id").append('<option value="'+data[i].geo_id+'">'+data[i].geo_name+'</option>');
            }
            $(".custom-loader").fadeOut(300);
        }
    });
   
}

function get_panchayat_datas(){
    block_validate();
            var block_id_tmp = $("#block_id").val();
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-geo-target/pmayg/get-panchayat-datas')}}",
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
                    console.log(data);
                    $("#panchayat_id").html('<option value="">-Select-</option>');
                    for(var i=0;i<data.length;i++){
                        $("#panchayat_id").append('<option value="'+data[i].geo_id+'">'+data[i].geo_name+'</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        
    }

    function get_updated_datas(e){
    
        var year_id_tmp = $("#year_id").val();
       
        var panchayat_id_tmp = $("#panchayat_id").val();

        $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-geo-target/pmayg/get-updated-datas')}}",
                data: {'year_id':year_id_tmp,'panchayat_id':panchayat_id_tmp},
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
                    console.log(data);
                    if(data)
                    {
                    $("#target").val(data.target);
                    }
                    else
                    {
                        $("#target").val("");
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });

    }

    function year_validate() {
   
   var year_val = $("#year_id").val();
   
   if (year_val == "") {
    year_error = true;
       $("#year_id").addClass('is-invalid');
       $("#year_id_error_msg").html("Year should not be blank");
   }  else {
    year_error = false;
       $("#year_id").removeClass('is-invalid');
   }
}

function subdivision_validate() {
   
   var subdivision_val = $("#subdivision_id").val();
  
   if (subdivision_val == "") {
    subdivision_error = true;
       $("#subdivision_id").addClass('is-invalid');
       $("#subdivision_id_error_msg").html("Subdivision should not be blank");
   }  else {
    subdivision_error = false;
       $("#subdivision_id").removeClass('is-invalid');
   }
}

function block_validate(){
    var block_val = $("#block_id").val();
    if(block_val==""){
        block_error = true;
        $("#block_id").addClass('is-invalid');
        $("#block_id_error_msg").html("Block should not be blank");
    }else{
        block_error = false;
        $("#block_id").removeClass('is-invalid');
    }
}

function panchayat_validate(){
    var panchayat_val = $("#panchayat_id").val();
    if(panchayat_val==""){
        panchayat_error = true;
        $("#panchayat_id").addClass('is-invalid');
        $("#panchayat_id_error_msg").html("Panchayat should not be blank");
    }else{
        panchayat_error = false;
        $("#panchayat_id").removeClass('is-invalid');
    }
}



function target_validate() {
   
   var target_val = $("#target").val();
   var regNumericSpace = new RegExp('^[0-9 ]+$');
   if (target_val == "") {
    target_error = true;
       $("#target").addClass('is-invalid');
       $("#target_error_msg").html("Target should not be blank");
   } else if (!regNumericSpace.test(target_val)) {
    target_error = true;
       $("#target").addClass('is-invalid');
       $("#target_error_msg").html("Please enter valid number");
   } else {
    target_error = false;
       $("#target").removeClass('is-invalid');
   }
}

function submitForm() {
    year_validate();
    subdivision_validate();
    block_validate();
    panchayat_validate();
    target_validate();

   

        if (year_error || subdivision_error || block_error || panchayat_error || target_error ) {
            return false;
           
        } // error occured
        else {
           
            return true;
           
        } // proceed to submit form data
       
    }
</script>
@endsection