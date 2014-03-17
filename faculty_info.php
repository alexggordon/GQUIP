<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']==USER_PERMISSION) {

?>

<div class="large-12 columns">
<h1>Faculty Information</h1>

<?php
$itemID = $_GET['id'];

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
				<label>First name</label>
					<label name="FirstName"><?php echo $item['FirstName']; ?></label>
			</div>
			<div class="large-2 columns">
				<label>Last name</label>
					<label name="LastName"><?php echo $item['LastName']; ?></label>
			</div>
			<div class="large-3 columns">
				<label>Department</label>
					<label name="OnCampusDepartment"><?php echo $item['OnCampusDepartment']; ?></label>
			</div>
			<div class="large-1 columns">
				<label>DPT.</label>
					<label name="Dept"><?php echo $item['Dept']; ?></label>
			</div>
			<div class="large-3 columns">
				<label>Email</label>
					<label name="Email"><?php echo $item['Email']; ?></label>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Hardware Assignments</legend>
		
		<?php
		while($hardware_assignmentItem = sqlsrv_fetch_array($hardware_assignmentResult, SQLSRV_FETCH_ASSOC))
		{
		?>		
		<div class="row">
			<div class="large-3 columns">
				<label>Control number</label>
					<label name="computers_name"><?php echo $hardware_assignmentItem['control']; ?></label>
			</div>
			<div class="large-3 columns">
				<label>Model</label>
					<label name="computers_type"><?php echo $hardware_assignmentItem['model']; ?></label>
			</div>
			<div class="large-3 columns">
				<label>Hard drive size</label>
					<label name="computers_type"><?php echo $hardware_assignmentItem['hard_drive']; ?></label>
			</div>
			<div class="large-3 columns">
				<label>Type of unit</label>
					<label name="computers_type"><?php echo $hardware_assignmentItem['computer_type']; ?></label>
			</div>						
			<div class="large-2 columns">
				<label>Date assigned</label>
					<label name="date_sold"><?php echo $hardware_assignmentItem['start_assignment']->format('Y-m-d H:i:s'); ?></label>
			</div>
			<?php
			if ($hardware_assignmentItem['end_assignment'] != "NULL")
			{
			?>
			<div class="large-2 columns">
				<label>Date assigned</label>
					<label name="date_sold"><?php echo $hardware_assignmentItem['end_assignment']->format('Y-m-d H:i:s'); ?></label>
			</div>
			<?php
			}
				echo "<a href=\"edit_assignment.php?edit=" . $hardware_assignmentItem['id'] . "\" class=\"button\">Edit</a>";
			?>
			</div>
		<?php
		}
		?>

	</fieldset>

	<fieldset>
		<legend>Add an assignment</legend>
		
		<div class="row">
			<div class="large-4 columns">
				<label>Computer assigned</label>
				<select name="computerChoice" id="computerChoice">

					<?php

					while($row = sqlsrv_fetch_array($populationResult))
					{
						echo "<option value=\"" . $row["computer_id"] . "\">ID: " . $row["computer_id"] . " - Control number: " . $row['control'] . " (" . $row['model'] . ")" . "</option>\n";
						// Use an array to get all legal values for the computers search
						$securityArray[] = $row["computer_id"];
					}

					?>

				</select>
			</div>
			<div class="large-3 columns">
				<label>Assignment stats</label>
				<input type='checkbox' name='full_time' value='yes'><span class="label radius">Is full time assignment</span><br />
				<input type='checkbox' name='primary_computer' value='yes'><span class="label radius">Is primary computer of the user</span><br />
				<input type='checkbox' name='replace_with_recycled' value='yes'><span class="label radius">Replace computer with recycled unit</span><br />
			</div>
			<div class="large-3 columns">
				<label>Type of assignment</label>
				<input type='radio' name='assignment_type' value='Dedicated computer' selected>Dedicated computer<br />
				<input type='radio' name='assignment_type' value='Special assignment'>Special assignment<br />
				<input type='radio' name='assignment_type' value='Lab computer'>Lab computer<br />
				<input type='radio' name='assignment_type' value='Kiosk computer'>Kiosk computer<br />
				<input type='radio' name='assignment_type' value='Printer'>Printer<br />
			</div>
			<div class="large-2 columns">
				<input type="submit" name="submit" value="Add Item" class="button expand" formmethod="post">
			</div>
		</div>

	</fieldset>
	<div class="large-12 columns">
	<div class="row" align="center">
	<a class="button" href="faculty.php">Back</a>
	</div>
	</div>
</form>
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