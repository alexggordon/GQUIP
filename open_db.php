<?php
<<<<<<< HEAD
// *************************************************************
// file: open_db.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The open_db function simply connects to the database and opens the connection to the database. It’s imported when we want to connect and simply saves us from 
// duplicated the code a 100 times. 
// *************************************************************
=======
<<<<<<< HEAD
// *************************************************************
// file: 
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: 
// 
// *************************************************************
=======

>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
>>>>>>> FETCH_HEAD
$serverName = "sql05train1.gordon.edu";
$connectionInfo = array(
'Database' => 'CTSEquipment');
$conn = sqlsrv_connect( $serverName, $connectionInfo);

//select a database to work with
// or die("Couldn't open database $myDB");
?>