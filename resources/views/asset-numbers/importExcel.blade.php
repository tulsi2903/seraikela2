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
                        <h4 class="card-title">{{$phrase->import_excel}}</h4>
                        <div class="card-tools">
                        <!-- <a href="{{url('asset-numbers')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
                        <a href="{{url('asset_Numbers/downloadFormatwithLocation')}}"><button style="float:right;" type="button" data-toggle="tooltip" title="{{$phrase->downloadFormatWithLocation}}" class="btn btn-primary" ><i class="fa fa-download"></i> {{$phrase->downloadFormatWithLocation}}</button></a>
                        <a href="{{url('asset_Numbers/downloadFormat')}}"><button style="float:right;margin-right: 1em;" type="button" data-toggle="tooltip" title="{{$phrase->downloadFormat}}" class="btn btn-warning" ><i class="fa fa-download"></i> {{$phrase->downloadFormat}}</button></a>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @if(Auth::user()->language == 1)
                        <h3>Import Guide For Resource Number</h3>
                        <p>
                            i. &nbsp;&nbsp;We can import recource number data using excel sheet. Here we are providing two types of upload sheet(Excel).
                            <br>
                            ii. &nbsp;<span style="color:#FFC107;">Download Format</span> button downloads an upload sheet format in which we can add a Resource.
                            <br>
                            iii. <span style="color: #00BCD4;">Download Format With Location</span> button downloads an upload sheet format in which we can add a Resource with, <br>&nbsp;&nbsp;&nbsp;&nbsp;<b>1.</b> Location of the Resource, <b>2.</b> Latitude, <b>3.</b> Longitude & <b>4.</b> No. of Sub-Resources.
                        </p>

                        <p>
                            <h5 style="color:red;">Note*</h5>
                            <span>For <span style="color: #00BCD4;">Download Format With Location</span></span><br>
                            &nbsp;&nbsp;<span>If we are adding main resource data then, "<b>Main Resource SNo</b>" & "<b>Count</b>" columns must be left empty.<br></span>
                            &nbsp;&nbsp;<span>If we are adding sub-resource data then, "<b>Location/Landmark</b>", "<b>Latitude</b>" & "<b>Longitude</b>" columns must be left empty.<br></span>
                            &nbsp;&nbsp;<span>If we are adding sub-resource data then, "<b>Main Resource SNo</b>" column will have main resource's sl. no. and "<b>Count</b>" column will have current value of Sub-Resources according to main resource's location.</span>
                        </p>
                        @else
                        <h3>संसाधन संख्या डेटा इम्पोर्ट के लिए मार्गदर्शन</h3>
                        <p>
                            i. &nbsp;&nbsp;हम एक्सेल शीट का उपयोग करके संसाधन संख्या डेटा को आयात कर सकते हैं| यहां हम दो प्रकार की अपलोड शीट प्रदान कर रहे हैं(एक्सेल)|
                            <br>
                            ii. &nbsp;<span style="color:#FFC107;">डाउनलोड फार्मेट</span> बटन एक अपलोड शीट प्रारूप डाउनलोड करता है जिसमें हम एक संसाधन जोड़ सकते हैं|
                            <br>
                            iii. <span style="color: #00BCD4;">डाउनलोड फार्मेट</span> बटन एक अपलोड शीट प्रारूप डाउनलोड करता है जिसमें हम एक संसाधन जोड़ सकते हैं जिसमे, <br>&nbsp;&nbsp;&nbsp;&nbsp;<b>1.</b> संसाधन का स्थान, <b>2.</b> Latitude, <b>3.</b> Longitude तथा <b>4.</b> उप-संसाधनों की संख्या|
                        </p>

                        <p>
                            <h5 style="color:red;">ध्यान दें*</h5>
                             <span style="color: #00BCD4;">डाउनलोड फार्मेट के लिये</span><br>
                            &nbsp;&nbsp;<span>- यदि हम मुख्य संसाधन डेटा जोड़ रहे हैं तो, "<b>मुख्य संसाधन क्रमांक</b>" तथा "<b>गिनती</b>" कॉलम खाली छोड़ना चाहिए|<br></span>
                            &nbsp;&nbsp;<span>- यदि हम उप-संसाधन डेटा जोड़ रहे हैं तो, "<b>स्थान/लैंडमार्क</b>", "<b>Latitude</b>" तथा "<b>Longitude</b>" कॉलम खाली छोड़ना चाहिए|<br></span>
                            &nbsp;&nbsp;<span>- यदि हम उप-संसाधन डेटा जोड़ रहे हैं तो, "<b>मुख्य संसाधन क्रमांक</b>" कॉलम में मुख्य संसाधन का क्रमांक होगा और "<b>गिनती</b>" कॉलम में मुख्य संसाधन के स्थान के अनुसार उप-संसाधन का वर्तमान मूल्य होगा|</span>
                        </p>

                        @endif
                        
                    <hr>
                </div>
                <div class="col-md-12">
                    <form class="row" action="{{url('asset-numbers/saveimporttoExcel')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group col-6">
                            
                            <label for="dept_name">{{$phrase->file_to_import}}<span style="color:red;margin-left:5px;">*</span></label>
                            @if(Auth::user()->language == 1)
                            <span>[Maximum no. of entries that can be imported at a time is 250]</span>
                            @else
                            <span>[अधिकतम 250 प्रविष्टियाँ एक बार में आयात की जा सकती हैं]</span>
                            @endif
                            <input type="file" name="excel_for_asset_number" id="excel_for_asset_number" class="form-control" required>
                        </div>
                        <div class="form-group col-6" style="margin-top: 35px;">
                            <button type="submit" class="btn btn-primary">{{$phrase->import}}</button>
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