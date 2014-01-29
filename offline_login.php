<?php
session_start();
$_SESSION['user'] = 'alex.gordon';
$_SESSION['access'] = '2';
echo "You're all set!";
// 1 is the user login
// 2 is the faculty login
// 3 is the administrator login
if($_SESSION['access']=="3" ) {
	echo " You're testing as an administrator";
}

// Faculty
if($_SESSION['access']=="2" ) {
	echo " You're testing as a faculty";
}

// User
if($_SESSION['access']=="1" ) {
	echo " You're testing as an user";
}
?>
