<!DOCTYPE html>
<html lang="en">

<head>
    <title>LOGIN form of DC SEARIEKELA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="{{asset('public/css/style.css')}}" rel='stylesheet' type='text/css' />
    <!-- style.css -->
    <link href="//fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
    <link href="//fonts.googleapis.com/css?family=Playball" rel="stylesheet">
</head>
<style>

</style>
<body>
    <div id="particles-js"></div>
    <div class="main-w3layouts">
        <div class="main-agile">
            <div class="row">
                <div class="col-md-3"  style="float:left;">
					<div class="col-md-4 w3ls-banner-left" style="    margin-left: 3em;">
							<div class="w3ls-banner-left-info">
								<h4>Subdivision</h4>
								<p>02</p>
							</div>
							<div class="w3ls-banner-left-info">
								<h4>Block</h4>
								<p>09</p>
							</div>
							<div class="w3ls-banner-left-info">
								<h4>Panchayat</h4>
								<p>132</p>
							</div>
							<div class="w3ls-banner-left-info">
								<h4>Villages</h4>
								<p>1148</p>
							</div>
						</div>
					</div>
					<!-- <div class="col-md-3">
						<img src="https://atlan.com/assets/img/platform-expanded.31045f13.png" style="height: 400px; float:left;">
					</div> -->
                <div class="col-md-6" style="float:right;">
                    <!-- Main-Content -->
                    <div class="main-w3layouts-form">
                        <h2><a href="https://seraikela.nic.in/" aria-label="Go to home" class="emblem" rel="home">
							<img class="site_logo" height="100" id="logo" src="https://cdn.s3waas.gov.in/s3b337e84de8752b27eda3a12363109e80/uploads/2018/03/2018030571.png" alt="District Seraikela Kharsawan, Government of Jharkhand">
						</a></h2>
                        <!-- main-w3layouts-form -->
                        
     
                    <div class="form">
                        <form method="POST" action="{{ route('login') }}">
                        @csrf
                            <div class="fields-w3-agileits">
                                <span class="fa fa-user" aria-hidden="true"></span>
                                <input  id="email" type="text" class="form-control input-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" required aria-required="true" autofocus placeholder="email">
                                <!-- @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror -->
                           
                            </div>
                            <div class="fields-w3-agileits">
                                <span class="fa fa-key" aria-hidden="true"></span>
                                <input  type="password" id="password"  class="form-control input-lg @error('password') is-invalid @enderror" name="password" required="" aria-required="true" autocomplete="current-password" placeholder="Password">
                                <!-- @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror -->
                            </div>   
                            <div class="remember-section-wthree">
                                <ul>
                                    <li>
                                        <label class="anim">
                                            <input type="checkbox" class="checkbox">
                                            <span> Remember me ?</span>
                                        </label>
                                    </li>
                                    <li> <a href="#">Forgot password?</a> </li>
                                </ul>
                                <div class="clear"> </div>
                            </div>
                            <input type="submit" value="Login" id="login"/> 
                        </form><!--// main-w3layouts-form -->
                    </div><!--// Main-Content-->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('public/js/particles.js')}}" type='text/javascript'></script>
<script src="{{asset('public/js/particles_app.js')}}" type=text/javascript></script>
</body></html>

   