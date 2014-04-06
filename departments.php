<?php 
<<<<<<< HEAD
// *************************************************************
// file: departments.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: A page used for displaying the data of all computers currently in use by a member of a given department. 
// 
// *************************************************************
=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
include 'header.php';
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Departments

if (!isset($_POST['departmentChoice']))
{
	$_POST['departmentChoice'] = "NULL";
}

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$populationQuery = "SELECT DISTINCT OnCampusDepartment
FROM FacStaff
WHERE OnCampusDepartment IS NOT NULL;";

//A connection to the database is established through the script open_db

include('open_db.php');

//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data

$populationResult = sqlsrv_query($conn, $populationQuery);

//This array gets all the possible departments that a search could target if
//it were valid

$securityArray[0] = "NULL";


//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

// Faculty
if($_SESSION['access']==FACULTY_PERMISSION ) {
header('Location: home.php');
}

// User or Manager 
if($_SESSION['access']==USER_PERMISSION || $_SESSION['access']==ADMIN_PERMISSION) {
?>

  <ul class="breadcrumbs">
  	<li><a href="home.php">Home</a></li>
  	<li class="current"><a href="#">Departments</a></li>
  </ul>

<div class="large-12 columns">
	<h1>Departments</h1>

	<form method="post" action="">
		<div class="row">
			<div class="large-6 columns">
				<select name="departmentChoice" id="departmentChoice">
					<option value="NULL" selected="selected">Units without a department</option>

					<?php

					while($row = sqlsrv_fetch_array($populationResult))
					{
						echo "<option value=\"" . $row["OnCampusDepartment"] . "\">" . $row["OnCampusDepartment"] . "</option>\n";
						// Use an array to get all legal values for the department search
						$securityArray[] = $row["OnCampusDepartment"];
					}

					?>

				</select>
			</div>
			<input type="submit" name="Search" class="small button">
		</div>
	</form>
</div>
<?php

$searchingDepartment = $_POST['departmentChoice'];

// Make sure the department used as the search parameter is valid
if (in_array($searchingDepartment, $securityArray))
{
	$departmentQuery = "SELECT *
	FROM hardware_assignments
	WHERE hardware_assignments.department_id = '$searchingDepartment'
	AND hardware_assignments.end_assignment IS NULL;";

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

sqlsrv_close( $conn);

include('footer.php')
?>