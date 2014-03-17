<?php
include('header.php');
if(!isset($_SESSION['user'])) {
	header('Location: login.php');
}

// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION || $_SESSION['access']==USER_PERMISSION) {
?>

<div class="large-10 large-centered columns">
	<h1>Search GQUIP</h1>

	<?php

	$realterms = "NULL";

	echo "<p>Searching terms:";
	if (!isset($_POST['searchTerms']) OR $_POST['searchTerms'] == "")
	{
		echo "<br />No search terms set</p>";
	}
	else
	{
		echo "<ul>";
		$realTerms = explode(",", $_POST['searchTerms']);
		foreach ( $realTerms as $thisTerm )
		{
			$thisTerm = trim($thisTerm);
			echo "<li>", $thisTerm, "</li>";
		}
		echo '</ul>';
		echo '</p>';
	}

	$query;
	$result;
	
	//Check if the tables to search are set
	
	echo "<p>Searching tables:";
	if (!isset($_POST['searchTables']))
	{
		echo "<br />No search tables set</p>";
	}
	else
	{
		echo "<ul>";
		foreach ( $_POST['searchTables'] as $table )
		{
			echo "<li>", $tableReadableArray[$table], "</li>";
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
		echo '</ul>';
		echo '</p>';

		include 'open_db.php';
	
		//Go through each table's query, get the relevant data, display it
		echo "Results:\n\n";
		foreach ( $_POST['searchTables'] as $table )
		{
			$searchResultsArray['tables'][] = $table;
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
				echo "<h3>" . $tableReadableArray[$table] . "</h3>";
				echo "<div class=\"large-12 large-centered columns\">\n";
				echo "<div class=\"row\">\n";
				echo "<table><thead><tr>\n";
				foreach ($columnReadableArray[$table] as $col)
				{
					$searchResultsArray[$table]['header'][] = $col;
					echo "<th>" . $col . "</th>";
				}
				echo "</thead></tr>\n";
				while($row['$table'] = sqlsrv_fetch_array($result['$table'], SQLSRV_FETCH_ASSOC))
				{
					echo "<tr>";
					foreach ( $columnArray[$table] as $tableRowValue )
					{
						$searchResultsArray[$table]['body'][$tableRowValue][] = $row['$table'][$tableRowValue];
						echo "<td>" . $row['$table'][$tableRowValue] . "</td>";
					}
					echo "</tr>";
				}
				echo "</table>";
				echo "</div>";
				echo "</div>";
				echo "<hr />\n\n";
			}
		}
	}
	?>

		<form data-abide type="submit" name="submit" enctype='multipart/form-data' action="search.php" method="POST">

			<fieldset>
				<legend>Areas searched</legend>
				<div class="large-12 columns">
					<div class="row">
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='computers'><span class="label radius">Computers</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='comments'><span class="label radius">Comments on computers</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='changes'><span class="label radius">Computer change records</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='hardware_assignments'><span class="label radius">Hardware assignments</span>
						</div>
					</div>
					<div class="row">
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='FacandStaff'><span class="label radius">Faculty</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='gordonstudents'><span class="label radius">Students</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='software'><span class="label radius">Software records</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='licenses'><span class="label radius">Licenses to students</span>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>		
				<legend>Terms</legend>
				<input type="text" name="searchTerms">
			</fieldset>
		
			<input type="submit" name="submit" value="Search" class="button" formmethod="post">
		</form>

		<hr />

		<fieldset>
			<legend>Create a PDF</legend>

		<form data-abide type="submit" name="submit" enctype='multipart/form-data' action="pdfprint.php" method="POST">
		
			<fieldset>
				<legend>Areas searched</legend>
				<div class="large-12 columns">
					<div class="row">
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='computers'><span class="label radius">Computers</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='comments'><span class="label radius">Comments on computers</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='changes'><span class="label radius">Computer change records</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='hardware_assignments'><span class="label radius">Hardware assignments</span>
						</div>
					</div>
					<div class="row">
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='FacandStaff'><span class="label radius">Faculty</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='gordonstudents'><span class="label radius">Students</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='software'><span class="label radius">Software records</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='licenses'><span class="label radius">Licenses to students</span>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>		
				<legend>Terms</legend>
				<input type="text" name="searchTerms">
			</fieldset>
		
			<input type="submit" name="submit" value="PDF of results" class="button" formmethod="post">
		</form>
		</fieldset>
		<?php
	
		?>
	
</div>

<?php
	}

	// Faculty
	if($_SESSION['access']==FACULTY_PERMISSION) {
	// Faculty should not have access to this page. 
	header('Location: home.php');
	}
	//footer
	include('footer.php');
?>