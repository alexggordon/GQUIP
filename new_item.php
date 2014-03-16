<?php
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
    $control = $_POST['controlNumber'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $serial_number = $_POST['serialNumber'];
    $memory = $_POST['ram'];
    $hard_drive = $_POST['hdSize'];
    $part_number = $_POST['partNumber'];
    $computer_type = $_POST['equipmentType'];
    $warranty_length = $_POST['warrantyLength'];
    $purchase_acct = $_POST['accountNumber'];
    $purchase_date = $_POST['purchaseDate'];
    $purchase_price = $_POST['purchasePrice'];
    $replacement_year = $_POST['replacementYear'];
    $ip_address = $_POST['ip_address'];
    $usage_status = $_POST['usage_status'];
    $inventoried = 0;
    if ($_POST['inventoried'] = "yes")
	{
		$inventoried = 1;
	}
    
    //SQL query to insert variables above into table
    $sql = "INSERT INTO dbo.computers ([control],[last_updated_by],[last_updated_at],[created_at],[manufacturer],[model],[serial_num],[memory],[hard_drive],[part_number],[computer_type],[warranty_length],[purchase_acct],[purchase_date],[purchase_price],[replacement_year],[ip_address],[usage_status],[inventoried])VALUES('$control','$last_updated_by','$last_updated_at','$created_at','$manufacturer','$model','$serial_number','$memory','$hard_drive','$part_number','$computer_type','$warranty_length','$purchase_acct','$purchase_date','$purchase_price','$replacement_year','$ip_address','$usage_status','$inventoried')";
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
					<input type="text" name="serialNumber" placeholder="S012345ABC">
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
			<div class="large-4 columns">
				<label>Equipment Type</label>
					<select class="medium" name="equipmentType" id="equipmentType" required>
					    <option value="1" selected>Laptop</option>
					    <option value="2">Desktop</option>
					    <option value="3">Tablet</option>
					    <option value="4">Printer</option>
					    <option value="5">Other</option>
					</select>
			</div>
			<div class="large-4 columns">
				<label>IP Address (if printer)</label>
					<input type="text" name="ip_address" id="ip_address" placeholder="192.168.1.1">
			</div>
			<div class="large-4 columns">
				<label>Usage Status</label>
					<select class="medium" name="usage_status" id="usage_status" required>
					    <option value="in circulation" selected>In circulation</option>
					    <option value="not in circulation">Not in circulation</option>
					    <option value="sold">Sold</option>
					    <option value="retired">Retired</option>
					    <option value="5">Other</option>
					</select>
			</div>
		</div>
		<div class="row">
			<div class="large-3 columns">
				<label>Inventory Status</label>
					<input type='checkbox' name='inventoried' value='yes'><span class="label radius">Is inventoried</span>
			</div>
			<div class="large-3 columns">
				<label>Part Number</label>
					<input type="text" name="partNumber" placeholder="4325-335" required>
			</div>
			<div class="large-3 columns">
				<label>Warranty Length</label>
				<select class="medium" name="warrantyLength" required>
				    <option value="1">1 Year</option>
				    <option value="2">2 Years</option>
				    <option value="3">3 Years</option>
				    <option value="4">4 Years</option>
				    <option value="5" selected>Expired/None</option>
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
					<input type="number" name="accountNumber" placeholder="000000-123456">
			</div>
			<div class="large-3 columns">
				<label>Purchasing Date</label>
					<input type="date" name="purchaseDate" placeholder="date">
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
					<input type="month" name="replacementYear">
			</div>
		</div>
	</fieldset>
	<!--
	<fieldset>
		<legend>Computer Assignment Info</legend>
		<div class="row">
		
		<?php 
		include('open_db.php');
		$populationQuery = "SELECT DISTINCT OnCampusDepartment
		FROM FacStaff
		WHERE OnCampusDepartment IS NOT NULL;";

		$facultyQuery = "SELECT FirstName, LastName
		FROM FacStaff ORDER by LastName ASC;";

		//The sqlsrv_query function allows PHP to make a query against the database
		//and returns the resulting data
		$facultyResult = sqlsrv_query($conn, $facultyQuery);

		$populationResult = sqlsrv_query($conn, $populationQuery);

		//This array gets all the possible departments that a search could target if
		//it were valid

		$securityArray[0] = "unassigned";

		 ?>
			<div class="large-3 columns">
				<label>User Name</label>
				<select class="medium" name="userName" required>
				    <option selected>User Name</option>
				<?php
				while($row = sqlsrv_fetch_array($facultyResult))
				{
					echo "<option value=\"" . $row["LastName"] . "" . $row["FirstName"] . "\">" . $row["LastName"] . "" . $row["FirstName"] . "</option>\n";
					// Use an array to get all legal values for the department search
					// $securityArray[] = $row["OnCampusDepartment"];
				}
				?>

				</select>
			</div>
			<div class="large-3 columns">
			<label>Department</label>
				<select class="medium" name="department" required>
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
	-->
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