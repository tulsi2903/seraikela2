@extends('layout.layout')

@section('title', 'Scheme Group')

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
    </style>
@endsection

@section('page-content')

<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>

<div class="card">
        <div class="col-md-12">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Group</h4>
                        <div class="card-tools">
                            <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                            <a href="{{url('scheme-group/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a>
                            @if($desig_permissions["scheme group"]["add"])
                                <a class="btn btn-secondary" href="{{url('scheme-group/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a class="btn btn-secondary" href="{{url('scheme-group/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br> -->
                    <div class="table-responsive table-hover table-sales">
                        <table class="table table-datatable" id="printable-area">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>Groups</th>
                                    <th>is Active</th>
                                    <th class="action-buttons">Actions</th>
                                </tr>
                            </thead>
                            <?php $count=1; ?>
                            @if(isset($results))
                                @foreach($results as $result)
                                    <tr>
                                        <td width="40px;">{{$count++}}</td>
                                        <td>{{$result->scheme_group_name}}</td>
                                        <td><?php if($result->is_active=='1'){
                                            echo "Active";
                                        }
                                        else{
                                            echo "Inactive";
                                        } ?></td>
                                        <td class="action-buttons">
                                            @if($desig_permissions["scheme group"]["del"])<a href="{{url('scheme-group/delete')}}/{{$result->scheme_group_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>&nbsp;&nbsp;@endif
                                            @if($desig_permissions["scheme group"]["edit"])<a href="{{url('scheme-group/add')}}?purpose=edit&id={{$result->scheme_group_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>@endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($count==1)
                                <tr>
                                    <td colspan="4"><center>No data to show</center></td>
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
                                <input type="hidden" name="group" value="group"> 
                                <input type="hidden" name="result" value="{{$results}}">
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


