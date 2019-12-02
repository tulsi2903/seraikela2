@extends('layout.layout')

@section('title', 'Scheme Review')

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

    </style>
@endsection

@section('page-content')
<div class="row">
    <div class="col-md-4">
        <div class="card"   style="border-top: 3px solid #5167a0;">
            <div class="card-header">
                <h4 class="card-title">Search</h4>
            </div>
            <div class="card-body">
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
                            <label for="panchayat_id">Panchayat<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="panchayat_id" id="panchayat_id" class="form-control">
                                <option value="">-Select-</option>
                                @foreach($panchayat_datas as $panchayat_data)
                                    <option value="{{$panchayat_data->geo_id}}">{{$panchayat_data->geo_name}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select Panchayat</div>
                        </div>
                    </div>
                </div>
                <hr class="new2">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary float-right" onclick="search()"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-md-8">
        <div class="card">
            <div class="card-body"  style="border-top: 3px solid #5167a0; min-height: 500px;">
                
            </div>
        </div>
    </div>
</div>


@endsection