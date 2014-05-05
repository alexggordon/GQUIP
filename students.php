<?php

// *************************************************************
// file: students.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: A page used for displaying the data of and relevant to all students currently in Gordon College’s Active Directory.
// 
// *************************************************************

// include nav bar and other default page items
include('header.php');
include('getPage.php');
include('paginate.php');
include('open_db.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// query 1, for total number of items from DB
$countQuery = "SELECT ID FROM dbo.gordonstudents";
$count = sqlsrv_query($conn, $countQuery, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
if ( !$count )
  die( print_r( sqlsrv_errors(), true));
$num_rows = sqlsrv_num_rows( $count );

// Set up ordering

// Find the requested content ordering
// When the sorting is the same kind as before, then "reverse" the ordering from previous
$sortMarker;
$orderingWatch;
if (isset($_GET['sorting']))
{
    $sortMarker = $_GET['sorting'];
    switch ($sortMarker)
    {   
      case "FirstName":
        $orderingWatch = "case when FirstName is null then 1 else 0 end, FirstName";
        break;    
      case "FlipFirstName":
        $orderingWatch = "FirstName DESC";
        break;
      case "LastName":
        $orderingWatch = "case when LastName is null then 1 else 0 end, LastName";
        break;
      case "FlipLastName":
        $orderingWatch = "LastName DESC";
        break;
      case "ID":
        $orderingWatch = "case when ID is null then 1 else 0 end, ID";
        break;
      case "FlipID":
        $orderingWatch = "ID DESC";
        break;
      case "Class":
        $orderingWatch = "case when Class is null then 1 else 0 end, Class";
        break;
      case "FlipClass":
        $orderingWatch = "Class DESC";
        break;
      case "Email":
        $orderingWatch = "case when Email is null then 1 else 0 end, Email";
        break;
      case "FlipEmail":
        $orderingWatch = "Email DESC";
        break;
    }
}
else
{
    $sortMarker = "LastName";
    $orderingWatch = "case when LastName is null then 1 else 0 end, LastName";
}

// query 2, for all items we need
$query = "SELECT ID, FirstName, LastName, Email, Class 
          FROM dbo.gordonstudents 
          ORDER BY " . $orderingWatch . ";";
$result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
if ( !$result )
  die( print_r( sqlsrv_errors(), true));

// 
// data for pagination

// This is the syntax we pass to the paginator.php to give us our numbers
// the left page link
$aLeft = 'students.php?sorting=' . $sortMarker . '&page=';
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
  
  
  <div class="row">
    <div class="large-10 large-centered columns">
    <h1>Students</h1>
    <ul class="breadcrumbs">
      <li><a href="home.php">Home</a></li>
      <li class="current"><a href="#">Students</a></li>
    </ul>
  
  <table cellspacing="0">
   <thead>
    <tr>
      <th width="150"><?php if ($sortMarker == "FirstName"){echo "<a href='students.php?sorting=FlipFirstName'>";} else {echo "<a href='students.php?sorting=FirstName'>";} ?>First Name</a></th>
      <th width="150"><?php if ($sortMarker == "LastName"){echo "<a href='students.php?sorting=FlipLastName'>";} else {echo "<a href='students.php?sorting=LastName'>";} ?>Last Name</a></th>
      <th width="100"><?php if ($sortMarker == "Class"){echo "<a href='students.php?sorting=FlipClass'>";} else {echo "<a href='students.php?sorting=Class'>";} ?>Class</a></th>
      <th width="300"><?php if ($sortMarker == "Email"){echo "<a href='students.php?sorting=FlipEmail'>";} else {echo "<a href='students.php?sorting=Email'>";} ?>Email</a></th>
    </tr>
    </thead>
<a href=""></a>
  <?php

  foreach($page as $row)
  {
     echo "<tr><td><a href=\"/student_info.php?&id=" . $row[0] . "\">" . $row[1] . "</a></td><td><a href=\"/student_info.php?&id=" . $row[0] . "\">" . $row[2] . "</a></td><td>" . $row[4] . "</td><td>" . $row[3] . "</td></tr>";
  }
  
  ?>
  </table>
  </div>
  </div>
  <div class="row">
  <div class="large-12 large-centered columns">
<?php

// Spit out the pagination info. This makes a call to the paginate.php function. 
echo PHPagination($pCurrent, $num_rows, $aLeft, $aRight, $rowsPerPage, $sArrows);

// close the database connection
sqlsrv_close( $conn );

}
?>
</div>
</div>
<?php

include('footer.php')
?>