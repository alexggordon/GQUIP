<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Manager or User
if($_SESSION['access']=="3"  OR $_SESSION['access']=="1" ) {
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
    $conn = mssql_connect($serverName, $connectionInfo);    
    //display error if database cannot be accessed 
    if (!$conn ) 
    {
        die('Unable to connect or select database!');
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

    //SQL query to insert variables above into table
    $sql = " INSERT INTO dbo.computer ([control],[manufacturer],[model],[serialNumber],[ram],[hdSize],[partNumber],[equipmentType],[warrantyLength],[accountNumber],[purchaseDate],[purchasePrice],[replacementYear],[userName],[department],[assignmentType])VALUES('$controlNumber','$manufacturer','$model','$serialNumber','$ram','$hdSize','$partNumber','$equipmentType','$warrantyLength','$accountNumber','$purchaseDate','$purchasePrice','$replacementYear','$userName','$department','$assignmentType')";
    $result = sqlsrv_query($sql, $conn);
    //if the query cant be executed
    if(!$result)
    {
        echo sqlsrv_error();
        exit;
    }
    // close the connection

    sqlsrv_close( $conn);
    echo "Data successfully inserted";
}
?>

<div class="large-12 columns">
<h1>New Equipment Item</h1>
<form data-abide action="$_SERVER['PHP_SELF']" method="POST">
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
					<input type="text" name="manufactuer" placeholder="Lenovo" required>
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
			<div class="large-3 columns">
				<label>Part Number</label>
					<input type="text" placeholder="4325-335" required>
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
		<input type="submit" name="submit" value="Create New Item" class="button expand" formmethod="post">
		</div>
	</div>
</form>
</div>

<?php
}
// Faculty
if($_SESSION['access']=="2" ) {
// Faculty should not have access to this page. 
header('Location: home.php');
}
//footer
include('footer.php')
?>