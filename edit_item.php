<?php
include('config.php');
include('header.php')
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
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




<?php
include('footer.php')
?>