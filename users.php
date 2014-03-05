<?php
include('config.php');
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead


//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data


//The connection to the database is closed through the script close_db

// $numRows = mssql_num_rows($result);
//echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>";

//display the results
// while($row = sqlsrv_fetch_array($result))
// {
  //A sub-query must be done here to find the licenses for a given user
  //and does the same thing as above, but within this loop iteration

  // $cur_user_id = $row["id"];
  // $licensequery = "SELECT * FROM Licenses JOIN Software ON Licenses.software_id = Software.id WHERE Licenses.user_id = $cur_user_id ORDER BY name;";


  // $licenseresult = mssql_query($licensequery);

  //The user's licensed software data is included here
  // while($licenserow = sqlsrv_fetch_array($licenseresult))
  // {

  // 	echo " && " . $licenserow["Software.name"];

  // }

// }
include('paginate.php');
$query = "SELECT  ID, FirstName, LastName, Email, Class
FROM     (SELECT  ROW_NUMBER() OVER (ORDER BY FirstName ASC) AS Row,
          ID, FirstName, LastName, Email, Class
FROM    dbo.gordonstudents) tmp
WHERE   Row >= 50 AND Row <= 100";

//A connection to the database is established through the script open_db

include('open_db.php');
$result = sqlsrv_query($conn,
                       $query);
// If user or administrator
if($_SESSION['access']=="3"  OR $_SESSION['access']=="1" ) {
  ?>
  <div class="row">
    <div class="large-10 large-centered columns">
    <h1>Users</h1>
    </div>
    </div>
  <div class="row">
    <div class="large-10 large-centered columns">
  <table cellspacing="0">
   <thead>
    <tr>
      <th>Last Name</th>
      <th>First Name</th>
      <th>Class</th>
    <th>Email</th>
    </tr>
    </thead>

  <?php

  while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
  {
     echo "<tr><td>" . $row['LastName'] . "</td><td>" . $row['FirstName'] . "</td><td>" . $row['Class'] . "</td><td>" . $row['Email'] . "</td></tr>";
  }

 //$row['index'] the index here is a field name
  
  ?>

  </table>
  </div>
  </div>
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
