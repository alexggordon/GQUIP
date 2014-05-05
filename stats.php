<?php 
// *************************************************************
// file: 
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: 
// 
// *************************************************************

// include nav bar and other default page items
include('header.php');
include('getPage.php');
include ('paginate.php');
include('open_db.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}

$countQuery Ω	 "SELECT ID FROM dbo.gordonstudents";
$count = sqlsrv_query($conn, $countQuery, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
if ( !$count )
  die( print_r( sqlsrv_errors(), true));
$num_rows = sqlsrv_num_rows( $count );

 ?>


 <?php
 }


 include('footer.php')
 ?>

