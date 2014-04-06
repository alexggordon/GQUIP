<!--- 
CONCERNS:
1: Is the "printer" option in assignment_type specifically referring to printer servers? (If not
	... I don't see a reason to have it, we can just rely on cameron_id being null/not null or add
	... a printer option to computer_type)
2: Is warranty_end being calculated as purchase_date + warranty_length at the computer's addition
	... to the database?
3: Why are there two separate input fields for replacement_year?
4: Doesn't the new item page at least need the ip_address, cameron_id, and warranty_type columns
	... in addition to what is already there?
5: Are some bits of information (like stuff for comments and possibly null fields) not going to be
	... on the new_item page?
6: For a computer, is the legacy_user_id (changed the name of the column for clarity) ALWAYS going
	... to be in the user_id column for the user table?
7: See my comments marked with <!!!> for in the create_tables file
-->

<?php
<<<<<<< HEAD
// *************************************************************
// file: new_item.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The page used to add equipment item content to GQUIP’s database. 
// 
// *************************************************************
=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION  OR $_SESSION['access']==USER_PERMISSION ) {
// 
// [control] [varchar](45) NOT NULL,
// [department] [varchar](45) NOT NULL,
// [last_updated_by] [int] NOT NULL,
// [serial] [varchar](25) NULL,
// [model] [varchar](25) NOT NULL,
// [manufacturer] [varchar](25) NOT NULL,
// [purchase_date] [varchar](25) NOT NULL,
// [purchase_price] [varchar](25) NOT NULL,
// [purchase_acct] [varchar](25) NOT NULL,
// [usage_status] [varchar](25) NULL,
// [memory] [varchar](25) NULL,
// [hard_drive] [varchar](25) NULL,
// [warranty_length] [varchar](25) NOT NULL,
// [warranty_end] [varchar](25) NOT NULL,
// [warranty_type] [varchar](25) NULL,
// [replacement_year] [varchar](25) NOT NULL,
// [computer_type] [varchar](25) NULL,
// [user_id] [varchar](25) NULL,
// [cameron_id] [varchar](25) NULL,
// [part_number] [varchar](25) NULL,
// [ip_address] [varchar](25) NULL



// Control Number = controlNumber
// Manufacturer = manufactuer
// Model = model
// Serial Number = serialNumber
// Memory Amount = ram
// Hard Drive Size = hdSize
// Part Number = partNumber
// EquipmentType = equipmentType
// WarrantyLength = warrantyLength
// Purchasing Account Number = accountNumber
// Purchasing Date = purchaseDate
// Purchasing Price = purchasePrice
// Replacement Year = replacementYear
// User Name = userName
// Department = department
// Assignment Type = assignmentType
//


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
    $controlNumber = $_POST['controlNumber'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $serialNumber = $_POST['serialNumber'];
    $ram = $_POST['ram'];
    $hdSize = $_POST['hdSize'];
    $partNumber = $_POST['partNumber'];

    if (isset($_POST['equipmentType'])) {
    	$equipmentType = $_POST['equipmentType'];
    } else {
    	$equipmentType = 0;
    }
   
    

    $warrantyLength = $_POST['warrantyLength'];
    $accountNumber = $_POST['accountNumber'];
    $purchaseDate = $_POST['purchaseDate'];
    $purchasePrice = $_POST['purchasePrice'];
    $replacementYear = $_POST['replacementYear'];
   

   // assignment
    $department = $_POST['department'];
    $assignmentType = $_POST['assignmentType'];


if (isset($_POST['primaryComputer'])) {
	$primary_computer = $_POST['primaryComputer'];
} else {
	$primary_computer = 0;
}


if (isset($_POST['fullTime'])) {
	$full_time = $_POST['fullTime'];
} else {
	$full_time = 0;
}

    $notes = $_POST['notes'];

    // Get the 
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
    $insertComputer = " INSERT INTO dbo.computers ([last_updated_by], [last_updated_at], [created_at], [control], [serial_num], [model], [manufacturer], [purchase_date], [purchase_price], [purchase_acct], [usage_status], [memory], [hard_drive], [warranty_length], [warranty_start], [replacement_year], [computer_type], [part_number])VALUES( '$lastUpdatedBy', '$lastUpdatedAtString', '$createdAtString', '$controlNumber', '$serialNumber', '$model', '$manufacturer', '$purchaseDate', '$purchasePrice', '$accountNumber', '$assignmentType', '$ram', '$hdSize', '$warrantyLength', '$purchaseDate',  '$replacementYear', '$equipmentType', '$partNumber')";

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


    $insertAssignment = " INSERT INTO dbo.hardware_assignments ( [computer],  [last_updated_by], [last_updated_at], [created_at], [user_id], [department_id], [full_time], [primary_computer], [start_assignment], [assignment_type], [nextneed_note])VALUES('$computer_id', '$lastUpdatedBy', '$lastUpdatedAtString', '$createdAtString', $selectFacultyStaffID, '$department', '$full_time', '$primary_computer', '$startAssignmentString', '$assignmentType', '$notes')";

    $assignment = sqlsrv_query($conn, $insertAssignment);
    if(!$assignment)
    {
        echo print_r( sqlsrv_errors(), true);
        exit;
    }

    sqlsrv_close( $conn);

        ?>
        <h1>Successfull Item Creation.</h1>
        <div class="large-12 columns">
    	<div class="row" align="center">
    	<a class="button" href=" <?php echo "info.php?id=". $controlNumber ."" ?> ">Click here to view the item</a>
    	</div>
         </div>
        <?php

}
else {
?>

<div class="large-12 columns">
<h1>New Equipment Item</h1>
<form data-abide type="submit" name="submit" enctype='multipart/form-data' action="new_item.php" method="POST">
	<fieldset>
		<legend>Equipment Info</legend>

		<div class="row">
			<div class="large-4 columns">
				<label>Control Number</label>
					<input type="text" name="controlNumber" placeholder="01234" required>
				<small class="error">A valid Control Number is required.</small>
			</div>
			<div class="large-4 columns">
				<label>Manufacturer</label>
					<input type="text" name="manufacturer" placeholder="Lenovo" required>
			</div>
			<div class="large-4 columns">
				<label>Model</label>
					<input type="text" name="model" placeholder="T440" required>
			</div>
		</div>
		<div class="row">
			<div class="large-4 columns">
				<label>Serial Number</label>
					<input type="text" name="serialNumber" placeholder="S012345ABC" required>
			</div>
			<div class="large-4 columns">			
				<div class="row collapse">		
					<label>Memory Amount </label>
					<div class="small-9 columns">
							<input type="number" name="ram" placeholder="6" required>
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
							<input type="number" name="hdSize" placeholder="500" required>
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
					<input type="text" placeholder="4325-335", name="partNumber" required>
			</div>
			<div class="large-3 columns">
				<label>Equipment Type</label>
					<select class="medium" name="equipmentType" required data-invalid>
					    <option DISABLED selected>Choose an Option</option>
					    <option value="1">Laptop</option>
					    <option value="2">Desktop</option>
					    <option value="3">Tablet</option>
					</select>
			</div>
			<div class="large-3 columns">
				<label>Warranty Length</label>
				<select class="medium" name="warrantyLength" required data-invalid>
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
					<input type="month" name="replacementYear" required>
			</div>

		</div>
	</fieldset>
	<fieldset>
		<legend>Purchasing Info</legend>

		<div class="row">
			<div class="large-3 columns">
				<label>Purchasing Account Number</label>
					<input type="number" name="accountNumber" placeholder="000000-123456" required>
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
						<input type="number" name="purchasePrice" placeholder="1480" required>
					</div>
				</div>
			</div>
			<div class="large-3 columns">
				<label>Replacement Year</label>
					<input type="text" name="replacementYear" required>
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
				    <option DISABLED selected>User Name</option>
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
				<label>Department</label>
					<select class="medium" name="department" required data-invalid>
						<option DISABLED selected>Department</option>
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
					<select class="medium" name="assignmentType" required data-invalid>
					    <option DISABLED selected>Assignment Type</option>
					    <option value="1">Dedicated Computer</option>
					    <option value="2">Special</option>
					    <option value="3">Lab</option>
					    <option value="4">Kiosk</option>
					    <option value="5">Printer</option>
					</select>
			</div>
		</div>
		<div class="row">
			<div class="large-2 columns">
				<label>Check if Primary Computer</label>
				<input type="checkbox" name="primaryComputer" value="1" required>
			</div>	
			<div class="large-2 columns">
				<label>Check if Full Time Assignment?</label>
				<input type="checkbox" name="fullTime" value="1" required data-invalid>
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