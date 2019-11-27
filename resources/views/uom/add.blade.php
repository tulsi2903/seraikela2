@extends('layout.layout')

@section('title', 'UOM')

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
                        <h4 class="card-title">UoM</h4>
                        <div class="card-tools">
                        <a href="{{url('uom')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('uom/store')}}" method="POST">
                    @csrf
                            <div class="form-group">
                                <label for="uom_name">UoM Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" name="uom_name" id="uom_name" class="form-control" value="{{$data->uom_name}}" autocomplete="off">
                                 
                               <div class="invalid-feedback" id="uom_name_error_msg"></div>
                                
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
    var uom_name_error = true;
    
    $(document).ready(function(){
        $("#uom_name").change(function(){
            uom_name_validate();
        });
       
    });
    
     function uom_name_validate(){
         
        var uom_name_val = $("#uom_name").val();
         var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
       if(uom_name_val==""){
            uom_name_error=true;
            $("#uom_name").addClass('is-invalid');
            $("#uom_name_error_msg").html("UoM should not be blank");
        }
        else if(!regAlphaNumericSpace.test(uom_name_val)){
            uom_name_error=true;
            $("#uom_name").addClass('is-invalid');
            $("#uom_name_error_msg").html("Please enter valid UoM");
        }
        
        
        else{
            uom_name_error=false;
            $("#uom_name").removeClass('is-invalid');
        }
    }
    
    function submitForm(){
        uom_name_validate();
      
        if(uom_name_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
</script>
@endsection