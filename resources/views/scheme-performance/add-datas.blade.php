@extends('layout.layout')

@section('title', 'Scheme Performance')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Scheme Performance</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-performance')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div style="display: inline-block; width: 150px; float: left; ">
                        <img src="{{url($scheme_data->scheme_logo)}}" style="max-width: 100%;">
                    </div>
                    <div style="display: inline-block; padding-left: 20px;;">
                        <h4 style="color: black;">({{$scheme_data->scheme_short_name}}) {{$scheme_data->scheme_name}}</h4>
                        <b>Asset:</b> {{$scheme_asset_data->scheme_asset_name}}
                        <br/><b>Block:</b> {{$block_data->geo_name}}
                        <br/><b>Panchayat:</b> {{$panchayat_data->geo_name}}
                        <br/><b>Year:</b> {{$year_data->year_value}}
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-12">
                    <button type="button" class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Enter Details</button>
                    <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                        <div style="padding: 15px 0; overflow: hidden; color: black;">
                            <div style="display: inline-block; float: left; font-size: 16px;">
                                <b>Data Saved:</b> {{count($scheme_performance_datas)}}
                            </div>
                            <a href="{{url('scheme-performance/viewimport')}}?scheme_id={{$scheme_data->scheme_id}}&year_id={{$year_data->year_id}}&block_id={{$block_data->geo_id}}" class="btn" style="float:right; background: #349601; color: white;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;Import</a>
                        </div>
                        <table class="table">
                            <thead style="background: #cedcff">
                                <tr>
                                    <?php
                                    // for attributes
                                    $attributes  = unserialize($scheme_asset_data->attribute);
                                    foreach($attributes as $attribute)
                                    {
                                        ?>
                                        <th>{{$attribute['name']}}</th>
                                        <?php
                                        
                                    }

                                    // for coordinates
                                    if($scheme_asset_data->geo_related==1){
                                        ?>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <?php
                                    }
                                    ?>
                                    <th>Status</th>
                                    <th>Gallery</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="enter-details-append">
                                <!-- append details -->
                            </tbody>
                        </table>
                        <div style="text-align: right;">
                            <button type="button" class="btn btn-secondary btn-sm btn-circle" onclick="appendRow()">Add More&nbsp;&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                        </div>
                        <hr/>
                        <button class="btn btn-secondary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var to_append = `
        <?php
            ?><tr><?php
                // for attributes
                $attributes  = unserialize($scheme_asset_data->attribute);
                foreach($attributes as $attribute)
                {
                    ?>
                    <td><input type="text" name="{{$attribute['id']}}[]" class="form-control" placeholder="{{$attribute['name']}}"></td>
                    <?php
                    
                }

                // for coordinates
                if($scheme_asset_data->geo_related==1){
                    ?>
                    <td><input type="text" name="latitude[]" class="form-control" placeholder="latitude"></td>
                    <td><input type="text" name="longitude[]" class="form-control" placeholder="longitude"></td>
                    <?php
                }
                ?>
                <td>
                    <select name="status" class="form-control">
                        <option value="0">Ongoing</option>
                        <option value="1">Completed</option>
                    </select>
                </td>
                <td>
                    <a href="javascript:void();"><i class="fas fa-plus"></i>Images</a>
                </td>
                <td>
                    <input type="text" name="comments[]" class="form-control" placeholder="comments">
                </td>
                <td><button type="button" class="btn btn-danger btn-xs delete-button-row"><i class="fas fa-trash-alt"></i></button></td><?php
            ?></tr><?php
        ?>
    `;

    function appendRow(){
        $("#enter-details-append").append(to_append);
    }

    appendRow();
    appendRow();

    $(document).ready(function() {
        $("#enter-details-append").delegate(".delete-button-row", "click", function() {
            swal({
                title: 'Are you sure?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                buttons:{
                    cancel: {
                        visible: true,
                        text : 'No, cancel!',
                        className: 'btn btn-danger'
                    },
                    confirm: {
                        text : 'Yes, delete it!',
                        className : 'btn btn-success'
                    }
                }
            }).then((willDelete) => {
                if (willDelete) {
                    $(this).closest("tr").remove();
                }
            });
        });
    });

</script>
<script>
function update_image()
{
    alert("fdfdf");
}
</script>

@endsection
