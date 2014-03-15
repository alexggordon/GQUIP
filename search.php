<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION) {
?>

<div class="large-12 columns">
<h1>Search GQUIP</h1>

<?php
if (!isset($_POST['searchInput']))
{
	echo "NO SEARCH IS SET";
	$_POST['searchInput'] = "unassigned";
}
else
{
	echo "SEARCH IS SET";
}

?>

</div>

<?php
	}

	// Faculty
	if($_SESSION['access']==FACULTY_PERMISSION OR $_SESSION['access']==USER_PERMISSION ) {
	// Faculty and users should not have access to this page. 
	header('Location: home.php');
	}
	//footer
	include('footer.php')
?>