<?php
//<!!!> all the lines of functional code that are commented in this version are already present
// -- open_db.php/close_db.php

$myServer = "SQL05TRAIN1";
$myUser = "GORDON\alex.gordon";
$myPass = "7132a8b2p45kldr69_";
$myDB = "CTSEquipmentCTSEquipmentCTSEquipment"; 

/* Specify the server and connection string attributes. */
$serverName = "admintrainsql.gordon.edu";

/* Get UID and PWD from application-specific files.  */
$uid = 'GORDON\\alex.gordon';
$pwd = '7132a8b2p45kldr69_';
$connectionInfo = array( "UID"=>$uid,
                         "PWD"=>$pwd,
                         "Database"=>"CTSEquipment");

/* Connect using SQL Server Authentication. */
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false )
{
     echo "Unable to connect.</br>";
     die( print_r( sqlsrv_errors(), true));
}

/* Query SQL Server for the login of the user accessing the
database. */
$tsql = "SELECT CONVERT(varchar(32), SUSER_SNAME())";
$stmt = sqlsrv_query( $conn, $tsql);
if( $stmt === false )
{
     echo "Error in executing query.</br>";
     die( print_r( sqlsrv_errors(), true));
}

/* Retrieve and display the results of the query. */
$row = sqlsrv_fetch_array($stmt);
echo "User login: ".$row[0]."</br>";

/* Free statement and connection resources. */
sqlsrv_free_stmt( $stmt);
sqlsrv_close( $conn);
?>