@extends('layout.layout')

@section('title', 'Scheme Performance(PMAYG)')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
<div class="card">
        <div class="col-md-12">
         
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Performance(PMAYG)</h4>
                        <div class="card-tools">
                        <a href="{{url('scheme-performance/pmayg')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        
        <div class="col-md-12">
            <div class="card-body">
                <form action="{{url('scheme-performance/pmayg/store')}}" method="POST" id="scheme-asset-form">
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
                        <label for="target">Target</label>
                            <input type="text" class="form-control" name="target" id="target" autocomplete="off" value="{{$data->target}}" disabled>
                           
                        </div>
                    </div>
                </div>
                   
                    
                    <hr/>
                
                    <div class="col-md-12">
                                <button class="btn"  style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;PMAYG Registration</button>
                                    <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                                    <table class="table order-list" style="margin-top: 10px;">
                                        <thead style="background: #cedcff">                                              
                                            <tr>
                                                <th>Registration No.<span style="color:red;margin-left:5px;">*</span></th>
                                                <th>Sanction No.<span style="color:red;margin-left:5px;">*</span></th>
                                                <th>Sanction Amount</th>
                                                <th>Installment Paid</th>
                                                <th>Amount Released</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Status</th>
                                                <th>Action</th>                                              
                                            </tr>
                                        </thead> 
                                        <tbody id="append-registration-data">
                                        <!-- <tr>
                                            <td><input type="text" class="form-control" name="registration_no[]" id="registration_no"></td>
                                            <td><input type="text" class="form-control" name="sanction_no[]" id="sanction_no"></td>
                                            <td><input type="text" class="form-control" name="sanction_amount[]" id="sanction_amount"></td>
                                            <td><select name="installment_paid[]" id="installment_paid" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="1">1st Installment</option>
                                                    <option value="2">2nd Installment</option>
                                                    <option value="3">3rd Installment</option>
                                                </select></td>
                                            <td><input type="text" class="form-control" name="amount_released[]" id="amount_released"></td>
                                            <td><input type="text" class="form-control" name="latitude[]" id="latitude"></td>
                                            <td><input type="text" class="form-control" name="longitude[]" id="longitude"></td>
                                            <td><select class="form-control" name="house_status[]" id="house_status">
                                                <option value="">--Select--</option>
                                                <option value="0">Sanctioned</option>
                                                <option value="1">In Progress</option>
                                                <option value="2">Completed</option>
                                                </select></td>
                                          
                                            <td><button type="button" class="btn btn-danger delete-button-row">Remove</button></td>
                                        </tr> -->
                                    
                                        </tbody>
                                        <tbody>
                                            <td style="text-align:right;" colspan="9"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;" onclick="append_table_data();"></i></tr>
                                        </tbody>

                                       
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                            <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" >
                            <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" >
                               
                            <button type="submit" class="btn btn-primary" style="float:right;" onclick="return submitForm();">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                        </div>
                </form>
            </div><!--end of card body-->
        </div>

<script>

$(document).ready(function(){

    $("#subdivision_id").change(function(){
        get_block_datas();
       
    });
    $("#block_id").change(function(){
        get_panchayat_datas();
        
    });
   
});

function get_block_datas(){
    
    var subdivision_id_tmp = $("#subdivision_id").val();
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:"{{url('scheme-performance/pmayg/get-block-datas')}}",
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
    
            var block_id_tmp = $("#block_id").val();
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-performance/pmayg/get-panchayat-datas')}}",
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
            url:"{{url('scheme-performance/pmayg/get-updated-datas')}}",
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

function append_table_data()
{
var data=   `<tr>
            <td><input type="text" class="form-control" name="registration_no[]" id="registration_no"></td>
            <td><input type="text" class="form-control" name="sanction_no[]" id="sanction_no"></td> 
            <td><input type="text" class="form-control" name="sanction_amount[]" id="sanction_amount"></td>
            <td><select name="installment_paid[]" id="installment_paid" class="form-control">
                    <option value="">--Select--</option>
                    <option value="1">1st Installment</option>
                    <option value="2">2nd Installment</option>
                    <option value="3">3rd Installment</option>
                </select></td> 
            <td><input type="text" class="form-control" name="amount_released[]" id="amount_released"></td>
            <td><input type="text" class="form-control" class="form-control" name="latitude[]" id="latitude"></td> 
            <td><input type="text" class="form-control" name="longitude[]" id="longitude"></td>
            <td><select name="house_status[]" id="house_status" class="form-control">
                                <option value="">--Select--</option>
                                <option value="0">Sanctioned</option>
                                <option value="1">In Progress</option>
                                <option value="2">Completed</option>
                                </select></td> 
            <td><button type="button" class="btn btn-danger delete-button-row">Remove</button></td>
            </tr>`;
    $("#append-registration-data").append(data);
}

$(document).ready(function(){
$("#append-registration-data").delegate(".delete-button-row", "click", function() {
$(this).closest("tr").remove();
      });
    });
</script>
@endsection




