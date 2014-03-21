<?php

// working as of 2/12/14
$serverName = "sql05train1.gordon.edu";
$connectionInfo = array(
'Database' => 'CTSEquipment');
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn )
{
     echo "Connection established.\n";
}
else
{
     echo "Connection could not be established.\n";
     die( print_r( sqlsrv_errors(), true));
}

//-----------------------------------------------
// Perform operations with connection.
//-----------------------------------------------

/* Close the connection. */
sqlsrv_close( $conn);
?>