<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);

			// Set the login cookie
			$cookie_value = hash('sha256', $result->ID . $_SERVER['REMOTE_ADDR']);
			setcookie('login_cookie', $cookie_value, time() + (86400 * 30), "/", "", true, true); // 86400 = 1 day



if(isset($_POST['login'])) 
{
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
	
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$sql = "SELECT ID, Password FROM tbluser WHERE Email=:email";
		$query = $dbh->prepare($sql);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		
		if($result && password_verify($password, $result->Password)) {
			// Regenerate session ID to prevent session fixation
			session_regenerate_id(true);
			$_SESSION['crmsuid'] = $result->ID;
			$_SESSION['login'] = $email;



			echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
		} else {
			echo "<script>alert('Invalid Details');</script>";
		}
	} else {
		echo "<script>alert('Invalid Email Format');</script>";
	}
}
?>
<!doctype html>
<html class="fixed">
<head>
<title>Signin | Crime Record Management System</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.css" />
<link rel="stylesheet" href="../assets/vendor/magnific-popup/magnific-popup.css" />
<link rel="stylesheet" href="../assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
<link rel="stylesheet" href="../assets/stylesheets/theme.css" />
<link rel="stylesheet" href="../assets/stylesheets/skins/default.css" />
<link rel="stylesheet" href="../assets/stylesheets/theme-custom.css">
<script src="../assets/vendor/modernizr/modernizr.js"></script>
</head>
<body>
<a href="../index.php" class="logo pull-left"><h2 style="padding-top: 30px;padding-left: 30px;color: blue"><i class="fa fa-home"></i></h2></a>
<section class="body-sign">
	<div class="center-sign">
		<a href="signin.php" class="logo pull-left">
			<strong style="font-size: 18px">Crime Record Management System</strong>
		</a>
		<hr />
		<div class="panel panel-sign">
			<div class="panel-title-sign mt-xl text-right">
				<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
			</div>
			<div class="panel-body">
				<form method="post">
					<div class="form-group mb-md">
						<label>Email</label>
						<div class="input-group input-group-icon">
							<input type="email" class="form-control input-md" placeholder="Email" required="true" name="email">
							<span class="input-group-addon">
								<span class="icon icon-md">
									<i class="fa fa-user"></i>
								</span>
							</span>
						</div>
					</div>

					<div class="form-group mb-md">
						<div class="clearfix">
							<label class="pull-left">Password</label>
							<a href="forgot-password.php" class="pull-right">Lost Password?</a>
						</div>
						<div class="input-group input-group-icon">
							<input type="password" class="form-control input-md" name="password" required="true" value="" placeholder="Password">

							<span class="input-group-addon">
								<span class="icon icon-md">
									<i class="fa fa-lock"></i>
								</span>
							</span>
						</div>
					</div>
					<!-- Google reCAPTCHA block -->
					<div class="row">
					<div class="col-sm-4 text-left">
					<div class="g-recaptcha" data-sitekey="6Lcnn5EqAAAAAPPAlTqjznykTMTrj44vj5ZVxsXM" data-callback="enablesubmitbtn"></div><br>
					<script>
						function enablesubmitbtn() {
							document.getElementById("submit").disabled = false;
						}
					</script>
					<input type="submit" id="submit" disabled="disabled"  class="btn btn-primary hidden-xs" name="login" >Sign In<br><br>
						</div>
					</div>
					<!-- End Google reCAPTCHA block -->
					<span class="mt-lg mb-lg line-thru text-center text-uppercase">
						<span>or</span>
					</span>

					<p class="text-center">Not Registered Yet <a href="signup.php">Sign up!</a>
				</form>
			</div>
		</div>
	</div>
</section>
<!-- end: page -->

<!-- Vendor -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="../assets/vendor/jquery/jquery.js"></script>
<script src="../assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="../assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="../assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="../assets/vendor/magnific-popup/magnific-popup.js"></script>
<script src="../assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
<script src="../assets/javascripts/theme.js"></script>
<script src="../assets/javascripts/theme.custom.js"></script>
<script src="../assets/javascripts/theme.init.js"></script>
</body>
</html>
