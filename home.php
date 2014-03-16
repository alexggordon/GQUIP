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

  $result = sqlsrv_query($conn, $query);
  
  if(!$result)
  {
    echo print_r( sqlsrv_errors(), true);
    exit;
  }
  
  $numRows = sqlsrv_num_rows($result); 

  ?>


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
      <th>Computer ID</th>
      <th>Control</th>
      <th>Model</th>
      <th>Manufacturer</th>
      <th>Department</th>
      <th>User</th>
      <th>Assignment</th>
      <th></th>
    </tr>
    </thead>
    <?php
    
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
  {
    echo "<tr><td>" . $row['computer_id'] . "</td><td>" . $row['control'] . 
    "</td><td>" . $row['model'] . "</td><td>" . $row['manufacturer'] . 
    "</td><td>" . $row['department_id'] . "</td><td>" . $row['user_id'] . 
    "</td><td>" . $row['assignment_type'] . 
    "</td><td><a class=\"button\" href=\"edit_item.php?edit=" . $row['computer_id'] . "\">Edit</a></td></tr>";
  }

  ?>

    </table>



<?php
}

if($_SESSION['access']==FACULTY_PERMISSION ) {

?>


<?php
}
include('footer.php');
?>
