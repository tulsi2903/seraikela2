@extends('layout.layout')

@section('title', 'DC DASHBOARD')

@section('page-style')
<style>
	.text-muted {
    color: #ffffff!important;
    font-weight: 700;
    }
    #card-detail1 {
    width: 100%;
    height: 100%;
    float: left;
    background: #2196F3;
    margin-left: 10px;
}
    #card-detail2{
        width: 100%;
        height:100%;
        float:left;
        background:#b93a31;
    }
    #card-detail3{
        width: 100%; height:100%; float:left;background: #8BC34A;
    }
    #card-detail4{
        width: 100%; height:100%; float:left;background: #c37605;
    }
    #card-detail5{
        width: 100%; height:100%; float:left; background: #2196F3;    margin-left: -14px;
    }
    #card-detail6{
        width:100%; height:100%; float:left; background: #78038c;margin-left: 10px;
    }
    #card-detail7{
        width:100%; height:100%; float:left;     background: #508c0a;
    }
    #card-detail8{
        width:100%; height:100%; float:left;     background: #795548;
    }
    #card-detail9{
        width:100%; height:100%; float:left;     background: #00BCD4;
    }
    #card-detail10{
        width:100%; height:100%; float:left;     background: #c91854; margin-left: -14px;
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

    .btn-border.btn-white {
    color: #fff!important;
    border: 1px solid #fff!important;
    font-weight: 600;
    }

    .card-stats .icon-big {
    width: 140%;
    height: 100%;
    font-size: 2.2em;
    min-height: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0px 0px 0px 0px #909090;
}
    .card-round {
        background: #6480c7;
    }
    .card, .card-light {
        border-radius: 0px;
        background-color: #3F51B5;
        margin-bottom: 0px;
        -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        box-shadow: 4px 4px 10px -6px #000000cf;
        width: 104%;
    }
    .text-right {
    text-align: center;
    color: #000;
    font-weight: 600;
    background: #f0f0f0;
    height: 30px;
    width: 30px;
    border-radius: 30px;
    margin-top: 0px;
    /* padding-top: 2px; */
    padding: 5px;
    padding-left: -7px;
    padding-right: 10px;
}
</style>
@endsection

@section('page-content')
<!-- <div id="particles-js"></div> -->


        <div class="row">
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
                                    <h4 class="card-title">{{$subdivision_count}}</h4>
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
                                    <h4 class="card-title">{{$block_count}}</h4>
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
                                    <h4 class="card-title">{{$panchayat_count}}</h4>
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
                                    <h4 class="card-title">{{$villages_count}}</h4>
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
                                    <h4 class="card-title">{{$asset_count}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                    <div class="card card-stats card card-round" style="width: 93%;height: 89px;">
                        <div class="card-body ">
                            <div class="row">
                                <ul class="pricing-content" style="color: #fff;margin-top: -4px;list-style-type: none;">
                                    <li><button class="btn btn-icon btn-success btn-round btn-xs"></button> &nbsp;&nbsp;Upto 70 to 100%</li>
                                    <li><button class="btn btn-icon btn-warning btn-round btn-xs"></button> &nbsp;&nbsp;Upto 50 to 70%</li>
                                    <li> <button class="btn btn-icon btn-danger btn-round btn-xs"></button> &nbsp;&nbsp;Upto 30 to 50%</li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <br><br>
        <div style="margin-top: -2em;">
            <div class="row" style="margin-left: 2px;">
    
                    <div class="col-7" style="background: currentColor;">
                        <div class="row" style="width: 126%; margin-left: -23px;margin-top: 14px;">
                            <div class="col-md-2">
                                <div class="card" id="card-detail1">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$health_scheme_count}}</div>
                                        <div class="h1 m-0"><i class="fa fa-heartbeat" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Health</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
        
                            <div class="col-md-2">
                                <div class="card" id="card-detail2">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$land_revenue_count}}</div>
                                        <i class="fas fa-graduation-cap" style="font-size:35px; color: #fff;"></i>
                                        <div class="text-muted mb-3">Land and Revenue</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
        
                            <div class="col-md-2">
                                <div class="card" id="card-detail3">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$welfare_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-hands" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Welfare</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
        
                            <div class="col-md-2">
                                <div class="card" id="card-detail4">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$education_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-graduation-cap" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Education</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
        
                            <div class="col-md-2">
                                <div class="card" id="card-detail5">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$land_acquisition_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-oil-can"style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Land Acquisition</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
                        </div><!--end of row-->
                        <br>
                        <div class="row" style="width: 126%; margin-left: -23px;">
                            <div class="col-md-2">
                                <div class="card" id="card-detail6">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$election_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-box" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Election</div>
                                    </div>
                                </div> 
                            </div><!--end of col-->
            
                            <div class="col-md-2">
                                <div class="card" id="card-detail7">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$agriculture_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-leaf" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Agriculture</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
            
                            <div class="col-md-2">
                                <div class="card" id="card-detail8">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$social_welfare_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-hands" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Social Welfare</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
            
                            <div class="col-md-2">
                                <div class="card" id="card-detail9">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$drinking_water_and_sanitation_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-oil-can" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Drinking Water and Sanitation</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
            
                            <div class="col-md-2">
                                <div class="card" id="card-detail10">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-right">{{$social_security_scheme_count}}</div>
                                        <div class="h1 m-0"><i class="fas fa-user-lock" style="font-size:35px; color: #fff;"></i></div>
                                        <div class="text-muted mb-3">Social Security Scheme</div>
                                    </div>
                                </div>
                            </div><!--end of col-->
                        </div><!--end of row-->
                    </div><!--enf of colmd7--> 



                <div class="col-md-5" style="margin-top:0px;">
                    <div class="card" style="border-top: 2px solid #617cbf; width:96%;border-radius: 0px;    margin-left: 10px;">
                        <div class="card-body" style="    background: #fff;">
                            <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist" style="margin-top: -15px;">
                                <li class="nav-item"><a class="nav-link active" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd" role="tab" aria-controls="pills-home-nobd" aria-selected="true">Scheme Performance (monthly)</a></li>
                                <li class="nav-item"><a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false">Department Performance (monthly)</a></li>
                            </ul>
                            <br><hr class="new2">
                            <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                                <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">                                    
                                    <div class="m-b-15">
                                    @foreach($get_schemes as $get_scheme)
                                        <h5>{{$get_scheme->scheme_name}}({{$get_scheme->scheme_short_name}})<span class="pull-right">60%</span></h5>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-info w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    @endforeach
                                    </div>
    
                                    <!-- <div class="m-b-15">
                                        <h5>Agriculture <span class="pull-right">15%</span></h5>
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
                                    </div> -->
                                </div>
                        <div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd"> 
                            <div class="m-b-15">
                                @foreach($departments as $department)
                                    <h5>{{$department->dept_name}}<span class="pull-right">60%</span></h5>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-info w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




    <!--department section-->

    </div>
</div>
<br>
    <div class="row" style="margin-bottom: -9em;">
        <div class="col-md-7">
            <div class="card-body" style="background: #fff;">
                <div id="chartContainer" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
        <div class="col-md-5">
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
  

@endsection
   