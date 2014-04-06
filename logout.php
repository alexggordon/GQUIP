<?php
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> FETCH_HEAD
// *************************************************************
// file: logout.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: We store the users authentication in an encrypted session variable (read cookie). The logout.php function deletes this cookie so as to remove all stored session 
// variables and directs the to the login page as they are no longer authenticated. 
// *************************************************************
<<<<<<< HEAD
=======
=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
>>>>>>> FETCH_HEAD
session_start();
unset($_SESSION);
session_destroy();
session_write_close();
header('Location: login.php');
die;
?>