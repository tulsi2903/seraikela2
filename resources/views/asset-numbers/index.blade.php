@extends('layout.layout')

@section('title', 'Resources Number')

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
        color: #fff !important;
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
    .logo-header .logo {
        color: #575962;
        opacity: 1;
        position: relative;
        height: 100%;
        margin-top: 1em;
    }

    .btn-toggle {
        color: #fff !important;
        margin-top: 1em;
    }
</style>
@endsection

@section('page-content')

<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>

<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Resources Number</h4>
                <div class="card-tools">
                    <!-- <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button></a> -->
                    
                    <button type="button" class="btn btn-icon btn-round btn-success"  onclick="openmodel();" ><i class="fa fa-envelope" aria-hidden="true"></i></button>
                    <button type="button" class="btn btn-icon btn-round btn-default" onclick="printViewone();"><i class="fa fa-print" aria-hidden="true"></i></button>

                    <!-- <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a> -->
                    <button type="button" onclick="exportSubmit('print_pdf');" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button>
                    <button type="button" onclick="exportSubmit('excel_sheet');" class="btn btn-icon btn-round btn-success"><i class="fas fa-file-excel"></i></button>
                    <!-- <a href="{{url('asset_Numbers/pdf/pdfURL')}}" target="_blank" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button></a>
                    <a href="{{url('asset_Numbers/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary"><i class="fas fa-file-excel"></i></button></a> -->
                    @if($desig_permissions["mod14"]["add"])
                    <a href="{{url('asset_Numbers/downloadFormat')}}" data-toggle="tooltip" title="Download Excel Format"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fa fa-download"></i></button></a>
                    <a href="{{url('asset_Numbers/downloadFormatwithLocation')}}" data-toggle="tooltip" title="Download Location Excel Format"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fa fa-download"></i></button></a>
                    <!-- <a href="{{url('asset_Numbers/changeViewforimport')}}" data-toggle="tooltip" title="Import From Excel"><button type="button" class="btn btn-icon btn-round btn-default" ><i class="fa fa-upload"></i></button></a> -->
                    <a class="btn btn-secondary" href="{{url('asset-numbers/add')}}" role="button" style="padding: 7px;"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Enter Value</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a class="btn btn-secondary" href="{{url('asset-numbers/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br> -->
                <div class="table-responsive table-hover table-sales">
                    <div id="printable-info-details">
                        <p class="card-title-print-1">Title: Resources Number</p>
                        <p class="card-title-print-2">Date & Time:
                            <?php  date_default_timezone_set('Asia/Kolkata'); $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                    </div>
                    <table class="table table-datatable" id="printable-area">
                        <thead style="background: #d6dcff;color: #000;">
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>Resource</th>
                                <th>Block</th>
                                <th>Panchyat</th>
                                <!-- <th>Pre Value</th> -->
                                <th>Current Value</th>
                                @if($desig_permissions["mod14"]["view"] ||$desig_permissions["mod14"]["del"] || $desig_permissions["mod14"]["edit"])
                                <th class="action-buttons">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count=1; ?>
                            @if(isset($datas))
                            @foreach($datas as $data)
                            <tr>
                                <td width="40px;">{{$count++}}

                                    <input type="text" value="{{$data->asset_numbers_id }}" name="asset_numbers_id_to_export[]" hidden >


                                </td>
                                <td>{{$data->year_value}}</td>
                                <td>{{$data->asset_name}}</td>
                                <td>{{$data->block_name}}</td>
                                <td>{{$data->panchayat_name}}</td>
                                <!-- <td>{{$data->pre_value}}</td> -->
                                <td>{{$data->current_value}}</td>
                                @if($desig_permissions["mod14"]["view"] ||$desig_permissions["mod14"]["del"] || $desig_permissions["mod14"]["edit"])
                                <td class="action-buttons">
                                    @if($desig_permissions["mod14"]["del"])
                                    <!--  <a href="{{url('asset_numbers/delete')}}/{{$data->asset_numbers_id}}/{$data->asset_geo_location_id}/{$data->asset_block_count_id}" id="delete-button" class="btn btn-secondary btn-sm"><i class="fas fa-trash-alt"></i></a>-->
                                    @endif
                                    @if($desig_permissions["mod14"]["edit"])
                                    &nbsp;&nbsp;<a href="{{url('asset-numbers/add')}}?purpose=edit&id={{$data->asset_numbers_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                    @endif
                                    @if($desig_permissions["mod14"]["view"])
                                    &nbsp;&nbsp;<a href="{{url('asset-numbers/view')}}/{{$data->asset_numbers_id}}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @endif
                            @if($count==1)
                            <tr>
                                <td colspan="7">
                                    <center>No data to shown</center>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         <!-- export starts -->
         <form action="{{url('asset-numbers/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data" id="export-form"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
            @csrf
            <input type="text" name="asset_numbers_id"  hidden>
            <input type="text" name="print" hidden > <!-- hidden input for export (pdf/excel) -->
            </form>
        <!-- export ends -->
    
    </div>
    @if(@session()->get('message')!="")
    <?php 
                        $message = session()->get('message');
                        session()->forget('message');

                        echo "<script>
                            $(document).ready(function () {
                                swal({
                                    title: 'Do You Want To Enter Further Sub Resources Details?',
                                    // text: 'You won't be able to revert this!',
                                    icon: 'warning',
                                    buttons: {
                                        cancel: {
                                            visible: true,
                                            text: 'No, cancel!',
                                            className: 'btn btn-danger'
                                        },
                                        confirm: {
                                            text: 'Yes, Sub Add',
                                            className: 'btn btn-success'
                                        }
                                    }
                                }).then((willDelete) => {
                                    if (willDelete) {
                                        window.location = 'asset-numbers/add?purpose=edit&id=' + $message;
                                    }
                                });
                            });
                        </script>";
                    ?>
    @endif
    @endsection

    <!-- email model -->
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
                                    <input type="hidden" name="asset_numbers" value="asset_numbers">
                                    <input type="hidden" name="data" value="{{$datas}}">
                                    <input type="text" name="search_query" id="dept_search" hidden>
                                    <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                                </div>
                                <div class="form-group">
                                    <input type="text" name="to" class="form-control" placeholder="To" required="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="cc" class="form-control" placeholder="CC">
                                </div>

                                <div class="form-group">
                                    <label for="subject" class="control-label">Subject <font color="red">*</font></label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required="" aria-required="true">
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
    <!-- end model> -->

    
    <script>
        function exportSubmit(type)
        {
            $("input[name='print']").val(type);
            var values = $("input[name='asset_numbers_id_to_export[]']").map(function(){return $(this).val();}).get();
            $("input[name='asset_numbers_id']").val(values);
            document.getElementById('export-form').submit();
        }
    </script>
      <script>
        function openmodel()
        {
            // alert("afj;l");
            var search_element=$( "input[type=search]" ).val();
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