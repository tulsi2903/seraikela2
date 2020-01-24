@extends('layout.layout')

@section('title', 'Scheme Performance')

@section('page-style')
<style>
    .modal-content {
    position: relative;
    display: -webkit-box;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    /* background-color: #fff; */
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: .3rem;
    outline: 0;
    background: linear-gradient(to bottom, #a5baef, #ffffff 70%, #ffffff, #ffffff 100%);
}
.modal-header {
    display: -webkit-box;
    display: flex;
    -webkit-box-align: start;
    align-items: flex-start;
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px dashed #000;
    border-top-left-radius: .3rem;
    border-top-right-radius: .3rem;
}
.modal-footer {
    display: -webkit-box;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: end;
    justify-content: flex-end;
    padding: 1rem;
    border-bottom: 1px dashed #999999;
    border-bottom-right-radius: .3rem;
    border-bottom-left-radius: .3rem;
    margin-top: -24px;
}

</style>
@endsection

@section('page-content')



<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"style="margin-top: 11em;">
            <div class="modal-header" style="border-top: 2px solid #5269a3">
                <h4 class="modal-title mt-0" style="font-family: 'Bree Serif', serif;color:#000;">Panchyat details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('scheme_performance/coordinatesupdate')}}" method="post" id="FormsaveImagescoordinatesLoacation" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row" style="padding:2em; margin-top: -3em;">
                        <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                            <thead>
                                <tr>
                                    <!-- <th>SI.No</th> -->
                                    <th>Block</th>
                                    <th>Panchyat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="append_coordinate_section">
                                <!-- append details -->
                            </tbody>
                            <tbody>
                                <tr>
                                    <!-- <td></td> -->
                                    <td>                                      
                                        <div class="form-group">
                                            <select name="block_id" id="block_id" class="form-control">
                                                <option value="">---Select---</option>
                                                @foreach($block_datas as $blocks)
                                                <option value="{{$blocks->geo_id }}">{{ $blocks->geo_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="block_id_error_msg"></div>
                                        </div>                                     
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <!-- <label for="panchayat_id">{{$phrase->panchayat}} <span style="color:red;margin-left:5px;">*</span></label> -->
                                            <select name="panchayat_id" id="panchayat_id" class="form-control">
                                                <option value="">--Select--</option>
                                            </select>
                                            <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                                        </div>
                                    </td>
                                    <td></td>                           
                                    <!-- <td colspan="4">
                                        <div style="text-align: right;">
                                            <button type="button" class="btn btn-secondary btn-sm btn-circle" onclick="appendcoordinates()">Add&nbsp;&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                        </div>
                                    </td> -->
                                </tr>
                                
                            </tbody>
                        </table>
                        <div id="cordinates_details">
                            <!-- append images -->
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" name="scheme_performance_id" id="scheme_performance_id_for_coordinates"> <!--  scheme_performance_id -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                    <button type="button" id="coordinate_save" onclick="submitcoordinateAjax();" class="btn btn-info waves-effect waves-light">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        $("#block_id").change(function () {
            // block_id_validate();
            get_panchayat_datas();
        });
    });


    function get_panchayat_datas() {
        var block_id_tmp = $("#block_id").val();
        $("#panchayat_id").html('<option value="">--Select--</option>');
        if (block_id_tmp) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('block/panchyat_data')}}",
                data: { 'block_id': block_id_tmp },
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function (data) {
                    $(".custom-loader").fadeIn(300);
                },
                error: function (xhr) {
                    alert("error" + xhr.status + "," + xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function (data) {
                    $("#panchayat_id").html('<option value="">--Select--</option>');
                    for (var i = 0; i < data.length; i++) {
                        $("#panchayat_id").append('<option value="' + data[i].geo_id + '">' + data[i].geo_name + '</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
        else{
            $("#panchayat_id").html('<option value="">--Select--</option>');
        }
    }
</script>


@endsection