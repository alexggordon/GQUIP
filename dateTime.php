<?php

// *************************************************************
// file: dateTime.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: This PHP file simply gets the current date and time. Used for entering items into the SQL database. 
// 
// *************************************************************

// set timezone
$timezone = new DateTimeZone("UTC");
// set date
$date = new DateTime("now", $timezone);
// current time
$dateTime = $date->format("Y-m-d\TH:i:s");
?>