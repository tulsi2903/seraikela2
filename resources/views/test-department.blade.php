@extends('layout.layout')

@section('title', 'Department')

@section('page-style')
    <style>
        .card-title {
            margin: 0;
            color: #147785;
            font-size: 20px;
            font-weight: 400;
            line-height: 1.6;
            margin-left: 11px;
        }
        #show-toggle1 {
        padding: 5px;
        text-align: center;
        background-color: #ffffff;
        margin-bottom: 7px;
        }
        
        #show-toggle1{
            padding: 6px;
            display: none;
        }
        .btn-secondary {
            background: #6861ce!important;
            border-color: #6861ce!important;
            margin-top: 10px;
        }
        .card-stats .icon-big {
        width: 140%;
        height: 140%;
        font-size: 2.2em;
        min-height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 4px 4px 11px 2px #909090;
        }
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
        .table td, .table th {
        font-size: 14px;
        border-top-width: 0;
        border-bottom: 1px solid;
        border-color: #888888!important;
        padding: 0 23px!important;
        height: 40px;
        vertical-align: middle!important;
        color: #000;
        }
        .card, .card-light {
        border-radius: 5px;
        background-color: #fff;
        margin-bottom: 30px;
        -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        border: 0;
        }
        hr.new2 {
        border-top: 1px dashed #000;
        }
    </style>
@endsection

@section('page-content')
    <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Department</h4>
                        <div class="card-tools">
                            <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-angle-down"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-times"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div style="float:left;margin-top: -12px;">
                        <button type="button" class="btn btn-icon btn-round btn-warning"><i class="fa fa-envelope" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-icon btn-round btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>
                    </div>
                    <div style="display: -webkit-box; float:right;margin-top: -22px;">
                        <div class="form-group">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-icon-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>
                        <div id="toggle1">
                            <button class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</button>
                        </div>
                    </div><br><br>
                    <div id="show-toggle1" class="collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" style="float:left;">Department Name</label>
                                    <input type="text" id="" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""style="float:left;">Discription</label>
                                    <input type="text" id="" value="" class="form-control">
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-info" style="margin-top: 2em;float: left;">save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table class="table table-hover">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                    <th></th>
                                    <th>Department Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fa fa-heartbeat" style="font-size:25px; color: #03A9F4;"></i></td>
                                    <td>Health</td>
                                    <td class="text-left">Health Department</td>
                                    <td class="text-left"><button type="button" class="btn btn-icon btn-success btn-round btn-xs"><i class="fa fa-check"></i></button></td>
                                    <td class="text-left">
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-icon btn-primary btn-round btn-xs" data-toggle="dropdown"><i class="fa fa-plus"></i></button>
                                            <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <li>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-heartbeat" style="font-size:25px; color: #03A9F4;"></i></td>
                                    <td>Health</td>
                                    <td class="text-left">Health Department</td>
                                    <td class="text-left"><button type="button" class="btn btn-icon btn-success btn-round btn-xs"><i class="fa fa-check"></i></button></td>
                                    <td class="text-left">
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-icon btn-primary btn-round btn-xs" data-toggle="dropdown"><i class="fa fa-plus"></i></button>
                                            <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <li>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-heartbeat" style="font-size:25px; color: #03A9F4;"></i></td>
                                    <td>Health</td>
                                    <td class="text-left">Health Department</td>
                                    <td class="text-left"><button type="button" class="btn btn-icon btn-success btn-round btn-xs"><i class="fa fa-check"></i></button></td>
                                    <td class="text-left">
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-icon btn-primary btn-round btn-xs" data-toggle="dropdown"><i class="fa fa-plus"></i></button>
                                            <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <li>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection