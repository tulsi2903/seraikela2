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

                        <label for="multiple_geo_tags">{{$phrase->multiple_geo_tags}}</label>
                        <br>
                        <?php 
                            if ($data['multiple_geo_tags']==1) 
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
                        <label for="no_of_tags">{{$phrase->no_of_tags}}</label>
                        <br><?php echo $data->no_of_tags ?: '-'; ?>
                        <div class="invalid-feedback" id="no_of_tags_error_msg"></div>
                    </div>
                </div>

            </div>
            <!--end of row-->

            <hr/>

            <button class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;{{$phrase->attributes}}</button>
            <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                <table class="table order-list" style="margin-top: 10px;">
                    <thead style="background: #cedcff">
                        <tr>
                            <th>{{$phrase->name}}</th>
                            <th>{{$phrase->uom}}</th>
                            <th width="130px;">{{$phrase->mandatory}}</th>
                        </tr>
                    </thead>
                    <tbody id="append-name-uom">
                        @if($data->attribute)
                            <?php 
                                $attributes  = unserialize($data->attribute);
                                foreach($attributes as $attribute)
                                { 
                                    ?>
                                        <tr>
                                            <td>
                                                {{$attribute['name']}}
                                            </td>
                                            <td>
                                                @foreach($uom_datas as $uom_data)
                                                    <?php if($uom_data->uom_id==$attribute['uom']){ echo $uom_data->uom_name; } ?>
                                                @endforeach
                                            </td> 
                                            <td>
                                                <?php if($attribute['mandatory']==1){ echo "Yes"; } else { echo "No"; } ?>
                                            </td>
                                        </tr>
                                    <?php
                                $i++;
                                }
                            ?>
                        @endif
                    </tbody>

                </table>
            </div>

        </div>
        <!--end of card body-->
    </div>

    @endsection