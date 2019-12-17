<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - District Scheme &amp; Resource Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="{{asset('public/css/login-style.css')}}" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>
	.content-detail h4 {
		font-size: 2.8em;
		font-family: 'Bree Serif', serif;
		color: #fff;
		line-height: 1.5em;
		text-shadow: 1px 5px 4px #000;
		text-align: center;
		margin-top: 0.5em;
	}

	.content-detail p {
		font-size: 1em;
		font-family: 'Bree Serif', serif;
		color: #fff;
		line-height: 1.5em;
		text-align: center;
		margin-top: 2em;
		line-height: 2em;
		letter-spacing: 1px;
	}
</style>
<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<div class="row">
			<div class="col-md-6"><br><br>
				<div class="content-detail" style="padding: 3em;margin-top:-4em;">
					<h4>District Scheme & Resource Management</h4>
					<p>A single dashboard where you can monitor and evaluate the Schemes & Programs with respect to
						time, geography, department and asset progress that are implemented in the district. Also, you
						can further review and generate reports. And can print, email and export the data in PDF or
						Excel format. It provides you with 24x7 availability to all the department growing individually
						for the growth of the district. Finally, this analysis will aid better decision support for the
						growth and development of our people.</p><br>
					<center><img src="{{url('public/images/icons circle.png')}}" style="height: 500px; margin-top: 1em;"></center>
				</div>
			</div>

			<div class="col-md-6" style="background: #0000004a;min-height: 1011px;margin-top: -3em;width: 50%;">
				<!-- <div class="main-agileinfo" style="    width: 67%;">
					<div class="agileits-top">
						<center><img src="http://jiada.baba.software/public/form/images/toplogo.png" style="height: 100%;"></center>
						<form action="#" method="post">
							<input class="text" type="text" name="Username" placeholder="Username" required="">
							<input class="text" type="password" name="password" placeholder="Password" required="">
							<div class="wthree-text">
								<ul>
									<li>
										<label class="anim" style="display: flex;">
											<input type="checkbox" class="checkbox" required="">
											<span>&nbsp;&nbsp;&nbsp; Remember me ?</span>
										</label>
									</li>
									<li><a href="#">Forgot password?</a> </li>
								</ul>
								<div class="clear"> </div>
							</div>
							<input type="submit" value="LOGIN">
						</form>
						<p>Don't have an Account? <a href="#"> Signup Now!</a></p>
					</div>
				</div> -->

				<!---728x90---><br><br><br><br><br>
			<div class="content-bottom">
				<center><img src="https://cdn.s3waas.gov.in/s3b337e84de8752b27eda3a12363109e80/uploads/2018/03/2018030571.png"></center><br>
				<form method="POST" action="{{ route('login') }}">
                @csrf
					<div class="field-group">
						<span class="fa fa-user" aria-hidden="true"></span>
						<div class="wthree-field">
							<input name="email" id="text1" type="text" value="{{ old('email') }}" placeholder="Username" autocomplete="off" required aria-required="true" placeholder="email" required autofocus>
						</div>
					</div>
					<div class="field-group">
						<span class="fa fa-lock" aria-hidden="true"></span>
						<div class="wthree-field">
							<input name="password" id="myInput" type="Password" required="" aria-required="true" placeholder="Password">
                        </div>
					</div>
					<div class="wthree-field">
						<button type="submit" class="btn">Login</button>
					</div>
					<ul class="list-login">
						<li class="switch-agileits">
							<label class="switch">
								<input type="checkbox">
								<span class="slider round"></span>
								keep Logged in
							</label>
						</li>
						<li>
							<a href="javascript:void();" class="text-right">forgot password?</a>
						</li>
						<li class="clearfix"></li>
					</ul>
				</form>
			</div>
		</div>
		<!---728x90--->
			</div>
		</div>
	</div>
	<ul class="w3lsg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
	</div>
	<!-- //main -->
</body>
</html>

   