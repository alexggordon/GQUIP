<?php
// Test Commment
include('config.php');
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$query = "SELECT * 
FROM Computers
ORDER BY control;"

//A connection to the database is established through the script open_db

include('open_db.php');

//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data

$result = mssql_query($query);

//The connection to the database is closed through the script close_db

include('close_db.php');

$numRows = mssql_num_rows($result); 
echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>"; 

//display the results 
while($row = mssql_fetch_array($result))
{

  $cur_control = $row["control"];
  $assignmentquery = "SELECT * 
  FROM Hardware_assignments
  JOIN Users
  ON Users.id = Hardware_assignments.user_id
  WHERE Hardware_assignments.control = $cur_control
  ORDER BY control;";

  include('open_db.php');

  $assignmentresult = mssql_query($assignmentquery);

  include('close_db.php');

  echo "<li>" . $row["control"] . $row["model"] . $row["manufacturer"] . " | EDIT_BUTTON_FOR_" . $row["control"] . " | " . "</li>";

  while($assignmentrow = mssql_fetch_array($assignmentresult))
  {
  
  	echo " && " . $assignmentrow["Users.last_name"] . $assignmentrow["Users.first_name"] . $assignmentrow["Hardware_assignments.start_assignment"] . $assignmentrow["Hardware_assignments.end_assignment"];
  
  }

}

//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

if($_SESSION['access']=="3" ) {
?>



<?php
}
if($_SESSION['access']=="2" ) {
?>

<a href="#" data-dropdown="drop1">Has Dropdown</a>
<ul id="drop1" class="f-dropdown" data-dropdown-content>
	<li><a href="#">This is a link</a></li>
	<li><a href="#">This is another</a></li>
	<li><a href="#">Yet another</a></li>
</ul>
<a href="#" data-dropdown="drop2">Has Content Dropdown</a>
<div id="drop2" data-dropdown-content class="f-dropdown content">
	<p>Some text that people will think is awesome! Some text that people will think is awesome! Some text that people will think is awesome!</p>
</div>

<?php
}
if($_SESSION['access']=="1" ) {
?>


<?php
}
include('footer.php');
?>
