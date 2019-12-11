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
                        <h4 class="card-title">Department</h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button>
                            <a id="toggle1" class="btn btn-secondary department-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a id="toggle1" class="btn btn-secondary department-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br> -->
                    <div id="show-toggle1">
                        <form action="{{url('department/store')}}" method="POST" id="department-form">
                        @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dept_name">Department Name<span style="color:red;margin-left:5px;">*</span></label>
                                        <input type="text" name="dept_name" id="dept_name" class="form-control" value="">
                                        <div class="invalid-feedback" id="dept_name_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dept_icon">Icon Class</label>
                                        <input type="text" name="dept_icon" id="dept_icon" class="form-control" placeholder="e.g. heartbeat">
                                        <div class="invalid-feedback" id="dept_icon_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="is_active">Is Active<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="is_active" id="is_active" class="form-control">
                                            <option value="">-Select-</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <div class="invalid-feedback" id="is_active_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div style="height:30px;"></div>
                                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                                        <button type="reset" class="btn btn-secondary">Reset&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive table-hover table-sales">
                        <form action="{{url('department/store')}}" method="POST"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                        @csrf    
                            <table class="table table-datatable" id="printable-area">
                                <thead style="background: #d6dcff;color: #000;">
                                    <tr>
                                        <th>#</th>
                                        <th>Icon</th>
                                        <th>Department Name</th>
                                        <th>Organisation</th>
                                        <th>Status</th>  
                                        <th class="action-buttons">Action</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count=1; ?>
                                    @if(isset($datas))
                                        @foreach($datas as $data)
                                        <tr data-row-id="{{$data->dept_id}}" data-row-values="{{$data->dept_icon}},{{$data->dept_name}},{{$data->org_name}},{{$data->is_active}}">
                                            <td>{{$count}}</td>
                                            <td><i class="fa fa-{{$data->dept_icon}}" style="font-size:25px; color: #03A9F4;"></i></td>
                                            <td>{{$data->dept_name}}</td>
                                            <td>{{$data->org_name}}</td>
                                            <td><?php if($data->is_active=='1'){
                                                echo '<i class="fas fa-check text-success"></i> Active';
                                            }
                                            else{
                                                echo '<i class="fas fa-times text-danger"></i> Inactive';
                                            } ?></td>
                                            <td class="action-buttons">
                                                <a href="{{url('department/delete')}}/{{$data->dept_id}}" id="delete-button" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                                &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-secondary" onclick="openInlineForm('{{$data->dept_id}}')"><i class="fas fa-edit"></i></button>
                                                
                                               
                                            </td>
                                        </tr>
                                        <?php $count++; ?>
                                        @endforeach
                                    @endif
                                    @if($count==1)
                                        <tr>
                                            <td colspan="4"><center>No data to show</center></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    /*
    *
    for appending inline form
    *
    */


    to_edit_id = 0;
    edit_form_opened = false;
    /* to open forms */
    function openInlineForm(id){
        closeInlineForm(); // to close if already opened any row form

        var edit_values = $("tr[data-row-id='"+id+"']").data('row-values'); // getting datas
        edit_values = edit_values.split(','); // converting to array

        var form_append = `<tr data-edit-id="`+id+`">
            <td></td>
            <td>
                <input value="`+edit_values[0]+`" class="form-control" name="dept_icon" id="edit_dept_icon">
                <div class="invalid-feedback" id="edit_dept_icon_error_msg"></div>
            </td>
            <td>
                <input value="`+edit_values[1]+`" class="form-control" name="dept_name" id="edit_dept_name">
                <div class="invalid-feedback" id="edit_dept_name_error_msg"></div>
            </td>
            <td>`+edit_values[2]+`</td>
            <td>
                <select class="form-control" name="is_active" id="edit_is_active">
                    <option value="">-Select-</option>`;
                    
        form_append += `<option value="1" `;
                if(edit_values[3]==1){
                    form_append += `selected`;
                }
        form_append += `>Active</option>`;
        form_append += `<option value="0" `;
                if(edit_values[3]==0){
                    form_append += `selected`;
                }
        form_append += `>Inactive</option>`;

        form_append +=`</select>
                    <div class="invalid-feedback" id="edit_is_active_error_msg"></div>
            </td>
            <td>
                <input type="text" name="edit_id" value="`+id+`" hidden>
                <button type="submit" onclick="return submitFormInline()" class="btn btn-success btn-sm">Save&nbsp;<i class="fas fa-check"></i></button>
                &nbsp;&nbsp;<button type="button" class="btn btn-dark btn-sm" onclick="closeInlineForm()">Cancel&nbsp;<i class="fas fa-times"></i></button>
            </td>
        </tr>`;


        $("tr[data-row-id='"+id+"']").after(form_append);
        $("tr[data-row-id='"+id+"']").hide();
        to_edit_id = id;
        edit_form_opened = true;
    }
    /* to close forms */
    function closeInlineForm(){
        if(edit_form_opened){
            $("tr[data-edit-id='"+to_edit_id+"']").remove();
            $("tr[data-row-id='"+to_edit_id+"']").show();
            edit_form_opened = false;
            to_edit_id = 0;
        }
    }

    /*
    *
    edit inline form: validation start
    *
    */
    var edit_dept_name_error = true;
    var edit_dept_icon_error = true;
    var edit_is_active_error = true;

    $(document).ready(function(){
        $(document).on("change", "#edit_dept_name", function(){
            edit_dept_name_validate();
        });
        $(document).on("change", "#edit_dept_icon", function(){
            edit_dept_icon_validate();
        });
        $(document).on("change","#edit_is_active", function(){
            edit_is_active_validate();
        });
    });

    // edit department name vallidation
    function edit_dept_name_validate(){
        var edit_dept_name_val = $("#edit_dept_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(edit_dept_name_val==""){
            edit_dept_name_error=true;
            $("#edit_dept_name").addClass('is-invalid');
            $("#edit_dept_name_error_msg").html("Department name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(edit_dept_name_val)){
            edit_dept_name_error=true;
            $("#edit_dept_name").addClass('is-invalid');
            $("#edit_dept_name_error_msg").html("Please enter valid department name");
        }
        else{
            edit_dept_name_error=false;
            $("#edit_dept_name").removeClass('is-invalid');
        }
    }

    // edit dept_icon validation
    function edit_dept_icon_validate(){
        var edit_dept_icon_val = $("#edit_dept_icon").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z-]+$');
        if(edit_dept_icon_val!=""){
            if(!regAlphaNumericSpace.test(edit_dept_icon_val)){
                edit_dept_icon_error=true;
                $("#edit_dept_icon").addClass('is-invalid');
                $("#edit_dept_icon_error_msg").html("Please enter valid icon class");
            }
            else{
                edit_dept_icon_error=false;
                $("#edit_dept_icon").removeClass('is-invalid');
            }
        }
        else{
            edit_dept_icon_error=false;
            $("#edit_dept_icon").removeClass('is-invalid');
        }
    }

    // edit is_active validation
    function edit_is_active_validate(){
        var edit_is_active_val = $("#edit_is_active").val();
        if(edit_is_active_val==""){
            edit_is_active_error=true;
            $("#edit_is_active").addClass('is-invalid');
            $("#edit_is_active_error_msg").html("Please select department's status");
        }
        else{
            edit_is_active_error=false;
            $("#edit_is_active").removeClass('is-invalid');
        } 
    }
    
    // final edit submission
    function submitFormInline(){
        edit_dept_name_validate();
        edit_dept_icon_validate();
        edit_is_active_validate();
        
        if(edit_dept_name_error||edit_dept_icon_error||edit_is_active_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>


<script>
    /* validation starts */
    // error variables as true = error occured
    var dept_name_error = true;
    var dept_icon_error = true;
    var is_active_error = true;

    $(document).ready(function(){
        $("#dept_name").change(function(){
            dept_name_validate();
        });
        $("#dept_icon").change(function(){
            dept_icon_validate();
        });
        $("#is_active").change(function(){
            is_active_validate();
        });


        // reset/initiate form
        $(".department-add-button").click(function(){
            initiateForm();
        });
    });

    // intialiate form: reset everything related to the form
    function initiateForm(){
        document.getElementById('department-form').reset();
        $("#dept_name").removeClass('is-invalid');
        $("#is_active").removeClass('is-invalid');
    }

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

    // dept_icon validation
    function dept_icon_validate(){
        var dept_icon_val = $("#dept_icon").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z-]+$');
        if(dept_icon_val!=""){
            if(!regAlphaNumericSpace.test(dept_icon_val)){
                dept_icon_error=true;
                $("#dept_icon").addClass('is-invalid');
                $("#dept_icon_error_msg").html("Please enter valid icon class");
            }
            else{
                dept_icon_error=false;
                $("#dept_icon").removeClass('is-invalid');
            }
        }
        else{
            dept_icon_error=false;
            $("#dept_icon").removeClass('is-invalid');
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
        dept_icon_validate();
        is_active_validate();
        
        if(dept_name_error||dept_icon_error||is_active_error){ return false; } // error occured
        else{ $(".custom-loader").show();  return true; } // proceed to submit form data
    }
</script>
</script>
@endsection
<!-- email -->
<div id="create-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">Send Email</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('send_mail')}}" method="post" id="FormValidation" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="card-body p-t-30" style="padding: 11px;">
                            <div class="form-group">
                                <input type="hidden" name="department" value="department">
                                <input type="hidden" name="data" value="{{$datas}}">
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div> 
                            <div class="form-group">  
                                <input type="text" name="to" class="form-control" placeholder="To" required="">
                            </div>
                            <div class="form-group">                           
                                <input type="text" name="cc" class="form-control" placeholder="CC" required="">
                            </div>
                           
                            <div class="form-group">
                                <label for="subject" class="control-label">Subject <font color="red">*</font></label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject"  required=""  aria-required="true">
                            </div>
                            <!-- <div class="form-group">
                                <label for="field-2" class="control-label">Message <font color="red">*</font></label>
                                <textarea class="wysihtml5 form-control article-ckeditor" required id="article-ckeditor"  placeholder="Message body" style="height: 100px" name="message" ></textarea>
                            </div> -->
                           
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->