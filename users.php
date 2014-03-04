<?php
include('config.php');
include('header.php');  
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$query = "SELECT * 
FROM Users
ORDER BY last_name, first_name;";

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
  //A sub-query must be done here to find the licenses for a given user
  //and does the same thing as above, but within this loop iteration

  $cur_user_id = $row["id"];
  $licensequery = "SELECT * FROM Licenses JOIN Software ON Licenses.software_id = Software.id WHERE Licenses.user_id = $cur_user_id ORDER BY name;";

  include('open_db.php');

  $licenseresult = mssql_query($licensequery);

  //The user's licensed software data is included here
  while($licenserow = mssql_fetch_array($licenseresult))
  {
  
  	echo " && " . $licenserow["Software.name"];
  
  }

}
// If user or administrator
if($_SESSION['access']=="3"  OR $_SESSION['access']=="1" ) {

  echo "<li>" . $row["last_name"] . $row["first_name"] . $row["role"] . $row["department"] . $row["auth"] . " | EDIT_BUTTON_FOR_" . $row["last_name"] . $row["first_name"] . " | " . "</li>";


  ?>



  <div class="row">
    <div class="large-10 large-centered columns">
  <table cellspacing="0">
   <thead>
    <tr>
      <th>Control</th>
      <th>Model</th>
      <th>Manufacturer</th>
    <th>Department</th>
    <th>User</th>
    <th>Assignment</th>
    <th>Options</th>
    </tr>
    </thead>
      <tr id="computer_3232" class="index-row false">
      <td><a href="computerdummydata.html">04180</a></td>
      <td>X300</td>
      <td>Lenovo</td>
      <td>President's Office</td>
      <td>Lindsay                       , D               M</td>
      <td>Full-Time Shared Non-standard</td>
      <td class="hide_for_printing"><a href="edit.html" class="button">Edit</a></td>
    </tr>
    </table>

  //The following segments consult with the permissions of the user and
  //accordingly render the page and/or allow the user to perform certain
  //actions based on the permissions level



<?php
}
// Faculty
if($_SESSION['access']=="2" ) {
?>

<?php
}
?>



<?php
include('footer.php')
?>