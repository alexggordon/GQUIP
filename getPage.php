<?php

// *************************************************************
// file: getPage.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The get page function works in tandem with the paginate php function (see paginate.php). It’s goal is to take the page number returned from paginate.php function 
// and then get the corresponding SQL data from the database.
// *************************************************************


include('open_db.php');
function getPage($query, $pageNum, $rowsPerPage)
{
	$offset = ($pageNum - 1) * $rowsPerPage;
	$rows = array();
	$i = 0;
	while(($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_NUMERIC, SQLSRV_SCROLL_ABSOLUTE, $offset + $i)) && $i < $rowsPerPage)
	{
		array_push($rows, $row);
		$i++;
	}
	return $rows;
}
?>