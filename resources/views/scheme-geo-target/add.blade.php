@extends('layout.layout')

@section('title', 'Scheme Geo Target')

@section('page-style')
<style>
    td{
        padding:10px 0 !important;
    }
</style>
@endsection

@section('page-content')
<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Scheme Geo Target</h4>
                <div class="card-tools">
                    <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card-body">
            <form action="{{url('scheme-geo-target/store')}}" method="POST" id="scheme-pmay-target-form" onsubmit="return false;">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="scheme_id">Scheme<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control">
                                <option value="">---Select---</option>
                                @foreach($scheme_datas as $scheme )
                                <option value="{{ $scheme->scheme_id }}">({{$scheme->scheme_short_name}}) {{ $scheme->scheme_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="scheme_id_error_msg"></div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="year_id">Year<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="year_id" id="year_id" class="form-control">
                                <option value="">---Select---</option>
                                @foreach($year_datas as $year_data )
                                <option value="{{ $year_data->year_id }}" <?php if($data->year_id == $year_data->year_id) echo"selected"; ?>>{{ $year_data->year_value }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="year_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="block_id">Block<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="block_id" id="block_id" class="form-control">
                                <option value="">---Select---</option>
                                @foreach( $block_datas as $block_data )
                                <option value="{{ $block_data->geo_id }}" <?php if($data->block_id == $block_data->geo_id ) echo"selected" ?>>{{ $block_data->geo_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="block_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="panchayat_id">Panchayat</label>
                            <select name="panchayat_id" id="panchayat_id" class="form-control">
                                <option value="">All Panchayats</option>
                            </select>
                            <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                        </div>
                    </div>
                    <!-- <div class="col-md-2">
                        <div class="form-group">
                            <div style="height:30px;"></div>
                            <button type="button" class="btn btn-secondary go-button" onclick="return next();">Search&nbsp;&nbsp;<i class="fas fa-search"></i></button>
                        </div>
                    </div> -->
                </div>
                <!--end of row-->
                <hr/>
                <br/>
                <div class="row">
                    <div class="col-md-10">
                        <div id="target-div-block">
                            <button type="button" class="btn" style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-location-arrow"></i>&nbsp;&nbsp;Targets</button>
                            <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
                                <div id="target-no-data" style="font-size:16px;padding-top:25px;">
                                    No target data to show, Please select scheme, year & block!
                                </div>
                                <table id="target-table" class="table order-list" style="margin-top: 10px; display: none;">
                                    <thead style="background: #cedcff">
                                        <tr>
                                            <th width="50px">Sl.No</th>
                                            <th>Panchayat</th>
                                            <th width="150px">Target</th>
                                            <th width="200px">Change Target</th>
                                            <th width="150px"></th>
                                            <th width="140px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="append-target">
                                        <!-- append target panchayat wise (depends on block/ panchayat selection) -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <!--end of card body-->
    </div>
</div>

<script>
    // defining error = true as default
    var scheme_id_error = true;
    var year_id_error = true;
    var block_id_error = true;
    var panchayat_id_error = true;

    
    $(document).ready(function(){
        $("#scheme_id").change(function(){
            scheme_id_validate();
        })
        $("#year_id").change(function(){
            year_id_validate();
        });
        $("#block_id").change(function(){
            block_id_validate();
            get_panchayat_datas();
        });
        $("#panchayat_id").change(function(){
            panchayat_id_validate();
        });

        $("#scheme_id, #year_id, #block_id, #panchayat_id").change(function(){
            next();
        });
    });

    function scheme_id_validate(){
        var scheme_id_val = $("#scheme_id").val();
        if(scheme_id_val==""){
            scheme_id_error = true;
            $("#scheme_id").addClass('is-invalid');
            $("#scheme_id_error_msg").html("Please select a scheme");
        }
        else{
            scheme_id_error = false;
            $("#scheme_id").removeClass('is-invalid');
        }
    }

    function year_id_validate(){
        var year_id_val = $("#year_id").val();
        if(year_id_val==""){
            year_id_error = true;
            $("#year_id").addClass('is-invalid');
            $("#year_id_error_msg").html("Please select a year");
        }
        else{
            year_id_error = false;
            $("#year_id").removeClass('is-invalid');
        }
    }

    function block_id_validate(){
        var block_id_val = $("#block_id").val();
        if(block_id_val==""){
            block_id_error = true;
            $("#block_id").addClass('is-invalid');
            $("#block_id_error_msg").html("Please select block");
        }
        else{
            block_id_error = false;
            $("#block_id").removeClass('is-invalid');
        }
    }

    function panchayat_id_validate(){
        panchayat_id_error = false;
        $("#panchayat_id").removeClass('is-invalid');
    }

    function get_panchayat_datas(){
        var block_id_tmp = $("#block_id").val();
        $("#panchayat_id").html('<option value="">All Panchayats</option>');
        if(block_id_tmp)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-geo-target/get-panchayat-datas')}}",
                data: {'block_id':block_id_tmp},
                method:"GET",
                contentType:'application/json',
                dataType:"json",
                beforeSend: function(data){
                    $(".custom-loader").fadeIn(300);
                },
                error:function(xhr){
                    alert("error"+xhr.status+","+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success:function(data){
                    $("#panchayat_id").html('<option value="">All Panchayats</option>');
                    for(var i=0;i<data.length;i++){
                        $("#panchayat_id").append('<option value="'+data[i].geo_id+'">'+data[i].geo_name+'</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }

    function next(){
        if($("#scheme_id").val()&&$("#year_id").val()&&$("#block_id").val()){
            get_target_details();
        }
        else{
            resetAppendTargetBlock(); // if mendatory fields are not selcted then no data to show
        }
    }

    // send data [scheme, year, block, panchayat(if selected)] and get target of each panchayat/ only panchayat (depend on block selected on panchayat selected)
    // the show/ append in table
    function get_target_details(){
        // validate first
        scheme_id_validate();
        year_id_validate();
        block_id_validate();
        panchayat_id_validate();

        if(scheme_id_error || year_id_error || block_id_error || panchayat_id_error){
            // error occured
        }
        else{ // no error
            // gathering data before send to backend
            scheme_id_tmp = $("#scheme_id").val();
            year_id_tmp = $("#year_id").val();
            block_id_tmp = $("#block_id").val();
            panchayat_id_tmp = $("#panchayat_id").val();


            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-geo-target/get-target-details')}}",
                data: {"scheme_id":scheme_id_tmp, "year_id":year_id_tmp, "block_id":block_id_tmp, "panchayat_id":panchayat_id_tmp},
                method:"GET",
                contentType:'application/json',
                dataType:"json",
                beforeSend: function(data){
                    resetAppendTargetBlock(); // resetting append-table/block before getting target datas
                    $(".custom-loader").fadeIn(300);
                },
                error:function(xhr){
                    alert("error"+xhr.status+","+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success:function(data){
                    console.log(data);
                    if(data.response=="success"){
                        data.target_datas.forEach(function(target_data, index){
                            // appedning data in target form for user input (target)
                            $("#append-target").append(`
                                <tr data-panchayat-id='`+target_data.geo_id+`' data-target='`+(target_data.target || 0)+`'>
                                    <td>`+(index+1)+`</td>
                                    <td>`+target_data.geo_name+` `+target_data.geo_id+`</td>
                                    <td>`+(target_data.target || 'No Target Set')+`</td>
                                    <td><input type="text" class="form-control change-target-input" value="`+(target_data.target || 0)+`"></td>
                                    <td><button type="button" class="btn btn-secondary btn-sm data-entry-save-button" onclick="saveTarget(`+target_data.scheme_geo_target_id+`, this)" disabled><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button></td>
                                    <td><a href="javascript:void();" class="data-entry-link">Data Entry <i class="fas fa-arrow-right"></i></a></td>
                                </tr>
                            `);
                        })

                        // caluculating total target
                        $("#append-target").append(`
                                <tr id="append-target-last-row" style="background: #b7b7b7; font-weight: bold;">
                                    <td></td>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            `);
                        calcTotalTarget(); // caluculating total target

                        $("#target-table").fadeIn(300);
                        $("#target-no-data").fadeOut(0); // show target table block for user input
                    }
                    else{ // data.response=="no_data"

                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }

    $(document).ready(function(){
        // get new target value and check if previous target value is different or not, if changed then enable save button or disable save button
        $("#append-target").delegate(".change-target-input","keyup", function(){
            calcTotalTarget(); // to calculate total
            var tr = $(this).closest("tr");

            $(this).val($(this).val().replace(/\D+/g, "")); // removing other than number

            // for save button
            if($(tr).data("target")==$(this).val()){
                $(tr).find(".data-entry-save-button").attr("disabled",true);
            }
            else{
                $(tr).find(".data-entry-save-button").attr("disabled",false);
            }
        });
        $("#append-target").delegate(".change-target-input","change", function(){
            var tr = $(this).closest("tr");
            if(!$(this).val()){
                $(this).val($(tr).data("target"));
            }
        });
    });

    // calculate total target for entire block OR selected panchayat
    function calcTotalTarget(){
        var target_fields = $("#append-target .change-target-input");
        var total_pre_target = 0;
        var total_target = 0;

        // for pre target
        for(var i=0; i<target_fields.length; i++){
            total_pre_target+=Number($(target_fields[i]).closest('tr').data("target"));
        }

        // for current target
        for(var i=0; i<target_fields.length; i++){
            total_target+=Number($(target_fields[i]).val());
        }

        $("#append-target #append-target-last-row").html(`
                                    <td></td>
                                    <td>Total</td>
                                    <td>`+total_pre_target+`</td>
                                    <td>`+total_target+`</td>
                                    <td></td>
                                    <td></td>
                            `);
    }

    // to save individual target
    function saveTarget(id, e){
        /**
        id = scheme_geo_target_id/null, e=this 
        first get panchayat_id from tr(data), target from input field, scheme_id, year_id etc and proceed to save
        **/
        var tr = $(e).closest("tr");
        scheme_id = $("#scheme_id").val();
        year_id = $("#year_id").val();
        block_id = $("#block_id").val();
        panchayat_id = $(tr).data("panchayat-id");
        target = $(tr).find(".change-target-input").val();
        
        // assigning purpose
        purpose = 'add';
        if(id){ purpose="edit"; }

        // confirm box, if they want to save or not
        swal({
            title: 'Save target data?',
            // text: "You won't be able to revert this!",
            icon: 'info',
            buttons:{
                cancel: {
                    visible: true,
                    text : 'No, cancel!',
                    className: 'btn btn-danger'
                },
                confirm: {
                    text : 'Yes, Save!',
                    className : 'btn btn-success'
                }
            }
        }).then((willDelete) => {
            if (willDelete) {
                var formData = new FormData();
                formData.append('scheme_id', scheme_id);
                formData.append('year_id', year_id);
                formData.append('block_id', block_id);
                formData.append('panchayat_id', panchayat_id);
                formData.append('scheme_geo_target_id', id);
                formData.append('target', target);
                formData.append('purpose', purpose);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('scheme-geo-target/save-target')}}",
                    data: formData,
                    method: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function (data) {
                        $(".custom-loader").fadeIn(300);
                    },
                    error: function (xhr) {
                        alert("error" + xhr.status + ", " + xhr.statusText);
                        $(".custom-loader").fadeOut(300);
                    },
                    success: function (data) {
                        console.log(data);
                        if(data.response=="success"){

                            get_target_details(); // to refresh targets again

                            swal("Success!", "New target has been saved.", {
                                icon : "success",
                                buttons: {
                                    confirm: {
                                        className : 'btn btn-success'
                                    }
                                },
                            }).then((ok) => {
                                if (ok) {
                                    // to do something after Okay clicked
                                }
                            });
                        }
                        else{
                            // error occured
                            swal("Error Occured!", data.response, {
                                icon : "error",
                                buttons: {
                                    confirm: {
                                        className : 'btn btn-danger'
                                    }
                                },
                            });
                        }
                        $(".custom-loader").fadeOut(300);
                    }
                });
            }
            else{
                // reject to save
            }
        });
    }

    // reset target block to no data, if user changes any mandatory fields
    function resetAppendTargetBlock(){
        $("#append-target").html("");
        $("#target-table").fadeOut(0);
        $("#target-no-data").fadeIn(300);
    }
</script>

@endsection