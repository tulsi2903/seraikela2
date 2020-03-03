@extends('layout.layout')

@section('title', 'Import Scheme Work data')

@section('page-style')
    <style>
    </style>
@endsection

@section('page-content')
<div class="card">
    <div class="card-header">
        <div class="card-head-row card-tools-still-right" style="background:#fff;">
            @if(Auth::user()->language == 1)
            <h4 class="card-title">Import Scheme Work Data</h4>
            @else
            <h4 class="card-title">योजना इम्पोर्ट</h4>
            @endif
            <div class="card-tools">
                <!-- <a href="{{url('scheme-geo-target')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a> -->
                <!-- <a href="{{url('scheme-performance/download_error_log')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-download"></i>&nbsp;&nbsp;Download Errorlog</a> -->
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card-body">

            <form action="{{url('scheme-performance/importtoExcel')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-6">
                        @if(Auth::user()->language == 1)
                        <h3 style="color:#00ace6;">Guide To Import Scheme Work Data</h3>
                        <p>
                            -  Select Scheme from scheme dropdown.
                            <br>
                            -  Click on Download Template button to download the template. Please note, every scheme has a different template.
                            <br>
                            -  Enter the data to be imported in the given excel format and save. Make sure, the excel is saved properly.
                            <br>
                            -  Choose the saved excel file from File to import section and click on Import button.
                        </p>
                        @else
                        <h3 style="color:#00ace6;">योजनाओं का डेटा इम्पोर्ट के लिए मार्गदर्शन</h3>
                        <p>
                            &nbsp;&nbsp;&nbsp;&nbsp;-  स्कीम ड्रॉपडाउन से स्कीम का चयन करें|
                            <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;-  टेम्पलेट डाउनलोड करने के लिए डाउनलोड Template बटन पर क्लिक करें| कृपया ध्यान दें, हर योजना का एक अलग टेम्पलेट होता है|
                            <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;-  दिए गए एक्सेल प्रारूप में इम्पोर्ट किए जाने वाले डेटा दर्ज करें और सहेजें| सुनिश्चित करें, एक्सेल ठीक से सहेजा गया है|
                            <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;-  इम्पोर्ट अनुभाग में फ़ाइल से सहेजी गई एक्सेल फ़ाइल चुनें और इम्पोर्ट बटन पर क्लिक करें|
                        </p>
                        @endif
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-4 col-7">
                        <div class="form-group">
                            <label for="scheme_id">{{$phrase->scheme}}<span style="color:red;margin-left:5px;">*</span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control">
                                <option value="">--Select--</option>
                                @foreach($scheme_datas as $scheme )
                                <option value="{{ $scheme->scheme_id }}">({{$scheme->scheme_short_name}}) {{ $scheme->scheme_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="scheme_id_error_msg"></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-5">
                        <div style="height: 30px;"></div>
                        <button  class="btn btn-primary"  type="button" onclick="download_format()" style="background: #349601; color: white;" title="Download Excel Format"><i class="fas fa-file-import"></i>&nbsp;&nbsp;{{$phrase->downloadFormat}}</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="import_section"  style="display:none; border: 1px solid rgb(189, 189, 189); border-radius: 5px;padding: 15px; background: white; margin: 15px 0 15px 0;" id="import_seGction">
                            <label for="dept_name">{{$phrase->import}}<span style="color:red;margin-left:5px;">*</span></label>
                            @if(Auth::user()->language == 1)
                            <span>[Maximum no. of entries that can be imported at a time is 250]</span>
                            @else
                            <span>[अधिकतम 250 डेटा को एक बार में import किया जा सकता है|]</span>
                            @endif
                            <div class="input-group mb-3">
                                <input type="file" name="excelcsv" id="excelcsv" class="form-control" required>
                                <div class="input-group-append">
                                  <button type="submit" class="btn btn-primary" onclick="return import_submit();"><i class="fas fa-check"></i>&nbsp;{{$phrase->import}}</button>
                                </div>
                                <div class="invalid-feedback" id="excelcsv_error_msg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-12">
                        @if(session()->get('to-download') == "yes")
                            <?php session()->forget('to-download'); ?>
                            <div style="border: 1px solid rgb(128, 128, 128); border-radius: 5px;padding: 15px; background: linear-gradient(to top, #a5baef, #ffffff 70%, #ffffff, #ffffff 100%); margin: 15px 0 15px 0;">
                                <table class="table table-datatable" id="printable-area">
                                    <tr>
                                        <th colspan="2" style="color: #147785;text-align: center;margin-bottom: -24px;">Import Summary</th>
                                    </tr>
                                    <tr>
                                        <td>DATE</td>
                                        <td>{{session()->get('currentdate')}}</td>
                                    </tr>
                                    <tr>
                                        <td>SCHEME NAME</td>
                                        <td>{{session()->get('scheme_name')}}</td>
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
                                </table>
                                <a href="{{url('scheme-performance/download_error_log')}}" class="btn btn-sm btn-secondary"><i class="fas fa-download"></i>&nbsp;&nbsp;Download Error-Log</a>
                            </div>

                            <script>
                                // to initially select scheme, and show excel file input form after a error shows
                                $(document).ready(function(){
                                    setTimeout(function(){
                                        var scheme_id_tmp = {{session()->get('scheme_id')}};
                                        $("#scheme_id").val(scheme_id_tmp);
                                        show_hide_form_element();
                                    }, 500);
                                });
                            </script>
                        @endif
                    </div>
                </div>

            </form>

        </div>
    </div>
</div><!--end of card-->
<script>
    
    // defining error = true as default
    var scheme_id_error = true;
    var excelcsv_error = true;
    
    
    $(document).ready(function () {
        $("#scheme_id").change(function () {
            show_hide_form_element();
            scheme_id_validate();
        });
        $("#excelcsv").change(function () {
            excelcsv_validate();
        });
    });
    
    function scheme_id_validate(){
        var scheme_id_val = $("#scheme_id").val();
        if (scheme_id_val == "") {
            scheme_id_error = true;
            $("#scheme_id").addClass('is-invalid');
            $("#scheme_id_error_msg").html("Please select a scheme");
        }
        else {
            scheme_id_error = false;
            $("#scheme_id").removeClass('is-invalid');
        }
    }

    function excelcsv_validate(){
        var excelcsv_val = $("#excelcsv").val();
        var ext = excelcsv_val.substring(excelcsv_val.lastIndexOf('.') + 1).toLowerCase();
        if (ext) // if selected
        {
            if (ext != "xls" && ext != "xlsx") {
                excelcsv_error = true;
                $("#excelcsv").addClass('is-invalid');
                $("#excelcsv_error_msg").html("Please select xls/xlsx (excel file) only");
            } else {
                excelcsv_error = false;
                $("#mapmarkericon").removeClass('is-invalid');
            }
        } 
        else {
            excelcsv_error = false;
            $("#excelcsv").addClass('is-invalid');
            $("#excelcsv_error_msg").html("Please select an excel file to import");
        }
    }
</script>
<script>
    function download_format(){
        scheme_id_validate();
        if(!scheme_id_error){
            scheme_id_tmp = $("#scheme_id").val();
            location.href='{{url("")}}/scheme-performance/downloadFormat?scheme_id='+scheme_id_tmp;
        }
    }

    function show_hide_form_element() {
        var scheme_id_tmp = $("#scheme_id").val();
        if (scheme_id_tmp != "") {
            $(".import_section").fadeIn(300);
        }
        else{
            $(".import_section").fadeOut(50);
        }
    }

    function import_submit(){
        scheme_id_validate();
        excelcsv_validate();

        if(scheme_id_error || excelcsv_error){
            return false;
        }
        else{
            return true;
        }
    }
</script>
@endsection