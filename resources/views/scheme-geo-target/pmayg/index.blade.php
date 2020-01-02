@extends('layout.layout')

@section('title', 'Scheme Geo Target')

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
                        <h4 class="card-title">Scheme Geo Target(PMAYG)</h4>
                        <div class="card-tools">
                            <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a>
                            <a class="btn btn-secondary" href="{{url('scheme-geo-target/pmayg/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                        
                        </div>
                    </div>
                </div>
            </div>
    
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <a class="btn btn-secondary" href="{{url('scheme-geo-target/add')}}" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a>
                    </div><br><br> -->
                    <div class="table-responsive table-hover table-sales">
                        <table class="table table-datatable" id="printable-area">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>Year</th>  
                                    <th>Subdivision</th>
                                    <th>Block</th>
                                    <th>Panchayat</th>
                                                                    
                                    
                                    <th>Target</th>
                                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <?php $count=1; ?>
                                @foreach($datas as $data)
                                        <tr>
                                            <td width="40px;">{{$count++}}</td>
                                            <td>{{$data->year_value}}</td>
                                            <td>{{$data->subdivision_name}}</td>
                                            <td>{{$data->block_name}}</td>
                                            <td>{{$data->panchayat_name}}</td> 
                                            <td>{{$data->target}}</td>
                                                                                    
                                            <td>
                                                <a href="{{url('scheme-geo-target/pmayg/delete')}}/{{$data->pmayg_target_id}}" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>
                                                &nbsp;&nbsp;<a href="{{url('scheme-geo-target/pmayg/add')}}?purpose=edit&id={{$data->pmayg_target_id}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                  @endforeach 
                            @if($count==1)
                                <tr>
                                    <td colspan="7"><center>No data to shown</center></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection
