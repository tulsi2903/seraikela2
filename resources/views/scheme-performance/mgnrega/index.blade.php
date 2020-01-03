@extends('layout.layout') @section('title', 'MGNREGA Performance') @section('page-style')
<style>
    .div-title {
        color: black;
    }
    
    #add-performance-datas-block {
        margin-top: 15px;
    }
    
    .to-append-block{

    }
    .to-append-row{
        border: 1px solid #c9c9c9;
        border-radius: 5px;
        overflow: hidden;
        padding: 15px;
        margin-bottom: 15px;
        background: white;
        position: relative;
    }
    #append-new-form{
        text-align: right;
    }
    .delete-to-append{
        padding: 5px 10px;
        border-bottom-left-radius: 5px;
        background: #f0f0f0;
        border: 1px solid #e4e4e4;
        position: absolute;
        right: -1px;
        top: -1px;
        background: #f25961;
        color: white;
        cursor: pointer;
    }
</style>
@endsection @section('page-content')
<div class="card">
    <div class="col-md-12">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">MGNREGA</h4>
                <!-- back button -->
            </div>
        </div>
    </div>

    <div class="card-body">
        <form action="{{url('scheme-performance/mgnrega/store')}}" method="POST" enctype="multipart/form-data" onsubmit="return false" id="scheme-performance">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="mgnrega_category_id">Category<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="mgnrega_category_id" id="mgnrega_category_id" class="form-control">
                            <option value="">--Select--</option>
                            @foreach($mgnrega_category_datas as $mgnrega_category)
                            <option value="{{$mgnrega_category->mgnrega_category_id}}">{{$mgnrega_category->mgnrega_category_name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="mgnrega_category_id_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="block_id">Block<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="block_id" id="block_id" class="form-control">
                            <option value="">--Select--</option>
                            @foreach($block_datas as $block_data)
                            <option value="{{$block_data->geo_id}}">{{$block_data->geo_name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="block_id_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="panchayat_id">Panchayat<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="panchayat_id" id="panchayat_id" class="form-control">
                            <option value="">--Select--</option>
                        </select>
                        <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="year_id">Year<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="year_id" id="year_id" class="form-control">
                            <option value="">--Select--</option>
                            @foreach($year_datas as $year_data)
                            <option value="{{$year_data->year_id}}">{{$year_data->year_value}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="year_id_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <span style="display: block; height: 30px;"></span>
                        <button type="button" class="btn btn-primary" onclick="next()"><i class="fas fa-search"></i>&nbsp;&nbsp;Next</button>
                    </div>
                </div>
            </div>

            <div id="add-performance-datas-block">
                <h4 class="div-title">Add Datas</h4>
                <div id="to-append-block">
                    <div class="to-append-row">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sanction_no">Sanction No<span style="color:red;margin-left:5px;">*</span></label>
                                    <input type="text" name="sanction_no" id="sanction_no" class="form-control">
                                    <div class="invalid-feedback" id="sanction_no_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="title">Title<span style="color:red;margin-left:5px;">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control">
                                    <div class="invalid-feedback" id="title_error_msg"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="latitude">Latitude<span style="color:red;margin-left:5px;">*</span></label>
                                    <input type="text" name="latitude" id="latitude" class="form-control">
                                    <div class="invalid-feedback" id="latitude_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="longitude">Longitude<span style="color:red;margin-left:5px;">*</span></label>
                                    <input type="text" name="longitude" id="longitude" class="form-control">
                                    <div class="invalid-feedback" id="longitude_error_msg"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status<span style="color:red;margin-left:5px;">*</span></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="1">Approoved not in progress</option>
                                        <option value="2">Ongoing</option>
                                        <option value="3">Completed</option>
                                    </select>
                                    <div class="invalid-feedback" id="status_error_msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="append-new-form">
                    <button class="btn btn-dark btn-sm"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add More</button>
                </div>
            </div>

            <button type="button" onclick="submitForm();" class="btn btn-secondary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>

            <!-- <div id="previous_performance_datas">
                <div class="table-responsive table-hover">
                    <table class="table">
                        <thead style="background: #d6dcff;color: #000;"> 
                            <tr>
                                <th>S.No.</th>
                                <th>Sanction No</th>
                                <th>Scheme Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </theadbackground: #d6dcff;color: #000;>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>JHMGNREGA/01/2019</td>
                                <td>Playground Near School</td>
                                <td>
                                    <i class="fas fa-check-circle text-success"></i>&nbsp;&nbsp;Completed
                                    <i class="fas fa-dot-circle text-dark"></i>&nbsp;&nbsp;Ongoing
                                </td>
                                <td>
                                    <a href="" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;<a href="" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                    &nbsp;&nbsp;<a href="" class="btn btn-danger btn-sm delete-button"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->

        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#block_id").change(function() {
            get_panchayat_datas();
            $("#block_id").removeClass('is-invalid');
        });
        // $("#org_id").change(function () {
        //     // org_id_validate();
        //     $("#org_id").removeClass('is-invalid');
        // });
    });

    function get_panchayat_datas(type, bl_id) {
        $("#panchayat_id").html("<option value=''>--Select--</option>");

        if ($("#block_id").val()) {
            $.ajax({
                url: "{{url('scheme-performance/mgnrega/get-panchayat-datas')}}",
                data: {
                    'block_id': $("#block_id").val()
                },
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function(data) {
                    $(".custom-loader").fadeIn(300);
                },
                error: function(xhr) {
                    $(".custom-loader").fadeOut(300);
                    alert("error" + xhr.status + "," + xhr.statusText);
                },
                success: function(data) {
                    // console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        $("#panchayat_id").append("<option value='" + data[i].geo_id + "'>" + data[i].geo_name + "</option>");
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }

    function next() {

    }
</script>

<script>
    // append content
    var to_append = $(".to-append-row").first().html();
    $(document).ready(function(){
        $("#append-new-form .btn").click(function(){
            $("#to-append-block").append(`<div class="to-append-row">`+to_append+`<span class="delete-to-append"><i class="fas fa-times"></i>&nbsp;&nbsp;Remove</span></div>`);
        });

        // to delete append
        $("body").delegate(".delete-to-append", "click", function(){
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
                        text : 'Yes, remove it!',
                        className : 'btn btn-success'
                    }
                }
            }).then((willDelete) => {
                if (willDelete) {
                    $(this).parent(".to-append-row").remove();
                }
            });
        });
    });
</script>

<script>
    function submitForm(){
        if(true){
            var formElement = $('#scheme-performance')[0]; 
            var form_data = new FormData(formElement);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('scheme-performance/mgnrega/store')}}",
                data: form_data,
                method: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function(data){
                    $(".custom-loader").fadeIn(300);
                },
                error: function(xhr){
                    alert("error"+xhr.status+", "+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function (data){
                    console.log(data);
                    // if(data.response=="success"){
                    //     reset_target_block();
                    //     swal("Success!", "Scheme target datas has been saved", {
                    //         icon : "success",
                    //         buttons: {
                    //             confirm: {
                    //                 className : 'btn btn-success'
                    //             }
                    //         },
                    //     });
                    //     setTimeout(function() {
                    //             document.location.reload()
                    //     }, 3000);
                    // }
                    // else{
                    //     // error occured
                    // }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
        else{
            // error occured
        }
    }
</script>

@endsection