@extends('layout.layout')

@section('title', 'Add Year')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
            <div class="col-md-12">
                <div class="card">
                    <h4 class="card-header">Year<a href="{{url('year')}}" class="btn btn-sm btn-primary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a></h4>
                    <div class="card-body">
                        <form action="{{url('year/store')}}" method="POST">
                        @csrf
                            
                            <div class="form-group">
                                <label for="year_value">From</label>
                                <select name="from_value" id="from_value" class="form-control">
                                    <option value="">--Select---</option>
                                   
                                      @for ($from=2015; $from < 2049; $from++) 
                                      <option value="{{$from}}" <?php if($data->from==$from){ echo "selected"; } ?>>{{$from}}</option>
                                        @endfor
                                </select>
                                <div class="invalid-feedback" id="from_value_error_msg"></div>
                            </div>
                        
                         <div class="form-group">
                                <label for="year_value">To</label>
                                <select name="to_value" id="to_value" class="form-control">
                                    <option value="">--Select---</option>
                                   
                                      @for ($to=2015; $to < 2049; $to++) 
                                      <option value="{{$to}}" <?php if($data->to==$to){ echo "selected"; } ?>>{{$to}}</option>
                                        @endfor
                                </select>
                                <div class="invalid-feedback" id="to_value_error_msg"></div>
                            </div>

                            <div class="form-group">
                                <label for="status">Is Active</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="1" <?php if($data->status=='1'){ echo "selected"; } ?>>Active</option>
                                    <option value="0" <?php if($data->status=='0'){ echo "selected"; } ?>>Inactive</option>
                                </select>
                                 <div class="invalid-feedback" id="status_error_msg"></div>

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
    var from_value_error = true;
    var to_value_error = true;
    var status_error = true;

    $(document).ready(function(){
        $("#from_value").change(function(){
            from_value_validate();
        });
        $("#to_value").change(function(){
            to_value_validate();
        });
        $("#status").change(function(){
            status_validate();
        });
    });


    //year validation
    function from_value_validate(){
        var from_value_val = $("#from_value").val();
        
        if(from_value_val==""){
            from_value_error=true;
            $("#from_value").addClass('is-invalid');
            $("#from_value_error_msg").html("From should not be blank");
        }
       
        else{
            from_value_error=false;
            $("#from_value").removeClass('is-invalid');
        }
    }
    
    function to_value_validate(){
        var to_value_val = $("#to_value").val();
        
        if(to_value_val==""){
            to_value_error=true;
            $("#to_value").addClass('is-invalid');
            $("#to_value_error_msg").html("From should not be blank");
        }
       
        else{
            to_value_error=false;
            $("#to_value").removeClass('is-invalid');
        }
    }

   
    // status validation
    function status_validate(){
        var status_val = $("#status").val();
        if(status_val==""){
            status_error=true;
            $("#status").addClass('is-invalid');
            $("#status_error_msg").html("Please select year's status");
        }
        else{
            status_error=false;
            $("#status").removeClass('is-invalid');
        } 
    }

    // final submission
    function submitForm(){
        from_value_validate();
        to_value_validate();
        status_validate();
        
        if(from_value_error||from_value_error||status_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
    
    
$(document).ready(function(){
    $("#from_value").change(function(){
        var tmp = $("#from_value").val();
        $('#to_value option').filter(function(){
           return parseInt(this.value,10) <= tmp;
        }).hide();
    });
    
   

    
});
        
</script>
@endsection

