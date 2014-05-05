<?php

// *************************************************************
// file: faculty_info.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: This page shows info about an individual faculty member and allows easy access to the computers assigned to them and contact information. 
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// Manager or User
if($_SESSION['access']==USER_PERMISSION || $_SESSION['access']==ADMIN_PERMISSION || $_SESSION['access']==FACULTY_PERMISSION)  {

?>
<div class="row">
<div class="large-10 large-centered columns">
<h1>Faculty Information</h1>

<ul class="breadcrumbs">
  <li><a href="home.php">Home</a></li>
  <li><a href="faculty.php">Faculty</a></li>
  <li class="current"><a href="#">Faculty Info</a></li>
</ul>

<?php
$itemID = $_GET['id'];
// submit form
if (isset($_POST['submit'])){

	//connect to the database 

	include 'open_db.php';

	//display error if database cannot be accessed
	
	if (!$conn ) 
	{
		echo('<div data-alert class="alert-box warning">
			  Sorry! Database is unavailable.
			  <a href="#" class="close">&times;</a>
			</div>');
		echo( print_r( sqlsrv_errors(), true));
	}

	//assign form input to variables
	
	include 'dateTime.php';
	$last_updated_by = $_SESSION['user'];
	$last_updated_at = $dateTime;
	$created_at = $dateTime;
	$start_assignment = $dateTime;
	$OnCampusDepartment = $_POST['OnCampusDepartment'];
	$computer = $_POST['computerChoice'];
	$full_time = 0;
	$primary_computer = 0;
	$replace_with_recycled = 0;
	if ($_POST['full_time'] = "yes")
	{
		$full_time = 1;
	}
	if ($_POST['primary_computer'] = "yes")
	{
		$primary_computer = 1;
	}
	if ($_POST['replace_with_recycled'] = "yes")
	{
		$replace_with_recycled = 1;
	}
	$assignment_type = $_POST['assignment_type'];

	//SQL query to insert variables above into table
	$hardware_assignmentSQL = "INSERT INTO dbo.hardware_assignments ([computer],[last_updated_by],[last_updated_at],[created_at],[user_id],[department],[replace_with_recycled],[full_time],[primary_computer],[assignment_type])VALUES($computer,'$last_updated_by','$last_updated_at',$itemID,'$OnCampusDepartment',$replace_with_recycled,$full_time,$primary_computer,'$assignment_type')";
	$result = sqlsrv_query($conn, $sql);

	//if the query cant be executed
	if(!$result)
	{
		echo print_r( sqlsrv_errors(), true);
		exit;
	}
	echo "<div class=\"large-8 large-centered columns\">";
	echo "<h3 class=\"large-centered\">Data successfully added</h3>";
	echo "<a class=\"button\" href=\"faculty.php\">OK</a>";
	echo "</div>";

	// close the connection
	sqlsrv_close( $conn);
}
else {
	
	include 'open_db.php';
	
	// Queries for a faculty member's info, their hardware assignments, and relevant computers information

	$facultyQuery = "SELECT *
	FROM FacandStaff
	WHERE FacandStaff.ID = $itemID;";
	
	$hardware_assignmentQuery = "SELECT hardware_assignments.id as hardware_assignment_id,
	hardware_assignments.last_updated_by as hardware_assignment_updater,
	hardware_assignments.last_updated_at as hardware_assignment_updated,
	hardware_assignments.created_at as hardware_assignment_created,
	hardware_assignments.start_assignment as start_assignment,
	hardware_assignments.end_assignment as end_assignment,		
	hardware_assignments.id as hardware_assignment_computer_id,
	hardware_assignments.assignment_type as assignment_type,
	computers.computer_id as computer_id,
	computers.control as control,
	computers.last_updated_by as computers_updater,
	computers.last_updated_at as computers_updated,
	computers.created_at as computers_created,
	computers.model as model,
	computers.hard_drive as hard_drive,
	computers.computer_type as computer_type
	FROM hardware_assignments
	LEFT JOIN computers
	ON hardware_assignments.computer = computers.computer_id
	WHERE hardware_assignments.user_id = $itemID;";
	
	$populationQuery = "SELECT computer_id, control, model
	FROM computers
	ORDER BY control;";
	
	$facultyResult = sqlsrv_query($conn, $facultyQuery);
	$hardware_assignmentResult = sqlsrv_query($conn, $hardware_assignmentQuery);
	$populationResult = sqlsrv_query($conn, $populationQuery);
	
	if(!$facultyResult)
	{
		echo print_r( sqlsrv_errors(), true);
		exit;
	}
	$item = sqlsrv_fetch_array($facultyResult, SQLSRV_FETCH_ASSOC);
	
?>

<form data-abide type="submit" name="submit" enctype='multipart/form-data' <?php echo "action=\"faculty_info.php?id=" . $itemID . "\""; ?> method="POST">
	<fieldset>
		<legend>Personal Data</legend>

		<div class="row">
			<div class="large-2 columns">
				<label><strong>First name<strong/></label>
					<label name="FirstName"><?php echo $item['FirstName']; ?></label>
			</div>
			<div class="large-2 columns">
				<label><strong>Last name<strong/></label>
					<label name="LastName"><?php echo $item['LastName']; ?></label>
			</div>
			<div class="large-3 columns">
				<label><strong>Department<strong/></label>
					<label name="OnCampusDepartment"><?php echo $item['OnCampusDepartment']; ?></label>
			</div>
			<div class="large-1 columns">
				<label><strong>DPT.<strong/></label>
					<label name="Dept"><?php echo $item['Dept']; ?></label>
			</div>
			<div class="large-3 columns">
				<label><strong>Email<strong/></label>
					<label name="Email"><?php echo $item['Email']; ?></label>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Computers assigned</legend>
		<table>
		<thead>
		<th>Control number</th>
		<th>Model</th>
		<th>Type of unit</th>
		<th>Assignment type</th>
		<th>Assignment start time</th>
		<th>Assignment end time</th>
		</thead>

	<?php
	//get the info on each of the individual's computers here
	while($row = sqlsrv_fetch_array($hardware_assignmentResult))
	{
		$comptype = "Laptop";
		if ($row['computer_type'] == "2")
		{
			$comptype = "Desktop";
		}
		if ($row['computer_type'] == "3")
		{
			$comptype = "Tablet";
		}
		$asgntype = "Dedicated unit";
		if ($row['assignment_type'] == "2")
		{
			$comptype = "Special";
		}
		if ($row['assignment_type'] == "3")
		{
			$comptype = "Lab";
		}
		if ($row['assignment_type'] == "4")
		{
			$comptype = "Kiosk";
		}
		if ($row['assignment_type'] == "5")
		{
			$comptype = "Printer";
		}
		$endasgn = "N/A";
		if ($row['end_assignment'] != NULL)
		{
			$endasgn = $row['end_assignment']->format('Y-m-d H:i:s');
		}
		$startasgn = "N/A";
		if ($row['end_assignment'] != NULL)
		{
			$startasgn = $row['start_assignment']->format('Y-m-d H:i:s');
		}
		echo "<tr><td>" . "<a href=\"/info.php?id=" . $row['control'] . "\">" . $row['control'] . "</a>" . "</td><td>" . $row['model'] . "</td><td>" . $comptype . "</td><td>" . $asgntype . "</td><td>" . $startasgn . "</td><td>" . $endasgn . "</td></tr>";
	}
	?>
		</table>
	</fieldset>

</form>
</div>
</div>

<?php
	}
	}
	// Faculty
	if($_SESSION['access']==FACULTY_PERMISSION) {
		// Faculty should not have access to this page.
		header('Location: home.php');
	}
	//footer
	include('footer.php')
?>