<?php 
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
include('header.php');
include('getPage.php');
include ('paginate.php');
include('open_db.php');
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}

$countQuery = "SELECT ID FROM dbo.gordonstudents";
$count = sqlsrv_query($conn, $countQuery, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
if ( !$count )
  die( print_r( sqlsrv_errors(), true));
$num_rows = sqlsrv_num_rows( $count );

 ?>


 <?php
 }


 include('footer.php')
 ?>

