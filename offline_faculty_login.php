<?php

// *************************************************************
// file: offline_faculty_login.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: Allows the user to bypass the authentication system to be authenticated as a faculty or staff. 
// 
// *************************************************************


session_start();
$_SESSION['user'] = 'alex.gordon';
$_SESSION['access'] = '2';
include('symbolic_values.php');
echo "You're all set!";
// 1 is the user login
// 2 is the faculty login
// 3 is the administrator login
if($_SESSION['access']==ADMIN_PERMISSION ) {
	echo " You're testing as an administrator.";
}

// Faculty
if($_SESSION['access']==FACULTY_PERMISSION ) {
	echo " You're testing as a faculty member.";
}

// User
if($_SESSION['access']==USER_PERMISSION ) {
	echo " You're testing as a user.";
}
?>
