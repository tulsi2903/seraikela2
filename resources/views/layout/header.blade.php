<style>
        .text-muted {
            color: #ffffff!important;
        }

         #ct{
            color: #fff;
            padding: 0.5em;
            border: 1px solid #fff;
        }
        .quick-actions.quick-actions-info .quick-actions-header {
         background: #ffffff;
        }
        .quick-actions.quick-actions-info:after {
            border-bottom-color: #ffffff!important;
        }
        .navbar-expand-lg .navbar-nav .dropdown-menu {
            left: auto;
            right: 0;
            width: 10px;
        }
        .dropdown-menu .dropdown-item {
            font-size: .88rem;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease, color 0.3s ease;
            cursor: pointer;
            z-index: 6;
            position: relative;
        }

        .flag.large {
            height: 32px;
            width: 32px;
            background-size: 32px 32px;
        }
        .flag.large {
            height: 32px;
            width: 32px;
            background-size: 32px 32px;
        }
        .ES {
            background:url("{{url('public/images/hin.png')}}") no-repeat;
        }

        .flag.large {
            height: 32px;
            width: 32px;
            background-size: 32px 32px;
        }
        .flag.large {
            height: 32px;
            width: 32px;
            background-size: 32px 32px;
        }
        .FR {
            background:url("{{url('public/images/eng.png')}}") no-repeat;
        }
                
        
    </style>

    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <div class="main-header">
        <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
            
            <span class="logo" style="padding-top: 10px;">
                <img src="http://jiada.baba.software/public/form/images/toplogo.png" alt="navbar brand" class="navbar-brand" style="height:50px;">
                <div class="heading">
                    <p style="margin-top:-52px;margin-left: 18px;color: #ffffff;font-weight: 500;font-size: 20px;line-height: 25px;text-align: center;font-family: serif;letter-spacing: 1px;text-shadow: 2px 4px 9px #8BC34A;">Seraikela Kharsawan</p>
                </div>
            </span>
            <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <i class="icon-menu"></i>
                </span>
            </button>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="icon-menu"></i>
                </button>
            </div>
    
            <div class="container-fluid">
                <div class="collapse" id="search-nav">
                    <div class="demo-icon" style="display: flex;">
                        <div style="margin-top: -7px;font-size: 25px; font-family: initial;text-shadow: 0px 2px 2px #000;    font-family: 'Bree Serif', serif;">&nbsp;&nbsp;District Scheme & Resource Management</div>
                    </div>
                </div>
                <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                    <li class="nav-item toggle-nav-search hidden-caret">
                        <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                            <i class="fa fa-search"></i>
                        </a>
                    </li>
                
                    <li class="nav-item dropdown hidden-caret">
                        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                            <p style="color: rgba(235, 225, 224, 0.932);font-weight: 500;font-family: 'Bree Serif', serif;margin-top: 5px;">Language :<i class="fas fa-sort-alpha-down"></i></p>
                        </a>
                        <div class="dropdown-menu quick-actions quick-actions-info animated fadeIn" style="background: linear-gradient(to top, #c9d8ff, #ffffff 70%, #ffffff, #ffffff 100%);">
                            <?php $one=1;
                            $two=2; ?>
                            <a href="{{url('lang/english/'.$one)}}">
                                <button type="button" tabindex="0" class="dropdown-item">
                                <span class="mr-3 opacity-8 flag large FR"></span>
                                English
                            </button>
                        </a> 
                            
                            <a href="{{url('lang/hindi/'.$two)}}"> <button type="button" tabindex="0" class="dropdown-item">
                                <span class="mr-3 opacity-8 flag large ES"></span>
                                    हिंदी
                            </button>
                            </a>
                           
                     
                        </div>
                    </li>

                        <li class="nav-item dropdown hidden-caret">
                            <span id='ct' ></span>
                        </li>
    
                    <li class="nav-item dropdown hidden-caret">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                            <div class="user-box">
                                <div class="avatar-sm">
                                    @if(Auth::user()->profile_picture)
                                    <img src="{{url('')}}/{{Auth::user()->profile_picture}}" alt="..." class="avatar-img rounded-circle" style="margin-top: 12px;">
                                    @else
                                    <img src="{{url('public/images/user.png')}}" alt="..." class="avatar-img rounded-circle" style="margin-top: 12px;">
                                    @endif
                                </div>
                                <div class="u-text">
                                    <h4 style="color: #fff;font-weight: 500;font-family: 'Bree Serif', serif; text-transform: capitalize;">{{session()->get('user_full_name')}}</h4>
                                    <p class="text-muted" style="color: #fff;font-weight: 500;font-family: 'Bree Serif', serif; text-align: center;">({{session()->get('user_designation_name')}})</p>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </div>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script>
        function display_ct() {
            var x = new Date()
            var x1=x.getDate()+ "/" + (x.getMonth() + 1) + "/" + x.getFullYear();
            x1 = x1 + " - " +  (x.getHours( )  % 12 || 12) + ":" +  x.getMinutes() + ":" +  x.getSeconds();
            document.getElementById('ct').innerHTML = x1;
            display_c();
        }

        function display_c(){
            var refresh=1000; // Refresh rate in milli seconds
            mytime=setTimeout('display_ct()',refresh)
        }
        display_ct();
    </script>

<script>
function myFunction() {
    document.getElementById("demo").innerHTML = "Hello World";
}
</script>