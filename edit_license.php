<?php

// *************************************************************
// file: edit_license.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The page used to edit information on a software license item.
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION || USER_PERMISSION) {


?>
<div class="row">
<div class="large-10 large-centered columns">
<h1>Editing License</h1>

<?php
// get the edit license
$itemID = $_GET['edit'];

// if post request 
if (isset($_POST['submit'])){
	// if post request is a delete
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
		// SQL query
		$deletionSQL = "DELETE FROM dbo.licenses WHERE index_id = $itemID;";
		// connet to the database
		$deletionAttempt = sqlsrv_query($conn, $deletionSQL);

		// If SQL connection
		sqlsrvErrorLinguist($deletionAttempt, "Problem with deleting the license");

		// close the connection
		sqlsrv_close($conn);
		echo "<div class=\"large-8 large-centered columns\">";
		echo "<h3 class=\"large-centered\">Data successfully removed</h3>";
		echo "<a class=\"button\" href=\"students.php\">OK</a>";
		echo "</div>";
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
		$seller = $_POST['seller'];
		$software_id = $_POST['softwareChoice'];

		//SQL query to insert variables above into table
		$sql = "UPDATE dbo.licenses 
				SET seller = '$seller', software_id = $software_id, last_updated_by = '$last_updated_by', last_updated_at = '$last_updated_at' WHERE licenses.index_id = $itemID;";
		$result = sqlsrv_query($conn, $sql);
		sqlsrvErrorLinguist($result, $errorMessage = "Problem with updating software");

		// close the connection

		sqlsrv_close( $conn);
		echo "<div class=\"large-8 large-centered columns\">";
		echo "<h3>Data successfully modified</h3>";
		echo "<a class=\"button expand\" href=\"students.php\">OK</a>";
		echo "</div>";
    }
}
else
{
	// connect to the database
	include 'open_db.php';
	// sql query
	$populationQuery = "SELECT *
	FROM software;";
	
	// connect to the database and execute the query
	$populationResult = sqlsrv_query($conn, $populationQuery);

	$editQuery = "SELECT * FROM licenses WHERE licenses.index_id = $itemID;";
	$editResult = sqlsrv_query($conn, $editQuery);
	sqlsrvErrorLinguist($editResult, "Problem with getting edited license");
	$item = sqlsrv_fetch_array($editResult, SQLSRV_FETCH_ASSOC);
	
	$softwareQuery = "SELECT * FROM software WHERE software.index_id = " . $item['software_id'] . ";";
	$softwareResult = sqlsrv_query($conn, $softwareQuery);
	sqlsrvErrorLinguist($softwareResult, "Problem with searching for software types permissible to use");
	$softwareItem = sqlsrv_fetch_array($softwareResult, SQLSRV_FETCH_ASSOC);
	
?>
<!-- submit form data -->
<form data-abide type="submit" name="submit" enctype='multipart/form-data' <?php echo "action=\"edit_license.php?edit=" . $itemID . "\""; ?> method="POST">
	<fieldset>
		<legend>License Info</legend>

		<div class="row">
			<div class="large-3 columns">
				<label>Time sold</label>
					<label name="date_sold"><?php echo $item['date_sold']->format('Y-m-d H:i:s'); ?></label>
			</div>
			<div class="large-3 columns">
				<label>Seller</label>
					<input type="text" name="seller" <?php echo "value=\"" . $item['seller'] . "\""; ?> required>
			</div>
			<div class="large-6 columns">
				<label>Software Licensed</label>
				<select name="softwareChoice" id="softwareChoice">

				<?php

				while($row = sqlsrv_fetch_array($populationResult))
				{
					echo "<option value=\"" . $row["index_id"];
					echo "\">" . $row['name'] . " (Type: " . $row['software_type'] . ")</option>\n";
					// Use an array to get all legal values for the license kind search
					if ($row['name'] != $softwareItem['name'])
					{
						$securityArray[] = $row["index_id"];
					}
				}

				?>

				</select>
			</div>
		</div>
	</fieldset>
		<div class="row" align="center">
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
			<a class="button expand" href="students.php">Cancel</a>
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
	if($_SESSION['access']==FACULTY_PERMISSION) {
	// Faculty and users should not have access to this page. 
	header('Location: home.php');
	}
	//footer
	include('footer.php')
?>