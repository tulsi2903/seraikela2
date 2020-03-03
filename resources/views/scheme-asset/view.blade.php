@extends('layout.layout')

@section('title', 'Scheme Asset')

@section('page-style')
<style>

</style>
@endsection

@section('page-content')
<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">{{$phrase->scheme_assets}}</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-asset')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;{{$phrase->back}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="scheme_asset_name">{{$phrase->name}}</label>
                        <br>{{$data->scheme_asset_name}}

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="geo_related">{{$phrase->geo_related}}</label>&nbsp;&nbsp;
                        <br>
                        <?php 
                            if ($data['geo_related']==1 ) 
                            {
                                echo "Yes";
                            }
                                else{ echo "No";
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="geo_related">{{$phrase->geo_related}}</label>&nbsp;&nbsp;
                        <br>
                        <?php 
                            if ($data['geo_related']==1 ) 
                            {
                                echo "Yes";
                            }
                                else{ echo "No";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!--end of row-->

        </div>
        <!--end of card body-->
    </div>

    @endsection