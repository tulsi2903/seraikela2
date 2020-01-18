@extends('layout.layout')

@section('title', 'Scheme Type')

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
    <form action="{{url('scheme-type/view_diffrent_formate')}}" method="POST" enctype="multipart/form-data"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
        @csrf
        <div class="col-md-12">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">{{$phrase->scheme_type}}</h4>
                        <div class="card-tools">
                            <!-- <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button></a> -->
                            <button type="button" class="btn btn-icon btn-round btn-success"  onclick="openmodel();" ><i class="fa fa-envelope" aria-hidden="true"></i></button>

                            
                            <button  type="submit" name="print" value="print_pdf" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button>

                            <button type="submit" name="print" value="excel_sheet" class="btn btn-icon btn-round btn-success" ><i class="fas fa-file-excel"></i></button>
                            <button type="button" class="btn btn-icon btn-round btn-default" onclick="printViewone();"><i class="fa fa-print" aria-hidden="true"></i></button>




                            <!-- <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a> -->
                            <!-- <a href="{{url('scheme-type/pdf/pdfURL')}}"  target="_BLANK"  data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a> -->
                            <!-- <a href="{{url('scheme-type/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a> -->
                            @if($desig_permissions["mod6"]["add"])
                                <a class="btn btn-secondary" href="{{url('scheme-type/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a class="btn btn-secondary" href="{{url('scheme-type/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br> -->
                    <div class="table-responsive table-hover table-sales">
                        <div id="printable-info-details">
                            <p class="card-title-print-1">Title: Scheme Type</p>
                            <p class="card-title-print-2">Date & Time: <?php $currentDateTime = date('d-m-Y H:i:s'); echo $currentDateTime; ?>
                            <p class="card-title-print-3">User Name: {{session()->get('user_full_name')}}</p>
                        </div>
                        <table class="table table-datatable" id="printable-area">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>{{$phrase->name}}</th>
                                    @if($desig_permissions["mod6"]["del"] || $desig_permissions["mod6"]["edit"])
                                    <th class="action-buttons">{{$phrase->action}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <?php $count=1; ?>
                            @if(isset($datas))
                                @foreach($datas as $data)
                                    <tr>
                                        <td width="40px;">{{$count++}}  <input type="hidden" value="{{$data->sch_type_id}}" name="scheme_type_id[]"> </td>
                                        <td>{{$data->sch_type_name}}</td>
                                        @if($desig_permissions["mod6"]["del"] || $desig_permissions["mod6"]["edit"])
                                        <td class="action-buttons">
                                            @if($desig_permissions["mod6"]["del"])<a href="{{url('scheme-type/delete')}}/{{$data->sch_type_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>@endif
                                            @if($desig_permissions["mod6"]["edit"])&nbsp;&nbsp;<a href="{{url('scheme-type/add')}}?purpose=edit&id={{$data->sch_type_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>@endif
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
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function printViewone()
    {
      window.print();
    }
</script>

<script>
    function openmodel()
    {
        var search_element=$( "input[type=search]" ).val();
        $('#create-email').modal('show');
        $('#dept_search').val(search_element);
        // alert(search_element);
    }
    
    </script>
@endsection
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
                                <input type="hidden" name="scheme_type" value="scheme_type">
                                <input type="hidden" name="data" value="{{$datas}}">
                                <input type="hidden" name="search_query" id="dept_search" >
                                <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                            </div> 
                            <div class="form-group">  
                                <input type="text" name="to" class="form-control" placeholder="To" required="">
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
<!-- end model -->