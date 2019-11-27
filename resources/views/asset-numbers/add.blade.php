@extends('layout.layout')

@section('title', 'Asset Numbers')

@section('page-content')
  <div class="row row-card-no-pd" style="border-top: 3px solid #5c76b7;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right" style="background:#fff;">
                        <h4 class="card-title">Asset Numbers</h4>
                        <div class="card-tools">
                        <a href="{{url('asset-numbers')}}" class="btn btn-sm btn-secondary" style="float:right;"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{url('asset-numbers/store')}}" method="POST">
                    @csrf
                           <div class="form-group">
                                <label for="year">Year<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($years as $year)
                                     <option value="{{ $year->year_id}}" <?php if($data->year==$year->year_id){ echo "selected"; } ?>>{{ $year->year_value}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="year_error_msg"></div>

                            </div>
                            <div class="form-group">
                                <label for="asset_id">Asset<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="asset_id" id="asset_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach( $assets as $asset )
                                     <option value="{{ $asset->asset_id }}" <?php if($data->asset_id==$asset->asset_id){ echo "selected"; } ?>>{{ $asset->asset_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="asset_id_error_msg"></div>

                            </div>
                             <div class="form-group">
                                <label for="geo_id">Panchayat<span style="color:red;margin-left:5px;">*</span></label>
                                <select name="geo_id" id="geo_id" class="form-control">
                                    <option value="">---Select---</option>
                                    @foreach($panchayats as $panchayat)
                                     <option value="{{ $panchayat->geo_id }}" <?php if($data->geo_id==$panchayat->geo_id){ echo "selected"; } ?>>{{ $panchayat->geo_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="geo_id_error_msg"></div>

                            </div>
                            <!-- <div class="form-group next-button">
                                <button type="button" class="btn btn-primary btn-secondary" onclick="next()" id="btn_hide">Next&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></button>
                            </div> -->
                           
                            <div class="form-group" id="previous_value_hide" style="display:none;">
                                <label for="previous_value">Previous Value</label>
                                <input type="text" name="previous_value" id="previous_value" class="form-control" readonly>
                            </div>
                            <div class="form-group" id="current_value_hide" style="display:none;">
                                <label for="current_value">Current Value</label>
                                <input type="text" name="current_value" id="current_value"  value="{{$data->current_value}}" class="form-control">
                                <div class="invalid-feedback" id="current_value_error_msg"></div>
                            </div>
                           
                            <div class="form-group" style="display:none;" id="append-location">
                                <label>Asset Locations</label>
                                 <div class="table-responsive table-hover table-sales">
                                 <table class="table">
                                <thead style="background: #d6dcff;color: #000;">
                                        <tr>
                                            <th>#</th>
                                            <th>Location/Landmark</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                        </tr>
                                    </thead>
                                    <tbody id="append-location-delete">

                                    </tbody>
                                    <tbody id="append-location-new">

                                    </tbody>
                                </table>
                            </div>
                                <div class="invalid-feedback" id="asset_location_error_msg"></div>
                                <!-- longitude / latitude form -->
                            </div>
                            
                            <div class="form-group" id="submit-buttons">
                                <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                                <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                                <button type="submit" class="btn btn-primary" onclick=" return submitSave()"><span id="save-button-text">Next</span>&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>
<script>
    /* validation starts */
    // error variables as true = error occured
    var year_error = true;
    var asset_id_error = true;
    var geo_id_error = true;

    $(document).ready(function(){
        $("#year").change(function(){
            year_validate();
            resetAll();
        });
        $("#asset_id").change(function(){
            asset_id_validate();
            resetAll();
        });
         $("#geo_id").change(function(){
            geo_id_validate();
            resetAll();
        });
         
    });

 //year validation
    function year_validate(){
        var year_val = $("#year").val();
       
        if(year_val==""){
            year_error=true;
            $("#year").addClass('is-invalid');
            $("#year_error_msg").html("Year should not be blank");
        }
        
        else{
            year_error=false;
            $("#year").removeClass('is-invalid');
        }
    }

    //asset_id validation
    function asset_id_validate(){
        var asset_id_val = $("#asset_id").val();
       
        if(asset_id_val==""){
            asset_id_error=true;
            $("#asset_id").addClass('is-invalid');
            $("#asset_id_error_msg").html("Asset Name should not be blank");
        }
       
        else{
            asset_id_error=false;
            $("#asset_id").removeClass('is-invalid');
        }
    }
    
    //geo_id validation
    function geo_id_validate(){
        var geo_id_val = $("#geo_id").val();
       
        if(geo_id_val==""){
            geo_id_error=true;
            $("#geo_id").addClass('is-invalid');
            $("#geo_id_error_msg").html("Panchayat should not be blank");
        }
       
        else{
            geo_id_error=false;
            $("#geo_id").removeClass('is-invalid');
           
        }
    }
</script>

<script>
    var movable = "no";
    var asset_location = [];
    var step = 1; // 1 = initial inputs, 2 = after ajax call (pre current assigned), 3 = after location form load

    function ajaxFunc(){
        var year_tmp = $("#year").val();
        var asset_id_tmp = $("#asset_id").val();
        var geo_id_tmp = $("#geo_id").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{url('asset-numbers/current_value')}}",
            data: {'year': year_tmp, 'asset_id': asset_id_tmp, 'geo_id': geo_id_tmp},
            method: "GET",
            contentType: 'application/json',
            dataType: "json",
            beforeSend: function(data){
                
            },
            error: function(xhr){
                alert("error"+xhr.status+", "+xhr.statusText);
            },
            success: function (data) {
                console.log(data);

                $("#previous_value").val('0');
                $("#current_value").val('0');
                movable = "no";
                asset_location = []
                step = 2;

                // assign movable = yes/no
                if(data.movable == "no")
                {
                    movable = data.movable;
                }
                else{
                    movable = "yes";
                }
                // assign previous value
                if(data.current_value){
                    $("#previous_value").val(data.current_value);
                }
                // asset_location (previous entries to delete by user when current is less than pre)
                if(data.asset_location){
                    asset_location = data.asset_location;
                    appendLocation();
                }
                // show previous/current value inputs
                $("#previous_value_hide").show(300);
                $("#current_value_hide").show(300);
            }
        });    
    }
</script>

<script>
    var loc_details=`<td><input type="text" name="location_name[]" id="location_name" class="form-control"></td><td><input type="text" name="latitude[]" id="latitude" class="form-control"></td><td><input type="text" name="longitude[]" id="longitude" class="form-control"></td>`;
   

    $(document).ready(function(){
        // detecting change in current value
        $("#current_value").change(function(){
            appendLocation();
        });
    });

    function appendLocation()
    {
        $("#append-location tbody").html("");
        step = 3; // assigning steps
        $("#save-button-text").html("Save");
        var diff  = parseFloat(parseFloat($("#current_value").val()) - parseFloat($("#previous_value").val()));
        if(movable == "no"){
            // show location inputs
            if(diff > 0){
                for(var i=0;i<diff;i++){
                    $("#append-location #append-location-new").append("<tr><td>"+(i+1)+"</td><td><input type='text' name='location_name[]' class='form-control'></td><td><input type='text' name='latitude[]' class='form-control'></td><td><input type='text' name='longitude[]' class='form-control'></td></tr>");
                }
                $("#append-location #append-location-new").show(300);
            }
            else{
                $("#append-location #append-location-new").hide(300);
            }

            // show previous locations
            if(asset_location.length > 0){
                // to show previous location for delete
                for(var i=0;i<asset_location.length;i++){
                    $("#append-location #append-location-delete").append("<tr><td><input type='checkbox' name='delete_asset_geo_loc_id[]' value='"+asset_location[i].asset_geo_loc_id+"'></td><td>"+asset_location[i].location_name+"</td><td>"+asset_location[i].latitude+"</td><td>"+asset_location[i].longitude+"</td></tr>");
                }
                $("#append-location #append-location-delete").show(300);
            }
            else{
                $("#append-location #append-location-delete").show(300);
            }
            // defining first row
            if(diff < 0 && $("#current_value").val()!='0'){
                $("#append-location #append-location-delete").prepend("<tr><td colspan='4'><center>Select any <b>"+(Math.abs(diff))+"</b> location(s) to delete</center></td>");
            }
            else{
                if(asset_location.length > 0){ $("#append-location #append-location-delete").prepend("<tr><td colspan='4'><center>Previous GPS locations</center></td>"); }
                else{ $("#append-location #append-location-delete").prepend("<tr><td colspan='4'><center>No Previous GPS found</center></td>");  }
            }

            // show entire table
            $("#append-location").show(300);
        }
        else{
            $("#append-location tbody").html(""); 
        }

        $("#submit-buttons").show(300);
    }        
</script>

<script>
    // final submit
    function submitSave(){
        if(step==1){ // next
            year_validate();
            asset_id_validate();
            geo_id_validate();
            $("#loc").show();
            if(year_error||asset_id_error||geo_id_error){
                
            }
            else{
                ajaxFunc();
            }
            return false;
        }
        else if(step==2){
            return false;
        }
        else{ // step == 3
            error = true ;
            // validate location_name (if diff>)

            // validate no of location selected for delete (else diff<)
            var diff  = parseFloat(parseFloat($("#current_value").val()) - parseFloat($("#previous_value").val()));
            if(diff>0)
            {
                var location_name = document.getElementsByName('location_name[]');
                var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
                var tmp = false; // error
                for (var i = 0; i < location_name.length; i++) {
                    if(location_name[i].value==""){
                        tmp=true;
                    }
                }

                if(tmp){
                    error = true ;
                    $("#asset_location_error_msg").html("Please enter landmark/location");
                    $("#asset_location_error_msg").show();
                }
                else{
                    error = false;
                    $("#asset_location_error_msg").hide();
                }
            }//closing of if
            if(diff < 0 && $("#current_value").val()!='0'){
                var delete_asset_geo_loc_id = document.getElementsByName('delete_asset_geo_loc_id[]');
                var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
                var tmp = false; // error
                var count = 0; // no of selected
                for (var i = 0; i < delete_asset_geo_loc_id.length; i++) {
                    if(delete_asset_geo_loc_id[i].checked){
                        count++;
                    }
                }

                if(Math.abs(diff)!=count){
                    tmp = true;
                }

                if(tmp){
                    error = true ;
                    $("#asset_location_error_msg").html("Please select "+Math.abs(diff)+" location(s) you want to delete");
                    $("#asset_location_error_msg").show();
                }
                else{
                    error = false;
                    $("#asset_location_error_msg").hide();
                }
            }

            // final return
            if(error){
                return false;
            }
            else{
                return true;
            }
        }//closing of else
    }

    // to reset everything if geo_id, year or asset_id changes
    /* for step = 1  i.e. (if step == 2)
    current value = null
    previous_value_hide  = hide
    current_value_hide = hide
    append_location = hide
    save-button-text = next
    */
    function resetAll(){
        if(step==2 || step==3){
            $("#previous_value_hide").hide(300);
            $("#current_value_hide").hide(300);
            $("#append-location").hide(300);
            $("#save-button-text").html("Next");
            $("#current_value").val("");
            step = 1;
        }
    }
</script>


<script>
    // for edit initialization
    $(document).ready(function(){
        var purpose = $("#hidden_input_purpose").val();

        if(purpose=="edit"){
            submitSave();
        }
    });
</script>

@endsection