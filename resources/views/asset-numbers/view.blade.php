@extends('layout.layout')

@section('title', 'Resource Number Details')

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
       
            <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
                <div class="col-md-12">   
                    <div class="card-title" style="float:left; margin-top: 11px;">{{$phrase->resource_number}}</div><br><br>
                    <hr class="new2">
                    <div class="card-body" style="margin-top: -35px;"> 
                        <table class="table table-striped mt-3">
                            <tbody>
                                <tr>
                                    <th>{{$phrase->year}}</th>
                                    <th>{{$phrase->resource}}</th>
                                    <th>{{$phrase->block}}</th>
                                    <th>{{$phrase->panchayat}}</th>
                                    <th>{{$phrase->current_value}}</th>
                                </tr>
                                <tr>
                                    <td>{{$asset_numbers->year_value}}</td>
                                    <td>{{$asset_numbers->asset_name}}</td>
                                    <td>{{$asset_numbers->block_name}}</td>
                                    <td>{{$asset_numbers->geo_name}}</td>
                                    <td>{{$asset_numbers->current_value}}</td>
                                </tr>
                            </tbody>
                        </table>
                        @if(count($asset_locations)!=0)
                            <div class="col-md-12">
                                <button class="btn"  style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Resource Locations</button>
                                    <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                                    <table id="basic-datatables" class=" table order-list" style="margin-top: 10px;">
                                        <thead style="background: #cedcff">                                              
                                            <tr>
                                                <th>{{$phrase->location_landmark}}</th>
                                                <th>{{$phrase->latitude}}</th>
                                                <th>{{$phrase->longitude}}</th>                                                
                                            </tr>
                                        </thead> 
                                        @foreach($asset_locations as $asset_location)                                  
                                        <tbody> 
                                            <td>{{$asset_location->location_name}}</td>
                                            <td>{{$asset_location->latitude}}</td>
                                            <td>{{$asset_location->longitude}}</td>
                                        </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                        @if($images)
                            <div class="col-ms-12">
                                <div id="images-block" style="padding: 15px 10px;">
                                    <span class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-images"></i>&nbsp;&nbsp;Gallery</span>
                                    <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    @foreach($images as $image)
                                                        <div class="images-delete-block" style="margin-right:5px; display:inline-block; position:relative; padding:3px;border:1px solid #c4c4c4;border-radious:3px;">
                                                            <img src="{{url($image)}}" style="height:150px; min-height:150px; min-width:100px;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        @endif                
                    </div>   
                </div>
            </div>
       
@endsection