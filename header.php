  <?php
// *************************************************************
// file: header.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The purpose of the header file is to manage general php imports and to display a dynamic nav bar depending on your user class. This nav bar also facilitates use of 
// the in-page search function. 
// *************************************************************
include 'symbolic_values.php';
session_start();
include("config.php");

if (isset($_POST['pdfsubmit']))
{
  $_SESSION['pdfpost'] = $_POST;
  header('Location: pdfprint.php');
}
 ?>
<!-- Every user class gets this header -->

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=10" >
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>GQUIP</title>

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/foundation.css">
  <link rel="stylesheet" href="css/top_bar_fix.css">

  <link type="text/css" media="screen" rel="stylesheet" href="css/responsive-tables.css">
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/responsive-tables.js"></script>
  <script type="text/javascript" src="js/forms.jquery.js"></script>
  <script type="text/javascript" src="js/jquery.customforms.js"></script>

  <link type="text/css" media="screen" rel="stylesheet" href="css/responsive-tables.css">
  <!-- End responsive table css -->
  <!-- Responsive table JS -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/responsive-tables.js"></script>

  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>
  <script src="js/foundation/foundation.abide.js"></script>

</head>
<body>


<?php 
// User is a Manager


if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']== USER_PERMISSION OR $_SESSION['access']== FACULTY_PERMISSION OR $_SESSION['access']== STAFF_PERMISSION) {

?>

<!-- Administrator Navigation Bar.  -->

  <!-- Header and Nav -->
  <div class="contain-to-grid sticky">
    <nav class="top-bar" data-topbar>
      <ul class="title-area">
        <!-- Title Area -->
        <li class="name">
          <h1><a href="home.php">GQUIP</a></h1>
        </li>
        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
      </ul>
      <section class="top-bar-section">

<?php 
}
if($_SESSION['access']== FACULTY_PERMISSION) {

?>

        <!-- first drop down -->
        <!-- Left Nav Section -->
        <ul class="left">
            <li class="has-dropdown">
                  <a href="#">Equipment</a>
                  <ul class="dropdown">
                          <li><a href="faculty.php">Facstaff</a></li>
                          <li><a href="departments.php">Departments</a></li>
                  </ul>
            </li>
            <li class="divider"></li>
            <li><a href="search.php">Advanced Search</a></li>
        </ul>



<?php 
}
if($_SESSION['access']== USER_PERMISSION) {

?>

<ul class="left">
    <li class="has-dropdown">
          <a href="#">Equipment</a>
          <ul class="dropdown">
                  <li><a href="faculty.php">Facstaff</a></li>
                  <li><a href="departments.php">Departments</a></li>
                  <li><a href="new_item.php">New Equipment Item</a></li>
          </ul>
    </li>
    <li class="divider"></li>
    <li class="has-dropdown">
          <a href="#">Software</a>
          <ul class="dropdown">
                  <li><a href="software.php">Software</a></li>
                  <li><a href="students.php">Students</a></li>
          </ul>
    </li>


<?php 
}
if($_SESSION['access']==ADMIN_PERMISSION) {

?>

<ul class="left">
    <li class="has-dropdown">
          <a href="#">Equipment</a>
          <ul class="dropdown">
                  <li><a href="faculty.php">Facstaff</a></li>
                  <li><a href="departments.php">Departments</a></li>
                  <li><a href="new_item.php">New Equipment Item</a></li>
                  <li><a href="inventory.php">Inventory</a></li>
          </ul>
    </li>
    <li class="divider"></li>
    <li class="has-dropdown">
          <a href="#">Software</a>
          <ul class="dropdown">
                  <li><a href="software.php">Software</a></li>
                  <li><a href="students.php">Students</a></li>
          </ul>
    </li>

<?php 
}
if($_SESSION['access']==ADMIN_PERMISSION) {

?>

    <li class="divider"></li>
    <li class="has-dropdown">
          <a href="#">Administration</a>
          <ul class="dropdown">
                  <li><label>Equipment</label></li>
                  <li><a href="csv_import.php">Import</a></li>
                  <li><a href="clear_inventory.php">Clear Inventory</a></li>
                  <li><label>Software</label></li>
                  <li><a href="add_software.php">Add Software</a></li>
          </ul>
    </li>

<?php 
}
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']== USER_PERMISSION) {

?>

    <li class="divider"></li>
    <li><a href="search.php">Advanced Search</a></li>
</ul>

<?php 
}
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']== USER_PERMISSION OR $_SESSION['access']== FACULTY_PERMISSION OR $_SESSION['access']== STAFF_PERMISSION) {

?>


        <!-- Right Nav Section -->
        <ul class="right">
          <li class="divider hide-for-small"></li>
          <li class="divider"></li>
          <li class="has-form">
            <?php  


            $pageBase = explode("?", $_SERVER['REQUEST_URI']);

            if ($pageBase[0] == "/faculty.php") {
              $page = "/faculty.php";
            } else {
              $page = "/home.php";
            }

             ?>
            <form data-abide type="submit" id="searchTerms" enctype='multipart/form-data' action=<?php echo $page; ?> method="POST">

                <div class="row collapse">
                  <div class="small-8 columns">
                    <input type="text" name="searchTerms" placeholder="Search" >
                  </div>
                  <div class="small-4 columns">
                      <input id="searchTerms" type="submit"  class="button" formmethod="POST" value="Search"></input>
                  </div>
                </div>
            </form>
          </li>
        <li class="divider show-for-small"></li>
        <li class="has-form">
          <a class="button" href="logout.php">Logout</a>
        </li>
      </ul>
    </section>
  </nav>
</div>

<?php
}
?>



