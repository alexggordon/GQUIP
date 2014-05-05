<?php 

// *************************************************************
// file: departments.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: A page used for displaying the data of all computers currently in use by a member of a given department. 
// 
// *************************************************************

// include nav bar and other default page items
include('header.php');

// check the session to see if the person is authenticated
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
}

//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

// User or Manager 
if ($_SESSION['access']==USER_PERMISSION || $_SESSION['access']==ADMIN_PERMISSION || $_SESSION['access']==FACULTY_PERMISSION) 
{

// Departments
if (!isset($_POST['departmentChoice']))
{
	$_POST['departmentChoice'] = "UNASSIGNED";
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

$securityArray[0] = 'UNASSIGNED';
$securityArray[1] = "NULL";

?>
<!-- breadcrumbs -->
<div class="row">
    <div class="large-10 large-centered columns">
    <h1>Departments</h1>
    <ul class="breadcrumbs">
      <li><a href="home.php">Home</a></li>
      <li class="current"><a href="#">Departments</a></li>
    </ul>
<!-- main content -->
<!-- begin form -->
	<form method="post" action="">
		<div class="row">
			<div class="large-10 columns">
				<select name="departmentChoice" id="departmentChoice">
					<option value='UNASSIGNED' selected="selected">Unassigned</option>

					<?php
					while($row = sqlsrv_fetch_array($populationResult))
					{
						echo "<option value=\"" . $row["OnCampusDepartment"] . "\"";
						if (isset($_POST['departmentChoice']) && $row["OnCampusDepartment"] == $_POST['departmentChoice'])
						{
							echo " selected";
						}
						echo ">" . $row["OnCampusDepartment"] . "</option>\n";
						// Use an array to get all legal values for the department search
						$securityArray[] = $row["OnCampusDepartment"];
					}
					?>

				</select>
				</div>
				<div class="large-2 columns">
				<input type="submit" name="SearchButton" value="Search" class="tiny button">
				</div>
				</div>
			  	<table cellspacing= "0" class="responsive expand">
				   	<thead>
					    <tr>
					      <th width="100">Control number</th>
					      <th width="100">Model</th>
					      <th width="150">Serial number</th>
					      <th width="125">User assigned</th>
					      <th width="110">Assignment type</th>
					      <th width="15">Inventory status</th>
					      <th width="100">Edit</th>
					    </tr>
				    </thead>
<?php
// pull department out of post data
$searchingDepartment = $_POST['departmentChoice'];

// Make sure the department used as the search parameter is valid
if (in_array($searchingDepartment, $securityArray))
{
	$departmentQuery;

	// See if the searched department is the "not assigned" choice
	if ($searchingDepartment == "UNASSIGNED")
	{
		//Find all computers NOT currently assigned to a user or department
		$departmentQuery = "SELECT *
		FROM computers cmp
		FULL JOIN hardware_assignments
		ON cmp.computer_id = hardware_assignments.computer
		FULL JOIN FacandStaff
		ON FacandStaff.ID = hardware_assignments.user_id
		WHERE NOT EXISTS (SELECT id
			FROM hardware_assignments haas
			WHERE haas.computer = cmp.computer_id
			AND haas.end_assignment IS NULL)
		AND cmp.computer_id IS NOT NULL;";
	}
	else
	{
		// SQL statement
		$departmentQuery = "SELECT *
		FROM computers
		LEFT JOIN hardware_assignments
		ON computers.computer_id = hardware_assignments.computer
		LEFT JOIN FacandStaff
		ON FacandStaff.ID = hardware_assignments.user_id
		WHERE hardware_assignments.department_id = '$searchingDepartment'
		AND hardware_assignments.end_assignment IS NULL;";
	}

	// Actually print out the results and content
	$departmentResult = sqlsrv_query($conn, $departmentQuery);

	while($row = sqlsrv_fetch_array($departmentResult))
	{
		// Parse out the content that is not human-readable
		$assignmentVal = "N/A";
		$foundUser = "N/A";
		if ($searchingDepartment != "UNASSIGNED")
		{
			$foundUser = $row['FirstName'] . " " . $row['LastName'];
			switch ($row['assignment_type'])
			{
				case DEDICATED_COMPUTER_ASSIGNMENT_TYPE:
					$assignmentVal = "Dedicated computer";
					break;
				case SPECIAL_ASSIGNMENT_TYPE:
					$assignmentVal = "Special assignment";
					break;
				case LAB_ASSIGNMENT_TYPE:
					$assignmentVal = "Lab assignment";
					break;
				case KIOSK_ASSIGNMENT_TYPE:
					$assignmentVal = "Kiosk unit";
					break;
				case PRINTER_ASSIGNMENT_TYPE:
					$assignmentVal = "Printer unit";
					break;
			}
		}
		$invStat = "No"; 
		if ($row['inventoried'] == 1)
		{
			$invStat = "Yes"; 
		}
		echo "<tr><td><a href=\"info.php?id=" . $row['control'] . "\">" . $row['control'] . "</a></td><td>" . $row['model'] . "</td><td>" . $row['serial_num'] . "</td><td>" . $foundUser . "</td><td>" . $assignmentVal . "</td><td>" . $invStat . "</td><td><a class=\"button tiny\" href=\"edit_item.php?control=" . $row['control'] . "\">Edit</a></td></tr>";
	}
}
else
{
	echo "ERROR! The department " . $searchingDepartment . " is not valid; please input a valid department name for getting information.";
}
}

sqlsrv_close( $conn);
?>
				</table>
	
			</div>
		</div>
		<!-- end form -->
	</form>
<?php
include('footer.php')
?>