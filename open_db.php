<?php
$myServer = "admintrainsql.gordon.edu";
$myUser = "alex.gordon@gordon.edu";
$myPass = "7132a8b2p45kldr69_";
$myDB = "CTSEquipment"; 


$dbhandle = mssql_connect($myServer, $myUser, $myPass);
	// or die("Couldn't connect to $myServer"); 

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle); 
// or die("Couldn't open database $myDB");
?>