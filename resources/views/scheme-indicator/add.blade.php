@extends('layout.layout')

@section('title', 'Scheme Indicator')

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Indicator</h4>
                        <div class="card-tools">
                        <a href="{{url('scheme-indicator')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('scheme-indicator/store')}}" method="POST">
                    @csrf
                            
                            <div class="form-group">
                                <label for="indicator_name">Indicator Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" id="indicator_name" name="indicator_name" class="form-control" value="{{$data->indicator_name}}" autocomplete="off">
                                <div class="invalid-feedback" id="indicator_name_error_msg"></div>
                            </div>
                        
                         <div class="form-group">
                                <label for="scheme_name">Scheme Name<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="scheme_name" id="scheme_name" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $scheme_structures as $scheme_structure )
                                     <option value="{{ $scheme_structure->scheme_id }}" <?php if($data->scheme_id==$scheme_structure->scheme_id){ echo "selected"; } ?>>{{ $scheme_structure->scheme_name }}({{$scheme_structure->scheme_short_name}})</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="scheme_name_error_msg"></div>
                            </div>

                                <div class="form-group">
                                <label for="uom">UoM<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="uom" id="uom" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $uoms as $uom )
                                     <option value="{{ $uom->uom_id }}" <?php if($data->unit==$uom->uom_id){ echo "selected"; } ?>>{{ $uom->uom_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="uom_error_msg"></div>
                            </div>

                            <div class="form-group">
                                <label for="performance">Performance<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="performance" id="performance" class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="1" <?php if($data->performance=='1'){ echo "selected"; } ?>>Yes</option>
                                    <option value="0" <?php if($data->performance=='0'){ echo "selected"; } ?>>No</option>
                                </select>
                                 <div class="invalid-feedback" id="performance_error_msg"></div>

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
    var indicator_name_error = true;
    var scheme_name_error = true;
    var uom_error = true;
    var performance_error = true;
   
    $(document).ready(function(){
        $("#indicator_name").change(function(){
            indicator_name_validate();
        });
        $("#scheme_name").change(function(){
            scheme_name_validate();
        });
         $("#uom").change(function(){
            uom_validate();
        });
          $("#performance").change(function(){
            performance_validate();
        });
          
    });


    //indicator name validation
    function indicator_name_validate(){
        var indicator_name_val = $("#indicator_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(indicator_name_val==""){
            indicator_name_error=true;
            $("#indicator_name").addClass('is-invalid');
            $("#indicator_name_error_msg").html("Indicator Name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(indicator_name_val)){
            indicator_name_error=true;
            $("#indicator_name").addClass('is-invalid');
            $("#indicator_name_error_msg").html("Please enter valid Indicator Name");
        }
        else{
            indicator_name_error=false;
            $("#indicator_name").removeClass('is-invalid');
        }
    }

 function scheme_name_validate(){
        var scheme_name_val = $("#scheme_name").val();
       if(scheme_name_val=="")
         {
            scheme_name_error=true;
            $("#scheme_name").addClass('is-invalid');
            $("#scheme_name_error_msg").html("Scheme Name should not be blank");
        }
        else{
            scheme_name_error=false;
            $("#scheme_name").removeClass('is-invalid');
        }
    }

//uom validate
    function uom_validate(){
        var uom_val = $("#uom").val();
       if(uom_val=="")
         {
            uom_error=true;
            $("#uom").addClass('is-invalid');
            $("#uom_error_msg").html("UoM should not be blank");
        }
        else{
            uom_error=false;
            $("#uom").removeClass('is-invalid');
        }
    }

    //performance validate
    function performance_validate(){
        var performance_val = $("#performance").val();
       if(performance_val=="")
       {
            performance_error=true;
            $("#performance").addClass('is-invalid');
            $("#performance_error_msg").html("Performance should not be blank");
        }
        else{
            performance_error=false;
            $("#performance").removeClass('is-invalid');
        }
    }


    // final submission
    function submitForm(){
        indicator_name_validate();
      scheme_name_validate();
       uom_validate();
      performance_validate();
      
        
        if(indicator_name_error || scheme_name_error || uom_error || performance_error ){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
</script>

@endsection

