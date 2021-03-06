@extends('layout.layout')

@section('title', 'Resources')

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
                <h4 class="card-title">Sub Resources Number</h4>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form action="{{url('asset-numbers/saveChilddata')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="table-responsive table-hover table-sales">
                        <table class="table" id="printable-area">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Previous Value</th>
                                    <th>Current Value</th>
                                </tr>
                            </thead>
                            @if(count($childdatas) > 0)
                            <?php $count=1; ?>
                            @if(isset($childdatas))
                            @foreach($childdatas as $data)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$data->asset_name}}<input type="text" class="form-control" name="child_asset_id[]" value="{{$data->asset_id}}" hidden></td>
                                <td><input type="text" class="form-control" name="previous_value_child[]" value="{{$data->current_value ?? 0}}" readonly></td>
                                <td><input type="text" class="form-control" name="current_value_child[]" id="current_value_child" value="{{$data->current_value}}" autocomplete="off"></td>

                                <input type="text" class="form-control" name="asset_numbers_child_id[]" value="{{$data->asset_numbers_id}}" hidden>
                            </tr>
                            @endforeach
                            @endif
                            @if($count==1)
                            <tr>
                                <td colspan="8">
                                    <center>No data to shown</center>
                                </td>
                            </tr>
                            @endif

                            @else

                            <?php $count=1; ?>
                            @if(isset($childdatasValue))
                            @foreach($childdatasValue as $datas)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$datas->asset_name}}<input type="text" class="form-control" name="child_asset_id[]" value="{{$datas->asset_id}}" hidden></td>
                                <td><input type="text" class="form-control" name="previous_value_child[]" value="{{$datas->current_value ?? 0}}" readonly></td>
                                <td><input type="text" class="form-control" name="current_value_child[]" id="current_value_child" value="{{$datas->current_value ?? 0}}" autocomplete="off"></td>

                                <input type="text" class="form-control" name="asset_numbers_child_id[]" value="" hidden>
                            </tr>
                            @endforeach
                            @endif
                            @if($count==1)
                            <tr>
                                <td colspan="8">
                                    <center>No data to shown</center>
                                </td>
                            </tr>
                            @endif

                            @endif
                        </table>
                        <input type="text" class="form-control" name="geo_child_id" value="{{$geo_child_id}}" hidden>
                        <input type="text" class="form-control" name="year_child_id" value="{{$year_child_id}}" hidden>
                        <input type="text" class="form-control" name="main_asset_id" value="{{$hidden_input_id}}" hidden>
                        <input type="text" class="form-control" name="geo_location_id" value="{{$geo_location_id}}" hidden>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <hr />
                                <button type="submit" class="btn btn-secondary">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                                &nbsp;&nbsp;<a href="{{url('asset-numbers/add')}}?purpose=edit&id={{$hidden_input_id}}"><button type="button" class="btn btn-dark">Cancel&nbsp;&nbsp;<i class="fas fa-times"></i></button></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>




@endsection