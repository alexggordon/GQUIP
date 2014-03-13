<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
// Manager
if($_SESSION['access']==ADMIN_PERMISSION ) {
?>

<!-- Page html goes here -->
<!-- no need for html tags or body tags. Just raw page html. -->


<?php
}
// Faculty
if($_SESSION['access']==FACULTY_PERMISSION ) {
?>

<!-- Page html goes here -->
<!-- no need for html tags or body tags. Just raw page html. -->

<?php
}
// User
if($_SESSION['access']==USER_PERMISSION ) {
?>

<!-- Page html goes here -->
<!-- no need for html tags or body tags. Just raw page html. -->

<?php
}
include('footer.php')
?>