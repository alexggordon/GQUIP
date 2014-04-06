<?php
<<<<<<< HEAD
// *************************************************************
// file: info.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose:  The purpose of this page is to display all the information about an equipment item. 
// 
// *************************************************************
=======
>>>>>>> d43e4053f086f079cc512432daaab90ef7aea892
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Manager
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']==FACULTY_PERMISSION OR $_SESSION['access']==USER_PERMISSION) {
	include 'open_db.php';
	if (isset($_GET['id'])) {
	$control = $_GET['id'];
	$selectNewInsert = "Select * from dbo.computers where control = " . $control . " ";
	$computer_id_result = sqlsrv_query($conn, $selectNewInsert);
	while( $row = sqlsrv_fetch_array( $computer_id_result, SQLSRV_FETCH_ASSOC) ) {
	      $computer_id = $row['computer_id'];
	      $created_on = $row['created_at'];
	      $created_by = $row['last_updated_by'];
	
	?>

	<div class="row">
	<h1 class="docs header">Details for <?php echo $control ; ?></h1>
		</div>
	  <!-- First Band (Image) -->
	<div class="row"> 
	<div class="large-12 columns"> 
	<div class="row">
	<div class="large-6 columns">
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
	 		  <?php echo $row['computer_type']; ?>
	 		</p>
	 		<p>
	 		
	 	    </p>
	</div>
	  </div>
	  <div class="large-6 columns">
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
			  <?php echo $row['warranty_length']; ?> Years
			</p>
			<p>
			  <b>Warranty Start:</b>
			<?php echo $row['warranty_start']; ?>
			</p>
	    </div>
	  </div>
	</div> 
	  	<div class="row">
	  	<div class="large-6 columns">
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
	$selectNewInsert = "Select * from dbo.hardware_assignments where computer = " . $computer_id . " ";
	$computer_id_result = sqlsrv_query($conn, $selectNewInsert);
	while( $row = sqlsrv_fetch_array( $computer_id_result, SQLSRV_FETCH_ASSOC) ) {

	 ?>	

	  <div class="large-6 columns">
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
				  while( $row = sqlsrv_fetch_array( $userResult, SQLSRV_FETCH_ASSOC) ) {
				        echo " " . $row['FirstName'] . "  " . $row['LastName'] .  " ";
				    }
				  ?>
				</p>
				<p>
				  <b>Assignment Type:</b>
				  <?php echo $row['assignment_type']; ?>
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
	  		<legend>Item History</legend>
		  		<div class="large-12 columns">
		  		<div class="accordion" data-accordion>
					
		  		<dd>
		  		  <a href="#panel3">Created on <?php  echo " " . $created_on->format('Y-m-d') . " by " . $created_by . " "     ; ?> 	</a>
		  		  <div id="panel3" class="content">
		  		  PHP
		  		  </div>
		  		</dd>

		  		</div>
		  		</div>
	  		</div>


	  		</fieldset>

	<?php
	} else {
		echo('<div data-alert class="alert-box warning"> Sorry! That item doesn\'t exist! <a href="#" class="close">&times;</a></div>');
	}

?>


<?php
}
include('footer.php')
?>