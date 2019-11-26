@extends('layout.layout')

@section('title', 'Scheme Geo Target')

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Geo Target</h4>
                        <div class="card-tools">
                        <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('scheme-geo-target/store')}}" method="POST">
                    @csrf
                            <div class="form-group">
                                <label for="scheme_name">Scheme Name<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="scheme_name" id="scheme_name" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $scheme_structures as $scheme_structure )
                                     <option value="{{ $scheme_structure->scheme_id }}" <?php if($data->scheme_id==$scheme_structure->scheme_id){ echo "selected"; } ?>>{{ $scheme_structure->scheme_name }}({{$scheme_structure->scheme_short_name}})</option>
                                    @endforeach
                                </select>
                                 <div class="invalid-feedback" id="scheme_name_error_msg"></div>
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
                                <label for="indicator">Indicator<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="indicator" id="indicator" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $indicators as $indicator )
                                     <option value="{{ $indicator->indicator_id }}" <?php if($data->indicator_id==$indicator->indicator_id){ echo "selected"; } ?>>{{ $indicator->indicator_name }}</option>
                                    @endforeach
                                </select>
                                 <div class="invalid-feedback" id="indicator_error_msg"></div>
                            </div>
                             <div class="form-group" id="scheme_group_block" style="display: none;">
                                <label for="asset_group_name">Asset Group Name<span style="color:red;margin-left:5px;">*</span></label>
                                 <select name="asset_group_name" id="asset_group_name" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($groups as $group )
                                     <option value="{{ $group->asset_group_id }}" <?php if($data->asset_group_id==$group->asset_group_id){ echo "selected"; } ?>>{{ $group->asset_group_name }}</option>
                                    @endforeach
                                 </select>
                                <div class="invalid-feedback" id="asset_group_name_error_msg"></div>
                            </div>
                             <div class="form-group">
                                <label for="target">Target<span style="color:red;margin-left:5px;">*</span></label>
                               <input type="text" name="target" id="target" class="form-control" value="{{$data->target}}" autocomplete="off">
                                 <div class="invalid-feedback" id="target_error_msg"></div>
                            </div>
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
                                <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                                <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                                <button type="submit" class="btn btn-primary" onclick="return submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                                <button type="reset" class="btn btn-secondary">Reset&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>
<script>

    var scheme_name_error = true;
   var panchayat_error = true;
   var indicator_error = true;
   var target_error = true;
   var year_error = true;
   var asset_group_name_error = true;

$(document).ready(function(){
    $('#scheme_name').change(function(){
        ajaxFunc();
    });
    $("#scheme_name").change(function(){
       scheme_name_validate();
    });
    $("#panchayat").change(function(){
      panchayat_validate();
    });
    $("#indicator").change(function(){
        indicator_validate();
    });
    $("#target").change(function(){
        target_validate();
    });
    $("#year").change(function(){
        year_validate();
    });
    $("#asset_group_name").change(function(){
        asset_group_name_validate();
    })

});
     function ajaxFunc(){
        var scheme_name_tmp = $("#scheme_name").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('scheme-geo-target/get-indicator-name')}}",
            data: {'scheme_id': scheme_name_tmp},
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(data){
                $(".loader").fadeIn(300);
            },
            error: function(xhr){
                alert("error"+xhr.status+", "+xhr.statusText);
                $(".loader").fadeOut(300);
            },
            success: function (data){
                console.log(data);
                $("#indicator").html('<option value="">-Select-</option>');
                for(var i=0; i<data.scheme_indicator_data.length; i++){
                    $("#indicator").append('<option value="'+data.scheme_indicator_data[i].indicator_id+'">'+data.scheme_indicator_data[i].indicator_name+'</option>');
                }

                if(data.independent==0){
                    $("#scheme_group_block").show();
                }
                else{
                    $("#scheme_group_block").hide();
                }
                $(".loader").fadeOut(300);
            }
        });
    }


//scheme name validation
   function scheme_name_validate(){
        var scheme_name_val = $("#scheme_name").val();
        if(scheme_name_val == ""){
            scheme_name_error = true;
            $("#scheme_name").addClass('is-invalid');
            $("#scheme_name_error_msg").html("Scheme Name should not be blank");
           }
           else{
            scheme_name_error = false;
            $("#scheme_name").removeClass('is-invalid');
           }
        }

//panchayat validation
   function panchayat_validate(){
        var panchayat_val = $("#panchayat").val();
        if(panchayat_val == ""){
            panchayat_error = true;
            $("#panchayat").addClass('is-invalid');
            $("#panchayat_error_msg").html("Panchayat should not be blank");
           }
           else{
            panchayat_error = false;
            $("#panchayat").removeClass('is-invalid');
           }
   }

//indicator validation
   function indicator_validate(){
 var indicator_val = $("#indicator").val();
        if(indicator_val == ""){
            indicator_error = true;
            $("#indicator").addClass('is-invalid');
            $("#indicator_error_msg").html("Indicator should not be blank");
           }
           else{
            indicator_error = false;
            $("#indicator").removeClass('is-invalid');
           }
   }

//target validation
    function target_validate(){
        var target_val = $("#target").val();
        var regNumeric = new RegExp('^[0-9]+$');
        if(target_val == "")
        {
            target_error = true;
            $("#target").addClass('is-invalid');
            $("#target_error_msg").html("Target should not be blank");
        }
        else if(!regNumeric.test(target_val)){
            $("#target").addClass('is-invalid');
            $("#target_error_msg").html("Please enter a valid target");
        }
        else{
            target_error = false;
            $("#target").removeClass('is-invalid');
        }
    }

//year_validation
   function year_validate(){
        var year_val = $("#year").val();
        if(year_val == ""){
            year_error = true;
            $("#year").addClass('is-invalid');
            $("#year_error_msg").html("Year should not be blank");
           }
           else{
            year_error = false;
            $("#year").removeClass('is-invalid');
           }
   }
   //asset group validation
   function asset_group_name_validate(){
    var asset_group_name_val = $("#asset_group_name").val();
   if(asset_group_name_val=="")
   {
    asset_group_name_error = true;
    $("#asset_group_name").addClass('is-invalid');
    $("#asset_group_name_error_msg").html("Asset Group Name should not be blank");

   }
   else{
    asset_group_name_error = false;
    $("asset_group_name").removeClass('is-invalid');
   }
   }

   function submitForm(){
      scheme_name_validate();
      panchayat_validate();
      indicator_validate(); 
      target_validate();
       year_validate();
       asset_group_name_validate(); 

    if(scheme_name_error || panchayat_error || indicator_error || target_error || year_error || asset_group_name_error ){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
   

  
</script>
@endsection