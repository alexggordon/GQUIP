<?php
include('config.php');
include('header.php')
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}
if($_SESSION['access']=="3" ) {
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<p>Access 3</p>
</body>
</html>


<?php
}
if($_SESSION['access']=="2" ) {
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<p>Access 2</p>
</body>
</html>


<?php
}
if($_SESSION['access']=="1" ) {
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<p>Access 1</p>
</body>
</html>


<?php
}
?>