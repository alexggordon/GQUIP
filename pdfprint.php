<?php

// *************************************************************
// file: pdfprint.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: A general purpose page that allows users to print out search data in PDF format, or copy-paste a selection of search data into a .csv file
// 
// *************************************************************

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

	// THIS GUY HERE holds the posted info so the session data can be cleaned safely
	$posted;
	if (isset($_SESSION['pdfpost']['searchTerms']) AND $_SESSION['pdfpost']['searchTerms'] != "")
	{
		$posted = $_SESSION['pdfpost'];
		$_SESSION['pdfpost'] = NULL;
		$pdf->Cell(30,10,"GQUIP, the Gordon College equipment database");
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Cell(30,10,"Searched terms: ");
		$pdf->Ln();
		$realTerms = str_replace(",", " ", $posted['searchTerms']);
		$realTerms = str_replace("  ", " ", $realTerms);
		$realTerms = explode(" ", $realTerms);
		foreach ( $realTerms as $thisTerm )
		{
			$thisTerm = trim($thisTerm);
			$pdf->Cell(30,10,$thisTerm . " ");
			$pdf->Ln();
		}
	}
	else
	{
		$posted = NULL;
		header('Location: search.php');
	}

	$pdf->Ln();

	$query;
	$result;
	
	//Check if the tables to search are set
	
	if (isset($posted['searchTables']))
	{

		$pdf->Cell(30,10,"Searched tables: ");
		$pdf->Ln();
		foreach ($posted['searchTables'] as $table)
		{
			$pdf->Cell(30,10,$tableReadableArray[$table]);
			$pdf->Ln();
		}
		foreach ($posted['searchTables'] as $table)
		{
			$query[$table] = "SELECT *
			FROM " . $table . " WHERE ";
			$numberCols = count($columnArray[$table]);
			$numberTerms = count($realTerms);
			//Ask all of the columns if they have a row containing this value
			if ($numberCols > 1)
			{
				for($colIndex = 0; $colIndex < ($numberCols - 1); $colIndex++)
				{
					for($realIndex = 0; $realIndex < ($numberTerms); $realIndex++)
					{
						$query[$table] .= $columnArray[$table][$colIndex] . " LIKE '%" . $realTerms[$realIndex] . "%' OR ";
					}
				}
			}
			if ($numberTerms > 1)
			{
				for($realIndex = 0; $realIndex < ($numberTerms - 1); $realIndex++)
				{
					$query[$table] .= $columnArray[$table][$numberCols-1] . " LIKE '%" . $realTerms[$realIndex] . "%' OR ";
				}
			}
			$query[$table] .= $columnArray[$table][$numberCols-1] . " LIKE '%" . $realTerms[$numberTerms-1] . "%';";
		}

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Cell(30,10,"Results:");
		$pdf->Ln();
		$pdf->Ln();

		include 'open_db.php';

		//Go through each table's query, get the relevant data, display it
		foreach ( $posted['searchTables'] as $table )
		{
			// Get the row data from each table
			$result[$table] = sqlsrv_query($conn, $query[$table], array(), array( "Scrollable" => 'static' ));
			$row_count = sqlsrv_num_rows($result[$table]);
			sqlsrvErrorLinguist($result[$table], "Problem with getting results");
			

			// Set up the pdf section header
			$pdf->Cell(30,10,"~~~~~~~~~~~~~~" . $tableReadableArray[$table]);
			$pdf->Ln();
			$pdf->Cell(30,10,"Result count: " . $row_count);
			//Ensure there are some results before starting to show tables
			if ($row_count > 0)
			{
				$pdf->Ln();
				$pdf->Ln();
				//Output this table's data
				while($row[$table] = sqlsrv_fetch_array($result[$table]))
				{
					foreach ( $columnArray[$table] as $tableRowValue )
					{
						//Make sure that the data here can be printed
						if (!($row[$table][$tableRowValue] instanceof DateTime))
						{
							$pdf->Cell($columnSizesArray[$table][$tableRowValue],5,$tableRowValue . ": " . $row[$table][$tableRowValue]);
						}
						else
						{
							$pdf->Cell($columnSizesArray[$table][$tableRowValue],5,$tableRowValue . ": " . $row[$table][$tableRowValue]->format('Y-m-d H:i:s'));
						}
						$pdf->Ln();
					}
					$pdf->Ln();
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->Cell(20,10,"N/A");
				$pdf->Ln();
				$pdf->Ln();
			}
		}
	}
	$pdf->Output();
}

?>