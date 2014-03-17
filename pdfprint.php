<?php
require 'fpdf.php';
include 'symbolic_values.php';
session_start();
if(!isset($_SESSION['user']))
{
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']==USER_PERMISSION)
{

	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);

	$realterms = "NULL";

	if (isset($_POST['searchTerms']) AND $_POST['searchTerms'] != "")
	{
		$realTerms = explode(",", $_POST['searchTerms']);
		$pdf->Cell(30,10,"Searched terms:");
		$pdf->Ln();
		foreach ( $realTerms as $thisTerm )
		{
			$thisTerm = trim($thisTerm);
			$pdf->Cell(30,10,$thisTerm);
			$pdf->Ln();
		}
	}

	$query;
	$result;
	
	//Check if the tables to search are set
	
	if (isset($_POST['searchTables']))
	{
		foreach ( $_POST['searchTables'] as $table )
		{
			$query['$table'] = "SELECT *
			FROM " . $table . " WHERE ";
			$numberCols = count($columnArray[$table]);
			//Ask all of the columns if they have a row containing this value
			if ($numberCols > 1)
			{
				for($colIndex = 0; $colIndex < (count($columnArray[$table]) - 1); $colIndex++)
				{
					$query['$table'] .= $columnArray[$table][$colIndex] . " LIKE '%" . $realTerms[0] . "%' OR ";
				}
			}
			$query['$table'] .= $columnArray[$table][$numberCols-1] . " LIKE '%" . $realTerms[0] . "%';";
		}

		include 'open_db.php';
	
		//Go through each table's query, get the relevant data, display it
		foreach ( $_POST['searchTables'] as $table )
		{
			$result['$table'] = sqlsrv_query($conn, $query['$table']);
			if(!$result['$table'])
			{
				echo print_r( sqlsrv_errors(), true);
				exit;
			}
			//Ensure there are some results before starting to show tables
			if (count($result['$table']) > 0)
			{
				//Output this table's data
				$pdf->Cell(30,5,$tableReadableArray[$table]);
				$pdf->Ln();
				foreach ($columnArray[$table] as $col)
				{
					$pdf->Cell($columnSizesArray[$table][$col],5,$col . ",");
				}
				$pdf->Ln();
				while($row['$table'] = sqlsrv_fetch_array($result['$table']))
				{
					foreach ( $columnArray[$table] as $tableRowValue )
					{
						$pdf->Cell($columnSizesArray[$table][$tableRowValue],5,$row['$table'][$tableRowValue] . ",");
					}
					$pdf->Ln();
				}
				$pdf->Ln();
			}
		}
	}
	$pdf->Output();
}

?>