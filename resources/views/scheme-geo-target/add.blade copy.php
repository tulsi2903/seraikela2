@extends('layout.layout')

@section('title', 'Scheme Geo Target')

@section('page_style')
    <style>

    </style>
@endsection

@section('page-content')
   <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Scheme Geo Target</h4>
                        <div class="card-tools">
                        <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card-body">
                <form action="{{url('scheme-geo-target/store')}}" method="POST">
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
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="scheme_sanction_id">Scheme Sanction ID<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="scheme_sanction_id" id="scheme_sanction_id" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                                <div class="invalid-feedback" id="scheme_sanction_id_error_msg"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="return search()"><i class="fas fa-serach"></i>&nbsp;GO</button>
                            </div>
                        </div>
                    </div>
                </form>

                <hr/>
            
                <div id="target-block" style="">
                    <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                        
                    </ul>
                    <div class="tab-content mt-2 mb-3" id="myTabContent">
                        <!-- <div class="tab-pane fade show active" id="indicator-view-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="checkbox" value="yes" name="geo_related[]">&nbsp;&nbsp;<label>Geo Related</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-1 col-form-label">Target</label>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" id="target-indicator-id-1" name="target[]" value="0" class="form-control" style="border-color: #9c9c9c!important" readonly>
                                    </div>
                                </div>
                            </div>
                            <table class='table table-bordered table-sm'>
                                <thead style="background: #cedcff">
                                        <tr>
                                            <th>Sanction ID<span style="color:red;margin-left:5px;">*</span></th>
                                            <th>Latitude<span style="color:red;margin-left:5px;">*</span></th>
                                            <th>Longitude</th>                                        
                                            <th>Action</th>                                        
                                        </tr>
                                </thead>
                                <tbody id="add-new-row-tbody-id-1">
                                    
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td width="200px">Add Target&nbsp;
                                            <button type="button" class="btn btn-iconn btn-sm btn-round btn-primary" onclick="addRemoveNewTargetRow('add','1')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody> 
                            </table>
                        </div> -->
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                        <button type="reset" class="btn btn-secondary">Reset&nbsp;&nbsp;<i class="fas fa-undo"></i></button>
                    </div>
                </div>
            </div><!--end of card body-->
        </div>
   </div>
<script>
    var independent = "1";

    var year_id_error = true;
    var scheme_id_error = true;
    var block_id_error = true;
    var panchayat_id_error = true;
    var group_id_error = true;
    var scheme_sanction_id_error = true;

    $(document).ready(function(){
    
        $("#year_id").change(function(){
            year_id_validate();
            get_scheme_sanction_id(); // to get scheme sanction id
        });
        $("#scheme_id").change(function(){
            scheme_id_validate();
            get_scheme_sanction_id(); // to get scheme sanction id
        });
        $("#block_id").change(function(){
            block_id_validate();
            get_panchayat_datas(); // ajax to get panchayat data for selected blocks
        });
        $("#panchayat_id").change(function(){
            panchayat_id_validate();
            get_scheme_sanction_id(); // to get scheme sanction id
        });
        $("#chk_independent").change(function(){
            independent_check();
            get_scheme_sanction_id(); // to get scheme sanction id
        });
        $("#group_id").change(function(){
            group_id_validate();
            get_scheme_sanction_id(); // to get scheme sanction id
        });
        $("#scheme_sanction_id").change(function(){
            scheme_sanction_id_validate();
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
        if(scheme_sanction_id_val == ""){
            scheme_sanction_id_error = true;
            $("#scheme_sanction_id").addClass('is-invalid');
            $("#scheme_sanction_id_error_msg").html("Panchayat should not be blank");
        }
        else{
            scheme_sanction_id_error = false;
            $("#scheme_sanction_id").removeClass('is-invalid');
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

        var new_scheme_sanction_id_entered= "0"; // no previous data on database found, so new <input> ID has been inserted ["0"=>selected, "1"=>new input]

        if(!year_id_error&&!scheme_id_error&&!block_id_error&&!panchayat_id_error&&!group_id_error&&!scheme_sanction_id_error){ // no error
            // gathering information to send
            var year_id_tmp = $("#year_id").val();
            var scheme_id_tmp = $("#scheme_id").val();
            var block_id_tmp = $("#block_id").val(); // no need to send
            var panchayat_id_tmp = $("#panchayat_id").val();
            var group_id_tmp = $("#group_id").val();
            var scheme_sanction_id_tmp = $("#scheme_sanction_id").val();
            var independent_tmp = independent;

            // alert(year_id_tmp +" "+ scheme_id_tmp +" "+ block_id_tmp +" "+ panchayat_id_tmp  +" "+  group_id_tmp +" "+ independent);
            if(new_scheme_sanction_id_entered=="0") // selected a scheme sansction id
            {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('scheme-geo-target/get-all-datas')}}",
                    data: {'year_id': year_id_tmp, 'scheme_id': scheme_id_tmp, 'panchayat_id': panchayat_id_tmp, 'group_id': group_id_tmp, 'independent': independent, "scheme_sanction_id": scheme_sanction_id_tmp},
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
                        $("#target-block .nav").html("");
                        $("#target-block .tab-content").html("");

                        for(i=0; i<data.data.length;i++){
                            nav_append = `<li class="nav-item">
                                            <a class="nav-link`;
                            if(i==0){
                                nav_append+=` active`;
                            }
                            nav_append+=`" id="indicator-`+data.data[i].indicator_id+`-tab" data-toggle="pill" href="#indicator-`+data.data[i].indicator_id+`-view-tab" role="tab" aria-selected="true">`+data.data[i].indicator_name+`</a>
                                        </li>`;
                            $("#target-block .nav").append(nav_append);



                            tab_content_append = `<div class="tab-pane fade`;
                            if(i==0){
                                tab_content_append+=` show active`;
                            }
                            tab_content_append+=`" id="indicator-`+data.data[i].indicator_id+`-view-tab" role="tabpanel">`;
                            tab_content_append +=   `<div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <input type="checkbox" value="yes" name="geo_related[]">&nbsp;&nbsp;<label>Geo Related</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-md-1 col-form-label">Target</label>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" id="target-indicator-id-1" name="target[]" value="`+data.data[i].target+`" class="form-control" style="border-color: #9c9c9c!important" readonly>
                                                            </div>
                                                        </div>
                                                    </div>`;
                            tab_content_append +=  `<table class='table table-bordered table-sm'>
                                                        <thead style="background: #cedcff">
                                                                <tr>
                                                                    <th>Sanction ID<span style="color:red;margin-left:5px;">*</span></th>
                                                                    <th>Latitude<span style="color:red;margin-left:5px;">*</span></th>
                                                                    <th>Longitude</th>                                        
                                                                    <th>Action</th>                                        
                                                                </tr>
                                                        </thead>
                                                        <tbody id="add-new-row-tbody-id-1">`;

                            for(j=0;j<data.data[i].indicator_datas;j++){
                                tab_content_append +=   `<tr>
                                                            <td>#</td>
                                                            <td>
                                                                <input type="text" class="form-control" value="" name="indicator_sansction_id[]" placeholder="Enter sanction id">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="" name="latitude[]" placeholder="Enter Latitude">
                                                            </td>                             
                                                            <td>
                                                                <input type="text" class="form-control" value="" name="latitude[]" placeholder="Enter Longitude">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-icon btn-sm btn-round btn-danger" onclick="addRemoveNewTargetRow('remove','1',this)">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>`;
                            }

                            tab_content_append +=   `   </tbody>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <td width="200px">Add Target&nbsp;
                                                                    <button type="button" class="btn btn-iconn btn-sm btn-round btn-primary" onclick="addRemoveNewTargetRow('add','1')">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>   
                                                    </table>
                                                </div>`;
                            $("#target-block .tab-content").append(tab_content_append);
                        }

                        $(".custom-loader").fadeOut(300);
                    }
                });
            }
            else{
                alert("new scheme sanction id inseted");
            }
        }
    }

    function addRemoveNewTargetRow(purpose, id, element){
        var pre_target=$("#target-indicator-id-"+id).val();
        if(purpose=="add"){
            $("#target-indicator-id-"+id).val(Number(pre_target)+1);

            to_append = `<tr>
                            <td>#</td>
                            <td>
                                <input type="text" class="form-control" value="" name="indicator_sansction_id[]" placeholder="Enter sanction id">
                            </td>
                            <td>
                                <input type="text" class="form-control" value="" name="latitude[]" placeholder="Enter Latitude">
                            </td>                             
                            <td>
                                <input type="text" class="form-control" value="" name="latitude[]" placeholder="Enter Longitude">
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm btn-round btn-danger" onclick="addRemoveNewTargetRow('remove','1',this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>`;
            $("#add-new-row-tbody-id-"+id).append(to_append);
        }
        else if(purpose=="remove"&&pre_target!="0"){
            if(confirm("Are you sure want to delete?")){
                $("#target-indicator-id-"+id).val(Number(pre_target)-1);
                $(element).closest("tr").remove();
            }
        }
    }


    function submitForm(){
        scheme_name_validate();
        panchayat_validate();
        indicator_validate(); 
        target_validate();
        year_validate();
      
        block_name_validate();

        if(scheme_name_error || panchayat_error || indicator_error || target_error || year_error ||block_name_error ){ return false; } // error occured
        else{ return true; } // proceed to submit form data
    }
    
  
</script>
@endsection