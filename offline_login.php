<?php
session_start();
$_SESSION['user'] = 'alex.gordon';
$_SESSION['access'] = '1';
echo "You're all set!";
?>
