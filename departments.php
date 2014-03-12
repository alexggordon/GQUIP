<?php 
include 'header.php';
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Departments

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$populationQuery = "SELECT DISTINCT department_id
FROM hardware_assignments;";

//A connection to the database is established through the script open_db

include('open_db.php');

//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data

$populationResult = sqlsrv_query($conn, $populationQuery);

//This array gets all the possible departments that a search could target if
//it were valid

$securityArray[] = array(0 => "unassigned");


//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

// Faculty
if($_SESSION['access']=="2" ) {
}

// User or Manager 
if($_SESSION['access']=="1" || $_SESSION['access']=="3") {
?>

<form method="post" action="">
<div class="row">
<div class="small-6 columns">
<select name="departmentChoice" id="departmentChoice">
<option value="unassigned" selected="selected">Unassigned units</option>

<?php

while($row = sqlsrv_fetch_array($populationResult))
{
	echo "<option value=\"" . $row["department_id"] . "\">" . $row["department_id"] . "</option>\n";
	// Use an array to get all legal values for the department search
	$securityArray[] = $row["department_id"];
}

?>

</select>
</div>
<input type="submit" name="Search">
</div>
</form>

<?php

$searchingDepartment = $_POST['departmentChoice'];

// Make sure the department used as the search parameter is valid
if (in_array($searchingDepartment, $securityArray))
{
	$departmentQuery = "SELECT *
	FROM hardware_assignments
	WHERE department_id = $searchingDepartment
	AND assignment_end IS NULL;";

	$departmentResult = sqlsrv_query($conn, $departmentQuery);

	while($row = sqlsrv_fetch_array($departmentResult))
	{
		echo $row['id'] . " - assignment of unit " . $row['control'] . " to user " . $row['user_id'] . "\n";
	}
}
else
{
	echo "ERROR! Department not valid; please input a valid department name for getting information.";
}
}
//The connection to the database is closed through the script close_db
include('close_db.php');

include('footer.php')
?>