@extends('layout.layout')

@section('title', 'Asset')

@section('page-content')
<div class="card">
    <div class="col-md-12">
            <div class="card-header">
                <div class="card-head-row card-tools-still-right" style="background:#fff;">
                    <h4 class="card-title">Asset</h4>
                    <div class="card-tools">
                        <a href="{{url('asset')}}" class="btn btn-sm btn-secondary" style="float:right;"><i
                                class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                    </div>
                </div>
            </div>
        </div>
    <div class="card-body">
            <form action="{{url('asset/store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="asset_name">Asset Name<span style="color:red;margin-left:5px;">*</span></label>
                        <input type="text" name="asset_name" id="asset_name" class="form-control"
                            value="{{$data->asset_name}}" autocomplete="off">
                        <div class="invalid-feedback" id="asset_name_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="asset_icon">Asset Icon</label>
                        <input type="file" name="asset_icon" id="asset_icon" class="form-control">
                        @if($hidden_input_purpose=="edit"&&$data->asset_icon)
                            <div id="asset_icon_delete_div" style="padding:5px 0;">
                                <div>Previous Icon</div>
                                <div style="display: inline-block;position:relative;padding:3px;border:1px solid #c4c4c4; border-radius:3px;">
                                    <img src="{{url($data->asset_icon)}}" style="height:80px;">
                                    <span style="position:absolute;top:0;right:0; background: rgba(0,0,0,0.5); font-size: 18px; cursor: pointer; padding: 5px 10px;" class="text-white" onclick="to_delete('{{$data->asset_icon}}',this)"><i class="fas fa-trash"></i></span>
                                </div>
                            </div>
                        @endif
                        <input type="text" name="asset_icon_delete" id="asset_icon_delete" value="" hidden>
                        <div class="invalid-feedback" id="asset_icon_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="movable">Type<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="movable" id="movable" class="form-control">
                            <option value="">---Select---</option>
                            <option value="1" <?php if($data->movable=='1'){ echo "selected"; } ?>>Movable</option>
                            <option value="0" <?php if($data->movable=='0'){ echo "selected"; } ?>>Immovable</option>
                        </select>
                        <div class="invalid-feedback" id="movable_error_msg"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dept_id">Department Name<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="dept_id" id="dept_id" class="form-control">
                            <option value="">---Select---</option>
                            @foreach( $departments as $department )
                            <option value="{{ $department->dept_id }}"
                                <?php if($data->dept_id==$department->dept_id){ echo "selected"; } ?>>
                                {{ $department->dept_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="department_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category">Category<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="category" id="category" class="form-control">
                            <option value="">---Select---</option>
                            @foreach( $categorys as $category )
                            <option value="{{ $category->asset_cat_id }}"
                                <?php if($data->category_id==$category->asset_cat_id){ echo "selected"; } ?>>
                                {{ $category->asset_cat_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="category_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="subcategory">Subcategory<span style="color:red;margin-left:5px;">*</span></label>
                        <select name="subcategory" id="subcategory" class="form-control">
                            <option value="">---Select---</option>
                            @foreach( $sub_categorys as $sub_category )
                            <option value="{{ $sub_category->asset_sub_id }}"
                                <?php if($data->subcategory_id==$sub_category->asset_sub_id){ echo "selected"; } ?>>
                                {{ $sub_category->asset_sub_cat_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="subcategory_error_msg"></div>
                    </div>
                </div>

                <div class="col-md-4" style="margin-top:1em;">
                    <div class="form-group">
                        <input type="text" name="hidden_input_purpose" value="{{$hidden_input_purpose}}" hidden>
                        <input type="text" name="hidden_input_id" value="{{$hidden_input_id}}" hidden>
                        <button type="submit" class="btn btn-primary" onclick="return submitForm()">Save&nbsp;&nbsp;<i class="fas fa-check"></i></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
/* validation starts */
// error variables as true = error occured
var asset_name_error = true;
var asset_icon_error = true;
var movable_error = true;
var department_error = true;
var category_error = true;
var subcategory_error = true;

$(document).ready(function() {
    $("#asset_name").change(function() {
        asset_name_validate();
    });
    $("#asset_icon").change(function() {
        asset_icon_validate();
    });
    $("#movable").change(function() {
        movable_validate();
    });
    $("#dept_id").change(function() {
        department_validate();
    });
    $("#category").change(function() {
        category_validate();
        ajaxFunc_subcategory();
    });
    $("#subcategory").change(function(){
        subcategory_validate();
    });
});


//asset name validation
function asset_name_validate() {
    var asset_name_val = $("#asset_name").val();
    var regAlphaNumericSpace = new RegExp('^[a-zA-Z0-9 ]+$');
    if (asset_name_val == "") {
        asset_name_error = true;
        $("#asset_name").addClass('is-invalid');
        $("#asset_name_error_msg").html("Asset Name should not be blank");
    } else if (!regAlphaNumericSpace.test(asset_name_val)) {
        asset_name_error = true;
        $("#asset_name").addClass('is-invalid');
        $("#asset_name_error_msg").html("Please enter valid asset");
    } else {
        asset_name_error = false;
        $("#asset_name").removeClass('is-invalid');
    }
}

// asset_icon
function  asset_icon_validate(){
    var asset_icon_val = $("#asset_icon").val();
    var ext = asset_icon_val.substring(asset_icon_val.lastIndexOf('.') + 1);
    if(ext) // if selected
    {
        if(ext !="jpg" && ext!="jpeg" && ext!="png")
        {
            asset_icon_error = true;
            $("#asset_icon").addClass('is-invalid');
            $("#asset_icon_error_msg").html("Please select jpg/png image only");
        }
        else
        {
            asset_icon_error = false;
            $("#asset_icon").removeClass('is-invalid');
        }
    }
    else{
        asset_icon_error = false;
        $("#asset_icon").removeClass('is-invalid');
    }
}

//movable validation
function movable_validate() {
    var movable_val = $("#movable").val();

    if (movable_val == "") {
        movable_error = true;
        $("#movable").addClass('is-invalid');
        $("#movable_error_msg").html("Type should not be blank");
    } else {
        movable_error = false;
        $("#movable").removeClass('is-invalid');
    }
}



// department name validation
function department_validate() {
    var department_val = $("#dept_id").val();


    if (department_val == "") {
        department_error = true;
        $("#dept_id").addClass('is-invalid');
        $("#department_error_msg").html("Department Name should not be blank");
    } else {
        department_error = false;
        $("#dept_id").removeClass('is-invalid');
    }
}

//category validation
function category_validate() {
    var category_val = $("#category").val();


    if (category_val == "") {
        category_error = true;
        $("#category").addClass('is-invalid');
        $("#category_error_msg").html("Category should not be blank");
    } else {
        category_error = false;
        $("#category").removeClass('is-invalid');
    }
}

//subcategory validation
function subcategory_validate() {
    var subcategory_val = $("#subcategory").val();


    if (subcategory_val == "") {
        subcategory_error = true;
        $("#subcategory").addClass('is-invalid');
        $("#subcategory_error_msg").html("Subcategory should not be blank");
    } else {
        subcategory_error = false;
        $("#subcategory").removeClass('is-invalid');
    }
}

 //FETCHING SUBCATEGORY ACCORDING TO CATEGORIES
 function ajaxFunc_subcategory(){
    var asset_cat_id_tmp = $("#category").val();
    
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:"{{url('asset/get-subcategory')}}",
        data: {'asset_cat_id':asset_cat_id_tmp},
        method:"GET",
        contentType:'application/json',
        dataType:"json",
        beforeSend: function(data){
            $(".custom-loader").fadeIn(300);
        },
        error:function(xhr){
            alert("error"+xhr.status+","+xhr.statusText);
            $(".custom-loader").fadeOut(300);
        },
        success:function(data){
            console.log(data);
            $("#subcategory").html('<option value="">-Select-</option>');
                for(var i=0;i<data.subcategory_data.length;i++){
                $("#subcategory").append('<option value="'+data.subcategory_data[i].asset_sub_id+'">'+data.subcategory_data[i].asset_sub_cat_name +'</option>');
                }
                $(".custom-loader").fadeOut(300);
        }
    });
}

function to_delete(image_path, e){
    $("#asset_icon_delete").val(image_path);
    $(e).closest("#asset_icon_delete_div").hide(200);
}

// final submission
function submitForm() {
    asset_name_validate();
    asset_icon_validate();
    movable_validate();
    department_validate();
    category_validate();
    subcategory_validate();

    if(asset_name_error || asset_icon_error || movable_error || department_error || category_error || subcategory_error) {
        return false;
    } // error occured
    else {
        return true;
    } // proceed to submit form data
}
</script>
@endsection