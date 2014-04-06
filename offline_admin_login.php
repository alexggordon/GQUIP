<?php
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> FETCH_HEAD
// *************************************************************
// file: offline_admin_login.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: Allows the user to bypass the authentication system to be authenticated as an admin. 
// 
// *************************************************************
<<<<<<< HEAD
=======
=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
>>>>>>> FETCH_HEAD
session_start();
$_SESSION['user'] = 'alex.gordon';
$_SESSION['access'] = '3';
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
