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
    $controlNumber = $_POST['controlNumber'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $serialNumber = $_POST['serialNumber'];
    $ram = $_POST['ram'];
    $hdSize = $_POST['hdSize'];
    $partNumber = $_POST['partNumber'];
    $equipmentType = $_POST['equipmentType'];
    $warrantyLength = $_POST['warrantyLength'];
    $accountNumber = $_POST['accountNumber'];
    $purchaseDate = $_POST['purchaseDate'];
    $purchasePrice = $_POST['purchasePrice'];
    $replacementYear = $_POST['replacementYear'];
    $userName = $_POST['userName'];
    $department = $_POST['department'];
    $assignmentType = $_POST['assignmentType'];
    $inventoried = $_POST['inventoried'];
    $is_retired = $_POST['is_retired'];

    //SQL query to insert variables above into table
    $sql = "UPDATE dbo.software SET control = $itemID, last_updated_by = '$last_updated_by', name = '$name', software_type = '$software_type' WHERE software.control = $itemID;";
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
else {
	
	include 'open_db.php';
	

	$editQuery = "SELECT * FROM computers WHERE computers.control = $itemID;";
	$editResult = sqlsrv_query($conn, $editQuery);
	if(!$editResult)
	{
		echo print_r( sqlsrv_errors(), true);
		exit;
	}
	$item = sqlsrv_fetch_array($editResult, SQLSRV_FETCH_ASSOC);
	
?>

<div class="large-12 columns">
<h1>Editing Computer</h1>
<form data-abide type="submit" name="submit" enctype='multipart/form-data' <?php echo "action=\"edit_software.php?edit=" . $itemID . "\""; ?> method="POST">
	
	<fieldset>
		<legend>Equipment Info</legend>

		<div class="row">
			<div class="large-4 columns">
				<label>Control Number</label>
					<input type="text" name="controlNumber" <?php echo "value=\"" . $item['control'] . "\""; ?> required>
				<small class="error">A valid Control Number is required.</small>
			</div>
			<div class="large-4 columns">
				<label>Manufacturer</label>
					<input type="text" name="manufactuer" <?php echo "value=\"" . $item['manufacturer'] . "\""; ?> required>
			</div>
			<div class="large-4 columns">
				<label>Model</label>
					<input type="text" name="model" <?php echo "value=\"" . $item['model'] . "\""; ?> required>
			</div>
		</div>
		<div class="row">
			<div class="large-4 columns">
				<label>Serial Number</label>
					<input type="text" name="serialNumber" <?php echo "value=\"" . $item['name'] . "\""; ?>>
			</div>
			<div class="large-4 columns">			
				<div class="row collapse">		
					<label>Memory Amount </label>
					<div class="small-9 columns">
							<input type="number" name="ram" <?php echo "value=\"" . $item['name'] . "\""; ?> required>
					</div>
					<div class="small-3 columns">
						 <span class="postfix">GB's</span >
					</div>
				</div>
			</div>
			<div class="large-4 columns">			
				<div class="row collapse">		
					<label>Hard Drive Size</label>
					<div class="small-9 columns">
							<input type="number" name="hdSize" <?php echo "value=\"" . $item['name'] . "\""; ?> required>
					</div>
					<div class="small-3 columns">
						 <span class="postfix">GB's</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="large-3 columns">
				<label>Part Number</label>
					<input type="text" <?php echo "value=\"" . $item['name'] . "\""; ?> required>
			</div>
			<div class="large-3 columns">
				<label>Equipment Type</label>
					<select class="medium" name="equipmentType
					" required>
					    <option DISABLED selected>Choose an Option</option>
					    <option value="1">Laptop</option>
					    <option value="2">Desktop</option>
					    <option value="3">Tablet</option>
					</select>
			</div>
			<div class="large-3 columns">
				<label>Warranty Length</label>
				<select class="medium" name="warrantyLength" required>
				    <option DISABLED selected>Length In Years</option>
				    <option value="1">1 Year</option>
				    <option value="2">2 Years</option>
				    <option value="3">3 Years</option>
				    <option value="4">4 Years</option>
				    <option value="5">Expired</option>
				</select>
			</div>
			<div class="large-3 columns">
				<label>Warranty Start Date</label>
					<input type="month" name="replacementYear">
			</div>

		</div>
	</fieldset>
	<fieldset>
		<legend>Purchasing Info</legend>

		<div class="row">
			<div class="large-3 columns">
				<label>Purchasing Account Number</label>
					<input type="number" name="accountNumber" <?php echo "value=\"" . $item['name'] . "\""; ?>>
			</div>
			<div class="large-3 columns">
				<label>Purchasing Date</label>
					<input type="date" name="purchaseDate" <?php echo "value=\"" . $item['name'] . "\""; ?>>
			</div>
			<div class="large-3 columns">		
				<div class="row collapse">		
					<label>Purchasing Price</label>
					<div class="small-3 columns">
						<span class="prefix">&#36;</span>
					</div>
					<div class="small-9 columns">
						<input type="number" name="purchasePrice" <?php echo "value=\"" . $item['name'] . "\""; ?> required>
					</div>
				</div>
			</div>
			<div class="large-3 columns">
				<label>Replacement Year</label>
					<input type="month" name="replacementYear">
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>User Info</legend>
		<div class="row">
			<div class="large-3 columns">
				<label>User Name</label>
				<select class="medium" name="userName" required>
				    <option DISABLED selected>User Name</option>
				    <option value="1">PHP GOES HERE</option>
				</select>
			</div>
			<div class="large-3 columns">
				<label>Department</label>
				<select class="medium" name="department" required>
				    <option DISABLED selected>Department</option>
				    <option value="1">PHP GOES HERE</option>
				</select>
			</div>
			<div class="large-3 columns">		
				<label>Assignment Type</label>
				<select class="medium" name="assignmentType" required>
				    <option DISABLED selected>Assignment Type</option>
				    <option value="1">Dedicated Computer</option>
				    <option value="2">Special</option>
				    <option value="3">Lab</option>
				    <option value="4">Kiosk</option>
				    <option value="5">Printer</option>
				</select>
			</div>
			<div class="large-3 columns">
				<label>Replacement Year</label>
				<input type="month" name="replacementYear">
			</div>

		</div>
	</fieldset>
		<div class="large-12 columns">
		<div class="row" align="center">
		<input type="submit" name="submit" value="Save Item" class="button expand" formmethod="post">
		<a class="button" href="home.php">Cancel</a>
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