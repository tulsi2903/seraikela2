@extends('layout.layout')

@section('title', 'Scheme Performance')

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Performance</h4>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('scheme-performance/store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                            <div class="form-group">
                                <label for="year">Year<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $years as $year )
                                     <option value="{{ $year->year_id }}" <?php if($data->year_id==$year->year_id){ echo "selected"; } ?>>{{ $year->year_value }}</option>
                                    @endforeach 
                                </select>
                                 <div class="invalid-feedback" id="year_error_msg"></div>
                            </div>

                            <div class="form-group">
                                <label for="district">District<span style="color: red;margin-left: 5px;">*</span></label>
                                <select name="district" id="district" class="form-control">
                                <option value="">---Select---</option>
                                @foreach($districts as $district)
                                     <option value="{{ $district->geo_id }}">{{ $district->geo_name }}</option>
                                @endforeach
                                </select>
                                <div class="invalid-feedback" id="district_error_msg"></div>
                            </div>

                            <div class="form-group">
                                <label for="subdivision">Subdivision<span style="color: red;margin-left: 5px;">*</span></label>
                                <select name="subdivision" id="subdivision" class="form-control">
                                <option value="">---Select---</option>
                                    @foreach($subdivisions as $subdivision)
                                     <option value="{{ $subdivision->geo_id }}">{{ $subdivision->geo_name }}</option>
                                    @endforeach 
                                </select>
                                <div class="invalid-feedback" id="subdivision_error_msg"></div>
                            </div>

                           
                             <div class="form-group">
                                <label for="block">Block<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="block" id="block" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $blocks as $block )
                                     <option value="{{ $block->geo_id }}" <?php if($bl_id==$block->geo_id){ echo "selected"; } ?>>{{ $block->geo_name }}</option>
                                    @endforeach
                                </select>
                                 <div class="invalid-feedback" id="block_error_msg"></div>
                            </div>
                             <div class="form-group">
                                <label for="panchayat">Panchayat<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="panchayat" id="panchayat" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $panchayats as $panchayat )
                                     <option value="{{ $panchayat->geo_id }}" <?php if($data->geo_id==$panchayat->geo_id){ echo "selected"; } ?>>{{ $panchayat->geo_name }}</option>
                                    @endforeach
                                </select>
                                 <div class="invalid-feedback" id="panchayat_error_msg"></div>
                            </div>
                             <div class="form-group">
                                <label for="scheme_name">Scheme<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="scheme_name" id="scheme_name" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $schemes as $scheme )
                                     <option value="{{ $scheme->scheme_id }}">{{ $scheme->scheme_name }}({{$scheme->scheme_short_name}})</option>
                                    @endforeach
                                </select>
                                 <div class="invalid-feedback" id="scheme_type_error_msg"></div>
                            </div>
                             <div class="form-group">
                                <label for="indicator">Indicator<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="indicator" id="indicator" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $indicators as $indicator )
                                     <option value="{{ $indicator->indicator_id }}">{{ $indicator->indicator_name }}</option>
                                    @endforeach
                                </select>
                                 <div class="invalid-feedback" id="indicator_type_error_msg"></div>
                            </div>
                             <div class="form-group">
                                <label for="target">Target<span style="color:red;margin-left:5px;">*</span></label>
                               <input type="text" name="target" id="target" class="form-control" autocomplete="off">
                                 <div class="invalid-feedback" id="target_error_msg"></div>
                                 <div id="error_msg_if_target_not_found" style="color:red;"></div>
                            </div>
                            
                            <div class="form-group" id="pre-value">
                                <label for="pre_value">Previous Value<span style="color:red;margin-left:5px;">*</span></label>
                               <input type="text" name="pre_value" id="pre_value" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group" id="current-value">
                                <label for="current_value">Current Value<span style="color:red;margin-left:5px;">*</span></label>
                               <input type="text" name="current_value" id="current_value" class="form-control" autocomplete="off">
                                 <div class="invalid-feedback" id="current_value_error_msg"></div>
                            </div>
                            <div class="form-group" id="attchment-id">
                                <label for="attchment" id="attchment">Upload Document</label>
                                <input type="file" name="attachment[]" id="attchment" class="form-control" multiple>
                            </div>
                            
                           
                            <div class="form-group">
                                <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                                <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                                <input type="text" name="scheme_geo_target_id" id="scheme_geo_target_id" hidden>
                                <button type="submit" class="btn btn-primary" id="go-button" onclick="return submitForm()">Go&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                                <button type="reset" class="btn btn-secondary">Reset&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>
<script>

   
   var block_name_error=true;
   var panchayat_error = true;
   var district_error = true;
   var subdivision_error = true;
   var year_error = true;
   var scheme_type_error = true;
   var indicator_error = true;
   
$(document).ready(function(){
    $("#block").change(function(){
       block_name_validate();
       ajaxFunc_bl();
       ajaxFunc_get_target();
    });
    $("#panchayat").change(function(){
      panchayat_validate();
      ajaxFunc_get_target();
    });
   
    $("#year").change(function(){
        year_validate();
        ajaxFunc_get_target();
    });
    $("#district").change(function(){
        district_validate();
        ajaxFunc_subdivision();
        ajaxFunc_get_target();
    });
    $("#subdivision").change(function(){
        subdivision_validate();
        ajaxFunc_block();
        ajaxFunc_get_target();
    });
    $("#scheme_name").change(function(){
        scheme_type_validate();
        ajaxFunc_indicator();
        ajaxFunc_get_target();
    });
    $("#indicator").change(function(){
        indicator_validate();
        ajaxFunc_get_target();
    });

});



//function for fetching panchayat according to block
    function ajaxFunc_bl(){
       var bl_id_tmp = $("#block").val();
       
       $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
       });
      $.ajax({
        url:"{{url('scheme-performance/get-panchayat-name')}}",
        data: {'bl_id':bl_id_tmp},
        method:"GET",
        contentType:'application/json',
        dataType:"json",
        beforeSend: function(data){
            $(".loader").fadeIn(300);
        },
        error:function(xhr){
            alert("error"+xhr.status+","+xhr.statusText);
            $(".loader").fadeOut(300);
        },
        success:function(data){
            console.log(data);
             $("#panchayat").html('<option value="">-Select-</option>');
                for(var i=0;i<data.panchayat_data.length;i++){
                $("#panchayat").append('<option value="'+data.panchayat_data[i].geo_id+'">'+data.panchayat_data[i].geo_name+'</option>');
                }

        }
      });
    }

    //function for fetching block from subdivision
     function ajaxFunc_block(){
        var sd_id_tmp = $("#subdivision").val();

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url:"{{url('scheme-performance/get-block-name')}}",
            data:{'sd_id':sd_id_tmp},
            method:"GET",
            contentType:'application/json',
            dataType:"json",
            beforeSend: function(data){
                $(".loader").fadeIn(300);
            },
            error:function(xhr){
                alert("error"+xhr.status+","+xhr.statusText);
                $(".loader").fadeOut(300);
            },
            success:function(data){
                console.log(data);
                 $("#block").html('<option value="">-Select-</option>');
                    for(var i=0;i<data.block_data.length;i++){
                    $("#block").append('<option value="'+data.block_data[i].geo_id+'">'+data.block_data[i].geo_name+'</option>');
                    }

            }

        });
    }

    //function for fetching subdivions according to district
    function ajaxFunc_subdivision(){
       
        var dist_id_tmp = $("#district").val();
      
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url:"{{url('scheme-performance/get-subdivision-name')}}",
            data:{'dist_id':dist_id_tmp},
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
                 $("#subdivision").html('<option value="">-Select-</option>');
                    for(var i=0;i<data.subdivision_data.length;i++){
                    $("#subdivision").append('<option value="'+data.subdivision_data[i].geo_id+'">'+data.subdivision_data[i].geo_name+'</option>');
                    }
                     $(".custom-loader").fadeOut(300);
            }

        });
    }

     function ajaxFunc_indicator(){
        var scheme_name_tmp = $("#scheme_name").val();
       
       if(scheme_name_tmp)
       {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('scheme-performance/get-indicator-name')}}",
            data: {'scheme_id': scheme_name_tmp},
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(data){
                $(".custom-loader").fadeIn(300);
            },
            error: function(xhr){
                alert("error"+xhr.status+", "+xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data){
                console.log(data);
                $("#indicator").html('<option value="">-Select-</option>');
                for(var i=0; i<data.scheme_indicator_data.length; i++){
                    $("#indicator").append('<option value="'+data.scheme_indicator_data[i].indicator_id+'">'+data.scheme_indicator_data[i].indicator_name+'</option>');
                }

                $(".custom-loader").fadeOut(300);
            }
        });
    }
    }

    function ajaxFunc_get_target(){
        var scheme_id_tmp = $("#scheme_name").val();
        var geo_id_tmp = $("#panchayat").val();
        var indicator_id_tmp = $("#indicator").val();
        var year_id_tmp = $("#year").val();
       


        if(scheme_id_tmp && geo_id_tmp && indicator_id_tmp && year_id_tmp)
        {

         $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
       });
         $.ajax({
            url:"{{url('scheme-performance/get-target')}}",
            data:{'scheme_id':scheme_id_tmp,'geo_id':geo_id_tmp,'indicator_id':indicator_id_tmp,'year_id':year_id_tmp},
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
                $("#scheme_geo_target_id").val(data.id);
                $("#pre_value").val(data.pre_value);
               

                if(data.target_data=='-1')
                {
                 $("#error_msg_if_target_not_found").html("No target found!!");
                  $("#target").val(0);
                 $("#pre-value").hide();
                 $("#current-value").hide();
                 $("#attchment-id").hide();
                 $("#go-button").hide();
                }
                else{
                $("#target").val(data.target_data);
               }

                $(".custom-loader").fadeOut(300);
            }
         });
       }
       else{
       $("#target").val(0);
   }
    }


//year_validation
   function year_validate(){
        var year_val = $("#year").val();
        if(year_val == ""){
            year_error = true;
            $("#year").addClass('is-invalid');
            $("#year_error_msg").html("Please select year");
           }
           else{
            year_error = false;
            $("#year").removeClass('is-invalid');
           }
   }

   //district validation
   function district_validate()
   {
    var district_val=$("#district").val();
    if(district_val=="")
    {
        district_error = true;
        $("#district").addClass('is-invalid');
        $("#district_error_msg").html("Please select district");

    }
    else{
        district_error=false;
        $("#district").removeClass('is-invalid');
    }
   }

   //subdivision validate
   function subdivision_validate()
   {
    var subdivision_val=$("#subdivision").val();
    if(subdivision_val=="")
    {
        subdivision_error = true;
        $("#subdivision").addClass('is-invalid');
        $("#subdivision_error_msg").html("Please select subdivision");

    }
    else{
        subdivision_error=false;
        $("#subdivision").removeClass('is-invalid');
    }
   }

//block name validation
function block_name_validate(){
    var block_name_val = $("#block").val();
    if(block_name_val=="")
    {
        block_name_error=true;
        $("#block").addClass('is-invalid');
        $("#block_error_msg").html("Please select block name");
    }
    else{
        block_name_error=false;
        $("#block").removeClass('is-invalid');
    }
}

//panchayat validation
   function panchayat_validate(){
        var panchayat_val = $("#panchayat").val();
        if(panchayat_val == ""){
            panchayat_error = true;
            $("#panchayat").addClass('is-invalid');
            $("#panchayat_error_msg").html("Please select panchayat");
           }
           else{
            panchayat_error = false;
            $("#panchayat").removeClass('is-invalid');
           }
   }

    // scheme type validation
    function scheme_type_validate(){
        var scheme_type_val = $("#scheme_name").val();
        

        if(scheme_type_val==""){
            scheme_type_error=true;
            $("#scheme_name").addClass('is-invalid');
            $("#scheme_type_error_msg").html("Please select scheme");
        }
        else{
            scheme_type_error=false;
            $("#scheme_name").removeClass('is-invalid');
        } 
    }

    //indicator validate
    function indicator_validate(){
        var indicator_val = $("#indicator").val();

        if(indicator_val==""){
            indicator_error = true;
            $("#indicator").addClass('is-invalid');
            $("#indicator_error_msg").html("Please select indicator");
        }
        else{
            indicator_error = false;
            $("#indicator").removeClass('is-invalid');
        }
    }



   

   function submitForm(){
     
     year_validate();
     district_validate();
     subdivision_validate();
     block_name_validate();
     panchayat_validate();
     scheme_type_validate();
     indicator_validate();
      
    

    if(  year_error || district_error||subdivision_error  ||block_name_error ||panchayat_error ||scheme_type_error||indicator_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
   

  
</script>
@endsection