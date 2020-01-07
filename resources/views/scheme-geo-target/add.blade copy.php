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
                <h4 class="card-title">Scheme Geo Target</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card-body">
            <form action="{{url('scheme-geo-target/store')}}" method="POST" id="scheme-pmay-target-form">
                @csrf
                <div class="row">
                <div class="col-md-2">
                        <div class="form-group">
                            <label for="scheme_id">Scheme<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control" onchange="get_updated_datas(this)">
                                <option value="">---Select---</option>
                                @foreach($scheme_datas as $scheme )
                                <option value="{{ $scheme->scheme_id }}">{{ $scheme->scheme_name }}({{$scheme->scheme_short_name}})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="scheme_id_error_msg"></div>
                        </div>
                    </div>

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
                    <!-- <div class="col-md-2">
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
                    </div> -->
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
                            <div style="height:30px;"></div>
                            <button type="button" class="btn btn-primary go-button" onclick="return goForm();">Go&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                        </div>
                    </div>
                    </div>
                <!--end of row-->

                <div class="row" id="target" style="display:none;">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="prev_target">Previous Target</label>
                            <input type="text" class="form-control" name="prev_target" id="prev_target" autocomplete="off" value="{{$data->target}}" disabled>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="current_target">Current Target<span style="color:red;margin-left:5px;">*</span></label>
                            <input type="text" class="form-control" name="current_target" id="current_target" autocomplete="off" value="">
                            <div class="invalid-feedback" id="current_target_error_msg"></div>
                        </div>
                    </div>

                    

                </div>
                <hr/>
                <button type="button" class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Based on Scheme Asset</button>
                <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                    <table class="table order-list" style="margin-top: 10px;">
                        <thead style="background: #cedcff">
                            <tr>
                                
                                <th>Sanction No.</th>
                                <th>Registration No.</th>
                                <th>Landmark</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Gallery</th>
                                <th>Status</th>
                                <th>Comments</th>
                                
                            </tr>
                        </thead>
                        <tbody id="append-datas">
                            <!-- Appending Registration Details -->
                        </tbody>

                        <tbody>
                            <td style="text-align:right;" colspan="9"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;" onclick="append_table_data();"></i></tr>
                        </tbody>
                       

                    </table>
                </div>
                <div class="form-group">
                   
                    <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                    <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                    <div style="height:30px;"></div>
                    <button type="submit" class="btn btn-primary" style="float:right;">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                </div>
            </form>
          
        </div>
        <!--end of card body-->
    </div>
</div>
<script>
     function append_table_data()
     {
        var data = ` <tr>
                    <td>
                        <input type="text" class="form-control" name="sanction_no" id="sanction_no" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="registration_no" id="registration_no" autocomplete="off">
                        
                    </td>
                    <td>
                        <input type="text" class="form-control" name="landmark" id="landmark" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="latitude" id="latitude" autocomplete="off">
                        
                    </td>
                    <td>
                        <input type="text" class="form-control" name="longitude" id="longitude" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="gallery" id="gallery" autocomplete="off">
                        
                    </td>
                    <td>
                    <select name="status" id="status" class="form-control">
                                <option value="">-Select-</option>
                                <option value="">0-25</option>
                                <option value="">26-50</option>
                                <option value="">51-75</option>
                                <option value="">76-100</option>
                              
                            </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="comments" id="comments" autocomplete="off">
                        
                    </td>
                    
                    </tr>`;
                  
        $("#append-datas").append(data);
      
            
     }
</script>
 
<script>

var scheme_error = true;
var year_error = true;
var subdivision_error = true;
var block_error = true;
var panchayat_error = true;
var current_target_error = true;

$(document).ready(function(){
  
    $("#scheme_id").change(function(){
        scheme_validate();
    })
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
    // $("#current_target").change(function(){
    //     current_target_validate();
    // });
    // $("#current_target").change(function(){
    //     $("#append-datas").html("");
    //     var data = ` <tr>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off" value="{{$key}}">
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off">
                        
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off" value="{{$key}}">
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off">
                        
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off" value="{{$key}}">
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off">
                        
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off" value="{{$key}}">
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control" autocomplete="off">
                        
    //                 </td>
                    
    //                 </tr>`;
    //     var current_val_tmp = $("#current_target").val();
    //     // alert("hi");

    //    for(i=0;i<Number(current_val_tmp);i++)
    //    {
    //     $("#append-datas").append(data);
    //    }
    //     // current_target_validate();
       
    // });
    
});

// function get_block_datas(){
//     subdivision_validate();
//     var subdivision_id_tmp = $("#subdivision_id").val();
//     $.ajaxSetup({
//         headers:{
//             'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
//         }
//     });
//     $.ajax({
//         url:"{{url('scheme-geo-target/get-block-datas')}}",
//         data: {'subdivision_id':subdivision_id_tmp},
//         method:"GET",
//         contentType:'application/json',
//         dataType:"json",
//         beforeSend:function(data){
//             $(".custom-loader").fadeIn(300);
//         },
//         error:function(xhr){
//             alert("error"+xhr.status+","+xhr.statusText);
//             $(".custom-loader").fadeOut(300);
//         },
//         success:function(data){
//             console.log(data);
//             $("#block_id").html('<option value="">-Select-</option>');
//             for(var i=0;i<data.length;i++){
//                 $("#block_id").append('<option value="'+data[i].geo_id+'">'+data[i].geo_name+'</option>');
//             }
//             $(".custom-loader").fadeOut(300);
//         }
//     });
   
// }

function get_panchayat_datas(){
    block_validate();
            var block_id_tmp = $("#block_id").val();
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-geo-target/get-panchayat-datas')}}",
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
      
        var scheme_id_tmp = $("#scheme_id").val();
        var year_id_tmp = $("#year_id").val();
        var panchayat_id_tmp = $("#panchayat_id").val();

        $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-geo-target/get-updated-datas')}}",
                data: {'scheme_id':scheme_id_tmp,'year_id':year_id_tmp,'panchayat_id':panchayat_id_tmp},
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
                    if(data.target)
                    {
                        $("#prev_target").val(data.target);
                    }
                    else
                    {
                        $("#prev_target").val(0);
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });

    }

    function scheme_validate(){
        var scheme_val = $("#scheme_id").val();

        if(scheme_val==""){
            scheme_error = true;
            $("#scheme_id").addClass('is-invalid');
            $("#scheme_id_error_msg").html("Scheme should not be blank");
         }
         else{
             scheme_error = false;
             $("#scheme_id").removeClass('is-invalid');
         }

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



function current_target_validate() {
   
   var current_target_val = $("#current_target").val();
   var regNumericSpace = new RegExp('^[0-9 ]+$');
   if (current_target_val == "") {
    current_target_error = true;
       $("#current_target").addClass('is-invalid');
       $("#current_target_error_msg").html("Current Target should not be blank");
   } else if (!regNumericSpace.test(target_val)) {
    current_target_error = true;
       $("#current_target").addClass('is-invalid');
       $("#current_target_error_msg").html("Please enter valid number");
   } else {
    current_target_error = false;
       $("#current_target").removeClass('is-invalid');
   }
}

function goForm() {

    scheme_validate();
    year_validate();
    subdivision_validate();
    block_validate();
    panchayat_validate();
   
     if (scheme_error ||year_error || subdivision_error || block_error || panchayat_error ) {
            return false;
           
        } // error occured
        else {
            $("#target").show();
            return true;
           
        } // proceed to submit form data
       
    }

// function submitForm(){
//     current_validate();

//     if(current_error){
//         return false;
//     }
//     else{
//         return true;
//     }
// }
</script>

@endsection