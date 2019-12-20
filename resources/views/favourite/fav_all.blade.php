@extends('layout.layout')

@section('title', 'Department')

@section('page-style')
    <style>
        
    </style>
@endsection


@section('page-content')

<!-------------------------------------------------starting of body---------------------------------------------------->
        <div class="card">
            <div class="col-md-12">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right" style="background:#fff;">
                            <h4 class="card-title">Add Favourites Details</h4>
                            <div class="card-tools">
                                <!-- <button type="button" class="btn btn-icon btn-round btn-success" data-target="#create-email" data-toggle="modal" ><i class="fa fa-envelope" aria-hidden="true"></i></button> -->
                                <button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button>
                                <!-- <a id="toggle1" class="btn btn-secondary department-add-button" href="javascript:void();" role="button"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add</a> -->
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <div class="nav flex-column nav-pills nav-secondary nav-pills-no-bd" id="v-pills-tab-without-border" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="v-pills-home-tab-nobd" data-toggle="pill" href="#v-pills-home-nobd" role="tab" aria-controls="v-pills-home-nobd" aria-selected="true">Departments</a>
                                    <a class="nav-link" id="v-pills-profile-tab-nobd" data-toggle="pill" href="#v-pills-profile-nobd" role="tab" aria-controls="v-pills-profile-nobd" aria-selected="false">Scheme</a>
                                    <a class="nav-link" id="v-pills-messages-tab-nobd" data-toggle="pill" href="#v-pills-messages-nobd" role="tab" aria-controls="v-pills-messages-nobd" aria-selected="false">Block</a>
                                    <a class="nav-link" id="v-pills-report-tab-nobd" data-toggle="pill" href="#v-pills-report-nobd" role="tab" aria-controls="v-pills-report-nobd" aria-selected="false">Panchayat</a>

                                </div>
                            </div>
                        
                            <div class="col-md-10" id="printable-area">
                                <div class="tab-content" id="v-pills-without-border-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home-nobd" role="tabpanel" aria-labelledby="v-pills-home-tab-nobd">
                                            <form action="{{url('fav_department')}}" method="post" >
                                                    @csrf
                                      
                                            <div class="table-responsive">
                                                <table class="display  table table-striped table-hover" >
                                                    <thead style="background: #d6dcff;color: #000;">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Department Name</th>
                                                            <th>Fav</th>
                                                                       
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                                                    
                                                    @if(count($datas_dept)!=0)
                                                        @foreach($datas_dept as $key => $val)
                                                            <!-- @if($val->is_active=='1') -->
                                                                <tr>
                                                                    <td><input type="checkbox" name="dept_id[]" value="{{$val->dept_id}}" @if($val->checked==1) checked @endif> </td>
                                                                    <td>{{$val->dept_name}}</d>
                                                                    <TD>@if($val->checked==1) 1 @else 0 @endif</TD>
                                                                </tr>
                                                            <!-- @endif -->
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table> 
                                            </div>
                                           
                                      
                                        <hr class="new2" style="width: 98%;"> 
                                        <div class="card-action" style="margin-top: -29px;">   
                                                <button type="submit" class="btn btn-secondary">Submit</button>
                                            </div>
                                        </form>
                                    </div><!--END OF TAB-->
                                
                                    
                                    <div class="tab-pane fade" id="v-pills-profile-nobd" role="tabpanel" aria-labelledby="v-pills-profile-tab-nobd">
                                            <form action="{{url('fav_scheme')}}" method="post">
                                                    @csrf
                                        <div class="card-body" style="margin-top:-32px;">											
                                            <div class="table-responsive">
                                                <table class="table table-datatable"   >
                                                    <thead style="background: #d6dcff;color: #000;">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Scheme Name</th>
                                                            <th>Short Name</th>
                                                                 
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                         @if(count($datas_scheme)!=0)
                                                            @foreach($datas_scheme as $key => $val)
                                                                <tr>
                                                                    <td><input type="checkbox" name="scheme_id[]" value="{{$val->scheme_id}}" @if($val->checked==1) checked @endif></td>
                                                                    <td>{{$val->scheme_name}}</td>
                                                                    <td>{{$val->scheme_short_name}}</td>
                                                                    <!-- <td>{{$val->scheme_id}}</td> -->
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!--end of card body-->
                                        <hr class="new2" style="width: 98%;"> 
                                        <div class="card-action" style="margin-top: -29px;">   
                                                <button type="submit" class="btn btn-secondary">Submit</button>
                                            </div>
                                            </form>
                                    </div>  <!-- end of tab -->

                                    <div class="tab-pane fade" id="v-pills-messages-nobd" role="tabpanel" aria-labelledby="v-pills-messages-tab-nobd">
                                            <form action="{{url('fav_block')}}" method="post">
                                                    @csrf
                                        <div class="card-body" style="margin-top:-32px;">											
                                            <div class="table-responsive">
                                                    <table class="table table-datatable" id="printable-area">
                                                            <thead style="background: #d6dcff;color: #000;">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Name of Block</th>
                                                                    <!-- <th>Name of Block</th> -->
                                                                </tr>
                                                            </thead>
                                                           
                                                            @if(count($datas_block)!=0)
                                                                @foreach($datas_block as $key => $val)
                                                                    <tr>
                                                                        <td><input type="checkbox" name="block_id[]" value={{$val->geo_id}} @if($val->checked==1) checked @endif></td>
                                                                        <td>{{$val->geo_name}}</td>
                                                                        <!-- <td>{{$val->geo_id}}</td> -->
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </table>
                                            </div>
                                        </div><!--end of card body-->
                                        <hr class="new2" style="width: 98%;"> 
                                        <div class="card-action" style="margin-top: -29px;">   
                                                <button class="btn btn-secondary">Submit</button>
                                            </div>
                                            </form>
                                    </div>
                                    

                                    <div class="tab-pane fade" id="v-pills-report-nobd" role="tabpanel" aria-labelledby="v-pills-report-tab-nobd" style="overflow-y: scroll; height:600px;">
                                            <form action="{{url('fav_panchayat')}}" method="post">
                                                    @csrf
                                        <div class="card-body" style="margin-top:-32px;">																								
                                            <div class="table-responsive">
                                                <table class="display table-datatable table table-striped table-hover">
                                                    <thead style="background: #d6dcff;color: #000;">
                                                        <tr>
                                                            <th colspan="5">Name of Panchayat</th>
                                                        </tr>
                                                    </thead>
                                                
                                                    <tbody>
                                                        @if(count($datas_panchayat)!=0)
                                                
                                                        <tr>
                                                            @foreach($datas_panchayat as $key => $val)
                                                            <td><input type="checkbox" name="panchayat_id[]"  value={{$val->geo_id}}@if($val->checked==1) checked @endif> {{$datas_panchayat[$key]['geo_name']}}</td>
                                                            <?php 
                                                                if((($key+1) % 5) == 0){
                                                                    echo "</tr><tr>";
                                                                }
                                                            ?>
                                                            @endforeach
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!--end of card body-->
                                        <hr class="new2" style="width: 98%;"> 
                                        <div class="card-action" style="margin-top: -29px;">   
                                                <button class="btn btn-secondary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            
                                
                            </form>  
                            </div>                       
                        </div>
                    </div>
             
          
</div><!--end of content-->
</div>

@endsection

  
