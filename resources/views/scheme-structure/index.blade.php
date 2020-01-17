@extends('layout.layout')

@section('title', 'Define Schemes')

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
</style>
@endsection

@section('page-content')
<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>
<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">{{$phrase->scheme}}</h4>
                <div class="card-tools">
                    <a href="#" data-toggle="tooltip" title="{{$phrase->send_email}}"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal"><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                    <a href="#" data-toggle="tooltip" title="{{$phrase->print}}"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                    <a href="{{url('scheme-structure/pdf/pdfURL')}}" data-toggle="tooltip" title="{{$phrase->export_pdf}}"><button type="button" class="btn btn-icon btn-round btn-warning"><i class="fas fa-file-export"></i></button></a>
                    <a href="{{url('scheme-structure/export/excelURL')}}" data-toggle="tooltip" title="{{$phrase->export_excel}}"><button type="button" class="btn btn-icon btn-round btn-primary"><i class="fas fa-file-excel"></i></button></a>
                    @if($desig_permissions["mod15"]["add"])
                    <a class="btn btn-secondary" href="{{url('scheme-structure/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add}}  </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a class="btn btn-secondary" href="{{url('scheme-structure/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br> -->
                <div class="table-responsive table-hover table-sales">
                    <table class="table table-datatable" id="printable-area">
                        <thead style="background: #d6dcff;color: #000;">
                            <tr>
                                <th>#</th>
                                <th>{{$phrase->scheme_logo}}</th>
                                <th>{{$phrase->scheme_name}}</th>
                                <th>{{$phrase->department}}</th>
                                <th>{{$phrase->sts}}</th>
                                @if($desig_permissions["mod15"]["edit"] || $desig_permissions["mod15"]["del"])
                                <th class="action-buttons">{{$phrase->action}}</th>
                                @endif
                            </tr>
                        </thead>
                        <?php $count=1; ?>
                        @if(isset($datas))
                        @foreach($datas as $data)
                        <tr>
                            <td width="40px;">{{$count++}}</td>
                            <td>@if($data->scheme_logo) <img src="{{$data->scheme_logo}}" style="height: 50px;"> @endif</td>
                            <td>({{$data->scheme_short_name}}) {{$data->scheme_name}}</td>
                            <td>{{$data->dept_name}}</td>
                            <td>
                                <?php if($data->status=="1"){
                                    ?><i class="fas fa-check-circle text-success"></i>&nbsp;&nbsp;Active<?php
                                } else{
                                    ?><i class="fas fa-dot-circle text-dark"></i>&nbsp;&nbsp;Inactive<?php
                                } ?>
                            </td>
                            @if($desig_permissions["mod15"]["edit"] || $desig_permissions["mod15"]["del"])
                            <td class="action-buttons">
                                @if($desig_permissions["mod15"]["edit"]) <a href="{{url('scheme-structure/add')}}?purpose=edit&id={{$data->scheme_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                @endif
                                @if($desig_permissions["mod15"]["view"])
                                &nbsp;&nbsp;<a href="{{url('scheme-structure/view')}}/{{$data->scheme_id}}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                @endif
                                @if($desig_permissions["mod15"]["del"])
                                &nbsp;&nbsp;<a href="{{url('scheme-structure/delete')}}/{{$data->scheme_id}};" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @endif
                        @if($count==1)
                        <tr>
                            <td colspan="4">
                                <center>No data to show</center>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    @endsection
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
                                    <input type="hidden" name="group" value="group">
                                    <input type="hidden" name="result" value="{{$datas}}">
                                    <!-- <input type="text" name="from" class="form-control" placeholder="From" required=""> -->
                                </div>
                                <div class="form-group">
                                    <input type="text" name="to" class="form-control" placeholder="{{$phrase->to}}" required="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="cc" class="form-control" placeholder="{{$phrase->cc}}">
                                </div>

                                <div class="form-group">
                                    <label for="subject" class="control-label">{{$phrase->subject}} <font color="red">*</font></label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="{{$phrase->subject}}" required="" aria-required="true">
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