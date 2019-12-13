@extends('layout.layout')

@section('title', 'Asset Review')

@section('page-style')
    <style>
        .card, .card-light {
            border-radius: 5px;
            /* background-color: #fff; */
            margin-bottom: 30px;
            -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
            -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
            box-shadow: 2px 6px 6px #6f6e6e;
            border: 0;
            /* background-image: linear-gradient(to right, white , #9296a2); */
            background: linear-gradient(to top, #cedcff, #ffffff 50%, #ffffff, #ffffff 75%);
        }
        hr.new2 {
            border-top: 1px dashed #000;
            
        }

        .no-data{
            padding: 15px;
            text-align: center;
            font-size: 16px;
            display: block;
        }
        #tabular-view, #graphical-view, #map-view{
            display: none;
        }
        svg text {
            fill: black;
            font-size: 25px;
            cursor: pointer;
        }

        svg g path {
            fill: #6db8fc;
            stroke: #0466bf;
            stroke-linejoin: round;
            stroke-width: 2px;
            transition: all 0.1s;
        }

        svg g:hover path {
            fill: #0466bf;
            cursor: pointer;
        }

        svg g path.active {
            fill: #bee8c9;
            stroke: #148532;
            stroke-width: 2px;
        }

        #info-box {
            display: none;
            position: fixed;
            top: 0px;
            left: 0px;
            z-index: +10;
            background-color: #121212;
            border: 2px solid #121212;
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .printable-area, .printable-area * {
                visibility: visible;
            }
            .printable-area{
                position: fixed;
                left: 0;
                top: 0;
                width: 100vw !important;
            }
            .print-button, .print-button *{
                visibility: hidden;
            }
        }
    </style>
@endsection

@section('page-content')

<div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
    <div class="col-md-4">
       
            <!-- <div class="card-header">
                <h4 class="card-title">Search</h4>
            </div> -->
            <div class="card-body">
                <div class="form-group">    
                    <label class="btn btn-outline-primary active rounded-pill review-for-buttons" for="review-for-block" style="cursor: pointer;">Block</label>
                    <label class="btn btn-outline-primary rounded-pill review-for-buttons" for="review-for-panchayat" style="cursor: pointer;">Panchayat</label>
                    <input type="radio" id="review-for-block" name="review_for" value="block" hidden checked>
                    <input type="radio" id="review-for-panchayat" name="review_for" value="panchayat" hidden>
                    <hr/>
                </div>
                <div class="row" style="margin-top: -24px;">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="year_id">Year<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="year_id" id="year_id" class="form-control">
                                <option value="">-Select-</option>
                                @foreach($year_datas as $year_data)
                                    <option value="{{$year_data->year_id}}">{{$year_data->year_value}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select year</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="dept_id">Department<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="dept_id" id="dept_id" class="form-control">
                                <option value="">-Select-</option>
                                @foreach($department_datas as $department_data)
                                    <option value="{{$department_data->dept_id}}">{{$department_data->dept_name}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select department</div>
                        </div>
                    </div>
                </div> 
                <div class="form-group">
                    <hr/>
                    <label for="">Block<span style="color:red;margin-left:5px;">*</span></label>
                    <div style="padding: 20px;text-align:center; max-width:550px;margin:auto;margin-top: -47px;">
                        <svg  viewBox="0 0 739 752" stroke-linecap="round" stroke-linejoin="round">
                            <g>
                                <path id="kukru" data-info="Kukru" data-geo-id="12" d="M385.25,12.875l2.5-3.5c10.143,0,16.727,10.909,27.5,14.5,14.572,4.857,21.2-8,33-8,0,4.333,10,22.281,10,26.5l7.5-.5c0-11.443,29.072-7,38-7l1.5,7c7.965,0,14.924-2.5,23-2.5,15.382,15.382,3.552,42.552,19.5,58.5,7.536,0,19,3.677,19,12.5,0,24.964-48.821,37.44-70,44.5l-28-1.5-30.5-20c-23.023-23.023-40.467-51.451-58.5-78.5,0-16.451,6-27.765,6-42.5Z"/>
                                <text x="460" y="100">Kukru</text>
                            </g>  
                            <g>
                                <path id="nimdih" data-info="Nimdih" data-geo-id="11" d="M531,120c-5.72,0-9.271,3-13,3,0,4.808-1.759,11.759-5,15-14.134,0-26.771,20.229-15,32,5.444,0,12,10.115,12,14,14.315,0,28.332-1,43-1,0,9.228,5,17.958,5,32q-1,2-2,4l-2,1c0,14.323-20,10.049-20,24,4.862,0,21.615,5.615,25,9l49,2,38,16c0,1.916,5.813,23,6,23,10.1,10.1,24.239,1.761,30-4,0-19.646-11-34.7-11-55l6-6,22-3,5-9,18,1,1-1V199l-3-2-29,3c0-5.842-4-17.3-4-27H676l-18,2c0-14.773-9.949-30-25-30V130l-14-7c0-4.665,1.624-28-2-28l-15-1-24-2c-5.453,5.453-22.628,20-30,20v13H537l-7-6Z"/>
                                <text x="565" y="180">Nimdih</text>
                            </g>
                            <g>   
                                <path id="ichagarh" data-info="Ichagarh" data-geo-id="10" d="M384,10c-48.073,24.036-80,34.676-80,99,7.122,0,10,16.27,10,23-2.116,0-10,22.557-10,27,10.544,0,8,18.614,8,28,1.96,0,5,3.273,5,5,19.852,0,20,33.447,20,49,5.777,0,12.415,8,29,8,0-.986,30.854-17.854,36-23l26-3c35.158-23.438,75-34.627,75-82h-2l-1-1c-9.368,0-43-7.626-43-14l-5-3c0-13.478-17.264-62-31-62L406,41c0-9.759-11.636-30-21-30Z"/>
                                <text x="330" y="120">Ichagrah</text>
                            </g>
                            <g> 
                                <path id="chandil" data-info="Chandil" data-geo-id="9" d="M342,248v-5c0-5.379,11-.411,11-5,5.214-5.214,6-31.11,6-39l-6-1q-0.5-5-1-10l14-1,3,12c10.575,0,52.608-5.608,58-11l51-1c0-7.787,1.5-14.5,6-19,11.723,0,33,.362,33,12,35.036,0,50-1.864,55,27-5.49,0-23,22.766-23,29,15.333-4.333,8.667,3.333,10,4,3.935,0,6,8.738,6,11,12.332,0,25.668-4,38-4,7.589,7.589,32.153,15,44,15,0,6.139,5.122,33,9,33,0,3.131,2.253,2,5,2,4.666-.667,13.334-8.333,18-9,0,5.783,11.584,17,17,17,0,10.718-10.564,38-19,38,0,11.8-2,26.321-2,35l-7,1c0-4.657-24.542-18.458-27-16l-2,3-3,16-4,3H622c0-11.494-22.706-24-35-24-26.187-26.187-41.229-48-91-48-22.248,0-68,7.631-68,11-14.342,0-69,1.6-69-8l-1-4c-0.986,0-10-22.315-10-27,0-2.4,7.035-12,10-12V253c-5.322,0-14-2.944-14-9"/>
                                <text x="410" y="248">Chandil</text>
                            </g>
                            <g> 
                                <path id="rajnagar" data-info="Rajnagar" data-geo-id="7" d="M370,573l-3,15c0,6.8,26,9.566,26,18,5.316,0-3,18-3,18l-1,1c0,10.425-29,14.446-29,26l1,2c2.145,0,19.429,11,28,11l4,18c-3.986,0-7,9.877-7,15,29.237,0,37.186,40,68,40,0-2.675,4.529-23,7-23,0-8.062,57-19,57-32,0-6.6-8-6.479-8-11q0.5-5.5,1-11l25-12c0,6.7,13.337,8,20,8,0-8.29,50.178-48,51-48l15-19c4.567-4.567,9.783-22,19-22l8-21V514l-10-27c-8.1,0-64-12.729-64-21l-28-9c-25.9,0-50.26,18-78,18l-27,23-7,15c0,21.874-15.533,22.766-34,32l-16,8-11,9-3,13Z"/>
                                <text x="450" y="620">Rajnagar</text>
                            </g>
                            <g> 
                                <path id="seraikela" data-info="Seraikela" data-geo-id="4" d="M373,592c-1.666-1.333-.334-2.667-2-4,0-3,5.5-2.5,0-6,4.917,1.25,9.583-3.25,5-3,5.344-5.344,11.289,2,17,2,6.484-6.484,6.059-4,17-4l2-6c0-4.46-5-2.939-5-6v-1l16,1c0,5.28,10.542,11,17,11l21-6c17.982,0-5-44.136-5-54-1.9,0-25-1.944-25-5q-0.5-3-1-6l1-1,9-1c0-6.812,5-14.416,5-24l-6-13,15-9c0-8.861,5.013-22.013,11-28l26-4,8-9c0-16.854,12-18.9,12-44q-0.5-3-1-6c0-17.029-15-26.757-15-42-2.115,0-11-7.6-11-10-18.182,0-36.04,1.51-54,6-17.753,4.438-33.317-5-50-5,0,6.268-3,17.079-3,26,9.491,9.491,7,29.127,7,45l-9,20c-19.764,19.764-27,21.76-27,49-6.018,0-35,26.313-35,30l14,14c4.978,0,9,1.378,9-4,3.992,0,.714-11,6-11,4.016,0,11,2.976,11,7q0.5,4.5,1,9l-3,13c5.455,0,4,15.909,4,22l-6,16,2,3,10,6c0,14.8-6,22.489-6,32,5.618,0,11.213,4,18,4v-4Z"/>
                                <text x="358" y="545">Seraikela</text>
                            </g>
                            <g> 
                                <path id="gamharia" data-info="Gamharia" data-geo-id="6" d="M460,316q0.5,4,1,8l28,4c0,18.7-2,28.61-2,42,3.813,0,7,2.312,7,6,0,10.989-1.809,17.809-8,24,0,9.571,5,11.713,5,18l-5,4-6,1-9-5c-9.18,0-11.446,4-20,4v6l5,8c-7.492,0-11.452-5-17-5v7c0,2.675,6.616,7,9,7,0,7.195-12.586,20-18,20v3c4.356,4.356,11,3,11,10,0,5.454-13.643,23-17,23q1,5,2,10c6.539,0,13.429,6,24,6,0-1.24,10.13-6,13-6,4.924,0,4.431,6,11,6l3-1c0-6.027,3.178-21,10-21l8-1,4,6h-2c0,1.414-2.414,1-1,1,0,13.409,33.336-1,35-1l3-4,4-3c0-6.065,2-14.947,2-18,13.193,0,44,11.39,44,20,21.36,0,34.776,6,58,6,0-7.573,7-21.5,7-31-2.2,0-9-7.927-9-11,16.071,0,13-33.658,13-49-3.917,0-14-7-22-7,0-5.984-2-18.519-2-25-3.612,0-26-24-26-24l10-9q1-10.5,2-21l-14-20c-26.678,0-33.9-18-58-18l-7,10c-27.5,0-41.692,10-64,10l-11,6q-1,2.5-2,5Z"/>
                                <text x="500" y="430">Gamharia</text>
                            </g>
                            <g> 
                                <path id="kharsawan" data-info="Kharsawan" data-geo-id="5" d="M211,460c-8.768,2.192-22.249,1-28,1,0,7.155-6,11.432-6,20,15.478,0,15.331,27,18,27,0,7.834,25.212,10,35,10,0-12.564,25.536-11,37-11V497h5c4.188,0,10.414,3,16,3v-4c-1.474,0-6-3.248-6-5h27c0-7.13,22.689-14,31-14,0-4.361,7.21-11,13-11,4.682,0,14.056,3,22,3l6-4v-7l-13-8V432c30.186,0,45-37.857,45-65-17,0-5-22.746-5-30l-3-4-22-2c0-39.38-51-6.468-51-33l-7-6c-42.451,0-90,91.675-90,135-7.349,7.349-8.209,20.209-15,27l-1,2-3,2c-2.531,0-4-.836-4,2h-1Z"/>
                                <text x="270" y="460">Kharsawan</text>
                            </g>
                            <g> 
                                <path id="kuchai" data-info="Kuchai" data-geo-id="8" d="M13.414,393.733l1-23,6.5-1.5,0.5-12.5,7-4,1-20.5,4.5-1,3.5,2.5,8-12.5,12.5-2,1,16.5h11.5c4.392-4.392,27-31.768,27-35l-19-3.5c0-17.895,16.881-36,34.5-36l-1.5,10.5c0,19.94,39-2,39-2q-1.251-4.749-2.5-9.5l2.5-2,28,2c0-19.334,87-1.245,87,14,25.48,0,31.761,17,60.5,17v3.5l-9.5,8q0.5,6.5,1,13l11,1.5v3l-4,4.5c-6.569,0-38,6.59-38,12v0.5c6.627,0,8,10.779,8,16,2.685,0,11.625,4,15,4,0,3.346,4.6,20.5-4,20.5l-9,1v3l10,10q-0.25,1-.5,2l-11.5.5q-0.5,10-1,20l-1.5,1-5.5-1.5q-1.75-4-3.5-8c-4,0-4.772-2.728-7.5,0,0,4.662,4.5,5.944,4.5,8.5q-0.5,4.5-1,9h-2l-1-.5c0-4.5-8.447-15.5-12-15.5v1q0.249,2.25.5,4.5c0,9.69-1,18.925-1,30-3.019,0-6.5,7.29-6.5,11l6,3c0,4.867-6.1,5.6-9,8.5,0,30.1-34.624-2.5-45-2.5q0.5-5.75,1-11.5l-18.5-16.5c0-15.218,3.5-29.618,3.5-47.5-6.282,0-13.434-7-23-7,0-16.32-33-19.885-33-32.5l-17.5,2-17.5,25-41-6.5-8,9c-5.591,0-11.914,2.5-15,2.5l-5.5,12.5-20.5.5Z"/>
                                <text x="175" y="360">Kuchai</text>
                            </g>            
                        </svg>
                        <div id="info-box"></div>
                    </div>
                    <input class="form-control" id="geo_id" hidden>
                    <div class="invalid-feedback">Please select block(s)</div>
                </div>
                <div class="form-group"> 
                    <div class="row" id="review-for-panchayat-form" style="display:none;"> 
                    
                    </div>
                    <input class="form-control" id="panchayat_id" hidden>
                    <div class="invalid-feedback">Please select panchayat(s)</div>
                </div>
                <div class="col-md-12">
                    <center><button type="button" class="btn btn-primary float-right" onclick="search()"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button></center>
                </div>
            </div>
        
    </div> 
   
    <div class="col-md-8" style="margin-left: -26px;">

            <div class="card-body" id="tab-height">
                <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabular-tab" data-toggle="pill" href="#tabular-view-tab" role="tab" aria-selected="true">Tabular View</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="graphical-tab" data-toggle="pill" href="#graphical-view-tab" role="tab" aria-selected="false">Graphical View</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="map-tab" data-toggle="pill" href="#map-view-tab" role="tab" aria-selected="false">Map View</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="gallery-tab" data-toggle="pill" href="#gallery-view-tab" role="tab" aria-selected="false">Gallery View</a>
                    </li>
                    <div class="col-md-12" style="margin-top: -27px;display: flow-root;">
                        <button type="button" class="btn btn-secondary btn-sm print-button" onclick="printReview('tabular')" style="float: right;margin-top: -14px;"><i class="fa fa-print" aria-hidden="true"></i></button>&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary btn-sm print-button" style="float: right;margin-top: -14px;margin-right: 7px;"><i class="fa fa-envelope" aria-hidden="true"></i></button>
                    </div>
                </ul><hr>
                <div class="tab-content mt-2 mb-3" id="myTabContent">
                    
                    <!-- tabular-view -->
                    <div class="tab-pane fade show active" id="tabular-view-tab" role="tabpanel">
                        <div id="tabular-view">
                        </div>
                        <div class="no-data">
                            <i class="fas fa-info-circle text-success"></i>&nbsp;&nbsp;No assets data to show
                        </div>
                    </div>
                    <!-- graphical-view -->
                    <div class="tab-pane fade" id="graphical-view-tab" role="tabpanel">
                        <!-- <h4>Graphical View&nbsp;<button type="button" class="btn btn-secondary btn-sm print-button" onclick="printReview('graphical')">Print&nbsp;<i class="fa fa-print" aria-hidden="true"></i></button></h4> -->
                        <div id="graphical-view">
                            <div style="text-align:center; padding: 20px;border-radius: 8px;">
                                <canvas id="asset-chart"></canvas>
                            </div>
                        </div>
                        <div class="no-data">
                            <i class="fas fa-info-circle text-success"></i>&nbsp;&nbsp;No assets data to show
                        </div>
                    </div>
                    <!-- map-view -->
                    <div class="tab-pane fade" id="map-view-tab" role="tabpanel">
                        <!-- <h4>Map View&nbsp;<button type="button" class="btn btn-secondary btn-sm print-button" onclick="printReview('map')">Print&nbsp;<i class="fa fa-print" aria-hidden="true"></i></button></h4> -->
                        <div id="map-view">
                            <div class="map-view-form">
                                <div class="row form-group">
                                    <div class="col-3">
                                        <label for="map-view-block">Select <span id="map-view-block-title">Block<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="map-view-block" id="map-view-block" class="form-control">
                                            <option value="">-Select-</option>
                                        </select>
                                        <div class="invalid-feedback">Please select block</div>
                                    </div>
                                    <div class="col-3">
                                        <label for="map-view-asset">Select Asset<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="map-view-asset" id="map-view-asset" class="form-control">
                                            <option value="">-Select-</option>
                                        </select>
                                        <div class="invalid-feedback">Please select asset</div>
                                    </div>
                                    <div class="col-2">
                                        <div style="height: 30px;"></div>
                                        <button type="button" class="btn btn-primary" id="map-view-search" onclick="mapSearch()">Search</button>
                                    </div>
                                </div>
                            </div>
                            <div id="mapCanvas" style="width: 100%; height: 400px; border-radius: 3px;"></div>
                        </div>
                        <div class="no-data" style="width: 100%; height: 400px; border-radius: 8px;">
                            <i class="fas fa-info-circle text-success"></i>&nbsp;&nbsp;No assets data to show
                        </div>
                    </div>

                       <!-- gallery-view -->
                       <div class="tab-pane fade" id="gallery-view-tab" role="tabpanel">
                        <!-- <h4>Gallery View&nbsp;<button type="button" class="btn btn-secondary btn-sm print-button" onclick="printReview('map')">Print&nbsp;<i class="fa fa-print" aria-hidden="true"></i></button></h4> -->
                        <div id="gallery-view">
                          
                        </div>
                        <div class="no-data" style="width: 100%; height: 400px; border-radius: 8px;">
                            <i class="fas fa-info-circle text-success"></i>&nbsp;&nbsp;No assets data to show
                        </div>
                    </div>

                </div>
            </div>
       
    </div>
</div>
</div>


<!-- for review-for (block, punchayat) radio buttons -->
<script>
    var review_for = 'block';
    var selected_panchayat = new Array;
    var panchayat_form_data_received = false;

    $(document).ready(function(){
        $("input[name=review_for]").change(function(){
            review_for_toggle();
        });
        $(".review-for-buttons").click(function(){
            $(".review-for-buttons").removeClass("active");
            $(this).addClass('active');
        });

        // for selected_panchayat and reset the value of panchayat_id
        $("#review-for-panchayat-form").delegate("select", "change", function(){
            var selected_panchayat = $("#review-for-panchayat-form select").map(function() {
                if($(this).val()!=""){ 
                    return $(this).val(); 
                }
            }).get();
            $("#panchayat_id").val(selected_panchayat);
        });
    });

    function review_for_toggle(){
        review_for = $("input[name=review_for]:checked").val();
        if(review_for=="block"){
            $("#review-for-panchayat-form").hide();
            panchayat_form_data_received = false;
        }
        else if(review_for=="panchayat"){
            $("#review-for-panchayat-form").show();
        }
    }
</script>

<script>
    // for svg's
    var selected_geo = new Array;
    // $("path").hover(function(e) {
    //     $('#info-box').css('display','block');
    //     $('#info-box').html($(this).data('info'));
    // });
    // $("path").mouseleave(function(e) {
    //     $('#info-box').css('display','none');
    // });
    // $(document).mousemove(function(e) {
    //     $('#info-box').css('top',e.pageY-$('#info-box').height()-30);
    //     $('#info-box').css('left',e.pageX-($('#info-box').width())/2);
    // }).mouseover();
    $("g").click(function(){
        var geo_id_tmp= $(this).children('path').data('geo-id').toString();
        if(selected_geo.includes(geo_id_tmp)){
            $(this).children('path').removeClass("active");
            selected_geo.splice(selected_geo.indexOf(geo_id_tmp), 1);
            $("#geo_id").val(selected_geo);
        }
        else{
            $(this).children('path').addClass("active");
            selected_geo.push(geo_id_tmp);
            $("#geo_id").val(selected_geo);
        }
        panchayat_form_data_received = false; // if any block changed then we assigned panchayat_form_data_received as "false" so "change block + search" load panchayat form data only not views datas
    });


    // removing is-invalid on change
    $(document).ready(function(){
        $("#dept_id").change(function(){
            if($("#dept_id").val()){
                $("#dept_id").removeClass('is-invalid');
            }
        });
        $("#year_id").change(function(){
            if($("#year_id").val()){
                $("#year_id").removeClass('is-invalid');
            }
        });
        $("#geo_id").change(function(){
            if($("#geo_id").val()){
                $("#geo_id").removeClass('is-invalid');
            }
        });
    });
    // next after search button pressed
    function search(){
        var dept_id_error = true;
        var year_id_error = true;
        var geo_id_error = true;
        var panchayat_id_error = true;

        // department
        if($("#dept_id").val()==""){
            $("#dept_id").addClass('is-invalid');
            dept_id_error=true;
        }
        else{
            $("#dept_id").removeClass('is-invalid');
            dept_id_error=false;
        }

        // year
        if($("#year_id").val()==""){
            $("#year_id").addClass('is-invalid');
            year_id_error=true;
        }
        else{
            $("#year_id").removeClass('is-invalid');
            year_id_error=false;
        }

        // geo/block selected
        if($("#geo_id").val()==""){
            $("#geo_id").addClass('is-invalid');
            geo_id_error=true;
        }
        else{
            $("#geo_id").removeClass('is-invalid');
            geo_id_error=false;
        }
        
        // panchayat selected
        if(review_for=="panchayat" && panchayat_form_data_received)
        {
            if($("#panchayat_id").val()==""){
                $("#panchayat_id").addClass('is-invalid');
                panchayat_id_error=true;
            }
            else{
                $("#panchayat_id").removeClass('is-invalid');
                panchayat_id_error=false;
            }
        }
        else{
            $("#panchayat_id").removeClass('is-invalid');
            panchayat_id_error=false;
        }

        if(!dept_id_error&&!year_id_error&&!geo_id_error&&!panchayat_id_error){
            // if not error then getting datas from controller
            if(review_for=="block"){
                getDatas(); // all view datas
            }
            else{ // review_for == panchayat, for panchayat, to get all panchayats of each selected blocks
                if(panchayat_form_data_received){ // if panchayat data already reaceived
                    getDatas();
                }
                else{
                    // resetting all views because we are now getting panchayat datas
                    resetTabularView();
                    resetGraphicalView();
                    resetMapView();
                    getPanchayatDatas(); // getting panchayat datas
                }
            }
        }
    }

    function getDatas(){
        // getting datas before send to controller
        dept_id = $("#dept_id").val();
        year_id = $("#year_id").val();
        geo_id = $("#geo_id").val(); // string, have convert to array in controller// block ids

        panchayat_id = "";// string, panchayat data if review_for==panchayat
        if(review_for=="panchayat")
        { panchayat_id = $("#panchayat_id").val(); }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset-review/get-datas')}}",
            data: {'review_for': review_for, 'geo_id': geo_id, 'panchayat_id': panchayat_id, 'dept_id': dept_id, 'year_id': year_id},
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(data){
                $(".custom-loader").fadeIn(300);
            },
            error: function(xhr){
                alert("error"+xhr.status+", "+xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data){
                console.log(data);
                // resetting all view's blocks/divs/inputs
                resetTabularView();
                resetGraphicalView();
                resetMapView();
                if(data.response=="no_data"){ // no data found
                   
                }
                else{ // data.response  == success
                    // calling/initialiazing all views
                    initializeTabularView(data.tabular_view);
                    intializeGraphicalView(data.chart_labels, data.chart_datasets);
                    initializeMapView(data.map_view_blocks, data.map_view_assets);
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    
    function getPanchayatDatas(){
        geo_id = $("#geo_id").val(); // string, have convert to array in controller
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset-review/get-panchayat-data')}}",
            data: {'geo_id': geo_id},
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(data){
                $(".custom-loader").fadeIn(300);
                panchayat_form_data_received = false;
            },
            error: function(xhr){
                alert("error"+xhr.status+", "+xhr.statusText);
                $(".custom-loader").fadeOut(300);
                panchayat_form_data_received = false;
            },
            success: function (data){
                console.log(data);
                if(data.response=="no_data"){ // no data found
                    
                }
                else{ // data.response  == success
                    var to_append = "";
                    $("#review-for-panchayat-form").html("");

                    for(var i=0; i<data.data.length; i++){
                        to_append += `<div class="col-6">
                                        <div class="form-group">`;
                        to_append += `<label>`+data.data[i].block_name +`</label>
                                        <select id="panchayat-select-`+i+`" class="form-control" multiple>
                                        <option value="">-Select-</option>`;

                        for(var j=0; j<data.data[i].panchayat_data.length; j++){
                            to_append+= `<option value="`+data.data[i].panchayat_data[j].geo_id+`">`+data.data[i].panchayat_data[j].geo_name+`</option>`;
                        }

                        to_append += `</select>
                                        </div>
                                    </div>`;
                    }

                    $("#review-for-panchayat-form").html(to_append);
                    panchayat_form_data_received = true;
                    
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }

</script>

<!-- tabular view -->
<script>
    function initializeTabularView(data){
        // data is multidimensional array, each row for each table row
        var toShowTabularForm = "<table class='table table-striped table-bordered table-datatable table-sm'>";
        for(var i=0; i<data.length; i++){
            if(i==0){ // for first row
                toShowTabularForm = toShowTabularForm + "<tr class='table-secondary'>";
            }
            else{ // for others
                toShowTabularForm = toShowTabularForm + "<tr>";
            }

            for(var j=0;j<data[0].length; j++){
                if(i==0){  // for first row i.e th
                    toShowTabularForm = toShowTabularForm + "<th>"+data[i][j]+"</th>";
                }
                else{ // for others
                    toShowTabularForm = toShowTabularForm + "<td>"+data[i][j]+"</td>";
                }
            }
            toShowTabularForm = toShowTabularForm + "</tr>";
        }
        toShowTabularForm = toShowTabularForm + "</table";
        $("#tabular-view").append(toShowTabularForm);
        $("#tabular-view").show();
        $("#tabular-view + .no-data").hide();
    }
    function resetTabularView(){
        $("#tabular-view").html("");
        $("#tabular-view").hide();
        $("#tabular-view + .no-data").show();
    }
</script>

<!-- chart view -->
<script>
    // chart intitalizing
    var assetChart = document.getElementById('asset-chart').getContext('2d');
    var chart_data = new Object();
    var assetChartCall;
    // whenever graphical view is active/shown
    function showGraph()
    {
        assetChartCall = new Chart(assetChart, {
            type: 'bar',
            data: chart_data,
            options: {
                offset : true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';

                            if (tooltipItem.yLabel <= 0.5) {
                                label += " : 0";
                            }
                            else{
                                label += " : " + tooltipItem.yLabel;
                            }
                            return label;
                        }
                    }
                }
            }
        });
    }
    function intializeGraphicalView(chart_labels, chart_datasets){
        var labels = [];
        var datasets = [];
        var backgroundColor = [];
        var borderColor = [];

        labels = chart_labels; // labels i.e block names

        for(var i=0;i<chart_datasets.length;i++){
            var datasets_obj = new Object();
            datasets_obj.label = chart_datasets[i].label;
            datasets_obj.data = chart_datasets[i].data;
            var color = getRandomColor();
            datasets_obj.backgroundColor = color[0];
            datasets_obj.borderColor = color[1];
            datasets_obj.borderWidth = 2;

            datasets_obj.categoryPercentage = 0.6;
            datasets_obj.barPercentage = 1.0;
            datasets_obj.barThickness = 'flex';
            datasets_obj.maxBarThickness = 60;
            
            datasets.push(datasets_obj);
        }
        chart_data.labels = labels;
        chart_data.datasets = datasets;
        console.log(chart_data);
        console.log(getRandomColor());

        // showing before showing bar graph
        $("#graphical-view").show();
        $("#graphical-view + .no-data").hide();
        showGraph();
    }
    function resetGraphicalView(){
        $("#graphical-view").hide();
        $("#graphical-view + .no-data").show();
    }
    function getRandomColor(){
        var color = ['#a6ffc9','#5eff9e']; //['brighter', 'darker']
        var random_tmp = Math.floor(Math.random() * (360 - 0 + 1)) + 0;
        color[0] = 'hsl('+random_tmp+',76%,70%)';
        color[1] = 'hsl('+random_tmp+',76%,50%)';
        return color;
    }
</script>


<!-- for map-view -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuYbCxfGw_c6lasAlpExIOFj55MVY6xSo"></script>
<script>
    function initializeMapView(map_view_blocks, map_view_assets){
        $("#map-view-block-title").html(review_for);

        $("#map-view-block").html('<option value="">-Select-</option>');
        for(var i=0; i<map_view_blocks.length; i++){
            $("#map-view-block").append('<option value="'+map_view_blocks[i].id+'">'+map_view_blocks[i].name+'</option>');
        }
        $("#map-view-asset").html('<option value="">-Select-</option>');
        for(var i=0; i<map_view_assets.length; i++){
            $("#map-view-asset").append('<option value="'+map_view_assets[i].id+'">'+map_view_assets[i].name+'</option>');
        }

        $("#map-view").show();
        $("#mapCanvas").hide();
        $("#map-view + .no-data").show();
    }
    // validation reset
    $(document).ready(function(){
        $("#map-view-block").change(function(){
            $(this).removeClass("is-invalid");
        });
        $("#map-view-asset").change(function(){
            $(this).removeClass("is-invalid");
        });
    });
    function mapSearch(){
        // validation for map-select/ map-inputs
        var errorMapSearch = false;
        var mapViewBlock = $("#map-view-block").val();
        var mapViewAsset = $("#map-view-asset").val();
        var mapViewYear = $("#year_id").val();

        if(mapViewBlock==""){
            $("#map-view-block").addClass("is-invalid");
            errorMapSearch = true;
        }
        else{
            $("#map-view-block").removeClass("is-invalid");
        }

        if(mapViewAsset == ""){
            $("#map-view-asset").addClass("is-invalid");
            errorMapSearch = true;
        }
        else{
            $("#map-view-asset").removeClass("is-invalid");
        }

        /* ajax */
        if(errorMapSearch==false){

            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
                url: "{{url('asset-review/get-map-data')}}",
                data: {'review_for': review_for, 'geo_id': mapViewBlock, 'asset_id': mapViewAsset, 'year_id': mapViewYear},
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function(data){
                    $(".custom-loader").fadeIn(300);
                },
                error: function(xhr){
                    alert("error"+xhr.status+", "+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function (data){
                    console.log(data);
                    if(data.response=="no_data"){ // no data found
                        $("#map-view").show();
                        $("#mapCanvas").hide();
                        $("#map-view + .no-data").show();
                    }
                    else{ // data.response == success
                        $("#map-view").show();
                        $("#mapCanvas").show();
                        $("#map-view + .no-data").hide();
                        showMap(data.map_data);
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }
    function showMap(data) {
        var mapCanvas = document.getElementById('mapCanvas');
        var mapOptions = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var centerLat = '';
        var centerLng = '';
        //console.log(data[0].lng);
        //Loop through each location.
        // Sample use of first data
        centerLat = data[0].latitude;
        centerLng = data[0].longitude;
        $.each(data, function(){
            //Plot the location as a marker
            var theposition = new google.maps.LatLng(this.latitude, this.longitude); 
            var marker = new google.maps.Marker({
                position: theposition,
                map: map,
                title: 'Uluru (Ayers Rock)',
                animation: google.maps.Animation.DROP
            });


            var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<h4 id="firstHeading" class="firstHeading">'+this.location_name+'</h4>'+
            '<div id="bodyContent">'+
            '<p><b>Panchayat:</b> '+this.geo_name+'</p>'+
            '</div>'+
            '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });



            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        });
        //map.setCenter(centerLat,centerLng);
        map.setCenter(new google.maps.LatLng(centerLat,centerLng));
    }
    function resetMapView(){
        $("#map-view").hide();
        $("#mapCanvas").hide();
        $("#map-view + .no-data").show();
        $("#map-view-block").removeClass("is-invalid");
        $("#map-view-asset").removeClass("is-invalid");
    }
</script>

<script>
    // for printing 
    function printReview(type)
    {
        // initializing/ resetting printing area
        $("#tabular-view-tab").removeClass('printable-area');
        $("#graphical-view-tab").removeClass('printable-area');
        $("#map-view-tab").removeClass('printable-area');

        // assigning/ set printable-area class to print area
        if(type=="tabular"){
            $("#tabular-view-tab").addClass("printable-area");
        }
        else if(type=="graphical"){
            $("#graphical-view-tab").addClass("printable-area");
        }
        else if(type=="map"){
            $("#map-view-tab").addClass("printable-area");
        }

        window.print();
    }
</script>

<!-- 
Process: (Block Level)
1. search()-> after year, department, blocks(svg) selected
    -> reset all views (call respective function for better resetting)
    -> show errors (year, department, blocks)
    -> call ajax to get data
    -> on success
        -> if no_data -> show no data in all in one div
        -> if success -> call initialiaze (all views) pass respective data
2. On each initialization function
    a) tabular view
        -> receive data as arugument and show table
    b) chart view
        -> receive data as argument and show chart
    c) map view
        -> receive data as arugument -> assign/append options for block & asset
        -> on search
            -> check errors for input fields (asset, blocks)
            -> cal ajax to get location data according to block and asset
            -> on success
                -> if no_data -> show message
                -> success -> show map markers on map

Reset Function:
1. Tabular view
    -> table html("")
2. Chart View
    -> destroy chart
3. Map view
    -> destroy map, 
 -->
@endsection