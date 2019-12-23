@extends('layout.layout')

@section('title', 'Asset Numbers')

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
        <div class="card">
            <div class="col-md-12">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right" style="background:#fff;">
                            <h4 class="card-title">Asset Numbers</h4>
                            <div class="card-tools">
                                <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                                <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                                <a href="{{url('asset_Numbers/pdf/pdfURL')}}" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                                <a href="{{url('asset_Numbers/export/excelURL')}}" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a>

                                <a class="btn btn-secondary" href="{{url('asset-numbers/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
    
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
                        <table class="table table-datatable" id="printable-area">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>Year</th>
                                    <th>Asset</th>
                                    <th>Block</th>
                                    <th>Panchyat</th>
                                    <!-- <th>Pre Value</th> -->
                                    <th>Current Value</th>
                                    <th class="action-buttons">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count=1; ?>
                                @if(isset($datas))
                                    @foreach($datas as $data)
                                        <tr>
                                            <td width="40px;">{{$count++}}</td>
                                            <td>{{$data->year_value}}</td>
                                            <td>{{$data->asset_name}}</td>
                                            <td>{{$data->block_name}}</td>
                                            <td>{{$data->panchayat_name}}</td>
                                            <!-- <td>{{$data->pre_value}}</td> -->
                                            <td>{{$data->current_value}}</td>
                                            <td class="action-buttons">
    <!--                                             <a href="{{url('asset_numbers/delete')}}/{{$data->asset_numbers_id}}/{$data->asset_geo_location_id}/{$data->asset_block_count_id}" id="delete-button" class="btn btn-secondary btn-sm"><i class="fas fa-trash-alt"></i></a>
    -->                                             &nbsp;&nbsp;<a href="{{url('asset-numbers/add')}}?purpose=edit&id={{$data->asset_numbers_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                                    &nbsp;&nbsp;<a href="{{url('asset-numbers/view')}}/{{$data->asset_numbers_id}}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($count==1)
                                    <tr>
                                        <td colspan="7"><center>No data to shown</center></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
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
                                <input type="hidden" name="asset_numbers" value="asset_numbers">
                                <input type="hidden" name="data" value="{{$datas}}">
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
<!-- end model> -->