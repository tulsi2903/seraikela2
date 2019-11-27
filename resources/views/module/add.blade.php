@extends('layout.layout')

@section('title', 'Module')

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Module</h4>
                        <div class="card-tools">
                        <a href="{{url('module')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('module/store')}}" method="POST">
                    @csrf
                        <div class="form-group">
                                <label for="module_name">Module Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" name="module_name" id="module_name" class="form-control" value="{{$data->mod_name}}" autocomplete="off">
                                 
                               <div class="invalid-feedback" id="module_name_error_msg"></div>
                                
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
    var module_name_error = true;
    

    $(document).ready(function(){
        $("#module_name").change(function(){
            module_name_validate();
        });
       
    });


    //module name validation
    function module_name_validate(){
        var module_name_val = $("#module_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');

        if(module_name_val==""){
            module_name_error=true;
            $("#module_name").addClass('is-invalid');
            $("#module_name_error_msg").html("Module Name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(module_name_val)){
            module_name_error=true;
            $("#module_name").addClass('is-invalid');
            $("#module_name_error_msg").html("Please enter valid module name");
        }

        else{
            module_name_error=false;
            $("#module_name_error_msg").removeClass('is-invalid');
        }
    }
    
   
    // final submission
    function submitForm(){
        module_name_validate();
        
        
        if(module_name_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
    
    

        
</script>
@endsection

