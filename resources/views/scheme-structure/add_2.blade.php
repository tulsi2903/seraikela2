@extends('layout.layout')

@section('title', 'Define Schemes')

@section('page-style')
<style>

</style>
@endsection

@section('page-content')
<div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="float:left;"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;Define Scheme</div>
            </div><br><br>
            <!-----------------------------------------start of Scheme Detail Form------------------------------------------>
            <div class="card-body" style="margin-top: -57px;">
                 <form action="{{url('scheme-structure/store')}}" enctype="multipart/form-data" method="POST">
                        @csrf()
                <div class="row">
                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="scheme_name">Scheme Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" name="scheme_name" id="scheme_name" class="form-control" value="{{$data->scheme_name}}" autocomplete="off">
                                <div class="invalid-feedback" id="scheme_name_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="scheme_short_name">Short Name<span style="color:red;margin-left:5px;">*</span></label>
                                <input type="text" name="scheme_short_name" id="scheme_short_name" class="form-control" value="{{$data->scheme_short_name}}" autocomplete="off">
                                <div class="invalid-feedback" id="scheme_short_name_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                 <label for="dept_id">Department<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="dept_id" id="dept_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $departments as $department )
                                     <option value="{{ $department->dept_id }}" <?php if($data->dept_id==$department->dept_id){ echo "selected"; } ?>>{{ $department->dept_name }}</option>
                                    @endforeach
                                </select>
                               <div class="invalid-feedback" id="department_name_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="is_active">Status<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="1" <?php if($data->is_active=='1'){ echo "selected"; } ?>>Active</option>
                                    <option value="0" <?php if($data->is_active=='0'){ echo "selected"; } ?>>Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="is_active_error_msg"></div>
                            </div>
                        </div>
                    </div>
                <div class="row">
                   <!--  <div class="col-md-3">
                            <div class="form-group" style="width: 70%;">
                                <label for="">Plan Start Date</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="mm/dd/yyyy" name="planned_sd" id="planned_sd" class="form-control datepicker-date" value="{{$data->planned_sd}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Plan End Date</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="mm/dd/yyyy" id="planned_ed" name="planned_ed" class="form-control datepicker-date" value="{{$data->planned_ed}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Actual Start Date</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="mm/dd/yyyy" id="actual_sd" name="actual_sd" class="form-control datepicker-date" value="{{$data->actual_sd}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Actual End Date</label>
                                    <div class="input-group">
                                        <input type="text" id="actual_ed" placeholder="mm/dd/yyyy" name="actual_ed" class="form-control datepicker-date" value="{{$data->actual_ed}}">
                                </div>
                            </div>
                        </div>
                            <div class="col-md-2">
                                <div class="form-check">
                                     <label for="independent">Independent<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="independent" id="independent" class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="1" <?php if($data->independent=='1'){ echo "selected"; } ?>>Yes</option>
                                    <option value="0" <?php if($data->independent=='0'){ echo "selected"; } ?>>No</option>
                                </select>
                                <div class="invalid-feedback" id="independent_error_msg"></div>
                                </div> 
                            </div>  -->
                             <!-- <div class="col-md-2"><br>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="geo_related" id="geo_related" value="1"<?php if($data->geo_related=='1'){ echo "checked"; } ?>>
                                        <span class="form-check-sign">Geo Related</span>
                                    </label>
                                </div>
                            </div>    -->
                        </div><!--end of row-->  
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" id="description" name="description">{{$data->description}}</textarea>
                                 <div class="invalid-feedback" id="scheme_name_error_msg"></div>
                                   <!--  <textarea class="form-control" id="comment" rows="5"></textarea> -->
                                </div> 
                            </div>
                        </div> 

                     <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Attachment</label>
                                    <div class="input-icon">
                                        <input type="file" class="form-control" name="attachment[]" id="attachment" placeholder="Download Document" multiple="multiple">
                                        <span class="input-icon-addon">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </span>
                                    </div>
                                     <?php
                                        if($data->attachment)
                                        {
                                            
                                            $attachment_array = explode(":",$data->attachment);
                                            for($i=0;$i<count($attachment_array);$i++)
                                            {
                                                
                                                echo "<table><tr><td>$attachment_array[$i]</td><td>&nbsp;&nbsp;<i class='fas fa-window-close' style='color:red;'></i></td></tr></table>";

                                            }
                                            
                                        }
                                    ?>
                                </div>
                            </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Scheme Logo</label>
                                    <div class="input-icon">
                                        <input type="file" class="form-control" name="scheme_logo" id="scheme_logo" placeholder="Scheme Logo" accept="image/*">
                                        <span class="input-icon-addon">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </span>
                                    </div>
                                     <?php
                                    if($data->scheme_logo)
                                    {
                                        echo "<table><tr>$data->scheme_logo</tr></table>";
                                       
                                    }
                                    ?>
                                </div>
                                
                            </div>

                             <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Map Marker Icon</label>
                                    <div class="input-icon">
                                        <input type="file" class="form-control" name="scheme_map_marker" id="scheme_map_marker" placeholder="Map Marker Icon" accept="image/*">
                                        <span class="input-icon-addon">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </span>
                                    </div>
                                    <?php
                                if($data->scheme_map_marker)
                                {
                                    echo "<table><tr>$data->scheme_map_marker</tr></table>";
                                }
                                ?>
                                </div>

                            </div>
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Created Date<font style="color:red;">*</font></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker-create-date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Submited By<font style="color:red;">*</font></label>
                                    <input type="" class="form-control" id="" placeholder="">
                                </div>
                            </div> -->
                        </div> 
                       
                      
                        <hr class="new2">
                        
                <div class="col-md-11">
                    <button class="btn"  style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-sort-amount-up"></i> &nbsp;Indicator</button>
                        <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                            <table id="basic-datatables" class=" table order-list" style="margin-top: 10px;">
                                <thead style="background: #cedcff">
                                   
                                    <tr>
                                       <!--  <th>Add</th> -->
                                        <th>Name</th>
                                        <th>Unit</th>
                                        <th>Performance</th> 
                                        <th>Remove</th>
                                    </tr>

                                </thead>
                                
                                <tbody>

                                    
                                    <?php if(count($indicator_datas)!=0){ ?>
                                        @foreach($indicator_datas as $indicator_data)

                                          <tr> 
                                                <!-- <td><span class="btn btn-icon btn-primary btn-round btn-xs" id="addrow" value="Add Row"><i class="fa fa-plus"></i> </span></td> -->

                                                <td class="col-sm-3" style="width: 40%"><input type="text"  name="indicator_name[]" id="indicator_name" class="form-control" value="{{$indicator_data->indicator_name}}" placeholder="name"><div class="invalid-feedback" id="indicator_name_error_msg"></div></td>
                                                <td class="col-sm-3" style="width: 20%">
                                                   
                                                     <select name="uom[]" id="uom" class="form-control">
                                                        <option value="">---Select---</option>
                                                        @foreach( $uoms as $uom )
                                                         <option value="{{ $uom->uom_id }}"  <?php if($indicator_data->uom==$uom->uom_id){ echo "selected"; } ?>>{{ $uom->uom_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="uom_error_msg"></div>
                                                </td>
                                                <td class="col-sm-3" style="width: 20%"><input class="form-check-input" type="checkbox" id="performance"  value="1" name="performance[]"  style="margin-left: 0em;"<?php if($indicator_data->performance=='1'){echo "checked";}?>></td>
                                                <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>
                                            </tr>
                                        @endforeach
                                    <?php
                                    }
                                    else{   //For adding record
                                    ?>
                                        <tr> 
                                            <!-- <td><span class="btn btn-icon btn-primary btn-round btn-xs" id="addrow" value="Add Row"><i class="fa fa-plus"></i> </span></td> -->

                                            <td class="col-sm-3" style="width: 40%"><input type="text"  name="indicator_name[]" id="indicator_name" class="form-control" value="{{$data->indicator_name}}" placeholder="name"><div class="invalid-feedback" id="indicator_name_error_msg"></div></td>
                                            <td class="col-sm-3" style="width: 20%">
                                               
                                                 <select name="uom[]" id="uom" class="form-control">
                                                    <option value="">---Select---</option>
                                                    @foreach( $uoms as $uom )
                                                     <option value="{{ $uom->uom_id }}"<?php if($data->unit==$uom->uom_id){ echo "selected"; } ?>>{{ $uom->uom_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="uom_error_msg"></div>
                                            </td>
                                            <td class="col-sm-3" style="width: 20%"><input class="form-check-input" type="checkbox" id="performance"  value="1" name="performance[]"  style="margin-left: 0em;"<?php if($data->performance=='1'){echo "checked";}?>></td>
                                           <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="4"><button type="button" class="btn btn-icon btn-primary btn-round btn-xs" value="Add Row" id="addrow" style="float: right;"><i class="fa fa-plus"></i></button> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> <hr class="new2">
                    <div class="card-action"> 
                        <input type="text" name="hidden_input_attachment" id="hidden_input_attachment" value="{{$data->attachment}}" hidden> 
                        <input type="text" name="hidden_input_scheme_logo" id="hidden_input_scheme_logo" value="{{$data->scheme_logo}}" hidden>
                        <input type="text" name="hidden_input_map_marker" id="hidden_input_map_marker" value="{{$data->scheme_map_marker}}" hidden>  
                        <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                        <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                        <button class="btn btn-secondary" onclick="return submitForm()">Submit</button>
                        <a href="{{url('scheme-structure')}}" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
                    <!-----------------------------------------end of User Form------------------------------------------>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Date Picker
        jQuery('#datepicker-end-date').datepicker();
            jQuery('#datepicker-end-date-inline').datepicker();
            jQuery('#datepicker-end-date-multiple').datepicker({
                numberOfMonths: 3,
                showButtonPanel: true
            });
    </script>

    <script>
        // Date Picker
        jQuery('#datepicker-create-date').datepicker();
            jQuery('#datepicker-create-date-inline').datepicker();
            jQuery('#datepicker-create-date-multiple').datepicker({
                numberOfMonths: 3,
                showButtonPanel: true
            });
    </script>

    <script>
        // Date Picker
        jQuery('#datepicker-start-date').datepicker();
            jQuery('#datepicker-start-date-inline').datepicker();
            jQuery('#datepicker-start-date-multiple').datepicker({
                numberOfMonths: 3,
                showButtonPanel: true
            });
    </script>
    <script>
        $(document).ready(function () {
        var i = 0;        
    $('#addrow').click(function(){
        i++;                
            // alert(i);
                    var data=`<tr>
                    <td><input type="text" class="form-control" placeholder="name" id="indicator_name" value="{{$data->indicator_name}}" name="indicator_name[]' + i + '"/><div class="invalid-feedback" id="indicator_name_error_msg"></div></td>
                    <td>
                    <select class="form-control" id="uom"  name="uom[]' + i + '">
                    <option value="">---Select---</option>
                    @foreach($uoms as $uom )
                    <option value="{{ $uom->uom_id }}" <?php if($data->unit==$uom->uom_id){ echo "selected"; } ?>>{{ $uom->uom_name }}</option>
                    @endforeach
                    </select>
                    <div class="invalid-feedback" id="uom_error_msg"></div>
                    </td>
                    <td><input class="form-check-input" type="checkbox" id="performance"  name="performance[]' + i + '"  value="1"  style="margin-left: 0em;"<?php if($data->performance=='1'){echo "checked";}?>/></td>
                    <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td></tr>`;
         $('table.order-list').append(data);
    }); 

            $("table.order-list").on("click", ".ibtnDel", function (event) {
                $(this).closest("tr").remove();       
                i -= 1
            });


        });

        function calculateGrandTotal() {
            var grandTotal = 0;
            $("table.order-list").find('input[name^="price"]').each(function () {
                grandTotal += +$(this).val();
            });
            $("#grandtotal").text(grandTotal.toFixed(2));
        }


    </script>


   <script>
    /* validation starts */
    // error variables as true = error occured
    var scheme_name_error = true;
    var scheme_short_name_error = true;
    var isactive_error = true;
    var department_name_error = true;
    var scheme_type_error = true;
    var independent = true;
    var indicator_name_error = true;
    var uom_error = true;
    var performance_error = true;

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
            $("#indicator_name").change(function(){
            indicator_name_validate();
        });
         $("#uom").change(function(){
            uom_validate();
        });
          $("#performance").change(function(){
            performance_validate();
        });
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
            $("#is_active_error_msg").html("Status should not be blank");
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
            $("#independent_error_msg").html("Independent should not be blank");
        }
        else{
            independent_error_msg=false;
            $("#independent").removeClass('is-invalid');
        } 
    }

    //indicator name validation
    function indicator_name_validate(){
        var indicator_name_val = $("#indicator_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9_]+$');
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

//uom validate
    function uom_validate(){
        var uom_val = $("#uom").val();
       if(uom_val=="")
         {
            uom_error=true;
            $("#uom").addClass('is-invalid');
            $("#uom_error_msg").html("Unit should not be blank");
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
        scheme_name_validate();
       scheme_short_name_validate();
       is_active_validate();
       department_name_validate();
       scheme_type_validate();
        independent_validate();
       indicator_name_validate();
       uom_validate();
       performance_validate();
        
        if(scheme_name_error || scheme_short_name_error || is_active_error || department_name_error || scheme_type_error || independent_error_msg||indicator_name_error || uom_error || performance_error){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }

</script>

@endsection