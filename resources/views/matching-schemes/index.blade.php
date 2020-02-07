@extends('layout.layout') @section('title', 'Matching Schemes') @section('page-style')
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
    
    .notduplicate_record {
        display: none;
    }
    
    .duplicate_record {
        display: none;
    }
</style>
@endsection @section('page-content')
<div class="card">
    <div class="col-md-12">

        <div class="card-header">
            <div class="card-head-row card-tools-still-right" style="background:#fff;">
                <h4 class="card-title">Matching Schemes</h4>
                <div class="card-tools">

                    <!-- <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
                </div>
            </div>
        </div>
    </div>
    <br>
    <a href="{{url('matching-schemes/view')}}"><button type="button" class="btn btn-info" style="border-radius:50px;width:200px;margin-left:50px;">View Matching Schemes</button></a>
    <div class="col-md-12">
        <div class="card-body">
            <table class="table-datatable display table table-striped table-hover">
                <thead style="background: #d6dcff;color: #000;">

                    <tr>
                        <th>#</th>
                        <th>Year</th>
                        <th>Block</th>
                        <th>Panchayat</th>
                        <th>Schemes</th>
                        <th>Assests</th>
                        <th>No of Matching Column</th>
                        <th>Attributes</th>
                        <th>Action</th>

                    </tr>
                    

                </thead>
                <?php $count=1; ?>
                    <tbody>

                        @foreach($datas as $data)

                        <tr>
                            <td>{{$count++}}

                            </td>
                            <td>{{$data->year_value}}</td>
                            <td>{{$data->geo_name}}</td>
                            <td>{{$data->panchayat_name}}</td>

                            <td>{{$data->scheme_name}}({{$data->scheme_short_name}})</td>
                            <td>{{$data->scheme_asset_name}}</td>
                            <td>
                                <?php $matching_array=explode(',',$data['matching_performance_id']);
                                    $matching_count=count($matching_array);
                                    echo $matching_count;
                                ?>

                            </td>
                            <td>

                                <?php   
                                 $attribute[0]=unserialize($data->attribute);
                                 $print_att;
                                 foreach($attribute[0][0] as $key_at=>$value_att)
                                 {
                                     $print_att=$value_att;
                                 }
                             print_r( $print_att);
                            ?>
                            </td>
                            <td>

                                <a href="javascript:void(0);" class="btn btn-sm btn-secondary" onclick="get_view_data({{$data->id}})"><i class="fas fa-eye"></i></a>

                            </td>

                        </tr>
                        @endforeach

                    </tbody>
            </table>

        </div>
    </div>

    <!-- View Div -->
    <div id="toggle_div" style="display:none;">
        <form action="{{url('scheme_performance/status_update')}}" name="myForm" id="duplicate-form" method="POST">
            @csrf
            <div class="modal-body">
                <div class="row" style="padding:2em;margin-top: -3em;">
                    <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>Block</th>
                                <th>Panchayat</th>
                                <th>Schemes</th>
                                <th>Assests</th>
                                <th>Attributes</th>

                                <th>Status Is</th>
                                <!-- <th>Date</th> -->
                                <th>Comment</th>
                                <!-- <th>Cancel</th> -->
                            </tr>
                        </thead>
                        <tbody id="dublicate_data">
                            <!-- append details -->
                        </tbody>

                    </table>

                </div>
            </div>

            <div class="modal-footer">
                <!-- <input type="text" name="count_value" id="count_value" hidden> -->
                <!-- <input type="text" name="hidden_input_for_scheme_performance_id" id="hidden_input_for_scheme_performance_id"> -->
                <input type="text" name="hidden_input_for_inprogress" id="hidden_input_for_inprogress" value="" >
                <input type="text" name="hidden_input_for_revert" id="hidden_input_for_revert" value="" >


                <button type="button" class="btn btn-secondary waves-effect" onclick="return hide_div();">Cancel</button>
                <!-- <button type="submit" class="btn btn-info waves-effect waves-light" onclick="return before_save({{$data->id}})">Save</button> -->
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="return validateForm()">Save</button>
                <input type="text" name="to_data" id="to_data" hidden>

            </div>
        </form>
    </div>
    <!-- End of view -->
</div>



<script>
    // variable to use globally
  

    var selected_inprogress = new Array;
    var selected_revert = new Array;
    var total_duplicate_record = 0;
    //

    function get_view_data(id) {

        $("#toggle_div").slideDown(300);

        $.ajax({
            url: "get/matching-schemes/details" + "/" + id,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function() {
                $("#dublicate_data").html("");
                $("#hidden_input_for_inprogress").val("");
                $("#hidden_input_for_revert").val("");
               
                selected_inprogress = [];
                selected_revert = [];
                total_duplicate_record = 0;

            },
            success: function(data) {
                //   console.log(data.Data.not_duplicate);

                 $("#hidden_input_for_inprogress").val(data.Data.not_duplicate);
                 selected_inprogress = data.Data.not_duplicate.split(",");
                //  console.log(data.Data.not_duplicate);
                alert(selected_inprogress);


                $("#hidden_input_for_revert").val(data.Data.duplicate);
                selected_revert = data.Data.duplicate.split(",");
                // console.log(data.Data.duplicate);
             
                total_duplicate_record =parseInt(data.tmp_matching);
                // console.log(total_duplicate_record);

                var append;
                var s_no = 0;
                for (var i = 0; i < data.tmp_matching; i++) {

                    s_no++;
                    append += `<tr><td><input type="text" name="get_scheme_performance_id" value="`+data.scheme_performance_id_to_append+`" hidden>` + s_no + `</td><td>` + data.Matching[i].year_value + `</td><td>` + data.Matching[i].geo_name + `</td>
                            <td>` + data.Matching[i].panchayat_name + `</td><td>` + data.Matching[i].scheme_short_name + `</td><td>` + data.Matching[i].scheme_asset_name + `</td>
                            <td>` + data.Matching[i].attribute + `</td>
                            <td>
                            <input type="text" name="matching_id" value="` + id + `" hidden>
                            <input type="text" name="scheme_performance_id[]" value="` + data.Matching[i].scheme_performance_id + `" hidden>`;

                   

                    if(data.Matching[i].type=="not_duplicate"){
                        append += `<span class="">This particular record is not duplicate</span><a href="javascript:void();" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
                    }
                    else if(data.Matching[i].type=="duplicate"){
                        append += `<span class="" style="color:red;">This particular record is duplicate</span><a href="javascript:void();" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
                    }
                    else{
                        append += `<button type="button" class="btn btn-primary inprogress"  onclick="inprogress_request(`+ data.Matching[i].scheme_performance_id + `,this)">Not Duplicate</button><span class="notduplicate_record">This particular record is not duplicate</span>
                        <button type="button" class="btn btn-primary revert" onclick="revert_request(`+ data.Matching[i].scheme_performance_id + `,this)">Duplicate</button><span style="color:red" class="duplicate_record">This particular record is duplicate</span>`;
                    }
                  
                      append += `</td>`;      



                if(data.append_comment[i]!= null){
                append += `<td><textarea class="form-control" name="comment[]">`+data.append_comment[i]+`</textarea></td>`;
                }
                else{
                    append += `<td><textarea class="form-control" name="comment[]"></textarea></td>`;
                }

                   
                }
                $("#dublicate_data").append(append);

            }
        });
    }



    function hide_div() {
        $("#toggle_div").slideUp(300);

    }

    function revert_request(id, e) {
        if (!selected_revert.includes(id)) {
            selected_revert.push(id);
            $("#hidden_input_for_revert").val(selected_revert);
            var tr = $(e).closest("tr");
            $(tr).find(".duplicate_record").show();
            $(tr).find(".inprogress").hide();
            $(tr).find(".revert").hide();
            // console.log(selected_revert);
        }
    }
    function inprogress_request(id, e) {
        if (!selected_inprogress.includes(id)) {
            selected_inprogress.push(id);
            $("#hidden_input_for_inprogress").val(selected_inprogress);
            var tr = $(e).closest("tr");
            $(tr).find(".notduplicate_record").show();
            $(tr).find(".inprogress").hide();
            $(tr).find(".revert").hide();
        }
    }
  
   
</script>
<script>
    function undo_data(primary_id_value,scheme_performance_id_value,matching_id_value,e){
       
        $.ajax({
            url: "undo/matching-scheme/data" + "?id=" + primary_id_value + "&scheme_performance_id=" +scheme_performance_id_value + "&matching_id="+ matching_id_value,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function() {

            },
            success: function(data) {
               console.log(primary_id_value);
               console.log(scheme_performance_id_value);
               console.log(matching_id_value);
               console.log(data.response);

               //fetching buttons in view page

            //    var tr = $(e).closest("tr");
            //    $(tr).find(".notduplicate_record").hide(); //not duplicate message
            //    $(tr).find(".duplicate").hide(); //duplicate message
            //    $(tr).find(".inprogress").show(); //not duplicate button
            //     $(tr).find(".revert").show(); //duplicate button
               
           
            }
        });
    }
</script>
<script>
    function validateForm() {
        //   alert(selected_inprogress.length);
        //  alert(selected_revert.length);
        //  alert(total_duplicate_record);

        if((selected_inprogress.length + selected_revert.length) == total_duplicate_record){
    
            $("#duplicate-form").submit();
        } 
        else{
            swal({
                title: 'Either of your status is unchecked, do you want to check it?',
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
                    // window.location = href;
                    
                } else {
                    $("#duplicate-form").submit();
                }
            });
        }
    }
</script>
@endsection