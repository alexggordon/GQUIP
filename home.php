<?php
// Test Commment
include('config.php');
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
if($_SESSION['access']=="3" ) {
?>



<?php
}
if($_SESSION['access']=="2" ) {
?>

<a href="#" data-dropdown="drop1">Has Dropdown</a>
<ul id="drop1" class="f-dropdown" data-dropdown-content>
	<li><a href="#">This is a link</a></li>
	<li><a href="#">This is another</a></li>
	<li><a href="#">Yet another</a></li>
</ul>
<a href="#" data-dropdown="drop2">Has Content Dropdown</a>
<div id="drop2" data-dropdown-content class="f-dropdown content">
	<p>Some text that people will think is awesome! Some text that people will think is awesome! Some text that people will think is awesome!</p>
</div>

<?php
}
if($_SESSION['access']=="1" ) {
?>


<?php
}
include('footer.php');
?>
