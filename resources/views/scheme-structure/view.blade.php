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

<div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
    <div class="col-md-12">
        <div class="card-title" style="float:left; margin-top: 11px;">Scheme Details</div><br><br>
        <hr class="new2">
        <div class="card-body" style="margin-top: -35px;">

            <table class="table table-striped mt-3">
                <tbody>
                    <tr>
                        <th>Scheme Name</th>
                        <th>Short Name</th>
                        <th>Scheme Type</th>
                        <th>Department</th>
                        <th>Status(is_active)</th>
                    </tr>

                    <tr>
                        <td>{{$scheme_details->scheme_name}}</td>
                        <td>{{$scheme_details->scheme_short_name}}</td>
                        <td>{{$scheme_types->sch_type_name}}</td>
                        <td>{{$departments->dept_name}}</td>

                        <td>{{$scheme_details->is_active}}</td>
                    </tr>
                    <tr>
                        <th colspan="5">Description</th>
                    </tr>
                    <tr style="height: 85px;">
                        <td colspan="5">{{$scheme_details->description}}</td>
                    </tr>

                    <tr>
                        <th colspan="2">Attachment</th>
                        <th colspan="2">Scheme Logo</th>
                        <th colspan="1">Map Marker Icon</th>
                    </tr>
                    <tr>
                        <td colspan='2'>
                            <?php

                                    $attachment_array = explode(":",$scheme_details->attachment);
                                    for($i=0;$i<count($attachment_array);$i++)
                                    {
                                        ?>
                            <a
                                href="{{url('public/uploaded_documents/')}}/{{$attachment_array[$i]}}">{{$attachment_array[$i]}}</a>
                            <br>

                            <?php
                                        
                                        

                                    }
                                    ?>
                        </td>



                        <td colspan='2'><img src="{{url('public/images')}}/{{$scheme_details->scheme_logo}}"
                                style="max-height:200px;"></td>
                        <td colspan='1'><img src="{{url('public/images')}}/{{$scheme_details->scheme_map_marker}}"
                                style="max-height:200px;"></td>


                    </tr>

                </tbody>
            </table>

            <div class="col-md-12">
                <button class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i
                        class="fas fa-sort-amount-up"></i> &nbsp;Indicator</button>
                <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                    <table id="basic-datatables" class=" table order-list" style="margin-top: 10px;">
                        <thead style="background: #cedcff">
                            <tr>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <?php if(count($indicator_datas)!=0){ ?>
                        @foreach($indicator_datas as $indicator_data)
                        <tbody>


                            <td>{{$indicator_data->indicator_name}}</td>
                            <td>{{$indicator_data->uom_name}}</td>
                            <td>{{$indicator_data->performance}}</td>

                        </tbody>
                        @endforeach
                        <?php }?>
                    </table>

                </div>
            </div>
            <br>
            <a href="{{url('scheme-structure')}}" class="btn btn-danger" style="float:right;">Cancel</a>
        </div>
    </div>
</div>


@endsection