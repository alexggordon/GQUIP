<?php
<<<<<<< HEAD
// *************************************************************
// file: dateTime.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: This PHP file simply gets the current date and time. Used for entering items into the SQL database. 
// 
// *************************************************************

=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
$timezone = new DateTimeZone("UTC");
$date = new DateTime("now", $timezone);
$dateTime = $date->format("Y-m-d\TH:i:s");
?>