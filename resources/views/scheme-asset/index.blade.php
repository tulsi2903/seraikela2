@extends('layout.layout')

@section('title', 'Scheme Asset')

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
<div class="card">
        <div class="col-md-12">
            <div class="card-header">
                <div class="card-head-row card-tools-still-right" style="background:#fff;">
                    <h4 class="card-title">{{$phrase->scheme_assets}} </h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-icon btn-round btn-success"  onclick="openmodel();" ><i class="fa fa-envelope" aria-hidden="true"></i></button>

                        <!-- <a href="#" data-toggle="tooltip" title="{{$phrase->send_email}} "><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button></a> -->
                        <!-- <a href="#" data-toggle="tooltip" title="{{$phrase->print}}"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a> -->
                        <button type="button" class="btn btn-icon btn-round btn-default" onclick="printViewone();"><i class="fa fa-print" aria-hidden="true"></i></button>
                        <button type="button" onclick="exportSubmit('print_pdf');" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button>
                        <button type="button" onclick="exportSubmit('excel_sheet');" class="btn btn-icon btn-round btn-success"><i class="fas fa-file-excel"></i></button>
                        <!-- <a href="#" data-toggle="tooltip" title="{{$phrase->export_pdf}}"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                        <a href="#" data-toggle="tooltip" title="{{$phrase->export_excel}}"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a> -->
                        <a class="btn btn-secondary" href="{{url('scheme-asset/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}</a>

                    </div>
                </div>
            </div>
        </div>
    
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive table-hover table-sales">
                        <div id="printable-info-details">
                            <p class="card-title-print-1">Title: Scheme Details </p>
                            <p class="card-title-print-2">Date & Time:
                                <?php date_default_timezone_set('Asia/Kolkata'); $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                    <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                        </div>
                        <table class="table table-datatable" id="printable-area">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>{{$phrase->icon}}</th>
                                    <th>{{$phrase->name}}</th>
                                    
                                    <th>{{$phrase->geo_related}}</th>
                                    <th>{{$phrase->multiple_geo_tags}}</th>
                                    <th>UoM</th>
                                    <th>{{$phrase->action}}</th>
                                </tr>
                            </thead>
                            <?php $count=1; ?>
                           
                               @foreach($datas as $data)
                                   
                                        <tr>
                                            <td width="40px;">{{$count++}}

                                                <input type="text" value="{{$data->scheme_asset_id}}" name="scheme_asset_id_to_export[]" hidden >

                                            </td>
                                            <td>@if($data->mapmarkericon) <img src="{{$data->mapmarkericon}}" style="height: 50px;"> @endif</td>  
                                            <td>{{$data->scheme_asset_name}}</td>
                                                                        
                                            <td>
                                                @if($data->geo_related == 1)
                                                Yes
                                                @else
                                                No
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->multiple_geo_tags == 1)
                                                Yes
                                                @else
                                                No
                                                @endif
                                            </td> 
                                            <td>{{$data->uom_name}}</td>   
                                            <td>
                                                <a href="{{url('scheme-asset/view')}}/{{$data->scheme_asset_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                                                &nbsp;&nbsp;<a href="{{url('scheme-asset/add')}}?purpose=edit&id={{$data->scheme_asset_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                                &nbsp;&nbsp;<a href="{{url('scheme-asset/delete')}}/{{$data->scheme_asset_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                   
                               
                           
                            @if($count==1)
                                <tr>
                                    <td colspan="9"><center>No data to shown</center></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>


    <!-- export starts -->
        <form action="{{url('scheme-asset/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data" id="export-form"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
        @csrf
        <input type="text" name="scheme_asset_id" hidden >
        <input type="text" name="print" hidden > <!-- hidden input for export (pdf/excel) -->
        </form>
    <!-- export ends -->


        </div>

        <script>
            function exportSubmit(type)
            {
                $("input[name='print']").val(type);
                var values = $("input[name='scheme_asset_id_to_export[]']").map(function(){return $(this).val();}).get();
                $("input[name='scheme_asset_id']").val(values);
                document.getElementById('export-form').submit();
            }
        </script>
        
        <script>
            function printViewone() {
                window.print();
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
                                <input type="hidden" name="scheme_asset" value="scheme_asset"> 
                                <input type="hidden" name="data" value="{{$datas}}">
                                <input type="text" name="search_query" id="dept_search" hidden>
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div> 
                            <div class="form-group">  
                                <input type="email" name="to" class="form-control" placeholder="{{$phrase->to}}" required="">
                            </div>
                            <div class="form-group">                           
                                <input type="text" name="cc" class="form-control" placeholder="{{$phrase->cc}}">
                            </div>
                           
                            <div class="form-group">
                                <label for="subject" class="control-label">{{$phrase->subject}} <font color="red">*</font></label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="{{$phrase->subject}}"  required=""  aria-required="true">
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


@endsection