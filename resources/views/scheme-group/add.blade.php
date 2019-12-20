@extends('layout.layout')

@section('title', 'Add Group')

@section('page-content')
                    <div class="card">
                        <div class="col-md-12">
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                                        <h4 class="card-title">Scheme Group</h4>
                                        <div class="card-tools">
                                        <a href="{{url('scheme-group')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="card-body">
                            <form action="{{url('scheme-group/store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="group_name">Group Name<span style="color:red;margin-left:5px;">*</span></label>
                                        <input type="text" name="group_name" id="group_name" class="form-control" value="{{$results->scheme_group_name}}" autocomplete="off">
                                        <div class="invalid-feedback" id="group_name_error_msg"></div>
                                    </div>
                                </div>
                                    
                                <div class="col-md-4"> 
                                    <div class="form-group">
                                        <label for="is_active">Is Active<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="is_active" id="is_active" name="is_active" class="form-control">
                                            <option value="">-Select-</option>
                                            <option value="1" <?php if($results->is_active=='1'){ echo "selected"; } ?>>Active</option>
                                            <option value="0" <?php if($results->is_active=='0'){ echo "selected"; } ?>>Inactive</option>
                                        </select>
                                        <div class="invalid-feedback" id="is_active_error_msg"></div>
                                    </div>
                                </div>

                                <div class="col-md-4" style="margin-top: 2em;"> 
                                    <div class="form-group">
                                        <input type="hidden" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" >
                                        <input type="hidden" name="hidden_input_id" value="{{$hidden_input_id}}" >
                                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                                        <button type="reset" class="btn btn-secondary">Reset&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                                    </div>
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
    var group_name_error = true;
   
    var is_active_error = true;

    $(document).ready(function(){
        $("#group_name").change(function(){
            group_name_validate();
        });
        $("#is_active").change(function(){
            is_active_validate();
        });
    });


    // department name vallidation
    function group_name_validate(){
        var group_name_val = $("#group_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(group_name_val==""){
            group_name_errorgroup_name_error=true;
            $("#group_name").addClass('is-invalid');
            $("#group_name_error_msg").html("Scheme Group Name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(group_name_val)){
            group_name_error=true;
            $("#group_name").addClass('is-invalid');
            $("#group_name_error_msg").html("Please enter valid asset group name");
        }
        else{
            group_name_error=false;
            $("#group_name").removeClass('is-invalid');
        }
    }

   
    // is_active validation
    function is_active_validate(){
        var is_active_val = $("#is_active").val();
        if(is_active_val==""){
            is_active_error=true;
            $("#is_active").addClass('is-invalid');
            $("#is_active_error_msg").html("Please select is active or not");
        }
        else{
            is_active_error=false;
            $("#is_active").removeClass('is-invalid');
        } 
    }

    // final submission
    function submitForm(){
        group_name_validate();
        
        is_active_validate();
        
        if(group_name_error||is_active_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
</script>
@endsection