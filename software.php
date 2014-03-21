<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$query = "SELECT *
FROM Software
ORDER BY name;";

//A connection to the database is established through the script open_db

include('open_db.php');

//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data

$result = sqlsrv_query($conn, $query);

$numRows = sqlsrv_num_rows($result); 

// If user or administrator
if($_SESSION['access']==ADMIN_PERMISSION  OR $_SESSION['access']==USER_PERMISSION ) {
  ?>
  
  <ul class="breadcrumbs">
  	<li><a href="home.php">Home</a></li>
  	<li class="current"><a href="#">Software</a></li>
  </ul>
  
  <div class="large-10 large-centered columns">
	  <h1>Software</h1>
	  <div class="row">
		  <table>
			<thead>
			<tr>
			  <th>Software ID</th>
			  <th>Name</th>
			  <th>Software type</th>
			  <th>Last updated by</th>
			  <th>Last updated at</th>
			  <th>Created at</th>
			  <th></th>
			</tr>
			</thead>

		  <?php

		  while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		  {
			 echo "<tr><td>" . $row['index_id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['software_type'] . "</td><td>" . $row['last_updated_by'] . 
			 "</td><td>" . $row['last_updated_at']->format('Y-m-d H:i:s') . "</td><td>" . $row['created_at']->format('Y-m-d H:i:s') . "</td><td><a class=\"button\" href=\"edit_software.php?edit=" . $row['index_id'] . "\">Edit</a></td></tr>";
		  }
  
		  ?>

		  </table>
	  </div>
  </div>
  
<?php
}
// Faculty
if($_SESSION['access']==FACULTY_PERMISSION ) {
?>

<?php
header('Location: home.php');
}
?>

<?php
//The connection to the database is closed through the script close_db

include('close_db.php');

include('footer.php');
?>