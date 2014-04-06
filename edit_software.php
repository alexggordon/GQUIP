<?php
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> FETCH_HEAD
// *************************************************************
// file: edit_software.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose:  The page used to edit information on a software item.
// 
// *************************************************************
<<<<<<< HEAD
=======
=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
>>>>>>> FETCH_HEAD
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION) {

$itemID = $_GET['edit'];

if (isset($_POST['submit'])){

	if ($_POST['submit'] == "Delete Item")
	{
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
		$deletionSQL = "DELETE FROM dbo.software WHERE index_id = $itemID;";

		$deletionAttempt = sqlsrv_query($conn, $deletionSQL);

		if(!$deletionAttempt)
		{
			echo print_r( sqlsrv_errors(), true);
			exit;
		}
		// close the connection

		sqlsrv_close($conn);
		echo "Data successfully removed";
		echo "<a class=\"button\" href=\"students.php\">OK</a>";
	}
	else
	{

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
		$name = $_POST['name'];
		$software_type = $_POST['software_type'];

		//SQL query to insert variables above into table
		$sql = "UPDATE dbo.software SET index_id = $itemID, last_updated_by = '$last_updated_by', last_updated_at = '$last_updated_at', name = '$name', software_type = '$software_type' WHERE software.index_id = $itemID;";
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
		echo $_POST['submit'];
		echo "<a class=\"button\" href=\"software.php\">OK</a>";
	}
}
else {
	
	include 'open_db.php';
	

	$editQuery = "SELECT * FROM software WHERE software.index_id = $itemID;";
	$editResult = sqlsrv_query($conn, $editQuery);
	if(!$editResult)
	{
		echo print_r( sqlsrv_errors(), true);
		exit;
	}
	$item = sqlsrv_fetch_array($editResult, SQLSRV_FETCH_ASSOC);
	
?>

<div class="large-12 columns">
<h1>Editing Software</h1>
<form data-abide type="submit" name="submit" enctype='multipart/form-data' <?php echo "action=\"edit_software.php?edit=" . $itemID . "\""; ?> method="POST">
	<fieldset>
		<legend>Software Info</legend>

		<div class="row">
			<div class="large-4 columns">
				<label>ID</label>
					<label name="id"><?php echo $itemID; ?></label>
			</div>
			<div class="large-4 columns">
				<label>Name</label>
					<input type="text" name="name" <?php echo "value=\"" . $item['name'] . "\""; ?> required>
			</div>
			<div class="large-4 columns">
				<label>Software type</label>
					<input type="text" name="software_type" <?php echo "value=\"" . $item['software_type'] . "\""; ?> required>
			</div>
		</div>
	</fieldset>
	<div class="row">
		<div class="large-4 columns">
			<dl class="accordion" data-accordion>
			  <dd>
				<a href="#deletePanel">Delete</a>
				<div id="deletePanel" class="content alert">
					<p>Are you sure you want to delete this item? This action cannot be undone.</p>
					<input type="submit" name="submit" value="Delete Item" class="button alert" formmethod="post">
				</div>
			  </dd>
			</dl>
		</div>
			<div class="large-4 columns">
			<a class="button expand" href="software.php">Cancel</a>
		</div>
		<div class="large-4 columns">
			<input type="submit" name="submit" value="Save Item" class="button expand" formmethod="post">
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