@extends('layout.layout')

@section('title', 'Department')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Department1</h4>
                        <div class="card-tools">
                        <a href="{{url('department')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('department/store')}}" method="POST">
                    @csrf
                        <div class="form-group">
                            <label for="dept_name">Department Name<span style="color:red;margin-left:5px;">*</span></label>
                            <input type="text" name="dept_name" id="dept_name" class="form-control" value="{{$data->dept_name}}">
                            <div class="invalid-feedback" id="dept_name_error_msg"></div>
                        </div>
                        <div class="form-group">
                            <label for="org_id">Organisation<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="org_id" id="org_id" class="form-control">
                                <option value="">-Select-</option>
                                @foreach($organisation_datas as $organisation_data)
                                    <option value="{{$organisation_data->org_id}}" <?php if($data->org_id==$organisation_data->org_id){ echo "selected"; } ?>>{{$organisation_data->org_name}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="org_id_error_msg"></div>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Is Active<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="">-Select-</option>
                                <option value="1" <?php if($data->is_active=='1'){ echo "selected"; } ?>>Active</option>
                                <option value="0" <?php if($data->is_active=='0'){ echo "selected"; } ?>>Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="is_active_error_msg"></div>
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


<script>
    /* validation starts */
    // error variables as true = error occured
    var dept_name_error = true;
    var org_id_error = true;
    var is_active_error = true;

    $(document).ready(function(){
        $("#dept_name").change(function(){
            dept_name_validate();
        });
        $("#org_id").change(function(){
            org_id_validate();
        });
        $("#is_active").change(function(){
            is_active_validate();
        });
    });


    // department name vallidation
    function dept_name_validate(){
        var dept_name_val = $("#dept_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(dept_name_val==""){
            dept_name_error=true;
            $("#dept_name").addClass('is-invalid');
            $("#dept_name_error_msg").html("Department name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(dept_name_val)){
            dept_name_error=true;
            $("#dept_name").addClass('is-invalid');
            $("#dept_name_error_msg").html("Please enter valid department name");
        }
        else{
            dept_name_error=false;
            $("#dept_name").removeClass('is-invalid');
        }
    }

    // org_id validation
    function org_id_validate(){
        var org_id_val = $("#org_id").val();
        if(org_id_val==""){
            org_id_error=true;
            $("#org_id").addClass('is-invalid');
            $("#org_id_error_msg").html("Please select organisation");
        }
        else{
            org_id_error=false;
            $("#org_id").removeClass('is-invalid');
        } 
    }

    // is_active validation
    function is_active_validate(){
        var is_active_val = $("#is_active").val();
        if(is_active_val==""){
            is_active_error=true;
            $("#is_active").addClass('is-invalid');
            $("#is_active_error_msg").html("Please select department's status");
        }
        else{
            is_active_error=false;
            $("#is_active").removeClass('is-invalid');
        } 
    }

    // final submission
    function submitForm(){
        dept_name_validate();
        org_id_validate();
        is_active_validate();
        
        if(dept_name_error||org_id_error||is_active_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
</script>
@endsection