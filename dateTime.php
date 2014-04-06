<?php
// *************************************************************
// file: dateTime.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: This PHP file simply gets the current date and time. Used for entering items into the SQL database. 
// 
// *************************************************************

$timezone = new DateTimeZone("UTC");
$date = new DateTime("now", $timezone);
$dateTime = $date->format("Y-m-d\TH:i:s");
?>