@extends('layout.layout')

@section('title', 'Geo Structure')

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

    @media print{

            #printable-area{
                margin-top: 250px !important;
                font-size: small;
            width: 100% !important;
            }

            .no-print, .no-print *
            {
                display: none !important;
            }
            #printable-info-details{
                visibility: visible;
                position: fixed;
                margin-left: 100px !important;
            font-size:medium;
            }
            #print-button, #print-button *{
                visibility: hidden;
            }
            .card-title-print-1{
                visibility: visible !important;
                position: fixed;
                color: #0a0a0a;
                font-size: 30px;;
                left: 0;
                top: 50px;
                width: 100vw !important;
                height: 100vw !important;
            }
            .card-title-print-2{
                visibility: visible !important;
                position: fixed;
                 color: #0a0a0a;
                 font-size: 30px;;
                left: 0;
                top: 100px;
                width: 100vw !important;
                height: 100vw !important;
            }
            .card-title-print-3{
                visibility: visible !important;
                position: fixed;
                 color: #0a0a0a;
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
    </style>
@endsection

@section('page-content')
<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>

<div class="card">
        <div class="col-md-12">         
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">{{$phrase->geo_struture}}</h4>
                        <div class="card-tools">
                            <a href="#" data-toggle="tooltip" title="{{$phrase->send_email}}"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                            <button type="button" data-toggle="tooltip" title="{{$phrase->print}}" class="btn btn-icon btn-round btn-default" onclick="printViewone();"><i class="fa fa-print" aria-hidden="true"></i></button>

                            <button  type="button" data-toggle="tooltip" title="{{$phrase->export_pdf}}" onclick="exportTrigger()" name="print" value="print_pdf" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button>
                            <button  type="button" data-toggle="tooltip" title="{{$phrase->export_excel}}" onclick="exportTriggerexcel()" name="print" value="excel_sheet" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-excel"></i></button>
                            <!-- <button type="submit" name="print" value="excel_sheet" class="btn btn-icon btn-round btn-success" ><i class="fas fa-file-excel"></i></button> -->


                            <!-- <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                            <a href="{{url('geo-structure/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a>
                            <a href="{{url('geo-structure/pdf/pdfURL')}}" data-toggle="tooltip" target="_BLANK" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="far fa-file-pdf" style="color: #850000;"></i></button></a> -->
                            @if($desig_permissions["mod12"]["add"])
                            <a class="btn btn-secondary department-add-button" href="{{url('geo-structure/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div><br>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd" role="tab" aria-controls="pills-home-nobd" aria-orientation="true">{{$phrase->district}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false">{{$phrase->sub_divisin}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab-nobd" data-toggle="pill" href="#pills-contact-nobd" role="tab" aria-controls="pills-contact-nobd" aria-selected="false">{{$phrase->block}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-panchayat-tab-nobd" data-toggle="pill" href="#pills-panchayat-nobd" role="tab" aria-controls="pills-panchayat-nobd" aria-selected="false">{{$phrase->panchayat}}</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                        <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
                            <form action="{{url('geo-structure/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                            @csrf
                                <div id="printable-info-details">
                                    <p class="card-title-print-1">Title: Geo Structure</p>
                                    <p class="card-title-print-2">Date & Time: <?php $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                    <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                                </div>
                                <table class="table" id="printable-area">
                                    <thead style="background: #d6dcff;color: #000;">
                                        <tr>
                                            <th>#</th>
                                            <th>{{$phrase->name}}</th>
                                            <th>{{$phrase->organisation}}</th>
                                            @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"] )
                                            <th class="action-buttons">{{$phrase->action}}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $count=1; ?>
                                    @if(isset($datas))
                                        @foreach($datas as $data)
                                            @if($data->level_id=="1")
                                                <tr>
                                                    <td width="40px;">{{$count++}}   <input type="hidden" value="{{$data->geo_id}}" name="district_geo_id[]"></td>
                                                    <td>{{$data->geo_name}}</td>
                                                    <td>{{$data->org_name}}</td>
                                                    @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"] )
                                                    <td class="action-buttons">
                                                    @if($desig_permissions["mod12"]["edit"])
                                                        &nbsp;&nbsp;<a href="{{url('geo-structure/add')}}?purpose=edit&id={{$data->geo_id}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" title="{{$phrase->edit}}"><i class="fas fa-edit"></i></a>
                                                    @endif
                                                    @if($desig_permissions["mod12"]["del"])
                                                        <a href="{{url('geo-structure/delete')}}/{{$data->geo_id}}" class="btn btn-danger btn-sm delete-button" data-toggle="tooltip" title="{{$phrase->delete}}"><i class="fas fa-trash-alt"></i></a>
                                                    @endif
                                                    
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if($count==1)
                                        <tr>
                                            <td colspan="4"><center>No data shown</center></td>
                                        </tr>
                                    @endif
                                </table>
                                <input type="text" name="type" value=""  hidden>
                                <input type="text" name="level_id" value="1" hidden>
                                <button type="submit" class="submit" hidden>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
                            <form action="{{url('geo-structure/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                                @csrf
                                <div id="printable-info-details">
                                    <p class="card-title-print-1">Title: Geo Structure</p>
                                    <p class="card-title-print-2">Date & Time: <?php $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                    <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                                </div>
                                <table class="table" id="printable-area">
                                    <thead style="background: #d6dcff;color: #000;">
                                        <tr>
                                            <th>#</th>
                                            <th>{{$phrase->name}}</th>
                                            <th>{{$phrase->district}}</th>
                                            <th>{{$phrase->organisation}}</th>
                                            @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"])
                                            <th class="action-buttons">{{$phrase->action}}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $count=1; ?>
                                    @if(isset($datas))
                                        @foreach($datas as $data)
                                            @if($data->level_id=="2")
                                                <tr>
                                                    <td width="40px;">{{$count++}}   <input type="hidden" value="{{$data->geo_id}}" name="sub_div_geo_id[]"></td>
                                                    <td>{{$data->geo_name}}</td>
                                                    <td>{{$data->parent_name}} <small>{{$data->parent_level_name}}</small></td>
                                                    <td>{{$data->org_name}}</td>
                                                    @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"] )
                                                    <td class="action-buttons">
                                                    @if($desig_permissions["mod12"]["edit"])
                                                    &nbsp;&nbsp;<a href="{{url('geo-structure/add')}}?purpose=edit&id={{$data->geo_id}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" title="{{$phrase->edit}}"><i class="fas fa-edit"></i></a>
                                                     @endif 
                                                    @if($desig_permissions["mod12"]["del"])
                                                    <a href="{{url('geo-structure/delete')}}/{{$data->geo_id}}" class="btn btn-danger btn-sm delete-button" data-toggle="tooltip" title="{{$phrase->delete}}"><i class="fas fa-trash-alt"></i></a>
                                                    @endif

                                                    </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if($count==1)
                                        <tr>
                                            <td colspan="4"><center>No data shown</center></td>
                                        </tr>
                                    @endif
                                </table>
                                <!-- <input type="text" name="type" value="sub_division" hidden>
                            <button type="submit" class="submit" hidden>

                                <input type="text" name="type" value="sub_div_excel" hidden> -->
                                <input type="text" name="type" value="" hidden>
                                <input type="text" name="level_id" value="2" hidden>
                                <button type="submit" class="submit" hidden>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-contact-nobd" role="tabpanel" aria-labelledby="pills-contact-tab-nobd">
                            <form action="{{url('geo-structure/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                                @csrf
                                <div id="printable-info-details">
                                    <p class="card-title-print-1">Title: Geo Structure</p>
                                    <p class="card-title-print-2">Date & Time: <?php $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                    <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                                </div>
                                <table class="table" id="printable-area">
                                    <thead style="background: #d6dcff;color: #000;">
                                        <tr>
                                            <th>#</th>
                                            <th>{{$phrase->name}}</th>
                                            <th>{{$phrase->sub_divisin}}</th>
                                            <th>{{$phrase->organisation}}</th>
                                            @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"])
                                            <th class="action-buttons">{{$phrase->action}}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $count=1; ?>
                                    @if(isset($datas))
                                        @foreach($datas as $data)
                                            @if($data->level_id=="3")
                                                <tr>
                                                    <td width="40px;">{{$count++}}  <input type="hidden" value="{{$data->geo_id}}" name="block_geo_id[]"></td>
                                                    <td>{{$data->geo_name}}</td>
                                                    <td>{{$data->parent_name}} <small>{{$data->parent_level_name}}</small></td>
                                                    <td>{{$data->org_name}}</td>
                                                    @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"] )
                                                    <td class="action-buttons">
                                                         @if($desig_permissions["mod12"]["edit"])
                                                        &nbsp;&nbsp;<a href="{{url('geo-structure/add')}}?purpose=edit&id={{$data->geo_id}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" title="{{$phrase->edit}}"><i class="fas fa-edit"></i></a>
                                                        @endif
                                                         @if($desig_permissions["mod12"]["del"])
                                                        <a href="{{url('geo-structure/delete')}}/{{$data->geo_id}}" class="btn btn-danger btn-sm delete-button" data-toggle="tooltip" title="{{$phrase->delete}}"><i class="fas fa-trash-alt"></i></a>
                                                        @endif
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if($count==1)
                                        <tr>
                                            <td colspan="4"><center>No data shown</center></td>
                                        </tr>
                                    @endif
                                </table> 
                                <input type="text" name="type" value="" hidden>
                                <input type="text" name="level_id" value="3" hidden>
                                <button type="submit" class="submit" hidden>
                            </form>                              
                        </div>
                        <div class="tab-pane fade" id="pills-panchayat-nobd" role="tabpanel" aria-labelledby="pills-panchayat-tab-nobd">
                            <form action="{{url('geo-structure/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                                @csrf
                                <div id="printable-info-details">
                                    <p class="card-title-print-1">Title: Geo Structure
                                    </p>
                                    <p class="card-title-print-2">Date & Time: <?php $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                                    <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                                </div>
                                <table class="table table-datatable" id="printable-area">
                                    <thead style="background: #d6dcff;color: #000;">
                                        <tr>
                                            <th>#</th>
                                            <th>{{$phrase->name}}</th>
                                            <th>{{$phrase->block}}</th>
                                            <th>{{$phrase->village}}</th>
                                            <th>{{$phrase->organisation}}</th>
                                            @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"])
                                            <th class="action-buttons">{{$phrase->action}}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $count=1; ?>
                                    @if(isset($datas))
                                        @foreach($datas as $data)
                                            @if($data->level_id=="4")
                                                <tr>
                                                    <td width="40px;">{{$count++}}  <input type="hidden" value="{{$data->geo_id}}" name="payanchat_geo_id[]"></td>
                                                    <td>{{$data->geo_name}}</td>
                                                    <td>{{$data->parent_name}}</td>
                                                    <td>{{$data->no_of_villages}}</td>
                                                    <td>{{$data->org_name}}</td>
                                                    @if($desig_permissions["mod12"]["edit"] || $desig_permissions["mod12"]["del"] )
                                                    <td class="action-buttons">
                                                    @if($desig_permissions["mod12"]["edit"])
                                                        &nbsp;&nbsp;<a href="{{url('geo-structure/add')}}?purpose=edit&id={{$data->geo_id}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" title="{{$phrase->edit}}"><i class="fas fa-edit"></i></a>
                                                        @endif
                                                        @if($desig_permissions["mod12"]["del"])
                                                        <a href="{{url('geo-structure/delete')}}/{{$data->geo_id}}" class="btn btn-danger btn-sm delete-button" data-toggle="tooltip" title="{{$phrase->delete}}"><i class="fas fa-trash-alt"></i></a>
                                                        @endif
                                                        
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if($count==1)
                                        <tr>
                                            <td colspan="4"><center>No data shown</center></td>
                                        </tr>
                                    @endif
                                </table>

                                <input type="text" name="type" value="" hidden>
                                <input type="text" name="level_id" value="4" hidden>
                                <button type="submit" class="submit" hidden>
                            </form>
                            
                        </div>
                    </div> <!-- end tab-content -->
                </div>
            </div>
        </div>
</div>

<script>
    function exportTrigger(){
        $(".tab-pane.active input[name='type']").val("pdf");
        $(".tab-pane.active .submit").click();
    }

    function exportTriggerexcel(){
        $(".tab-pane.active input[name='type']").val("excel");
        $(".tab-pane.active .submit").click();
    }
</script>
 
<script>
    function printViewone()
    {
        $("#printable-area").removeClass('table-datatable');
        // alert("fdfsdf");
        window.print();
    }
</script>
<script>
    $(document).ready(function(){
    $('.table table-datatable').dataTable( {
    "pageLength": 15
    } );
    });
</script>
   

<!-- email model -->
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
                                <input type="hidden" name="geo_structure" value="geo_structure">
                                <input type="hidden" name="data" value="{{$datas}}">
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div> 
                            <div class="form-group">  
                                <input type="email"  maxlenght="60" name="to" class="form-control" placeholder="To" required="">
                            </div>
                            <div class="form-group">                           
                                <input type="email"  maxlenght="60" name="cc" class="form-control" placeholder="CC">
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
<!-- end model -->

@endsection