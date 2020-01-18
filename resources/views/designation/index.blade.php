@extends('layout.layout')

@section('title', 'Define Designation')

@section('page-style')
    <style>
    .logo-header .logo {
        color: #575962;
        opacity: 1;
        position: relative;
        height: 100%;
        margin-top: 1em;
    }
    .btn-toggle {
        color: #fff!important;
        margin-top: 1em;
    }
    #printable-info-details {
        visibility: hidden;
        height: 0px;
        /* position: fixed;
        left: 0;
        top: 20px;
        width: 100vw !important; */
    }

    @media print{

            #printable-area{
                margin-top: 250px !important;
            }

            .no-print, .no-print *
            {
                display: none !important;
            }
            #printable-info-details{
                visibility: visible;
                position: fixed;
            }
            #print-button, #print-button *{
                visibility: hidden;
            }
            .card-title-print-1{
                visibility: visible !important;
                position: fixed;
                color: #147785;
                font-size: 30px;;
                left: 0;
                top: 50px;
                width: 100vw !important;
                height: 100vw !important;
            }
            .card-title-print-2{
                visibility: visible !important;
                position: fixed;
                 color: #147785;
                 font-size: 30px;;
                left: 0;
                top: 100px;
                width: 100vw !important;
                height: 100vw !important;
            }
            .card-title-print-3{
                visibility: visible !important;
                position: fixed;
                 color: #147785;
                 font-size: 30px;;
                left: 0;
                top: 140px;
                width: 100vw !important;
                height: 100vw !important;
            }
            .action-buttons{
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
                        <h4 class="card-title">{{$phrase->define_designation}}</h4>
                        <div class="card-tools">
                            <!-- <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button></a> -->
                            <!-- <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a> -->

                            <button type="button" class="btn btn-icon btn-round btn-success"  onclick="openmodel();" ><i class="fa fa-envelope" aria-hidden="true"></i></button>

                            <button type="button" class="btn btn-icon btn-round btn-default" onclick="printViewone();"><i class="fa fa-print" aria-hidden="true"></i></button>

                            <button type="button" onclick="exportSubmit('print_pdf');" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button>
                            <button type="button" onclick="exportSubmit('excel_sheet');" class="btn btn-icon btn-round btn-success"><i class="fas fa-file-excel"></i></button>

                            
                            <!-- <a href="{{url('designation/pdf/pdfURL')}}" target="_BLANK" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a> -->
                            <!-- <a href="{{url('designation/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a> -->
                            @if($desig_permissions["mod5"]["add"])
                                <a id="toggle1" class="btn btn-secondary designation-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}</a>
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
         <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a id="toggle1" class="btn btn-secondary designation-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div> -->
                    <div id="show-toggle1">
                        <form action="{{url('designation/store')}}" method="POST" id="designation-form">
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">{{$phrase->name}}<span style="color:red;margin-left:5px;">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" autocomplete="off">
                                        <div class="invalid-feedback" id="name_error_msg"></div>
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
                    <form action="{{url('designation/store')}}" method="POST"> 
                    @csrf

                        <div id="printable-info-details">
                            <p class="card-title-print-1">Title: Define Designation </p>
                            <p class="card-title-print-2">Date & Time: <?php $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                            <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                        </div>
                            <table class="table table-datatable" id="printable-area">
                                <thead style="background: #d6dcff;color: #000;">
                                    <tr>
                                        <th>#</th>
                                        <th>{{$phrase->name}}</th>
                                        @if($desig_permissions["mod5"]["del"] ||$desig_permissions["mod5"]["edit"] ) 
                                        <th class="action-buttons">{{$phrase->action}}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <?php $count=1; ?>
                                @if(isset($datas))
                                    @foreach($datas as $data)
                                        <tr data-row-id="{{$data->desig_id}}" data-row-values="{{$data->name}}">
                                            <td width="40px;">{{$count++}} 
                                                <input type="text" value="{{$data->desig_id}}" name="desig_id_to_export[]" hidden>
                                            </td>
                                            <td>{{$data->name}}</td>
                                            @if($desig_permissions["mod5"]["del"] ||$desig_permissions["mod5"]["edit"] ) 
                                            <td class="action-buttons">
                                                @if($desig_permissions["mod5"]["del"])<a href="{{url('designation/delete')}}/{{$data->desig_id}}" id="delete-button" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>@endif
                                                @if($desig_permissions["mod5"]["edit"]) &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-secondary" onclick="openInlineForm('{{$data->desig_id}}')"><i class="fas fa-edit"></i></button>@endif
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                @if($count==1)
                                    <tr>
                                        <td colspan="3"><center>No data to show</center></td>
                                    </tr>
                                @endif
                            </table>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- export starts -->
<form action="{{url('designation/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data" id="export-form"> 
    @csrf
        <input type="text" name="designation_id" hidden>
        <input type="text" name="print" hidden> <!-- hidden input for export (pdf/excel) -->
</form>
<!-- export ends -->
</div>



<script>
    function exportSubmit(type){
        $("input[name='print']").val(type);
        var values = $("input[name='desig_id_to_export[]']").map(function(){return $(this).val();}).get();
        $("input[name='designation_id']").val(values);
        document.getElementById('export-form').submit();
    }
</script>

<script>
    function printViewone()
    {
      window.print();
    }
</script>
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
                <input type="text" name="name" id="edit_name" class="form-control" value="`+edit_values[0]+`" autocomplete="off">
                <div class="invalid-feedback" id="edit_name_error_msg"></div>
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
    edit: validation
    */
    var edit_name_error = true;

    $(document).ready(function(){
        $(document).on("change", "#edit_name", function(){
            edit_name_validate();
        });
    });

    // name vallidation
    function edit_name_validate(){
        var edit_name_val = $("#edit_name").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(edit_name_val==""){
            edit_name_error=true;
            $("#edit_name").addClass('is-invalid');
            $("#edit_name_error_msg").html("Designation name should not be blank");
        }
        else if(!regAlphaNumericSpace.test(edit_name_val)){
            edit_name_error=true;
            $("#edit_name").addClass('is-invalid');
            $("#edit_name_error_msg").html("Please enter valid designation name");
        }
        else{
            edit_name_error=false;
            $("#edit_name").removeClass('is-invalid');
        }
    }

    // final submission
    function submitFormInline(){
        edit_name_validate();
        
        if(edit_name_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    } 
</script>


<script>
    /* validations */
    // error variables as true = error occured
    var name_error = true;

    $(document).ready(function(){
        $("#name").change(function(){
            name_validate();
        });

        // reset/initiate form
        $(".designation-add-button").click(function(){
            initiateForm();
        });
    });

    // intitiate everything related to "add form"
    function initiateForm(){
        document.getElementById('designation-form').reset();
        $("#name").removeClass('is-invalid');
    }

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

    // final submission
    function submitForm(){
        name_validate();
        
        if(name_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>


@endsection

<script>
    function openmodel()
    {
        var search_element=$( "input[type=search]" ).val();
        $('#create-email').modal('show');
        $('#dept_search').val(search_element);
        // alert(search_element);
    }
    
    </script>

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
                                <input type="hidden" name="designation" value="designation">
                                <input type="hidden" name="data" value="{{$datas}}">
                                <input type="hidden" name="search_query" id="dept_search" >
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div> 
                            <div class="form-group">  
                                <input type="text" name="to" class="form-control" placeholder="To" required="">
                            </div>
                            <div class="form-group">                           
                                <input type="text" name="cc" class="form-control" placeholder="CC" >
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