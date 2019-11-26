@extends('layout.layout')

@section('title', 'Scheme Indicator')

@section('page-content')
    <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Indicator</h4>
                        <div class="card-tools">
                            <!-- <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-angle-down"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-times"></span></button> -->
                            <button type="button" class="btn btn-icon btn-round btn-warning" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-icon btn-round btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a class="btn btn-secondary" href="{{url('scheme-indicator/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br>
                    <div class="table-responsive table-hover table-sales">
                        <table class="table">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Indicator Name</th>
                                     <th scope="col">Scheme Name</th>
                                    <th scope="col">Unit</th>
                                     <th scope="col">Performance</th>
                                     <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <?php $count=1; ?>
                            @if(isset($datas))
                                @foreach($datas as $data)
                                    <tr>
                                        <td width="40px;">{{$count++}}</td>
                                        <td>{{$data->indicator_name}}</td>
                                         <td>{{$data->scheme_name}} ({{$data->scheme_short_name}})</td>
                                         <td>{{$data->unit}}</td>
                                         
                                         <td>{{$data->performance}}</td>
                                       
                                        <td>
                                            <a href="{{url('scheme-indicator/delete')}}/{{$data->indicator_id}}" id="delete-button" class="btn btn-secondary btn-sm"><i class="fas fa-trash-alt"></i></a>
                                            &nbsp;&nbsp;<a href="{{url('scheme-indicator/add')}}?purpose=edit&id={{$data->indicator_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($count==1)
                                <tr>
                                    <td colspan="5"><center>No data to shown</center></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>
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
                                <input type="hidden" name="scheme_indicator" value="scheme_indicator">
                                <input type="hidden" name="data" value="{{$datas}}">
                                <input type="text" name="from" class="form-control" placeholder="From" required="">
                            </div> 
                            <div class="form-group">  
                                <input type="text" name="to" class="form-control" placeholder="To" required="">
                            </div>
                            <div class="form-group">                           
                                <input type="text" name="cc" class="form-control" placeholder="CC" required="">
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
<!-- end model -->