@extends('layout.layout')

@section('title', 'Asset Review')

@section('page-style')
<style>
    .card,
    .card-light {
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

    .no-data {
        padding: 15px;
        text-align: center;
        font-size: 16px;
        display: block;
    }

    #tabular-view,
    #graphical-view,
    #map-view {
        display: none;
    }
    .review-for-buttons:hover, .review-for-buttons.active{
        color: white !important;
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

    .map-content-all{
        position: relative;
        padding: 10px;
        text-align:center;
        margin:auto;
        margin-top: -10px;
    }
    .block-map-content{ /* inactive */
        filter: blur(2px) grayscale(100%);
    }
    .block-map-content.active{ /* active */
        filter: unset;
    }
    .panchayat-map-content{ /* inactive */
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform: scale(0,0);
        transition: all 0.3s ease-in-out;
        text-align: center;
        margin:auto;
    }
    .panchayat-map-content.active{ /* active */
        transform: scale(1, 1);
    }
    .panchayat-map-content svg{
        -webkit-filter: drop-shadow( 3px 3px 2px rgba(0, 0, 0, .7));
        filter: drop-shadow( 3px 3px 2px rgba(0, 0, 0, .7));
    }
    .panchayat-map-content svg path {
        fill: #6db8fc;
        stroke: #0466bf;
        stroke-linejoin: round;
        stroke-width: 2px;
        transition: all 0.1s;
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

        .printable-area,
        .printable-area * {
            visibility: visible;
        }

        .printable-area {
            position: fixed;
            left: 0;
            top: 0;
            width: 100vw !important;
        }

        .print-button,
        .print-button * {
            visibility: hidden;
        }
    }

    .gallery-view-image-thumb{
        margin-right:5px; 
        display:inline-block; 
        position:relative;
        border:1px solid #383838;
        border-radius: 3px;
        overflow: hidden;
    }
    .gallery-view-image-thumb img{
        height:300px;
        min-height:300px;
        min-width:200px;
    }
    .gallery-view-image-thumb .gallery-view-image-thumb-labels{
        position:absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        border-radius: 3px;
        background: rgba(0,0,0,0.5);
        font-size: 18px;
        cursor: pointer;
        padding: 5px 10px;
        color: white;
        text-transform: capitalize;
        transition: padding 0.3s ease-in-out;
    }
    .gallery-view-image-thumb-asset-name{
        font-size: 14px;
        display: block;
    }
    .gallery-view-image-thumb-geo-name{
        font-size: 12px;
        display: block;
    }
    .gallery-view-image-thumb:hover .gallery-view-image-thumb-labels{
        padding-top: 15px;
        padding-bottom: 15px;;
    }
</style>
@endsection

@section('page-content')

<div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7; min-height:812px;">
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
                <hr />
            </div>
            <div class="row">
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
                <hr />
                <label for="">Block<span style="color:red;margin-left:5px;">*</span></label>
                <div class="map-content-all">
                    <div class="block-map-content active">
                        <svg viewBox="0 0 739 752" stroke-linecap="round" stroke-linejoin="round">
                            <g  data-info="Kukru" data-geo-id="12" data-panchayat-target="kukru">
                                <path d="M385.25,12.875l2.5-3.5c10.143,0,16.727,10.909,27.5,14.5,14.572,4.857,21.2-8,33-8,0,4.333,10,22.281,10,26.5l7.5-.5c0-11.443,29.072-7,38-7l1.5,7c7.965,0,14.924-2.5,23-2.5,15.382,15.382,3.552,42.552,19.5,58.5,7.536,0,19,3.677,19,12.5,0,24.964-48.821,37.44-70,44.5l-28-1.5-30.5-20c-23.023-23.023-40.467-51.451-58.5-78.5,0-16.451,6-27.765,6-42.5Z" />
                                <text x="460" y="100">Kukru</text>
                            </g>
                            <g data-info="Nimdih" data-geo-id="11">
                                <path d="M531,120c-5.72,0-9.271,3-13,3,0,4.808-1.759,11.759-5,15-14.134,0-26.771,20.229-15,32,5.444,0,12,10.115,12,14,14.315,0,28.332-1,43-1,0,9.228,5,17.958,5,32q-1,2-2,4l-2,1c0,14.323-20,10.049-20,24,4.862,0,21.615,5.615,25,9l49,2,38,16c0,1.916,5.813,23,6,23,10.1,10.1,24.239,1.761,30-4,0-19.646-11-34.7-11-55l6-6,22-3,5-9,18,1,1-1V199l-3-2-29,3c0-5.842-4-17.3-4-27H676l-18,2c0-14.773-9.949-30-25-30V130l-14-7c0-4.665,1.624-28-2-28l-15-1-24-2c-5.453,5.453-22.628,20-30,20v13H537l-7-6Z" />
                                <text x="565" y="180">Nimdih</text>
                            </g>
                            <g data-info="Ichagarh" data-geo-id="10" >
                                <path d="M384,10c-48.073,24.036-80,34.676-80,99,7.122,0,10,16.27,10,23-2.116,0-10,22.557-10,27,10.544,0,8,18.614,8,28,1.96,0,5,3.273,5,5,19.852,0,20,33.447,20,49,5.777,0,12.415,8,29,8,0-.986,30.854-17.854,36-23l26-3c35.158-23.438,75-34.627,75-82h-2l-1-1c-9.368,0-43-7.626-43-14l-5-3c0-13.478-17.264-62-31-62L406,41c0-9.759-11.636-30-21-30Z" />
                                <text x="330" y="120">Ichagrah</text>
                            </g>
                            <g  data-info="Chandil" data-geo-id="9">
                                <path d="M342,248v-5c0-5.379,11-.411,11-5,5.214-5.214,6-31.11,6-39l-6-1q-0.5-5-1-10l14-1,3,12c10.575,0,52.608-5.608,58-11l51-1c0-7.787,1.5-14.5,6-19,11.723,0,33,.362,33,12,35.036,0,50-1.864,55,27-5.49,0-23,22.766-23,29,15.333-4.333,8.667,3.333,10,4,3.935,0,6,8.738,6,11,12.332,0,25.668-4,38-4,7.589,7.589,32.153,15,44,15,0,6.139,5.122,33,9,33,0,3.131,2.253,2,5,2,4.666-.667,13.334-8.333,18-9,0,5.783,11.584,17,17,17,0,10.718-10.564,38-19,38,0,11.8-2,26.321-2,35l-7,1c0-4.657-24.542-18.458-27-16l-2,3-3,16-4,3H622c0-11.494-22.706-24-35-24-26.187-26.187-41.229-48-91-48-22.248,0-68,7.631-68,11-14.342,0-69,1.6-69-8l-1-4c-0.986,0-10-22.315-10-27,0-2.4,7.035-12,10-12V253c-5.322,0-14-2.944-14-9" />
                                <text x="410" y="248">Chandil</text>
                            </g>
                            <g data-info="Rajnagar" data-geo-id="7" >
                                <path d="M370,573l-3,15c0,6.8,26,9.566,26,18,5.316,0-3,18-3,18l-1,1c0,10.425-29,14.446-29,26l1,2c2.145,0,19.429,11,28,11l4,18c-3.986,0-7,9.877-7,15,29.237,0,37.186,40,68,40,0-2.675,4.529-23,7-23,0-8.062,57-19,57-32,0-6.6-8-6.479-8-11q0.5-5.5,1-11l25-12c0,6.7,13.337,8,20,8,0-8.29,50.178-48,51-48l15-19c4.567-4.567,9.783-22,19-22l8-21V514l-10-27c-8.1,0-64-12.729-64-21l-28-9c-25.9,0-50.26,18-78,18l-27,23-7,15c0,21.874-15.533,22.766-34,32l-16,8-11,9-3,13Z" />
                                <text x="450" y="620">Rajnagar</text>
                            </g>
                            <g data-info="Seraikela" data-geo-id="4" >
                                <path d="M373,592c-1.666-1.333-.334-2.667-2-4,0-3,5.5-2.5,0-6,4.917,1.25,9.583-3.25,5-3,5.344-5.344,11.289,2,17,2,6.484-6.484,6.059-4,17-4l2-6c0-4.46-5-2.939-5-6v-1l16,1c0,5.28,10.542,11,17,11l21-6c17.982,0-5-44.136-5-54-1.9,0-25-1.944-25-5q-0.5-3-1-6l1-1,9-1c0-6.812,5-14.416,5-24l-6-13,15-9c0-8.861,5.013-22.013,11-28l26-4,8-9c0-16.854,12-18.9,12-44q-0.5-3-1-6c0-17.029-15-26.757-15-42-2.115,0-11-7.6-11-10-18.182,0-36.04,1.51-54,6-17.753,4.438-33.317-5-50-5,0,6.268-3,17.079-3,26,9.491,9.491,7,29.127,7,45l-9,20c-19.764,19.764-27,21.76-27,49-6.018,0-35,26.313-35,30l14,14c4.978,0,9,1.378,9-4,3.992,0,.714-11,6-11,4.016,0,11,2.976,11,7q0.5,4.5,1,9l-3,13c5.455,0,4,15.909,4,22l-6,16,2,3,10,6c0,14.8-6,22.489-6,32,5.618,0,11.213,4,18,4v-4Z" />
                                <text x="358" y="545">Seraikela</text>
                            </g>
                            <g data-info="Gamharia" data-geo-id="6">
                                <path id="gamharia" d="M460,316q0.5,4,1,8l28,4c0,18.7-2,28.61-2,42,3.813,0,7,2.312,7,6,0,10.989-1.809,17.809-8,24,0,9.571,5,11.713,5,18l-5,4-6,1-9-5c-9.18,0-11.446,4-20,4v6l5,8c-7.492,0-11.452-5-17-5v7c0,2.675,6.616,7,9,7,0,7.195-12.586,20-18,20v3c4.356,4.356,11,3,11,10,0,5.454-13.643,23-17,23q1,5,2,10c6.539,0,13.429,6,24,6,0-1.24,10.13-6,13-6,4.924,0,4.431,6,11,6l3-1c0-6.027,3.178-21,10-21l8-1,4,6h-2c0,1.414-2.414,1-1,1,0,13.409,33.336-1,35-1l3-4,4-3c0-6.065,2-14.947,2-18,13.193,0,44,11.39,44,20,21.36,0,34.776,6,58,6,0-7.573,7-21.5,7-31-2.2,0-9-7.927-9-11,16.071,0,13-33.658,13-49-3.917,0-14-7-22-7,0-5.984-2-18.519-2-25-3.612,0-26-24-26-24l10-9q1-10.5,2-21l-14-20c-26.678,0-33.9-18-58-18l-7,10c-27.5,0-41.692,10-64,10l-11,6q-1,2.5-2,5Z" />
                                <text x="500" y="430">Gamharia</text>
                            </g>
                            <g data-info="Kharsawan" data-geo-id="5" >
                                <path d="M211,460c-8.768,2.192-22.249,1-28,1,0,7.155-6,11.432-6,20,15.478,0,15.331,27,18,27,0,7.834,25.212,10,35,10,0-12.564,25.536-11,37-11V497h5c4.188,0,10.414,3,16,3v-4c-1.474,0-6-3.248-6-5h27c0-7.13,22.689-14,31-14,0-4.361,7.21-11,13-11,4.682,0,14.056,3,22,3l6-4v-7l-13-8V432c30.186,0,45-37.857,45-65-17,0-5-22.746-5-30l-3-4-22-2c0-39.38-51-6.468-51-33l-7-6c-42.451,0-90,91.675-90,135-7.349,7.349-8.209,20.209-15,27l-1,2-3,2c-2.531,0-4-.836-4,2h-1Z" />
                                <text x="270" y="460">Kharsawan</text>
                            </g>
                            <g data-info="Kuchai" data-geo-id="8" >
                                <path d="M13.414,393.733l1-23,6.5-1.5,0.5-12.5,7-4,1-20.5,4.5-1,3.5,2.5,8-12.5,12.5-2,1,16.5h11.5c4.392-4.392,27-31.768,27-35l-19-3.5c0-17.895,16.881-36,34.5-36l-1.5,10.5c0,19.94,39-2,39-2q-1.251-4.749-2.5-9.5l2.5-2,28,2c0-19.334,87-1.245,87,14,25.48,0,31.761,17,60.5,17v3.5l-9.5,8q0.5,6.5,1,13l11,1.5v3l-4,4.5c-6.569,0-38,6.59-38,12v0.5c6.627,0,8,10.779,8,16,2.685,0,11.625,4,15,4,0,3.346,4.6,20.5-4,20.5l-9,1v3l10,10q-0.25,1-.5,2l-11.5.5q-0.5,10-1,20l-1.5,1-5.5-1.5q-1.75-4-3.5-8c-4,0-4.772-2.728-7.5,0,0,4.662,4.5,5.944,4.5,8.5q-0.5,4.5-1,9h-2l-1-.5c0-4.5-8.447-15.5-12-15.5v1q0.249,2.25.5,4.5c0,9.69-1,18.925-1,30-3.019,0-6.5,7.29-6.5,11l6,3c0,4.867-6.1,5.6-9,8.5,0,30.1-34.624-2.5-45-2.5q0.5-5.75,1-11.5l-18.5-16.5c0-15.218,3.5-29.618,3.5-47.5-6.282,0-13.434-7-23-7,0-16.32-33-19.885-33-32.5l-17.5,2-17.5,25-41-6.5-8,9c-5.591,0-11.914,2.5-15,2.5l-5.5,12.5-20.5.5Z" />
                                <text x="175" y="360">Kuchai</text>
                            </g>
                        </svg>
                    </div>
                    <div class="panchayat-map-content">
                        <svg id="panchayat-kukru" viewBox="0 0 750 567">
                            <g data-info="Ichadih" data-geo-id="100">
                                <path d="M163.87 229.39L211.89 228.25L215.32 214.53C218.47 214.53 243.9 208.9 243.9 204.24C256.57 204.24 272.41 206.53 281.63 206.53C281.63 216.4 304.19 239.68 311.36 239.68L317.07 235.11L351.37 233.96L352.52 259.11C357.65 259.11 397.1 277.71 397.1 285.41L398.25 285.41C398.63 297.22 399.01 309.03 399.39 320.84L381.1 342.56C375.29 346.44 366.33 349.42 359.38 349.42C359.38 358.35 328.51 368.65 328.51 376.86C324.17 381.19 325.08 397.02 325.08 404.29C320.92 408.45 303.35 451.18 303.35 456.88L290.78 456.88C290.78 443.32 265.6 400.86 254.19 400.86C254.19 397.28 245.08 386 240.47 386C240.47 384.44 222.05 368.85 221.04 368.85L205.03 344.85C205.03 322.51 190.17 303.39 190.17 280.83C187.11 277.77 184.45 270.15 184.45 265.97C182.29 265.97 168.45 242.34 168.45 233.96L163.87 229.39Z" />
                                <text x="230" y="325">Ichadih</text>
                            </g>
                            <g data-info="Tiruladih" data-geo-id="101">
                                <path d="M219.51 76.02L184.45 76.02L174.54 72.97L169.21 72.21C153.4 72.21 136.44 73.23 121.95 68.4L94.51 61.54L80.79 50.87C80.79 43.45 53.35 24.51 53.35 10.48C52.34 10.48 33.54 1.33 33.54 1.33C22.55 1.33 4.19 12.77 0.76 12.77C0.76 25.42 7.6 28.69 10.67 40.96C11.18 51.38 11.69 61.79 12.2 72.21L24.39 92.02C35.14 102.77 40.45 120.22 56.4 120.22L62.5 125.56L80.03 159.85C81.05 174.08 82.06 188.3 83.08 202.53L91.46 207.86C96.83 207.86 117.38 215.35 117.38 216.24C131.95 216.24 147.45 224.63 163.87 224.63C178.73 209.78 182.93 178.45 182.93 154.52C200.09 137.35 219.51 100.92 219.51 76.78L219.51 76.02Z" />
                                <text x="70" y="132">Tirulkdih</text>
                            </g>
                            <g data-info="Chaura" data-geo-id="102">
                                <path d="M191.31 76.02C199.2 86.54 200.94 122.51 185.21 122.51L180.64 128.6C180.64 144.48 163.87 158.47 163.87 178.14C163.62 194.91 163.36 211.67 163.11 228.44C163.11 230.47 162.68 230.72 164.63 230.72C164.63 239.38 183.61 235.3 189.79 235.3C193.74 239.25 192.33 247.74 196.65 252.06C208.02 252.06 225.07 238.57 232.47 245.97C232.47 250.11 235.61 262.73 240.85 262.73C246.76 268.64 258.15 259.68 262.2 259.68C262.2 252.15 265.68 218.53 272.1 218.53L275.91 217.77C275.91 228.71 283.54 237.51 283.54 246.73L288.11 247.49L309.45 237.58C309.45 224.09 323.17 218.89 323.17 213.2L320.12 210.91C305.63 210.91 308.69 201.06 308.69 189.57C318.28 189.57 332.32 172.95 332.32 164.42L331.55 163.66C331.3 163.15 331.05 162.64 330.79 162.14L319.36 152.99C319.36 135.07 309.37 133.77 303.35 121.74L301.83 120.22C301.83 97.9 298.33 61.09 284.3 47.06C281.78 47.06 267.53 45.35 267.53 43.25C259.71 43.25 252.62 45.54 246.19 45.54C231.44 60.28 227.32 70.68 201.22 70.68C201.22 73.2 193.1 76.78 190.55 76.78L191.31 76.02Z" />
                                <text x="200" y="150">Chaura</text>
                            </g>
                            <g data-info="Kukru" data-geo-id="103">
                                <path d="M303.35 136.8C315.67 142.95 321.8 143.66 336.51 143.66C336.51 127.34 386.25 118.51 403.96 118.51C405.81 116.66 430.4 112.93 433.69 116.22C444.16 116.22 470.27 117.91 470.27 127.65C474.46 127.65 481.08 179.09 464.56 179.09L465.7 203.1C456.39 203.1 443.98 223.42 443.98 232.82C424.18 232.82 386.88 227.04 373.09 240.82L323.93 239.68C315.4 248.21 275.94 253.4 262.2 253.4L255.34 247.68L255.34 235.11C261.22 235.11 266.89 222.41 271.34 217.96C276.74 217.96 274.77 202.12 274.77 197.38C285.15 187.01 291.92 172.26 291.92 155.09C298.17 148.84 302.21 149.39 302.21 140.23L303.35 136.8Z" />
                                <text x="330" y="180">Kukru</text>
                            </g>
                            <g data-info="Latemda" data-geo-id="104">
                                <path d="M637.96 316.84C637.86 316.84 619.37 312.27 602.9 312.27L578.51 309.98L487.8 311.51C475.75 299.45 405.18 305.41 379.57 305.41L351.37 297.03C348.32 290.68 345.27 284.33 342.23 277.97C342.23 266.05 336.89 254.98 336.89 242.16L362.8 231.49C427.78 231.49 448.93 199.29 448.93 137.75L477.9 135.46C489.33 146.89 519.35 149.27 538.87 145.37C555.26 142.09 563.71 136.22 578.51 136.22C579.01 137.24 579.52 138.26 580.03 139.27L581.55 140.04L583.08 140.8C583.08 148.78 613.43 157.9 617.38 169.76L618.9 171.28L628.81 205.58C628.81 224.77 634.15 244.49 634.15 265.02C634.15 281.21 637.96 296.4 637.96 310.74C637.7 312.78 637.45 314.81 637.2 316.84L637.96 316.84Z" />
                                <text x="450" y="250">Letemda</text>
                            </g>
                            <g data-info="Janum" data-geo-id="105">
                                <path d="M378.81 478.41C383.89 475.87 389.48 463.91 389.48 458.59C390.29 458.59 403.49 447.64 405.49 445.64C411.91 445.64 424.55 438.77 429.12 434.2C448.91 434.2 512.2 408.34 512.2 386.95C500.87 386.95 492.28 373.9 484.76 366.38L474.85 359.52C455.79 359.52 436.76 323.75 429.12 308.46L426.07 306.17C418.92 299.03 398.63 291.36 398.63 290.17C366.82 290.17 342.99 316.07 342.99 343.52L333.84 349.61C319.35 349.61 314.79 370.45 314.79 383.14C310.87 383.14 293.45 429.84 293.45 439.54C292.61 439.54 283.34 470.21 286.59 479.93C287.86 482.98 289.13 486.03 290.4 489.08L305.64 509.65L359.76 525.66L375 505.84C376.27 502.54 377.54 499.24 378.81 495.93L378.81 478.41Z" />
                                <text x="350" y="400">Janum</text>
                            </g>
                            <g data-info="Barasisirum" data-geo-id="106">
                                <path d="M421.49 299.31C438.8 299.31 470.88 304.04 482.47 292.45L487.04 290.93C498.8 290.93 563.08 293.03 567.07 297.03L614.33 297.03L634.15 291.69C644.96 302.5 657.01 369.48 657.01 389.24L647.1 395.34L595.27 404.48L572.41 413.63C572.41 420.56 520.33 450.97 513.72 450.97C494 450.97 467.99 419.14 467.99 399.15C466.43 399.15 448.83 383.69 446.65 379.33C443.7 379.33 436.74 367.57 436.74 364.85C423.12 364.85 419.97 316.75 419.97 300.08L423.02 299.31L421.49 299.31Z" />
                                <text x="470" y="360">Berasisirum</text>
                            </g>
                            <g data-info="Paregama" data-geo-id="107">
                                <path d="M494.66 450.97C500.3 449.56 500.76 441.92 500.76 436.49L516.01 420.49L544.21 409.06C554.73 409.06 569.54 405.06 576.22 398.39C593.24 398.39 612.72 391.61 622.71 381.62L655.49 380.1L663.87 385.43C663.87 390.11 668.45 394.66 668.45 397.62C673.27 398.89 678.1 400.16 682.93 401.43L748.48 406.01L748.48 411.34C748.48 413.06 718.86 439.54 714.94 439.54C711.38 444.62 707.82 449.7 704.27 454.78C704.27 468.25 688.26 486.05 688.26 500.51C674.99 500.51 657.95 504.32 641.77 504.32C637.96 497.97 634.15 491.62 630.34 485.27L589.94 484.5C574.65 489.6 564.7 498.22 547.26 498.22C541.34 498.22 516.77 483.43 516.77 479.93C498.62 479.93 488.57 468.1 488.57 451.73L492.76 454.59C492.36 449.97 491.69 449.45 495.43 449.45L492.76 454.59L496.19 452.49L494.66 450.97Z" />
                                <text x="580" y="450">Paregama</text>
                            </g>
                            <g data-info="Odia" data-geo-id="108">
                                <path d="M365.85 515.75C365.85 497.94 371.19 483.5 371.19 467.74C376.56 462.36 376.5 454.81 381.86 449.45L383.38 447.92C398.26 447.92 418.02 421.25 426.07 421.25L428.35 419.72C448.93 419.72 470.37 415.91 492.38 415.91L502.29 420.49L507.62 422.01C513.09 422.01 532.01 433.67 532.01 435.73C544.07 435.73 560.98 439.53 560.98 453.26C570.62 453.26 583.84 485.03 583.84 493.65L563.26 494.41C563.26 499.19 554.65 522.61 553.35 522.61C553.35 555.53 503.04 555.99 475.61 561.47C468.62 562.87 431.52 569.21 426.07 563.76C426.07 557.35 422.26 547.84 422.26 541.66L411.59 535.56C395.05 535.56 365.09 530.65 365.09 513.46C365.35 514.22 365.6 514.99 365.85 515.75Z" />
                                <text x="450" y="500">Odia</text>
                            </g>
                        </svg>
                    </div>
                </div>
                <input class="form-control" id="geo_id">
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
                <div style="margin-top: 4px; margin-left: auto; order: 2; display:inline-block;">
                    <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success"><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                    <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                    <a href="{{url('asset-review/pdf/pdfURL')}}" class="asset-review-export-as" data-toggle="tooltip" title="Export as PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                    <a href="{{url('asset-review/export/excelURL')}}" class="asset-review-export-as" data-toggle="tooltip" title="Export as Excel"><button type="button" class="btn btn-icon btn-round btn-success" ><i class="fas fa-file-excel"></i></button></a>
                </div>
            </ul>
            <hr>
            <div id="all-view-details-filter" class="printable-area" style="display: none; overflow:hidden; background: #efefef; padding: 10px 10px; border-radius: 5px;">
                <div id="all-view-details" style="display:inline-block; width: 50%;">
                </div>
            </div>
            <div class="tab-content mt-2 mb-3" id="myTabContent">
                <!-- tabular-view -->
                <div class="tab-pane fade show active printable-area" id="tabular-view-tab" role="tabpanel">
                    <div id="tabular-view">
                    </div>
                    <div class="no-data">
                        <i class="fas fa-info-circle text-success"></i>&nbsp;&nbsp;No assets data to show
                    </div>
                </div>
                <!-- graphical-view -->
                <div class="tab-pane fade printable-area" id="graphical-view-tab" role="tabpanel">
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
                <div class="tab-pane fade printable-area" id="map-view-tab" role="tabpanel">
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
                        <i class="fas fa-info-circle text-success"></i>&nbsp;&nbsp;No geo locations found
                    </div>
                </div>

                <!-- gallery-view -->
                <div class="tab-pane fade printable-area" id="gallery-view-tab" role="tabpanel">
                    <!-- <h4>Gallery View&nbsp;<button type="button" class="btn btn-secondary btn-sm print-button" onclick="printReview('map')">Print&nbsp;<i class="fa fa-print" aria-hidden="true"></i></button></h4> -->
                    <div id="gallery-view">
                        <!--  -->
                    </div>
                    <div class="no-data" style="width: 100%; height: 400px; border-radius: 8px;">
                        <i class="fas fa-info-circle text-success"></i>&nbsp;&nbsp;No images found
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</div>


<!-- for review-for (block, punchayat) radio buttons -->
<script>
    var to_export_datas = "";
    var review_for = 'block';
    var selected_panchayat = new Array;
    var panchayat_form_data_received = false;

    $(document).ready(function () {
        $("input[name=review_for]").change(function () {
            review_for_toggle();
        });
        $(".review-for-buttons").click(function () {
            $(".review-for-buttons").removeClass("active");
            $(this).addClass('active');
        });

        // for selected_panchayat and reset the value of panchayat_id
        $("#review-for-panchayat-form").delegate("select", "change", function () {
            var selected_panchayat = $("#review-for-panchayat-form select").map(function () {
                if ($(this).val() != "") {
                    return $(this).val();
                }
            }).get();
            $("#panchayat_id").val(selected_panchayat);
        });
    });

    function review_for_toggle() {
        // resetting every view
        resetTabularView();
        resetGraphicalView();
        resetMapView();
        resetGalleryView();
        resetCommon(); // to reset common things among all views
        resetMapContent(); // reset map content, remove active class form all block/panchayat paths, geo_id = null, geo selected = null
        review_for = $("input[name=review_for]:checked").val();
        if (review_for == "block") {
            $("#review-for-panchayat-form").hide();
            panchayat_form_data_received = false;
        }
        else if (review_for == "panchayat") {
            $("#review-for-panchayat-form").html("");
            $("#review-for-panchayat-form").show();
        }
    }
</script>

<script>
    // for svg's/ map contents
    var selected_geo = new Array;
    var panchayat_map_content_opened = false; // if panchayat map is opened/displays

    $(".block-map-content g").click(function () {
        if(review_for=="panchayat"){
             /****** for panchayat selection ******/
            // var clicked_block_position = $(this).offset();
            // top_pos = clicked_block_position.top - $(this).closest('div').offset().top;
            // left_pos = clicked_block_position.left - $(this).closest('div').offset().left;
            // $(".panchayat-map-content").css({"top": top_pos-150, "left": left_pos-150});
            panchayat_target = $(this).data("panchayat-target");
            $(".panchayat-map-content svg").css("display", "none"); // hide all svg first
            $(".panchayat-map-content #panchayat-"+panchayat_target).css("display", "inline-block"); // show respective svg
            $(".block-map-content").toggleClass("active");
            $(".panchayat-map-content").toggleClass("active");
            panchayat_map_content_opened = true;
        }
        else{
            var geo_id_tmp = $(this).data('geo-id').toString();
            if (selected_geo.includes(geo_id_tmp)) {
                $(this).children('path').removeClass("active");
                selected_geo.splice(selected_geo.indexOf(geo_id_tmp), 1);
                $("#geo_id").val(selected_geo);
            }
            else {
                $(this).children('path').addClass("active");
                selected_geo.push(geo_id_tmp);
                $("#geo_id").val(selected_geo);
            }
        }

        panchayat_form_data_received = false; // if any block changed then we assigned panchayat_form_data_received as "false" so "change block + search" load panchayat form data only not views datas
    });
    $(".panchayat-map-content g").click(function () {
        if(review_for=="panchayat"){
            var geo_id_tmp = $(this).data('geo-id').toString();
            if (selected_geo.includes(geo_id_tmp)) {
                $(this).children('path').removeClass("active");
                selected_geo.splice(selected_geo.indexOf(geo_id_tmp), 1);
                $("#geo_id").val(selected_geo);
            }
            else {
                $(this).children('path').addClass("active");
                selected_geo.push(geo_id_tmp);
                $("#geo_id").val(selected_geo);
            }
        }

        panchayat_form_data_received = false; // if any block changed then we assigned panchayat_form_data_received as "false" so "change block + search" load panchayat form data only not views datas
    });
    function resetMapContent(){ // reset map content, remove active class form all block/panchayat paths, geo_id = null, geo selected = null
        $(".map-content-all path").removeClass("active");
        selected_geo = [];
        $("#geo_id").val("");
    }

    $(document).ready(function(){
        $(document).mouseup(function(e){
            if(panchayat_map_content_opened)
            {
                var container = $(".panchayat-map-content svg g");
                if (!container.is(e.target) && container.has(e.target).length === 0) 
                {
                    $(".block-map-content").toggleClass("active");
                    $(".panchayat-map-content").toggleClass("active");
                    panchayat_map_content_opened = false;
                }
            }
        });
    });

    // removing is-invalid on change
    $(document).ready(function () {
        $("#dept_id").change(function () {
            if ($("#dept_id").val()) {
                $("#dept_id").removeClass('is-invalid');
                resetTabularView();
                resetGraphicalView();
                resetMapView();
                resetGalleryView();
                resetCommon(); // to reset common things among all views
            }
        });
        $("#year_id").change(function () {
            if ($("#year_id").val()) {
                $("#year_id").removeClass('is-invalid');
                resetTabularView();
                resetGraphicalView();
                resetMapView();
                resetGalleryView();
                resetCommon(); // to reset common things among all views
            }
        });
        $("#geo_id").change(function () {
            if ($("#geo_id").val()) {
                $("#geo_id").removeClass('is-invalid');
                resetTabularView();
                resetGraphicalView();
                resetMapView();
                resetGalleryView();
                resetCommon(); // to reset common things among all views
            }
        });
    });

    // next after search button pressed
    function search() {
        var dept_id_error = true;
        var year_id_error = true;
        var geo_id_error = true;
        var panchayat_id_error = true;

        // department
        if ($("#dept_id").val() == "") {
            $("#dept_id").addClass('is-invalid');
            dept_id_error = true;
        }
        else {
            $("#dept_id").removeClass('is-invalid');
            dept_id_error = false;
        }

        // year
        if ($("#year_id").val() == "") {
            $("#year_id").addClass('is-invalid');
            year_id_error = true;
        }
        else {
            $("#year_id").removeClass('is-invalid');
            year_id_error = false;
        }

        // geo/block selected
        if ($("#geo_id").val() == "") {
            $("#geo_id").addClass('is-invalid');
            geo_id_error = true;
        }
        else {
            $("#geo_id").removeClass('is-invalid');
            geo_id_error = false;
        }

        // panchayat selected
        if (review_for == "panchayat" && panchayat_form_data_received) {
            if ($("#panchayat_id").val() == "") {
                $("#panchayat_id").addClass('is-invalid');
                panchayat_id_error = true;
            }
            else {
                $("#panchayat_id").removeClass('is-invalid');
                panchayat_id_error = false;
            }
        }
        else {
            $("#panchayat_id").removeClass('is-invalid');
            panchayat_id_error = false;
        }

        if (!dept_id_error && !year_id_error && !geo_id_error && !panchayat_id_error) {
            // if not error then getting datas from controller
            if (review_for == "block") {
                getDatas(); // all view datas
            }
            else { // review_for == panchayat, for panchayat, to get all panchayats of each selected blocks
                if (panchayat_form_data_received) { // if panchayat data already reaceived
                    getDatas();
                }
                else {
                    // resetting all views because we are now getting panchayat datas
                    resetTabularView();
                    resetGraphicalView();
                    resetMapView();
                    resetGalleryView();
                    resetCommon(); // to reset common things among all views
                    getPanchayatDatas(); // getting panchayat datas
                }
            }
        }
        else{
            // resetting all views because we are now getting panchayat datas
            resetTabularView();
            resetGraphicalView();
            resetMapView();
            resetGalleryView();
            resetCommon(); // to reset common things among all views
        }
    }

    function getDatas() {
        // getting datas before send to controller
       
        dept_id = $("#dept_id").val();
        year_id = $("#year_id").val();
        geo_id = $("#geo_id").val(); // string, have convert to array in controller// block ids

        panchayat_id = "";// string, panchayat data if review_for==panchayat
        if (review_for == "panchayat") { panchayat_id = $("#panchayat_id").val(); }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset-review/get-datas')}}",
            data: { 'review_for': review_for, 'geo_id': geo_id, 'panchayat_id': panchayat_id, 'dept_id': dept_id, 'year_id': year_id },
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function (data) {
                $(".custom-loader").fadeIn(300);
            },
            error: function (xhr) {
                alert("error" + xhr.status + ", " + xhr.statusText);
                $(".custom-loader").fadeOut(300);
            },
            success: function (data) {
                console.log(data);
                // resetting all view's blocks/divs/inputs
                resetTabularView();
                resetGraphicalView();
                resetMapView();
                resetGalleryView();
                resetCommon(); // to reset common things among all views
                if (data.response == "no_data") { // no data found
                    to_export_datas = "";
                }
                else { // data.response  == success
                    // calling/initialiazing all views
                    to_export_datas = data.tabular_view;
                    initializeTabularView(data.tabular_view);
                    intializeGraphicalView(data.chart_labels, data.chart_datasets);
                    initializeMapView(data.map_view_blocks, data.map_view_assets);
                    initializeGalleryView(data.gallery_images);
                    
                    // all-view-details
                    initialiteCommon();
                    $("#all-view-details").html("<b>Department: </b>"+$("#dept_id option:selected").text());
                    $("#all-view-details").append("<br/><b>Year: </b>"+$("#year_id option:selected").text());
                }
                $(".custom-loader").fadeOut(300);
            }
        });
    }


    function getPanchayatDatas() {
        geo_id = $("#geo_id").val(); // string, have convert to array in controller
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset-review/get-panchayat-data')}}",
            data: { 'geo_id': geo_id },
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function (data) {
                $(".custom-loader").fadeIn(300);
                panchayat_form_data_received = false;
            },
            error: function (xhr) {
                alert("error" + xhr.status + ", " + xhr.statusText);
                $(".custom-loader").fadeOut(300);
                panchayat_form_data_received = false;
            },
            success: function (data) {
                console.log(data);
                if (data.response == "no_data") { // no data found

                }
                else { // data.response  == success
                    var to_append = "";
                    $("#review-for-panchayat-form").html("");

                    for (var i = 0; i < data.data.length; i++) {
                        to_append += `<div class="col-6">
                                        <div class="form-group">`;
                        to_append += `<label>` + data.data[i].block_name + `</label>
                                        <select id="panchayat-select-`+ i + `" class="form-control" multiple>
                                        <option value="">-Select-</option>`;

                        for (var j = 0; j < data.data[i].panchayat_data.length; j++) {
                            to_append += `<option value="` + data.data[i].panchayat_data[j].geo_id + `">` + data.data[i].panchayat_data[j].geo_name + `</option>`;
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
    function initializeTabularView(data) {
        // data is multidimensional array, each row for each table row
        var toShowTabularForm = "<table class='table table-striped table-bordered table-datatable table-sm'>";
        for (var i = 0; i < data.length; i++) {
            if (i == 0) { // for first row
                toShowTabularForm = toShowTabularForm + "<tr class='table-secondary'>";
            }
            else { // for others
                toShowTabularForm = toShowTabularForm + "<tr>";
            }

            for (var j = 0; j < data[0].length; j++) {
                if (i == 0) {  // for first row i.e th
                    if (data[i][j] == "") { // for first row of first index name by raj
                    toShowTabularForm = toShowTabularForm + "<th> Name </th>";
                    } else {
                    toShowTabularForm = toShowTabularForm + "<th>" + data[i][j] + "</th>";
                    }
                }
                else { // for others
                    toShowTabularForm = toShowTabularForm + "<td>" + data[i][j] + "</td>";
                }
            }
            toShowTabularForm = toShowTabularForm + "</tr>";
        }
        toShowTabularForm = toShowTabularForm + "</table";
        $("#tabular-view").append(toShowTabularForm);
        $("#tabular-view").show();
        $("#tabular-view + .no-data").hide();
    }
    function resetTabularView() {
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
    function showGraph() {
        assetChartCall = new Chart(assetChart, {
            type: 'bar',
            data: chart_data,
            options: {
                offset: true,
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
                        label: function (tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';

                            if (tooltipItem.yLabel <= 0.5) {
                                label += " : 0";
                            }
                            else {
                                label += " : " + tooltipItem.yLabel;
                            }
                            return label;
                        }
                    }
                }
            }
        });
    }
    function intializeGraphicalView(chart_labels, chart_datasets) {
        var labels = [];
        var datasets = [];
        var backgroundColor = [];
        var borderColor = [];

        labels = chart_labels; // labels i.e block names

        for (var i = 0; i < chart_datasets.length; i++) {
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
    function resetGraphicalView() {
        $("#graphical-view").hide();
        $("#graphical-view + .no-data").show();
    }
    function getRandomColor() {
        var color = ['#a6ffc9', '#5eff9e']; //['brighter', 'darker']
        var random_tmp = Math.floor(Math.random() * (360 - 0 + 1)) + 0;
        color[0] = 'hsl(' + random_tmp + ',76%,70%)';
        color[1] = 'hsl(' + random_tmp + ',76%,50%)';
        return color;
    }
</script>


<!-- for map-view -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuYbCxfGw_c6lasAlpExIOFj55MVY6xSo"></script>
<script>
    var marker_icon = "";
    function initializeMapView(map_view_blocks, map_view_assets) {
        $("#map-view-block-title").html(review_for);

        $("#map-view-block").html('<option value="">-Select-</option>');
        for (var i = 0; i < map_view_blocks.length; i++) {
            $("#map-view-block").append('<option value="' + map_view_blocks[i].id + '">' + map_view_blocks[i].name + '</option>');
        }
        $("#map-view-asset").html('<option value="">-Select-</option>');
        for (var i = 0; i < map_view_assets.length; i++) {
            $("#map-view-asset").append('<option value="' + map_view_assets[i].id + '">' + map_view_assets[i].name + '</option>');
        }

        $("#map-view").show();
        $("#mapCanvas").hide();
        $("#map-view + .no-data").show();
    }
    // validation reset
    $(document).ready(function () {
        $("#map-view-block").change(function () {
            $(this).removeClass("is-invalid");
        });
        $("#map-view-asset").change(function () {
            $(this).removeClass("is-invalid");
        });
    });
    function mapSearch() {
        // validation for map-select/ map-inputs
        var errorMapSearch = false;
        var mapViewBlock = $("#map-view-block").val();
        var mapViewAsset = $("#map-view-asset").val();
        var mapViewYear = $("#year_id").val();

        if (mapViewBlock == "") {
            $("#map-view-block").addClass("is-invalid");
            errorMapSearch = true;
        }
        else {
            $("#map-view-block").removeClass("is-invalid");
        }

        if (mapViewAsset == "") {
            $("#map-view-asset").addClass("is-invalid");
            errorMapSearch = true;
        }
        else {
            $("#map-view-asset").removeClass("is-invalid");
        }

        /* ajax */
        if (errorMapSearch == false) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('asset-review/get-map-data')}}",
                data: { 'review_for': review_for, 'geo_id': mapViewBlock, 'asset_id': mapViewAsset, 'year_id': mapViewYear },
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function (data) {
                    $(".custom-loader").fadeIn(300);
                },
                error: function (xhr) {
                    alert("error" + xhr.status + ", " + xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function (data) {
                    console.log(data);
                    if (data.response == "no_data") { // no data found
                        $("#map-view").show();
                        $("#mapCanvas").hide();
                        $("#map-view + .no-data").show();
                    }
                    else { // data.response == success
                        $("#map-view").show();
                        $("#mapCanvas").show();
                        $("#map-view + .no-data").hide();
                        if (data.icon) {
                            marker_icon = data.icon;
                        }
                        else {
                            marker_icon = null;
                        }
                        showMap(data.map_data);
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }
    function showMap(data) {
        var icon = {
            url: marker_icon, // url
            scaledSize: new google.maps.Size(50, 50), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };
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
        $.each(data, function () {
            //Plot the location as a marker
            var theposition = new google.maps.LatLng(this.latitude, this.longitude);
            var marker = new google.maps.Marker({
                position: theposition,
                map: map,
                title: 'Uluru (Ayers Rock)',
                icon: icon,
                animation: google.maps.Animation.DROP
            });


            var contentString = '<div id="content">' +
                '<div id="siteNotice">' +
                '</div>' +
                '<h4 id="firstHeading" class="firstHeading">' + this.location_name + '</h4>' +
                '<div id="bodyContent">' +
                '<p><b>Panchayat:</b> ' + this.geo_name + '</p>' +
                '</div>' +
                '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });



            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });
        });
        //map.setCenter(centerLat,centerLng);
        map.setCenter(new google.maps.LatLng(centerLat, centerLng));
    }
    function resetMapView() {
        $("#map-view").hide();
        $("#mapCanvas").hide();
        $("#map-view + .no-data").show();
        $("#map-view-block").removeClass("is-invalid");
        $("#map-view-asset").removeClass("is-invalid");
    }
</script>

<script>

    function initializeGalleryView(gallery_images){
        if(gallery_images.length!=0){
            var to_append_images = "";
            for(i=0;i<gallery_images.length;i++){
                for(j=0;j<gallery_images[i].images.length;j++)
                {
                    to_append_images += `<div class="gallery-view-image-thumb">
                            <img src="`+gallery_images[i].images[j]+`">
                            <div class="gallery-view-image-thumb-labels">
                                <span class="gallery-view-image-thumb-asset-name"><b>Asset:</b> `+gallery_images[i].asset_name+`</span>
                                <span class="gallery-view-image-thumb-geo-name"><b>Block:</b> `+gallery_images[i].block_name+`<span>
                                <span class="gallery-view-image-thumb-geo-name"><b>Panchayat:</b> `+gallery_images[i].panchayat_name+`<span>
                            </div>
                        </div>`;
                }
            }
            $("#gallery-view").html(to_append_images);
            $("#gallery-view").show();
            $("#gallery-view + .no-data").hide();
        }
        else{
            resetGalleryView();
        }
        console.log(gallery_images);
    }

    function resetGalleryView() {
        $("#gallery-view").hide();
        $("#gallery-view + .no-data").show();
    }
</script>

<script>
    function resetCommon(){
        $("#all-view-details-filter").hide();
        $("#all-view-details").html("");
    }
    function initialiteCommon(){
        $("#all-view-details-filter").show();
        $("#all-view-details").html("Initiate");
    }
</script>

<script>
    // for printing 
    function printReview(type) {
        // initializing/ resetting printing area
        $("#tabular-view-tab").removeClass('printable-area');
        $("#graphical-view-tab").removeClass('printable-area');
        $("#map-view-tab").removeClass('printable-area');

        // assigning/ set printable-area class to print area
        if (type == "tabular") {
            $("#tabular-view-tab").addClass("printable-area");
        }
        else if (type == "graphical") {
            $("#graphical-view-tab").addClass("printable-area");
        }
        else if (type == "map") {
            $("#map-view-tab").addClass("printable-area");
        }

        window.print();
    }
</script>

<script>
    // export to pdf / excel
    $(document).ready(function(){
        $('.asset-review-export-as').click(function(e) {
            e.preventDefault();
            var href = this.href;
            if(to_export_datas.length!=0)
            {
                console.log(JSON.stringify(to_export_datas));
                window.location.href = ""+href+"?datas=" + JSON.stringify(to_export_datas);
            }
        });
    });
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