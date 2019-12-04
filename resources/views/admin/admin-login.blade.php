<!DOCTYPE HTML>
<html>
<head>  
    <!--  -->
    <title>LogIn  Page Of Seraikela</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <link href="css/external.css" rel="stylesheet" type="text/css" />

    <style type="text/css">

        form {
            width: 100%;
            padding: 1em;
            position: relative;
        }
        fieldset legend {
            margin: 0.5em;
            /* background: #5fb485; */
            font-size: 14px;
            color: #212121;
            padding: .3em.5em;
            width: 18%;
            color: #fff;
            text-align: -webkit-center;
            background-image: linear-gradient(to right, #00863d , #90ca4c);
            /* font-weight: 600; */
        }
        fieldset {
            border: none;
            padding: 1em;
            /* margin: 1.5em 0 0; */
            background: rgb(255, 255, 255);
        }

        fieldset.invalid {
            border: none;
        }


        @media all and (min-width: 400px) {
            form {
              width: 400px;
              margin: 0 auto;
          }
      }
      @media all and (min-width: 800px) {
        /*-- w3layouts --*/
        form {
            width: 100%;
        }
        @media (max-width:768px){
            form {
                width: 500px;
            }
        }
        @media (max-width:736px){
            .main h1 {
                font-size: 2.2em;
            }
        }
        @media (max-width:640px){
            .main {
                padding: 3em 0;
            }
            .main h1 {
                margin-bottom: 0;
            }
        }
        @media (max-width: 480px){
            form {
                width: 460px;
            }
            .main h1 {
                font-size: 2em;
            }
            fieldset legend {
                font-size: 1.1em;
            }
        }
        @media (max-width: 414px){
            form {
                width: 390px;
            }
        }
        @media (max-width: 384px){
            form {
                width: 365px;
                margin: 0 auto;
            }
        }
        @media (max-width: 375px){
            form {
                width: 355px;
                padding-top: 0;
            }
        }
        @media (max-width: 320px){
            .main h1 {
                font-size: 1.6em;
            }
            fieldset legend {
                font-size: 1em;
            }
            form {
                width: 303px;
            }

        }



        .login {
            display: -webkit-flex;
            display: -webkit-box;
            display: -moz-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
            align-items: center;
            -webkit-box-pack: center;
            -moz-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
        }

        h1 {
            font-size: 3.2em;
            font-weight: 300;
            text-transform: capitalize;
            color: #000000;
            text-shadow: 1px 1px 1px #00ba9d;
            letter-spacing: 1px;
            margin: 0.5em 1vw 1.6em;
            text-align: center;
            font-family: 'Niconne', cursive;
        }
        .login form {
            position: relative;
            max-width: 500px;
            margin: 0 5vw;
            padding: 3.5vw;
            border: none;
            background: -webkit-linear-gradient(43deg, #ffffff 44%, #f3f3f3 42%);
            box-sizing: border-box;
            display: flex;
            border-top: 0px solid #00863d;
            display: -webkit-flex;
            flex-wrap: wrap;
            border: 3px solid #00863d;
        }
        .login label {
            font-size: 15px;
            color: #000;
            float: left;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: capitalize;
            letter-spacing: 1px;
            cursor: pointer;
        }

        .agile-field-txt {
            flex-basis: 100%;
            -webkit-flex-basis: 100%;
            margin-bottom: 1.5em;
            clear: both;
        }

        .login label i {
            font-size: 1.1em;
            margin-right: 3px;
            color: #00863d;
        }

        .login input[type="text"],
        .login input[type="password"] {
            width: 100%;
            color: #000;
            outline: none;
            font-size: 16px;
            letter-spacing: 0.5px;
            /* line-height: 25px; */
            padding: 15px;
            box-sizing: border-box;
            border: none;
            box-shadow: 3px 0px 12px rgba(0, 0, 0, 0.49);
            -webkit-appearance: none;
            font-family: 'Source Sans Pro', sans-serif;
            background: #fff;
        }

        .check1 {
            text-align: left;
        }

        label.check {
            float: none;
            ;
        }

        .agile_label {
            float: left;
            margin: 10px 0 0;
        }

        .agile-right {
            float: right;
            margin: 10px 0 0;
        }

        .agile-right a {
            font-size: 14px;
            color: #000;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: capitalize;
            letter-spacing: 2px;
        }

        .login.w3l-sub {
            margin-top: 1em;
            flex-basis: 100%;
            -webkit-flex-basis: 100%;
        }

        .login input[type=submit] {
            color: #000;
            width: 100%;
            padding: 0.5em 0;
            margin-top: 2em;
            font-size: 1.1em;
            letter-spacing: 2px;
            cursor: pointer;
            border: none;
            border-radius: 2px;
            border: 5px double #00863d;
            outline: none;
            background:#fff;
            font-family: 'Source Sans Pro', sans-serif;
            transition: 0.5s all ease;
            -webkit-transition: 0.5s all ease;
            -moz-transition: 0.5s all ease;
            -o-transition: 0.5s all ease;
            -ms-transition: 0.5s all ease;
        }

        .login input[type=submit]:hover {
            background: #ffffff;
            color: #000;
        }

        .bot {
            margin-top: 1em;
            width: 100%;
        }

        .login img {
            position: absolute;
            top: -15%;
            left: 40%;
            /* border: 7px solid #00ba9d; */
            border-left-color: #fff;
            border-right-color: #fff;
            /* background: #ffffff; */
        }

        /* switch */

        label.switch {
            position: relative;
            display: inline-block;
            height: 23px;
            padding-left: 3.8em;
            cursor: pointer;
            color: #000;
        }

        li:nth-child(2) a,
        label.switch {
            text-transform: capitalize;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 3px;
            width: 20%;
            background-color: #777;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 10px;
            width: 10px;
            left: 4px;
            bottom: 5px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #363af4;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(18px);
            -ms-transform: translateX(18px);
            transform: translateX(18px);
        }

        .form-end {
            float: right;
            width: 35%;
        }

        /*--responsive--*/

        @media(max-width:1920px) {
            h1 {
                font-size: 3.5vw;
            }
        }

        @media(max-width:1024px) {
            h1 {
                font-size: 4.5vw;
            }
        }

        @media(max-width:800px) {
            h1 {
                font-size: 5vw;
            }
            .w3ls-login img {
                width: 14%;
                left: 42%;
            }
        }

        @media(max-width:480px) {
            h1 {
                font-size: 2.5em;
            }
            login form {
                padding: 7.5vw;
            }
        }

        @media(max-width:414px) {
            .login img {
                top: -8%;
            }
        }

        @media(max-width:440px) {
            h1 {
                font-size: 2.3em;
            }
            .parent {
                display: block;
            }
        }

        @media(max-width:384px) {
            .agile_label,.agile-right {
                float: none;
            }
        }

        @media(max-width:320px) {
            h1 {
                font-size: 1.8em;
            }
            .login form {
                padding: 25px 8px;
            }
        }

        /*--//responsive--*/
    </style>
</head>
<body style="background:#e8e7e7";>
    <div class="login box box--big" style="margin-top:11%;">
            <!-- form starts here  --> 
        <form class="cmxform form-horizontal tasi-form" id="loginForm" method="POST" action="{{ route('login')}}" novalidate="novalidate">
            @csrf
            <img src="https://cdn.s3waas.gov.in/s3b337e84de8752b27eda3a12363109e80/uploads/2018/03/2018030571.png" alt="" />

           <div class="agile-field-txt">
            <label><i class="fa fa-user" aria-hidden="true"></i> Username / Email</label>
                <input id="login" type="text" class="form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : ''}}"
                name="login" value="{{ old('username') ?: old('email') }}" required autofocus">

                @if ($errors->has('username') || $errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                </span>
                @endif
            </div>


            <div class="agile-field-txt">
                <label><i class="fa fa-lock" aria-hidden="true"></i> password</label>
                <!-- <input type="password" name="password" placeholder=" " required="" id="myInput" /> -->

                <input  type="password" id="myInput" name="password" required="" aria-required="true" autocomplete="current-password" placeholder="Password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror



                <div class="agile_label">
                    <input id="check3" name="check3" type="checkbox" value="show password" onclick="myFunction()">
                    <label class="check" for="check3">Show password</label>
                </div>


                <div class="agile-right">
                    <a href="#">forgot password?</a>
                </div>
            </div>

            <div class="bot">
                <div class="switch-agileits">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span> keep me signed in
                    </label>
                </div>
                <a href="#"><input type="submit" value="LOGIN"></a>
            </div>
        </form>
    </div>
</body>
</html>

    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    <script>
