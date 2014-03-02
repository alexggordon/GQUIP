<?php

// file: home.php
// created by: Alex Gordon, Elliott Staude
// date: 02-27-2014
// purpose: allowing a user of the GQUIP database to access the content within by way of authenticate.php
// part of the collection of files for the GQUIP project, designed for Gordon College, 2013-2014
// 
//

// Test Commment
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}


if($_SESSION['access']=="3" ) {

  //The set of SQL queries for the page is put together before connecting
  //to the database to cut back on overhead

  $query = "SELECT * FROM computers
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

    //Get the rows from hardware assignments, comments, and changes based on the "control" column
    $cur_control = $row["control"];

    //List of users assigned this unit over its lifespan
    $assignmentquery = "SELECT * 
    FROM hardware_assignments JOIN FacStaff ON FacStaff.ID = hardware_assignments.user_id ORDER BY control;";

    //List of comments made on this unit over its lifespan
    $commentquery = "SELECT * 
    FROM comments JOIN FacStaff WHERE comments.computer_id = $cur_control ORDER BY comment.created_at;";

    //List of changes made to this unit over its lifespan
    $changequery = "SELECT * 
    FROM changes JOIN computers WHERE changes.computer_id = $cur_control ORDER BY changes.created_at;";

    //Open the connection
    include('open_db.php');

    //Ask for the data relevant to the 3 queries related to this specific unit
    $assignmentresult = mssql_query($assignmentquery);

    $commentresult = mssql_query($commentquery);

    $changeresult = mssql_query($changequery);

    //Close the connection
    include('close_db.php');

    //Create the data for this specific unit
    echo "<li>" . $row["control"] . $row["model"] . $row["manufacturer"] . " | EDIT_BUTTON_FOR_" . $row["control"] . " | " . "</li>";

    //Output the assignment data for each of the computer's assignments
    while($assignmentrow = mssql_fetch_array($assignmentresult))
    {

      //Form is similar to:
      //User: Smith, J.; Start of assignment: 05/05/2000:19:22:28.289; End of assignment: 05/20/2000:11:20:46.94
      echo " User: " . $assignmentrow["users.last_name"] . ", " . $assignmentrow["users.first_name"] . 
      " Start of assignment: " . $assignmentrow["hardware_assignments.start_assignment"] .
      "; End of assignment: ". $assignmentrow["hardware_assignments.end_assignment"];
    
    }

    //Output the assignment data for each of the computer's changes
    while($changerow = mssql_fetch_array($changeresult))
    {
    
      //Form is similar to:
      // Change history: <LOTS OF ROWS OF TEXT HERE>; Change made: 05/05/2000:19:22:28.289
      echo " Change history: " . $changerow["changes.text"] . "; Change made: " . $changerow["changes.created_at"];
    
    }

    //Output the assignment data for each of the computer's comments
    while($commentrow = mssql_fetch_array($commentresult))
    {

      //Form is similar to:
      //J. Smith: I'm commenting on this unit! Posted at: 05/20/2000:11:20:46.94
      echo $commentrow["users.first_name"] . " " . $commentrow["users.last_name"] . ": " . 
      $commentrow["comment.text"] . " Posted at: "$commentrow["comment.created_at"];
    
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
