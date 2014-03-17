<!-- header.php  -->
<?php
include 'symbolic_values.php';
session_start();
 ?>
<!-- Every user class gets this header -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Home</title>

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/foundation.css">
  <link rel="stylesheet" href="css/top_bar_fix.css">

  <!-- Responsive table css -->
<!--   <link rel="stylesheet" href="css/globals.css">
  <link rel="stylesheet" href="css/typography.css">
  <link rel="stylesheet" href="css/grid.css">
  <link rel="stylesheet" href="css/ui.css">
  <link rel="stylesheet" href="css/forms.css"> -->
<!--   <link rel="stylesheet" href="css/orbit.css">
  <link rel="stylesheet" href="css/reveal.css">
  <link rel="stylesheet" href="css/mobile.css">
  <link rel="stylesheet" href="css/app.css"> -->
  <link rel="stylesheet" href="css/responsive-tables.css">
  <!-- End responsive table css -->
  <!-- Responsive table JS -->
  <script src="js/jquery.min.js"></script>
  <script src="js/responsive-tables.js"></script>
  <!-- End responsive table JS -->

  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>
  <script src="js/foundation/foundation.abide.js"></script>

</head>
<body>


<?php 
// User is a Manager


if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']== USER_PERMISSION OR $_SESSION['access']== FACULTY_PERMISSION) {

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
                          <li><a href="faculty.php">Faculty</a></li>
                          <li><a href="departments.php">Departments</a></li>
                  </ul>
            </li>
            <li class="divider"></li>
            <li><a href="search.php">Advanced Search</a></li>
        </ul>



<?php 
}
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']== USER_PERMISSION) {

?>

<ul class="left">
    <li class="has-dropdown">
          <a href="#">Equipment</a>
          <ul class="dropdown">
                  <li><a href="faculty.php">Faculty</a></li>
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

    <li class="divider"></li>
    <li class="has-dropdown">
          <a href="#">Administration</a>
          <ul class="dropdown">
                  <li><label>Equipment</label></li>
                  <li><a href="csv_import.php">Import</a></li>
                  <li><label>Software</label></li>
                  <li><a href="add_software.php">Add Software</a></li>
                  <li><a href="software.php">Edit or Delete software</a></li>
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
if($_SESSION['access']==ADMIN_PERMISSION OR $_SESSION['access']== USER_PERMISSION) {

?>


        <!-- Right Nav Section -->
        <ul class="right">
          <li class="divider hide-for-small"></li>
          <li class="divider"></li>
          <li class="has-form">
              <form data-abide name="search" enctype='multipart/form-data' action="search.php" method="POST">
        
        <?php
        $pageBase = explode("?", $_SERVER['REQUEST_URI']);
        switch ($pageBase[0])
        {       
          case "/add_software.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"software\">";
            break;
       // used? case "/delete_item.php":
          case "/departments.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"computers\">";
            echo "<input hidden name=\"searchTables[1]\" value=\"FacandStaff\">";
            echo "<input hidden name=\"searchTables[2]\" value=\"hardware_assignments\">";
            break;
       // used? case "/details.php":
          case "/edit_computer.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"computers\">";
            echo "<input hidden name=\"searchTables[1]\" value=\"comments\">";
            echo "<input hidden name=\"searchTables[2]\" value=\"changes\">";
            break;
       // used? case "/edit_item.php":
          case "/edit_license.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"gordonstudents\">";
            echo "<input hidden name=\"searchTables[1]\" value=\"software\">";
            echo "<input hidden name=\"searchTables[2]\" value=\"licenses\">";
            break;
          case "/edit_software.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"software\">";
            break;
          case "/faculty.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"FacandStaff\">";
            break;
          case "/home.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"computers\">";
            echo "<input hidden name=\"searchTables[1]\" value=\"comments\">";
            echo "<input hidden name=\"searchTables[2]\" value=\"changes\">";
            break;
          case "/new_item.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"computers\">";
            break;
          case "/search.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"software\">";
            break;
          case "/software.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"software\">";
            break;
          case "/student_info.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"gordonstudents\">";
            echo "<input hidden name=\"searchTables[1]\" value=\"software\">";
            echo "<input hidden name=\"searchTables[2]\" value=\"licenses\">";
            break;
          case "/students.php":
            echo "<input hidden name=\"searchTables[0]\" value=\"gordonstudents\">";
            break;
        }
        ?>
        
                <div class="row collapse">
                  <div class="small-8 columns">
                    <input type="text" name="searchTerms">
                  </div>
                  <div class="small-4 columns">
                    <input type="submit" class="button" name="search" action="search.php" value="Search">
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



