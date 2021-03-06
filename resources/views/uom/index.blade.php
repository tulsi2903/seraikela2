@extends('layout.layout')

@section('title', 'UoM')

@section('page-style')
    <style>
        .table tbody tr td{
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }
        #printable-info-details {
        visibility: hidden;
        height: 0px;
        /* position: fixed;
        left: 0;
        top: 20px;
        width: 100vw !important; */
    }
    @media print {
        #printable-area {
            margin-top: 250px !important;
            font-size: small;
            width: 100% !important;

        }
        .no-print,
        .no-print * {
            display: none !important;
        }
        #printable-info-details {
            visibility: visible;
            position: fixed;
            margin-left: 100px !important;
            font-size:medium;
        }
        #print-button,
        #print-button * {
            visibility: hidden;
        }
        .card-title-print-1 {
            visibility: visible !important;
            position: fixed;
            color: #0a0a0a;
            font-size: 30px;
           
            left: 0;
            top: 30px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .card-title-print-2 {
            visibility: visible !important;
            position: fixed;
            color: #0a0a0a;
            font-size: 30px;
            
            left: 0;
            top: 100px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .card-title-print-3 {
            visibility: visible !important;
            position: fixed;
            color: #0a0a0a;
            font-size: 30px;
            
            left: 0;
            top: 140px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .action-buttons {
            display: none;
        }
    }
    </style>
@endsection

@section('page-content')

<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>

    <div class="card">
        <div class="col-md-12">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">{{$phrase->uom}}</h4>
                        <div class="card-tools">
                            <a href="#" data-toggle="tooltip" title="{{$phrase->send_email}}"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="{{$phrase->print}}"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                            <a href="{{url('uom/pdf/pdfURL')}}" target="_BLANK" data-toggle="tooltip" title="{{$phrase->export_pdf}}"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                            <a href="{{url('uom/export/excelURL')}}" data-toggle="tooltip" title="{{$phrase->export_excel}}"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a>
                            @if($desig_permissions["mod4"]["add"])
                                <a id="toggle1" class="btn btn-secondary uom-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}</a>
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a id="toggle1" class="btn btn-secondary uom-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div> -->
                    <div id="show-toggle1">
                        <form action="{{url('uom/store')}}" method="POST" id="uom-form">
                        @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="uom_name">{{$phrase->uom}}<span style="color:red;margin-left:5px;">*</span></label>
                                        <input type="text" name="uom_name" id="uom_name" class="form-control" autocomplete="off">
                                        <div class="invalid-feedback" id="uom_name_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="uom_type">{{$phrase->uom_type}}<font style="color:red;">*</font></label>                                     
                                            <select name="uom_type_id" id="uom_type" class="form-control form-control">
                                                <option value="">--Select--</option>
                                                @foreach($uom_type as $uom_type_datas)
                                                <option value="{{$uom_type_datas->uom_type_id}}">{{$uom_type_datas->uom_type_name}}</option>
                                                @endforeach
                                                                             
                                            </select>
                                        <div class="invalid-feedback" id="uom_type_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="conversion_unit">Conversion Unit<font style="color:red;">*</font></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="add-conversion-unit-prepend"></span>
                                            </div>
                                            <input type="text" name="conversion_unit" id="conversion_unit" class="form-control" maxlength="10" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="add-conversion-unit-append"></span>
                                            </div>
                                            <div class="invalid-feedback" id="conversion_unit_error_msg"></div>
                                        </div>
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
                        <div id="printable-info-details">
                            <p class="card-title-print-1">Title: Uom Details </p>
                            <p class="card-title-print-2">Date & Time:
                                <?php date_default_timezone_set('Asia/Kolkata'); $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                    <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                        </div>
                        <form action="{{url('uom/store')}}" method="POST"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                        @csrf
                            <table class="table table-datatable" id="printable-area">
                                <thead style="background: #d6dcff;color: #000;">
                                    <tr>
                                        <th>#</th>
                                        <th>{{$phrase->uom}}</th>
                                        <th>{{$phrase->uom_type}}</th>
                                        <th>Conversion Unit</th>
                                        @if($desig_permissions["mod4"]["del"] || $desig_permissions["mod4"]["edit"])
                                        <th class="action-buttons">{{$phrase->action}}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <?php $count=1; ?>
                                @if(isset($datas))
                                    @foreach($datas as $data)
                                        <tr data-row-id="{{$data->uom_id}}" data-row-values="{{$data->uom_name}},{{$data->uom_type_id}},{{$data->conversion_unit}}">
                                            <td width="40px;">{{$count++}}</td>
                                            <td>{{$data->uom_name}}</td>                                          
                                            <td>{{$data->uom_type_name}}</td>                                       
                                            <td>
                                                <?php
                                                    echo "1 ".$data->uom_name." = <b>".$data->conversion_unit."</b>";
                                                    if($data->uom_type_id==1){
                                                        echo " meter";
                                                    }
                                                    else if($data->uom_type_id==2){
                                                        echo " litre";
                                                    }
                                                ?>
                                            </td>                                       
                                            @if($desig_permissions["mod4"]["del"] || $desig_permissions["mod4"]["edit"])
                                                <td class="action-buttons">
                                                    @if($desig_permissions["mod4"]["edit"])&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-secondary" onclick="openInlineForm('{{$data->uom_id}}')" data-toggle="tooltip" title="{{$phrase->edit}}"><i class="fas fa-edit"></i></button>@endif
                                                    @if($desig_permissions["mod4"]["del"])<a href="{{url('uom/delete')}}/{{$data->uom_id}}" class="btn btn-danger btn-sm delete-button" data-toggle="tooltip" title="{{$phrase->delete}}"><i class="fas fa-trash-alt"></i></a>@endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                @if($count==1)
                                    <tr>
                                        <td colspan="5"><center>No data to shown</center></td>
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
    uom_type_html = $("#uom_type").html();
    /* to open forms */
    function openInlineForm(id){
        closeInlineForm(); // to close if already opened any row form

        var edit_values = $("tr[data-row-id='"+id+"']").data('row-values'); // getting datas
        edit_values = String(edit_values).split(','); // converting to array

        var form_append = `<tr data-edit-id="`+id+`">
            <td></td>
            <td>
                <input type="text" name="uom_name" id="edit_uom_name" class="form-control" value="`+edit_values[0]+`" autocomplete="off">
                <div class="invalid-feedback" id="edit_uom_name_error_msg"></div>
            </td>
           
            <td>
                    <select class="form-control" name="uom_type_id" id="edit_uom_type">`;

                    form_append += uom_type_html;
                    form_append +=`</select>
                                <div class="invalid-feedback" id="edit_uom_type_error_msg"></div>
            </td>

            
            <td>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="edit-conversion-unit-prepend"></span>
                    </div>
                    <input type="text" name="conversion_unit" id="edit_conversion_unit" class="form-control" value="`+edit_values[2]+`" maxlength="10" autocomplete="off">
                    <div class="input-group-append">
                        <span class="input-group-text" id="edit-conversion-unit-append"></span>
                    </div>
                    <div class="invalid-feedback" id="edit_conversion_unit_error_msg"></div>
                </div>
            </td>

            <td>
                <input type="text" name="edit_id" value="`+id+`" hidden>
                <button type="submit" onclick="return submitFormInline()" class="btn btn-success btn-sm">{{$phrase->submit}}&nbsp;<i class="fas fa-check"></i></button>
                &nbsp;&nbsp;<button type="button" class="btn btn-dark btn-sm" onclick="closeInlineForm()">{{$phrase->cancel}}&nbsp;<i class="fas fa-times"></i></button>
            </td>
        </tr>`;

        $("tr[data-row-id='"+id+"']").after(form_append);
        $("tr[data-row-id='"+id+"']").hide();
        to_edit_id = id;
        edit_form_opened = true;

        $(".custom-loader").show();
        setTimeout(function(){ 
            $("#edit_uom_type").val(edit_values[1]);
            edit_change_per_unit_text();
            $(".custom-loader").hide();
        }, 1000);
    }
                // <td>
                //             <input type="text" name="conversion_unit" id="edit_conversion_unit" class="form-control" value="`+edit_values[2]+`" maxlength="10" autocomplete="off">
                //             <div class="invalid-feedback" id="edit_conversion_unit_error_msg"></div>
                //         </td>
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
    var edit_uom_name_error = true;
    var edit_uom_type_error = true;
    var edit_conversion_unit_error = true;
    
    $(document).ready(function(){
        $(document).on("change", "#edit_uom_name", function(){
            edit_change_per_unit_text();
            edit_uom_name_validate();
        });
        $(document).on("change", "#edit_uom_type", function(){
            edit_change_per_unit_text();
            edit_uom_type_validate();
        });
        // for conversion_unit
        $(document).on("keyup", "#edit_conversion_unit", function(){
            $(this).val($(this).val().replace(/[^0-9.]+/g, ""));
        });
        $(document).on("change", "#edit_conversion_unit", function(){
            edit_conversion_unit_validate();
        });
    });
    // to chnage unit conversion text (append and prepend for add)
    function edit_change_per_unit_text(){
        var tmp_uom_name = $("#edit_uom_name").val();
        var tmp_uom_type = $("#edit_uom_type").val();
        $("#edit-conversion-unit-prepend").html("1 "+tmp_uom_name+" =");
        if(tmp_uom_type==1){
            $("#edit-conversion-unit-append").html("meter");
        }
        else if(tmp_uom_type==2){
            $("#edit-conversion-unit-append").html("litre");
        }
    }
    
    function edit_uom_name_validate(){
        var edit_uom_name_val = $("#edit_uom_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(edit_uom_name_val==""){
            edit_uom_name_error=true;
            $("#edit_uom_name").addClass('is-invalid');
            $("#edit_uom_name_error_msg").html("UoM should not be blank");
        }
        else if(!regAlphaNumericSpace.test(edit_uom_name_val)){
            edit_uom_name_error=true;
            $("#edit_uom_name").addClass('is-invalid');
            $("#edit_uom_name_error_msg").html("Please enter valid UoM");
        }
        else{
            edit_uom_name_error=false;
            $("#edit_uom_name").removeClass('is-invalid');
        }
    }
    function edit_uom_type_validate(){
        var edit_uom_type_val = $("#edit_uom_type").val();
        if(edit_uom_type_val==""){
            edit_uom_type_error=true;
            $("#edit_uom_type").addClass('is-invalid');
            $("#edit_uom_type_error_msg").html("UoM Type Should not be blank");
        }
        else {
            edit_uom_type_error = false;
                $("#edit_uom_type").removeClass('is-invalid');
            }
    }
    function edit_conversion_unit_validate(){
        var edit_conversion_unit_val = $("#edit_conversion_unit").val();
        if(edit_conversion_unit_val==""){
            edit_conversion_unit_error=true;
            $("#edit_conversion_unit").addClass('is-invalid');
            $("#edit_conversion_unit_error_msg").html("Please enter convertion unit");
        }
        else if(edit_conversion_unit_val.split('.').length>=3){
            edit_conversion_unit_error=true;
            $("#edit_conversion_unit").addClass('is-invalid');
            $("#edit_conversion_unit_error_msg").html("Please enter valid conversion unit");
        }
        else{
            edit_conversion_unit_error=false;
            $("#edit_conversion_unit").removeClass('is-invalid');
        }
    }
    
    function submitFormInline(){
        edit_uom_name_validate();
        edit_uom_type_validate();
        edit_conversion_unit_validate();
      
        if(edit_uom_name_error || edit_uom_type_error || edit_conversion_unit_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>


<script>
     /* validation starts */
    // error variables as true = error occured
    var uom_name_error = true;
    var uom_type_error = true;
    var conversion_unit_error = true;
    
    $(document).ready(function(){
        $("#uom_name").change(function(){
            change_per_unit_text();
            uom_name_validate();
        });
        $("#uom_type").change(function(){
            change_per_unit_text();
            uom_type_validate();
        });
        // for conversion_unit
        $(document).on("keyup", "#conversion_unit", function(){
            $(this).val($(this).val().replace(/[^0-9.]+/g, ""));
        });
        $(document).on("change", "#conversion_unit", function(){
            conversion_unit_validate();
        });
       
        // reset/initiate form
        $(".uom-add-button").click(function(){
            initiateForm();
        });
    });
    // to chnage unit conversion text (append and prepend for add)
    function change_per_unit_text(){
        var tmp_uom_name = $("#uom_name").val();
        var tmp_uom_type = $("#uom_type").val();
        $("#add-conversion-unit-prepend").html("1 "+tmp_uom_name+" =");
        if(tmp_uom_type==1){
            $("#add-conversion-unit-append").html("meter");
        }
        else if(tmp_uom_type==2){
            $("#add-conversion-unit-append").html("litre");
        }
    }

    // intitiate everything reletaed to "add form"
    function initiateForm(){
        document.getElementById('uom-form').reset();
        $("#uom_name").removeClass('is-invalid');
        $("#uom_type").removeClass('is-invalid');
        $("#add-conversion-unit-append").html("");
        $("#add-conversion-unit-prepend").html("");

    }
    
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
    function uom_type_validate(){
        var uom_type_val = $("#uom_type").val();
        if(uom_type_val==""){
            uom_type_error=true;
            $("#uom_type").addClass('is-invalid');
            $("#uom_type_error_msg").html("UoM Type Should not be blank");
        }
        else {
            uom_type_error = false;
                $("#uom_type").removeClass('is-invalid');
            }
    }
    function conversion_unit_validate(){
        var conversion_unit_val = $("#conversion_unit").val();
        if(conversion_unit_val==""){
            conversion_unit_error=true;
            $("#conversion_unit").addClass('is-invalid');
            $("#conversion_unit_error_msg").html("Please enter convertion unit");
        }
        else if(conversion_unit_val.split('.').length>=3){
            conversion_unit_error=true;
            $("#conversion_unit").addClass('is-invalid');
            $("#conversion_unit_error_msg").html("Please enter valid conversion unit");
        }
        else{
            conversion_unit_error=false;
            $("#conversion_unit").removeClass('is-invalid');
        }
    }
    
    function submitForm(){
        uom_name_validate();
        uom_type_validate();
        conversion_unit_validate();

      
        if(uom_name_error || uom_type_error || conversion_unit_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>
@endsection