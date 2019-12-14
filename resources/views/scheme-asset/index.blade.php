@extends('layout.layout')

@section('title', 'scheme-asset')

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
                        <h4 class="card-title">Scheme-Asset</h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button>
                            <a id="toggle1" class="btn btn-secondary asset-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div id="show-toggle1">
                        <form action="{{url('scheme-asset/store')}}" method="POST" id="asset-form">
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="scheme_asset">Scheme-Asset<span style="color:red;margin-left:5px;">*</span></label>
                                        <input type="text" name="scheme_asset" id="scheme_asset" class="form-control" autocomplete="off">
                                        <div class="invalid-feedback" id="scheme_asset_error_msg"></div>
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
                        <form action="{{url('scheme-asset/store')}}" method="POST"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                        @csrf
                            <table class="table table-datatable" id="printable-area">
                                <thead style="background: #d6dcff;color: #000;">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th class="action-buttons">Actions</th>
                                    </tr>
                                </thead>
                                <?php $count=1; ?>
                                @if(isset($datas))
                                    @foreach($datas as $data)
                                        <tr data-row-id="{{$data->scheme_assets_id}}" data-row-values="{{$data->scheme_assets_name}}">
                                            <td width="40px;">{{$count++}}</td>
                                            <td>{{$data->scheme_assets_name}}</td>
                                            <!-- <td>{{$data->scheme_assets_id}}</td> -->
                                            <td class="action-buttons">
                                                <a href="{{url('scheme-asset/delete')}}/{{$data->scheme_assets_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>
                                                &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-secondary" onclick="openInlineForm('{{$data->scheme_assets_id}}')"><i class="fas fa-edit"></i></button>
                                            </td>
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
   </div>

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

<script>
    /* validation starts */
   // error variables as true = error occured
   var scheme_asset_error = true;
   
    $(document).ready(function(){
       $("#scheme_asset").keyup(function(){
           scheme_asset_validate();
       });
       $(".asset-add-button").click(function(){
            initiateForm();

       });
           
   });
   function scheme_asset_validate(){
       var scheme_asset_value = $("#scheme_asset").val();
       var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
       if(scheme_asset_value==""){
           scheme_asset_error=true;
           $("#scheme_asset").addClass('is-invalid');
           $("#scheme_asset_error_msg").html("Should not be blank");
       }
       else if(!regAlphaNumericSpace.test(scheme_asset_value)){
           scheme_asset_error=true;
           $("#scheme_asset").addClass('is-invalid');
           $("#scheme_asset_error_msg").html("Please Validate");
       }
       else{
           scheme_asset_error=false;
           $("#scheme_asset").removeClass('is-invalid');
       }
   }
    
   function initiateForm(){
        document.getElementById('asset-form').reset();
        $("#scheme_asset").removeClass('is-invalid');
    }
   
   function submitForm(){
       scheme_asset_validate();
     
       if(scheme_asset_error)
       { 
           return false;
       } // error occured
       else
       { 
           $(".custom-loader").show(); 
           return true;
       } // proceed to submit form data
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
                <input type="text" name="scheme_asset" id="edit_scheme_asset" class="form-control" value="`+edit_values[0]+`" autocomplete="off">
                <div class="invalid-feedback" id="edit_scheme_asset_error_msg"></div>
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
    var edit_scheme_asset_error = true;
    
    $(document).ready(function(){
        $(document).on("change", "#edit_scheme_asset", function(){
            edit_scheme_asset_validate();
        });
    });
    
     function edit_scheme_asset_validate(){
        var edit_uom_name_val = $("#edit_scheme_asset").val();
        var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
        if(edit_uom_name_val==""){
            edit_scheme_asset_error=true;
            $("#edit_scheme_asset").addClass('is-invalid');
            $("#edit_scheme_asset_error_msg").html("Should not be blank");
        }
        else if(!regAlphaNumericSpace.test(edit_uom_name_val)){
            edit_scheme_asset_error=true;
            $("#edit_scheme_asset").addClass('is-invalid');
            $("#edit_scheme_asset_error_msg").html("Invalid Correct it!!!!!");
        }
        else{
            edit_scheme_asset_error=false;
            $("#edit_scheme_asset").removeClass('is-invalid');
        }
    }
    
    function submitFormInline(){
        edit_scheme_asset_validate();
      
        if(edit_scheme_asset_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>
@endsection