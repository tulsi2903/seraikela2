@extends('layout.layout')

@section('title', 'Schemes Details')

@section('page-style')
<style>
.row-card-no-pd {
    border-radius: 5px;
    margin-left: 0;
    margin-right: 0;
    background: linear-gradient(to top, #cedcff, #ffffff 50%, #ffffff, #ffffff 75%);
    margin-bottom: 1px;
    padding-top: 0px;
    padding-bottom: 15px;
    position: relative;
    -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
    -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
    box-shadow: 2px 6px 9px #7f7f7f;
}

hr.new2 {
    border-top: 1px dashed #000;
}
</style>
@endsection

@section('page-content')

<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">{{$phrase->scheme_detail}} </h4>
                <div class="card-tools">
                    <a href="{{url('scheme-structure')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;{{$phrase->back}}</a>
                </div>
            </div>
        </div>
    </div>
    <!-----------------------------------------start of Scheme Detail Form------------------------------------------>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div style="display: inline-block; width: 150px; float: left; ">
                    <img src="{{url($data->scheme_logo)}}" style="max-width: 100%;">
                </div>
                <div style="display: inline-block; padding-left: 20px;">
                    <h2 style="color: black;">{{$data->scheme_name}} ({{$data->scheme_short_name}})</h2>
                    <p>
                        <b>{{$phrase->scheme_type}}:</b> {{$data->sch_type_name}}
                        <br/><b>{{$phrase->department}}:</b> {{$data->dept_name}}
                        <br/><b>{{$phrase->asset}}:</b> {{$data->scheme_asset_name}}
                        <br/><b>{{$phrase->sts}}:</b> <?php if($data->status=="1"){
                            ?><i class="fas fa-check-circle text-success"></i>&nbsp;&nbsp;Active<?php
                        } else{
                            ?><i class="fas fa-dot-circle text-dark"></i>&nbsp;&nbsp;Inactive<?php
                        } ?>
                        <br/><b>{{$phrase->spans_across_borders}}</b> <?php if($data->spans_across_borders=="1"){
                            ?>&nbsp;&nbsp;<i class="fas fa-check-circle text-success"></i><?php
                        } else{
                            ?>&nbsp;&nbsp;<i class="fas fa-dot-circle text-dark"></i><?php
                        } ?>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-12">
                <h4 style="color: black;">{{$phrase->description}}</h4 style="color: black;">
                <p>{!! nl2br(e($data->description)) !!}</p>
            </div>
            <hr/>
        </div>
        <hr/>
        <div class="row">
            <div class="col-3">
                <h4 style="color: black;">{{$phrase->map_marker}}</h4 style="color: black;">
                    @if($data->scheme_map_marker)
                        <img src="{{url($data->scheme_map_marker)}}" style="max-width: 80px;">
                    @endif
            </div>
            <div class="col-3">
                <h4 style="color: black;">{{$phrase->attachment}}</h4 style="color: black;">
                    @if($data->attachment)
                        <a href="{{url($data->attachment)}}" target="_blank"><i class="fas fa-file-download"></i><b>&nbsp;&nbsp;Click here to show/ download</b></a>
                    @endif
            </div>
        </div>
    </div>
</div>


@endsection