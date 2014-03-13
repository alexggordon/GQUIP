<?php
include('header.php');
include('open_db.php');
// include('paginate.php');
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}


function getPage($result, $pageNum, $rowsPerPage)
{
  $offset = ($pageNum - 1) * $rowsPerPage;
  $rows = array();
  $i = 0;
  while(($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC, SQLSRV_SCROLL_ABSOLUTE, $offset + $i)) && $i < $rowsPerPage)
  {
    array_push($rows, $row);
    $i++;
  }
  return $rows;
}


$countQuery = "SELECT ID FROM dbo.gordonstudents";
$count = sqlsrv_query($conn, $countQuery, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$num_rows = sqlsrv_num_rows( $count );


$rowsPerPage = 25;


$query = "SELECT  ID, FirstName, LastName, Email, Class FROM dbo.gordonstudents ORDER BY FirstName ASC";
$result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
if ( !$result )
  die( print_r( sqlsrv_errors(), true));

$numOfPages = ceil($num_rows/$rowsPerPage);



if($_SESSION['access']=="3"  OR $_SESSION['access']=="1" ) {
  ?>
  <div class="row">
    <div class="large-10 large-centered columns">
    <h1>Users</h1>
    </div>
    </div>
  <div class="row">
    <div class="large-10 large-centered columns">
  <table cellspacing="0">
   <thead>
    <tr>
      <th width="200">First Name</th>
      <th width="200">Last Name</th>
      <th width="200">Class</th>
    <th width="200">Email</th>
    </tr>
    </thead>

  <?php

  $pageNum = isset($_GET['page']) ? $_GET['page'] : 1;
  $page = getPage($result, $pageNum, $rowsPerPage);

  foreach($page as $row)
  {
     echo "<tr><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[4] . "</td><td>" . $row[3] . "</td></tr>";
  }

 //$row['index'] the index here is a field name
  
  ?>

  </table>
  </div>
  </div>
  <div class="row">
  <div class="large-3 large-centered columns">
<?php
if($pageNum > 1)
{
  $prevPageLink = "?page=".($pageNum - 1);
  echo "<a href='$prevPageLink'>Previous Page</a>";
}

// Display Next Page link if applicable.
if($pageNum < $numOfPages)
{
  $nextPageLink = "?page=".($pageNum + 1);
  echo "&nbsp;&nbsp;<a href='$nextPageLink'>Next Page</a>";
}

sqlsrv_close( $conn );

}
?>
</div>
</div>
<?php
// Faculty
if($_SESSION['access']=="2" ) {
?>

<?php
}
?>



<?php
include('footer.php')
?>
