<?php

// *************************************************************
// file: student_info.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: This page shows info about an individual student and allows easy access to the software assigned to them and contact information. 
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']==USER_PERMISSION) {

?>
<div class="row">
<div class="large-10 large-centered columns">

<h1>Student Information</h1>

<ul class="breadcrumbs">
  <li><a href="home.php">Home</a></li>
  <li><a href="students.php">Students</a></li>
  <li class="current"><a href="#">Student Info</a></li>
</ul>

<?php
$itemID = $_GET['id'];

$licenseKinds[] = NULL;

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
	$date_sold = $dateTime;
	$seller = $_POST['seller'];
	$software_id = $_POST['licenseChoice'];
	$softwareTypeResult = "0";

	$possessedSoftwareTypeQuery = "SELECT DISTINCT software_type
	FROM software
	RIGHT JOIN licenses
	ON software.index_id = licenses.software_id
	WHERE licenses.id = $itemID;";

	$findSubmittedSoftwareQuery = "SELECT DISTINCT software_type
	FROM software
	WHERE software.index_id = $software_id;";
	
	$findSubmittedSoftwareResult = sqlsrv_query($conn, $findSubmittedSoftwareQuery);
	$softwareStats = sqlsrv_fetch_array($findSubmittedSoftwareResult, SQLSRV_FETCH_ASSOC);

	$softwareTypeResult = sqlsrv_query($conn, $possessedSoftwareTypeQuery);
	while($thisHereItem = sqlsrv_fetch_array($softwareTypeResult, SQLSRV_FETCH_ASSOC))
	{
		$licenseKinds[] = $thisHereItem['software_type'];
	}
	if (array_search($softwareStats['software_type'], $licenseKinds) === false)
	{
		//SQL query to insert variables above into table
		$sql = "INSERT INTO dbo.licenses ([last_updated_by],[last_updated_at],[created_at],[date_sold],[seller],[software_id],[id])VALUES('$last_updated_by','$last_updated_at','$created_at','$date_sold','$seller','$software_id','$itemID')";
		$result = sqlsrv_query($conn, $sql);

		//if the query cant be executed
		if(!$result)
		{
			echo print_r( sqlsrv_errors(), true);
			exit;
		}
		echo "<div class=\"row\">";
		echo "<div class=\"large-10 large-centered columns\">";
		echo "<h3 class=\"large-centered\">Data successfully added</h3>";
		echo "<a class=\"button expand\" href=\"student_info.php?&id=" . $itemID . " \">OK</a>";
		echo "</div>";
		echo "</div>";
	}
	else
	{
		echo "<div class=\"large-8 large-centered columns\">";
		echo "<h3 class=\"large-centered\">Sorry - that type of license is already possessed by the student</h3>";
		echo "<a class=\"button\" href=\"students.php\">OK</a>";
		echo "</div>";
	}
	// close the connection
	sqlsrv_close( $conn);
}
else {
	
	include 'open_db.php';
	
	// Queries for a student's info, their purchased licenses, and relevant software information

	$studentQuery = "SELECT *
	FROM gordonstudents
	WHERE gordonstudents.id = $itemID;";
	
	$licenseQuery = "SELECT licenses.index_id as license_id,
	licenses.last_updated_by as license_updater,
	licenses.last_updated_at as license_updated,
	licenses.created_at as license_created,
	licenses.date_sold as date_sold,
	licenses.id as id,
	licenses.seller as seller,
	licenses.software_id as software_id,
	software.index_id as index_id,
	software.last_updated_by as software_updater,
	software.last_updated_at as software_updated,
	software.created_at as software_created,
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

<form data-abide type="submit" name="submit" enctype='multipart/form-data' <?php echo "action=\"student_info.php?id=" . $itemID . "\""; ?> method="POST">
	<fieldset>
		<legend>Personal Data</legend>

		<div class="row">
			<div class="large-2 columns">
				<label><strong>First Name</strong></label>
					<label name="FirstName"><?php echo $item['FirstName']; ?></label>
			</div>
			<div class="large-2 columns">
				<label><strong>Middle name</strong></label>
					<label name="MiddleName"><?php echo $item['MiddleName']; ?></label>
			</div>
			<div class="large-2 columns">
				<label><strong>Last name</strong></label>
					<label name="LastName"><?php echo $item['LastName']; ?></label>
			</div>
			<div class="large-1 columns">
				<label><strong>Class</strong></label>
					<label name="Class"><?php echo $item['Class']; ?></label>
			</div>
			<div class="large-2 columns">
				<label><strong>Grad Student</strong></label>
					<label name="grad_student"><?php echo $item['grad_student']; ?></label>
			</div>
			<div class="large-3 columns">
				<label><strong>Email</strong></label>
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
				<label><strong>Software Name</strong></label>
					<label name="software_name"><?php echo $licenseItem['name']; ?></label>
			</div>
			<div class="large-3 columns">
				<label><strong>Software Type</strong></label>
					<label name="software_type"><?php echo $licenseItem['software_type']; ?></label>
			</div>
			<div class="large-2 columns">
				<label><strong>Seller</strong></label>
					<label name="edited_seller"><?php echo $licenseItem['seller']; ?></label>
			</div>
			<div class="large-2 columns">
				<label><strong>Time Sold</strong></label>
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
			<div class="large-4 columns">
				<label>Software licensed</label>
				<select name="licenseChoice" id="licenseChoice">

					<?php

					while($row = sqlsrv_fetch_array($populationResult))
					{
						echo "<option value=\"" . $row["index_id"] . "\">" . $row['name'] . "</option>\n";
						// Use an array to get all legal values for the software search
						$securityArray[] = $row["index_id"];
					}

					?>

				</select>
			</div>
			<div class="large-4 columns">
				<label>Seller to student</label>
				<input type="text" name="seller" placeholder="John Smith" required>
			</div>
			<div class="large-4 columns">
				<input type="submit" name="submit" value="Add Item" class="button expand" formmethod="post">
			</div>
		</div>

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