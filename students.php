<?php
include('header.php');
include('getPage.php');
include ('paginate.php');
include('open_db.php');
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// query 1, for total number of items from DB
$countQuery = "SELECT ID FROM dbo.gordonstudents";
$count = sqlsrv_query($conn, $countQuery, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
if ( !$count )
  die( print_r( sqlsrv_errors(), true));
$num_rows = sqlsrv_num_rows( $count );

// query 2, for all items we need
$query = "SELECT  ID, FirstName, LastName, Email, Class FROM dbo.gordonstudents ORDER BY FirstName ASC";
$result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
if ( !$result )
  die( print_r( sqlsrv_errors(), true));

// 
// data for pagination

// This is the syntax we pass to the paginator.php to give us our numbers
// the left page link
$aLeft = 'students.php?&page=';
// the right page link. If blank, then left will be used. 
$aRight = '';
// do we want to show the fancy arrows?
$sArrows = TRUE;
// the number of items in the database
$num_rows = sqlsrv_num_rows( $count );
// rows of items per page
$rowsPerPage = 25;
// number of pages equals number of items divided by how many we show per page
$numOfPages = ceil($num_rows/$rowsPerPage);

// some quick math to find out what page we're on
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

// This finds out our current starting place (or item)
if (isset($_GET['page'])) {
  $pCurrent = $_GET['page'];
} else {
  $pCurrent = 0;
}


// gets the correct SQL Data. This makes a call to the getPage.php function
$page = getPage($result, $pageNum, $rowsPerPage);

// If faculty or user
if($_SESSION['access']=="3"  OR $_SESSION['access']=="1" ) {
  ?>
  
  <ul class="breadcrumbs">
    <li><a href="home.php">Home</a></li>
    <li class="current"><a href="#">Students</a></li>
  </ul>
  
  <div class="row">
    <div class="large-10 large-centered columns">
    <h1>Users</h1>
    </div>
    </div>
  <div class="row">
    <div class="large-12 large-centered columns">
  <table cellspacing="0">
   <thead>
    <tr>
      <th width="100">First Name</th>
      <th width="100">Last Name</th>
      <th width="100">ID</th>
      <th width="200">Class</th>
    <th width="200">Email</th>
    </tr>
    </thead>
  <?php

  foreach($page as $row)
  {
     echo "<tr><td><a href=\"/student_info.php?&id=" . $row[0] . "\">" . $row[1] . "</a></td><td><a href=\"/student_info.php?&id=" . $row[0] . "\">" . $row[2] . "</a></td><td><a href=\"/student_info.php?&id=" . $row[0] . "\">" . $row[0] . "</a></td><td>" . $row[4] . "</td><td>" . $row[3] . "</td></tr>";
  }
  
  ?>
  </table>
  </div>
  </div>
  <div class="row">
  <div class="large-9 large-centered columns">
<?php

// Spit out the pagination info. This makes a call to the paginate.php function. 
echo PHPagination($pCurrent, $num_rows, $aLeft, $aRight, $rowsPerPage, $sArrows);

// close the database connection
sqlsrv_close( $conn );


?>
</div>
</div>
<?php
}
// Faculty
if($_SESSION['access']=="2" ) {
?>

<?php
}


include('footer.php')
?>
