<?php
include('config.php');
include('header.php')
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
if($_SESSION['access']=="3" ) {
?>


<?php
}
if($_SESSION['access']=="2" ) {
?>


<?php
}
if($_SESSION['access']=="1" ) {
?>

<?php
}
?>




<?php
include('footer.php')
?>