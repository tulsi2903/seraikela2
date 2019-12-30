@extends('layout.layout')

@section('title', 'Department')

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
    .btn-warning {
    background: #673AB7!important;
    border-color: #673AB7!important;
    color: #fff!important;
}
</style>
@endsection

@section('page-content')

<?php  $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use ?>

    <div class="card">
        <div class="col-md-12">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Department Import Review</h4>
                    </div>
                </div>
            </div>
        <div class="card-body" style="margin-top: -1em;">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive table-hover table-sales">
                        <form action="{{url('department/ImportreviewSave')}}" method="POST"> <!-- for for edit, if inline edit form append then this form action/method will triggered -->
                        @csrf    
                            <table class="table" id="printable-area">
                                <thead style="background: #d6dcff;color: #000;">
                                    <tr>
                                        <th>#</th>
                                        <th>Department Name</th>
                                        <th>Organisation Name</th>
                                        <th>Status</th>  
                                        <th>Date</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count=1; ?>
                                    @if(isset($toReturn))
                                        @foreach($toReturn as $data)
                                        <tr>
                                            <td><input type="text" name="slno[]" class="form-control" value="{{$data['sl']}}"></td>
                                            <td><input type="text" name="department_name[]" class="form-control" value="{{$data['department_name']}}"></td>
                                            <td><input type="text" name="organization_name[]" class="form-control" value="{{$data['organization_name']}}"></td>
                                            <td><input type="text" name="status[]" class="form-control" value="{{$data['status']}}"></td>
                                            <td><input type="text" name="date[]" class="form-control" value="{{$data['date']}}"></td>
                                        </tr>
                                        <?php $count++; ?>
                                        @endforeach
                                    @endif
                                    @if($count==1)
                                        <tr>
                                            <td colspan="4"><center>No data to show</center></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="form-group row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">Import</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


@endsection