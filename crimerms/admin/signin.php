<?php
session_start();
session_regenerate_id(true);
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) 
{
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	$sql ="SELECT ID, Password FROM tbladmin WHERE UserName=:username";
	$query=$dbh->prepare($sql);
	$query->bindParam(':username', $username, PDO::PARAM_STR);
	$query->execute();
	$result=$query->fetch(PDO::FETCH_OBJ);

	if($result && password_verify($password, $result->Password))
	{
		$_SESSION['crmsaid']=$result->ID;

		if(!empty($_POST["remember"])) {
			// Secure cookies for username
			setcookie("user_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60), "/", "", true, true);
			// Secure cookies for password
			setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60), "/", "", true, true);
		} else {
			if(isset($_COOKIE["user_login"])) {
				setcookie("user_login", "", time() - 3600, "/", "", true, true);
			}
			if(isset($_COOKIE["userpassword"])) {
				setcookie("userpassword", "", time() - 3600, "/", "", true, true);
			}
		}

		$_SESSION['login']=$_POST['username'];
		echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
	} else {
		echo "<script>alert('Invalid Details');</script>";
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
		<a href="sigin.php" class="logo pull-left">
			<strong style="font-size: 18px">Crime Record Management System</strong>
		</a>

		<div class="panel panel-sign">
			<div class="panel-title-sign mt-xl text-right">
				<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
			</div>
			<div class="panel-body">
				<form method="post">
					<div class="form-group mb-md">
						<label>Username</label>
						<div class="input-group input-group-icon">
							<input name="username" type="text" class="form-control input-md" required="true" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>"/>
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
							<input type="password" class="form-control input-md" name="password" required="true" value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>"/>
							<span class="input-group-addon">
								<span class="icon icon-md">
									<i class="fa fa-lock"></i>
								</span>
							</span>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-8">
							<div class="checkbox-custom checkbox-default">
								<input id="RememberMe" name="remember" type="checkbox" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>/>
								<label for="RememberMe">Remember Me</label>
							</div>
						</div>
					</div>
						<div class="col-sm-12 text-center">
							<div class="g-recaptcha" data-sitekey="6Lcnn5EqAAAAAPPAlTqjznykTMTrj44vj5ZVxsXM" data-callback="enablesubmitbtn"></div><br>
							<script>
									function enablesubmitbtn() {
									document.getElementById("signin").disabled = false;
									}
							</script>
							<button type="submit" id="signin" class="btn btn-primary" disabled="disabled" name="login">Sign In</button>
						</div>
					</div>
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
