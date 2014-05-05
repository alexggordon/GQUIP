<?php

// *************************************************************
// file: new_item.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The page used to add equipment item content to GQUIP’s database. 
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION  OR $_SESSION['access']==USER_PERMISSION ) {


if (isset($_POST['submit'])){
	//connect to the database 
	$serverName = "sql05train1.gordon.edu";
	$connectionInfo = array(
	'Database' => 'CTSEquipment');
	$conn = sqlsrv_connect($serverName, $connectionInfo);    
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
	// post data 
	$controlNumber = $_POST['controlNumber'];
	$manufacturer = $_POST['manufacturer'];
	$model = $_POST['model'];
	$serialNumber = $_POST['serialNumber'];
	$usageStatus = "circulation";
	$ram = $_POST['ram'];
	$hdSize = $_POST['hdSize'];
	$partNumber = $_POST['partNumber'];
	$equipmentType;
	if (isset($_POST['equipmentType'])) {
		$equipmentType = $_POST['equipmentType'];
	} else {
		$equipmentType = 0;
	}
	$warrantyLength = $_POST['warrantyLength'];
	$accountNumber = $_POST['accountNumber'];
	$purchaseDate = $_POST['purchaseDate'];
	$warrantyStart = $purchaseDate;
	$purchasePrice = $_POST['purchasePrice'];
	$replacementYear = $_POST['replacementYear'];
	$department = $_POST['department'];
	$assignmentType = $_POST['assignmentType'];

	// if primary computer is set
	if (isset($_POST['primaryComputer'])) {
		$primary_computer = $_POST['primaryComputer'];
	} else {
		$primary_computer = 0;
	}

	// if fulltime is set
	if (isset($_POST['fullTime'])) {
		$full_time = $_POST['fullTime'];
	} else {
		$full_time = 0;
	}
	$notes = $_POST['notes'];

	// Get the faculty/staff member getting assigned
	$selectFacultyStaffID = $_POST['userName'];

	if (isset($_SESSION['user'])) {
		$lastUpdatedBy = $_SESSION['user'];
	}

	$timezone = new DateTimeZone("UTC");
	$lastUpdatedAt = new DateTime("now", $timezone);
	$lastUpdatedAtString = $lastUpdatedAt->format('Y-m-d H:i:s');
	$createdAt = new DateTime("now", $timezone);
	$createdAtString = $createdAt->format('Y-m-d H:i:s');
	$startAssignment = new DateTime("now", $timezone);
	$startAssignmentString = $startAssignment->format('Y-m-d H:i:s');

	//SQL query to insert variables above into table
	$insertComputer = " INSERT INTO dbo.computers ([last_updated_by], [last_updated_at], [created_at], [control], [serial_num], [model], [manufacturer], [purchase_date], [purchase_price], [purchase_acct], [usage_status], [memory], [hard_drive], [warranty_length], [warranty_start], [replacement_year], [computer_type], [part_number])VALUES( '$lastUpdatedBy', '$lastUpdatedAtString', '$createdAtString', '$controlNumber', '$serialNumber', '$model', '$manufacturer', '$purchaseDate', '$purchasePrice', '$accountNumber', '$usageStatus', '$ram', '$hdSize', '$warrantyLength', '$purchaseDate',  '$replacementYear', '$equipmentType', '$partNumber')";

	$computer = sqlsrv_query($conn, $insertComputer);
	if(!$computer)
	{
	    echo print_r( sqlsrv_errors(), true);
	    exit;
	}

	$selectNewInsert = "Select computer_id from dbo.computers where control = " . $controlNumber . " ";
	$computer_id_result = sqlsrv_query($conn, $selectNewInsert);
	while( $row = sqlsrv_fetch_array( $computer_id_result, SQLSRV_FETCH_ASSOC) ) {
	      $computer_id = $row['computer_id'];
	}

	if ($department != "unassigned" || $selectFacultyStaffID != "unassigned")
    {
    	$insertAssignment;
        if ($selectFacultyStaffID == "unassigned")
        {
            $insertAssignment = " INSERT INTO dbo.hardware_assignments ( [computer],  [last_updated_by], [last_updated_at], [created_at], [department_id], [full_time], [primary_computer], [start_assignment], [assignment_type], [nextneed_note])VALUES('$computer_id', '$lastUpdatedBy', '$lastUpdatedAtString', '$createdAtString', '$department', '$full_time', '$primary_computer', '$startAssignmentString', '$assignmentType', '$notes')";
        }
        else
        {
			$insertAssignment = " INSERT INTO dbo.hardware_assignments ( [computer],  [last_updated_by], [last_updated_at], [created_at], [user_id], [department_id], [full_time], [primary_computer], [start_assignment], [assignment_type], [nextneed_note])VALUES('$computer_id', '$lastUpdatedBy', '$lastUpdatedAtString', '$createdAtString', $selectFacultyStaffID, '$department', '$full_time', '$primary_computer', '$startAssignmentString', '$assignmentType', '$notes')";
		}
		$assignment = sqlsrv_query($conn, $insertAssignment);
		sqlsrvErrorLinguist($assignment, "SQL problem with adding this assignment");
		$changeContent = "";
		$changeContent .= "*** ASSIGNMENT ADDED *** \n";
        $changeContent .= "*** ASSIGNMENT ADDED *** \n";
        $changeContent .= "*** ASSIGNMENT ADDED *** \n";
        $changeContent .= "*** ASSIGNMENT ADDED *** \n";
        $changeContent .= "*** ASSIGNMENT ADDED *** \n";
        $changeQuery = "INSERT INTO
                        changes
                        ([body],[last_updated_by],[creator],[last_updated_at],[created_at],[computer_id])VALUES('$changeContent','$lastUpdatedBy','$lastUpdatedBy','$createdAtString','$createdAtString','$computer_id');";
                        $changeResult = sqlsrv_query($conn, $changeQuery);
                        sqlsrvErrorLinguist($changeResult, "SQL problem output 108");
	}
	sqlsrv_close( $conn);
	    ?>
	    <div class="row">
	    <div class="large-10 large-centered columnslarge-centered columns">
	    <h1>Successfully Created Item.</h1>
		<a class="button" href=" <?php echo "info.php?id=". $controlNumber ."" ?> ">Click here to view the item</a>
	    </div>
	     </div>
	    <?php
} else {
?>
<div class="row">
<div class="large-12 large-centered columns">
<h1>New Equipment Item</h1>
<ul class="breadcrumbs">
  <li><a href="home.php">Home</a></li>
  <li class="current"><a href="#">Create New Item</a></li>
</ul>
<form data-abide type="submit" name="submit" enctype='multipart/form-data' action="new_item.php" method="POST">
	<fieldset>
		<legend>Equipment Info</legend>

		<div class="row">
			<div class="large-4 columns">
				<label>Control Number</label>
					<input type="text" name="controlNumber" placeholder="01234" maxlength="10" required>
				<small class="error">A valid Control Number is required.</small>
			</div>
			<div class="large-4 columns">
				<label>Manufacturer</label>
					<input type="text" name="manufacturer" placeholder="Lenovo" maxlength="20" required>
			</div>
			<div class="large-4 columns">
				<label>Model</label>
					<input type="text" name="model" placeholder="T440" maxlength="10" required>
			</div>
		</div>
		<div class="row">
			<div class="large-4 columns">
				<label>Serial Number</label>
					<input type="text" name="serialNumber" placeholder="S012345ABC" maxlength="20" required>
			</div>
			<div class="large-4 columns">			
				<div class="row collapse">		
					<label>Memory Amount </label>
					<div class="small-9 columns">
							<input type="number" name="ram" placeholder="6" max='2000000000' required>
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
							<input type="number" name="hdSize" placeholder="500" max='2000000000' required>
					</div>
					<div class="small-3 columns">
						 <span class="postfix">GB's</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="large-4 columns">
				<label>Part Number</label>
					<input type="text" placeholder="4325-335" name="partNumber" maxlength="20" required>
			</div>
			<div class="large-4 columns">
				<label>Equipment Type</label>
					<select class="medium" name="equipmentType" required data-invalid>
					    <option <?php echo "value=" . LAPTOP_EQUIPMENT_TYPE . ">"; ?>Laptop</option>
					    <option <?php echo "value=" . DESKTOP_EQUIPMENT_TYPE . ">"; ?>Desktop</option>
					    <option <?php echo "value=" . TABLET_EQUIPMENT_TYPE . ">"; ?>Tablet</option>
					</select>
			</div>
			<div class="large-4 columns">
				<label>Warranty Length</label>
				<select class="medium" name="warrantyLength" required data-invalid>
				    <option <?php echo "value=" . ONE_YEAR_WARRANTY_LENGTH . ">"; ?>1 Year</option>
				    <option <?php echo "value=" . TWO_YEAR_WARRANTY_LENGTH . ">"; ?>2 Years</option>
				    <option <?php echo "value=" . THREE_YEAR_WARRANTY_LENGTH . ">"; ?>3 Years</option>
				    <option <?php echo "value=" . FOUR_YEAR_WARRANTY_LENGTH . ">"; ?>4 Years</option>
				    <option <?php echo "value=" . EXPIRED_WARRANTY_LENGTH . ">"; ?>Expired</option>
				</select>
			</div>

		</div>
	</fieldset>
	<fieldset>
		<legend>Purchasing Info</legend>

		<div class="row">
			<div class="large-3 columns">
				<label>Purchasing Account Number</label>
					<input type="text" name="accountNumber" placeholder="000000-123456" maxlength="20" required>
			</div>
			<div class="large-3 columns">
				<label>Purchasing Date</label>
					<input type="date" name="purchaseDate" placeholder="date" required>
			</div>
			<div class="large-3 columns">		
				<div class="row collapse">		
					<label>Purchasing Price</label>
					<div class="small-3 columns">
						<span class="prefix">&#36;</span>
					</div>
					<div class="small-9 columns">
						<input type="number" name="purchasePrice" placeholder="1480" max='2000000000' required>
					</div>
				</div>
			</div>
			<div class="large-3 columns">
				<label>Replacement Year</label>
					<input type="text" name="replacementYear" maxlength="10" required>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Computer Assignment Info</legend>
		<div class="row">
		
		<?php 
		include('open_db.php');
		$populationQuery = "SELECT DISTINCT OnCampusDepartment
		FROM FacStaff
		WHERE OnCampusDepartment IS NOT NULL;";

		$facultyQuery = "SELECT FirstName, LastName, ID
		FROM FacStaff ORDER by LastName ASC;";

		//The sqlsrv_query function allows PHP to make a query against the database
		//and returns the resulting data
		$facultyResult = sqlsrv_query($conn, $facultyQuery);

		$populationResult = sqlsrv_query($conn, $populationQuery);

		//This array gets all the possible departments that a search could target if
		//it were valid

		$securityArray[0] = "unassigned";
		 ?>
			<div class="large-4 columns">
				<label>User Name</label>
				<select class="medium" name="userName" required data-invalid>
				    <option value="unassigned">Unassigned</option>
				<?php
				while($row = sqlsrv_fetch_array($facultyResult))
				{
					echo "<option value=\" " . $row["ID"] . " \">" . $row["LastName"] . ", " . $row["FirstName"] . "</option>\n";
					// Use an array to get all legal values for the department search
					// $securityArray[] = $row["OnCampusDepartment"];
				}
				?>

				</select>
			</div>
			<div class="large-4 columns">
				<label>Department (if not assigned to a person)</label>
					<select class="medium" name="department" required data-invalid>
						<option value="unassigned" selected>Unassigned</option>
						<?php
						while($row = sqlsrv_fetch_array($populationResult))
						{
							echo "<option value=\"" . $row["OnCampusDepartment"] . "\">" . $row["OnCampusDepartment"] . "</option>\n";
							// Use an array to get all legal values for the department search
							$securityArray[] = $row["OnCampusDepartment"];
						}
						?>
					</select>
			</div>
			<div class="large-4 columns">		
			<label>Assignment Type</label>
			<select class="medium" name="assignmentType" required>
                <option <?php echo "value='" . DEDICATED_COMPUTER_ASSIGNMENT_TYPE . "'"; ?>>Dedicated Computer</option>
                <option <?php echo "value='" . SPECIAL_ASSIGNMENT_TYPE . "'"; ?>>Special</option>
                <option <?php echo "value='" . LAB_ASSIGNMENT_TYPE . "'"; ?>>Lab</option>
                <option <?php echo "value='" . KIOSK_ASSIGNMENT_TYPE . "'"; ?>>Kiosk</option>
                <option <?php echo "value='" . PRINTER_ASSIGNMENT_TYPE . "'"; ?>>Printer</option>
            </select>
			</div>
		</div>
		<div class="row">
			<div class="large-2 columns">
				<label>Check if Primary Computer</label>
				<input type="checkbox" name="primaryComputer" value="1">
			</div>	
			<div class="large-2 columns">
				<label>Check if Full Time Assignment</label>
				<input type="checkbox" name="fullTime" value="1" data-invalid>
			</div>
			<div class="large-8 columns">
				<label>Notes</label>
				<textarea name="notes"></textarea>
			</div>
		</div>
	</fieldset>
		<div class="large-12 columns">
		<div class="row" align="center">
		<input type="submit" name="submit" value="Create New Item" class="button expand" formmethod="post">
		</div>
		</div>
</form>
</div>
</div>
<?php
	}
	}
	// Faculty
	if($_SESSION['access']==FACULTY_PERMISSION ) {
	// Faculty should not have access to this page. 
	header('Location: home.php');
	}
	//footer
	include('footer.php')
?>