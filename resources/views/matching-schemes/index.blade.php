@extends('layout.layout') 

@section('title', 'Matching Schemes') 

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
    
    .notduplicate_record {
        display: none;
    }
    
    .duplicate_record {
        display: none;
    }

    #toggle_div table td{
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
    table tr td, table tr th, .table-striped td, .table-striped th{
        border: 1px solid rgb(165, 165, 165) !important;
        border-top: 1px solid rgb(165, 165, 165) !important;
        border-bottom: 1px solid rgb(165, 165, 165) !important;
    }
</style>
@endsection 

@section('page-content')
<div class="card">
    <div class="card-header">
        <div class="card-head-row card-tools-still-right" style="background:#fff;">
            <h4 class="card-title">Matching Schemes Datas</h4>
            <div class="card-tools">
                <!-- <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- <a href="{{url('matching-schemes/view')}}" class="btn btn-secondary">View Matching Schemes</a>
        <br/>
        <br/> -->
        <table class="display table table-striped table-hover">
            <thead>
                <tr style="background: #d6dcff;color: #000;">
                    <th>#</th>
                    <th>Schemes</th>
                    <th>Year</th>
                    <th>Block</th>
                    <th>Panchayat</th>
                    <th>Asset</th>
                    <th>No of Matching Work Datas</th>
                    <th>Attributes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php $count=1; ?>
            <tbody>
                @foreach($datas as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->scheme_name}}({{$data->scheme_short_name}})</td>
                    <td>{{$data->year_value}}</td>
                    <td>{{$data->geo_name}}</td>
                    <td>{{$data->panchayat_name}}</td>
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
                        <a href="javascript:void(0);" class="btn btn-sm btn-secondary" onclick="get_view_data({{$data->id}})" title="Click to view duplicate work data"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>



        <!-- View Div -->
        <div id="duplicate-form-block" style="display: none;border: 1px solid rgb(206, 206, 206); border-radius: 5px;padding: 15px; background: linear-gradient(to top, #a5baef, #ffffff 70%, #ffffff, #ffffff 100%); margin: 15px 0 15px 0;">
            <h4 style="color: black;">Duplicate Work Datas</h4>
            <form action="{{url('matching-scheme/assign-to')}}" method="POST">
                @csrf
                <table class="display table table-striped table-hover">
                    <thead style="background: #9ec5ff;color: #000;">
                        <tr>
                            <th>#</th>
                            <th>Schemes</th>
                            <th>Year</th>
                            <th>Location</th>
                            <th>Asset</th>
                            <th>Attributes</th>
                            <th>Change Status</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody id="duplicate-form-tbody">
                        <!-- append details -->
                    </tbody>
                </table>
                <div style="text-align: right; margin: 0 -15px -15px -15px; padding: 15px; border-top: 1px solid rgb(146, 146, 146);">
                    <input type="text" name="id" id="to-update-data-id" value="">
                    <button type="button" class="btn btn-secondary waves-effect" onclick="return hide_div();">Cancel</button>
                    <button type="button" class="btn btn-info waves-effect waves-light" onclick="return saveData()">Save</button>
                </div>  
            </form>
        </div>
        <!-- End of view -->


    </div>
</div>



<script>
    // variable to use globally
    var matching_performance_ids_count = 0;
    var selected_performance_ids_count = 0;

    // function get_view_data(id) {
    //     $("#toggle_div").fadeOut(50); // reset

    //     $.ajax({
    //         url: "matching-scheme/get-all-matching-datas" + "?id=" + id,
    //         method: "GET",
    //         contentType: 'application/json',
    //         dataType: "json",
    //         beforeSend: function() {
    //             $("#dublicate_data").html("");
    //             $("#hidden_input_for_inprogress").val("");
    //             $("#hidden_input_for_revert").val("");
    //             selected_inprogress = [];
    //             selected_revert = [];
    //             total_duplicate_record = 0;
    //         },
    //         success: function(data) {
    //             // console.log(data);
    //             if(data.Data.not_duplicate){
    //                 selected_performance_ids_count+=data.Data.not_duplicate.split(",").length;
    //             }  
    //             if(data.Data.duplicate){
    //                 selected_performance_ids_count+=data.Data.duplicate.split(",").length;
    //             }
    //             matching_performance_ids_count =parseInt(data.tmp_matching);
    //             // console.log(selected_performance_ids_count+ ", " +matching_performance_ids_count);
    //             var append;
    //             var s_no = 0;
    //             for (var i = 0; i < data.tmp_matching; i++) {
    //                 s_no++;
    //                 append += `<tr>
    //                             <td><input type="text" name="get_scheme_performance_id" value="`+data.scheme_performance_id_to_append+`" hidden>` + s_no + `</td><td>` + data.Matching[i].year_value + `</td><td>` + data.Matching[i].geo_name + `</td>
    //                             <td>` + data.Matching[i].panchayat_name + `</td><td>` + data.Matching[i].scheme_short_name + `</td><td>` + (data.Matching[i].scheme_asset_name || "N/A")+ `</td>
    //                             <td>` + data.Matching[i].attribute + `</td>
    //                             <td>
    //                             <input type="text" name="matching_id" value="` + id + `" hidden>
    //                             <input type="text" name="scheme_performance_id[]" value="` + data.Matching[i].scheme_performance_id + `" hidden>`;

    //                         if(data.Matching[i].type=="not_duplicate"){
    //                             append += `<span class="duplicate_msg" style="color:red;display:none;">This particular record is duplicate</span>`;
    //                             append += `<span class="not_duplicate_msg">This particular record is not duplicate</span></a>`;
    //                             append += `<button type="button" style="display:none;" class="btn btn-primary btn-inprogress" onclick="status_not_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)");">Not Duplicate</button>
    //                             <button type="button" style="display:none;" class="btn btn-primary btn-revert" onclick="status_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)">Duplicate</button>`;
    //                             append += ` &nbsp;<a href="javascript:void();" class="undo_icon_not_duplicate" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
    //                         }
    //                         else if(data.Matching[i].type=="duplicate"){
    //                             append += `<span class="duplicate_msg" style="color:red;">This particular record is duplicate</span>`;
    //                             append += `<span class="not_duplicate_msg" style="display:none;">This particular record is not duplicate</span></a>`;
    //                             append += `<button type="button" style="display:none;" class="btn btn-primary btn-inprogress" onclick="status_not_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)");">Not Duplicate</button>
    //                             <button type="button" style="display:none;" class="btn btn-primary btn-revert" onclick="status_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)">Duplicate</button>`;
    //                             append += ` &nbsp;<a href="javascript:void();" class="undo_icon_not_duplicate" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
    //                         }
    //                         else{
    //                             append += `<span class="duplicate_msg" style="color:red;display:none;">This particular record is duplicate</span>`;
    //                             append += `<span class="not_duplicate_msg" style="display:none;">This particular record is not duplicate</span></a>`;
    //                             append += `<button type="button"  class="btn btn-primary btn-inprogress" onclick="status_not_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)");">Not Duplicate</button>
    //                             <button type="button"  class="btn btn-primary btn-revert" onclick="status_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)">Duplicate</button>`;
    //                             append += ` &nbsp;<a href="javascript:void();" style="display:none;" class="undo_icon_not_duplicate" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
    //                         }
                  
    //                 append += `</td>`;
    //                 if(data.append_comment[i]!= null){
    //                     append += `<td><input class="form-control" name="comment[]" value="`+data.append_comment[i]+`" placeholder="comment"></td>`;
    //                 }
    //                 else{
    //                     append += `<td><input class="form-control" name="comment[]" placeholder="comment"></td>`;
    //                 }
    //             }
    //             $("#dublicate_data").append(append);
    //             $("#toggle_div").slideDown(150);
    //         }
    //     });
    // }

    function get_view_data(id) {
        $("#duplicate-form-block").fadeOut(50); // reset
        $("#duplicate-form-tbody").html(""); // reset

        $.ajax({
            url: "matching-scheme/get-all-matching-datas" + "?id=" + id,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function() {
                $(".custom-loader").fadeIn(150);
            },
            success: function(data) {
                console.log(data);
                var append;
                for (var i = 0; i < data.matching_performance_datas.length; i++) {
                    append += `<tr data-type="`+data.matching_performance_datas[i].type+`">
                                <td>
                                    <input type="text" name="matching_performance_ids" value="`+data.matching_performance_datas[i].scheme_performance_id+`">
                                    `+(i+1)+`
                                </td>
                                <td>` + data.matching_performance_datas[i].year_value + `</td>
                                <td>` + data.matching_performance_datas[i].scheme_short_name + `</td>
                                <td>Block: ` + data.matching_performance_datas[i].geo_name + `<br/>Panchayat: ` + data.matching_performance_datas[i].panchayat_name + `</td>
                                <td>` + (data.matching_performance_datas[i].scheme_asset_name || "N/A")+ `</td>
                                <td>` + data.matching_performance_datas[i].attribute + `</td>
                                <td>`;

                                append+=`
                                    <select name="status[]" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="probable_duplicate"
                                        `;
                                        if(data.matching_performance_datas[i].type=="probable_duplicate"){
                                            append+=` selected`;
                                        }
                                        append+=`>Probable Duplicate</option>
                                        <option value="duplicate"
                                        `;
                                        if(data.matching_performance_datas[i].type=="duplicate"){
                                            append+=` selected`;
                                        }
                                        append+=`>Duplicate Data</option>
                                        <option value="not_duplicate"
                                        `;
                                        if(data.matching_performance_datas[i].type=="not_duplicate"){
                                            append+=` selected`;
                                        }
                                        append+=`>Not Duplicate Data</option>
                                    </select>
                                `;

                                // if(data.Matching[i].type=="not_duplicate"){
                                //     append += `<span class="duplicate_msg" style="color:red;display:none;">This particular record is duplicate</span>`;
                                //     append += `<span class="not_duplicate_msg">This particular record is not duplicate</span></a>`;
                                //     append += `<button type="button" style="display:none;" class="btn btn-primary btn-inprogress" onclick="status_not_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)");">Not Duplicate</button>
                                //     <button type="button" style="display:none;" class="btn btn-primary btn-revert" onclick="status_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)">Duplicate</button>`;
                                //     append += ` &nbsp;<a href="javascript:void();" class="undo_icon_not_duplicate" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
                                // }
                                // else if(data.Matching[i].type=="duplicate"){
                                //     append += `<span class="duplicate_msg" style="color:red;">This particular record is duplicate</span>`;
                                //     append += `<span class="not_duplicate_msg" style="display:none;">This particular record is not duplicate</span></a>`;
                                //     append += `<button type="button" style="display:none;" class="btn btn-primary btn-inprogress" onclick="status_not_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)");">Not Duplicate</button>
                                //     <button type="button" style="display:none;" class="btn btn-primary btn-revert" onclick="status_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)">Duplicate</button>`;
                                //     append += ` &nbsp;<a href="javascript:void();" class="undo_icon_not_duplicate" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
                                // }
                                // else{
                                //     append += `<span class="duplicate_msg" style="color:red;display:none;">This particular record is duplicate</span>`;
                                //     append += `<span class="not_duplicate_msg" style="display:none;">This particular record is not duplicate</span></a>`;
                                //     append += `<button type="button"  class="btn btn-primary btn-inprogress" onclick="status_not_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)");">Not Duplicate</button>
                                //     <button type="button"  class="btn btn-primary btn-revert" onclick="status_duplicate_matching_schemes(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this)">Duplicate</button>`;
                                //     append += ` &nbsp;<a href="javascript:void();" style="display:none;" class="undo_icon_not_duplicate" onclick="undo_data(`+id+`,`+data.scheme_performance_id_to_append+`,`+data.Matching[i].scheme_performance_id+`,this);"><i class="fa fa-undo" aria-hidden="true" style="color:blue;"></i></a>`;
                                // }
                    
                    append += `</td>`;
                    append += `<td><input class="form-control" name="comment[]" value="`+(data.matching_performance_datas[i].comments || '')+`" placeholder="comment"></td>`;
                }
                $("#duplicate-form-tbody").append(append);
                $("#to-update-data-id").val(id);
                $("#duplicate-form-block").slideDown(150);
                $(".custom-loader").fadeOut(300);
            }
        });
    }

    function hide_div() {
        $("#duplicate-form-block").slideUp(300);
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
                // alert("hi");
            },
            success: function(data) {
        
                // console.log(data);
                // count
                matching_performance_ids_count = data.matching_performance_ids_count;
                selected_performance_ids_count = data.selected_performance_ids_count;

                if(data.response == true)
                {
                    var tr = $(e).closest("tr");
                    $(tr).find(".undo_icon_not_duplicate").hide();
                    $(tr).find(".duplicate_msg").hide();
                    $(tr).find(".not_duplicate_msg").hide();
                    $(tr).find(".btn-inprogress").show();
                    $(tr).find(".btn-revert").show();
                }
            }
        });
    }
</script>
<script>
    function status_duplicate_matching_schemes(primary_id_value,scheme_performance_id_value,matching_id_value,e)
    {
        $.ajax({
            url: "status-duplicate/change/matching-scheme/data"+ "?id=" +primary_id_value+ "&scheme_performance_id=" +scheme_performance_id_value+ "&matching_id="+matching_id_value,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(){

            },
            success:function(data){
                // console.log(data);
                // count
                matching_performance_ids_count = data.matching_performance_ids_count;
                selected_performance_ids_count = data.selected_performance_ids_count;

                if(data.response == true)
                {
                    var tr = $(e).closest("tr");
                    $(tr).find(".undo_icon_not_duplicate").show();
                    $(tr).find(".duplicate_msg").show();
                    $(tr).find(".not_duplicate_msg").hide();
                    $(tr).find(".btn-inprogress").hide();
                    $(tr).find(".btn-revert").hide();
                }
            }
        });
    }
</script>
<script>
    function status_not_duplicate_matching_schemes(primary_id_value,scheme_performance_id_value,matching_id_value,e)
    {
        $.ajax({
            url: "status-not-duplicate/change/matching-scheme/data"+ "?id=" +primary_id_value+ "&scheme_performance_id=" +scheme_performance_id_value+ "&matching_id="+matching_id_value,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(){

            },
            success:function(data){
                // console.log(data);
                // count
                matching_performance_ids_count = data.matching_performance_ids_count;
                selected_performance_ids_count = data.selected_performance_ids_count;

                if(data.response == true)
                {
                    var tr = $(e).closest("tr");
                    $(tr).find(".undo_icon_not_duplicate").show();
                    $(tr).find(".duplicate_msg").hide();
                    $(tr).find(".not_duplicate_msg").show();
                    $(tr).find(".btn-inprogress").hide();
                    $(tr).find(".btn-revert").hide();
                }
            }
        });
    }
</script>
<script>
    function validateForm() {

        if(matching_performance_ids_count == selected_performance_ids_count){
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


    function saveData(){
        // var tr = $("#duplicate-form-tbody").find("tr");
        
        return true;
    }
</script>
@endsection