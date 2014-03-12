<?php session_start();
include 'header.php';
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Departments

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$populationQuery = "SELECT DISTINCT OnCampusDepartment
FROM FacandStaff
WHERE FacandStaff.OnCampusDepartment IS NOT NULL;";

//A connection to the database is established through the script open_db

include('open_db.php');

//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data

$populationResult = sqlsrv_query($conn, $populationQuery);

//This array gets all the possible departments that a search could target if
//it were valid

$securityArray[0] = "unassigned";


//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

// Faculty
if($_SESSION['access']=="2" ) {
}

// User or Manager 
if($_SESSION['access']=="1" || $_SESSION['access']=="3") {
?>

  <div class="row">
    <div class="large-10 large-centered columns">
    <h1>Departments</h1>
    </div>
  </div>

  	<div class="row">
    <div class="large-10 large-centered columns">
  	<table cellspacing="0">

	<form method="post" action="">
	<select name="departmentChoice" id="departmentChoice">
	<option value="unassigned" selected="selected">Unassigned</option>

<?php

while($row = sqlsrv_fetch_array($populationResult))
{
	echo "<option value=\"" . $row["OnCampusDepartment"] . "\">" . $row["OnCampusDepartment"] . "</option>\n";
	// Use an array to get all legal values for the department search
	$securityArray[] = $row["OnCampusDepartment"];
}

?>

	</select>

	<input type="submit" name="Search">

	</form>

<?php

	$searchingDepartment;

	if (isset($_POST['departmentChoice']))
	{
		$searchingDepartment = $_POST['departmentChoice'];

		// Make sure the department used as the search parameter is valid
		if (in_array($searchingDepartment, $securityArray))
		{
			/* <!!!> WE NEED HARDWARE_ASSIGNMENTS FOR THIS TO WORK </!!!> $departmentQuery = "SELECT *
			FROM hardware_assignments;";

			$departmentResult = sqlsrv_query($conn, $departmentQuery);

			while($row = sqlsrv_fetch_array($departmentResult))
			{
				echo $row['id'] . " - assignment of unit " . $row['control'] . " to user " . $row['user_id'] . "\n";
			}*/
		}
		else
		{
			echo "ERROR! " . $_POST['departmentChoice'] . " is not a valid department; please input a valid department name for getting information.";
		}
	}
}
//The connection to the database is closed through the script close_db
include('close_db.php');
?>

  </table>
  </div>
  </div>

<?php
include('footer.php')
?>