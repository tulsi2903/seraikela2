@extends('layout.layout')

@section('title', 'UoM')

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
                        <h4 class="card-title">UoM</h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-icon btn-round btn-warning"><i class="fa fa-envelope" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-icon btn-round btn-info" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a id="toggle1" class="btn btn-secondary uom-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div>
                    <div id="show-toggle1">
                        <form action="{{url('uom/store')}}" method="POST" id="uom-form">
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="uom_name">UoM Name<span style="color:red;margin-left:5px;">*</span></label>
                                        <input type="text" name="uom_name" id="uom_name" class="form-control" autocomplete="off">
                                        <div class="invalid-feedback" id="uom_name_error_msg"></div>
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
                        <form action="{{url('uom/store')}}" method="POST"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
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
                                        <tr data-row-id="{{$data->uom_id}}" data-row-values="{{$data->uom_name}}">
                                            <td width="40px;">{{$count++}}</td>
                                            <td>{{$data->uom_name}}</td>
                                            <td class="action-buttons">
                                                <a href="{{url('uom/delete')}}/{{$data->uom_id}}" id="delete-button" class="btn btn-secondary btn-sm"><i class="fas fa-trash-alt"></i></a>
                                                &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-secondary" onclick="openInlineForm('{{$data->uom_id}}')"><i class="fas fa-edit"></i></button>
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
                <input type="text" name="uom_name" id="edit_uom_name" class="form-control" value="`+edit_values[0]+`" autocomplete="off">
                <div class="invalid-feedback" id="edit_uom_name_error_msg"></div>
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
    var edit_uom_name_error = true;
    
    $(document).ready(function(){
        $(document).on("change", "#edit_uom_name", function(){
            edit_uom_name_validate();
        });
    });
    
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
    
    function submitFormInline(){
        edit_uom_name_validate();
      
        if(edit_uom_name_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>


<script>
     /* validation starts */
    // error variables as true = error occured
    var uom_name_error = true;
    
    $(document).ready(function(){
        $("#uom_name").change(function(){
            uom_name_validate();
        });
       
        // reset/initiate form
        $(".uom-add-button").click(function(){
            initiateForm();
        });
    });

    // intitiate everything reletaed to "add form"
    function initiateForm(){
        document.getElementById('uom-form').reset();
        $("#uom_name").removeClass('is-invalid');
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
    
    function submitForm(){
        uom_name_validate();
      
        if(uom_name_error){ return false; } // error occured
        else{ $(".custom-loader").show(); return true; } // proceed to submit form data
    }
</script>
@endsection