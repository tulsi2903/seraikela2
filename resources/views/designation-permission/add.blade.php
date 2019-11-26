@extends('layout.layout')

@section('title', 'Designation Permission')

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Designation Permission</h4>
                        <div class="card-tools">
                        <a href="{{url('designation-permission')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('designation-permission/store')}}" method="POST">
                    @csrf
                        <div class="form-group">
                                <label for="desig_id">Designation</label>
                                <select name="desig_id" id="desig_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($designations as $designation )
                                     <option value="{{ $designation->desig_id }}" <?php if($data->desig_id==$designation->desig_id){ echo "selected"; } ?>>{{ $designation->name }}</option>
                                    @endforeach
                                 </select>
                                 
                               <div class="invalid-feedback" id="designation_error_msg"></div>
                                
                            </div>  
                        <div class="form-group">
                                <label for="mod_id">Module Name</label>
                                <select name="mod_id" id="mod_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($module_names as $module_name )
                                     <option value="{{ $module_name->mod_id }}" <?php if($data->mod_id==$module_name->mod_id){ echo "selected"; } ?>>{{ $module_name->mod_name }}</option>
                                    @endforeach
                                 </select>
                                 
                               <div class="invalid-feedback" id="module_name_error_msg"></div>
                                
                            </div>  
                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="checkbox" id="add" name="add" value='1' <?php if($data->add=='1'){ echo "checked"; } ?>>
                                        <label class="form-check-label" for="add">Add</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="edit" name="edit" value='1'  <?php if($data->edit=='1'){ echo "checked"; } ?>>
                                        <label class="form-check-label" for="edit">Edit</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="view" name="view" value='1' <?php if($data->view=='1'){ echo "checked"; } ?>>
                                        <label class="form-check-label" for="view" >View</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" id="delete" name="delete" value='1' <?php if($data->del=='1'){ echo "checked"; } ?>>
                                        <label class="form-check-label" for="delete" >Delete</label>
                                    </div>
                                </div>
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
    var designation_error = true;
    var module_name_error = true;
    

    $(document).ready(function(){
         $("#desig_id").change(function(){
            designation_validate();
        });
        $("#mod_id").change(function(){
            module_name_validate();
        });
       
    });


//designation validation
    function designation_validate(){
        var designation_val = $("#desig_id").val();
       
        if(designation_val==""){
            designation_error=true;
            $("#desig_id").addClass('is-invalid');
            $("#designation_error_msg").html("Designation should not be blank");
        }
        else{
            designation_error=false;
            $("#designation_error_msg").removeClass('is-invalid');
        }
    }

    //module name validation
    function module_name_validate(){
        var module_name_val = $("#mod_id").val();
        

        if(module_name_val==""){
            module_name_error=true;
            $("#mod_id").addClass('is-invalid');
            $("#module_name_error_msg").html("Module Name should not be blank");
        }
        else{
            module_name_error=false;
            $("#module_name_error_msg").removeClass('is-invalid');
        }
    }
    
   
    // final submission
    function submitForm(){
        designation_validate();
        module_name_validate();
        
        
        if(designation_error||module_name_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
    
    

        
</script>
@endsection

