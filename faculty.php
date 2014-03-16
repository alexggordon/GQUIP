<?php
include('header.php');
include('getPage.php');
include ('paginate.php');
include('open_db.php');
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// queries
$countQuery = "SELECT ID FROM dbo.FacandStaff";
$count = sqlsrv_query($conn, $countQuery, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
if ( !$count )
  die( print_r( sqlsrv_errors(), true));
$num_rows = sqlsrv_num_rows( $count );


$query = "SELECT ID, FirstName, LastName, Email, Dept FROM dbo.FacandStaff ORDER BY FirstName ASC";
$result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
if ( !$result )
  die( print_r( sqlsrv_errors(), true));



// data for pagination
$num_rows = sqlsrv_num_rows( $count );
$rowsPerPage = 25;
$numOfPages = ceil($num_rows/$rowsPerPage);

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if ($page == 0) {
      $pageNum = 1;
    } else {
      $pageNum = (($page / $rowsPerPage) + 1);
    }
} else {
  $pageNum = 1;
}

if (isset($_GET['page'])) {
  $pCurrent = $_GET['page'];
} else {
  $pCurrent = 0;
}

$aLeft = 'users.php?&page=';
$aRight = '';

$sMultiplier = 25;
$sArrows = TRUE;



// get page, this parses the page info
$page = getPage($result, $pageNum, $rowsPerPage);





if($_SESSION['access']=="3"  OR $_SESSION['access']=="1" ) {
  ?>
  <div class="row">
    <div class="large-10 large-centered columns">
    <h1>Faculty Users</h1>
    </div>
    </div>
  <div class="row">
    <div class="large-10 large-centered columns">
  <table cellspacing="0">
   <thead>
    <tr>
      <th width="100">First Name</th>
      <th width="100">Last Name</th>
      <th width="100">ID</th>
      <th width="200">Dept.</th>
    <th width="200">Email</th>
    </tr>
    </thead>
<a href=""></a>
  <?php

  foreach($page as $row)
  {
     echo "<tr><td><a href=\"/faculty_info.php?id=" . $row[0] . "\">" . $row[1] . "</a></td><td><a href=\"/faculty_info.php?id=" . $row[0] . "\">" . $row[2] . "</a></td><td><a href=\"/faculty_info.php?id=" . $row[0] . "\">" . $row[0] . "</a></td><td>" . $row[4] . "</td><td>" . $row[3] . "</td></tr>";
  }

 //$row['index'] the index here is a field name
  
  ?>

  </table>
  </div>
  </div>
  <div class="row">
  <div class="large-9 large-centered columns">
<?php


echo PHPagination($pCurrent, $num_rows, $aLeft, $aRight, $sMultiplier, $sArrows);

sqlsrv_close( $conn );

}
?>
</div>
</div>
<?php
// Faculty
if($_SESSION['access']=="2" ) {
?>

<?php
}
?>



<?php
include('footer.php')
?>
