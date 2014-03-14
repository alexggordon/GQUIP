<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION) {

$itemID = $_GET['id'];

if (isset($_POST['submit'])){

	if ($_POST['submit'] == "Save Item")
	{
		// Set the last_updated_by value

		$last_updated_by = $_SESSION['user'];

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
		/* $name = $_POST['name'];
		$software_type = $_POST['software_type'];
		*/

		//SQL query to insert variables above into table
		$sql = "UPDATE dbo.software SET index_id = $itemID, last_updated_by = '$last_updated_by', name = '$name', software_type = '$software_type' WHERE software.index_id = $itemID;";
		$result = sqlsrv_query($conn, $sql);
	
		//if the query cant be executed
		if(!$result)
		{
			echo print_r( sqlsrv_errors(), true);
			exit;
		}
		// close the connection

		sqlsrv_close( $conn);
		echo "Data successfully modified";
		echo "<a class=\"button\" href=\"students.php\">OK</a>";
    }
    if ($_POST['submit'] == "Save Item")
	{
		// Set the last_updated_by value

		$last_updated_by = $_SESSION['user'];

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
		/* $name = $_POST['name'];
		$software_type = $_POST['software_type'];
		*/

		//SQL query to insert variables above into table
		$sql = "UPDATE dbo.software SET index_id = $itemID, last_updated_by = '$last_updated_by', name = '$name', software_type = '$software_type' WHERE software.index_id = $itemID;";
		$result = sqlsrv_query($conn, $sql);
	
		//if the query cant be executed
		if(!$result)
		{
			echo print_r( sqlsrv_errors(), true);
			exit;
		}
		// close the connection

		sqlsrv_close( $conn);
		echo "Data successfully modified";
		echo "<a class=\"button\" href=\"students.php\">OK</a>";
    }
}
else {
	
	include 'open_db.php';
	
	// Queries for a student's info, their purchased licenses, and relevant software information

	$studentQuery = "SELECT *
	FROM gordonstudents
	WHERE gordonstudents.id = $itemID;";
	
	$licenseQuery = "SELECT *
	FROM licenses
	LEFT JOIN software
	ON licenses.software_id = software.index_id
	WHERE licenses.id = $itemID;";
	
	$populationQuery = "SELECT *
	FROM software;";
	
	$studentResult = sqlsrv_query($conn, $studentQuery);
	$licenseResult = sqlsrv_query($conn, $licenseQuery);
	$populationResult = sqlsrv_query($conn, $populationQuery);
	
	if(!$studentResult)
	{
		echo print_r( sqlsrv_errors(), true);
		exit;
	}
	$item = sqlsrv_fetch_array($studentResult, SQLSRV_FETCH_ASSOC);
	
?>

<div class="large-12 columns">
<h1>Student Information</h1>
<form data-abide type="submit" name="submit" enctype='multipart/form-data' <?php echo "action=\"student_info.php?id=" . $itemID . "\""; ?> method="POST">
	<fieldset>
		<legend>Personal Data</legend>

		<div class="row">
			<div class="large-4 columns">
				<label>First name</label>
					<label name="id"><?php echo $item['FirstName']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Middle name</label>
					<label name="id"><?php echo $item['MiddleName']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Last name</label>
					<label name="id"><?php echo $item['LastName']; ?></label>
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="large-4 columns">
				<label>Class</label>
					<label name="id"><?php echo $item['Class']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Is a grad student</label>
					<label name="id"><?php echo $item['grad_student']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Email</label>
					<label name="id"><?php echo $item['Email']; ?></label>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Software Licenses</legend>
		
		<?php
		while($licenseItem = sqlsrv_fetch_array($licenseResult, SQLSRV_FETCH_ASSOC))
		{
		?>
		<div class="row">
			<div class="large-4 columns">
				<label>Software name</label>
					<label name="id"><?php echo $licenseItem['software.name']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Software id</label>
					<label name="id"><?php echo $licenseItem['software.index_id']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Software type</label>
					<label name="id"><?php echo $licenseItem['software.software_type']; ?></label>
			</div>
		</div>
		<?php
		}
		?>
		
	</fieldset>
		<div class="large-12 columns">
		<div class="row" align="center">
		<input type="submit" name="submit" value="Save Items" class="button" formmethod="post">
		</div>
	</div>
	
	<fieldset>
	<legend>Add a license</legend>
		
	<div class="row">
	<div class="large-8 columns">
	<select name="licenseChoice" id="licenseChoice">

	<?php

	while($row = sqlsrv_fetch_array($populationResult))
	{
		echo "<option value=\"" . $row["index_id"] . "\">ID: " . $row["index_id"] . " - " . $row['name'] . "(" . $row['software_type'] . ")</option>\n";
		// Use an array to get all legal values for the department search
		$securityArray[] = $row["index_id"];
	}

	?>

	</select>
	</div>
	<input type="submit" name="submit" value="Add Item" class="button" formmethod="post">
	</div>
		
	</fieldset>
		<div class="large-12 columns">
		<div class="row" align="center">
		<a class="button" href="students.php">Cancel</a>
		</div>
	</div>
</form>
</div>

<?php
	}
	}
	// Faculty
	if($_SESSION['access']==FACULTY_PERMISSION OR $_SESSION['access']==USER_PERMISSION ) {
		// Faculty and users should not have access to this page. 
		header('Location: home.php');
	}
	//footer
	include('footer.php')
?>