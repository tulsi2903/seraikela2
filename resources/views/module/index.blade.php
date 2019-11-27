@extends('layout.layout')

@section('title', 'Module')

@section('page-content')
    <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Module</h4>
                        <div class="card-tools">
                            <!-- <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-angle-down"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-times"></span></button> -->
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
                        <a class="btn btn-secondary" href="{{url('module/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br>
                    <div class="table-responsive table-hover table-sales">
                        <table class="table table-datatable" id="printable-area">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>Module Name</th>
                                    <th class="action-buttons">Actions</th>
                                </tr>
                            </thead>
                            <?php $count=1; ?>
                            @if(isset($datas))
                                @foreach($datas as $data)
                                    <tr>
                                        <td width="40px;">{{$count++}}</td>
                                        <td>{{$data->mod_name}}</td>
                                        <td class="action-buttons">
                                            <a href="{{url('module/delete')}}/{{$data->mod_id}}" id="delete-button" class="btn btn-secondary btn-sm"><i class="fas fa-trash-alt"></i></a>
                                            &nbsp;&nbsp;<a href="{{url('module/add')}}?purpose=edit&id={{$data->mod_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
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
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection