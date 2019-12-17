<?php
    if (!Auth::guest())
    {
        // logged in
    }
    else{
        ?>
        <script>window.location = "{{url('login')}}";</script>
        <?php
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- page title -->
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{url('public/assets/img/icon.ico')}}" type="image/x-icon">

    <!-- Fonts and icons -->
    <script src="{{url('public/assets/js/webfont.min.js')}}"></script>
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},    
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                 urls:["{{url('public/assets/css/fonts.min.css')}}"]
                 },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{url('public/css/app.css')}}">
    <link rel="stylesheet" href="{{url('public/assets/css/atlantis.min.css')}}">
    <link rel="stylesheet" href="{{url('public/assets/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/chart/Chart.min.css')}}">
    

    <!-- core js file, jquery, bootstrap etc -->
    <script src="{{url('public/js/app.js')}}"></script>

    <!-- all pages styling -->
    <style>
        /* page loader */
        .custom-loader {
            position: fixed;
            text-align: center;
            z-index: +50;
            width: 100vw;
            height: 100vh;
            padding-top: 250px;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.4);
            color: white;
            display: none;
        }

         .demo-icon {
            margin-top: 7px;
            margin-left: 10px;
            color: #fff;
            font-weight: 500;
            letter-spacing: 0.5px;
            font-size: 26px;
            width: 150%;
        }
        .logo-header {
            float: left;
            width: 250px;
            height: 66px;
            line-height: 60px;
            color: #333;
            z-index: 1001;
            font-size: 17px;
            font-weight: 400;
            padding-left: 25px;
            padding-right: 25px;
            z-index: 1001;
            display: flex;
            align-items: center;
            position: relative;
            transition: all .3s;
        }
        .messages-notif-box .notif-center a .notif-content .subject, .notif-box .notif-center a .notif-content .subject {
            font-size: 13px;
            font-weight: 600;
            display: block;
            margin-bottom: 0px;
            margin-top: 11px;
        }

        .card-title {
            margin: 0;
            color: #147785;
            font-size: 20px;
            font-weight: 400;
            line-height: 1.6;
            margin-left: 11px;
        }
        #show-toggle1 {
        padding: 5px;
        background-color: #ffffff;
        margin-bottom: 7px;
        }
        
        #show-toggle1{
            padding: 6px;
            display: none;
        }
        .card-stats .icon-big {
        width: 140%;
        height: 140%;
        font-size: 2.2em;
        min-height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 4px 4px 11px 2px #909090;
        }
        .row-card-no-pd {
            border-radius: 5px;
            margin-left: 0;
            margin-right: 0;
            background: linear-gradient(to top, #a5baef, #ffffff 55%, #ffffff, #ffffff 75%);
            margin-bottom: 1px;
            padding-top: 0px;
            padding-bottom: 15px;
            position: relative;
            -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
            -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
            box-shadow: 2px 6px 9px #7f7f7f;
        }
        .table td, .table th {
        font-size: 14px;
        border-top-width: 0;
        border-bottom: 1px solid;
        border-color: #888888!important;
        padding: 0 23px!important;
        height: 40px;
        vertical-align: middle!important;
        color: #000;
        }
        .card, .card-light {
        border-radius: 5px;
        background-color: #fff;
        margin-bottom: 30px;
        -webkit-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        -moz-box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
        border: 0;
        }
        hr.new2 {
        border-top: 1px dashed #000;
        }

        @media print {
            body{
                background: white !important;
            }
            body, body * {
                visibility: hidden;
            }
            #printable-area, #printable-area * {
                visibility: visible;
            }
            #printable-area{
                position: fixed;
                left: 0;
                top: 40px;
                width: 100vw !important;
            }
            #print-button, #print-button *{
                visibility: hidden;
            }
            .card-title{
                visibility: visible !important;
                position: fixed;
                left: 0;
                top: 0px;
                width: 100vw !important;
            }
            .action-buttons{
                display: none;
            }
         } 
    </style>

    <!-- respective pages styling -->
    @yield('page-style')
</head>
<body>
	<div class="wrapper static-sidebar">
		<!-- main-header -->
        @include('layout.header')
        <!-- main-header ends -->

		<!-- Sidebar -->
        @include('layout.sidebar')
		<!-- End Sidebar -->

		<div class="main-panel">
			<div class="content">
				<div class="page-inner">
                    @if(session()->exists('alert-class')&&session()->exists('alert-content'))
                        @if(session()->get('alert-class')=="alert-success")
                            <script>
                                $(document).ready(function(){
                                    swal("Success!", "{{session()->get('alert-content')}}", {
                                        icon : "success",
                                        buttons: {
                                            confirm: {
                                                className : 'btn btn-success'
                                            }
                                        },
                                    });
                                });
                            </script>
                        @endif
                        @if(session()->get('alert-class')=="alert-danger")
                            <script>
                                $(document).ready(function(){
                                    swal("Error Occured!", "{{session()->get('alert-content')}}", {
                                        icon : "error",
                                        buttons: {
                                            confirm: {
                                                className : 'btn btn-danger'
                                            }
                                        },
                                    });
                                });
                            </script>
                        @endif
                        <?php
                            session()->forget('alert-class');
                            session()->forget('alert-content');
                        ?>
                    @endif
                    @yield('page-content')
					<!-- <div class="page-header">
						<h4 class="page-title">Dashboard</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="#">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Pages</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Starter Page</a>
							</li>
						</ul>
					</div>
					<div class="page-category">Inner page content goes here</div> -->
				</div> <!-- page inner end here -->
			</div> <!-- content end here -->
			<!-- footer starts here -->
            @include('layout.footer')
            <!-- footer ends here -->
		</div> <!-- main panel closed -->

	</div> <!-- wrapper closed -->


    <!-- custom loader -->
    <div class="custom-loader">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <br/>Loading..
    </div>
    

    <!-- for delete button, alert/confirm -->
    <script>
        $(document).ready(function(){
            $('#delete-button, .delete-button').click(function(e) {
                e.preventDefault();
                var href = this.href;

                swal({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    buttons:{
                        cancel: {
                            visible: true,
                            text : 'No, cancel!',
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text : 'Yes, delete it!',
                            className : 'btn btn-success'
                        }
                    }
                }).then((willDelete) => {
                    if (willDelete) {
                        window.location = href;
                    } else {
                        return false;
                        swal("Your imaginary file is safe!", {
                            buttons : {
                                confirm : {
                                    className: 'btn btn-success'
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        function printView()
        {
          window.print();
        }
    </script>

    <script type="text/javascript">
       $(document).ready( function () {
            $('.table-datatable').DataTable();
        } );
   </script>
   <script>
        // Date Picker
        $(document).ready( function (){
            jQuery('.datepicker-date').datepicker();
            jQuery('.datepicker-date-multiple').datepicker({
                numberOfMonths: 3,
                showButtonPanel: true
            });
        });
    </script>

	<!-- core scripts starts, footer-scripts  -->
    @include('layout.footer-scripts')
    <!-- core scripts ends, footer scripts -->
</body>
</html>