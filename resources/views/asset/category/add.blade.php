@extends('layout.layout')

@section('title', 'Resource Category')

@section('page-content')
<div class="card">
        <div class="col-md-12">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">{{$phrase->resource_catagory}}</h4>
                        <div class="card-tools">
                        <a href="{{url('assetcat')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;{{$phrase->back}}</a>
                        </div>
                    </div>
                </div>
            </div>
        
        <div class="card-body">
           
                    <form action="{{url('assetcat/store')}}" method="POST">
                    @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asset_name">{{$phrase->resource_category_name}}<span style="color:red;margin-left:5px;">*</span></label>
                        <input type="text" name="asset_cat_name" id="asset_name" class="form-control" value="{{$data->asset_cat_name}}" autocomplete="off">
                         <div class="invalid-feedback" id="asset_name_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="movable">{{$phrase->type}} <span style="color:red;margin-left:5px;">*</span></label>
                        <select name="movable" id="movable" class="form-control">
                            <option value="">---Select---</option>
                            <option value="1" <?php if($data->movable=='1'){ echo "selected"; } ?>>Movable</option>
                            <option value="0" <?php if($data->movable=='0'){ echo "selected"; } ?>>Immovable</option>
                        </select>
                            <div class="invalid-feedback" id="movable_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dept_id">{{$phrase->resource_category_description}}<span style="color:red;margin-left:5px;"></span></label>
                        <input type="textarea" class="form-control" name="asset_cat_description" value="{{$data->asset_cat_description}}">
                    </div>
                </div>
                       
                <div class="col-md-3" style="margin-top: 2em;">
                    <div class="form-group">
                        <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                        <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">{{$phrase->save}}&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                        <button type="reset" class="btn btn-secondary">{{$phrase->reset}}&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                    </div>
                </div>
                    </form>
                </div>
            </div>
       
    </div>
</div>

<script>
    /* validation starts */
    // error variables as true = error occured
    var asset_name_error = true;
    var movable_error = true;
    var department_error = true;

    $(document).ready(function(){
        $("#asset_name").change(function(){
            asset_name_validate();
        });
        $("#movable").change(function(){
            movable_validate();
        });
         $("#dept_id").change(function(){
            department_validate();
        });
    });


    //asset name validation
    function asset_name_validate(){
        var asset_name_val = $("#asset_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9,&/ ]+$');
        if(asset_name_val==""){
            asset_name_error=true;
            $("#asset_name").addClass('is-invalid');
            $("#asset_name_error_msg").html("Asset Name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(asset_name_val)){
            asset_name_error=true;
            $("#asset_name").addClass('is-invalid');
            $("#asset_name_error_msg").html("Please enter valid asset");
        }
        else{
            asset_name_error=false;
            $("#asset_name").removeClass('is-invalid');
        }
    }
    
     //movable validation
    function movable_validate(){
        var movable_val = $("#movable").val();
       
        if(movable_val==""){
            movable_error=true;
            $("#movable").addClass('is-invalid');
            $("#movable_error_msg").html("Type should not be blank");
        }
        
        else{
            movable_error=false;
            $("#movable").removeClass('is-invalid');
        }
    }


   
    // department name validation
    function department_validate(){
        var department_val = $("#dept_id").val();
        

        if(department_val==""){
            department_error=true;
            $("#dept_id").addClass('is-invalid');
            $("#department_error_msg").html("Department Name should not be blank");
        }
        else{
            department_error=false;
            $("#dept_id").removeClass('is-invalid');
        } 
    }

    // final submission
    function submitForm(){
        asset_name_validate();
       movable_validate();
        department_validate();
        
        if(asset_name_error || movable_error || department_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
</script>
@endsection