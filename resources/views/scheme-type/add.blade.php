@extends('layout.layout')

@section('title', 'Scheme Type')


@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Type</h4>
                        <div class="card-tools">
                        <a href="{{url('scheme-type')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('scheme-type/store')}}" method="POST">
                    @csrf

                            <div class="form-group">
                                <label for="sch_type_name">Scheme Name</label>
                                <input type="text" name="sch_type_name" id="sch_type_name" class="form-control" value="{{$data->sch_type_name}}">
                                <div class="invalid-feedback" id="scheme_name_error_msg"></div>
                            </div>
                            
                           
                            <div class="form-group">
                                <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                                <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                                <button type="submit" class="btn btn-primary" onclick="return submitForm()" id="sub">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
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
    
  $("#scheme_name_error_msg").hide();
    var err_scheme_name =true;
      
    $(document).ready(function(){
       
         $("#sch_type_name").blur(function(){

            scheme_name();

        });
        function scheme_name(){

          var c = $("#sch_type_name").val();
          
          
          if (c==""){
            $("#scheme_name_error_msg").show();
            $("#sch_type_name").addClass('is-invalid');
            $("#scheme_name_error_msg").html("Please enter Scheme");
            $("#scheme_name_error_msg").focus();
            $("#scheme_name_error_msg").css("color","red");
            err_scheme_name=false;
            
          }
          else{
            err_scheme_name=true;
             $("#sch_type_name").removeClass('is-invalid');
              $("#scheme_name_error_msg").hide();
          }
    
        }
    // sheme_type validation
//    function scheme_type_validate(){
//       
//        var scheme_type_val = $("#sch_type_name").val();
//        if(scheme_type_val==""){
//            scheme_type_error=true;
//            $("#sch_type_name").addClass('is-invalid');
//            $("#scheme_name_error_msg").html("Please select scheme");
//        }
//        else{
//            scheme_type_error=false;
//            $("#scheme_name_error_msg").removeClass('is-invalid');
//        } 
//    }

    // final submission
//    function submitForm(){
//         scheme_type_validate();
//        
//        if(scheme_type_error){ return false; } // error occured
//        else{ return true; } // proceed to submit form data
//       
//    }
    
       $("#sub").click(function(){

      
        err_scheme_name =true;
       

        scheme_name();
       
     

        if(err_scheme_name==true)
        {
          return true;
        }else{
          return false;
        }
        

     });
    });
        
</script>
@endsection