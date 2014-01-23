<?php
$myServer = "AdminTrainSQL.gordon.edu";
$myUser = "alex.gordon";
$myPass = "7132a8b2p45kldr69_";
$myDB = "CTSEquipment"; 
echo "1";

$dbhandle = mssql_connect($myServer, $myUser, $myPass) 
	or die("Couldn't connect to $myServer"); 

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle) or die("Couldn't open database $myDB"); 

echo "2";
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
mssql_close($dbhandle);
?>