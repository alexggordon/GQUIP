<?php
include('config.php');
include('header.php')
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$query = "SELECT * 
FROM Software
ORDER BY name;";

//A connection to the database is established through the script open_db

include('open_db.php');

//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data

$result = mssql_query($query);

//The connection to the database is closed through the script close_db

include('close_db.php');

$numRows = mssql_num_rows($result); 
echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>"; 

//display the results 
while($row = mssql_fetch_array($result))
{
  echo "<li>" . $row["name"] . $row["software_type"] . $row["last_updated_by"] . " | EDIT_BUTTON_FOR_" . $row["name"] . " | " . "</li>";
}

//The following segments consult with the permissions of the user and
//accordingly render the page and/or allow the user to perform certain
//actions based on the permissions level


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