<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Manager
if($_SESSION['access']=="3" ) {
?>

<!-- Page html goes here -->
<!-- no need for html tags or body tags. Just raw page html. -->


<?php
}
// Faculty
if($_SESSION['access']=="2" ) {
?>

<!-- Page html goes here -->
<!-- no need for html tags or body tags. Just raw page html. -->

<?php
}
// User
if($_SESSION['access']=="1" ) {
?>

<!-- Page html goes here -->
<!-- no need for html tags or body tags. Just raw page html. -->

<?php
}
include('footer.php')
?>