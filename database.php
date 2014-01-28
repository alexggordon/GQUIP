<?php
//<!!!> all the lines of functional code that are commented in this version are already present
// -- open_db.php/close_db.php

// $myServer = "admintrainsql.gordon.edu";
// $myUser = "alex.gordon@gordon.edu";
// $myPass = "7132a8b2p45kldr69_";
// $myDB = "CTSEquipment"; 

// $dbhandle = mssql_connect($myServer, $myUser, $myPass) 
	// or die("Couldn't connect to $myServer"); 

//select a database to work with
// $selected = mssql_select_db($myDB, $dbhandle) or die("Couldn't open database $myDB"); 

//declare the SQL statement that will query the database
$query = "SELECT id, name, year ";
$query .= "FROM cars ";
$query .= "WHERE name='BMW'"; 

//execute the SQL query and return records
$result = mssql_query($query);

$numRows = mssql_num_rows($result); 
echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>"; 

//display the results 
while($row = mssql_fetch_array($result))
{
  echo "<li>" . $row["id"] . $row["name"] . $row["year"] . "</li>";
}
//close the connection
// mssql_close($dbhandle);
?>