<?php

// *************************************************************
// file: info.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose:  The purpose of this page is to display all the information about an equipment item. 
// 
// *************************************************************


include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Manager
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']==FACULTY_PERMISSION OR $_SESSION['access']==USER_PERMISSION) {
	include 'open_db.php';
	if (isset($_GET['id'])) {
	$control = $_GET['id'];
	$computer_id;
	$commentresult;
	$selectNewInsert = "Select * from dbo.computers where control = " . $control . " ";
	$computer_id_result = sqlsrv_query($conn, $selectNewInsert);
	while( $row = sqlsrv_fetch_array( $computer_id_result, SQLSRV_FETCH_ASSOC) ) 
	{
	      $computer_id = $row['computer_id'];

		    $commentquery = "SELECT * FROM comments 
		    WHERE computer_id = " . $computer_id . "
		    ORDER BY created_at DESC;";
		    $commentresult = sqlsrv_query($conn, $commentquery);
		    sqlsrvErrorLinguist($commentresult, "SQL problem output 103");

	      $created_on = $row['created_at'];
	      $created_by = $row['last_updated_by'];

			// Parse out the content that is not human-readable
			$unitType = "Other";
			switch ($row['computer_type'])
			{
				case LAPTOP_EQUIPMENT_TYPE:
					$unitType = "Laptop";
					break;
				case DESKTOP_EQUIPMENT_TYPE:
					$unitType = "Desktop";
					break;
				case TABLET_EQUIPMENT_TYPE:
					$unitType = "Tablet";
					break;
			}
	?>
	<div class="row">
	<div class="large-10 large-centered columns">
	<h1 class="docs header">Details for <?php echo $control ; ?></h1>
	<ul class="breadcrumbs">
	  <li><a href="home.php">Home</a></li>
	  <li class="current"><a href="#">Computer Detail</a></li>
	</ul>
		</div>
		</div>
	  <!-- First Band (Image) -->


	<?php if ($row['usage_status'] == 'retired' || $row['usage_status'] == 'sold')
	{
		?>
	<div class="row">
	<div class="large-10 large-centered columns">
	<h3>This unit is recorded as <?php echo $row['usage_status'];?></h3>
	</div>
	</div>
	<?php
	}
	?>

	<div class="row">
	<div class="large-10 large-centered columns">
	<?php echo "<a class=\"button small\" href=\"edit_item.php?control=" . $control . "\">Edit</a>"; ?>
	</div>
	</div>

	<div class="row">
	<div class="large-10 large-centered columns">
	<div class="row">
	<div class="large-5 columns">
	<div class="panel">
	  <h5>Computer Info</h5>
	 		<p>
	 		  <b>Control:</b>
	 		  <?php echo $row['control']; ?>
	 		</p>
	 
	 		<p>
	 		  <b>Serial:</b>
	 		  <?php echo $row['serial_num']; ?>
	 		</p>
	 
	 		<p>
	 		  <b>Model:</b>
	 		  <?php echo $row['model']; ?>
	 		</p>

	 
	 		<p>
	 		  <b>Manufacturer:</b>
	 		  <?php echo $row['manufacturer']; ?>
	 		</p>

	 		<p> 
	 		  <b>Part Number:</b>
	 		  <?php echo $row['part_number']; ?>
	 		</p>

	 		<p>
	 		  <b>Type:</b>
	 		  <?php echo $unitType; ?>
	 		</p>
	 		<p>
	 		
	 	    </p>
	</div>
	  </div>
	  <div class="large-5 columns">
	    <div class="panel">
	      <h5>Hardware Info</h5>
			<p><b>Memory:</b>
			<?php echo $row['memory']; ?>
			</p>
			<p>
			  <b>Hard drive Size:</b>
			  <?php echo $row['hard_drive']; ?> GB's
			</p>
			
			<p>
			  <b>Warranty Length:</b>
			  <?php if ($row['warranty_length'] == 5) {echo 'Expired';} else {echo $row['warranty_length'];} ?> Years
			</p>
			<p>
			  <b>Warranty Start:</b>
			<?php echo $row['warranty_start']; ?>
			</p>
	    </div>
	  </div>
	</div> 
	  	<div class="row">
	  	<div class="large-5 columns">
	  	    <div class="panel">
	  	      <h5>Purchasing Info</h5>
			<p>
			  <b>Purchase date:</b>
			<?php 
			echo $row['purchase_date']->format('Y-m-d H:i:s');?>
			</p>

			<p>
			  <b>Purchase price:</b>
			  <?php echo $row['purchase_price']; ?>
			</p>

			<p>
			  <b>Purchase acct:</b>
			  <?php echo $row['purchase_acct']; ?>
			</p>
			<br />
			<p>
			  <b>Year for Replacement:</b>
			  <?php echo $row['replacement_year']; ?>
			</p>
			<br />
	  	    </div>
	  	  </div>
	<?php 
	}
	$selectNewInsert = "Select * from dbo.hardware_assignments where computer = " . $computer_id . " AND end_assignment IS NULL";
	$computer_id_result = sqlsrv_query($conn, $selectNewInsert);
	$primaryVal;
	$fullVal;
	while( $row = sqlsrv_fetch_array( $computer_id_result, SQLSRV_FETCH_ASSOC) )
	{
			// Parse out the content that is not human-readable
			$assignmentVal = "N/A";
			if (getAssignmentTypeOutputFromValue($row['assignment_type']))
			{
				$assignmentVal = getAssignmentTypeOutputFromValue($row['assignment_type']);
			}
			$primaryVal = $row['primary_computer'];
			$fullVal = $row['full_time'];
	 ?>	

	  <div class="large-5 columns">
	      <div class="panel">
	        <h5>Assignment Info</h5>
				<p>
				  <b>Department:</b>
				<?php echo $row['department_id']; ?>
				</p>
				<p>
				  <b>User:</b>
				  <?php 

				  $selectUser = "Select FirstName, LastName from dbo.FacandStaff where ID = " . $row['user_id'] . " ";
				  $userResult = sqlsrv_query($conn, $selectUser);
				  if($userResult)
				  {
				  	while( $row = sqlsrv_fetch_array( $userResult, SQLSRV_FETCH_ASSOC) )
				  	{
				        echo " " . $row['FirstName'] . "  " . $row['LastName'] .  " ";
				    }
				  }
				  else
				  {
					echo "N/A";
				  }
				  ?>
				</p>
				<p>
				  <b>Assignment Type:</b>
				  <?php echo $assignmentVal; ?>
				</p>
				<p>
				  <b>Is full time:</b>
				  <?php echo $fullVal; ?>
				</p>
				<p>
				  <b>Is primary unit:</b>
				  <?php echo $primaryVal; ?>
				</p>
				<br />
				<p>
	  		</p>	
	      </div>
	    </div>
	<?php 

	}
	?>
	

	  	</div>
		</div>
	  		</div> 

		<div class="row">
	  		<fieldset>
	  		<?php $panelNum = 1;?>
	  		<legend>Comments</legend>
		  		<div class="large-10 large-centered columns">
		  		<dl class="accordion" data-accordion>
		  		<?php 

		  		$changeQuery = "SELECT *
				FROM changes
				WHERE changes.computer_id = $computer_id
				ORDER BY created_at DESC;";

				$changeResults = sqlsrv_query($conn, $changeQuery);
				sqlsrvErrorLinguist($changeResults, "Problem with getting comments for unit");
				while ($thisChange = sqlsrv_fetch_array($commentresult))
				{
					$panelNum++;
					?>
  		  		<dd>
  		  			<?php echo "<a href=\"#panel" . $panelNum . "\">"; ?>Commentary made at <?php  echo " " . $thisChange['created_at']->format('Y-m-d H:i:s') . " by " . $thisChange['user_name'] . " "; ?></a>
		  		  	<?php echo "<div id=\"panel" . $panelNum . "\" class=\"content\">"; ?><kbd>
					<?php

					echo $thisChange['body'];
					?>
					</kbd></div>
		  		</dd>
					<?php
				}

		  		?>
		  		</dl>
		  		</fieldset>
		  		</div>
	  		</div>

	  		<div class="row">
	  		<fieldset>
	  		<legend>Item History</legend>
		  		<div class="large-10 large-centered columns">
		  		<dl class="accordion" data-accordion>
		  		<?php 

		  		$changeQuery = "SELECT *
				FROM changes
				WHERE changes.computer_id = $computer_id
				ORDER BY created_at DESC;";

				$changeResults = sqlsrv_query($conn, $changeQuery);
				sqlsrvErrorLinguist($changeResults, "Problem with getting change info for unit");
				while ($thisChange = sqlsrv_fetch_array($changeResults))
				{
					$panelNum++;
					?>
  		  		<dd>
  		  			<?php echo "<a href=\"#panel" . $panelNum . "\">"; ?>Change made on<?php  echo " " . $thisChange['created_at']->format('Y-m-d H:i:s') . " by " . $thisChange['creator'] . " "; ?></a>
		  		  	<?php echo "<div id=\"panel" . $panelNum . "\" class=\"content\">"; ?>
					<?php
					$formattedChange = str_replace("\n", "<br />", $thisChange['body']);
					$formattedChange = str_replace("*** CHANGED ***", "<kbd>*** CHANGED ***</kbd>", $formattedChange);
					$formattedChange = str_replace("*** ASSIGNMENT ADDED ***", "<kbd>*** ASSIGNMENT ADDED ***</kbd>", $formattedChange);
					$formattedChange = str_replace("*** ASSIGNMENT REMOVED ***", "<kbd>*** ASSIGNMENT REMOVED ***</kbd>", $formattedChange);
					echo $formattedChange;
					?>
					</div>
		  		</dd>
					<?php
				}

		  		?>
		  		<dd>
		  		  <a href="#panel1">Created on <?php  echo " " . $created_on->format('Y-m-d H:i:s') . " by " . $created_by . " "     ; ?> 	</a>
		  		</dd>
		  		</dl>
		  		</fieldset>
		  		</div>
	  		</div>

	<?php
	} else {
		echo('<div data-alert class="alert-box warning"> Sorry! That item doesn\'t exist! <a href="#" class="close">&times;</a></div>');
	}

?>


<?php
}
include('footer.php')
?>