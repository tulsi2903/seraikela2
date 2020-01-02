@extends('layout.layout')

@section('title', 'Scheme Geo Target')

@section('page_style')
    <style>

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
                <form action="{{url('scheme-geo-target/store')}}" method="POST" id="scheme-target-form">
                @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="year_id">Year<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="year_id" id="year_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($year_datas as $year_data )
                                        <option value="{{ $year_data->year_id }}">{{ $year_data->year_value }}</option>
                                    @endforeach 
                                </select>
                                <div class="invalid-feedback" id="year_id_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="scheme_id">Scheme Name<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="scheme_id" id="scheme_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $scheme_datas as $scheme_data )
                                        <option value="{{ $scheme_data->scheme_id }}">({{$scheme_data->scheme_short_name}}) {{ $scheme_data->scheme_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="scheme_id_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="block_id">Block<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="block_id" id="block_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $block_datas as $block_data )
                                        <option value="{{ $block_data->geo_id }}">{{ $block_data->geo_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="block_id_error_msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="panchayat_id">Panchayat<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="panchayat_id" id="panchayat_id" class="form-control">
                                    <option value="">---Select---</option>
                                </select>
                                    <div class="invalid-feedback" id="panchayat_id_error_msg"></div>
                            </div>
                        </div>
                    </div><!--end of row-->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="chk_independent">Independent</label>&nbsp;&nbsp;
                                        <input type="checkbox" name="chk_independent" id="chk_independent" value="1" checked>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="group_id">Scheme Group<span style="color:red;margin-left:5px;">*</span></label>
                                        <select name="group_id" id="group_id" class="form-control" disabled="true">
                                            <option value="">---Select---</option>
                                            @foreach($group_datas as $group_data )
                                                <option value="{{ $group_data->scheme_group_id }}">{{ $group_data->scheme_group_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="group_id_error_msg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <div style="display: inline-block; width:250px;">
                                    <div class="form-group">
                                        <label for="scheme_sanction_id">Scheme Sanction ID</label>
                                        <select name="scheme_sanction_id" id="scheme_sanction_id" class="form-control">
                                            <option value="">--Select--</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="display: inline-block;">
                                    <div class="form-group">
                                        <label>OR<span style="color:red;margin-left:5px;">*</span></label>
                                    </div>
                                </div>
                                <div style="display: inline-block; width:250px;">
                                    <div class="form-group">
                                        <label for="new_scheme_sanction_id">New Scheme Sanction ID</label>
                                        <input name="new_scheme_sanction_id" id="new_scheme_sanction_id" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="scheme_sanction_id_error_msg" style="padding-left: 155px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="search()" id="go-button"><i class="fas fa-serach"></i>&nbsp;GO</button>
                            </div>
                        </div>
                    </div>

                    <hr/>
                
                    <div id="target-block" style="display:none;">
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                            <!-- append indicator names as pills -->
                        </ul>
                        <div class="tab-content mt-2 mb-3" id="myTabContent" style="border: 1px solid #d6d6d6; padding: 10px; border-radius: 5px;">
                            <!-- append indicator contents -->
                        </div>
                        
                        <div id="target-block-error-msg" style="padding: 10px 0; color: red;">
                        </div>
                        <div class="form-group">
                            <input type="text" name="to_delete_scheme_performance_id" id="to_delete_scheme_performance_id" value="">
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                        </div>
                    </div>
                </form>
            </div><!--end of card body-->
        </div>
 </div>


<script>
    var independent = "1";
    var new_scheme_sanction_id_entered= "0"; // no previous data on database found, so new <input> ID has been inserted ["0"=>selected, "1"=>new input]

    var year_id_error = true;
    var scheme_id_error = true;
    var block_id_error = true;
    var panchayat_id_error = true;
    var group_id_error = true;
    var scheme_sanction_id_error = true;

    $(document).ready(function(){
    
        $("#year_id").change(function(){
            year_id_validate();
        });
        $("#scheme_id").change(function(){
            scheme_id_validate();
        });
        $("#block_id").change(function(){
            block_id_validate();
            get_panchayat_datas(); // ajax to get panchayat data for selected blocks
        });
        $("#panchayat_id").change(function(){
            panchayat_id_validate();
        });
        $("#chk_independent").change(function(){
            independent_check();
        });
        $("#group_id").change(function(){
            group_id_validate();
        });
        $("#scheme_sanction_id").change(function(){
            scheme_sanction_id_validate();
        });
        $("#new_scheme_sanction_id").change(function(){
            new_scheme_sanction_id_validate();
            scheme_sanction_id_validate();
        });

        // getting scheme_sanction_id
        $("#year_id, #scheme_id, #panchayat_id, #chk_independent, #group_id").change(function(){
            get_scheme_sanction_id(); // to get scheme sanction id
        });

        // resetting target-block i.e target block views when anything changes from step 1, so user have to click GO/Search
        $("#year_id, #scheme_id, #block_id, #panchayat_id, #chk_independent, #group_id, #scheme_sanction_id, #new_scheme_sanction_id").change(function(){
            reset_target_block(); // to get scheme sanction id
        });

    });

    //year_validation
    function year_id_validate(){
        var year_id_val = $("#year_id").val();
        if(year_id_val == ""){
            year_id_error = true;
            $("#year_id").addClass('is-invalid');
            $("#year_id_error_msg").html("Year should not be blank");
        }
        else{
            year_id_error = false;
            $("#year_id").removeClass('is-invalid');
        }
    }

    //scheme name validation
    function scheme_id_validate(){
        var scheme_id_val = $("#scheme_id").val();
        if(scheme_id_val == ""){
            scheme_id_error = true;
            $("#scheme_id").addClass('is-invalid');
            $("#scheme_id_error_msg").html("Scheme Name should not be blank");
        }
        else{
            scheme_id_error = false;
            $("#scheme_id").removeClass('is-invalid');
        }
    }

    //block name validation
    function block_id_validate(){
        var block_id_val = $("#block_id").val();
        if(block_id_val=="")
        {
            block_id_error=true;
            $("#block_id").addClass('is-invalid');
            $("#block_id_error_msg").html("Block Name should not be blank");
        }
        else{
            block_id_error=false;
            $("#block_id").removeClass('is-invalid');
        }
    }

    //panchayat validation
    function panchayat_id_validate(){
        var panchayat_id_val = $("#panchayat_id").val();
        if(panchayat_id_val == ""){
            panchayat_id_error = true;
            $("#panchayat_id").addClass('is-invalid');
            $("#panchayat_id_error_msg").html("Panchayat should not be blank");
        }
        else{
            panchayat_id_error = false;
            $("#panchayat_id").removeClass('is-invalid');
        }
    }

    // group error if independent scheme
    function group_id_validate(){
        if(independent=="0")
        {
            var group_id_val = $("#group_id").val();
            if(group_id_val == ""){
                group_id_error = true;
                $("#group_id").addClass('is-invalid');
                $("#group_id_error_msg").html("Scheme Group should not be blank");
            }
            else{
                group_id_error = false;
                $("#group_id").removeClass('is-invalid');
            }
        }
        else{
            group_id_error = false;
            $("#group_id").removeClass('is-invalid');
        }
    }

    //panchayat validation
    function scheme_sanction_id_validate(){
        var scheme_sanction_id_val = $("#scheme_sanction_id").val();
        var new_scheme_sanction_id_val = $("#new_scheme_sanction_id").val();
        if(scheme_sanction_id_val == "" && new_scheme_sanction_id_val==""){
            scheme_sanction_id_error = true;
            $("#scheme_sanction_id").addClass('is-invalid');
            $("#new_scheme_sanction_id").addClass('is-invalid');
            $("#scheme_sanction_id_error_msg").show().html("Scheme Sanction ID should not be blank");
        }
        else{
            scheme_sanction_id_error = false;
            $("#scheme_sanction_id").removeClass('is-invalid');
            $("#new_scheme_sanction_id").removeClass('is-invalid');
            $("#scheme_sanction_id_error_msg").hide();
        }
    }

    function new_scheme_sanction_id_validate(){
        var new_scheme_sanction_id_val = $("#new_scheme_sanction_id").val();
        if(new_scheme_sanction_id_val){
            new_scheme_sanction_id_entered = "1";
            $("#scheme_sanction_id").val("");
            $("#scheme_sanction_id").prop("disabled", true);
        }
        else{
            new_scheme_sanction_id_entered = "0";
            $("#scheme_sanction_id").val("");
            $("#scheme_sanction_id").prop("disabled", false);
        }
    }

    // resetting all validation as before
    function reset_validation()
    {
        year_id_error = true;
        $("#year_id").removeClass('is-invalid');

        scheme_id_error = true;
        $("#scheme_id").removeClass('is-invalid');
        
        block_id_error = true;
        $("#block_id").removeClass('is-invalid');
        
        panchayat_id_error = true;
        $("#panchayat_id").removeClass('is-invalid');

        group_id_error = true;
        $("#group_id").removeClass('is-invalid');
        
        scheme_sanction_id_error = true;
        $("#scheme_sanction_id").removeClass('is-invalid');
    }

    // block_id:change => getting panchayat datas to be append on panchayat select(dropdown)
    function get_panchayat_datas(){
        block_id_validate();
        if(block_id_error==false){
            var block_id_tmp = $("#block_id").val();
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
                    console.log(data);
                    $("#panchayat_id").html('<option value="">-Select-</option>');
                    for(var i=0;i<data.length;i++){
                        $("#panchayat_id").append('<option value="'+data[i].geo_id+'">'+data[i].geo_name+'</option>');
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
        else{
            $("#panchayat_id").html('<option value="">-Select-</option>');
        }
    }

    // check if selected independent checkbox is selected or not (enable group_id select)
    function independent_check(){
        if($("#chk_independent").prop("checked")==true){
            independent = "1";
            $("#group_id").val("");
            $("#group_id").removeClass("is-invalid");
            $("#group_id").prop("disabled", true);
        }
        else{
            independent = "0";
            $("#group_id").val("");
            $("#group_id").removeClass("is-invalid");
            $("#group_id").prop("disabled", false);
        }
    }

    // get scheme_sanction_id according to year, scheme, panchayat, group (if independent)
    function get_scheme_sanction_id(){
        // gathering information to send
        var year_id_tmp = $("#year_id").val();
        var scheme_id_tmp = $("#scheme_id").val();
        var block_id_tmp = $("#block_id").val(); // no need to send
        var panchayat_id_tmp = $("#panchayat_id").val();
        var group_id_tmp = $("#group_id").val();
        var independent_tmp = independent;

        var have_all_data = false;
        if(independent_tmp=="1"){
            if(year_id_tmp!=""&&scheme_id_tmp!=""&&panchayat_id_tmp!=""){
                have_all_data = true;
            }
            else{
                have_all_data = false;
            }
        }
        else{ // independemt_tmp == "0"
            if(year_id_tmp!=""&&scheme_id_tmp!=""&&panchayat_id_tmp!=""&&group_id_tmp){
                have_all_data = true;
            }
            else{
                have_all_data = false;
            }
        }

        if(have_all_data)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('scheme-geo-target/get-scheme-sanction-id')}}",
                data: {'year_id': year_id_tmp, 'scheme_id': scheme_id_tmp, 'panchayat_id': panchayat_id_tmp, 'group_id': group_id_tmp, 'independent': independent},
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                beforeSend: function(data){
                    $(".custom-loader").fadeIn(300);
                },
                error: function(xhr){
                    alert("error"+xhr.status+", "+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function (data){
                    console.log(data);
                    $("#scheme_sanction_id").html('<option value="">--Select--</option>');
                    if(data.response=="success"){
                        for(i=0;i<data.scheme_sanction_id.length;i++)
                        {
                            $("#scheme_sanction_id").append('<option value="'+data.scheme_sanction_id[i].scheme_sanction_id+'">'+data.scheme_sanction_id[i].scheme_sanction_id+'</option>');
                        }
                    }
                    else{ // no_data

                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
        else{
            $("#scheme_sanction_id").html('<option value="">--Select--</option>');
        }
    }
    

    // search to get indicator datas, target datas (according to indicator, latitude, longitude), sanction id (according to scheme+/group and target wise)
    function search(){
        year_id_validate();
        scheme_id_validate();
        block_id_validate();
        panchayat_id_validate();
        group_id_validate();
        scheme_sanction_id_validate();

        if(!year_id_error&&!scheme_id_error&&!block_id_error&&!panchayat_id_error&&!group_id_error&&!scheme_sanction_id_error){ // no error
            // gathering information to send
            var year_id_tmp = $("#year_id").val();
            var scheme_id_tmp = $("#scheme_id").val();
            var block_id_tmp = $("#block_id").val(); // no need to send
            var panchayat_id_tmp = $("#panchayat_id").val();
            var group_id_tmp = $("#group_id").val();
            var scheme_sanction_id_tmp = $("#scheme_sanction_id").val();
            var new_scheme_sanction_id_tmp = $("#scheme_sanction_id").val();
            var independent_tmp = independent;

            // alert(year_id_tmp +" "+ scheme_id_tmp +" "+ block_id_tmp +" "+ panchayat_id_tmp  +" "+  group_id_tmp +" "+ independent);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('scheme-geo-target/get-all-datas')}}",
                    data: {'year_id': year_id_tmp, 'scheme_id': scheme_id_tmp, 'panchayat_id': panchayat_id_tmp, 'group_id': group_id_tmp, 'independent': independent, "scheme_sanction_id": scheme_sanction_id_tmp, 'new_scheme_sanction_id': new_scheme_sanction_id_tmp, 'new_scheme_sanction_id_entered': new_scheme_sanction_id_entered},
                    method: "GET",
                    contentType: 'application/json',
                    dataType: "json",
                    beforeSend: function(data){
                        $(".custom-loader").fadeIn(300);
                        reset_target_block(); // to reset target area
                    },
                    error: function(xhr){
                        alert("error"+xhr.status+", "+xhr.statusText);
                        $(".custom-loader").fadeOut(300);
                    },
                    success: function (data){
                        console.log(data);

                        if(data.response=="success")
                        {
                            for(i=0; i<data.data.length;i++){
                                // nav buttons/ tab buttons
                                nav_append = `<li class="nav-item">
                                                <a class="nav-link`;
                                if(i==0){ nav_append+=` active`; }
                                nav_append+=`" id="indicator-`+data.data[i].indicator_id+`-tab" data-toggle="pill" href="#indicator-`+data.data[i].indicator_id+`-view-tab" role="tab" aria-selected="true">`+data.data[i].indicator_name+`</a>
                                            </li>`;
                                $("#target-block .nav").append(nav_append);


                                // nav contents/ tab contents
                                tab_content_append = `<div class="tab-pane fade`;
                                if(i==0){
                                    tab_content_append+=` show active`;
                                }
                                tab_content_append+=`" id="indicator-`+data.data[i].indicator_id+`-view-tab" role="tabpanel" data-indicator-id='`+data.data[i].indicator_id+`' data-geo-related='`+data.data[i].geo_related+`' data-pre-target='`+data.data[i].target+`'>`;
                                tab_content_append+= `<input type="text" name="indicator_id[]" value="`+data.data[i].indicator_id+`" hidden>`; // which indicator going=> hidden
                                tab_content_append +=   `<div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <input type="checkbox" value="`+data.data[i].indicator_id+`" name="geo_related[]"`;
                                    if(data.data[i].geo_related=="1"){ tab_content_append+=` checked`; }
                                                                    
                                tab_content_append+=`>&nbsp;&nbsp;<label>Geo Related</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Target</label>
                                                                    <input type="text" id="target-indicator-id-`+data.data[i].indicator_id+`" name="target[]" value="`+data.data[i].target+`" class="form-control" style="border-color: #adadad !important; color: black;" readonly>
                                                                </div>
                                                            </div>
                                                        </div>`;
                                tab_content_append +=  `<table class='table table-bordered table-sm'>
                                                            <thead style="background: #cedcff">
                                                                    <tr>
                                                                        <th>Sanction ID<span style="color:red;margin-left:5px;">*</span></th>
                                                                        <th>Latitude</th>
                                                                        <th>Longitude</th>                                        
                                                                        <th>Comments</th>                                       
                                                                        <th>Action</th>                                        
                                                                    </tr>
                                                            </thead>
                                                            <tbody id="add-new-row-tbody-id-`+data.data[i].indicator_id+`">`;
                            
                                // indicator_datas i.e. geo_performance loop starts
                                for(j=0;j<data.data[i].indicator_datas.length;j++){
                                    tab_content_append +=   `<tr>
                                                                <td>
                                                                    <input type="text" name="scheme_performance_id[]" value="`+data.data[i].indicator_datas[j].scheme_performance_id+`" hidden>
                                                                    <input type="text" class="form-control" value="`+data.data[i].indicator_datas[j].indicator_sanction_id+`" name="indicator_sanction_id[]" placeholder="Enter sanction id">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" value="`+(data.data[i].indicator_datas[j].latitude || "")+`" name="latitude[]" placeholder="Enter Latitude"`;
                                                                    if(data.data[i].geo_related=="0"){ tab_content_append+=` readonly`; }  
                                     tab_content_append +=      `>
                                                                </td>                             
                                                                <td>
                                                                    <input type="text" class="form-control" value="`+(data.data[i].indicator_datas[j].longitude || "")+`" name="longitude[]" placeholder="Enter Longitude"`;
                                                                    if(data.data[i].geo_related=="0"){ tab_content_append+=` readonly`; } 
                                    tab_content_append +=    `>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" value="`+(data.data[i].indicator_datas[j].comments || "")+`" name="comments[]" placeholder="comments">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-icon btn-sm btn-round btn-danger" onclick="addRemoveNewTargetRow('remove', '`+data.data[i].indicator_id+`', this, '`+data.data[i].indicator_datas[j].scheme_performance_id+`')">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>`;
                                }
                                // indicator_datas i.e. geo_performance loop ends

                                tab_content_append +=   `   </tbody>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="4"></td>
                                                                    <td width="200px">Add Target&nbsp;
                                                                        <button type="button" class="btn btn-iconn btn-sm btn-round btn-primary" onclick="addRemoveNewTargetRow('add', '`+data.data[i].indicator_id+`', this)">
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>   
                                                        </table>
                                                    </div>`;
                                $("#target-block .tab-content").append(tab_content_append);

                                // opposite of reset_target_block, i.e. show target block and hide GO Button
                                $("#target-block").show();
                                $("#go-button").hide();
                            }
                        }
                        else{ // response=="no_data"

                        }

                        $(".custom-loader").fadeOut(300);
                    }
                });
        }
    }

    function reset_target_block(){
        $("#go-button").show();
        $("#target-block").hide();
        $("#target-block .nav").html("");
        $("#target-block .tab-content").html("");
        $("#target-block-error-msg").html("");
        $("#to_delete_scheme_performance_id").val("");
    }
</script>

<script>
    /*
        target block validations
    */
    var to_delete_scheme_performance_id = new Array;
    var indicator_sanction_id_error = true; 

    $(document).ready(function(){
        $(document).on("keyup", "input[name='indicator_sanction_id[]']",  function(){
            indicator_sanction_id_validate();
        });
    });

    function indicator_sanction_id_validate(){
        indicator_sanction_id_error = false; // assigning false, so if any error found it will becoame true ie.e error occured
        $("#target-block-error-msg").html("");

        var indicator_sanction_id_val = $("input[name='indicator_sanction_id[]']");
        for(i=0;i<indicator_sanction_id_val.length;i++)
        {
            if($(indicator_sanction_id_val[i]).val()==""){
                indicator_sanction_id_error = true;
                $(indicator_sanction_id_val[i]).addClass("is-invalid");
                $("#target-block-error-msg").html("Error occurred! Indicator sanction ID's should not be blank.");
            }
            else{
                $(indicator_sanction_id_val[i]).removeClass("is-invalid");
            }
        }
    }

    // geo-related 
    $(document).ready(function(){
        $(document).on("click", "input[name='geo_related[]']", function() {
            var id = $(this).closest(".tab-pane").attr("data-indicator-id");
            if($(this).prop("checked")==true){
                $("div[data-indicator-id='"+id+"'] input[name='latitude[]'], div[data-indicator-id='"+id+"'] input[name='longitude[]']").prop("readonly", false);
                $("div[data-indicator-id='"+id+"']").attr("data-geo-related", "1");
            }
            else{
                $("div[data-indicator-id='"+id+"'] input[name='latitude[]'], div[data-indicator-id='"+id+"'] input[name='longitude[]']").prop("readonly", true);
                $("div[data-indicator-id='"+id+"']").attr("data-geo-related", "0");
            }
        });
    });

    // add or remove rows in indicator target block
    function addRemoveNewTargetRow(purpose, id, element, scheme_performance_id_tmp){
        var pre_target=$("#target-indicator-id-"+id).val();
        if(purpose=="add"){
            $("#target-indicator-id-"+id).val(Number(pre_target)+1);

            to_append = `<tr>
                            <td>
                                <input type="text" name="scheme_performance_id[]" value="" hidden>
                                <input type="text" class="form-control" value="" name="indicator_sanction_id[]" placeholder="Enter sanction id">
                            </td>
                            <td>
                                <input type="text" class="form-control" value="" name="latitude[]" placeholder="Enter Latitude"`;
                            if($(element).closest(".tab-pane").attr("data-geo-related")=="0"){
                                to_append+=` readonly`;
                            }
                                
            to_append+=`>
                    </td>                             
                            <td>
                                <input type="text" class="form-control" value="" name="longitude[]" placeholder="Enter Longitude"`;
                            
                            if($(element).closest(".tab-pane").attr("data-geo-related")=="0"){
                                to_append+=` readonly`;
                            }
                            
                            
            to_append+=`>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="" name="comments[]" placeholder="comments">
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm btn-round btn-danger" onclick="addRemoveNewTargetRow('remove','`+id+`',this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>`;
            $("#add-new-row-tbody-id-"+id).append(to_append);
        }
        else if(purpose=="remove"&&pre_target!="0"){
            swal({
                title: 'Are you sure want to delete?',
                // text: "You won't be able to revert this!",
                type: 'warning',
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
                    $("#target-indicator-id-"+id).val(Number(pre_target)-1);
                    $(element).closest("tr").remove();

                    // // assigning to_delete_scheme_performance_id for deleting purposes (in hidden input)
                    // var scheme_performance_id_tmp; // already passed from argument
                    if(scheme_performance_id_tmp)
                    {
                        if(to_delete_scheme_performance_id.includes(scheme_performance_id_tmp)){
                            to_delete_scheme_performance_id.splice(to_delete_scheme_performance_id.indexOf(scheme_performance_id_tmp), 1);
                            $("#to_delete_scheme_performance_id").val(to_delete_scheme_performance_id);
                        }
                        else{
                            to_delete_scheme_performance_id.push(scheme_performance_id_tmp);
                            $("#to_delete_scheme_performance_id").val(to_delete_scheme_performance_id);
                        }
                    }
                } else {
                    // cancel clicked
                }
            });
            // if(confirm("Are you sure want to delete?")){
            //     $("#target-indicator-id-"+id).val(Number(pre_target)-1);
            //     $(element).closest("tr").remove();
            // }
        }
    }
</script>

<script>
    // final form submit
    function submitForm(){
        // call all validation
        indicator_sanction_id_validate();

        if(indicator_sanction_id_error==false){
            var form_data = $('#scheme-target-form').serialize();
            // var formElement = document.querySelector("#scheme-target-form");
            // var form_data = new FormData(formElement);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('scheme-geo-target/store')}}",
                data: form_data,
                method: "GET",
                contentType: 'application/json',
                dataType: "json",
                processData: false,
                beforeSend: function(data){
                    $(".custom-loader").fadeIn(300);
                },
                error: function(xhr){
                    alert("error"+xhr.status+", "+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success: function (data){
                    console.log(data);
                    if(data.response=="success"){
                        reset_target_block();
                        swal("Success!", "Scheme target datas has been saved", {
                            icon : "success",
                            buttons: {
                                confirm: {
                                    className : 'btn btn-success'
                                }
                            },
                        });
                        setTimeout(function() {
                                document.location.reload()
                        }, 3000);
                    }
                    else{
                        // error occured
                    }
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