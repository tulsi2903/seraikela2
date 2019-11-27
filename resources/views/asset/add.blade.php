@extends('layout.layout')

@section('title', 'Asset')

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Asset</h4>
                        <div class="card-tools">
                        <a href="{{url('asset')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('asset/store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="asset_name">Asset Name<span style="color:red;margin-left:5px;">*</span></label>
                        <input type="text" name="asset_name" id="asset_name" class="form-control" value="{{$data->asset_name}}" autocomplete="off">
                         <div class="invalid-feedback" id="asset_name_error_msg"></div>
                        </div>
                        <div class="form-group">
                            <label for="movable">Type<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="movable" id="movable" class="form-control">
                                <option value="">---Select---</option>
                                <option value="1" <?php if($data->movable=='1'){ echo "selected"; } ?>>Movable</option>
                                <option value="0" <?php if($data->movable=='0'){ echo "selected"; } ?>>Immovable</option>
                            </select>
                             <div class="invalid-feedback" id="movable_error_msg"></div>
                        </div>
                        <div class="form-group">
                            <label for="dept_id">Department Name<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="dept_id" id="dept_id" class="form-control">
                                <option value="">---Select---</option>
                                @foreach( $departments as $department )
                                 <option value="{{ $department->dept_id }}" <?php if($data->dept_id==$department->dept_id){ echo "selected"; } ?>>{{ $department->dept_name }}</option>
                                @endforeach
                            </select>
                           <div class="invalid-feedback" id="department_error_msg"></div>
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
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
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