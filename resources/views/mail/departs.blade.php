
<!DOCTYPE html>
<html>

<head>
    <title>Email output page</title>
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <style type="text/css">
        body {
            width: 100%;
            text-align: -webkit-center;
            vertical-align: top;
            background: #e4e4e4;

            font-family: 'Bree Serif', serif;

        }
        
        html {
            width: 100%;
            font-family: 'Bree Serif', serif;
        }
        
        table {
            font-size: 14px;
            border: 0;
            font-family: 'Bree Serif', serif;
        }
        /* ----------- responsivity ----------- */
        
        @media only screen and (max-width: 768px) {
            .container {
                width: 650px;
            }
            .4_grids {
                width: 216px !important;
            }
            .container-middle {
                width: 416px !important;
            }
            table.ban {
                background: url(images/banner.jpg);
                background-size: cover;
                height: 342px!important;
            }
        }
        
        @media only screen and (max-width: 622px) {
            /*------ top header ------ */
            .header-bg {
                width: 422px !important;
                height: 10px !important;
            }
            .main-header {
                line-height: 28px !important;
            }
            .main-subheader {
                line-height: 28px !important;
            }
            /*----- --features ---------*/
            .feature {
                width: 416px !important;
            }
            .feature-middle {
                width: 216px !important;
                text-align: center !important;
            }
            .feature-img {
                width: 422px !important;
                height: auto !important;
            }
            .container {
                width: 500px !important;
            }
            .container-middle {
                width: 416px !important;
            }
            .mainContent {
                width: 216px !important;
            }
            .main-image {
                width: 216px !important;
                height: auto !important;
            }
            .banner {
                width: 216px !important;
                height: auto !important;
            }
            /*------ sections ---------*/
            .section-item {
                width: 216px !important;
            }
            .section-img {
                width: 216px !important;
                height: auto !important;
            }
            /*------- prefooter ------*/
            .prefooter-header {
                padding: 0 10px !important;
                line-height: 24px !important;
            }
            .prefooter-subheader {
                padding: 0 10px !important;
                line-height: 24px !important;
            }
            /*------- footer ------*/
            .top-bottom-bg {
                width: 416px !important;
                height: auto !important;
            }
            .feature1 {
                width: 160px !important;
            }
            .img-responsive {
                width: 100%;
            }
        }
        
        @media only screen and (max-width: 480px) {
            .container {
                width: 160px !important;
            }
            .icon {
                width: 70%;
            }
        }
        
        @media only screen and (max-width:480px) {
            /*------ top header ------ */
            .header-bg {
                width: 280px !important;
                height: 10px !important;
            }
            .top-header-left {
                width: 260px !important;
                text-align: center !important;
            }
            .top-header-right {
                width: 260px !important;
            }
            .main-header {
                line-height: 28px !important;
                text-align: center !important;
            }
            .main-subheader {
                line-height: 28px !important;
                text-align: center !important;
            }
            /*------- header ----------*/
            .logo {
                width: 260px !important;
            }
            .nav {
                width: 260px !important;
            }
             
            /*------ sections ---------*/
            .section-item {
                width: 222px !important;
            }
            .section-img {
                width: 222px !important;
                height: auto !important;
            }
            /*------- prefooter ------*/
            .prefooter-header {
                padding: 0 10px !important;
                line-height: 28px !important;
            }
            .prefooter-subheader {
                padding: 0 10px !important;
                line-height: 28px !important;
            }
            /*------- footer ------*/
            .top-bottom-bg {
                width: 260px !important;
                height: auto !important;
            }
            table {
                width: 100% !important;
            }
            .gallery-img a img {
                height: 134px !important;
            }
            .gallery-img1 a img {
                height: 60px !important;
            }
            .gallery-img2 a img {
                height: 60px !important;
            }
            span {
                font-size: 2em!important;
            }
            a.log {
                font-size: 2.2em!important;
            }
            a.nav {
                font-size: 0.9em!important;
            }
            table.ban {
                width: 100%!important;
                height: 300px!important;
            }
            td.price {
                font-size: 1.8em!important;
            }
            td.top-text {
                height: 22px;
            }
            td.h-title {
                font-size: 1.8em!important;
            }
            td.line {
                padding: 5px 0!important;
                border-top: 1px solid #fff!important;
                border-bottom: 1px solid#fff!important;
            }
            a.learn {
                padding: 9px 12px!important;
                width: 115px!important;
            }
            td.price {
                line-height: 25px!important;
            }
            td.l-bottom {
                height: 20px!important;
            }
            table.ban2 {
                background: url(images/bottom.jpg);
                background-size: cover;
                height: 280px!important;
            }
            table.orenge {
                padding: 0 2em!important;
            }
            td.orenge-h {
                height: 30px!important;
            }
        }
        
        @media only screen and (max-width:414px) {
            td.scale-center-both img {
                height: 372px!important;
                width: 290px!important;
            }
            span {
                font-size: 2em!important;
            }
            a.log {
                font-size: 2.2em!important;
            }
            a.nav {
                font-size: 0.8em!important;
            }
            table.ban {
                width: 100%!important;
                height: 300px!important;
            }
            td.price {
                font-size: 1.8em!important;
            }
            td.top-text {
                height: 22px;
            }
            td.h-title {
                font-size: 1.8em!important;
            }
            td.line {
                padding: 5px 0!important;
                border-top: 1px solid #fff!important;
                border-bottom: 1px solid#fff!important;
            }
            a.learn {
                padding: 9px 12px!important;
                width: 115px!important;
            }
            td.price {
                line-height: 25px!important;
            }
            td.l-bottom {
                height: 20px!important;
            }
            td.scale-center-both img {
                height: 265px!important;
                width: 300px!important;
            }
            table.ban2 {
                background: url(images/bottom.jpg);
                background-size: cover;
                height: 280px!important;
            }
        }
        
        @media only screen and (max-width:320px) {
            td.scale-center-both img {
                height: 265px!important;
                width: 290px!important;
            }
            span {
                font-size: 2em!important;
            }
            a.log {
                font-size: 3em!important;
            }
            a.nav {
                font-size: 0.8em!important;
            }
            a.log span {
                font-size: 1em!important;
            }
            table.ban {
                width: 100%!important;
                height: 245px!important;
            }
            td.price {
                font-size: 1.8em!important;
            }
            td.top-text {
                height: 22px;
            }
            td.h-t {
                font-size: 2.7em!important;
            }
            table.ban2 {
                background: url(images/bottom.jpg);
                background-size: cover;
                height: 230px!important;
            }
            table.orenge {
                padding: 0 1em!important;
            }
            a.nav {
                font-size: 0.75em!important;
                margin-right: 1px!important;
            }
        }
    </style>

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <br><br>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="100%" align="center" valign="top">
                <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="container top-header-left" style="    border: 1px solid #000;">
                    <tr>
                        <td bgcolor="#5269a3">
                            <table width="560" border="0" align="center" cellpadding="0" cellspacing="0" class="mainContent">
                                <tr><td height="20"></td></tr>
                                <tr>
                                    <td class="h-t" align="center" mc:edit="title1" class="main-header" style="color: #fff; font-size:2em;font-weight:300;    font-family: 'Bree Serif', serif;">District Scheme & Resource Management</td>
                                </tr>
                                <tr>
                                    <td height="10"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td bgcolor="ffffff" height="60"></td></tr>
            

                  
				
					<tr style="background-color: #fff;">
	                	<td>
                            <center>
                            <table border="1px" style="border-collapse: collapse;width: 75%;    margin-top: -3em;border: 1px solid #818181;"
                            class="table table-striped table-bordered table-datatable table-sm">
                            <thead style="background: azure;">
                                <tr class="table-secondary">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Organisation</th>
                                    <th scope="col">is Active</th>
                    
                                </tr>
                            </thead> 
                            @if(@count($user['results']!=0))
                                @foreach($user['results'] as $key => $val)
                                <tr>
                                    <td style="    padding-left: 1em;">{{++$key}}  <input type="hidden" value="{{$val->dept_id}}" name="dept_id[]" ></td>
                                    <td style="    padding-left: 1em;">{{$val->dept_name}}</td>
                                    <td style="    padding-left: 1em;">{{$val->org_name}}</td>
                                    <td style="    padding-left: 1em;">
                                        @if($val->is_active=='1')
                                        <h3 style="color: green;">Active</h3>
                                        @else
                                        <h3 style="color: red;">In-Active</h3>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                    
                        </table><br>
                    </center>
						</td>
					</tr>
                   


                    <tr bgcolor="#5269a3">
	                	<td>
                        <table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="container-middle">
                            <tr>
                            <td style="font-family:Myriad Pro; font-size: 1em; color: #ffffff; line-height: 10px;" class="editable"><br><center>© 2020 IT-SCIENT </center><br>
                        </tr>
                       </table>
                       </td>
                    </tr>
   
                    <tr>
                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="   background: #dce6ff;">
                            <tbody>
                             <!--head_part-->
                             </tbody><tbody>
                                <tr>
                                    <td height="2"></td>
                                </tr>   
                             
                                <tr>
                                    <td>
                                        <table class="menu" style="margin: 0 auto; border-collaps:collaps; mso-table-lspace:0pt; mso-table-rspace:0pt; " align="center" border="0" cellpadding="0" cellspacing="0">
                                            
                                            <tbody>
                                                <tr>  
                                              <td class="menu" align="center">
                                                <a href="http://baba.software/"><img src="{{url('public/mail/baba.png')}}" alt="" style="width:5%; height:20px;"></a>

                                               
                                                <a class="nav" href="http://baba.software/payroll-baba.php" style="text-align:center;margin-right:12px; font-size:14px;font-family:Myriad Pro;    color: #3F51B5;letter-spacing:1px;text-decoration:none;border-left: 1px solid #3F51B5;">&nbsp;PAYROLL</a>
                                                <a class="nav" href="http://baba.software/crm-baba.php" style="text-align:center;margin-right:12px; font-size:14px;font-family:Myriad Pro;    color: #673AB7;letter-spacing:1px;text-decoration:none;border-left: 1px solid #673AB7;">&nbsp;CRM</a>

                                        
                                                <a class="nav" href="https://baba.software/lms-baba.php" style="text-align:center;margin-right:12px; font-size:14px;font-family:Myriad Pro; color: #086bb9;letter-spacing:1px;text-decoration:none;border-left: 1px solid #086bb9;">&nbsp;LMS</a>
                                                
                                                <a class="nav" href="http://baba.software/sales-baba.php" style="text-align:center;margin-right:12px; font-size:14px; font-family:Myriad Pro;color:#009688;letter-spacing:1px;text-decoration:none;border-left: 1px solid #009688;">&nbsp;SALES</a>
                                                
                                                <a class="nav" href="https://baba.software/ats.php" style="text-align:center;margin-right:12px; font-size:14px;font-family:Myriad Pro;color: #217096;letter-spacing:1px;text-decoration:none;border-left: 1px solid #217096;">&nbsp;ATS</a>
                                                <a class="nav" href="http://baba.software/hr-baba.php" style="text-align:center;margin-right:12px; font-size:14px;font-family:Myriad Pro;color: #00BCD6;letter-spacing:1px;text-decoration:none;border-left: 1px solid #00BCD6;">&nbsp;HR</a>
                                                <a class="nav" href="http://baba.software/ar.php#" style="text-align:center;margin-right:12px; font-size:14px;font-family:Myriad Pro;color: #000000;letter-spacing:1px;text-decoration:none;border-left: 1px solid #000000">&nbsp;AR</a>
                                                <a href="https://www.paatham.in/" ><img src="{{url('public/mail/paatham new.png')}}" alt="" style="width:7%; height:23px;"></a>

                                            
                                               
                                              </td>
                                            </td>
                                            
                                              
                                            </tr>
                                              <tr>
                                          <td height="10"></td>
                                        </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                                    
                            



                    <tr style="background: #5269a3;">
	                	<td>
	                		<table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="container-middle">
								<tbody><tr><td height="10"></td></tr>
								<tr align="center">
									<td style="font-family:Myriad Pro; font-size: 1em; color: #ffffff; line-height: 24px;" class="editable">
                                        || Phone: 510.516.7820 || Fax: 877.701.4872 || Email: sumant@itscient.com || Web: www.itscient.com ||
									</td>
								</tr>
								<tr><td height="10"></td></tr>
							</tbody></table>
						</td>
					</tr>
                            </tbody>
                        </table>
						</td>
					</tr>

				</table>
              
            </td>
        </tr>
	</table>
</body>
</html>