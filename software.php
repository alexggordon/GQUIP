<?php
// *************************************************************
// file: Software.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: A page used for displaying the data of all varieties of software that can be licensed out by Gordon College to a Gordon student.
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}

// CSV file preparation for user download
// If the user requests a CSV copy of computer info, the content here (stored in
// ... GQUIPLicenses.csv) is given to the user for download
$CSVQuery = "SELECT
gordonstudents.FirstName as FirstName,
gordonstudents.MiddleName as MiddleName,
gordonstudents.LastName as LastName,
gordonstudents.Class as Class,
gordonstudents.Email as Email,
gordonstudents.grad_student as IsGradStudent,
licenses.date_sold as license_date_sold,
licenses.seller as license_seller,
licenses.last_updated_by as license_last_updated_by,
licenses.last_updated_at as license_last_updated_at,
licenses.created_at as license_created_at,
software.name as software_name,
software.software_type as software_type,
software.last_updated_by as software_last_updated_by,
software.last_updated_at as software_last_updated_at,
software.created_at as software_created_at
FROM gordonstudents
LEFT JOIN licenses
ON gordonstudents.id = licenses.id
LEFT JOIN software
ON licenses.software_id = software.index_id
WHERE licenses.index_id IS NOT NULL
ORDER BY software.name;";

//A connection to the database is established through the script open_db
include('open_db.php');

$CSVResult = sqlsrv_query($conn, $CSVQuery);
sqlsrvErrorLinguist($CSVResult, "Problem with getting license info for CSV");

// Prepare the query-returned data 
$CSVFile = 'GQUIPLicenses.csv';
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
      case "Name":
        $orderingWatch = "case when name is null then 1 else 0 end, name";
        break;    
      case "FlipName":
        $orderingWatch = "name DESC";
        break;
      case "Type":
        $orderingWatch = "case when software_type is null then 1 else 0 end, software_type";
        break;
      case "FlipType":
        $orderingWatch = "software_type DESC";
        break;
      case "UpdBy":
        $orderingWatch = "case when last_updated_by is null then 1 else 0 end, last_updated_by";
        break;
      case "FlipUpdBy":
        $orderingWatch = "last_updated_by DESC";
        break;
      case "UpdAt":
        $orderingWatch = "case when last_updated_at is null then 1 else 0 end, last_updated_at";
        break;
      case "FlipUpdAt":
        $orderingWatch = "last_updated_at DESC";
        break;
      case "CtdAt":
        $orderingWatch = "case when created_at is null then 1 else 0 end, created_at";
        break;
      case "FlipCtdAt":
        $orderingWatch = "created_at DESC";
        break;
    }
}
else
{
    $sortMarker = "Name";
    $orderingWatch = "case when name is null then 1 else 0 end, name";
}


//The set of SQL queries for the page is put together before connecting
//to the database to cut back on overhead

$query = "SELECT *
FROM Software
ORDER BY " . $orderingWatch . ";";

//The mssql_query function allows PHP to make a query against the database
//and returns the resulting data

$result = sqlsrv_query($conn, $query);
sqlsrvErrorLinguist($result, "Problem with getting software information");
$numRows = sqlsrv_num_rows($result);

// If user or administrator
if($_SESSION['access']==ADMIN_PERMISSION  OR $_SESSION['access']==USER_PERMISSION ) {
  ?>
  
  
<div class="row">
	<div class="large-10 large-centered columns">
		  <h1>Software</h1>
		  <ul class="breadcrumbs">
		  	<li><a href="home.php">Home</a></li>
		  	<li class="current"><a href="#">Software</a></li>
		  </ul>
		  </div>
		  <div class="large-10 large-centered columns">
			  <table cellspacing= "0" class="responsive expand">
				<thead>
				<tr>
				  <th width="100"><?php if ($sortMarker == "Name"){echo "<a href='software.php?sorting=FlipName'>";} else {echo "<a href='software.php?sorting=Name'>";} ?>Name</th>
				  <th width="100"><?php if ($sortMarker == "Type"){echo "<a href='software.php?sorting=FlipType'>";} else {echo "<a href='software.php?sorting=Type'>";} ?>Software type</th>
				  <th width="100"><?php if ($sortMarker == "UpdBy"){echo "<a href='software.php?sorting=FlipUpdBy'>";} else {echo "<a href='software.php?sorting=UpdBy'>";} ?>Last updated by</th>
				  <th width="100"><?php if ($sortMarker == "UpdAt"){echo "<a href='software.php?sorting=FlipUpdAt'>";} else {echo "<a href='software.php?sorting=UpdAt'>";} ?>Last updated at</th>
				  <th width="75"><?php if ($sortMarker == "CtdAt"){echo "<a href='software.php?sorting=FlipCtdAt'>";} else {echo "<a href='software.php?sorting=CtdAt'>";} ?>Created at</th>
				  <th width="25"></th>
				</tr>
				</thead>
	
			  <?php
	
			  while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			  {
				 echo "<tr><td>" . $row['name'] . "</td><td>" . $row['software_type'] . "</td><td>" . $row['last_updated_by'] . 
				 "</td><td>" . $row['last_updated_at']->format('Y-m-d H:i:s') . "</td><td>" . $row['created_at']->format('Y-m-d H:i:s') . "</td><td><a class=\"button\" href=\"edit_software.php?edit=" . $row['index_id'] . "\">Edit</a></td></tr>";
			  }
	
			  ?>
	
			  </table>
              <?php if ($_SESSION['access']==ADMIN_PERMISSION)
        {
        ?>
            <a href="GQUIPLicenses.csv" target="_blank" class="small button">Get CSV Record of Licenses</a>
        <?php
        } ?>
	</div>
</div>
  
<?php
}
// Faculty
if($_SESSION['access']==FACULTY_PERMISSION ) {
header('Location: software.php');
}

//The connection to the database is closed through the script close_db

include('close_db.php');

include('footer.php');
?>