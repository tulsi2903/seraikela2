@extends('layout.layout')

@section('title', 'Designation Permission')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
    <div class="card">
        <div class="col-md-12"> 
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Designation Permission</h4>
                        <div class="card-tools">
                            <a href="#" data-toggle="tooltip" title="Send Mail"><button type="button" class="btn btn-icon btn-round btn-success"><i class="fa fa-envelope" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Print"><button type="button" class="btn btn-icon btn-round btn-default" id="print-button" onclick="printView();"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Export to PDF"><button type="button" class="btn btn-icon btn-round btn-warning" ><i class="fas fa-file-export"></i></button></a>
                            <a href="#" data-toggle="tooltip" title="Export to Excel"><button type="button" class="btn btn-icon btn-round btn-primary" ><i class="fas fa-file-excel"></i></button></a>
                        </div>
                    </div>
                </div>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                        <?php
                            for($i=0;$i<count($to_return_designation);$i++){
                                ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?Php if($i==0){ echo "active"; } ?>" id="{{$to_return_designation[$i]->desig_id}}-tab" data-toggle="tab" href="#view-tab-{{$to_return_designation[$i]->desig_id}}" role="tab" aria-selected="true">{{$to_return_designation[$i]->name}}</a>
                                    </li>
                                <?php
                            }
                        ?>
                    </ul>
                    <hr>
                    <div class="tab-content mt-2 mb-3">
                        <!-- different views -->
                        <?php 
                        for($i=0;$i<count($to_return);$i++){
                            ?>
                                <div class="tab-pane fade <?Php if($i==0){ echo "show active"; } ?>" id="view-tab-{{$to_return[$i][0]->desig_id}}" role="tabpanel">
                                    <h4>{{$to_return[$i][0]->name}} Permissions</h4>
                                    <form action="{{url('designation-permission/save-permissions')}}" method="POST">
                                    @csrf
                                        <table class="table table-datatable" id="printable-area">
                                            <thead style="background: #d6dcff;color: #000;">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Module Name</th>
                                                    <th>Add</th>
                                                    <th>Edit</th>
                                                    <th>View</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-transform: capitalize;">
                                                <?php
                                                for($j=0;$j<count($to_return[$i]);$j++)
                                                {
                                                    ?>
                                                        <tr>
                                                            <td width="40px;">{{$j+1}}</td>
                                                            <td>{{$to_return[$i][$j]->module}}</td>
                                                            <td><input type="checkbox" name="add[]" value="{{$to_return[$i][$j]->module_id}}" <?php if($to_return[$i][$j]->add==1){ echo "checked"; } ?>></td>
                                                            <td><input type="checkbox" name="edit[]" value="{{$to_return[$i][$j]->module_id}}" <?php if($to_return[$i][$j]->edit==1){ echo "checked"; } ?>></td>
                                                            <td><input type="checkbox" name="view[]" value="{{$to_return[$i][$j]->module_id}}" <?php if($to_return[$i][$j]->view==1){ echo "checked"; } ?>></td>
                                                            <td><input type="checkbox" name="del[]" value="{{$to_return[$i][$j]->module_id}}" <?php if($to_return[$i][$j]->del==1){ echo "checked"; } ?>></td>
                                                        </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <input type="text" name="desig_id" value="{{$to_return[$i][0]->desig_id}}" hidden>
                                        <div style="text-align:right"><button type="submit" class="btn btn-secondary">Save Changes&nbsp;<i class="fas fa-check"></i></button></div>
                                    </form>
                                </div>
                            <?php
                        }
                        ?>
                        <!-- <div class="tab-pane fade show active" id="tabular-view-tab" role="tabpanel">
                            <h4>DC Permissions</h4>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

@endsection