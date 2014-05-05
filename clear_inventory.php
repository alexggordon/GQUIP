<?php 

// *************************************************************
// file: clear_inventory.php
// created by: Alex Gordon, Elliott Staude
// date: 04-22-2014
// purpose: A page used for clearing the inventory status of all computers on record.
//
// *************************************************************

// include nav bar and other default page items
include('header.php');

// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) 
{
  header('Location: login.php');
}

//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level

// Faculty
if($_SESSION['access']==FACULTY_PERMISSION || $_SESSION['access']==USER_PERMISSION )
{
	header('Location: home.php');
}

// User or Manager 
if($_SESSION['access']==ADMIN_PERMISSION) 
{

?>

<div class="row">
    <div class="large-10 large-centered columns">
    <h1>Clear Inventory</h1>
    <ul class="breadcrumbs">
      <li><a href="home.php">Home</a></li>
      <li class="current"><a href="#">Clear Inventory</a></li>
    </ul>
    </div>
    </div>


<?php

	// This is the BIG RED BUTTON - resets the inventory status of all computers on record

	if (isset($_POST['ResetInventory']))
	{
		include('open_db.php');
		$clearingQuery = "UPDATE computers
		SET inventoried = 0";

		// Run query
		$clearingResult = sqlsrv_query($conn, $clearingQuery);

		// Make sure that the query went through successfully
		if(!$clearingResult)
		{
			echo print_r( sqlsrv_errors(), true);
			exit;
		}
		sqlsrv_close( $conn);
		echo "<div class=\"large-8 large-centered columns\">";
		echo "<h3 class=\"large-centered\">Data successfully cleared</h3>";
		echo "</div>";
	}
	?>


  <form method="post" action="">
	  <div class="row">
	    <div class="large-4 large-centered columns">
	  		<dl class="accordion large-12 columns" data-accordion>
			  <dd>
				<a href="#deletePanel">Reset inventory</a>
				<div id="deletePanel" class="content alert">
					<p>Are you sure you want to clear inventory and reset all computers to noninventoried status? This action cannot be undone.</p>
					<input type="submit" name="ResetInventory" value="Reset inventory" class="button alert">
				</div>
			  </dd>
			</dl>
	    </div>
	  </div>
  </form>

	<?php

}
include('footer.php')
?>