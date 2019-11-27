@extends('layout.layout')

@section('title', 'Designation')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
    
       <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
            <div class="col-md-12">
                <div class="card">
                    <h4 class="card-header">Designation</h4>
                    <div class="card-body">
                        <form action="{{url('designation/store')}}" method="POST">
                        @csrf
                            <div class="form-group">
                                <label for="name">Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{$data->name}}" autocomplete="off">
                                <div class="invalid-feedback" id="name_error_msg"></div>
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
    /* validations */
    // error variables as true = error occured
    var name_error = true;
    var org_id_error = true;

    $(document).ready(function(){
        $("#name").change(function(){
            name_validate();
        });
        $("#org_id").change(function(){
            org_id_validate();
        });
    });


    // name vallidation
    function name_validate(){
        var name_val = $("#name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(name_val==""){
            name_error=true;
            $("#name").addClass('is-invalid');
            $("#name_error_msg").html("Designation name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(name_val)){
            name_error=true;
            $("#name").addClass('is-invalid');
            $("#name_error_msg").html("Please enter valid designation name");
        }
        else{
            name_error=false;
            $("#name").removeClass('is-invalid');
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

    // final submission
    function submitForm(){
        name_validate();
        org_id_validate();
        
        if(name_error||org_id_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
</script>
@endsection