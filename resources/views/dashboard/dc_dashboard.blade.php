@extends('layout.layout')

@section('title', 'My District')

@section('page-style')
<style>
    .text-muted {
        color: #ffffff !important;
        font-weight: 700;
    }

    #card-detail1 {

        background: #2196F3;

    }

    #card-detail2 {
        background: #b93a31;
    }

    #card-detail3 {
        background: #8BC34A;
    }

    #card-detail4 {
        background: #c37605;
    }

    #card-detail5 {
        background: #2196F3;
    }

    #card-detail6 {
        background: #78038c;
    }

    #card-detail7 {
        background: #508c0a;
    }

    #card-detail8 {
        background: #795548;
    }

    #card-detail9 {
        background: #00BCD4;
    }

    #card-detail10 {
        background: #c91854;
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
        color: #fff !important;
        border: 1px solid #fff !important;
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

    .card,
    .card-light {
        border-radius: 0px;
        background-color: #3F51B5;
        margin-bottom: 0px;
        -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        box-shadow: 4px 4px 10px -6px #000000cf;
        width: 100%;
        min-height: 0px;

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
    margin-top: -17px;
}
    .col-sm-2 {
        position: relative;
        width: 100%;
        padding-right: 5px;
        padding-left: 5px;
    }
    
    svg text {
        fill: black;
        font-size: 25px;
        cursor: pointer;
    }

    svg g path {
    fill: #e0e0e0;
    stroke: #525252;
    stroke-linejoin: round;
    stroke-width: 2px;
    transition: all 0.1s;
}

    /* svg g:hover path {
        fill: #0466bf;
        cursor: pointer;
    } */

    svg g path.active {
        fill: #bee8c9;
        stroke: #148532;
        stroke-width: 2px;
    }


    #scheme-performane-table th, #scheme-performane-table td{
        border: 1px solid black;
        padding: 5px !important;
    }
    #scheme-performane-table tr td:first-child, #scheme-performane-table td a{
        font-weight: bold;
    }

</style>


@endsection

@section('page-content')
<!-- <div id="particles-js"></div> -->



<div class="row" style="margin-left: 0px;">
    <div class="col-sm-2">
        <div class="card-stats card card-round" style="min-height:0px; border-top: 0px solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-hotel" style="color: aliceblue;font-size: 25px;"></i>
                        </div>
                    </div>

                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">{{$phrase->sub_divisin}}</p>
                            <h4 class="card-title">{{$subdivision_count}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card-stats card card-round" style="min-height:0px; border-top: 0px solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-th" style="color: aliceblue;font-size: 25px;"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">{{$phrase->block}}</p>
                            <h4 class="card-title">{{$block_count}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card-stats card card-round" style="min-height:0px; border-top: 0px solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-university" style="color: aliceblue;font-size: 25px;"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">{{$phrase->panchayat}}</p>
                            <h4 class="card-title">{{$panchayat_count}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card-stats card card-round" style="min-height:0px; border-top: 0px solid">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-store-alt" style="color: aliceblue;font-size: 25px;"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">{{$phrase->village}}</p>
                            <h4 class="card-title">1148</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card-stats card card-round" style="min-height:0px; border-top: 0px solid">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-layer-group" style="color: aliceblue;font-size: 25px;"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">{{$phrase->resource}}</p>
                            <h4 class="card-title">{{$asset_count}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card-stats card card-round" style="min-height:0px; border-top: 0px solid">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-layer-group" style="color: aliceblue;font-size: 25px;"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">{{$phrase->scheme}}</p>
                            <h4 class="card-title">{{$scheme_count}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end of first row-->
<div class="row">
    <div class="col-md-12" style="margin-top:1em;">
        <div class="card">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 style="color: #000;font-size: 1.2em;float: left;padding: 1em;">Scheme Performance <!-- (monthly) --></h4>
            </div>
            <hr style="margin-top: -12px;">
            <div class="card-body">
                <div style="max-height: 520px; padding-bottom: 15px; overflow: auto;">
                    @if($dashboard_scheme_performance_has_datas=="success")
                        <table class="table" id="scheme-performane-table">
                            <thead style="background: #d6dcff;color: #000;">
                                <tr>
                                <?php 
                                for($i=0; $i<count($performance_table_heading_1); $i++)
                                {
                                    if($i!=0){
                                        echo "<th colspan='3' style='text-align: center'>";
                                        $value = explode("::", $performance_table_heading_1[$i]);
                                        echo "<img src='".$value[1]."' style='height: 35px;margin-right: 15px;'>".$value[0]."</th>";
                                    }
                                    else{
                                        echo "<th style='text-align: center'></th>";
                                    }
                                } 
                                ?>
                                </tr>
                                <tr>
                                <?php 
                                for($i=0; $i<count($performance_table_heading_2); $i++)
                                {
                                    echo "<th>".$performance_table_heading_2[$i]."</th>";
                                } 
                                ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($performance_table_datas as $key=>$performance_table_data){
                                    echo "<tr>";
                                        foreach($performance_table_data as $key_2=>$value_before){
                                            $value = explode(":", $value_before);
                                            if(count($value)==2)
                                            {
                                                $value[1] = (int)$value[1];
                                                if($value[1]==0){
                                                    echo "<td style='background: #ffcfcf;'>";
                                                }
                                                else if($value[1]<30){
                                                    echo "<td style='background: #ff9999;'>";
                                                }
                                                else if($value[1]<70){
                                                    echo "<td style='background: #ffcfcf;'>";
                                                }
                                                else if($value[1]==100){
                                                    echo "<td style='background: #2cbd36;'>";
                                                }
                                                else {
                                                    echo "<td style='background: #87f387;'>";
                                                }   
                                            }
                                            else{
                                                echo "<td>";
                                            }

                                            if($value[0]){
                                                echo $value[0];
                                            }
                                            else{
                                                echo "0";
                                            }

                                            echo "</td>";
                                        }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    @else
                    <h4><span style="color: #09c521;"><i class="fas fa-info"></i></span>&nbsp;&nbsp;{{$dashboard_scheme_performance_has_datas}}</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-6" style="margin-top:1em;">
        <div class="card">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 style="color: #000;font-size: 1.2em;float: left;padding: 1em;">Department Performance (monthly)</h4>
            </div>
            <hr style="margin-top: -12px;">
            <div class="card-body">
                <h2><strong><span style="color: #09c521;"><i class="fas fa-wrench"></i></span>&nbsp;&nbsp;Under Development</strong></h2>
            </div> 
        </div>
    </div> -->
</div>
<!--enf of row-->




<!--           
                <div class="row" style="margin-top: 1em; width:122%;"> 
                    <div class="col-md-1">
                        <div id="card-detail1">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$health_scheme_count}}</div>
                                <div class="h1 m-0"><i class="fa fa-heartbeat" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Health</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div id="card-detail2">
                            <div class="card-body p-3 text-center">
                                <center><div class="text-right">{{$land_revenue_count}}</div></center>
                                <i class="fas fa-graduation-cap" style="font-size:35px; color: #fff;"></i>
                                <div class="text-muted mb-3">Land and Revenue</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div id="card-detail3">
                            <div class="card-body p-3 text-center">
                                <center><div class="text-right">{{$welfare_count}}</div></center>
                                <div class="h1 m-0"><i class="fas fa-hands" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Welfare</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div id="card-detail4">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$education_count}}</div>
                                <div class="h1 m-0"><i class="fas fa-graduation-cap" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Education</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div id="card-detail5">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$land_acquisition_count}}</div>
                                <div class="h1 m-0"><i class="fas fa-oil-can"style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Land Acquisition</div>
                            </div>
                        </div>
                    </div>
             
               
                    <div class="col-md-1">
                        <div id="card-detail6">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$election_count}}</div>
                                <div class="h1 m-0"><i class="fas fa-box" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Election</div>
                            </div>
                        </div> 
                    </div>
    
                    <div class="col-md-1">
                        <div id="card-detail7">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$agriculture_count}}</div>
                                <div class="h1 m-0"><i class="fas fa-leaf" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Agriculture</div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-1">
                        <div id="card-detail8">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$social_welfare_count}}</div>
                                <div class="h1 m-0"><i class="fas fa-hands" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Social Welfare</div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-1">
                        <div id="card-detail9">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$drinking_water_and_sanitation_count}}</div>
                                <div class="h1 m-0"><i class="fas fa-oil-can" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Drinking Water and Sanitation</div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-1">
                        <div id="card-detail10">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">{{$social_security_scheme_count}}</div>
                                <div class="h1 m-0"><i class="fas fa-user-lock" style="font-size:35px; color: #fff;"></i></div>
                                <div class="text-muted mb-3">Social Security Scheme</div>
                            </div>
                        </div>
                    </div>
                </div> -->


<!-- content-starts-here -->


<div class="col-md-12">
    <h4 style="color: #000;
    font-size: 1.2em;
    float: left;
    padding: 1em;
    width: 100%;
    background: #b3c4f1;
    margin-top: 1em;
    border-left: 3px solid #212f51;">{{$phrase->no_of_scheme}}</h4>
</div>

<div class="row" style="width: 118%;
margin-left: 1em;">
   
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$health_scheme_count}}</div></center>
            <div class="focus-layout" id="card-detail1">
                <i class="fa fa-heartbeat" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->health}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$education_count}}</div></center>
            <div class="focus-layout" id="card-detail2">
                <i class="fas fa-graduation-cap" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->education}} {{$education_count}}</h4>
            </div>
        </div>

    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$land_revenue_count}}</div></center>
            <div class="focus-layout" id="card-detail3">
                <i class="fas fa-graduation-cap" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->land_revenue}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$welfare_count}}</div></center>
            <div class="focus-layout" id="card-detail4">
                <i class="fas fa-hands" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->welfare}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$land_acquisition_count}}</div></center>
            <div class="focus-layout" id="card-detail5">
                <i class="fas fa-oil-can" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->landAcquisition}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$election_count}}</div></center>
            <div class="focus-layout" id="card-detail6">
                <i class="fas fa-box" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->election}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$agriculture_count}}</div></center>
            <div class="focus-layout" id="card-detail7">
                <i class="fas fa-leaf" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->agriculture}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$social_welfare_count}}</div></center>
            <div class="focus-layout" id="card-detail8">
                <i class="fas fa-hands" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->socialWelfare}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$drinking_water_and_sanitation_count}}</div></center>
            <div class="focus-layout" id="card-detail9">
                <i class="fas fa-oil-can" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->drinkingWaterAndSanitation}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-1 focus-grid" style="padding-right: 5px;padding-left: 5px;">
        <div class="focus-border">
            <center><div class="text-right">{{$social_security_scheme_count}}</div></center>
            <div class="focus-layout" id="card-detail10">
                <i class="fas fa-user-lock" style="font-size:35px; color: #fff;margin-top: 0.5em;"></i>
                <h4 class="clrchg">{{$phrase->socialSecurityScheme}}</h4>
            </div>
        </div>
    </div>
</div><br>

<div class="row">
    <div class="col-md-6">
        <div class="card" style="min-height: 600px;">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 style="color: #000;font-size: 1.2em;float: left;padding: 1em;">{{$phrase->no_of_resources}}  </h4>
                <hr style="margin-top: 4em;border-top: 1px dashed #717070;">

            </div>
      
            <canvas id="dashbaord-asset-pie-chart" width="800" height="450"></canvas>
       
    </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="min-height: 600px;">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 style="color: #000;font-size: 1.2em;float: left;padding: 1em;">{{$phrase->map_of_saraikela}}</h4>
                <hr style="margin-top: 4em;border-top: 1px dashed #717070;">


            </div>
        <div class="card-body">
            <!-- <div class="col-md-10 ml-auto mr-auto">
                <div class="mapcontainer">
                    <div id="map-example" class="vmap" style="height: 400px"></div>
                </div>
            </div> -->
            <div style="padding: 20px;text-align:center; max-width:550px;margin:auto;margin-top: -47px;">
                <svg viewBox="0 0 739 752" stroke-linecap="round" stroke-linejoin="round">
                    <g>
                        <path id="kukru" data-info="Kukru" data-geo-id="12" d="M385.25,12.875l2.5-3.5c10.143,0,16.727,10.909,27.5,14.5,14.572,4.857,21.2-8,33-8,0,4.333,10,22.281,10,26.5l7.5-.5c0-11.443,29.072-7,38-7l1.5,7c7.965,0,14.924-2.5,23-2.5,15.382,15.382,3.552,42.552,19.5,58.5,7.536,0,19,3.677,19,12.5,0,24.964-48.821,37.44-70,44.5l-28-1.5-30.5-20c-23.023-23.023-40.467-51.451-58.5-78.5,0-16.451,6-27.765,6-42.5Z" />
                        <text x="460" y="100">{{$phrase->kukru}}</text>
                    </g>
                    <g>
                        <path id="nimdih" data-info="Nimdih" data-geo-id="11" d="M531,120c-5.72,0-9.271,3-13,3,0,4.808-1.759,11.759-5,15-14.134,0-26.771,20.229-15,32,5.444,0,12,10.115,12,14,14.315,0,28.332-1,43-1,0,9.228,5,17.958,5,32q-1,2-2,4l-2,1c0,14.323-20,10.049-20,24,4.862,0,21.615,5.615,25,9l49,2,38,16c0,1.916,5.813,23,6,23,10.1,10.1,24.239,1.761,30-4,0-19.646-11-34.7-11-55l6-6,22-3,5-9,18,1,1-1V199l-3-2-29,3c0-5.842-4-17.3-4-27H676l-18,2c0-14.773-9.949-30-25-30V130l-14-7c0-4.665,1.624-28-2-28l-15-1-24-2c-5.453,5.453-22.628,20-30,20v13H537l-7-6Z" />
                        <text x="565" y="180">{{$phrase->nimdih}}</text>
                    </g>
                    <g>
                        <path id="ichagarh" data-info="Ichagarh" data-geo-id="10" d="M384,10c-48.073,24.036-80,34.676-80,99,7.122,0,10,16.27,10,23-2.116,0-10,22.557-10,27,10.544,0,8,18.614,8,28,1.96,0,5,3.273,5,5,19.852,0,20,33.447,20,49,5.777,0,12.415,8,29,8,0-.986,30.854-17.854,36-23l26-3c35.158-23.438,75-34.627,75-82h-2l-1-1c-9.368,0-43-7.626-43-14l-5-3c0-13.478-17.264-62-31-62L406,41c0-9.759-11.636-30-21-30Z" />
                        <text x="330" y="120">{{$phrase->ichagrah}}</text>
                    </g>
                    <g>
                        <path id="chandil" data-info="Chandil" data-geo-id="9" d="M342,248v-5c0-5.379,11-.411,11-5,5.214-5.214,6-31.11,6-39l-6-1q-0.5-5-1-10l14-1,3,12c10.575,0,52.608-5.608,58-11l51-1c0-7.787,1.5-14.5,6-19,11.723,0,33,.362,33,12,35.036,0,50-1.864,55,27-5.49,0-23,22.766-23,29,15.333-4.333,8.667,3.333,10,4,3.935,0,6,8.738,6,11,12.332,0,25.668-4,38-4,7.589,7.589,32.153,15,44,15,0,6.139,5.122,33,9,33,0,3.131,2.253,2,5,2,4.666-.667,13.334-8.333,18-9,0,5.783,11.584,17,17,17,0,10.718-10.564,38-19,38,0,11.8-2,26.321-2,35l-7,1c0-4.657-24.542-18.458-27-16l-2,3-3,16-4,3H622c0-11.494-22.706-24-35-24-26.187-26.187-41.229-48-91-48-22.248,0-68,7.631-68,11-14.342,0-69,1.6-69-8l-1-4c-0.986,0-10-22.315-10-27,0-2.4,7.035-12,10-12V253c-5.322,0-14-2.944-14-9" />
                        <text x="410" y="248">{{$phrase->chandil}}</text>
                    </g>
                    <g>
                        <path id="rajnagar" data-info="Rajnagar" data-geo-id="7" d="M370,573l-3,15c0,6.8,26,9.566,26,18,5.316,0-3,18-3,18l-1,1c0,10.425-29,14.446-29,26l1,2c2.145,0,19.429,11,28,11l4,18c-3.986,0-7,9.877-7,15,29.237,0,37.186,40,68,40,0-2.675,4.529-23,7-23,0-8.062,57-19,57-32,0-6.6-8-6.479-8-11q0.5-5.5,1-11l25-12c0,6.7,13.337,8,20,8,0-8.29,50.178-48,51-48l15-19c4.567-4.567,9.783-22,19-22l8-21V514l-10-27c-8.1,0-64-12.729-64-21l-28-9c-25.9,0-50.26,18-78,18l-27,23-7,15c0,21.874-15.533,22.766-34,32l-16,8-11,9-3,13Z" />
                        <text x="450" y="620">{{$phrase->rajnagar}}</text>
                    </g>
                    <g>
                        <path id="seraikela" data-info="Seraikela" data-geo-id="4" d="M373,592c-1.666-1.333-.334-2.667-2-4,0-3,5.5-2.5,0-6,4.917,1.25,9.583-3.25,5-3,5.344-5.344,11.289,2,17,2,6.484-6.484,6.059-4,17-4l2-6c0-4.46-5-2.939-5-6v-1l16,1c0,5.28,10.542,11,17,11l21-6c17.982,0-5-44.136-5-54-1.9,0-25-1.944-25-5q-0.5-3-1-6l1-1,9-1c0-6.812,5-14.416,5-24l-6-13,15-9c0-8.861,5.013-22.013,11-28l26-4,8-9c0-16.854,12-18.9,12-44q-0.5-3-1-6c0-17.029-15-26.757-15-42-2.115,0-11-7.6-11-10-18.182,0-36.04,1.51-54,6-17.753,4.438-33.317-5-50-5,0,6.268-3,17.079-3,26,9.491,9.491,7,29.127,7,45l-9,20c-19.764,19.764-27,21.76-27,49-6.018,0-35,26.313-35,30l14,14c4.978,0,9,1.378,9-4,3.992,0,.714-11,6-11,4.016,0,11,2.976,11,7q0.5,4.5,1,9l-3,13c5.455,0,4,15.909,4,22l-6,16,2,3,10,6c0,14.8-6,22.489-6,32,5.618,0,11.213,4,18,4v-4Z" />
                        <text x="358" y="545">{{$phrase->seraikela}}</text>
                    </g>
                    <g>
                        <path id="gamharia" data-info="Gamharia" data-geo-id="6" d="M460,316q0.5,4,1,8l28,4c0,18.7-2,28.61-2,42,3.813,0,7,2.312,7,6,0,10.989-1.809,17.809-8,24,0,9.571,5,11.713,5,18l-5,4-6,1-9-5c-9.18,0-11.446,4-20,4v6l5,8c-7.492,0-11.452-5-17-5v7c0,2.675,6.616,7,9,7,0,7.195-12.586,20-18,20v3c4.356,4.356,11,3,11,10,0,5.454-13.643,23-17,23q1,5,2,10c6.539,0,13.429,6,24,6,0-1.24,10.13-6,13-6,4.924,0,4.431,6,11,6l3-1c0-6.027,3.178-21,10-21l8-1,4,6h-2c0,1.414-2.414,1-1,1,0,13.409,33.336-1,35-1l3-4,4-3c0-6.065,2-14.947,2-18,13.193,0,44,11.39,44,20,21.36,0,34.776,6,58,6,0-7.573,7-21.5,7-31-2.2,0-9-7.927-9-11,16.071,0,13-33.658,13-49-3.917,0-14-7-22-7,0-5.984-2-18.519-2-25-3.612,0-26-24-26-24l10-9q1-10.5,2-21l-14-20c-26.678,0-33.9-18-58-18l-7,10c-27.5,0-41.692,10-64,10l-11,6q-1,2.5-2,5Z" />
                        <text x="500" y="430">{{$phrase->gamharia}}</text>
                    </g>
                    <g>
                        <path id="kharsawan" data-info="Kharsawan" data-geo-id="5" d="M211,460c-8.768,2.192-22.249,1-28,1,0,7.155-6,11.432-6,20,15.478,0,15.331,27,18,27,0,7.834,25.212,10,35,10,0-12.564,25.536-11,37-11V497h5c4.188,0,10.414,3,16,3v-4c-1.474,0-6-3.248-6-5h27c0-7.13,22.689-14,31-14,0-4.361,7.21-11,13-11,4.682,0,14.056,3,22,3l6-4v-7l-13-8V432c30.186,0,45-37.857,45-65-17,0-5-22.746-5-30l-3-4-22-2c0-39.38-51-6.468-51-33l-7-6c-42.451,0-90,91.675-90,135-7.349,7.349-8.209,20.209-15,27l-1,2-3,2c-2.531,0-4-.836-4,2h-1Z" />
                        <text x="270" y="460">{{$phrase->kharsawan}}</text>
                    </g>
                    <g>
                        <path id="kuchai" data-info="Kuchai" data-geo-id="8" d="M13.414,393.733l1-23,6.5-1.5,0.5-12.5,7-4,1-20.5,4.5-1,3.5,2.5,8-12.5,12.5-2,1,16.5h11.5c4.392-4.392,27-31.768,27-35l-19-3.5c0-17.895,16.881-36,34.5-36l-1.5,10.5c0,19.94,39-2,39-2q-1.251-4.749-2.5-9.5l2.5-2,28,2c0-19.334,87-1.245,87,14,25.48,0,31.761,17,60.5,17v3.5l-9.5,8q0.5,6.5,1,13l11,1.5v3l-4,4.5c-6.569,0-38,6.59-38,12v0.5c6.627,0,8,10.779,8,16,2.685,0,11.625,4,15,4,0,3.346,4.6,20.5-4,20.5l-9,1v3l10,10q-0.25,1-.5,2l-11.5.5q-0.5,10-1,20l-1.5,1-5.5-1.5q-1.75-4-3.5-8c-4,0-4.772-2.728-7.5,0,0,4.662,4.5,5.944,4.5,8.5q-0.5,4.5-1,9h-2l-1-.5c0-4.5-8.447-15.5-12-15.5v1q0.249,2.25.5,4.5c0,9.69-1,18.925-1,30-3.019,0-6.5,7.29-6.5,11l6,3c0,4.867-6.1,5.6-9,8.5,0,30.1-34.624-2.5-45-2.5q0.5-5.75,1-11.5l-18.5-16.5c0-15.218,3.5-29.618,3.5-47.5-6.282,0-13.434-7-23-7,0-16.32-33-19.885-33-32.5l-17.5,2-17.5,25-41-6.5-8,9c-5.591,0-11.914,2.5-15,2.5l-5.5,12.5-20.5.5Z" />
                        <text x="175" y="360">{{$phrase->kuchai}}</text>
                    </g>
                </svg>
               
            </div>
        </div>
    </div>
</div>

<script>
    
    var dashboardAssetPieChart_labels = [];
    var dashboardAssetPieChart_data = [];
    
    $(document).ready(function(){
        $.ajax({
            url: "{{url('dashboard/asset_department_wise')}}",
            data: { 'ok': "ok" },
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function (data) {
                
            },
            error: function (xhr){
                alert("error" + xhr.status + "," + xhr.statusText);
            },
            success: function (data){
                console.log(data);
                for(var i=0; i<data.length; i++){
                    dashboardAssetPieChart_labels.push(data[i].dept_name);
                    dashboardAssetPieChart_data.push(data[i].total);
                }
                intialize_pie_chart();
            }
        });
    });

    // for dashbaord-asset-pie-chart
    function intialize_pie_chart(){
        var dashboardAssetPieChartId = document.getElementById('dashbaord-asset-pie-chart');
        var dashboardAssetPieChart = new Chart(dashboardAssetPieChartId, {
            type: 'pie',
            data: {
                labels: dashboardAssetPieChart_labels,
                datasets: [{
                    label: "Population (millions)",
                    backgroundColor: ["#A93226", "#AF7AC5","#2980B9","#1ABC9C","#F5B041","#5D6D7E","#F1948A","#21618C", "#F7DC6F", "##6C3483"],
                    data: dashboardAssetPieChart_data
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Resources'
                }
            }
        });
    }
</script>


<script>
    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light1", // "light1", "light2", "dark1", "dark2"
            title: {
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