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
                           
                        </tr>
                    </thead>
                    <?php $count=1; ?>
                    <tbody>
                        
                      @foreach($datas as $data)
                      <?php 
                      $scheme_performance=DB::table('scheme_performance')->where('scheme_performance_id',$data['scheme_performance_id'])->first();
                      $attributes=unserialize($scheme_performance->attribute);
                     
                      $scheme_name=DB::table('scheme_structure')->where('scheme_id',$scheme_performance->scheme_id)->first();
                      $year_id=DB::table('year')->where('year_id',$scheme_performance->year_id)->first();
                      $block_id = DB::table('geo_structure')->where('geo_id', $scheme_performance->block_id)->first();
                      $panchayat_id = DB::table('geo_structure')->where('geo_id', $scheme_performance->panchayat_id)->first();
                      $scheme_assets= DB::table('scheme_assets')->where('scheme_asset_id',$scheme_performance->scheme_asset_id)->first();

                      ?>
                        <tr>
                            <td>{{$count++}} </td>
                            <td>{{$year_id->year_value}}</td>
                            <td>{{$block_id->geo_name}}</td>
                            <td>{{$panchayat_id->geo_name}}</td>
                            
                            <td>{{$scheme_name->scheme_name}}</td>
                            <td>{{$scheme_assets->scheme_asset_name}}</td>
                            <td> 
                                <?php $matching_array=explode(',',$data['matching_performance_id']);
                                    $matching_count=count($matching_array);
                                    echo $matching_count;
                                ?>

                            </td>
                            <td> <?php   
                                foreach($attributes[0] as $key_att=>$value_att)
                                {
                                    $print_att=$value_att;
                                }   
                                echo $print_att;
                            ?></td>
                            
                           
                    
                        </tr>
                       @endforeach
                       
                    </tbody>
                </table>
           
            
        </div>
    </div>
</div>










@endsection