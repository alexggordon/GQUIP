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

// 
if($_SESSION['access']=="3"  OR $_SESSION['access']=="1" ) {

// Query
  $query = "SELECT * FROM computers
  ORDER BY last_updated_at DESC;";

  include('open_db.php');

  $result = sqlsrv_query($query);

  $numRows = sqlsrv_num_rows($result); 


  ?>



  <div class="row">
    <div class="large-10 large-centered columns">
  <table cellspacing="0">
   <thead>
    <tr>
      <th>Control</th>
      <th>Model</th>
      <th>Manufacturer</th>
    <th>Department</th>
    <th>User</th>
    <th>Assignment</th>
    <th>Options</th>
    </tr>
    </thead>
      <tr id="computer_3232" class="index-row false">
      <td><a href="computerdummydata.html">04180</a></td>
      <td>X300</td>
      <td>Lenovo</td>
      <td>President's Office</td>
      <td>Lindsay                       , D               M</td>
      <td>Full-Time Shared Non-standard</td>
      <td class="hide_for_printing"><a href="edit.html" class="button">Edit</a></td>
    </tr>
    </table>

<?php
//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

}
if($_SESSION['access']=="2" ) {

?>


<?php
}
include('footer.php');
?>
