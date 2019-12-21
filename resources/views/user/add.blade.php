@extends('layout.layout')
@section('title', 'Users')
<style>
</style>

@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border-top: 3px solid #5c76b7;">
                <div class="card-header">
                    <div class="card-title" style="float:left;"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;User Detail</div>
                    <div id="toggle1">
                        <div  style="float:right;"><button class="btn btn-secondary"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;Add Users</button></div>
                    </div>
                </div>
                <!-----------------------------------------start of User Form------------------------------------------>
                <div id="show-toggle1">
                    <div class="row">
                        <div class="col-md-12"><br>
                            <div class="card" style="min-height: unset !important;border-top: 1px solid #5c76b7;">
                            <form action="{{url('user/store')}}" method="POST">
                            @csrf      
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="title">Title<span style="color:red;">*</span></label>
                                                <select name="title" id="title" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="Mr">Mr.</option>
                                                    <option value="Mrs">Mrs.</option>
                                                    <!-- <option value="Shri">Shri.</option>
                                                    <option value="Smt">Smt.</option> -->
                                                </select>
                                                <div class="invalid-feedback" id="title_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="first_name">First Name<span style="color:red;">*</span></label>
                                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" maxlength="20">
                                                <div class="invalid-feedback" id="first_name_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="middle_name">Middle Name</label>
                                                <input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="Middle Name" maxlength="20">
                                                <div class="invalid-feedback" id="middle_name_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" maxlength="20">
                                                <div class="invalid-feedback" id="last_name_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="org_id">Organisation<span style="color:red;">*</span></label>
                                                <select name="org_id" id="org_id" class="form-control form-control">
                                                    <option value="">--Select--</option>
                                                    @foreach($organization_data as $organization)
                                                        <option value="{{$organization->org_id}}">{{$organization->org_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="org_id_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="desig_id">Designation<span style="color:red;">*</span></label>
                                                <select name="desig_id" id="desig_id" class="form-control form-control">
                                                    <option value="">--Select--</option>
                                                    @foreach($designation_data as $designation)
                                                        <option value="{{$designation->desig_id}}">{{$designation->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="desig_id_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="text" name="start_date" id="start_date" class="form-control start_date_end_date_datepicker" placeholder="dd/mm/yyyy" autocomplete="off">
                                                <div class="invalid-feedback" id="start_date_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="end_date">End date</label>
                                                <input type="text" name="end_date" id="end_date" class="form-control start_date_end_date_datepicker" placeholder="dd/mm/yyyy" autocomplete="off">
                                                <div class="invalid-feedback" id="end_date_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email Id.<font style="color:red;">*</font></label>
                                                <input type="text" name="email" id="email" class="form-control" placeholder="example@example.com" maxlength="50">
                                                <div class="invalid-feedback" id="email_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="username">Username<span style="color:red;">*</span></label>
                                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" maxlength="20">
                                                <div class="invalid-feedback" id="username_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="password">Password<span style="color:red;">*</span></label>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" maxlength="15">
                                                <div class="invalid-feedback" id="password_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="confirm_password">Confirm Password<span style="color:red;">*</span></label>
                                                <input type="text" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-type Password" maxlength="15">
                                                <div class="invalid-feedback" id="confirm_password_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mobile">Mobile No.</label>
                                                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="10 digit number only" maxlength="10">
                                                <div class="invalid-feedback" id="mobile_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" name="address" id="address" class="form-control" placeholder="Address" maxlength="100">
                                                <div class="invalid-feedback" id="address_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status<font style="color:red;">*</font></label>
                                                <select name="status" id="status" class="form-control form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <div class="invalid-feedback" id="status_error_msg"></div class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-action">
                                        <button type="submit" onclick="return submitForm()" class="btn btn-secondary">Submit</button>
                                    </div>
                                </div>
                            </form>    
                            </div>
                        </div>
                    </div>
                </div>
                <!-----------------------------------------end of User Form------------------------------------------>

                <!-----------------------------------------start of table------------------------------------------>
                <div class="card-body">
                    <table class="table-datatable display table table-striped table-hover" >
                        <thead style="background: #d6dcff;color: #000;">
                            <tr>
                                <th>Name</th>
                                <th>Email Id</th>
                                <th>Username</th>
                                <th>Designation</th>
                                <th>Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($results as $key => $val)
                            <tr>
                                <td>{{$val->first_name}}</td>
                                <td>{{$val->email}}</td>
                                <td>{{$val->username}}</td>
                                <td>{{$val->desig_name}}</td>
                                <td>{{$val->address}}</td>
                                <td>
                                    @if($val->status==1)
                                    <span style="padding:5px 10px; border: 2px solid #00b100;">Active</span>
                                    @else
                                    <span style="padding:5px 10px; border: 2px solid #ff1c1c;">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-----------------------------------------end of table------------------------------------------>
            </div>
        </div>
    </div>

    <script>
        // Date Picker
        $(document).ready(function(){
            $('.start_date_end_date_datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
    </script>

    <script>
        // validations starts
        var title_error = true;
        var first_name_error = true;
        var middle_name_error = true;
        var last_name_error = true;

        var org_id_error = true;
        var desig_id_error = true;
        var start_date_error = true;
        var end_date_error = true;

        var email_error = true;
        var username_error = true;
        var password_error = true;
        var confirm_password_error = true;

        var mobile_error = true;
        var address_error = true;
        var status_error = true;

        $(document).ready(function(){
            $("#title").change(function(){
                title_validate();
            });
            $("#first_name").change(function(){
                first_name_validate();
            });
            $("#middle_name").change(function(){
                middle_name_validate();
            });
            $("#last_name").change(function(){
                last_name_validate();
            });

            $("#org_id").change(function(){
                org_id_validate();
            });
            $("#desig_id").change(function(){
                desig_id_validate();
            });
            $("#start_date").change(function(){
                start_date_validate();
            });
            $("#end_date").change(function(){
                start_date_validate();
            });

            $("#email").change(function(){
                email_validate();
            });
            $("#username").change(function(){
                username_validate();
            });
            $("#password").change(function(){
                password_validate();
            });
            $("#confirm_password").change(function(){
                confirm_password_validate();
            });

            $("#mobile").change(function(){
                mobile_validate();
            });
            $("#mobile").keyup(function(){ // replacing no numbers
                $("#mobile").val($("#mobile").val().replace(/[^0-9]+/g, ""));
            });

            $("#address").change(function(){
                address_validate();
            });
            $("#status").change(function(){
                status_validate();
            });
        });

        function title_validate(){
            var title_val = $("#title").val();
            if(title_val){
                title_error = false;
                $("#title").removeClass("is-invalid");
            }
            else{
                title_error = true;
                $("#title").addClass("is-invalid");
                $("#title_error_msg").html("Please select title");
            }
        }

        function first_name_validate(){
            var first_name_val = $("#first_name").val();
            var regEx = new RegExp('^[a-zA-Z]+$');
            if(first_name_val==""){
                first_name_error = true;
                $("#first_name").addClass("is-invalid");
                $("#first_name_error_msg").html("Please enter first name");
            }
            else if(!regEx.test(first_name_val)){
                first_name_error = true;
                $("#first_name").addClass("is-invalid");
                $("#first_name_error_msg").html("Please enter valid name");
            }
            else{
                first_name_error = false;
                $("#first_name").removeClass("is-invalid");
            }
        }

        function middle_name_validate(){
            var middle_name_val = $("#middle_name").val();
            var regEx = new RegExp('^[a-zA-Z]+$');
            if(middle_name_val!=""){
                if(!regEx.test(middle_name_val)){
                    middle_name_error = true;
                    $("#middle_name").addClass("is-invalid");
                    $("#middle_name_error_msg").html("Please enter valid name");
                }
                else{
                    middle_name_error = false;
                    $("#middle_name").removeClass("is-invalid");
                }
            }
            else{
                middle_name_error = false;
                $("#middle_name").removeClass("is-invalid");
            }
        }

        function last_name_validate(){
            var last_name_val = $("#last_name").val();
            var regEx = new RegExp('^[a-zA-Z]+$');
            if(last_name_val!=""){
                if(!regEx.test(last_name_val)){
                    last_name_error = true;
                    $("#last_name").addClass("is-invalid");
                    $("#last_name_error_msg").html("Please enter valid name");
                }
                else{
                    last_name_error = false;
                    $("#middle_name").removeClass("is-invalid");
                }
            }
            else{
                last_name_error = false;
                $("#last_name").removeClass("is-invalid");
            }
        }

        function org_id_validate(){
            var org_id_val = $("#org_id").val();
            if(org_id_val){
                org_id_error = false;
                $("#org_id").removeClass("is-invalid");
            }
            else{
                org_id_error = true;
                $("#org_id").addClass("is-invalid");
                $("#org_id_error_msg").html("Please select organisation");
            }
        }

        function desig_id_validate(){
            var desig_id_val = $("#desig_id").val();
            if(desig_id_val){
                desig_id_error = false;
                $("#desig_id").removeClass("is-invalid");
            }
            else{
                desig_id_error = true;
                $("#desig_id").addClass("is-invalid");
                $("#desig_id_error_msg").html("Please select organisation");
            }
        }

        function start_date_validate(){
            start_date_error = false;
        }  
        
        function end_date_validate(){
            end_date_error = false;
        }

        function email_validate(){
            var email_val = $("#email").val();
            var regEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(email_val==""){
                email_error = true;
                $("#email").addClass("is-invalid");
                $("#email_error_msg").html("Please enter your email ID");
            }
            else if(!regEx.test(email_val)){
                email_error = true;
                $("#email").addClass("is-invalid");
                $("#email_error_msg").html("Please enter valid email ID");
            }
            else{
                email_error = false;
                $("#email").removeClass("is-invalid");
            }
        }

        function username_validate(){
            var username_val = $("#username").val();
            var regEx = new RegExp('^[a-zA-Z0-9_-]+$');
            if(username_val==""){
                username_error = true;
                $("#username").addClass("is-invalid");
                $("#username_error_msg").html("Please enter username");
            }
            else if(!regEx.test(username_val)){
                username_error = true;
                $("#username").addClass("is-invalid");
                $("#username_error_msg").html("Only alphabets, numbers and characters (_-) allowed");
            }
            else{
                username_error = false;
                $("#username").removeClass("is-invalid");
            }
        }

        function password_validate(){
            var password_val = $("#password").val();
            var regEx = new RegExp('^[a-zA-Z0-9_-]+$');
            if(password_val==""){
                password_error = true;
                $("#password").addClass("is-invalid");
                $("#password_error_msg").html("Please enter password");
            }
            else if(!regEx.test(password_val)){
                password_error = true;
                $("#password").addClass("is-invalid");
                $("#password_error_msg").html("Only alphabets, numbers and characters (_-) allowed");
            }
            else if(password_val.length<4){
                password_error = true;
                $("#password").addClass("is-invalid");
                $("#password_error_msg").html("Password should be greater than 4 digit");
            }
            else{
                password_error = false;
                $("#password").removeClass("is-invalid");
            }
        }
        
        
        function confirm_password_validate(){
            var confirm_password_val = $("#confirm_password").val();
            var regEx = new RegExp('^[a-zA-Z0-9_-]+$');
            if(confirm_password_val==""){
                confirm_password_error = true;
                $("#confirm_password").addClass("is-invalid");
                $("#confirm_password_error_msg").html("Please re-type password");
            }
            else if(!regEx.test(confirm_password_val)){
                confirm_password_error = true;
                $("#confirm_password").addClass("is-invalid");
                $("#confirm_password_error_msg").html("Only alphabets, numbers and characters (_-) allowed");
            }
            else if(confirm_password_val.length<4){
                confirm_password_error = true;
                $("#confirm_password").addClass("is-invalid");
                $("#confirm_password_error_msg").html("Password should be greater than 4 digit");
            }
            else{
                if($("#password").val())
                {
                    if($("#confirm_password").val()==$("#password").val()){
                        confirm_password_error = false;
                        $("#confirm_password").removeClass("is-invalid");
                    }
                    else{
                        confirm_password_error = true;
                        $("#confirm_password").addClass("is-invalid");
                        $("#confirm_password_error_msg").html("Password did not matched");
                    }
                }
                else{
                    confirm_password_error = false;
                    $("#confirm_password").removeClass("is-invalid");
                }
            }
        }

        function mobile_validate(){
            var mobile_val = $("#mobile").val();
            var regEx = new RegExp('^[0-9]+$');
            if(mobile_val){
                if(!regEx.test(mobile_val)){
                    mobile_error = true;
                    $("#mobile").addClass("is-invalid");
                    $("#mobile_error_msg").html("Please enter valid mobile no");
                }
                else if(mobile_val.length!=10){
                    mobile_error = true;
                    $("#mobile").addClass("is-invalid");
                    $("#mobile_error_msg").html("Please enter 10 digit no only");
                }
                else{
                    mobile_error = false;
                    $("#mobile").removeClass("is-invalid");
                }
            }
            else{
                mobile_error = false;
                $("#mobile").removeClass("is-invalid");
            }
        }

        function address_validate(){
            address_error = false;
        }

        function status_validate(){
            var status_val = $("#status").val();
            if(status_val){
                status_error = false;
                $("#status").removeClass("is-invalid");
            }
            else{
                status_error = true;
                $("#status").addClass("is-invalid");
                $("#status_error_msg").html("Please select status");
            }
        }

        // final submit
        function submitForm(){
            title_validate();
            first_name_validate();
            middle_name_validate();
            last_name_validate();

            org_id_validate();
            desig_id_validate();
            start_date_validate()
            end_date_validate();

            email_validate();
            username_validate();
            password_validate();
            confirm_password_validate();

            mobile_validate();
            address_validate();
            status_validate();

            if(title_error||first_name_error||middle_name_error||last_name_error||org_id_error||desig_id_error||start_date_error||end_date_error||email_error||username_error||password_error||confirm_password_error||mobile_error||confirm_password_error||mobile_error||address_error||status_error){
                console.log(title_error+" "+first_name_error+" "+middle_name_error+" "+last_name_error+" "+org_id_error+" "+desig_id_error+" "+start_date_error+" "+end_date_error+" "+email_error+" "+username_error+" "+password_error+" "+confirm_password_error+" "+mobile_error+" "+address_error+" "+status_error);
                console.log(password_error);
                return false;
            }
            else{
                return true;
            }
        }
    </script>


</div>
@endsection