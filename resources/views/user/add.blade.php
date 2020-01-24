@extends('layout.layout')

@section('title', 'Users')

@section('page-style')
<style>
</style>
@endsection

@section('page-content')

<?php $desig_permissions = session()->get('desig_permission'); // assigning desig_permission so we can use 
?>
<div class="row">
    <div class="col-md-12">
        <div class="card" style="border-top: 3px solid #5c76b7;">
            <div class="card-header">
                <div class="card-title" style="float:left;"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;{{$phrase->user_details}}</div>
                @if(@$desig_permissions["mod1"]["add"])
                <div id="toggle1">
                    <div style="float:right;margin-bottom: 1em;"><button class="btn btn-secondary" onclick="resetUserForm()"><span class="btn-label"><i class="fa fa-plus"></i></span>&nbsp;{{$phrase->add_users}}</button></div>
                </div>
                @endif
            </div>
            <!-----------------------------------------start of User Form------------------------------------------>
            <div id="show-toggle1">
                <div class="row">
                    <div class="col-md-12"><br>
                        <div class="card" style="min-height: unset !important;border-top: 1px solid #5c76b7;">
                            <form action="{{url('user/store')}}" method="POST" id="user-form" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="title">{{$phrase->title}}<span style="color:red;">*</span></label>
                                                <select name="title" id="title" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="Mr">Mr.</option>
                                                    <option value="Mrs">Mrs.</option>
                                                    <option value="Miss">Miss.</option>
                                                    <!-- <option value="Shri">Shri.</option>
                                                    <option value="Smt">Smt.</option> -->
                                                </select>
                                                <div class="invalid-feedback" id="title_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="first_name">{{$phrase->f_name}}<span style="color:red;">*</span></label>
                                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" maxlength="20" autocomplete="off">
                                                <div class="invalid-feedback" id="first_name_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="middle_name">{{$phrase->m_name}}</label>
                                                <input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="Middle Name" maxlength="20" autocomplete="off">
                                                <div class="invalid-feedback" id="middle_name_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="last_name">{{$phrase->l_name}}</label>
                                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" maxlength="20" autocomplete="off">
                                                <div class="invalid-feedback" id="last_name_error_msg"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="org_id">{{$phrase->organisation}}<span style="color:red;">*</span></label>
                                                <select name="org_id" id="org_id" class="form-control form-control">
                                                    <option value="">--Select--</option>
                                                    @foreach($organization_data as $organization)
                                                    <option value="{{$organization->org_id}}">{{$organization->org_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="org_id_error_msg"></div>
                                            </div>
                                        </div> -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="desig_id">{{$phrase->designation}}<span style="color:red;">*</span></label>
                                                <select name="desig_id" id="desig_id" class="form-control form-control">
                                                    <option value="">--Select--</option>
                                                    @foreach($designation_data as $designation)
                                                    <option value="{{$designation->desig_id}}">{{$designation->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="desig_id_error_msg"></div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="start_date">{{$phrase->start_date}} </label>
                                                <input type="text" name="start_date" id="start_date" class="form-control start_date_end_date_datepicker" placeholder="dd/mm/yyyy" autocomplete="off">
                                                <div class="invalid-feedback" id="start_date_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="end_date">{{$phrase->end_date}} </label>
                                                <input type="text" name="end_date" id="end_date" class="form-control start_date_end_date_datepicker" placeholder="dd/mm/yyyy" autocomplete="off">
                                                <div class="invalid-feedback" id="end_date_error_msg"></div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">{{$phrase->email_id}}
                                                    <font style="color:red;">*</font>
                                                </label>
                                                <input type="text" name="email" id="email" class="form-control" placeholder="example@example.com" maxlength="50" autocomplete="off">
                                                <div class="invalid-feedback" id="email_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="username">{{$phrase->user_name}}<span style="color:red;">*</span></label>
                                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" maxlength="20" autocomplete="off">
                                                <div class="invalid-feedback" id="username_error_msg"></div>
                                            </div>
                                        </div>


                                        <div class="col-md-3 edit-form-elements">
                                            <div class="form-group">
                                                <label for="password">{{$phrase->password}}<span style="color:red;">*</span></label>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" maxlength="15" autocomplete="off">
                                                <div class="invalid-feedback" id="password_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 edit-form-elements">
                                            <div class="form-group">
                                                <label for="confirm_password">{{$phrase->conf_password}} <span style="color:red;">*</span></label>
                                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-type Password" maxlength="15" autocomplete="off">
                                                <div class="invalid-feedback" id="confirm_password_error_msg"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mobile">{{$phrase->mobile_no}}</label>
                                                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="10 digit number only" maxlength="10" autocomplete="off">
                                                <div class="invalid-feedback" id="mobile_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="address">{{$phrase->address}}</label>
                                                <input type="text" name="address" id="address" class="form-control" placeholder="Address" maxlength="100" autocomplete="off">
                                                <div class="invalid-feedback" id="address_error_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">{{$phrase->sts}}
                                                    <font style="color:red;">*</font>
                                                </label>
                                                <select name="status" id="status" class="form-control form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <div class="invalid-feedback" id="status_error_msg"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="profile_picture">Profile Picture</label>
                                                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                                                <div id="profile_picture_delete_div" style="padding:5px 0; display: none;">
                                                    <div>Previous Image</div>
                                                    <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                                        <img src="" style="height:120px;">
                                                        <span style="position:absolute;top:0;right:0; background: rgba(0,0,0,0.5); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick=""><i class="fas fa-trash"></i></span>
                                                    </div>
                                                </div>
                                                <input type="text" name="profile_picture_delete" id="profile_picture_delete" value="" hidden>
                                                <div class="invalid-feedback" id="profile_picture_error_msg"></div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="card-action">
                                        <input type="text" name="hidden_input_purpose" id="hidden_input_purpose" value="add" hidden>
                                        <input type="text" name="hidden_input_id" id="hidden_input_id" value="NA" hidden>

                                        <button type="submit" onclick="return submitForm()" class="btn btn-secondary">{{$phrase->submit}}</button>
                                        &nbsp;&nbsp;<button type="button" class="btn btn-dark" onclick="hideForm()">{{$phrase->cancel}}&nbsp;&nbsp;<i class="fas fa-times"></i></button>
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
                <table class="table-datatable display table table-striped table-hover">
                    <thead style="background: #d6dcff;color: #000;">
                        <tr>
                            <th>Profile Picture</th>
                            <th>{{$phrase->name}}</th>
                            <th>{{$phrase->email_id}} </th>
                            <th>{{$phrase->user_name}}</th>
                            <th>{{$phrase->designation}}</th>
                            <th>{{$phrase->address}}</th>
                            <th>{{$phrase->sts}}</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($results as $key => $val)
                        <tr>
                            <td>@if($val->profile_picture)<center><img src="{{$val->profile_picture}}" style="height: 50px;"></center> @endif</td>
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
                           
                            <td>
                            
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                @if(@$desig_permissions["mod1"]["edit"])
                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                   
                                    <button class="dropdown-item" type="button" onclick="editUser('{{$val->id}}')">Edit Profile</button>
                                    <button class="dropdown-item" type="button" onclick="myFun({{$val->id}})">Change Password</button>
                                </div>
                                @endif
                                </div>
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

<!-- Model for chnge password -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Password Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('user/password_change')}}" method="POST" id="new_model">
                @csrf
                    <div class="form-group">
                        <label for="new_password" class="col-form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        <div class="invalid-feedback" id="new_password_error_msg"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password_model" class="col-form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password_model" name="confirm_password">
                        <div class="invalid-feedback" id="confirm_password_error_msg_model"></div>
                    </div>

               
            </div>
            <div class="modal-footer">

                <input type="text" name="input_id" id="input_id" hidden>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" onclick="return passwordSubmit()" class="btn btn-primary">Submit</button>

            </div>
            </form>
        </div>
    </div>
</div>

<!-- end -->

<script>
    
      var new_password_error = true;
      var confirm_password_error_model = true;

$(document).ready(function() {
    $("#new_password").change(function(){
      
        new_password_validate();
    });
    $("#confirm_password_model").change(function(){
      
      confirm_password_validate_model();
  });
});

    function new_password_validate()
    {
    
        var password_val = $("#new_password").val();
        var regEx = new RegExp('^[a-zA-Z0-9_\@\#\!\$\%\^\&\*\-]+$');
       
     
        if (password_val == "") {
          
            new_password_error = true;
            $("#new_password").addClass("is-invalid");
            $("#new_password_error_msg").html("Please enter password");
        } else if (!regEx.test(password_val) ) {
            new_password_error = true;
            $("#new_password").addClass("is-invalid");
            $("#new_password_error_msg").html("Only alphabets, numbers and special characters are allowed");
        } else if (password_val.length < 4) {
            new_password_error = true;
            $("#new_password").addClass("is-invalid");
            $("#new_password_error_msg").html("Password should be greater than 4 digit");
        } else {
            new_password_error = false;
            $("#new_password").removeClass("is-invalid");
        }
    }

    function confirm_password_validate_model()
    {
       
        var password_val = $("#confirm_password_model").val();
        var regEx = new RegExp('^[a-zA-Z0-9_\@\#\!\$\%\^\&\*\-]+$');
       
       
        if (password_val == "") {
            confirm_password_error_model = true;
            $("#confirm_password_model").addClass("is-invalid");
            $("#confirm_password_error_msg_model").html("Please enter password");
        } else if (!regEx.test(password_val)) {
            confirm_password_error_model = true;
            $("#confirm_password_model").addClass("is-invalid");
            $("#confirm_password_error_msg_model").html("Only alphabets, numbers and special characters are allowed");
        } else if (password_val.length < 4) {
            confirm_password_error_model = true;
            $("#confirm_password_model").addClass("is-invalid");
            $("#confirm_password_error_msg_model").html("Password should be greater than 4 digit");
        } else {
            confirm_password_error_model = false;
            $("#confirm_password_model").removeClass("is-invalid");
        }
    }

function passwordSubmit(){
  
        if(new_password_error || confirm_password_error_model){  return false; } // error occured
        else{   return true; } // proceed to submit form data
    }

  

    
</script>

<script>
    function to_delete_profile_picture(path, e) {
        $("#profile_picture_delete").val(path);
        $(e).closest("#profile_picture_delete_div").fadeOut(300);
    }
</script>

<script>
    // Date Picker
    $(document).ready(function() {
        $('.start_date_end_date_datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
    });


    function myFun(id) {
        $("#input_id").val(id);
        $('#exampleModal').modal('show');
    }
</script>

<script>
    // validations starts
    var profile_picture = true;
    var title_error = true;
    var first_name_error = true;
    var middle_name_error = true;
    var last_name_error = true;

   
    var desig_id_error = true;
   
    var email_error = true;
    var username_error = true;
    var password_error = true;
    var confirm_password_error = true;

    var mobile_error = true;
    var address_error = true;
    var status_error = true;

  
   
    

    $(document).ready(function() {
        $("#profile_picture").change(function() {
            profile_picture_validate();
        })
        $("#title").change(function() {
            title_validate();
        });
        $("#first_name").change(function() {
            first_name_validate();
        });
        $("#middle_name").change(function() {
            middle_name_validate();
        });
        $("#last_name").change(function() {
            last_name_validate();
        });

        $("#desig_id").change(function() {
            desig_id_validate();
        });
       

        $("#email").change(function() {
            email_validate();
        });
        $("#username").change(function() {
            username_validate();
        });
        $("#password").change(function() {
            password_validate();
        });
        $("#confirm_password").change(function() {
            confirm_password_validate();
        });

        $("#mobile").change(function() {
            mobile_validate();
        });
        $("#mobile").keyup(function() { // replacing no numbers
            $("#mobile").val($("#mobile").val().replace(/[^0-9]+/g, ""));
        });

        $("#address").change(function() {
            address_validate();
        });
        $("#status").change(function() {
            status_validate();
        });
       
   
    });

    

    function profile_picture_validate() {
        var profile_picture_val = $("#profile_picture").val();
        var ext = profile_picture_val.substring(profile_picture_val.lastIndexOf('.') + 1).toLowerCase();
        if (ext) // if selected
        {
            if (ext != "jpg" && ext != "jpeg" && ext != "png") {
                profile_picture_error = true;
                $("#profile_picture").addClass('is-invalid');
                $("#profile_picture_error_msg").html("Please select JPG/JPEG/PNG only");
            } else {
                profile_picture_error = false;
                $("#profile_picture").removeClass('is-invalid');
            }
        } else {
            profile_picture_error = false;
            $("#profile_picture").removeClass('is-invalid');
        }
    }

    function title_validate() {
        var title_val = $("#title").val();
        if (title_val) {
            title_error = false;
            $("#title").removeClass("is-invalid");
        } else {
            title_error = true;
            $("#title").addClass("is-invalid");
            $("#title_error_msg").html("Please select title");
        }
    }

    function first_name_validate() {
        var first_name_val = $("#first_name").val();
        var regEx = new RegExp('^[a-zA-Z]+$');
        if (first_name_val == "") {
            first_name_error = true;
            $("#first_name").addClass("is-invalid");
            $("#first_name_error_msg").html("Please enter first name");
        } else if (!regEx.test(first_name_val)) {
            first_name_error = true;
            $("#first_name").addClass("is-invalid");
            $("#first_name_error_msg").html("Please enter valid name");
        } else {
            first_name_error = false;
            $("#first_name").removeClass("is-invalid");
        }
    }

    function middle_name_validate() {
        var middle_name_val = $("#middle_name").val();
        var regEx = new RegExp('^[a-zA-Z]+$');
        if (middle_name_val != "") {
            if (!regEx.test(middle_name_val)) {
                middle_name_error = true;
                $("#middle_name").addClass("is-invalid");
                $("#middle_name_error_msg").html("Please enter valid name");
            } else {
                middle_name_error = false;
                $("#middle_name").removeClass("is-invalid");
            }
        } else {
            middle_name_error = false;
            $("#middle_name").removeClass("is-invalid");
        }
    }

    function last_name_validate() {
        var last_name_val = $("#last_name").val();
        var regEx = new RegExp('^[a-zA-Z]+$');
        if (last_name_val != "") {
            if (!regEx.test(last_name_val)) {
                last_name_error = true;
                $("#last_name").addClass("is-invalid");
                $("#last_name_error_msg").html("Please enter valid name");
            } else {
                last_name_error = false;
                $("#middle_name").removeClass("is-invalid");
            }
        } else {
            last_name_error = false;
            $("#last_name").removeClass("is-invalid");
        }
    }

 

    function desig_id_validate() {
        var desig_id_val = $("#desig_id").val();
        if (desig_id_val) {
            desig_id_error = false;
            $("#desig_id").removeClass("is-invalid");
        } else {
            desig_id_error = true;
            $("#desig_id").addClass("is-invalid");
            $("#desig_id_error_msg").html("Please select organisation");
        }
    }

  

    function email_validate() {
        var email_val = $("#email").val();
        var regEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (email_val == "") {
            email_error = true;
            $("#email").addClass("is-invalid");
            $("#email_error_msg").html("Please enter your email ID");
        } else if (!regEx.test(email_val)) {
            email_error = true;
            $("#email").addClass("is-invalid");
            $("#email_error_msg").html("Please enter valid email ID");
        } else {
            email_error = false;
            $("#email").removeClass("is-invalid");
        }
    }

    function username_validate() {
        var username_val = $("#username").val();
        var regEx = new RegExp('^[a-zA-Z0-9_-]+$');
        if (username_val == "") {
            username_error = true;
            $("#username").addClass("is-invalid");
            $("#username_error_msg").html("Please enter username");
        } else if (!regEx.test(username_val)) {
            username_error = true;
            $("#username").addClass("is-invalid");
            $("#username_error_msg").html("Only alphabets and numbers are allowed");
        } else {
            username_error = false;
            $("#username").removeClass("is-invalid");
        }
    }

    function password_validate() {
        var password_val = $("#password").val();
        var regEx = new RegExp('^[a-zA-Z0-9_\@\#\!\$\%\^\&\*\-]+$');
       
        if ($("#hidden_input_purpose").val() == "add") {
            if (password_val == "") {
                password_error = true;
                $("#password").addClass("is-invalid");
                $("#password_error_msg").html("Please enter password");
            } else if (!regEx.test(password_val) ) {
                password_error = true;
                $("#password").addClass("is-invalid");
                $("#password_error_msg").html("Only alphabets, numbers and special characters are allowed");
            } else if (password_val.length < 4) {
                password_error = true;
                $("#password").addClass("is-invalid");
                $("#password_error_msg").html("Password should be greater than 4 digit");
            } else {
                password_error = false;
                $("#password").removeClass("is-invalid");
            }
        } else {
            password_error = false;
            $("#password").removeClass("is-invalid");
        }
    }

    

    function confirm_password_validate() {
        var confirm_password_val = $("#confirm_password").val();
        var regEx = new RegExp('^[a-zA-Z0-9_\@\#\!\$\%\^\&\*\-]+$');
      
        if ($("#hidden_input_purpose").val() == "add") {
            if (confirm_password_val == "") {
                confirm_password_error = true;
                $("#confirm_password").addClass("is-invalid");
                $("#confirm_password_error_msg").html("Please re-type password");
            } else if (!regEx.test(confirm_password_val)) {
                confirm_password_error = true;
                $("#confirm_password").addClass("is-invalid");
                $("#confirm_password_error_msg").html("Only alphabets, numbers and special characters are allowed");
            } else if (confirm_password_val.length < 4) {
                confirm_password_error = true;
                $("#confirm_password").addClass("is-invalid");
                $("#confirm_password_error_msg").html("Password should be greater than 4 digit");
            } else {
                if ($("#password").val()) {
                    if ($("#confirm_password").val() == $("#password").val()) {
                        confirm_password_error = false;
                        $("#confirm_password").removeClass("is-invalid");
                    } else {
                        confirm_password_error = true;
                        $("#confirm_password").addClass("is-invalid");
                        $("#confirm_password_error_msg").html("Password did not matched");
                    }
                } else {
                    confirm_password_error = false;
                    $("#confirm_password").removeClass("is-invalid");
                }
            }
        } else {
            confirm_password_error = false;
            $("#confirm_password").removeClass("is-invalid");
        }
    }

    function mobile_validate() {
        var mobile_val = $("#mobile").val();
        var regEx = new RegExp('^[0-9]+$');
        if (mobile_val) {
            if (!regEx.test(mobile_val)) {
                mobile_error = true;
                $("#mobile").addClass("is-invalid");
                $("#mobile_error_msg").html("Please enter valid mobile no");
            } else if (mobile_val.length != 10) {
                mobile_error = true;
                $("#mobile").addClass("is-invalid");
                $("#mobile_error_msg").html("Please enter 10 digit no only");
            } else {
                mobile_error = false;
                $("#mobile").removeClass("is-invalid");
            }
        } else {
            mobile_error = false;
            $("#mobile").removeClass("is-invalid");
        }
    }

    function address_validate() {
        address_error = false;
    }

    function status_validate() {
        var status_val = $("#status").val();
        if (status_val) {
            status_error = false;
            $("#status").removeClass("is-invalid");
        } else {
            status_error = true;
            $("#status").addClass("is-invalid");
            $("#status_error_msg").html("Please select status");
        }
    }

    // final submit
    function submitForm() {
        title_validate();
        first_name_validate();
        middle_name_validate();
        last_name_validate();

     
        desig_id_validate();
      

        email_validate();
        username_validate();
        password_validate();
        confirm_password_validate();

        mobile_validate();
        address_validate();
        status_validate();

        if (title_error || first_name_error || middle_name_error || last_name_error  || desig_id_error  || email_error || username_error || password_error || confirm_password_error || mobile_error || confirm_password_error || mobile_error || address_error || status_error) {
            console.log(title_error + " " + first_name_error + " " + middle_name_error + " " + last_name_error + " "  + desig_id_error + " " + email_error + " " + username_error + " " + password_error + " " + confirm_password_error + " " + mobile_error + " " + address_error + " " + status_error);
            console.log(password_error);
            return false;
        } else {
            return true;
        }
    }

   

</script>

<script>
    function editUser(id) {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            
            url: "{{ url('user/getuser-details/') }}" + '/' + id,
            method: "GET",
            contentType: 'application/json',
           
            success: function(data) {
                console.log(data);
                resetUserForm();
                $('.edit-form-elements').hide();
                if (data.profile_picture) { // previous icon
                    $("#profile_picture_delete_div img").prop("src", '{{url("")}}/' + data.profile_picture);
                    $("#profile_picture_delete_div span").attr("onclick", "to_delete_profile_picture('" + data.profile_picture + "',this)");
                    $("#profile_picture_delete_div").show();
                }
                $("#hidden_input_purpose").val("edit"); // assigning as edit
                $("#hidden_input_id").val(id); // assigning as edit id

                $("#show-toggle1").slideDown(300);
                // appending datas
                $("#title").val(data.title);
                $("#first_name").val(data.first_name);
                $("#middle_name").val(data.middle_name);
                $("#last_name").val(data.last_name);
              
                $("#desig_id").val(data.desig_id);
               
                $("#email").val(data.email);
                $("#username").val(data.username);
                // $("#password").val(data.password);
                $("#mobile").val(data.mobile);
                $("#address").val(data.address);
                $("#status").val(data.status);
            }
        });
    }


    function resetUserForm() {
        document.getElementById("user-form").reset();
        $('.edit-form-elements').show();
        $("#hidden_input_purpose").val("add"); // resetting hidden input purpose to add
        $("#hidden_input_id").val("NA"); // restting hidden input id to NA
        $("#title").removeClass("is-invalid");
        $("#first_name").removeClass("is-invalid");
        $("#middle_name").removeClass("is-invalid");
        $("#middle_name").removeClass("is-invalid");
        $("#middle_name").removeClass("is-invalid");
        $("#last_name").removeClass("is-invalid");
        $("#org_id").removeClass("is-invalid");
        $("#desig_id").removeClass("is-invalid");
        $("#email").removeClass("is-invalid");
        $("#username").removeClass("is-invalid");
        $("#password").removeClass("is-invalid");
        $("#password").removeClass("is-invalid");
        $("#confirm_password").removeClass("is-invalid");
        $("#confirm_password").removeClass("is-invalid");
        $("#confirm_password").removeClass("is-invalid");
        $("#mobile").removeClass("is-invalid");
        $("#mobile").removeClass("is-invalid");
        $("#status").removeClass("is-invalid");
    }

    function hideForm() {
        // resetUserForm(); // resetting form
        // document.getElementById("user-form").reset();
        resetUserForm(); // resetting form
        $("#show-toggle1").slideUp(150); // opening form div
    }
</script>

@endsection