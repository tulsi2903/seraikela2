@extends('layout.layout')

@section('title', 'Define Schemes')

@section('page-content')
  <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Define Schemes</h4>
                        <div class="card-tools">
                        <a href="{{url('scheme-structure')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('scheme-structure/store')}}" method="POST">
                    @csrf
                            <div class="form-group">
                                <label for="scheme_name">Scheme Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" name="scheme_name" id="scheme_name" class="form-control" value="{{$data->scheme_name}}" autocomplete="off">
                                <div class="invalid-feedback" id="scheme_name_error_msg"></div>
                            </div>
                            <div class="form-group">
                                <label for="scheme_short_name">Short Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" name="scheme_short_name" id="scheme_short_name" class="form-control" value="{{$data->scheme_short_name}}" autocomplete="off">
                                <div class="invalid-feedback" id="scheme_short_name_error_msg"></div>
                            </div>
                            <div class="form-group">
                                <label for="is_active">Is Active<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="1" <?php if($data->is_active=='1'){ echo "selected"; } ?>>Active</option>
                                    <option value="0" <?php if($data->is_active=='0'){ echo "selected"; } ?>>Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="is_active_error_msg"></div>
                            </div>
                              <div class="form-group">
                                <label for="dept_id">Department Name<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="dept_id" id="dept_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $departments as $department )
                                     <option value="{{ $department->dept_id }}" <?php if($data->dept_id==$department->dept_id){ echo "selected"; } ?>>{{ $department->dept_name }}</option>
                                    @endforeach
                                </select>
                               <div class="invalid-feedback" id="department_name_error_msg"></div>
                            </div>
                             <div class="form-group">
                                <label for="sch_type_id">Scheme Type<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="scheme_type_id" id="scheme_type_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $scheme_types as $scheme_type )
                                     <option value="{{ $scheme_type->sch_type_id }}" <?php if($data->scheme_type_id==$scheme_type->sch_type_id){ echo "selected"; } ?>>{{ $scheme_type->sch_type_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="scheme_type_error_msg"></div>
                            </div>
                             <div class="form-group">
                                <label for="independent">Independent<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="independent" id="independent" class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="1" <?php if($data->independent=='1'){ echo "selected"; } ?>>Yes</option>
                                    <option value="0" <?php if($data->independent=='0'){ echo "selected"; } ?>>No</option>
                                </select>
                                <div class="invalid-feedback" id="independent_error_msg"></div>
                            </div>
                     <div class="row">
                            <div class="form-group col-md-6">
                                <label for="planned_sd">Planned Start Date</label>
                                <input type="date" id="planned_sd" name="planned_sd" class="form-control" value="{{$data->planned_sd}}">
                            </div>
                             <div class="form-group col-md-6">
                                <label for="planned_ed">Planned End Date</label>
                                <input type="date" id="planned_ed" name="planned_ed" class="form-control" value="{{$data->planned_ed}}">
                            </div>
                    </div>
                    <div class="row">
                         <div class="form-group col-md-6">
                                <label for="actual_sd">Actual Start Date</label>
                                <input type="date" id="actual_sd" name="actual_sd" class="form-control" value="{{$data->actual_sd}}">
                            </div>
                             <div class="form-group col-md-6">
                                <label for="actual_ed">Actual End Date</label>
                                <input type="date" id="actual_ed" name="actual_ed" class="form-control" value="{{$data->actual_ed}}">
                            </div>
                    </div>
                            <div class="form-group">
                                <label for="description">Description/Comment</label>
                                <textarea class="form-control" id="description" name="description">{{$data->description}}</textarea>
                                 <div class="invalid-feedback" id="scheme_name_error_msg"></div>
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
    var scheme_name_error = true;
    var scheme_short_name_error = true;
    var isactive_error = true;
    var department_name_error = true;
    var scheme_type_error = true;
    var independent = true;

    $(document).ready(function(){
        $("#scheme_name").change(function(){
            scheme_name_validate();
        });
        $("#scheme_short_name").change(function(){
            scheme_short_name_validate();
        });
         $("#is_active").change(function(){
            isactive_validate();
        });
          $("#dept_id").change(function(){
            department_validate();
        });
           $("#scheme_type_id").change(function(){
            scheme_type_id_validate();
        });
            $("#independent").change(function(){
            independent_validate();
        });
    });


    //scheme name validation
    function scheme_name_validate(){
        var scheme_name_val = $("#scheme_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(scheme_name_val==""){
            scheme_name_error=true;
            $("#scheme_name").addClass('is-invalid');
            $("#scheme_name_error_msg").html("Scheme Name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(scheme_name_val)){
            scheme_name_error=true;
            $("#scheme_name").addClass('is-invalid');
            $("#scheme_name_error_msg").html("Please enter valid scheme");
        }
        else{
            scheme_name_error=false;
            $("#scheme_name").removeClass('is-invalid');
        }
    }

//scheme short name validation
     function scheme_short_name_validate(){
        var scheme_short_name_val = $("#scheme_short_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(scheme_short_name_val==""){
            scheme_short_name_error=true;
            $("#scheme_short_name").addClass('is-invalid');
            $("#scheme_short_name_error_msg").html("Scheme Short Name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(scheme_short_name_val)){
            scheme_short_name_error=true;
            $("#scheme_short_name").addClass('is-invalid');
            $("#scheme_short_name_error_msg").html("Please enter valid short name");
        }
        else{
            scheme_short_name_error=false;
            $("#scheme_short_name").removeClass('is-invalid');
        }
    }
    
      //is-active validation
    function is_active_validate(){
        var is_active_val = $("#is_active").val();
       
        if(is_active_val==""){
            is_active_error=true;
            $("#is_active").addClass('is-invalid');
            $("#is_active_error_msg").html("Is Active should not be blank");
        }
        
        else{
            is_active_error=false;
            $("#is_active").removeClass('is-invalid');
        }
    }


   
     // department name validation
    function department_name_validate(){
        var department_name_val = $("#dept_id").val();
        

        if(department_name_val==""){
            department_name_error=true;
            $("#dept_id").addClass('is-invalid');
            $("#department_name_error_msg").html("Department Name should not be blank");
        }
        else{
            department_name_error=false;
            $("#dept_id").removeClass('is-invalid');
        } 
    }

     // scheme type validation
    function scheme_type_validate(){
        var scheme_type_val = $("#scheme_type_id").val();
        

        if(scheme_type_val==""){
            scheme_type_error=true;
            $("#scheme_type_id").addClass('is-invalid');
            $("#scheme_type_error_msg").html("Scheme Type should not be blank");
        }
        else{
            scheme_type_error=false;
            $("#scheme_type_id").removeClass('is-invalid');
        } 
    }

     // independent validation
    function independent_validate(){
        var independent_val = $("#independent").val();
        

        if(independent_val==""){
            independent_error_msg=true;
            $("#independent").addClass('is-invalid');
            $("#independent_error_msg").html("Scheme Type should not be blank");
        }
        else{
            independent_error_msg=false;
            $("#independent").removeClass('is-invalid');
        } 
    }

    // final submission
    function submitForm(){
        scheme_name_validate();
       scheme_short_name_validate();
       is_active_validate();
       department_name_validate();
       scheme_type_validate();
        independent_validate();
        
        if(scheme_name_error || scheme_short_name_error || is_active_error || department_name_error || scheme_type_error || independent_error_msg){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
</script>
@endsection