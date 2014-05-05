<?php

// *************************************************************
// file: Faculty.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: A page used for displaying the data of and relevant to all faculty and staff members currently in Gordon College’s Active Directory.
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');
include('getPage.php');
include ('paginate.php');
include('open_db.php');
// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
// queries

// Set up ordering

// Find the requested content ordering
// When the sorting is the same kind as before, then "reverse" the ordering from previous
$sortMarker;
$orderingWatch;
if (isset($_GET['sorting']))
{
    $sortMarker = $_GET['sorting'];
    switch ($sortMarker)
    {   
      case "FirstName":
        $orderingWatch = "case when FirstName is null then 1 else 0 end, FirstName";
        break;    
      case "FlipFirstName":
        $orderingWatch = "FirstName DESC";
        break;
      case "LastName":
        $orderingWatch = "case when LastName is null then 1 else 0 end, LastName";
        break;
      case "FlipLastName":
        $orderingWatch = "LastName DESC";
        break;
      case "ID":
        $orderingWatch = "case when ID is null then 1 else 0 end, ID";
        break;
      case "FlipID":
        $orderingWatch = "ID DESC";
      case "Facstaff":
        $orderingWatch = "case when type is null then 1 else 0 end, type";
        break;
      case "FlipFacstaff":
        $orderingWatch = "type DESC";
        break;
      case "Dept":
        $orderingWatch = "case when OnCampusDepartment is null then 1 else 0 end, OnCampusDepartment";
        break;
      case "FlipDept":
        $orderingWatch = "OnCampusDepartment DESC";
        break;
      case "Email":
        $orderingWatch = "case when Email is null then 1 else 0 end, Email";
        break;
      case "FlipEmail":
        $orderingWatch = "Email DESC";
        break;
    }
}
else
{
    $sortMarker = "LastName";
    $orderingWatch = "case when LastName is null then 1 else 0 end, LastName";
}

// sql query

if (isset($_POST['searchTerms'])) {
      $strippedQuery = strip_tags($_POST['searchTerms']);
     $query = "SELECT ID, FirstName, LastName, Email, OnCampusDepartment, type
     FROM dbo.FacandStaff 
     where ID LIKE '" . $strippedQuery . "'
     OR FirstName LIKE '" . $strippedQuery . "'
     OR LastName LIKE '" . $strippedQuery . "'
     OR Email LIKE '" . $strippedQuery . "'
     OR OnCampusDepartment LIKE '" . $strippedQuery . "'
     OR type LIKE '" . $strippedQuery . "'
     ORDER BY " . $orderingWatch . ";";

     ?>
     <div class="row">
       <div class="large-10 large-centered columns">
       <h1>Searching for <?php echo $strippedQuery; ?></h1>

     <?php 


} else {
  $query = "SELECT ID, FirstName, LastName, Email, OnCampusDepartment, type
            FROM dbo.FacandStaff 
            ORDER BY " . $orderingWatch . ";";


            ?>
            <div class="row">
              <div class="large-10 large-centered columns">
              <h1>Facstaff</h1>

            <?php 

}


// $countQuery = "SELECT ID FROM dbo.FacandStaff";
$count = sqlsrv_query($conn, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
if ( !$count )
  die( print_r( sqlsrv_errors(), true));
$num_rows = sqlsrv_num_rows( $count );


$result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
if ( !$result ) {
  die( print_r( sqlsrv_errors(), true));
}



$thereIsData = sqlsrv_has_rows( $result );

// data for pagination
$num_rows = sqlsrv_num_rows( $count );


$rowsPerPage = 25;
$numOfPages = ceil($num_rows/$rowsPerPage);

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if ($page == 0) {
      $pageNum = 1;
    } else {
      $pageNum = (($page / $rowsPerPage) + 1);
    }
} else {
  $pageNum = 1;
}

if (isset($_GET['page'])) {
  $pCurrent = $_GET['page'];
} else {
  $pCurrent = 0;
}

$aLeft = 'faculty.php?sorting=' . $sortMarker . '&page=';
$aRight = '';

$sMultiplier = 25;
$sArrows = TRUE;



// get page, this parses the page info
$page = getPage($result, $pageNum, $rowsPerPage);





if($_SESSION['access']=="3"  OR $_SESSION['access']=="2"  OR $_SESSION['access']=="1" ) {
  ?>
  
    <ul class="breadcrumbs">
      <li><a href="home.php">Home</a></li>
      <li class="current"><a href="#">Facstaff</a></li>
    </ul>
    </div>
    </div>
  <div class="row">
    <div class="large-10 large-centered columns">

    <?php 
    if ($thereIsData) {

     ?>

  <table cellspacing="0">
   <thead>
    <tr>
      <form method="post" action="">
      <th width="75"><?php if ($sortMarker == "FirstName"){echo "<a href='faculty.php?sorting=FlipFirstName'>";} else {echo "<a href='faculty.php?sorting=FirstName'>";} ?>First Name</a></th>
      <th width="75"><?php if ($sortMarker == "LastName"){echo "<a href='faculty.php?sorting=FlipLastName'>";} else {echo "<a href='faculty.php?sorting=LastName'>";} ?>Last Name</a></th>
      <th width="50"><?php if ($sortMarker == "Facstaff"){echo "<a href='faculty.php?sorting=FlipFacstaff'>";} else {echo "<a href='faculty.php?sorting=Facstaff'>";} ?>Faculty/Staff</a></th>
      <th width="200"><?php if ($sortMarker == "Dept"){echo "<a href='faculty.php?sorting=FlipDept'>";} else {echo "<a href='faculty.php?sorting=Dept'>";} ?>Department</a></th>
      <th width="200"><?php if ($sortMarker == "Email"){echo "<a href='faculty.php?sorting=FlipEmail'>";} else {echo "<a href='faculty.php?sorting=Email'>";} ?>Email</a></th>
      </form>
    </tr>
    </thead>
<a href=""></a>
  <?php

  foreach($page as $row)
  {
     echo "<tr><td><a href=\"/faculty_info.php?&id=" . $row[0] . "\">" . $row[1] . "</a></td><td><a href=\"/faculty_info.php?&id=" . $row[0] . "\">" . $row[2] . "</a></td><td>" . $row[5] . "</td><td>" . $row[4] . "</td><td>" . $row[3] . "</td></tr>";
  }

 //$row['index'] the index here is a field name
  
  ?>

  </table>
  </div>
  </div>
  <div class="row">
  <div class="large-12 large-centered columns">
<?php


echo PHPagination($pCurrent, $num_rows, $aLeft, $aRight, $sMultiplier, $sArrows);

  } else {
      if (isset($_POST['searchTerms'])) {
          $strippedQuery = strip_tags($_POST['searchTerms']);
          $newQuery = "SELECT *
          FROM computers
          where control LIKE '" . $strippedQuery  . "'
          OR model LIKE '" . $strippedQuery  . "'
          OR manufacturer LIKE '" . $strippedQuery  . "' ;";


          $resultTwo = sqlsrv_query($conn, $newQuery);
          $thereIsNewData = sqlsrv_has_rows( $resultTwo );
          if ($thereIsNewData ) {
            ?>
                </div>
                </div>
                <div class="row" align="center">
                <div class="large-10 large-centered columns">
                <h1>There's no search results here, but it looks like there is some in Equipment!</h1>
                <form data-abide type="submit" id="searchTerms" enctype='multipart/form-data' action="/home.php"  method="POST">
                <?php
                  echo "<input type='hidden' name=\"searchTerms\" value='". $strippedQuery  ."'>";
                ?>
                <input id="searchTerms" type="submit"  class="button" formmethod="POST" value="Go home"></input>
                </form>


            <?php 
          } elseif (!$thereIsNewData ) {
                ?>
                    </div>
                    </div>
                    <div class="row">
                    <div class="large-9 large-centered columns">
                    <h1>There's no search results.</h1>
                <?php 
          } else {
                ?>
                    </div>
                    </div>
                    <div class="row">
                    <div class="large-9 large-centered columns">
                    <h1>There's no data.</h1>
                <?php 
          }

     } else {
      ?>
      </div>
      </div>
      <div class="row">
      <div class="large-9 large-centered columns">
      <h1>There's nothing here.</h1>
      <?php
     }
}





sqlsrv_close( $conn );


?>
</div>

<!-- 

Hey... wanna see something cool?

&&&&&&&&&&&&&&&&&&&&&&&&&
&                       &
&                *      &
&          *    **      &
&     *    **   *       &
&    *   ***   **       &
&           **          &
&    ***  *****         &
&    * *****    **      &
&          *** *** *    &
&  ())     ___   **     &
& fsshpop  ===    *     &
&         /***\  *      &
&        /**~~*\        &
&       (*~~****)       &
&       |~~*~~~~|       &
&     * |~~~~~*~|*      &
&    ** |~~~~~~~|*      &
&       (~~~~~~~)       &
&        |~~~~~|        &
&        |~~~~~|        &
& **    (~~~~~~~)       &
&       |~~~~~~~|       &
&     **(._._._.)       &
&     *   **** **       &
&  ******* ***** ***    &
& ****  ****   ** *  *  &
&      ********  * * ** &
&&&&&&&&&&&&&&&&&&&&&&&&&

...oops.

-2014

For the love of all that is good and holy, kids, do not let Alex Gordon near your soda.
You will not have soda for long otherwise.

-->

</div>

<?php
}
include('footer.php')
?>
