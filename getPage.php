<?php
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