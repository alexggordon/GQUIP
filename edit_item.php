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

  	$commentquery = "SELECT * FROM comments
  			WHERE comments.computer = $search;";
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




<?php
include('footer.php')
?>