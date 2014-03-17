<?php

// file: home.php
// created by: Alex Gordon, Elliott Staude
// date: 02-27-2014
// purpose: allowing a user of the GQUIP database to access the content within by way of authenticate.php
// part of the collection of files for the GQUIP project, designed for Gordon College, 2013-2014
// 
include('header.php');
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}

  //The following segments consult with the permissions of the user and
  //accordingly render the page and/or allow the user to perform certain
  //actions based on the permissions level

if($_SESSION['access']==ADMIN_PERMISSION  OR $_SESSION['access']==USER_PERMISSION ) {
include 'paginate.php';
include 'getPage.php';
// Query
  $query = "SELECT hardware_assignments.id as hardware_assignment_id,
  hardware_assignments.last_updated_by as hardware_assignment_updater,
  hardware_assignments.last_updated_at as hardware_assignment_updated,
  hardware_assignments.created_at as hardware_assignment_created,
  hardware_assignments.start_assignment as start_assignment,
  hardware_assignments.end_assignment as end_assignment,    
  hardware_assignments.id as hardware_assignment_computer_id,
  hardware_assignments.assignment_type as assignment_type,
  hardware_assignments.user_id as user_id,
  hardware_assignments.department_id as department_id,
  computers.computer_id as computer_id,
  computers.control as control,
  computers.last_updated_by as computers_updater,
  computers.last_updated_at as computers_updated,
  computers.created_at as computers_created,
  computers.manufacturer as manufacturer,
  computers.model as model,
  computers.hard_drive as hard_drive,
  computers.computer_type as computer_type
  FROM hardware_assignments
  RIGHT JOIN computers
  ON hardware_assignments.computer = computers.computer_id
  ORDER BY control;";



  include('open_db.php');



  // query 2, for all items we need

  $result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
  if ( !$result ) {
    echo print_r( sqlsrv_errors(), true);
    exit;
  }
  
  $num_rows = sqlsrv_num_rows($result); 
  // 
  // data for pagination

  // This is the syntax we pass to the paginator.php to give us our numbers
  // the left page link
  $aLeft = 'home.php?&page=';
  // the right page link. If blank, then left will be used. 
  $aRight = '';
  // do we want to show the fancy arrows?
  $sArrows = TRUE;
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



  ?>

  <ul class="breadcrumbs">
    <li class="current"><a href="#">Home</a></li>
  </ul>

  <div class="row">
    <div class="large-10 large-centered columns">
    <h1>Home</h1>
    </div>
  </div>

  <div class="row">
    <div class="large-10 large-centered columns">
  <table cellspacing="0">
   <thead>
    <tr>
      <th width="100">Control</th>
      <th width="200">Model</th>
      <th width="100">Manufacturer</th>
      <th width="200">Department</th>
      <th width="100">User ID</th>
      <th width="100"></th>
    </tr>
    </thead>
    <?php
    

    foreach($page as $row)
    {
      echo "<tr><td><a href=\"info.php?id=" . $row[11] . "\">" . $row[11] . "</a></td><td>" . $row[16] . "</td><td>" . $row[15] . "</td><td>" . $row[9] . "</td><td>" . $row[8] . "</td><td><a class=\"button tiny\" href=\"edit_item.php?id=" . $row[11] . "\">Edit</a></td></tr>";
    }

  ?>

    </table>
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
if($_SESSION['access']==FACULTY_PERMISSION ) {

?>


<?php
}
include('footer.php');
?>
