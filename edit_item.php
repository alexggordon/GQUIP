/* <COMMENT>

<?php
include('config.php');
include('header.php')
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

if(isset($_GET['control']))
{
	$search = $_GET['control'];
	$itemquery = "SELECT * FROM computers
  			WHERE control IN 
  			(SELECT Max(last_updated_at) FROM computers where control = $search);";

    $commentquery = "SELECT * FROM comments JOIN users 
    		WHERE comments.computer = $cur_control 
    		ORDER BY comment.created_at;";

  	$assignmentquery = "SELECT * FROM hardware_assignments
  			WHERE control IN
  			(SELECT Max(last_updated_at) FROM hardware_assignments 
  			WHERE control = $search);";

  	include('open_db.php');

  	$result = sqlsrv_query($itemquery);
  	$commentresult = sqlsrv_query($commentquery);
  	$assignmentresult = sqlsrv_query($assignmentquery);

  	include('close_db.php');

	while($row = sqlsrv_fetch_array($result))
	  {

	    echo "<li>" . $row["control"] . $row["model"] . $row["manufacturer"] . " | EDIT_BUTTON_FOR_" . $row["control"] . " | " . "</li>";

	    while($assignmentrow = sqlsrv_fetch_array($assignmentresult))
	    {
	    
	      echo " && " . $assignmentrow["users.last_name"] . $assignmentrow["users.first_name"] . $assignmentrow["hardware_assignments.start_assignment"] . $assignmentrow["hardware_assignments.end_assignment"];
	    
	    }

	    while($commentrow = sqlsrv_fetch_array($commentresult))
	    {
	    
	      echo " && " . $commentrow["users.first_name"] . $commentrow["users.last_name"] . $commentrow["comment.created_at"] . $commentrow["comment.text"];
	    
	    }

	  }
}



// Manager
if($_SESSION['access']=="3" ) {
?>


<?php
}
// Faculty
if($_SESSION['access']=="2" ) {
?>


<?php
}
// User
if($_SESSION['access']=="1" ) {
?>

<?php
}
?>

}

</COMMENT>
*/


<?php
include('footer.php')
?>

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
    $conn = sqlsrv_connect($serverName, $connectionInfo);    
    //display error if database cannot be accessed 
    if (!$conn ) 
    {
        die('Unable to connect or select database!');
    }
    //assign form input to variables
    // <!!!> where do the fields for these new columns go in the form?
    $controlNumber = $_POST['controlNumber'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $serialNumber = $_POST['serialNumber'];
    $ram = $_POST['ram'];
    $hdSize = $_POST['hdSize'];
    $partNumber = $_POST['partNumber'];
    $equipmentType = $_POST['equipmentType'];
    $warrantyLength = $_POST['warrantyLength'];
    $warrantyEnd = $_POST['warrantyEnd'];			//New
    $warrantyType = $_POST['warrantyType'];			//New
    $accountNumber = $_POST['accountNumber'];
    $purchaseDate = $_POST['purchaseDate'];
    $purchasePrice = $_POST['purchasePrice'];
    $replacementYear = $_POST['replacementYear'];
    $userName = $_POST['userName'];
    $department = $_POST['department'];
    $assignmentType = $_POST['assignmentType'];
    $cameronId = $_POST['cameronId'];				//New
    $ipNumber = $_POST['ipNumber'];					//New

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

if(isset($_GET['control']))
{
	$search = $_GET['control'];
	$itemquery = "SELECT * FROM computers
  			WHERE control IN 
  			(SELECT Max(last_updated_at) FROM computers where control = $search);";

    $commentquery = "SELECT * FROM comments JOIN users 
    		WHERE comments.computer = $cur_control 
    		ORDER BY comment.created_at;";

  	$assignmentquery = "SELECT * FROM hardware_assignments
  			WHERE control IN
  			(SELECT Max(last_updated_at) FROM hardware_assignments 
  			WHERE control = $search);";

  	include('open_db.php');

  	$result = sqlsrv_query($itemquery);
  	$commentresult = sqlsrv_query($commentquery);
  	$assignmentresult = sqlsrv_query($assignmentquery);

  	include('close_db.php');

	// If a computer matches this control number, return its data
  	$numRows = sqlsrv_num_rows($result); 
  	if($numRows > 0)
  	{

  	$row = sqlsrv_fetch_array($result);
  	$assignmentrow = sqlsrv_fetch_array($commentresult);
?>
<div class="large-12 columns">
<h1>New Equipment Item</h1>
<form data-abide action="$_SERVER['PHP_SELF']" method="POST">
	<fieldset>
		<legend>Equipment Info</legend>

		<div class="row">
			<div class="large-4 columns">
				<label>Control Number</label>
					<input type="text" name="controlNumber" value=<?="$row[\"controlNumber\"]"?> required>
				<small class="error">A valid Control Number is required.</small>
			</div>
			<div class="large-4 columns">
				<label>Manufacturer</label>
					<input type="text" name="manufactuer" value=<?="$row[\"manufactuer\"]"?> required>
			</div>
			<div class="large-4 columns">
				<label>Model</label>
					<input type="text" name="model" value=<?="$row[\"model\"]"?> required>
			</div>
		</div>
		<div class="row">
			<div class="large-4 columns">
				<label>Serial Number</label>
					<input type="text" name="serialNumber" value=<?="$row[\"serial_num\"]"?>>
			</div>
			<div class="large-4 columns">			
				<div class="row collapse">		
					<label>Memory Amount </label>
					<div class="small-9 columns">
							<input type="number" name="ram" value=<?="$row[\"memory\"]"?> required>
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
							<input type="number" name="hdSize" value=<?="$row[\"hard_drive\"]"?> required>
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
					<input type="text" value=<?="$row[\"part_number\"]"?> required>
			</div>
			<div class="large-3 columns">
				<label>Equipment Type</label>
					<select class="medium" name="equipmentType" required>	
						<option DISABLED>Category of Computer</option>
						<option <?php if("$row[\"computer_type\"]" == "1") {echo "selected";} ?> value="1">Laptop</option>;
						<option <?php if("$row[\"computer_type\"]" == "2") {echo "selected";} ?> value="2">Desktop</option>;
						<option <?php if("$row[\"computer_type\"]" == "3") {echo "selected";} ?> value="3">Tablet</option>;
					</select>
			</div>
			<div class="large-3 columns">
				<label>Warranty Length</label>
				<select class="medium" name="warrantyLength" required>
				    <option DISABLED>Length In Years</option>
				    <option <?php if("$row[\"warranty_length\"]" == "1") {echo "selected";} ?> value="1">1 Year</option>
				    <option <?php if("$row[\"warranty_length\"]" == "2") {echo "selected";} ?> value="2">2 Years</option>
				    <option <?php if("$row[\"warranty_length\"]" == "3") {echo "selected";} ?> value="3">3 Years</option>
				    <option <?php if("$row[\"warranty_length\"]" == "4") {echo "selected";} ?> value="4">4 Years</option>
				    <option <?php if("$row[\"warranty_length\"]" == "5") {echo "selected";} ?> value="5">Expired</option>
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
					<input type="number" name="accountNumber" value=<?="$row[\"purchase_acct\"]"?>>
			</div>
			<div class="large-3 columns">
				<label>Purchasing Date</label>
					<input type="date" name="purchaseDate" value=<?="$row[\"purchase_date\"]"?>>
			</div>
			<div class="large-3 columns">		
				<div class="row collapse">		
					<label>Purchasing Price</label>
					<div class="small-3 columns">
						<span class="prefix">&#36;</span>
					</div>
					<div class="small-9 columns">
						<input type="number" name="purchasePrice" value=<?="$row[\"purchase_price\"]"?> required>
					</div>
				</div>
			</div>
			<div class="large-3 columns">
				<label>Replacement Year</label>
					<input type="month" name="replacementYear" value=<?="$row[\"replacement_year\"]"?>>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>User Info</legend>
		<div class="row">
			<div class="large-3 columns">
				<label>User Name</label>
				<select class="medium" name="userName" required>
				    <option DISABLED>User Name</option>
				    <option selected value=<?="$assignmentrow[\"users.id\"]"?>><?="$assignmentrow[\"users.first_name\"] . $assignmentrow[\"users.last_name\"]"?></option>
				</select>
			</div>
			<div class="large-3 columns">
				<label>Department</label>
				<select class="medium" name="department" required>
				    <option DISABLED selected><?="$assignmentrow[\"hardware_assignments.department\"]"?></option>
				    <option value="1">PHP GOES HERE</option>
				</select>
			</div>
			<div class="large-3 columns">		
				<label>Assignment Type</label>
				<select class="medium" name="assignmentType" required>
				    <option DISABLED selected>Assignment Type</option>
				    <?php // <!!!> what column is used for kiosk and printer data? ?>
				    <option <?php if("$assignmentrow[\"hardware_assignments.dedicated\"]" == "1") {echo "selected";} ?> value="1">Dedicated Computer</option>
				    <option <?php if("$assignmentrow[\"hardware_assignments.special\"]" == "1") {echo "selected";} ?> value="2">Special</option>
				    <option <?php if("$assignmentrow[\"hardware_assignments.lab\"]" == "1") {echo "selected";} ?> value="3">Lab</option>
				    <option <?php if("$assignmentrow[\"hardware_assignments.\"]" == "4") {echo "selected";} ?> value="4">Kiosk</option>
				    <option <?php if("$assignmentrow[\"hardware_assignments.\"]" == "5") {echo "selected";} ?> value="5">Printer</option>
				</select>
			</div>
			<div class="large-3 columns">
				<?php // <!!!> what is this supposed to do? there is already a replacement year field ?>
				<label>Replacement Year</label>
				<input type="month" name="replacementYear"> 
			</div>

		</div>
	</fieldset>
		<div class="large-12 columns">
		<div class="row" align="center">
		<input type="submit" name="submit" value="Done" class="button expand" formmethod="post">
		</div>
	</div>

	<!-- Comment area: still needs the logic for adding the comment to the records -->

	<div class="large-10 columns">
	<div class="row" align="center">
	<label>Comments
        <textarea placeholder="Add your comment here..."></textarea>
    </label>
    <input type="submit" name="submitComment" value="Dummy Comment Button" class="button expand">
	<?php

	while($commentrow = mssql_fetch_array($commentresult))
    {

      echo " && " . $commentrow["users.first_name"] . $commentrow["users.last_name"] . $commentrow["comment.created_at"] . $commentrow["comment.text"];
    
    }

	?>
	</div>
	</div>

</form>
</div>;

<?php
		}
		else
		{
			// If there are no rows for this query, redirect to the home page.
			header('Location: home.php');
		}
	}
	else
	{
		// If the user does not set a control value, redirect to the home page.
		header('Location: home.php');
	}
}
// Faculty
if($_SESSION['access']=="2" ) {
// Faculty should not have access to this page. 
header('Location: home.php');
}
//footer
include('footer.php')
?>