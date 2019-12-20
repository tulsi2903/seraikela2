@extends('layout.layout')
@section('title', 'Add user')
<style>
    hr.new2 {
        border-top: 1px dashed #000;
    }
    
    .card .card-header,
    .card-light .card-header {
        padding: 0rem 1.25rem;
        background-color: transparent;
        border-bottom: 0px solid #ebecec!important;
        margin-top: 9px;
    }
    
    .form-floating-label .placeholder {
        position: absolute;
        padding: .375rem .75rem;
        transition: all .2s;
        opacity: .8;
        margin-bottom: 0!important;
        font-size: 18px!important;
        font-weight: 400;
        top: 12px;
    }
    .card .card-action, .card-light .card-action {
    padding: 11px;
    background-color: transparent;
    line-height: 30px;
    border-top: 1px solid #ebecec!important;
    font-size: 14px;
    }
    .card, .card-light {
    border-radius: 5px;
    /* background-color: #fff; */
    margin-bottom: 30px;
    -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
    -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
    box-shadow: 2px 6px 6px #6f6e6e;
    border: 0;
    /* background-image: linear-gradient(to right, white , #9296a2); */
    background: linear-gradient(to top, #cedcff, #ffffff 50%, #ffffff, #ffffff 75%);
}
hr.new2 {
        border-top: 1px dashed #000;
        margin-top: -13px;
    }
    #show-toggle1 {
    padding: 5px;
    text-align: center;
    background-color: #ffffff;
    margin-bottom: 7px;
    }
    
    #show-toggle1{
        padding: 6px;
        display: none;
    }
    .form-check label, .form-group label {
    margin-bottom: .5rem;
    color: #495057;
    font-weight: 600;
    font-size: 1rem;
    white-space: nowrap;
    float: left;
    }
    .card-title {
    margin: 0;
    color: #575962;
    font-size: 20px;
    font-weight: 400;
    line-height: 1.6;
    margin-top: 6px;
    }
   
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
                            <div class="card" style="border-top: 1px solid #5c76b7;">
                            <form action="{{url('user/store')}}" method="POST">
                                    @csrf      
                                <div class="card-body" style="margin-top: -15px;">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Title</label>
                                                <select name="title" class="form-control form-control" id="defaultSelect">
                                                    <!-- <option>--select--</option> -->
                                                    <option>Mr.</option>
                                                    <option>Mrs.</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">First Name <font style="color:red;">*</font></label>
                                                <input type="" name="first_name" class="form-control" id="" required placeholder="First Name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Last Name <font style="color:red;">*</font></label>
                                                <input type="" name="last_name" class="form-control" id="" required placeholder="Last Name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Status<font style="color:red;">*</font></label>
                                                <select name="is_active" class="form-control form-control" required id="defaultSelect">
                                                    <!-- <option>--select--</option> -->
                                                    <option> Active </option>
                                                    <option>Inactive</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Start Date<font style="color:red;">*</font></label>
                                                <div class="input-group">
                                                    <input type="date" name="start_date" class="form-control"  required placeholder="mm/dd/yyyy" id="datepicker-start-date">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">End date<font style="color:red;">*</font></label>
                                                <div class="input-group">
                                                    <input type="date" name="end_date" class="form-control" required placeholder="mm/dd/yyyy" id="datepicker-end-date">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Designation <font style="color:red;">*</font></label>
                                                    <select name="desig_id" class="form-control form-control" required id="defaultSelect">
                                                        @foreach($designation_data as $designation)
                                                            <option value="{{$designation->desig_id}}">{{$designation->name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Username<font style="color:red;">*</font></label>
                                                <input type="" name="username" class="form-control" id="" required placeholder="Username">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Password<font style="color:red;">*</font></label>
                                                <input type="" name="password" class="form-control" required id="" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Confirm Password<font style="color:red;">*</font></label>
                                                <input type="" name="confirm_pass" class="form-control" required id="" placeholder="Re-type Password">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Organisation <font style="color:red;">*</font></label>
                                                    <select name="org_id" class="form-control form-control" required id="defaultSelect">
                                                        @foreach($organization_data as $organization)
                                                            <option value="{{$organization->org_id}}">{{$organization->org_name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Mobile No.<font style="color:red;">*</font></label>
                                                <input type="" name="mobile" class="form-control" required id="" placeholder="(999) 999-9999">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Email Id.<font style="color:red;">*</font></label>
                                                <input type="" name="email" class="form-control" required id="" placeholder="example@gmail.com">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Address</label>
                                                <div class="input-group">
                                                    <input type="text"  name="address" class="form-control" placeholder="Address" aria-label="" aria-describedby="basic-addon1">
                                                    <div class="input-group-prepend" style="margin-left:10px;">
                                                        <!-- <button class="btn btn-icon btn-primary btn-round btn-xs">
                                                            <i class="fa fa-plus"></i>
                                                        </button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">


                                        </div>
                                    </div>
                                    <div class="card-action">
                                        <hr class="new2">
                                        <button class="btn btn-secondary">Submit</button>
                                        <button class="btn btn-danger">Cancel</button>
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
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover" >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email Id.</th>
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
                                    <td>{{$val->email_id}}</td>
                                    <td>{{$val->username}}</td>
                                    <td>{{$val->designame}}</td>
                                    <td>{{$val->address}}</td>
                                    <td>{{$val->is_active}}</td>
                                </tr>
                                @endforeach
                            
                            
                            </tbody>
                        </table>
                    </div>
                </div>
                
            <!-----------------------------------------end of table------------------------------------------>
            </div>
        </div>
    </div>

    <script>
        // Date Picker
        jQuery('#datepicker-end-date').datepicker();
            jQuery('#datepicker-end-date-inline').datepicker();
            jQuery('#datepicker-end-date-multiple').datepicker({
                numberOfMonths: 3,
                showButtonPanel: true
            });
    </script>

    <script>
        // Date Picker
        jQuery('#datepicker-start-date').datepicker();
            jQuery('#datepicker-start-date-inline').datepicker();
            jQuery('#datepicker-start-date-multiple').datepicker({
                numberOfMonths: 3,
                showButtonPanel: true
            });
    </script>
</div>
@endsection