<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION) {

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
	$date_sold = $dateTime;
	$seller = $_POST['seller'];
	$software_id = $_POST['licenseChoice'];

	//SQL query to insert variables above into table
	$sql = "INSERT INTO dbo.licenses ([last_updated_by],[date_sold],[seller],[software_id],[id])VALUES('$last_updated_by','$date_sold','$seller','$software_id','$itemID')";
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
else {
	
	include 'open_db.php';
	
	// Queries for a student's info, their purchased licenses, and relevant software information

	$studentQuery = "SELECT *
	FROM gordonstudents
	WHERE gordonstudents.id = $itemID;";
	
	$licenseQuery = "SELECT licenses.index_id as license_id,
	licenses.last_updated_by as license_updater,
	licenses.date_sold as date_sold,
	licenses.id as id,
	licenses.seller as seller,
	licenses.software_id as software_id,
	software.index_id as index_id,
	software.last_updated_by as software_updater,
	software.name as name,
	software.software_type as software_type
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
					<label name="FirstName"><?php echo $item['FirstName']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Middle name</label>
					<label name="MiddleName"><?php echo $item['MiddleName']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Last name</label>
					<label name="LastName"><?php echo $item['LastName']; ?></label>
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="large-4 columns">
				<label>Class</label>
					<label name="Class"><?php echo $item['Class']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Is a grad student</label>
					<label name="grad_student"><?php echo $item['grad_student']; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Email</label>
					<label name="Email"><?php echo $item['Email']; ?></label>
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
			<div class="large-3 columns">
				<label>Software name</label>
					<label name="software_name"><?php echo $licenseItem['name']; ?></label>
			</div>
			<div class="large-3 columns">
				<label>Software type</label>
					<label name="software_type"><?php echo $licenseItem['software_type']; ?></label>
			</div>
			<div class="large-2 columns">
				<label>Seller</label>
					<label name="edited_seller"><?php echo $licenseItem['seller']; ?></label>
			</div>
			<div class="large-2 columns">
				<label>Time sold</label>
					<label name="date_sold"><?php echo $licenseItem['date_sold']->format('Y-m-d H:i:s'); ?></label>
			</div>
			<?php
			echo "<a href=\"edit_license.php?edit=" . $licenseItem['license_id'] . "\" class=\"button\">Edit</a>";
			?>
			</div>
		<?php
		}
		?>

	</fieldset>

	<fieldset>
	<legend>Add a license</legend>
		
	<div class="row">
	<div class="large-8 columns">
	<label>Software licensed</label>
	<select name="licenseChoice" id="licenseChoice">

	<?php

	while($row = sqlsrv_fetch_array($populationResult))
	{
		echo "<option value=\"" . $row["index_id"] . "\">ID: " . $row["index_id"] . " - " . $row['name'] . " (" . $row['software_type'] . ")</option>\n";
		// Use an array to get all legal values for the department search
		$securityArray[] = $row["index_id"];
	}

	?>

	</select>
	<label>Seller to student</label>
	<input type="text" name="seller" placeholder="John Smith" required>
	</div>
	<input type="submit" name="submit" value="Add Item" class="button" formmethod="post">
	</div>

	</fieldset>
		<div class="large-12 columns">
		<div class="row" align="center">
		<a class="button" href="students.php">Back</a>
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