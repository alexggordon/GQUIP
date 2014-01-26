<?php

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (!isset($_GET['success'])) {
$get_success = "";
}
else {
$get_success = $_GET['success'];
}

if (!empty($_FILES)) { 

    /* Format the errors and die */
	
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

    /* connect */
    function connect() {
        if (!function_exists('sqlsrv_num_rows')) { // Insure sqlsrv_1.1 is loaded.
            die ('sqlsrv_1.1 is not available');
        }

        /* Log all Errors */
        sqlsrv_configure("WarningsReturnAsErrors", TRUE);        // BE SURE TO NOT ERROR ON A WARNING
        sqlsrv_configure("LogSubsystems", SQLSRV_LOG_SYSTEM_ALL);
        sqlsrv_configure("LogSeverity", SQLSRV_LOG_SEVERITY_ALL);

        $conn = sqlsrv_connect('cslogs', array
        (
        'UID' => 'mailreport',
        'PWD' => '123456',
        'Database' => 'Mail',
        'CharacterSet' => 'UTF-8',
        'MultipleActiveResultSets' => true,
        'ConnectionPooling' => true,
        'ReturnDatesAsStrings' => true,
        ));

        if ($conn === FALSE) {
            get_last_error();
        }

        return $conn;
    }

    function query($conn, $query) {
        $result = sqlsrv_query($conn, $query);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    /* Prepare a reusable query (prepare/execute) */
	
    function prepare ( $conn, $query, $params ) {
        $result = sqlsrv_prepare($conn, $query, $params);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    /*
    do the deed. once prepared, execute can be called multiple times
    getting different values from the variable references.
    */
	
    function execute ( $stmt ) {
        $result = sqlsrv_execute($stmt);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    function fetch_array($query) {
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        if ($result === FALSE) {
            get_last_error();
        }
        return $result;
    }

    $conn = connect();

    /* prepare the statement */
    $query = "INSERT Records values ( ? , ? , ? )";
    $param1 = null; // this will hold col1 from the CSV
    $param2 = null; // this will hold col2 from the CSV
	$param3 = null; // this will hold col3 from the CSV
    $params = array( $param1, $param2, $param3 );
    $prep = prepare ( $conn, $query, $params );
    //$result = execute ( $prep );

    //get the csv file 
	
    $file = $_FILES["csv"]["tmp_name"]; 
	
  /*
    Here is where you read in and parse your CSV file into an array.
    That may get too large, so you would have to read smaller chunks of rows.
  */
  
    $csv_array = file($file);
    foreach ($csv_array as $row_num => $row) {
        $row = trim ($row);
        $column = explode ( ',' , $row );
        $param1 = $column[0];
        $param2 = $column[1];
        $param3 = $column[2];

        // insert the row
		
        $result = execute ( $prep );
    }
	
/* Free statement and connection resources. */

sqlsrv_close($conn);
header( "Location: test.php?success=1" );
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
<title>Import a CSV File with PHP & MS SQL Server</title> 
</head> 

<body> 

<?php if (!empty($get_success)) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?> 

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
  Choose your file: <br /> 
  <input name="csv" type="file" id="csv" /> 
  <input type="submit" name="Submit" value="Submit" /> 
</form> 

</body> 
</html> 