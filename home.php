<?php
// *************************************************************
// file: home.php
// created by: Alex Gordon, Elliott Staude
// date: 02-27-2014
// purpose: allowing a user of the GQUIP database to access the content within by way of authenticate.php
// part of the collection of files for the GQUIP project, designed for Gordon College, 2013-2014
// *************************************************************

// include nav bar and other default page items
include('header.php');

// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}

  //The following segments consult with the permissions of the user and
  //accordingly render the page and/or allow the user to perform certain
  //actions based on the permissions level

if($_SESSION['access']==ADMIN_PERMISSION  OR $_SESSION['access']==USER_PERMISSION ) {
include 'paginate.php';
include 'getPage.php';

// CSV file preparation for user download
// If the user requests a CSV copy of computer info, the content here (stored in
// ... GQUIPComputers.csv) is given to the user for download
$CSVQuery = "SELECT
  computers.control as control,
  computers.manufacturer as manufacturer,
  computers.model as model,
  computers.hard_drive as hard_drive,
  computers.memory as computer_memory,
  computers.part_number as computer_part_number,
  computers.computer_type as computer_type,
  computers.serial_num as computer_serial,
  computers.usage_status as computer_usage_status,
  computers.inventoried as computer_inventoried,
  computers.warranty_length as computer_warranty_length,
  computers.warranty_start as computer_warranty_start,
  computers.warranty_type as computer_warranty_type,
  computers.purchase_date as computer_purchase_date,
  computers.purchase_price as computer_purchase_price,
  computers.purchase_acct as computer_purchase_acct,
  computers.replacement_year as computer_replacement_year,
  computers.cameron_id as computer_cameron_id,
  computers.ip_address as computer_ip,
  computers.last_updated_by as updater_of_computer,
  computers.last_updated_at as computer_updated_at,
  computers.created_at as computer_created_at,
  hardware_assignments.assignment_type as assignment_type,
  hardware_assignments.department_id as department_id,
  hardware_assignments.full_time as full_time,
  hardware_assignments.primary_computer as is_primary,
  hardware_assignments.start_assignment as start_assignment,
  hardware_assignments.end_assignment as end_assignment,
  hardware_assignments.last_updated_by as updater_of_hardware_assignment,
  hardware_assignments.last_updated_at as hardware_assignment_updated_at,
  hardware_assignments.created_at as hardware_assignment_created_at,
  hardware_assignments.nextneed_note as notes,
  FacandStaff.FirstName as FirstName,
  FacandStaff.LastName as LastName,
  FacandStaff.OnCampusDepartment as FacandStaff_Department,
  FacandStaff.Dept as FacandStaff_DPT,
  FacandStaff.Type as FacandStaff_type,
  FacandStaff.Email as FacandStaff_email
FROM computers
LEFT JOIN hardware_assignments
ON computers.computer_id = hardware_assignments.computer
LEFT JOIN FacandStaff
ON FacandStaff.ID = hardware_assignments.user_id
WHERE hardware_assignments.end_assignment IS NULL
ORDER BY computers.control;";



// Get data from the database via query
include('open_db.php');

$CSVResult = sqlsrv_query($conn, $CSVQuery);
sqlsrvErrorLinguist($CSVResult, "Problem with getting computer info for CSV");

// Prepare the query-returned data 
$CSVFile = 'GQUIPComputers.csv';
$fp = fopen($CSVFile, 'w');
$colNameFlag = 0;

while($thisHereItem = sqlsrv_fetch_array($CSVResult, SQLSRV_FETCH_ASSOC))
{
    $cleansedArray = NULL;

    // If needed, set up the names of each of the table columns as a row
    if ($colNameFlag == 0)
    {
        $colNameFlag = 1;
        fputcsv($fp, array_keys($thisHereItem));
    }

    // Print each item's information to the csv file
    foreach ($thisHereItem as $column)
    {
        if ($column instanceof DateTime)
        {
            $cleansedArray[] = $column->format('Y-m-d H:i:s');
        }
        else
        {
            $cleansedArray[] = $column;
        }
    }
    fputcsv($fp, $cleansedArray);
}
fclose($fp);


// Set up ordering

// Find the requested content ordering
// When the sorting is the same kind as before, then "reverse" the ordering from previous
$sortMarker;
$orderingWatch;
if (isset($_GET['sorting']))
{
    $sortMarker = $_GET['sorting'];
    switch ($sortMarker)
    {   
      case "Control":
        $orderingWatch = "case when control is null then 1 else 0 end, control";
        break;    
      case "FlipControl":
        $orderingWatch = "control DESC";
        break;
      case "Model":
        $orderingWatch = "case when model is null then 1 else 0 end, model";
        break;
      case "FlipModel":
        $orderingWatch = "model DESC";
        break;
      case "Manufacturer":
        $orderingWatch = "case when manufacturer is null then 1 else 0 end, manufacturer";
        break;
      case "FlipManufacturer":
        $orderingWatch = "manufacturer DESC";
        break;
    }
}
else
{
    $sortMarker = "Control";
    $orderingWatch = "case when control is null then 1 else 0 end, control";
}

// Query
if (isset($_POST['searchTerms'])) {

    $strippedQuery = strip_tags($_POST['searchTerms']);

     $query = "SELECT *
     FROM computers
     where control LIKE '" . $strippedQuery  . "'
     OR model LIKE '" . $strippedQuery  . "'
     OR manufacturer LIKE '" . $strippedQuery  . "'
     ORDER BY " . $orderingWatch . ";";
     // $query = "SELECT *
     // FROM computers
     // where CONTAINS (control, '" . $strippedQuery  . "')
     // OR CONTAINS (model, '" . $strippedQuery . "')
     // OR CONTAINS (manufacturer, '" . $strippedQuery . "')
     // ORDER BY " . $orderingWatch . ";";



     ?>
     <div class="row">
         <div class="large-10 large-centered columns">
         <h1>Searching for <?php echo $strippedQuery; ?></h1>
     <?php 

} else {
  $query = "SELECT *
  FROM computers
  ORDER BY " . $orderingWatch . ";";
  ?>

  <div class="row">
      <div class="large-10 large-centered columns">
      <h1>Home</h1>

  <?php 
}



  // query 2, for all items we need

  $result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
  sqlsrvErrorLinguist($result, "Problem with getting computer data");
  $num_rows = sqlsrv_num_rows($result); 
   $thereIsData = sqlsrv_has_rows( $result );
  // data for pagination

  // This is the syntax we pass to the paginator.php to give us our numbers
  // the left page link
  $aLeft = 'home.php?&page=';
  // the right page link. If blank, then left will be used. 
  $aRight = '';
  // do we want to show the fancy arrows?
  $sArrows = TRUE;
  // rows of items per page
  $rowsPerPage = 25;
  // number of pages equals number of items divided by how many we show per page
  $numOfPages = ceil($num_rows/$rowsPerPage);

  // some quick math to find out what page we're on
  if (isset($_GET['page'])) {
      $page = $_GET['page'];
      if ($page == 0) {
        $pageNum = 1;
      } else {
        $pageNum = (($page / $rowsPerPage) + 1);
      }
  } else {
    $pageNum = 1;
  }

  // This finds out our current starting place (or item)
  if (isset($_GET['page'])) {
    $pCurrent = $_GET['page'];
  } else {
    $pCurrent = 0;
  }

  // gets the correct SQL Data. This makes a call to the getPage.php function
  $page = getPage($result, $pageNum, $rowsPerPage);

  ?>
<!-- breadcrumbs -->
    <ul class="breadcrumbs">
      <li class="current"><a href="#">Home</a></li>
    </ul>
    </div>
    </div>
  <div class="row">
    <div class="large-10 large-centered columns">

<?php 
if ($thereIsData) {

 ?>

  <table cellspacing= "0" class="responsive">
   <thead>
    <tr>
      <th width="100"><?php if ($sortMarker == "Control"){echo "<a href='home.php?sorting=FlipControl'>";} else {echo "<a href='home.php?sorting=Control'>";} ?>Control</a></th>
      <th width="200"><?php if ($sortMarker == "Model"){echo "<a href='home.php?sorting=FlipModel'>";} else {echo "<a href='home.php?sorting=Model'>";} ?>Model</a></th>
      <th width="100"><?php if ($sortMarker == "Manufacturer"){echo "<a href='home.php?sorting=FlipManufacturer'>";} else {echo "<a href='home.php?sorting=Manufacturer'>";} ?>Manufacturer</a></th>
      <th width="200">Dept</th>
      <th width="100">User</th>
      <th width="100">Edit</th>
    </tr>
    </thead>
    <?php
    

    foreach($page as $row)
    {
        $computerAssignQuery = "SELECT * from hardware_assignments where computer = " . $row[0] . " AND end_assignment IS NULL;";
        $assignResult = sqlsrv_query($conn, $computerAssignQuery);
        sqlsrvErrorLinguist($assignResult, "Problem with getting assignment data");
        $asgnRow = sqlsrv_fetch_array($assignResult);
        $firstNameHolder = "N/A";
        $lastNameHolder = "N/A";
        $departmentName;
        if (isset($asgnRow) && $asgnRow['user_id'] != NULL)
        {
            $holderQuery = "SELECT FirstName, LastName from FacandStaff where ID = " . $asgnRow['user_id'] . ";";
            $holderResult = sqlsrv_query($conn, $holderQuery);
            sqlsrvErrorLinguist($holderResult, "Problem with getting user data");
            $holderRow = sqlsrv_fetch_array($holderResult);
            $firstNameHolder = $holderRow['FirstName'];
            $lastNameHolder = $holderRow['LastName'];
        }

        $item = "<tr><td><a href=\"info.php?id=" . $row[4] . "\">" . $row[4]  . "</a></td><td>" . $row[6] . "</td><td>" . $row[7] . "</td><td>";
        if ($firstNameHolder != "N/A")
        {
          $item .=  $asgnRow['department_id'] . "</td><td>" . $firstNameHolder . " " . $lastNameHolder . "</td><td><a class=\"button tiny\" href=\"edit_item.php?control=" . $row[4] . "\">Edit</a></td></tr>";
        }
        else
        {
          $item .= $asgnRow['department_id'] . "</td><td></td><td><a class=\"button tiny\" href=\"edit_item.php?control=" . $row[4] . "\">Edit</a></td></tr>";
        }
        echo $item;

    }

  ?>

    </table>
      <div class="row">
      <div class="large-9 large-centered columns">
    <?php

    // Spit out the pagination info. This makes a call to the paginate.php function. 
    echo PHPagination($pCurrent, $num_rows, $aLeft, $aRight, $rowsPerPage, $sArrows);


    } else {
        if (isset($_POST['searchTerms'])) {
            $strippedQuery = strip_tags($_POST['searchTerms']);
            $query = "SELECT ID, FirstName, LastName, Email, OnCampusDepartment, type
            FROM dbo.FacandStaff 
            where ID LIKE '" . $strippedQuery . "'
            OR FirstName LIKE '" . $strippedQuery . "'
            OR LastName LIKE '" . $strippedQuery . "'
            OR Email LIKE '" . $strippedQuery . "'
            OR OnCampusDepartment LIKE '" . $strippedQuery . "'
            OR type LIKE '" . $strippedQuery . "' ;";

            $result = sqlsrv_query($conn, $query);
            $thereIsNewData = sqlsrv_has_rows( $result );
            if ($thereIsNewData ) {
              ?>
                  <div class="row" align="center">
                  <div class="large-9 large-centered columns">
                  <h1>There's no search results here, but it looks like FacStaff has some!</h1>
                  <form data-abide type="submit" id="searchTerms" enctype='multipart/form-data' action="/faculty.php"  method="POST">
                  <?php
                    echo "<input type='hidden' name=\"searchTerms\" value='". $strippedQuery  ."'>";
                  ?>
                  <input id="searchTerms" type="submit"  class="button" formmethod="POST" value="Go to FacStaff"></input>
                  </form>


              <?php 
            } elseif (!$thereIsNewData ) {
                  ?>
                      <div class="row">
                      <div class="large-10 large-centered columns">
                      <h1>There's no search results.</h1>
                  <?php 
            } else {
                  ?>
                      <div class="row">
                      <div class="large-9 large-centered columns">
                      <h1>There's no data.</h1>
                  <?php 
            }

       } else {
        ?>
        <div class="row">
        <div class="large-9 large-centered columns">
        <h1>There's nothing here.</h1>
        <?php
       }
  }
    // close the database connection
    sqlsrv_close( $conn );


    ?>
    </div>
    </div>
<?php if ($_SESSION['access']==ADMIN_PERMISSION)
    {
    ?>
        <a href="GQUIPComputers.csv" target="_blank" class="small button">Get CSV Record of Units</a>
    <?php
    }
}
include('footer.php');
?>

