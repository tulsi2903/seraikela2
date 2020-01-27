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

</style>
@endsection

@section('page-content')
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
                            <td>{{$count++}} </td>
                            <td>{{$data->year_value}}</td>
                            <td>{{$data->geo_name}}</td>
                            <td>{{$data->panchayat_name}}</td>
                            
                            <td>{{$data->scheme_name}}</td>
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
                            ?></td>
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
    <form action="{{url('scheme_performance/delete')}}" method="POST">
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
                        
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="dublicate_data">
                        <!-- append details -->
                    </tbody>
                    
                </table>
                
            </div>
        </div>
           
        <div class="modal-footer">
            <input type="text" name="hidden_input_for_inprogress" id="hidden_input_for_inprogress" value="" hidden>
            <input type="text" name="hidden_input_for_revert" id="hidden_input_for_revert" value="" hidden>
            <button type="button" class="btn btn-secondary waves-effect" onclick="return hide_div();">Cancel</button>
            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
        </div>
    </form>
</div>
<!-- End of view -->
</div>

@endsection

<script>
    function get_view_data(id) {
       
        $("#toggle_div").slideDown(300);
        
       
        $.ajax({
            url: "get/matching-schemes/details"+"/"+id,
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(){
                $("#dublicate_data").html("");
                $("#hidden_input_for_inprogress").html("");
                $("#hidden_input_for_revert").html("");
            },
            success: function (data){
              
                var append;
                var s_no = 0;
              for(var i=0; i<data.tmp_matching; i++ )
              {
                  s_no++;
                append  +=`<tr><td>`+s_no+`</td><td>`+data.Matching[i].year_value+`</td><td>`+data.Matching[i].geo_name+`</td><td>`+data.Matching[i].panchayat_name+`</td><td>`+data.Matching[i].scheme_name+`</td><td>`+data.Matching[i].scheme_asset_name+`</td><td>Attributes</td>
                            <td><button type="button" class="btn btn-primary" onclick="inprogress_request(`+data.Matching[i].scheme_performance_id+`)">In-Progress</button><button type="button" class="btn btn-primary" onclick="revert_request(`+data.Matching[i].scheme_performance_id+`)">Cancel</button></td></tr>`;
              }
              $("#dublicate_data").append(append);

            }
        });
    }
   
   function hide_div()
   {
    $("#toggle_div").slideUp(300);
    }

   function revert_request(id)
   {
     $("#hidden_input_for_revert").val($("#hidden_input_for_revert").val()+","+id);
   
   }

   function inprogress_request(id)
   {
   
    $("#hidden_input_for_inprogress").val($("#hidden_input_for_inprogress").val()+","+id);
   }

  
</script>