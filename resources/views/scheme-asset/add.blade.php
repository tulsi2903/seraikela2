@extends('layout.layout')

@section('title', 'Scheme Asset')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
<div class="card">
        <div class="col-md-12">
         
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Asset</h4>
                        <div class="card-tools">
                        <a href="{{url('scheme-asset')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        
        <div class="col-md-12">
            <div class="card-body">
                <form action="{{url('scheme-asset/store')}}" method="POST" id="scheme-asset-form">
                @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                            <label for="scheme_asset_name">Name<span style="color:red;margin-left:5px;">*</span></label>
                            <input name="scheme_asset_name" id="scheme_asset_name" class="form-control" autocomplete="off" value="{{$data->scheme_asset_name}}">
                                <div class="invalid-feedback" id="scheme_asset_name_error_msg"></div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <br><br>
                                <label for="geo_related">Geo Related</label>&nbsp;&nbsp;
                                <input type="checkbox" name="geo_related" id="geo_related" value="1"<?php echo ($data['geo_related']==1 ? 'checked' : '');?>>
                                
                            </div>
                        </div>

                        
                        <!-- @if($hidden_input_purpose=="edit" && $data['geo_related']==1)
                          
                            <div class="form-group" id="multiple_geo_tag">
                                <br><br>
                                <label for="multiple_geo_tags">Multiple Geo Tags</label>&nbsp;&nbsp;
                                <input type="checkbox" name="multiple_geo_tags" id="multiple_geo_tags" value="1"<?php echo ($data['multiple_geo_tags']==1 ? 'checked' : '');?>>
                                
                            </div>
                       
                       

                        
                            <div class="form-group" id="no_of_tag">
                            <label for="no_of_tags">Number of Tags<span style="color:red;margin-left:5px;">*</span></label>
                            <input name="no_of_tags" id="no_of_tags" class="form-control" autocomplete="off" value="{{$data->no_of_tags}}">
                                <div class="invalid-feedback" id="no_of_tags_error_msg"></div>
                            </div>
                       
                        
                        @endif -->

                        <div class="col-md-2">
                            <div class="form-group" id="multiple_geo_tag">
                                <br><br>
                                <label for="multiple_geo_tags">Multiple Geo Tags</label>&nbsp;&nbsp;
                                <input type="checkbox" name="multiple_geo_tags" id="multiple_geo_tags" value="1"<?php echo ($data['multiple_geo_tags']==1 ? 'checked' : '');?>>
                                
                            </div>
                        </div>
                       

                        <div class="col-md-3">
                            <div class="form-group" id="no_of_tag" >
                            <label for="no_of_tags">Number of Tags<span style="color:red;margin-left:5px;">*</span></label>
                            <input name="no_of_tags" id="no_of_tags" class="form-control" autocomplete="off" value="{{$data->no_of_tags}}">
                                <div class="invalid-feedback" id="no_of_tags_error_msg"></div>
                            </div>
                        </div>
                        
                    </div><!--end of row-->

                   
                    
                    <hr/>
                
                    <div class="col-md-12">
                                <button class="btn"  style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Attributes</button>
                                    <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                                    <table class="table order-list" style="margin-top: 10px;">
                                        <thead style="background: #cedcff">                                              
                                            <tr>
                                                <th>Name</th>
                                                <th>UoM</th>
                                                <th>Action</th>                                              
                                            </tr>
                                        </thead> 
                                        <tbody id="append-name-uom">
                                        <?php $attributes = unserialize($data->attribute); ?>

                                        @foreach($attributes as $key=>$attribute)
                                            <tr>
                                            <td><input type="text" class="form-control" name="attribute_name[]" id="attribute_name" autocomplete="off" value="{{$key}}"></td>
                                            <td> <select name="attribute_uom[]" id="attribute_uom" class="form-control" onchange="attribute_uom_validate();">
                                            
                                                @foreach($uom_datas as $uom_data )
                                                    <option value="{{ $uom_data->uom_id }}" <?php if ($uom_data->uom_id == $attribute) {
                                                                echo "selected";
                                                            } ?>> {{ $uom_data->uom_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">UoM should not be blank</div>
                                            </td>
                                            <td><button type="button" class="btn btn-danger delete-button-row">Remove</button></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tbody>
                                            <td style="text-align:right;" colspan="3"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;" onclick="append_table_data();"></i></tr>
                                        </tbody>

                                       
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                            <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                            <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                               
                            <button type="submit" class="btn btn-primary" style="float:right;" onclick="return submitForm();">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                        </div>
                </form>
            </div><!--end of card body-->
        </div>

<script>
function append_table_data()
{
    var data=`<tr><td><input type="text" class="form-control" name="attribute_name[]" id="attribute_name" autocomplete="off" onchange="attribute_name_validate();"><div class="invalid-feedback">Name should not be blank</div></td>
                                    <td> <select name="attribute_uom[]" id="attribute_uom" class="form-control" onchange="attribute_uom_validate();">
                                    <option value="">---Select---</option>
                                    @foreach($uom_datas as $uom_data )
                                        <option value="{{ $uom_data->uom_id }}">{{ $uom_data->uom_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">UoM should not be blank</div>
                                </td> <td><button type="button" class="btn btn-danger delete-button-row">Remove</button></td></tr>`;
    $("#append-name-uom").append(data);
}

$(document).ready(function(){
$("#append-name-uom").delegate(".delete-button-row", "click", function() {
$(this).closest("tr").remove();
      });
    });
</script>

<script>
$(document).ready(function () {
    var chkbox = $('#multiple_geo_tags');
    $('#multiple_geo_tags').on('click',function () {
        if (chkbox.is(':checked')) {
           
        } 
        else{
            $('#no_of_tags').val("");
            
        }
    });
    // if(chkbox="" && tags_val!="")
    // {
    //     $('.multiple_geo_tags').attr('checked', true);
    // }
});
</script>
<!-- <script>
$(document).ready(function(){
   $("#multiple_geo_tag").hide();
   $("#no_of_tag").hide();
   
});
$(document).ready(function () {
    var ckbox = $('#geo_related');
    $('#geo_related').on('click',function () {
        if (ckbox.is(':checked')) {
            $("#multiple_geo_tag").show();
        } 
        else{
            $("#multiple_geo_tag").hide();
            $("#no_of_tag").hide();
        }
    });
});
$(document).ready(function () {
    var chkbox = $('#multiple_geo_tags');
    $('#multiple_geo_tags').on('click',function () {
        if (chkbox.is(':checked')) {
            $("#no_of_tag").show();
        } 
        else{
            $("#no_of_tag").hide();
        }
    });
});
</script> -->

<!-- for validation -->
<script>
var scheme_asset_name_error = true;
var no_of_tags_error = true;
var attribute_name_error = true;
var attribute_uom_error = true;

$(document).ready(function(){
   
    $("#scheme_asset_name").change(function(){
        scheme_asset_name_validate();
    });
    $("#no_of_tags").change(function(){
        var tags_val = $('#no_of_tags').val();
       if(tags_val)
       {
        $('#multiple_geo_tags').attr('checked', true);
        $('#geo_related').attr('checked', true);
       }
        no_of_tags_validate();
       
    });
    
});



 function no_of_tags_validate() {
   
        var no_of_tags_val = $("#no_of_tags").val();
        var regNumericSpace = new RegExp('^[0-9 ]+$');
        if (no_of_tags_val == "") {
            no_of_tags_error = false;
            // $("#no_of_tags").addClass('is-invalid');
            // $("#no_of_tags_error_msg").html("Number of tags should not be blank");
        } else if (!regNumericSpace.test(no_of_tags_val)) {
            no_of_tags_error = true;
            $("#no_of_tags").addClass('is-invalid');
            $("#no_of_tags_error_msg").html("Please enter valid number");
        } else {
            no_of_tags_error = false;
            $("#no_of_tags").removeClass('is-invalid');
        }
    }

    function scheme_asset_name_validate() {
        var scheme_asset_name_val = $("#scheme_asset_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9/_ ]+$');
        if (scheme_asset_name_val == "") {
            scheme_asset_name_error = true;
            $("#scheme_asset_name").addClass('is-invalid');
            $("#scheme_asset_name_error_msg").html("Scheme Asset Name should not be blank");
        } else if (!regAlphaNumericSpace.test(scheme_asset_name_val)) {
            scheme_asset_name_error = true;
            $("#scheme_asset_name").addClass('is-invalid');
            $("#scheme_asset_name_error_msg").html("Please enter valid name");
        } else {
            scheme_asset_name_error = false;
            $("#scheme_asset_name").removeClass('is-invalid');
        }
    }

    function attribute_name_validate() {
        var attribute_name_val =  $("input[name='attribute_name[]']");
       
        console.log(attribute_name_val.length);
        for(i=0;i<attribute_name_val.length;i++)
        {
            if (attribute_name_val[i].value == "") {
                attribute_name_error = true;
                $(attribute_name_val[i]).addClass('is-invalid');
            } 
            else {
                attribute_name_error = false;
                $(attribute_name_val[i]).removeClass('is-invalid');
            }
        }

        if(attribute_name_val.length == 0)
        {
            attribute_name_error = false;
        }
    }

    function attribute_uom_validate(){
        var attribute_uom_val = $("select[name='attribute_uom[]']");
        for(i=0;i<attribute_uom_val.length;i++)
        {
            if(attribute_uom_val[i].value== ""){
                attribute_uom_error = true;
                $(attribute_uom_val[i]).addClass('is-invalid');
            }
            else{
                attribute_uom_error = false;
                $(attribute_uom_val[i]).removeClass('is-invalid');
            }
        }
        if(attribute_uom_val.length == 0)
        {
            attribute_uom_error = false;
        }

    }


    function submitForm() {
        scheme_asset_name_validate();
        no_of_tags_validate();
        attribute_name_validate();
        attribute_uom_validate();



        if (scheme_asset_name_error || no_of_tags_error || attribute_name_error || attribute_uom_error) {
            return false;
           
        } // error occured
        else {
           
            return true;
           
        } // proceed to submit form data
       
    }
</script>
@endsection




