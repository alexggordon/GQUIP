<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION) {

$itemID = $_GET['edit'];

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
    $name = $_POST['name'];
    $software_type = $_POST['software_type'];

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
    echo "<a class=\"button\" href=\"software.php\">OK</a>";
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
		<div class="large-12 columns">
		<div class="row" align="center">
		<input type="submit" name="submit" value="Save Item" class="button" formmethod="post">
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