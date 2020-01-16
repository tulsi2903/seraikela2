@extends('layout.layout')

@section('title', 'scheme-performance')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <!-- <div class="card"> -->
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Import From Excel</h4>
                        <div class="card-tools">
                        <a href="{{url('asset-numbers')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('asset-numbers/saveimporttoExcel')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group">
                            
                            <label for="dept_name">File to import<span style="color:red;margin-left:5px;">*</span></label>
                            <span>[Maximum no. of entries that can be imported at a time is 250]</span>
                            <input type="file" name="excel_for_asset_number" id="excel_for_asset_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection