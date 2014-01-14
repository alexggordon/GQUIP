<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<!--Designed and Written by Alex Gordon and Elliott Staude, Copy write 2013, Gordon College-->
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
<?php
include("authenticate.php");
// check to see if user is logging out
if(isset($_GET['out'])) {
		// destroy session
		session_unset();
		$_SESSION = array();
		unset($_SESSION['user'],$_SESSION['access']);
		session_destroy();
}
if(isset($_POST['userLogin'])){
		if(authenticate($_POST['userLogin'],$_POST['userPassword']))
		{
				header("Location: home.php");
				die("Unable to connect to home");
		} else {
				$error = 1;
		}
}
if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";
if (isset($_GET['out'])) echo "Logout successful<br />";?>
<?php
include("authenticate.php");
 
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
    if(authenticate($_POST['userLogin'],$_POST['userPassword']))
    {
        // authentication passed
        header("Location: index.php");
        die();
    } else {
        // authentication failed
        $error = 1;
    }
}
 
// output error to user
if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br /-->";
 
// output logout success
if (isset($_GET['out'])) echo "Logout successful";
?>
	<!-- Header and Nav -->

<div class="row">
		<div class="large-3 columns">
			<h1><img src="img/logo.gif"></h1>
		</div>
		<div class="large-9 columns">
			<ul class="button-group right">
		<li><h1>G-Quip</h1></li>
			</ul>
		</div>
	</div>
		<!-- First Band (Image) -->
 <div class="row">
	<form method="post" action="login.php" data-abide>
	 <fieldset>
		<legend>Log In</legend>
		<div class="row">
			 
			 <div class="large-9 large-centered columns">
				 <div class="row collapse">
					 <label>Username <small>required</small></label>
					 <div class="small-9 columns">
						 <input name="userLogin" type="text" placeholder="first.last" pattern="alpha_numeric" required>
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
				<label for="password">Password <small>required</small></label>
					<div class="small-10 columns">
						<input type="password" id="password" pattern="password_" placeholder="password" name="userPassword" required="">
						<small class="error">You might want to check your password. It doesn't meet Gordon's standards so it shouldn't work!</small>
					</div>
					<div class="small-2 columns">
<!--             <a href="home.php" class="button postfix">Log In</a> -->
								<input type="submit" name="submit" value="Submit" class="button postfix" value="Log In">
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	</form>
</div>

	<!-- Footer -->
<div><?php include('footer.php');?></div>
	<script>
		$(document)
			.foundation()
			.foundation('abide', {
				patterns: {
					password_: /(?=^.{7,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
				}
			});
	</script>


	
</body>
</html>