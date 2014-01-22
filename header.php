<?php
include('config.php');
if(!isset($_SESSION['user'])) die('Forbidden');

if($_SESSION['access']=="3" ) {
     echo 'WebManagers – page for the administrator';
}
if($_SESSION['access']=="2" ) {
     echo 'WebUsers – page for the users ';
}
if($_SESSION['access']=="1" ) {
     echo 'WebUsers – page for the users ';
}
?>