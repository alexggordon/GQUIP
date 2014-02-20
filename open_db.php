<?php



$serverName = "sql05train1.gordon.edu";
$connectionInfo = array(
'Database' => 'CTSEquipment');
$conn = sqlsrv_connect( $serverName, $connectionInfo);

//select a database to work with
// or die("Couldn't open database $myDB");
?>