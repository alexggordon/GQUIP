<?php
// *************************************************************
// file: 
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: A general-use search page for finding data in a given section of the GQUIP database by table.
// 
// *************************************************************

// include nav bar and other default page items
include('header.php');

// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION || $_SESSION['access']==USER_PERMISSION) {
include('open_db.php');
?>

<div class="large-10 large-centered columns">
	<h1>Search GQUIP</h1>

	<?php

	$realTerms;

	echo "<p>Searching terms:";
	if (!isset($_POST['searchTerms']) OR $_POST['searchTerms'] == "")
	{
		echo "<br />No search terms set";
	}
	else
	{
		echo "<ul>";
		$realTerms = str_replace(",", " ", $_POST['searchTerms']);
		$realTerms = str_replace("  ", " ", $realTerms);
		$realTerms = explode(" ", $realTerms);
		foreach ( $realTerms as $thisTerm )
		{
			$thisTerm = trim($thisTerm);
			echo "<li>", $thisTerm, "</li>";
		}
		echo '</ul>';
		
		echo '</p>';
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
			echo '</ul>';
			echo '</p>';

			include 'open_db.php';
		
			//Go through each table's query, get the relevant data, display it
			echo "Results:\n\n";
			foreach ( $_POST['searchTables'] as $table )
			{
				$searchResultsArray['tables'][] = $table;
				$result[$table] = sqlsrv_query($conn, $query[$table]);
				sqlsrvErrorLinguist($result[$table], "Problem with searching for results on table " . $table);
				$IDName;
				$pageName;
				$requiresQuery = 0;
				switch ($table)
				{
					case "computers":
						$IDName = "control";
						$pageName = "info.php?id=";
						$linkText = "Computer information";
						break;
					case "hardware_assignments":		//***
						$IDName = "computer";
						$pageName = "info.php?id=";
						$requiresQuery = 1;
						$linkText = "Computer information";
						break;
					case "changes":						//***
						$IDName = "computer_id";
						$pageName = "info.php?id=";
						$requiresQuery = 1;
						$linkText = "Computer information";
						break;
					case "FacandStaff":
						$IDName = "ID";
						$pageName = "faculty_info.php?&id=";
						$linkText = "Faculty/Staff member information";
						break;
					case "gordonstudents":
						$IDName = "id";
						$pageName = "student_info.php?&id=";
						$linkText = "Student information";
						break;
					case "software":
						$IDName = "index_id";
						$pageName = "edit_software.php?edit=";
						$linkText = "Software edit page";
						break;
					case "licenses":
						$IDName = "id";
						$pageName = "student_info.php?&id=";
						$linkText = "Student with license";
						break;
					case "comments":					//***
						$IDName = "computer_id";
						$pageName = "info.php?id=";
						$requiresQuery = 1;
						$linkText = "Computer information";
						break;
				}

				//Ensure there are some results before starting to show tables
				if (count($result[$table]) > 0)
				{
					//Output this table's data
					echo "<h3>" . $tableReadableArray[$table] . "</h3>";
					echo "<div class=\"large-12 large-centered columns\">\n";
					echo "<div class=\"row\">\n";
					echo "<table><thead><tr>\n";
					echo "<th>Link to page</th>";
					foreach ($columnReadableArray[$table] as $col)
					{
						$searchResultsArray[$table]['header'][] = $col;
						echo "<th>" . $col . "</th>";
					}
					echo "</thead></tr>\n";
					while($row[$table] = sqlsrv_fetch_array($result[$table], SQLSRV_FETCH_ASSOC))
					{
						echo "<tr>";

						$linkValue = $pageName;
						if ($requiresQuery)
						{
							$linkedComputer = "SELECT control FROM computers WHERE computer_id = " . $row[$table][$IDName] . ";";
							$linkedResult = sqlsrv_query($conn, $linkedComputer);
							sqlsrvErrorLinguist($linkedResult, "There was an unusual value detected in the rows returned");
							$rowItem = sqlsrv_fetch_array($linkedResult);
							$linkValue .= $rowItem['control'];
						}
						else
						{
							$linkValue .= $row[$table][$IDName];
						}

						echo "<td><a class='button tiny' href='" . $linkValue . "'>" . $linkText . "</a></td>";

						foreach ( $columnArray[$table] as $tableRowValue )
						{

							$searchResultsArray[$table]['body'][$tableRowValue][] = $row[$table][$tableRowValue];
							if (!($row[$table][$tableRowValue] instanceof DateTime))
							{
								if () {
									echo "<td>" . $row[$table][$tableRowValue] . "</td>";
								} else {
									echo "<td>" . $row[$table][$tableRowValue] . "</td>";
								}
								
							}
							else
							{
								echo "<td>" . $row[$table][$tableRowValue]->format('Y-m-d H:i:s') . "</td>";
							}
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
	}
	?>

		<form data-abide type="submit" name="submit" enctype='multipart/form-data' action="search.php" method="POST">

			<fieldset>
				<legend>Areas searched</legend>
				<div class="large-12 columns">
					<div class="row">
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='FacandStaff'><span class="label radius">Faculty</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='computers'><span class="label radius">Computers</span>
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
							<input type='checkbox' name='searchTables[]' value='gordonstudents'><span class="label radius">Students</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='software'><span class="label radius">Software records</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='licenses'><span class="label radius">Licenses to students</span>
						</div>
						<div class="large-3 columns">
							<input type='checkbox' name='searchTables[]' value='comments'><span class="label radius">Comments</span>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>		
				<legend>Terms</legend>
				<input type="text" name="searchTerms">
			</fieldset>
		
			<input type="submit" name="submit" value="Search" class="button" formmethod="post">
			<input type="submit" name="pdfsubmit" value="PDF of Results" class="button" formmethod="post">
		</form>
		
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
	sqlsrv_close($conn);
	//footer
	include('footer.php');
?>