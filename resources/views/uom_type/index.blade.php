@extends('layout.layout')

@section('title', 'UoM Type')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')

<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>


    <div class="card">
        <div class="col-md-12">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">{{$phrase->uom_type}} </h4>
                        <div class="card-tools">
                            <!-- <a href="#" data-toggle="tooltip" title="{{$phrase->send_email}}"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="{{$phrase->print}}"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                            <a href="" target="_BLANK" data-toggle="tooltip" title="{{$phrase->export_pdf}}"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                            <a href="" data-toggle="tooltip" title="{{$phrase->export_excel}}"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a> -->
                            @if($desig_permissions["mod22"]["add"])
                                <a id="toggle1" class="btn btn-secondary uom-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div id="show-toggle1">
                        <form action="{{url('uom_type/store')}}" method="POST" id="uom-form">
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="uom_type_name">{{$phrase->uom_type}}<span style="color:red;margin-left:5px;">*</span></label>
                                        <input type="text" name="uom_type_name" id="uom_type_name" class="form-control" autocomplete="off">
                                        <div class="invalid-feedback" id="uom_type_name_error_msg"></div>
                                    </div>
                                </div>                             
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div style="height:30px;"></div>
                                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">{{$phrase->submit}}&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                                        <button type="reset" class="btn btn-secondary">{{$phrase->reset}}&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive table-hover table-sales">
                        <form action="{{url('uom_type/store')}}" method="POST"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                        @csrf
                            <table class="table table-datatable" id="printable-area">
                                <thead style="background: #d6dcff;color: #000;">
                                    <tr>
                                        <th>#</th>
                                        <th>{{$phrase->uom_type}}</th>
                                        @if($desig_permissions["mod22"]["edit"] || $desig_permissions["mod22"]["del"])                                       
                                        <th class="action-buttons">{{$phrase->action}}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <?php $count=1; ?>
                                @if(isset($datas))
                                    @foreach($datas as $data)
                                        <tr data-row-id="{{$data->uom_type_id}}" data-row-values="{{$data->uom_type_name}}">
                                            <td width="40px;">{{$count++}}</td>
                                            <td>{{$data->uom_type_name}}</td> 
                                            @if(desig_permissions["mod22"]["edit"] || $desig_permissions["mod22"]["del"])
                                            <td class="action-buttons">
                                            @if(desig_permissions["mod22"]["edit"])
                                                 &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-secondary" onclick="openInlineForm('{{$data->uom_type_id}}')" data-toggle="tooltip" title="{{$phrase->edit}}"><i class="fas fa-edit"></i></button>
                                                 @endif
                                                @if(desig_permissions["mod22"]["del"])
                                                <a href="{{url('uom_type/delete')}}/{{$data->uom_type_id}}" class="btn btn-danger btn-sm delete-button" data-toggle="tooltip" title="{{$phrase->delete}}"><i class="fas fa-trash-alt"></i></a>
                                                @endif
                                                
                                            </td>
                                            @endif                                       
                                        </tr>
                                    @endforeach
                                @endif
                                @if($count==1)
                                    <tr>
                                        <td colspan="4"><center>No data to shown</center></td>
                                    </tr>
                                @endif
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
 

   <!-- email -->
<div id="create-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">{{$phrase->send_email}}</h4>
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
                                <input type="hidden" name="uom" value="uom"> 
                                <input type="hidden" name="data" value="{{$datas}}">
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div> 
                            <div class="form-group">  
                                <input type="email" name="to" class="form-control" placeholder="To" required="">
                            </div>
                            <div class="form-group">                           
                                <input type="text" name="cc" class="form-control" placeholder="CC">
                            </div>
                           
                            <div class="form-group">
                                <label for="subject" class="control-label">{{$phrase->subject}} <font color="red">*</font></label>
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
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">{{$phrase->close}}</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">{{$phrase->send}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

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
        edit_values = String(edit_values).split(','); // converting to array

        var form_append = `<tr data-edit-id="`+id+`">
            <td></td>
            <td>
                <input type="text" name="uom_type_name" id="edit_uom_type_name" class="form-control" value="`+edit_values[0]+`" autocomplete="off">
                <div class="invalid-feedback" id="edit_uom_type_name_error_msg"></div>
            </td>
            <td>
                <input type="text" name="edit_id" value="`+id+`" hidden>
                <button type="submit" onclick="return submitFormInline()" class="btn btn-success btn-sm">{{$phrase->save}}&nbsp;<i class="fas fa-check"></i></button>
                &nbsp;&nbsp;<button type="button" class="btn btn-dark btn-sm" onclick="closeInlineForm()">{{$phrase->cancel}}&nbsp;<i class="fas fa-times"></i></button>
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
    var edit_uom_type_name_error = true;

    
    $(document).ready(function(){
        $(document).on("change", "#edit_uom_type_name", function(){
            edit_uom_type_name_validate();
        });

    });
    
    function edit_uom_type_name_validate(){
        var edit_uom_type_name_val = $("#edit_uom_type_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(edit_uom_type_name_val==""){
            edit_uom_type_name_error=true;
            $("#edit_uom_type_name").addClass('is-invalid');
            $("#edit_uom_type_name_error_msg").html("UoM Type Should not be blank");
        }
        else if(!regAlphaNumericSpace.test(edit_uom_type_name_val)){
            edit_uom_type_name_error=true;
            $("#edit_uom_type_name").addClass('is-invalid');
            $("#edit_uom_type_name_error_msg").html("Please enter valid UoM Type");
        }
        else{
            edit_uom_type_name_error=false;
            $("#edit_uom_type_name").removeClass('is-invalid');
        }
    }
    
    function submitFormInline(){
        edit_uom_type_name_validate();   
        if(edit_uom_type_name_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>


<script>
     /* validation starts */
    // error variables as true = error occured
    var uom_type_name_error = true;
    
    $(document).ready(function(){
        $("#uom_type_name").change(function(){
            uom_type_name_validate();
        });    
        // reset/initiate form
        $(".uom-add-button").click(function(){
            initiateForm();
        });
    });

    // intitiate everything reletaed to "add form"
    function initiateForm(){
        document.getElementById('uom-form').reset();
        $("#uom_type_name").removeClass('is-invalid');
    }
    
     function uom_type_name_validate(){
        var uom_type_name_val = $("#uom_type_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(uom_type_name_val==""){
            uom_type_name_error=true;
            $("#uom_type_name").addClass('is-invalid');
            $("#uom_type_name_error_msg").html("UoM Type Should not be blank");
        }
        else if(!regAlphaNumericSpace.test(uom_type_name_val)){
            uom_type_name_error=true;
            $("#uom_type_name").addClass('is-invalid');
            $("#uom_type_name_error_msg").html("Please enter valid UoM Type");
        }
        else{
            uom_type_name_error=false;
            $("#uom_type_name").removeClass('is-invalid');
        }
    }
    
    function submitForm(){
        uom_type_name_validate();

        if(uom_type_name_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>
@endsection