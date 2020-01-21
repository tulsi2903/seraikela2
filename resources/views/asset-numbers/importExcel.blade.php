@extends('layout.layout')

@section('title', 'Resource Number')

@section('page-style')
    <style>
        
    </style>
@endsection

@section('page-content')
<div class="card">
        <div class="col-md-12">
            <!-- <div class="card"> -->
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Import From Excel</h4>
                        <div class="card-tools">
                        <!-- <a href="{{url('asset-numbers')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
                        <a href="{{url('asset_Numbers/downloadFormatwithLocation')}}" data-toggle="tooltip" title="Download Location Excel Format"><button style="float:right;" type="button" class="btn btn-primary" ><i class="fa fa-download"></i> Download Format With Location</button></a>
                        <a href="{{url('asset_Numbers/downloadFormat')}}" data-toggle="tooltip" title="Download Excel Format"><button style="float:right;margin-right: 1em;" type="button" class="btn btn-warning" ><i class="fa fa-download"></i> Download Format</button></a>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                        <h3>Import Guide For Resource Number</h3>
                        <p>
                            i. &nbsp;&nbsp;We can import recource number data using excel sheet. here we are Providing two types of upload sheet(Excel).
                            <br>
                            ii. &nbsp;<span style="color:#FFC107;">Download Format</span> button downloads a upload sheet format in which we can add a Resource.
                            <br>
                            iii. <span style="color: #00BCD4;">Download Format With Location</span> button downloads a upload sheet format in which we can add a Resource with, <br>&nbsp;&nbsp;&nbsp;&nbsp;<b>1.</b> location of the Resource, <b>2.</b> Lattitude, <b>3.</b> Longitude & <b>4.</b> No. of Sub-Resources.
                        </p>

                        <p>
                            <h5 style="color:#704d2f;">Note*</h5>
                            <span>For <span style="color: #00BCD4;">Download Format With Location</span></span><br>
                            &nbsp;&nbsp;<span>If we are adding main resource data then, "<b>Main Resource SNo</b>" & "<b>Count</b>" columns must be left empty.<br></span>
                            &nbsp;&nbsp;<span>If we are adding sub-resource data then, "<b>Location/Landmark</b>", "<b>Latitude</b>" & "<b>Longitude</b>" columns must be left empty.<br></span>
                            &nbsp;&nbsp;<span>If we are adding sub-resource data then, "<b>Main Resource SNo</b>" column will have main resource's sl. no. and "<b>Count</b>" column will have current value of Sub-Resources according to main resource's location.</span>
                        </p>
                        
                    <hr>
                </div>
                <div class="col-md-12">
                    <form class="row" action="{{url('asset-numbers/saveimporttoExcel')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group col-6">
                            
                            <label for="dept_name">File to import<span style="color:red;margin-left:5px;">*</span></label>
                            <span>[Maximum no. of entries that can be imported at a time is 250]</span>
                            <input type="file" name="excel_for_asset_number" id="excel_for_asset_number" class="form-control" required>
                        </div>
                        <div class="form-group col-6" style="margin-top: 35px;">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                    <hr>
                </div>
                <div class="col-md-12">
                    @if(session()->get('to-download') == "yes")
                        <?php session()->forget('to-download'); ?>
                        <h4 style="color: #147785;text-align: left;margin-bottom: -24px;">Import Summary</h4>
                        <hr style="margin-top: 2rem;">
                        <table class="table table-datatable" id="printable-area">
                            <!-- <tr>
                                <th colspan="2" style="text-align:center">District Resource and Scheme Management</th>
                            </tr> -->
                            <tr>
                                <td>Date</td>
                                <td>{{session()->get('currentdate')}}</td>
                            </tr>
                            <tr>
                                <td>TOTAL RECORD COUNT</td>
                                <td>{{session()->get('totalCount')}}</td>
                            </tr>
                            <tr>
                                <td>TOTAL SUCCESS COUNT</td>
                                <td>{{session()->get('totalsuccess')}}</td>
                            </tr>
                            <tr>
                                <td>TOTAL FAIL COUNT</td>
                                <td>{{session()->get('totalfail')}}</td>
                            </tr>
                            <!-- <tr>
                                <td>USER NAME</td>
                                <td>{{session()->get('user_full_name')}}</td>
                            </tr> -->
                        </table>
                        <a href="{{url('asset-numbers/error_log_download')}}" class="btn btn-sm btn-secondary"><i class="fas fa-download"></i>&nbsp;&nbsp;Download Import Summary</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection