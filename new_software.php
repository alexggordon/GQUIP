<?php
<<<<<<< HEAD
// *************************************************************
// file: new_software.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose:  The page used to add software item content to GQUIP’s database.
// 
// *************************************************************
=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION) {

if (isset($_POST['submit'])){

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
    $id = $_POST['id'];
    $name = $_POST['name'];
    $software_type = $_POST['software_type'];

    //SQL query to insert variables above into table
    $sql = " INSERT INTO dbo.software ([INDEX_ID],[last_updated_by],[name],[software_type])VALUES('$id','$last_updated_by','$name','$software_type')";
    $result = sqlsrv_query($conn, $sql);
    //if the query cant be executed
    if(!$result)
    {
        echo print_r( sqlsrv_errors(), true);
        exit;
    }
    // close the connection

    sqlsrv_close( $conn);
    echo "Data successfully inserted";
}
else {
?>

<div class="large-12 columns">
<h1>New Software</h1>
<form data-abide type="submit" name="submit" enctype='multipart/form-data' action="new_software.php" method="POST">
	<fieldset>
		<legend>Software Info</legend>

		<div class="row">
			<div class="large-4 columns">
				<label>ID</label>
					<input type="number" name="id" placeholder="1100101" required>
				<small class="error">A valid ID is required for this software.</small>
			</div>
			<div class="large-4 columns">
				<label>Name</label>
					<input type="text" name="name" placeholder="Microsoft Office" required>
			</div>
			<div class="large-4 columns">
				<label>Software type</label>
					<input type="text" name="software_type" placeholder="Document editing package" required>
			</div>
		</div>
	</fieldset>
		<div class="large-12 columns">
		<div class="row" align="center">
		<input type="submit" name="submit" value="Create New Item" class="button" formmethod="post" action="software.php">
		<a class="button" href="software.php">Cancel</a>
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