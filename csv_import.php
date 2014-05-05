<?php

// *************************************************************
// file: csv_import.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The purpose of CSV import class is to import a CSV document into the database. This allows a user to add a significant amount of data to the database without 
// adding everything manually. 
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}
// Manager
if($_SESSION['access']==ADMIN_PERMISSION ) {
    $self = $_SERVER['PHP_SELF'];
    $request = $_SERVER['REQUEST_METHOD'];



// if (!isset($_GET['success'])) {
// $get_success = "";
// }
// else {
// $get_success = $_GET['success'];
// }

    if (isset($_POST['submit'])){
        echo "<div class=\"large-10 large-centered columns\">";
        echo "<h3 class=\"large-centered\">Data successfully inserted</h3>";
        echo "<a class=\"button\" href=\"home.php\">OK</a>";
        echo "</div>";
         //generic success notice 
    }

if (!empty($_FILES)) { 

    // this function gets errors from SQL Server and reports them. 
    function get_last_error() {
        $retErrors = sqlsrv_errors(SQLSRV_ERR_ALL);
        $errorMessage = 'No errors found';

        if ($retErrors != null) {
            $errorMessage = '';

            foreach ($retErrors as $arrError) {
                $errorMessage .= "SQLState: ".$arrError['SQLSTATE']."<br>\n";
                $errorMessage .= "Error Code: ".$arrError['code']."<br>\n";
                $errorMessage .= "Message: ".$arrError['message']."<br>\n";
            }
        }

        die ($errorMessage);
    }

    // connect to sql server. 
    function connect() {
        if (!function_exists('sqlsrv_num_rows')) { // Insure sqlsrv_1.1 is loaded.
            die ('sqlsrv is not available');
        }

        // server configurations
        sqlsrv_configure("WarningsReturnAsErrors", TRUE);        // BE SURE TO NOT ERROR ON A WARNING
        sqlsrv_configure("LogSubsystems", SQLSRV_LOG_SYSTEM_ALL);
        sqlsrv_configure("LogSeverity", SQLSRV_LOG_SEVERITY_ALL);

        // connect to the server
        $serverName = "sql05train1.gordon.edu";
        $connectionInfo = array(
        'Database' => 'CTSEquipment');
        $conn = sqlsrv_connect( $serverName, $connectionInfo);

        if ($conn === FALSE) {
            // if the function can't connect, get the last error and report it. 
            get_last_error();
        }

        return $conn;
    }

    // this runs the query against the server. 
    function query($conn, $query) {
        $result = sqlsrv_query($conn, $query);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    // this will prepare a reusable query. This allows for easy cacheing. 	
    function prepare ( $conn, $query, &$params ) {
        $result = sqlsrv_prepare($conn, $query, &$params);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    /*
    do the deed. once prepared, execute can be called multiple times
    getting different values from the variable references.
    */
    // execute the query. Once the query is perpared (see the function), execute can 
    // be called multiple times getting different values from the variables. 
	
    function execute ( $stmt ) {
        $result = sqlsrv_execute($stmt);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    // grabs the query result data off of SQL server. 
    function fetch_array($query) {
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    $conn = connect();

    // prepare the query statement. It is done in this form for easy modification. 
    $query = "INSERT dbo.computers ([last_updated_by], [last_updated_at], [created_at], [control], [serial_num], [model], [manufacturer], [memory], [hard_drive], [part_number], [purchase_date], [purchase_price], [purchase_acct], [replacement_year], [usage_status]) values ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?)";
    $lastUpdatedBy = null; // this will hold col1 from the CSV
    $lastUpdatedAtString = null; // this will hold col2 from the CSV
    $createdAtString = null; // this will hold col3 from the CSV
    $controlNumber = null; // this will hold col3 from the CSV
    $serialNumber = null; // this will hold col3 from the CSV
    $model = null; // this will hold col3 from the CSV
    $manufacturer = null; // this will hold col3 from the CSV
    $memory = null; // this will hold col3 from the CSV
    $hdSize = null; // this will hold col3 from the CSV
    $partNumber = null;
    $purchaseDate = null; // this will hold col3 from the CSV
    $purchasePrice = null; // this will hold col3 from the CSV
    $accountNumber = null; 
    $replacementYear = null; 
    $usage_status = null;

    $params = array( &$lastUpdatedBy, &$lastUpdatedAtString, &$createdAtString, &$controlNumber, &$serialNumber, &$model, &$manufacturer, &$memory, &$hdSize, &$partNumber, &$purchaseDate, &$purchasePrice, &$accountNumber, &$replacementYear, &$usage_status );
    $prep = prepare ( $conn, $query, &$params );
    //$result = execute ( $prep );

    //get the csv file 
	
    $file = $_FILES["csv"]["tmp_name"]; 
	
  /*
    Here is where you read in and parse your CSV file into an array.
    That may get too large, so you would have to read smaller chunks of rows.
  */

    if (isset($_SESSION['user'])) {
        $lastUpdatedBy = $_SESSION['user'];
    }

    $timezone = new DateTimeZone("UTC");
  
    $csv_array = file($file);
    foreach ($csv_array as $row_num => $row) {
        $row = trim ($row);
        $column = explode ( ',' , $row );
            $lastUpdatedBy = $_SESSION['user'];
            $lastUpdatedAt = new DateTime("now", $timezone);
            $lastUpdatedAtString = $lastUpdatedAt->format('Y-m-d H:i:s');
            $createdAtString = new DateTime("now", $timezone);
            $controlNumber = $column[0];
            $serialNumber = $column[1];
            $model = $column[2];
            $manufacturer = $column[3];
            $memory = $column[4];
            $hdSize = $column[5];
            $partNumber = $column[6];
            $purchaseDate = $column[7];
            $purchasePrice = $column[8];
            $accountNumber = $column[9];
            $replacementYear = $column[10];
            $usage_status = "circulation";

        // insert the row
        $result = execute ( $prep );
    }
	
/* Free statement and connection resources. */

sqlsrv_close($conn);
}
?>
<br>
<br>
<div class="row">
    <div class="large-12 columns">
        <h1>Welcome to the CSV data import page.</h1>
        <p>If you have yet to read the instructions, please refer to the <a href="/faq.php">GQUIP CSV import FAQ</a>. Please double check your file to make sure that adheres to the provided <a href="/template.csv">template</a>.</p>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
          <fieldset>
              <legend>Choose your file: </legend>
              <div class="large-12 columns">
                            <input name="csv" type="file" id="csv">
                            <input type="submit" name="Submit" value="Import" class="button" /> </div>
          </fieldset>
        </form> 
    </div>
</div>

<?php
}
// Faculty
if($_SESSION['access']==FACULTY_PERMISSION ) {

// Faculty can not access this page
header('Location: home.php');

}
// User
if($_SESSION['access']==USER_PERMISSION ) {

// User can not access this page
header('Location: home.php');

}
include('footer.php')
?>