<?php 

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

