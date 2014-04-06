<?php
<<<<<<< HEAD
// *************************************************************
=======
<<<<<<< HEAD
// *************************************************************
=======

>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
>>>>>>> FETCH_HEAD
// file: login.php
// created by: Alex Gordon, Elliott Staude
// date: 02-19-2014
// purpose: allowing a user of the GQUIP database to access the content within by way of authenticate.php
// part of the collection of files for the GQUIP project, designed for Gordon College, 2013-2014
<<<<<<< HEAD
// *************************************************************
=======
<<<<<<< HEAD
// *************************************************************
=======
// 
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
>>>>>>> FETCH_HEAD
//

// include the domain authentication function
include("authenticate.php");

// if already logged in, redirect to home
if(isset($_SESSION['user'])) {
    header('Location: home.php');
}

// check to see if user is logging out
if(isset($_GET['out'])) {
    // destroy session
    session_unset();
    $_SESSION = array();
    unset($_SESSION['user'],$_SESSION['access']);
    session_destroy();
}

// check to see if login form has been submitted
if(isset($_POST['userLogin'])){
    // run information through authenticator
    if(authenticate_with_ad($_POST['userLogin'],$_POST['userPassword']))
    {
        // authentication passed
        header("Location: home.php");
        die();
    } else {
        // authentication failed
        $error = 1;
    }
}
// output error to user
if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";
?>
<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<!--Designed and Written by Alex Gordon and Elliott Staude, copyright 2013, Gordon College-->
<head>
	<meta charset="utf-8" />

	<!-- Set the viewport width to device width for mobile -->
	<meta name="viewport" content="width=device-width" />

	<title>Login</title>

	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/foundation.css">

	<script src="js/vendor/custom.modernizr.js"></script>
</head>
<body>
<br>
<br>
	<!-- Header and Nav -->
<div class="row">
	<div class="large-12 columns">
		<ul class="button-group">
			<ul class="button-group left">
				<li><img src="img/logo75.png"></li>
			</ul>
			<ul class="button-group right">
				<li><h1>GQUIP</h1></li>
			</ul>
		</ul>
	</div>
</div>
		<!-- First Band (Image) -->
 <div class="row">
	<form action="login.php" method="post" data-abide>
	 <fieldset>
		<legend>Log In</legend>
		<div class="row">
			 
			 <div class="large-9 large-centered columns">
				 <div class="row collapse">
					 <label>Username <small>required</small></label>
					 <div class="small-9 columns">
						 <input name="userLogin" type="text" placeholder="first.last" required>
						 <small class="error">A valid username is required.</small>
					 </div>
					 <div class="small-3 columns">
						 <span class="postfix">@gordon.edu</span>
					 </div>
				 </div>
			 </div>
		</div>
		<div class="row">
			<div class="large-9 large-centered columns">
				<div class="row collapse">
				<label for="password">
				 <small>required</small></label>
					<div class="small-10 columns">
						<input name="userPassword" type="password" placeholder="password" required="">
						<small class="error">You might want to check your password. It doesn't meet Gordon's standards so it shouldn't work!</small>
					</div>
					<div class="small-2 columns">
					
					</div>
					<input type="submit" name="submit" value="Submit" class="button postfix" formmethod="post" formaction="login.php" value="Log In">
				</div>
			</div>
		</div>
	</fieldset>
	</form>
</div>

	<!-- Footer -->
<div><?php include('footer.php');?></div>

</body>
</html>