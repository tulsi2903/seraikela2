@extends('layout.layout') 

@section('title', 'Year') 

@section('page-style')
<style>
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
        }
        .no-print,
        .no-print * {
            display: none !important;
        }
        #printable-info-details {
            visibility: visible;
            position: fixed;
        }
        #print-button,
        #print-button * {
            visibility: hidden;
        }
        .card-title-print-1 {
            visibility: visible !important;
            position: fixed;
            color: #147785;
            font-size: 30px;
            ;
            left: 0;
            top: 50px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .card-title-print-2 {
            visibility: visible !important;
            position: fixed;
            color: #147785;
            font-size: 30px;
            ;
            left: 0;
            top: 100px;
            width: 100vw !important;
            height: 100vw !important;
        }
        .card-title-print-3 {
            visibility: visible !important;
            position: fixed;
            color: #147785;
            font-size: 30px;
            ;
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
                    <h4 class="card-title">{{$phrase->year}}</h4>
                    <div class="card-tools">
                        <!-- <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button></a> -->
                        <button type="button" class="btn btn-icon btn-round btn-success" onclick="openmodel();"><i class="fa fa-envelope" aria-hidden="true"></i></button>
                        <button type="button" onclick="exportSubmit('print_pdf');" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button>
                        <button type="button" onclick="exportSubmit('excel_sheet');" class="btn btn-icon btn-round btn-success"><i class="fas fa-file-excel"></i></button>
                        <button type="button" class="btn btn-icon btn-round btn-default" onclick="printViewone();"><i class="fa fa-print" aria-hidden="true"></i></button>

                        <!-- <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                            <a href="{{url('year/pdf/pdfURL')}}" target="_BLANK" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                            <a href="{{url('year/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a> -->
                        @if($desig_permissions["mod3"]["add"])
                        <a id="toggle1" class="btn btn-secondary year-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}</a> 
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <br>
                    <!-- <div style="display: -webkit-box; float:right;margin-top: -6px;">
                    <div style=" margin-bottom:-49px;margin-right: 1em;">
                        <a id="toggle1" class="btn btn-secondary year-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                        </div>
                    </div> -->
                    <div id="show-toggle1">
                        <form action="{{url('year/store')}}" method="POST" id="year-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year_value">{{$phrase->from}}<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="from_value" id="from_value" class="form-control">
                                            <option value="">--Select---</option>
                                            @for ($from=2015; $from
                                            < 2049; $from++) <option value="{{$from}}">{{$from}}</option>
                                                @endfor
                                        </select>
                                        <div class="invalid-feedback" id="from_value_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year_value">{{$phrase->to}}<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="to_value" id="to_value" class="form-control">
                                            <option value="">--Select---</option>
                                            @for ($to=2015; $to
                                            < 2049; $to++) <option value="{{$to}}">{{$to}}</option>
                                                @endfor
                                        </select>
                                        <div class="invalid-feedback" id="to_value_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">{{$phrase->is_active}}<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">---Select---</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <div class="invalid-feedback" id="status_error_msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div style="height:30px;"></div>
                                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">{{$phrase->save}}&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                                        <button type="reset" class="btn btn-secondary" onclick="reset_from();">{{$phrase->reset}}&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive table-hover table-sales">
                        <form action="{{url('year/store')}}" method="POST">
                            @csrf
                            <div id="printable-info-details">
                                <p class="card-title-print-1">Title: Year </p>
                                <p class="card-title-print-2">Date & Time:
                                    <?php $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                        <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                            </div>
                            <table class="table table-datatable" id="printable-area">
                                <thead style="background: #d6dcff;color: #000;">
                                    <tr>
                                        <th>#</th>
                                        <th>{{$phrase->year}}</th>
                                        <th>{{$phrase->is_active}}</th>
                                        @if($desig_permissions["mod3"]["del"] ||$desig_permissions["mod3"]["edit"] )
                                        <th class="action-buttons">{{$phrase->action}}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <?php $count=1; ?>
                                    @if(isset($datas)) @foreach($datas as $data)
                                    <?php $year_value_tmp = explode('-',$data->year_value); ?>
                                        <tr data-row-id="{{$data->year_id}}" data-row-values="{{$year_value_tmp[0]}},{{$year_value_tmp[1]}},{{$data->status}}">
                                            <td width="40px;">{{$count++}}
                                                <input type="text" value="{{$data->year_id}}" name="year_id_to_export[]" hidden>
                                            </td>
                                            <td>{{$data->year_value}}</td>
                                            <td>
                                                <?php if($data->status=='1'){
                                                echo '<i class="fas fa-check text-success"></i> Active';
                                            }
                                            else{
                                                echo '<i class="fas fa-times text-danger"></i> Inactive';
                                            } ?></td>
                                            @if($desig_permissions["mod3"]["del"] ||$desig_permissions["mod3"]["edit"] )
                                            <td class="action-buttons">
                                                @if($desig_permissions["mod3"]["del"])<a href="{{url('year/delete')}}/{{$data->year_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>@endif @if($desig_permissions["mod3"]["edit"])&nbsp;&nbsp;
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="openInlineForm('{{$data->year_id}}')"><i class="fas fa-edit"></i></button>
                                                @endif
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach @endif @if($count==1)
                                        <tr>
                                            <td colspan="4">
                                                <center>No data to shown</center>
                                            </td>
                                        </tr>
                                        @endif
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- export starts -->
        <form action="{{url('year/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data" id="export-form"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
        @csrf
        <input type="text" name="year_id" hidden>
        <input type="text" name="print" hidden> <!-- hidden input for export (pdf/excel) -->
        </form>
        <!-- export ends -->

        <!-- email -->
        <div id="create-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title mt-0">{{$phrase->action}}</h4>
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
                                        <input type="hidden" name="year" value="year">
                                        <input type="hidden" name="data" value="{{$datas}}">
                                        <input type="hidden" name="search_query" id="dept_search">
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
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" aria-required="true">
                                    </div>
                                    <!-- <div class="form-group">
                                <label for="field-2" class="control-label">Message <font color="red">*</font></label>
                                <textarea class="wysihtml5 form-control article-ckeditor" required id="article-ckeditor"  placeholder="Message body" style="height: 100px" name="message" ></textarea>
                            </div> -->

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">{{$phrase->subject}}</button>
                            <button type="submit" class="btn btn-info waves-effect waves-light">{{$phrase->send}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->

    </div>
    <script>
        function openmodel() {
            var search_element = $("input[type=search]").val();
            $('#create-email').modal('show');
            $('#dept_search').val(search_element);
            // alert(search_element);
        }
    </script>

    <script>
        function printViewone() {
            window.print();
        }
    </script>

    <script>
    function exportSubmit(type){
        $("input[name='print']").val(type);
        var values = $("input[name='year_id_to_export[]']").map(function(){return $(this).val();}).get();
        $("input[name='year_id']").val(values);
        document.getElementById('export-form').submit();
    }
    </script>

    <script>
        function reset_from()
        {
        $("select").removeClass("is-invalid");
        }
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
                    <div>
                        <div style="display: inline-block; width:46%;">
                            <label>From<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="from_value" id="edit_from_value" class="form-control">
                                <option value="">--Select---</option>
                                @for ($from=2015; $from < 2049; $from++) 
                                    <option value="{{$from}}"`;
                                        if(edit_values[0]=={{$from}}){
                                            form_append += `selected`;
                                        }
                                    form_append +=`>{{$from}}</option>
                                @endfor
                            </select>
                            <div class="invalid-feedback" id="edit_from_value_error_msg"></div>
                        </div>
                        -
                        <div style="display: inline-block; width:46%;">
                            <label>To<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="to_value" id="edit_to_value" class="form-control">
                                <option value="">--Select---</option>
                                @for ($to=2015; $to < 2049; $to++) 
                                    <option value="{{$to}}"`;
                                        if(edit_values[1]=={{$to}}){
                                            form_append += `selected`;
                                        }
                                    form_append +=`>{{$to}}</option>
                                @endfor
                            </select>
                            <div class="invalid-feedback" id="edit_to_value_error_msg"></div>
                        </div>
                    </div>
                </td>
                <td>
                    <select class="form-control" name="status" id="edit_status">
                        <option value="">-Select-</option>`;
                        
            form_append += `<option value="1" `;
                    if(edit_values[2]==1){
                        form_append += `selected`;
                    }
            form_append += `>Active</option>`;
            form_append += `<option value="0" `;
                    if(edit_values[2]==0){
                        form_append += `selected`;
                    }
            form_append += `>Inactive</option>`;

            form_append +=`</select>
                        <div class="invalid-feedback" id="edit_status_error_msg"></div>
                </td>
                <td>
                    <input type="text" name="edit_id" value="`+id+`" hidden>
                    <button type="submit" onclick="return submitFormInline()" class="btn btn-success btn-sm">Save&nbsp;<i class="fas fa-check"></i></button>
                    &nbsp;&nbsp;<button type="button" class="btn btn-dark btn-sm" onclick="closeInlineForm()">Cancel&nbsp;<i class="fas fa-times"></i></button>
                </td>
            </tr>`;


            $("tr[data-row-id='"+id+"']").after(form_append);
            $("tr[data-row-id='"+id+"']").hide();
            reset_edit_from_value_options(); // to hide less than "from" value of "to" value
            to_edit_id = id;
            edit_form_opened = true;
        }
        /* to close forms */
        function closeInlineForm() {
            if (edit_form_opened) {
                $("tr[data-edit-id='" + to_edit_id + "']").remove();
                $("tr[data-row-id='" + to_edit_id + "']").show();
                edit_form_opened = false;
                to_edit_id = 0;
            }
        }

        /*
        *
        edit inline form: validation start
        *
        */
        var edit_from_value_error = true;
        var edit_to_value_error = true;
        var edit_status_error = true;

        $(document).ready(function() {
            $(document).on("change", "#edit_from_value", function() {
                edit_from_value_validate();
            });
            $(document).on("change", "#edit_to_value", function() {
                edit_to_value_validate();
            });
            $(document).on("change", "#edit_status", function() {
                edit_status_validate();
            });
        });

        //year validation
        function edit_from_value_validate() {
            var edit_from_value_val = $("#edit_from_value").val();
            if (edit_from_value_val == "") {
                edit_from_value_error = true;
                $("#edit_from_value").addClass('is-invalid');
                $("#edit_from_value_error_msg").html("From should not be blank");
            } else {
                edit_from_value_error = false;
                $("#edit_from_value").removeClass('is-invalid');
            }
        }

        function edit_to_value_validate() {
            var edit_to_value_val = $("#edit_to_value").val();

            if (edit_to_value_val == "") {
                edit_to_value_error = true;
                $("#edit_to_value").addClass('is-invalid');
                $("#edit_to_value_error_msg").html("From should not be blank");
            } else {
                edit_to_value_error = false;
                $("#edit_to_value").removeClass('is-invalid');
            }
        }

        // status validation
        function edit_status_validate() {
            var edit_status_val = $("#edit_status").val();
            if (edit_status_val == "") {
                edit_status_error = true;
                $("#edit_status").addClass('is-invalid');
                $("#edit_status_error_msg").html("Please select year's status");
            } else {
                edit_status_error = false;
                $("#edit_status").removeClass('is-invalid');
            }
        }

        // final submission
        function submitFormInline() {
            edit_from_value_validate();
            edit_to_value_validate();
            edit_status_validate();

            if (edit_from_value_error || edit_to_value_error || edit_status_error) {
                return false;
            } // error occured
            else {
                $(".custom-loader").show();
                return true;
            } // proceed to submit form data
        }

        // edit: hide less than "from" value of "to" value
        $(document).ready(function() {
            $(document).on("change", "#edit_from_value", function() {
                reset_edit_from_value_options();
            });
        });

        function reset_edit_from_value_options() {
            var tmp = $("#edit_from_value").val();
            $('#edit_to_value option').show();
            $('#edit_to_value option').filter(function() {
                return parseInt(this.value, 10) <= tmp;
            }).hide();
        }
    </script>

    <script>
        /* validation starts */
        // error variables as true = error occured
        var from_value_error = true;
        var to_value_error = true;
        var status_error = true;

        $(document).ready(function() {
            $("#from_value").change(function() {
                from_value_validate();
            });
            $("#to_value").change(function() {
                to_value_validate();
            });
            $("#status").change(function() {
                status_validate();
            });

            // reset/initiate form
            $(".year-add-button").click(function() {
                initiateForm();
            });
        });

        // to reset everything as before
        function initiateForm() {
            document.getElementById('year-form').reset();
            $("#from_value").removeClass('is-invalid');
            $("#to_value").removeClass('is-invalid');
            $("#status").removeClass('is-invalid');
        }

        //year validation
        function from_value_validate() {
            var from_value_val = $("#from_value").val();

            if (from_value_val == "") {
                from_value_error = true;
                $("#from_value").addClass('is-invalid');
                $("#from_value_error_msg").html("From should not be blank");
            } else {
                from_value_error = false;
                $("#from_value").removeClass('is-invalid');
            }
        }

        function to_value_validate() {
            var to_value_val = $("#to_value").val();

            if (to_value_val == "") {
                to_value_error = true;
                $("#to_value").addClass('is-invalid');
                $("#to_value_error_msg").html("From should not be blank");
            } else {
                to_value_error = false;
                $("#to_value").removeClass('is-invalid');
            }
        }

        // status validation
        function status_validate() {
            var status_val = $("#status").val();
            if (status_val == "") {
                status_error = true;
                $("#status").addClass('is-invalid');
                $("#status_error_msg").html("Please select year's status");
            } else {
                status_error = false;
                $("#status").removeClass('is-invalid');
            }
        }

        // final submission
        function submitForm() {
            from_value_validate();
            to_value_validate();
            status_validate();

            if (from_value_error || to_value_error || status_error) {
                return false;
            } // error occured
            else {
                $(".custom-loader").show();
                return true;
            } // proceed to submit form data
        }

        // Add: hide less than "from" value of "to" value
        $(document).ready(function() {
            $("#from_value").change(function() {
                var tmp = $("#from_value").val();
                $('#to_value option').show();
                $('#to_value option').filter(function() {
                    return parseInt(this.value, 10) <= tmp;
                }).hide();
            });
        });
    </script>
    @endsection