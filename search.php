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
			echo "<li>", $table, "</li>";
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
					echo "<th>" . $col . "</th>";
				}
				echo "</thead></tr>\n";
				echo $query['$table'];
				while($row['$table'] = sqlsrv_fetch_array($result['$table'], SQLSRV_FETCH_ASSOC))
				{
					echo $table;
					echo $row['$table'][0];
					echo "<tr>";
					foreach ( $columnArray[$table] as $tableRowValue )
					{
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
				<input type='checkbox' name='searchTables[]' value='computers'>Computers<br />
				<input type='checkbox' name='searchTables[]' value='comments'>Comments on computers<br />
				<input type='checkbox' name='searchTables[]' value='changes'>Computer change records<br />
				<input type='checkbox' name='searchTables[]' value='hardware_assignments'>Hardware assignments<br />
				<input type='checkbox' name='searchTables[]' value='FacandStaff'>Faculty<br />
				<input type='checkbox' name='searchTables[]' value='gordonstudents'>Students<br />
				<input type='checkbox' name='searchTables[]' value='software'>Software records<br />
				<input type='checkbox' name='searchTables[]' value='licenses'>Licenses to students<br />
			</fieldset>

			<fieldset>		
				<legend>Terms</legend>
				<input type="text" name="searchTerms">
			</fieldset>
		
			<input type="submit" name="submit" value="Search" class="button" formmethod="post">
		</form>
		<hr />
	
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
	include('footer.php')
?>