@extends('layout.layout')

@section('title', 'Scheme Performance')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
    <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Performance</h4>
                        <div class="card-tools">
                            <!-- <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-angle-down"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-times"></span></button> -->
                            <button type="button" class="btn btn-icon btn-round btn-warning" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-icon btn-round btn-info" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection
