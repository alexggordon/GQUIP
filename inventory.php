<?php 

// *************************************************************
// file: inventory.php
// created by: Alex Gordon, Elliott Staude
// date: 04-22-2014
// purpose: A page used for modifying the inventory status of a computer. 
// 
// *************************************************************

// include nav bar and other default page items
include('header.php');

// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
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


//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

// Faculty
if($_SESSION['access']==FACULTY_PERMISSION ) {
header('Location: home.php');
}

// User or Manager 
if($_SESSION['access']==USER_PERMISSION || $_SESSION['access']==ADMIN_PERMISSION) 
{

// Departments
if (!isset($_POST['departmentChoice']))
{
	$_POST['departmentChoice'] = "UNASSIGNED";
}
else
{
	$_GET['dept'] = NULL;
}
if (isset($_GET['dept']))
{
	$_POST['departmentChoice'] = urldecode($_GET['dept']);
}

$filterVar = "ALL";
if (isset($_GET['filter']))
{
	$filterVar = "NONINV";
	if ($_GET['filter'] == "inven")
	{
		$filterVar = "INV";
	}
}

// Checks for inventory changes

if (isset($_POST['UpdateInventory']))
{

	$addList;
	$removeList;
	$toggleList;
	if (isset($_POST['checkInven']))
	{
		if (isset($_POST['startInven']))
		{
			$addList = array_diff($_POST['checkInven'], $_POST['startInven']);
			$removeList = array_diff($_POST['startInven'], $_POST['checkInven']);
			$toggleList = array_merge($addList, $removeList);
		}
		else
		{
			$addList = $_POST['checkInven'];
			$toggleList = $addList;
		}
	}
	else
	{
		if (isset($_POST['startInven']))
		{
			$removeList = $_POST['startInven'];
			$toggleList = $removeList;
		}
	}
	if (isset($toggleList) && count($toggleList) > 0)
	{
		$alterQuery = "UPDATE computers
					SET inventoried = inventoried ^ 1
					WHERE (";
		if (count($toggleList) > 1)
		{
			for($alterInd = 0; $alterInd < (count($toggleList) - 1); $alterInd++)
			{
				$alterQuery .= "computer_id = " . $toggleList[$alterInd] . " OR ";
			}
		}
		$alterQuery .= "computer_id = " . $toggleList[(count($toggleList) - 1)] . ");";
		
		// Run query
		$alterResult = sqlsrv_query($conn, $alterQuery);

		// Make sure that the query went through successfully

		if(!$alterResult)
		{
			echo print_r( sqlsrv_errors(), true);
			exit;
		}
	}
}

?>
<!-- breadcrumbs -->
<div class="row">
    <div class="large-10 large-centered columns">
    <h1>Inventory Status</h1>
    <ul class="breadcrumbs">
      <li><a href="home.php">Home</a></li>
      <li class="current"><a href="#">Inventory</a></li>
    </ul>
    </div>
    </div>
<!-- main content -->
<!-- begin form -->
	<form method="post" action="">
		<div class="row">
			<div class="large-10 large-centered columns">
				<select name="departmentChoice" id="departmentChoice">
					<option value='UNASSIGNED' selected="selected">Units without a department</option>

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
				<input type="submit" name="SearchButton" value="Search" class="tiny button">
				<dl class="sub-nav">
					<dt>Limit results:</dt>
					<dd <?php if ($filterVar == "ALL"){ echo "class=\"active\""; } ?>><a <?php if (isset($_GET['dept'])){echo "href=\"inventory.php?dept=" . urlencode($_GET['dept']) . "\"";} else{echo "href=\"inventory.php?dept=" . urlencode($_POST['departmentChoice']) . "\"";} ?>>All units</a></dd>
					<dd <?php if ($filterVar == "INV"){ echo "class=\"active\""; } ?>><a <?php if (isset($_GET['dept'])){echo "href=\"inventory.php?filter=inven&dept=" . urlencode($_GET['dept']) . "\"";} else {echo "href=\"inventory.php?filter=inven&dept=" . urlencode($_POST['departmentChoice']) . "\"";} ?>>Inventoried units</a></dd>
					<dd <?php if ($filterVar == "NONINV"){ echo "class=\"active\""; } ?>><a <?php if (isset($_GET['dept'])){echo "href=\"inventory.php?filter=noninven&dept=" . urlencode($_GET['dept']) . "\"";} else{echo "href=\"inventory.php?filter=noninven&dept=" . urlencode($_POST['departmentChoice']) . "\"";} ?>>Non-inventoried units</a></dd>
				</dl>
			</div>
		</div>
	  	<div class="row">
		  	<div class="large-10 large-centered columns">
			  	<table cellspacing= "0" class="responsive expand">
				   	<thead>
					    <tr>
					      <th width="100">Control number</th>
					      <th width="200">Model</th>
					      <th width="175">Serial number</th>
					      <th width="200">User assigned</th>
					      <th width="100">Assignment type</th>
					      <th width="25">Inventory status</th>
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
		LEFT JOIN hardware_assignments
		ON cmp.computer_id = hardware_assignments.computer
		LEFT JOIN FacandStaff
		ON FacandStaff.ID = hardware_assignments.user_id
		WHERE NOT EXISTS (SELECT * 
			FROM hardware_assignments haas
			WHERE haas.computer = cmp.computer_id
			AND haas.end_assignment IS NULL)";
			if ($filterVar == "ALL")
			{
				$departmentQuery .= ";";
			}
			else
			{
				if ($filterVar == "INV")
				{
					$departmentQuery .= " AND cmp.inventoried = 1;";
				}
				else
				{
					$departmentQuery .= " AND cmp.inventoried = 0;";
				}
			}
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
		AND hardware_assignments.end_assignment IS NULL";
		if ($filterVar == "ALL")
		{
			$departmentQuery .= ";";
		}
		else
		{
			if ($filterVar == "INV")
			{
				$departmentQuery .= " AND computers.inventoried = 1;";
			}
			else
			{
				$departmentQuery .= " AND computers.inventoried = 0;";
			}
		}
	}

	// Actually print out the results and content
	$departmentResult = sqlsrv_query($conn, $departmentQuery);
	sqlsrvErrorLinguist($departmentResult, "Problem with searching for departments");

	while($row = sqlsrv_fetch_array($departmentResult))
	{
		// Parse out the content that is not human-readable
		$assignmentVal = "N/A";
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
		$invBoxInput = "<input type='checkbox' name='checkInven[]' value=" . $row['computer_id'] . ">"; 
		if ($row['inventoried'] == 1)
		{
			$invBoxInput = "<input type='checkbox' name='checkInven[]' value=" . $row['computer_id'] . " checked><input type='hidden' name='startInven[]' value=" . $row['computer_id'] . ">"; 
		}
		echo "<tr><td><a href=\"info.php?id=" . $row['control'] . "\">" . $row['control'] . "</a></td><td>" . $row['model'] . "</td><td>" . $row['serial_num'] . "</td><td>" . $row['FirstName'] . " " . $row['LastName'] . "</td><td>" . $assignmentVal . "</td><td>" . $invBoxInput . "</td></tr>";
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
				<input type="submit" name="UpdateInventory" value="Update inventory" class="button large-4 columns">
	
			</div>
		</div>
		<!-- end form -->
	</form>
<?php
include('footer.php')
?>