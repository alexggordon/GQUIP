<?php
// Test Commment
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}


if($_SESSION['access']=="3" ) {

  //The set of SQL queries for the page is put together before connecting
  //to the database to cut back on overhead

  $query = "SELECT * FROM computers 
  WHERE control IN 
  (SELECT Max(last_updated_at) FROM computers GROUP BY control) 
  ORDER BY last_updated_at DESC;";

  //A connection to the database is established through the script open_db

  include('open_db.php');

  //The mssql_query function allows PHP to make a query against the database
  //and returns the resulting data

  $result = mssql_query($query);

  //The connection to the database is closed through the script close_db

  include('close_db.php');

  $numRows = mssql_num_rows($result); 
  // echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>"; 

  //display the results 
  while($row = mssql_fetch_array($result))
  {

    $cur_control = $row["control"];
    $assignmentquery = "SELECT * FROM hardware_assignments JOIN users ON users.id = hardware_assignments.user_id WHERE hardware_assignments.control = $cur_control ORDER BY control;";

    $commentquery = "SELECT * FROM comments JOIN WHERE comments.control = $cur_control ORDER BY comment;";

    include('open_db.php');

    $assignmentresult = mssql_query($assignmentquery);

    include('close_db.php');

    echo "<li>" . $row["control"] . $row["model"] . $row["manufacturer"] . " | EDIT_BUTTON_FOR_" . $row["control"] . " | " . "</li>";

    while($assignmentrow = mssql_fetch_array($assignmentresult))
    {
    
      echo " && " . $assignmentrow["users.last_name"] . $assignmentrow["users.first_name"] . $assignmentrow["hardware_assignments.start_assignment"] . $assignmentrow["hardware_assignments.end_assignment"];
    
    }



  }

  //The following segments consult with the permissions of the user and
  //accordingly render the page and/or allow the user to perform certain
  //actions based on the permissions level


}
if($_SESSION['access']=="2" ) {
?>


<?php
}
if($_SESSION['access']=="1" ) {
?>


<?php
}
include('footer.php');
?>
