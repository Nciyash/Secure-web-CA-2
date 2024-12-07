<?php 
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

function generateToken() {
	return bin2hex(random_bytes(32));
}

if (!isset($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = generateToken();
}

if(isset($_POST['submit'])) {
	if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
		echo "<script>alert('Invalid CSRF token');</script>";
		exit;
	}

	$fname = htmlspecialchars($_POST['fname']);
	$mobno = htmlspecialchars($_POST['mobno']);
	$email = htmlspecialchars($_POST['email']);
	$password = $_POST['password'];

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "<script>alert('Invalid email format');</script>";
		exit;
	}

	if (strlen($password) > 72) {
		echo "<script>alert('Password is too long');</script>";
		exit;
	}

	$passwordHash = password_hash($password, PASSWORD_DEFAULT);
	$ret = "SELECT Email FROM tbluser WHERE Email=:email";
	$query = $dbh->prepare($ret);
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->execute();
	$results = $query->fetchAll(PDO::FETCH_OBJ);

	if ($query->rowCount() == 0) {
		$sql = "INSERT INTO tbluser (FullName, MobileNumber, Email, Password) VALUES (:fname, :mobno, :email, :password)";
		$query = $dbh->prepare($sql);
		$query->bindParam(':fname', $fname, PDO::PARAM_STR);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->bindParam(':mobno', $mobno, PDO::PARAM_INT);
		$query->bindParam(':password', $passwordHash, PDO::PARAM_STR);
		$query->execute();
		$lastInsertId = $dbh->lastInsertId();

		if ($lastInsertId) {
			echo "<script>alert('You have signed up successfully');</script>";
			echo "<script>window.location.href ='signin.php'</script>";
		} else {
			echo "<script>alert('Something went wrong. Please try again');</script>";
			echo "<script>window.location.href ='signup.php'</script>";
		}
	} else {
		echo "<script>alert('Email-id already exists. Please try again');</script>";
	}
}
?>
<!doctype html>
<html class="fixed">
<head>
	<title>Sign Up | Crime Record Management System</title>
	<!-- Web Fonts  -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" href="../assets/vendor/magnific-popup/magnific-popup.css" />
	<link rel="stylesheet" href="../assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
	<link rel="stylesheet" href="../assets/stylesheets/theme.css" />
	<link rel="stylesheet" href="../assets/stylesheets/skins/default.css" />
	<link rel="stylesheet" href="../assets/stylesheets/theme-custom.css">
	<script src="../assets/vendor/modernizr/modernizr.js"></script>
	<script type="text/javascript">
		// For Email availability
		function checkAvailability() {
			$("#loaderIcon").css('display', 'block');
			jQuery.ajax({
				url: "check_availability.php",
				data: 'emailid=' + $("#email").val(),
				type: "POST",
				success: function(data) {
					$("#user-availability-status").html(data);
					$("#loaderIcon").css('display', 'none');
				},
				error: function() {}
			});
		}
	</script>
</head>
<body>
<a href="../index.php" class="logo pull-left"><h2 style="padding-top: 30px;padding-left: 30px;color: blue"><i class="fa fa-home"></i></h2></a>
<section class="body-sign">
	<div class="center-sign">
		<a href="signup.php" class="logo pull-left">
			<strong style="font-size: 18px">Crime Record Management System</strong>
		</a>
		<hr />
		<div class="panel panel-sign">
			<div class="panel-title-sign mt-xl text-right">
				<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign Up</h2>
			</div>
			<div class="panel-body">
				<form method="post">
					<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
					<div class="form-group mb-md">
						<label>Full Name</label>
						<input id="fname" type="text" class="form-control input-md" placeholder="Full Name" name="fname" required="true">
					</div>
					<div class="form-group mb-md">
						<label>E-mail Address</label>
						<input id="email" type="email" class="form-control input-md" placeholder="Email" name="email" required="true" onBlur="return checkAvailability()">
						<span id="user-availability-status"></span>
					</div>
					<div class="form-group mb-md">
						<label>Mobile Number</label>
						<input id="mobno" type="text" class="form-control input-md" placeholder="Mobile" name="mobno" maxlength="10" pattern="[0-9]+" required="true">
					</div>
					<div class="form-group mb-md">
						<label>Password</label>
						<input id="password" type="password" class="form-control input-md" placeholder="Password" name="password" required="true">
					</div>
					<div class="row">
						<div class="col-sm-4 text-left">
							<button type="submit" class="btn btn-primary hidden-xs" id="submit" name="submit">Sign Up</button>
						</div>
					</div>
					<span class="mt-lg mb-lg line-thru text-center text-uppercase">
						<span>or</span>
					</span>
					<p class="text-center">Already have an account? <a href="signin.php">Sign In!</a>
				</form>
			</div>
		</div>
	</div>
</section>
<!-- end: page -->
<!-- Vendor -->
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