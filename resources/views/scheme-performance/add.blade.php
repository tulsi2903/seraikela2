@extends('layout.layout')

@section('title', 'Scheme Performance')

@section('page-content')
<div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row card-tools-still-right" style="background:#fff;">
                    <h4 class="card-title">Scheme Performance</h4>

                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return false" id="scheme-performance">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="scheme_sanction_id">Scheme Sanction ID<span style="color:red;margin-left:5px;">*</span></label>
                        <input type="text" name="scheme_sanction_id" class="form-control" id="scheme_sanction_id" placehonder="Enter sanction id">
                    </div>
                </div>
                <div class="col-md-2">
                    <div style="height:29px;"></div>
                    <button type="button" class="btn btn-primary" onclick="return search()" style="margin-top: 5%;"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                </div>
            </div>
            <div class="invalid-feedback" id="scheme_sanction_id_error_msg"></div>
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
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    var scheme_sanction_id_error = true;

    $(document).ready(function(){
        $("#scheme_sanction_id").change(function(){
            scheme_sanction_id_validate();
        });
    });

    function scheme_sanction_id_validate(){
        reset_target_block();
        var scheme_sanction_id_val = $("#scheme_sanction_id").val();
        if(scheme_sanction_id_val==""){
            scheme_sanction_id_error = true;
            $("#scheme_sanction_id").addClass("is-invalid");
            $("#scheme_sanction_id_error_msg").html("Scheme Sanction ID should not be blank");
            $("#scheme_sanction_id_error_msg").show();
        }
        else{
            scheme_sanction_id_error = false;
            $("#scheme_sanction_id").removeClass("is-invalid");
            $("#scheme_sanction_id_error_msg").html("");
            $("#scheme_sanction_id_error_msg").hide();
        }
    }


    function search(){
        // validations
        scheme_sanction_id_validate();

        var scheme_sanction_id_tmp = $("#scheme_sanction_id").val();

        if(scheme_sanction_id_error==false)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('scheme-performance/get-scheme-performance-datas')}}",
                data: {'scheme_sanction_id': scheme_sanction_id_tmp},
                method:"GET",
                contentType:'application/json',
                dataType:"json",
                beforeSend: function(data){
                    $(".custom-loader").fadeIn(300);
                    reset_target_block();
                },
                error:function(xhr){
                    alert("error"+xhr.status+","+xhr.statusText);
                    $(".custom-loader").fadeOut(300);
                },
                success:function(data){
                    console.log(data);
                    if(data.response=="success")
                    {
                        for(var i=0;i<data.data.length;i++){
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
                            tab_content_append+= `<div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <b>Target:</b>&nbsp;`+data.data[i].target+`
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <table class="table table-bordered table-sm">
                                                        <thead style="background: #cedcff">
                                                                <tr>
                                                                    <th>Indicator Sanction ID</th>
                                                                    <th>Location</th>
                                                                    <th>Completion<br/>Percentage</th>                                        
                                                                    <th>Status</th>                                       
                                                                    <th>Images</th>                                        
                                                                </tr>
                                                        </thead>
                                                        <tbody>`;

                            //  indicator_datas i.e. geo_performance loop starts
                            for(j=0;j<data.data[i].indicator_datas.length;j++){
                            tab_content_append+=     `<tr>
                                                            <td>
                                                                <input type="text" name="scheme_performance_id[]" value="`+data.data[i].indicator_datas[j].scheme_performance_id+`" hidden="">
                                                                `+data.data[i].indicator_datas[j].indicator_sanction_id+`
                                                            </td>
                                                            <td>
                                                                Latuitude: `+(data.data[i].indicator_datas[j].latitude || "")+`<br/>
                                                                Longitude: `+(data.data[i].indicator_datas[j].longitude || "")+`
                                                            </td>                             
                                                            <td>
                                                                <input type="text" class="form-control" value="`+data.data[i].indicator_datas[j].completion_percentage+`" name="completion_percentage[]" placeholder="in %">
                                                            </td>
                                                            <td>
                                                                <select name="status[]" class="form-control">
                                                                    <option value="0"`; if(data.data[i].status=="0"){ tab_content_append+=` selected`; } 
                                                                    tab_content_append+=`>Not-Completed</option>
                                                                    <option value="1"`; if(data.data[i].status=="1"){ tab_content_append+=` selected`; } 
                                                                    tab_content_append+=`>Completed</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="input-icon">
                                                                    <input type="file" class="form-control" name="images_`+data.data[i].indicator_datas[j].scheme_performance_id+`">
                                                                    <span class="input-icon-addon">
                                                                        <i class="fas fa-file-image"></i>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>`;
                            }
                            // indicator_datas i.e. geo_performance loop ends

                            tab_content_append+=       `</tbody> 
                                                    </table>`;
                            tab_content_append+= ` </div>`;
                            $("#target-block .tab-content").append(tab_content_append);

                            // opposite of reset_target_block, i.e. show target block and hide GO Button
                            $("#target-block").show();
                        }
                    }
                    else{ // no data
                        $("#scheme_sanction_id").addClass("is-invalid");
                        $("#scheme_sanction_id_error_msg").html("No data found! Please check/re-enter scheme sanction ID.");
                        $("#scheme_sanction_id_error_msg").show();
                    }
                    $(".custom-loader").fadeOut(300);
                }
            });
        }
    }

    function reset_target_block(){
        $("#target-block").hide();
        $("#target-block .nav").html("");
        $("#target-block .tab-content").html("");
        $("#target-block-error-msg").html("");
    }
</script>

<script>
    // performance validation starts
    function performance_validate(){
        return true;
    }

    function submitForm(){
        // call all validation

        // performance_validate combine all validations and return true or false
        if(performance_validate()){
            // var form_data = $('#scheme-performance').serialize();
            // var formElement = document.querySelector("#scheme-performance");
            // var form_data = new FormData(formElement);
            var formElement = $('#scheme-performance')[0]; 
            var form_data = new FormData(formElement);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('scheme-performance/store')}}",
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


<script>
//     var block_name_error = true;
//     var panchayat_error = true;
//     var district_error = true;
//     var subdivision_error = true;
//     var year_error = true;
//     var scheme_type_error = true;
//     var indicator_error = true;

//     $(document).ready(function() {

//         $("#block").change(function() {
//             // block_name_validate();
//             ajaxFunc_bl();

//         });
//         $("#panchayat").change(function() {
//             // panchayat_validate();

//         });

//         $("#year").change(function() {
//             // year_validate();

//         });
//         $("#district").change(function() {
//             // district_validate();
//             ajaxFunc_subdivision();

//         });
//         $("#subdivision").change(function() {
//             // subdivision_validate();
//             ajaxFunc_block();

//         });
//         $("#scheme_name").change(function() {
//             // scheme_type_validate();
//             // ajaxFunc_indicator();

//         });
//         $("#indicator").change(function() {
//             ajaxFunc_target();


//         });



//     });



//     //function for fetching panchayat according to block
//     function ajaxFunc_bl() {
//         var bl_id_tmp = $("#block").val();

//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });
//         $.ajax({
//             url: "{{url('scheme-performance/get-panchayat-name')}}",
//             data: {
//                 'bl_id': bl_id_tmp
//             },
//             method: "GET",
//             contentType: 'application/json',
//             dataType: "json",
//             beforeSend: function(data) {
//                 $(".custom-loader").fadeIn(300);
//             },
//             error: function(xhr) {
//                 alert("error" + xhr.status + "," + xhr.statusText);
//                 $(".custom-loader").fadeOut(300);
//             },
//             success: function(data) {
//                 console.log(data);
//                 $("#panchayat").html('<option value="">-Select-</option>');
//                 for (var i = 0; i < data.panchayat_data.length; i++) {
//                     $("#panchayat").append('<option value="' + data.panchayat_data[i].geo_id + '">' + data.panchayat_data[i].geo_name + '</option>');
//                 }

//             }
//         });
//     }

//     //function for fetching block from subdivision
//     function ajaxFunc_block() {
//         var sd_id_tmp = $("#subdivision").val();

//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });

//         $.ajax({
//             url: "{{url('scheme-performance/get-block-name')}}",
//             data: {
//                 'sd_id': sd_id_tmp
//             },
//             method: "GET",
//             contentType: 'application/json',
//             dataType: "json",
//             beforeSend: function(data) {
//                 $(".loader").fadeIn(300);
//             },
//             error: function(xhr) {
//                 alert("error" + xhr.status + "," + xhr.statusText);
//                 $(".loader").fadeOut(300);
//             },
//             success: function(data) {
//                 console.log(data);
//                 $("#block").html('<option value="">-Select-</option>');
//                 for (var i = 0; i < data.block_data.length; i++) {
//                     $("#block").append('<option value="' + data.block_data[i].geo_id + '">' + data.block_data[i].geo_name + '</option>');
//                 }

//             }

//         });
//     }

//     //function for fetching subdivions according to district
//     function ajaxFunc_subdivision() {

//         var dist_id_tmp = $("#district").val();

//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });

//         $.ajax({
//             url: "{{url('scheme-performance/get-subdivision-name')}}",
//             data: {
//                 'dist_id': dist_id_tmp
//             },
//             method: "GET",
//             contentType: 'application/json',
//             dataType: "json",
//             beforeSend: function(data) {
//                 $(".custom-loader").fadeIn(300);
//             },
//             error: function(xhr) {
//                 alert("error" + xhr.status + "," + xhr.statusText);
//                 $(".custom-loader").fadeOut(300);
//             },
//             success: function(data) {
//                 console.log(data);
//                 $("#subdivision").html('<option value="">-Select-</option>');
//                 for (var i = 0; i < data.subdivision_data.length; i++) {
//                     $("#subdivision").append('<option value="' + data.subdivision_data[i].geo_id + '">' + data.subdivision_data[i].geo_name + '</option>');
//                 }
//                 $(".custom-loader").fadeOut(300);
//             }

//         });
//     }

//     function ajaxFunc_indicator() {
//         var scheme_name_tmp = $("#scheme_name").val();

//         if (scheme_name_tmp) {
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 }
//             });
//             $.ajax({
//                 url: "{{url('scheme-performance/get-indicator-name')}}",
//                 data: {
//                     'scheme_id': scheme_name_tmp
//                 },
//                 method: "GET",
//                 contentType: 'application/json',
//                 dataType: "json",
//                 beforeSend: function(data) {
//                     $(".custom-loader").fadeIn(300);
//                 },
//                 error: function(xhr) {
//                     alert("error" + xhr.status + ", " + xhr.statusText);
//                     $(".custom-loader").fadeOut(300);
//                 },
//                 success: function(data) {
//                     console.log(data);
//                     // $("#indicator").html('<option value="">-Select-</option>');
//                     // for(var i=0; i<data.scheme_indicator_data.length; i++){
//                     //     $("#indicator").append('<option value="'+data.scheme_indicator_data[i].indicator_id+'">'+data.scheme_indicator_data[i].indicator_name+'</option>');
//                     // }

//                     get_table_data(data);

//                     $(".custom-loader").fadeOut(300);

//                 }
//             });
//         }
//         $("#indicator-tab").show();
//         $("#save-button").show();
//     }





//     function ajaxFunc_target() {
//         var scheme_id_tmp = $("#scheme_name").val();
//         var geo_id_tmp = $("#panchayat").val();
//         var indicator_id_tmp = $("#indicator").val();
//         var year_id_tmp = $("#year").val();
//         var district_tmp = $("#district").val();
//         var subdivision_tmp = $("#subdivision").val();
//         var panchayat_tmp = $("#panchayat").val();



//         if (scheme_id_tmp && geo_id_tmp && year_id_tmp && indicator_id_tmp) {

//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 }
//             });
//             $.ajax({
//                 url: "{{url('scheme-performance/get-target')}}",
//                 data: {
//                     'scheme_id': scheme_id_tmp,
//                     'geo_id': geo_id_tmp,
//                     'year_id': year_id_tmp,
//                     'indicator_id': indicator_id_tmp
//                 },
//                 method: "GET",
//                 contentType: 'application/json',
//                 dataType: "json",
//                 beforeSend: function(data) {
//                     $(".custom-loader").fadeIn(300);
//                 },
//                 error: function(xhr) {
//                     alert("error" + xhr.status + "," + xhr.statusText);
//                     $(".custom-loader").fadeOut(300);
//                 },
//                 success: function(data) {
//                     console.log(data);
//                     $("#scheme_geo_target_id").val(data.id);
//                     $("#pre_value").val(data.pre_value);
//                     if (data.target_get_data == '-1') {

//                         $("#error_msg_if_target_not_found").show();
//                         $("#indicator-tab").hide();
//                         $("#save-button").hide();


//                     } else {
//                         $("#error_msg_if_target_not_found").hide();
//                         $("#indicator-tab").show();
//                         $("#save-button").show();
//                         $("#target").val(data.target_get_data);

//                     }

//                     $(".custom-loader").fadeOut(300);
//                 }
//             });
//         } else {
//             // $("#target").val(0);
//         }


//     }


//     function get_table_data(data) {
//         var to_append = `<div class="col-md-11" id="indicator-tab">
        
//             <button class="btn"  style="margin-left:1.5%;background: #0f85e2!important;color:#fff;"><i class="fas fa-sort-amount-up"></i> &nbsp;Indicator</button>
//                 <div class="card-body" style="background: #f2f6ff; border: 1px solid #a5bbf6;margin-top: -18px;">
//                         <table id="multi-filter-select" class="display table table-striped table-hover" style="margin-top: 10px;">
//                         <thead style="background: #cedcff">
//                             <tr>
//                                 <th>Indicator<span style="color:red;margin-left:5px;">*</span></th>
//                                 <th>Target Value<span style="color:red;margin-left:5px;">*</span></th>
//                                 <th>Previous Value<span style="color:red;margin-left:5px;">*</span></th>
//                                 <th>Current Value<span style="color:red;margin-left:5px;">*</span></th> 
//                                 <th>Upload Document</th>                                          
//                             </tr>
//                         </thead>
//                         <tbody id='show'>`;



//         $.each(data.scheme_indicator_data, function(value, index) {

//             to_append += `<tr> 
//                             <td>
//                                 ` + index.indicator_id + `
//                             </td>
//                             <td>
//                                 <div class="form-group">
//                                     <input type="text" name="target" id="target" class="form-control" autocomplete="off">
//                                     <div class="invalid-feedback" id="target_error_msg"></div>
                                    
//                                 </div>
//                             </td>
//                             <td>
//                                 <div class="form-group">
//                                     <input type="text" name="pre_value" id="pre_value" class="form-control" autocomplete="off">
//                                 </div>
//                             </td>
//                             <td>
//                                 <div class="form-group">
//                                     <input type="text" name="current_value" id="current_value" class="form-control" autocomplete="off">
//                                     <div class="invalid-feedback" id="current_value_error_msg"></div>
//                                 </div>
//                             </td>
//                             <td>
//                                 <div class="form-group">
//                                     <input type="file" name="attchment[]" id="attchment" class="form-control" multiple>
//                                 </div>
//                             </td>
//                         </tr>`;
//             alert("hnrdgtu8hrdsss");

//         });
//         // if(data.scheme_indicator_data.length>0)
//         // {
//         //     for(i=0;i<data.scheme_indicator_data.length;i++)
//         //     {

//         // }

//         to_append += `</tbody>  
//                 </table>`;

//         $('#append-indicator-row').append(to_append);

//         // $('#append-indicator-row').append(to_append1);

//     }









//     //year_validation
//     function year_validate() {
//         var year_val = $("#year").val();
//         if (year_val == "") {
//             year_error = true;
//             $("#year").addClass('is-invalid');
//             $("#year_error_msg").html("Please select year");
//         } else {
//             year_error = false;
//             $("#year").removeClass('is-invalid');
//         }
//     }

//     //district validation
//     function district_validate() {
//         var district_val = $("#district").val();
//         if (district_val == "") {
//             district_error = true;
//             $("#district").addClass('is-invalid');
//             $("#district_error_msg").html("Please select district");

//         } else {
//             district_error = false;
//             $("#district").removeClass('is-invalid');
//         }
//     }

//     //subdivision validate
//     function subdivision_validate() {
//         var subdivision_val = $("#subdivision").val();
//         if (subdivision_val == "") {
//             subdivision_error = true;
//             $("#subdivision").addClass('is-invalid');
//             $("#subdivision_error_msg").html("Please select subdivision");

//         } else {
//             subdivision_error = false;
//             $("#subdivision").removeClass('is-invalid');
//         }
//     }

//     //block name validation
//     function block_name_validate() {
//         var block_name_val = $("#block").val();
//         if (block_name_val == "") {
//             block_name_error = true;
//             $("#block").addClass('is-invalid');
//             $("#block_error_msg").html("Please select block name");
//         } else {
//             block_name_error = false;
//             $("#block").removeClass('is-invalid');
//         }
//     }

//     //panchayat validation
//     function panchayat_validate() {
//         var panchayat_val = $("#panchayat").val();
//         if (panchayat_val == "") {
//             panchayat_error = true;
//             $("#panchayat").addClass('is-invalid');
//             $("#panchayat_error_msg").html("Please select panchayat");
//         } else {
//             panchayat_error = false;
//             $("#panchayat").removeClass('is-invalid');
//         }
//     }

//     // scheme type validation
//     function scheme_type_validate() {
//         var scheme_type_val = $("#scheme_name").val();


//         if (scheme_type_val == "") {
//             scheme_type_error = true;
//             $("#scheme_name").addClass('is-invalid');
//             $("#scheme_type_error_msg").html("Please select scheme");
//         } else {
//             scheme_type_error = false;
//             $("#scheme_name").removeClass('is-invalid');
//         }
//     }

//     //indicator validate
//     function indicator_validate() {
//         var indicator_val = $("#indicator").val();

//         if (indicator_val == "") {
//             indicator_error = true;
//             $("#indicator").addClass('is-invalid');
//             $("#indicator_error_msg").html("Please select indicator");
//         } else {
//             indicator_error = false;
//             $("#indicator").removeClass('is-invalid');
//         }
//     }

//     //for cuurent value validation
//     function current_value_validate() {
//         var current_val = $("#current_value").val();
//         var regexOnlyNumbers = /^[0-9]+$/;
//         if (current_val == "") {
//             current_value_error = true;
//             $("#current_value").addClass('is-invalid');
//             $("#current_value_error_msg").html("Please enter current value");
//         } else if (!regexOnlyNumbers.test(current_val)) {
//             current_value_error = true;
//             $("#current_value").addClass('is-invalid');
//             $("#current_value_error_msg").html("Please enter a valid value");

//         } else {
//             current_value_error = false;
//             $("#current_value").removeClass('is-invalid');
//         }
//     }





//     function goForm() {

//         year_validate();
//         district_validate();
//         subdivision_validate();
//         block_name_validate();
//         panchayat_validate();
//         scheme_type_validate();
//         ajaxFunc_indicator();



//         if (year_error || district_error || subdivision_error || block_name_error || panchayat_error || scheme_type_error) {
//             return false;
//         } // error occured
//         else {
//             return true;

//             // ajaxFunc_target();

//         } // proceed to submit form data
//     }
</script>
@endsection