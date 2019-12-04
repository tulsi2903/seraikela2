@extends('layout.layout')

@section('page-style')
    <style>
        hr.new2 {
            border-top: 1px dashed #000;
            margin-top: -13px;
        }
        .text-muted {
        color: #ffffff!important;
        font-weight: 700;
        }
        #card-detail1{
            width: 29%; height:35%; float:left; margin-left:2%;    background: #3F51B5;

        }
        #card-detail2{
            width: 29%;
            height:35%;
            float:left;
            margin-left:2%;
            background:#b93a31;
        }
        #card-detail3{
            width: 29%; height:35%; float:left; margin-left:2%;background: #8BC34A;
        }
        #card-detail4{
            width: 29%; height:35%; float:left; margin-left:2%;     background: #c37605;
        }
        #card-detail5{
            width: 29%; height:35%; float:left; margin-left:2%; background: #2196F3;
        }
        #card-detail6{
            width: 29%; height:35%; float:left; margin-left:2%;     background: #78038c;
        }
        .progress {
        -webkit-box-shadow: none !important;
        background-color: #f5f5f5;
        box-shadow: none !important;
        height: 10px;
        margin-bottom: 18px;
        overflow: hidden;
        }
        .card-title {
        margin: 0;
        color: #FFEB3B;
        font-size: 22px;
        font-weight: 400;
        line-height: 1.6;
        text-align: center;
        }
        .card-category {
            margin-top: 8px;
            font-size: 16px;
            color: #FFEB3B;
            margin-bottom: -4px;
            word-break: normal;
        }
        .bg-primary-gradient {
            background: #1572e8!important;
            background: -webkit-linear-gradient(legacy-direction(-45deg), #06418e, #1572e8)!important;
            background: linear-gradient(-45deg, #673AB7, #00BCD4)!important;
            margin-top: -22px;
            height: 151px;
        }
        .btn-border.btn-white {
        color: #fff!important;
        border: 1px solid #fff!important;
        font-weight: 600;
        }
        .card-round{
            background-image: linear-gradient(#3b4c75, #5269a2, #7292e2);

        }
        #map{
            background: #fff;
            padding: 0px;
            height: 339px;
            border-top: 2px solid #627dc2;
        }
    </style>
@endsection

@section('page-content')
        <div class="panel-header" style="background: #7292e2;">
            <div class="page-inner py-5"style="margin-top: -15px;">
                <div class="d-flex align-items-right align-items-md-center flex-column flex-md-row">
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="#" class="btn btn-white btn-border btn-round mr-2"><i class="icon-directions"></i> &nbsp;My District</a>
                        <a href="{{url('scheme')}}" class="btn btn-white btn-border btn-round mr-2">Scheme</a>
                        <a href="{{url('asset')}}" class="btn btn-white btn-border btn-round mr-2">Asset</a>
                        <a href="{{url('review')}}" class="btn btn-white btn-border btn-round mr-2">Review</a>
                        <a href="#" class="btn btn-white btn-border btn-round mr-2">Report</a>

                        <button type="button" class="btn btn-icon btn-round btn-default"><i class="fa fa-envelope" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-icon btn-round btn-default"><i class="fa fa-print" aria-hidden="true"></i></button>

                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row mt--2" style="width:120%;">
                <div class="col-sm-2">
                    <div class="card card-stats card card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-hotel"style="color: aliceblue;font-size: 35px;"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Subdivision</p>
                                        <h4 class="card-title">02</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card card-stats card card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                    <i class="fas fa-th"style="color: aliceblue;font-size: 35px;"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Blocks</p>
                                        <h4 class="card-title">09</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card card-stats card card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-university"style="color: aliceblue;font-size: 35px;"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Panchayat</p>
                                        <h4 class="card-title">132</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card card-stats card card-round">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-store-alt"style="color: aliceblue;font-size: 35px;"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Village</p>
                                        <h4 class="card-title">1148</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card card-stats card card-round">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-layer-group"style="color: aliceblue;font-size: 35px;"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Asset</p>
                                        <h4 class="card-title">100</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div>
        <hr class="new2"><br>
        <div class="row">
            <div class="col-5" style="margin-right: -40px;">
                <div style="margin-top:-11px;">
                    <div class="card" id="card-detail1">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">6%</div>
                            <div class="h1 m-0"><i class="fa fa-heartbeat" style="font-size:35px; color: #fff;"></i></div>
                            <div class="text-muted mb-3">Health</div>
                        </div>
                    </div>

                    <div class="card" id="card-detail2">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">6%</div>
                            <i class="fas fa-graduation-cap" style="font-size:35px; color: #fff;"></i>
                            <div class="text-muted mb-3">Education</div>
                        </div>
                    </div>

                    <div class="card" id="card-detail3">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">6%</div>
                            <div class="h1 m-0"><i class="fas fa-leaf" style="font-size:35px; color: #fff;"></i></div>
                            <div class="text-muted mb-3">Agriculture</div>
                        </div>
                    </div>

                    <div class="card" id="card-detail4">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">6%</div>
                            <div class="h1 m-0"><i class="fas fa-hands" style="font-size:35px; color: #fff;"></i></div>
                            <div class="text-muted mb-3">Welfare</div>
                        </div>
                    </div>

                    <div class="card" id="card-detail5">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">6%</div>
                            <div class="h1 m-0"><i class="fas fa-oil-can"style="font-size:35px; color: #fff;"></i></div>
                            <div class="text-muted mb-3">Drinking Water</div>
                        </div>
                    </div>

                    <div class="card" id="card-detail6">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">6%</div>
                            <div class="h1 m-0"><i class="fas fa-user-lock" style="font-size:35px; color: #fff;"></i></div>
                            <div class="text-muted mb-3">Social Security</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7" style="margin-top: -24px;">
                <div class="card" style="border-top:2px solid #000;width: 104%;border-radius: 0px;">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist" style="margin-top: -15px;">
                            <li class="nav-item"><a class="nav-link active" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd" role="tab" aria-controls="pills-home-nobd" aria-selected="true">Scheme Performance (monthly)</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false">Department Performance (monthly)</a></li>
                        </ul>
                        <br><hr class="new2">
                        <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                            <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">                                    
                                <div class="m-b-15">
                                    <h5>Drinking Water<span class="pull-right">60%</span></h5>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-info w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="m-b-15">
                                    <h5>Agruculture <span class="pull-right">15%</span></h5>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="m-b-15">
                                    <h5>Education<span class="pull-right">60%</span></h5>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-danger w-50" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="m-b-15">
                                    <h5>Social Security<span class="pull-right">25%</span></h5>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-secondary w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="new2">
        <div class="row">
            <div class="col-md-8">
                <div class="card-body" style="background: #fff;">
                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                </div>
            </div>
            <div class="col-md-4">
                    <div class="card-body" id="map">
                        <div class="col-md-10 ml-auto mr-auto">
                            <div class="mapcontainer">
                                <div id="map-example" class="vmap" style="height: 400px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light1", // "light1", "light2", "dark1", "dark2"
            title:{
                text: ""
            },
            data: [{
                type: "column", //change type to bar, line, area, pie, etc
                //indexLabel: "{y}", //Shows y value on all Data Points
                indexLabelFontColor: "#5A5757",
                indexLabelPlacement: "outside",
                dataPoints: [
                    { x: 10, y: 71 },
                    { x: 20, y: 55 },
                    { x: 30, y: 50 },
                    { x: 40, y: 65 },
                    { x: 50, y: 92, indexLabel: "Highest" },
                    { x: 60, y: 68 },
                    { x: 70, y: 38 },
                    { x: 80, y: 71 },
                    { x: 90, y: 54 },
                    { x: 100, y: 60 },
                    { x: 110, y: 36 },
                    { x: 120, y: 49 },
                    { x: 130, y: 21, indexLabel: "Lowest" }
                ]
            }]
        });
        chart.render();

        }
    </script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
@ensection
   