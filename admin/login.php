<?php
require_once("../config/config.php");
?>
<!doctype html>
<html>
<head>
<title><?php $con = new config; echo $con->customerName; ?> Administration Login</title>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- Apple devices fullscreen -->
<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- Bootstrap responsive -->
<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
<!-- Theme CSS -->
<link rel="stylesheet" href="css/style.css">
<!-- Color CSS -->
<link rel="stylesheet" href="css/themes.css">

<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js"></script>
<script src="js/eakroko.js"></script>

<!-- Favicon -->
<link rel="shortcut icon" href="img/favicon.ico" />
<!-- Apple devices Homescreen icon -->
<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png" />
</head>

<body class='login'>
<div class="wrapper">
	<h1>
		<a><img src="img/logo-big.png" alt="" class='retina-ready' width="59" height="49"><?php echo $con->customerName; ?></a>
	</h1>
	<div class="login-body">
		<h2>SIGN IN</h2>
		
		<?php if (isset($_GET['error'])) { ?>
		
		<div class="alert alert-error">
			<strong>Login Failed</strong> please check your email and password.
		</div>
		
		<?php } ?>
		
		<form id="loginForm" action="./login_script.php" method="post">
			<div class="email">
				<input type="text" name='useremail' placeholder="Email address" class='input-block-level'>
			</div>
			<div class="pw">
				<input type="password" name="password" placeholder="Password" class='input-block-level'>
			</div>
			<div class="submit">
				<input type="submit" value="Sign me in" class='btn btn-primary'>
			</div>
		</form>
		<!--<div class="forget">
			<a href="#"><span>Forgot password?</span></a>
		</div>-->
	</div>
</div>

</body>
</html>
