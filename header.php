<!-- header.php  -->
<!--  -->




<?php
// Include Login information
include('config.php');
// Check if the user is logged in
if(!isset($_SESSION['user'])) {
// User not logged in, send to login screen
header('Location: login.php');
}
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

</head>
<body>


<?php 
// User is a Manager
if($_SESSION['access']=="3" ) {
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
        <!-- Left Nav Section -->
        <ul class="left">
          <li class="divider"></li>
          <li><a href="departments.php">Departments</a></li>
          <li class="divider"></li>
          <li><a href="users.php">Users</a></li>
          <li class="divider"></li>
          <li><a href="#">Advanced Search</a></li>
          <li class="divider"></li>

          <li class="has-dropdown">
            <a href="#">Administration</a>
            <ul class="dropdown">
              <li class="has-dropdown">
              <a href="software.php">Faculty</a>
                <ul class="dropdown">
                  <li><label>Manage Faculty</label></li>
                  <li><a href="#">Add a Faculty Computer Option</a></li>
                  <li><a href="#">See Software Distribution</a></li>
                </ul>
              </li>
              <li class="divider"></li>
              <li><a href="csv_import.php">Mass Import</a></li>
            </ul>
          </li>
          <li class="divider"></li>
        </ul>

        <!-- Right Nav Section -->
        <ul class="right">
          <li class="divider hide-for-small"></li>
        </li>
        <li class="divider"></li>
        <li class="has-form">
          <form>
            <div class="row collapse">
              <div class="small-8 columns">
                <input type="text">
              </div>
              <div class="small-4 columns">
                <a href="#" class="button">Search</a>
              </div>
            </div>
          </form>
        </li>
        <li class="divider show-for-small"></li>
        <li class="has-form">
          <a class="button" href="logout.php">Logout <?php echo $_SESSION['user']; ?></a>
        </li>
      </ul>
    </section>
  </nav>
</div>

<?php
}
// User is a Faculty Member
if($_SESSION['access']=="2" ) {
?>

<!-- Faculty Navigation Bar  -->
<!DOCTYPE html>
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

  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>

</head>
<body>

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
        <!-- Left Nav Section -->
        <ul class="left">
          <li class="divider"></li>
          <li><a href="request.php">Request New Computer</a></li>
          <li class="divider"></li>
          <li><a href="request.php">Department Computers</a></li>
          <li class="divider"></li>
        </ul>

        <!-- Right Nav Section -->
        <ul class="right">  
          <li>
            <a class="button" href="logout.php">Logout</a>
          </li>
        </ul>
    </section>
  </nav>

<?php
}
// User
if($_SESSION['access']=="1" ) {
?>
<!-- Administrator Navigation Bar.  -->
<!DOCTYPE html>
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

  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>

</head>
<body>

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
        <!-- Left Nav Section -->
        <ul class="left">
          <li class="divider"></li>
          <li><a href="departments.php">Departments</a></li>
          <li class="divider"></li>
          <li><a href="users.php">Software</a></li>
          <li class="divider"></li>
          <li><a href="#">Advanced Search</a></li>
          <li class="divider"></li>
          <li><a href="#">Stats</a></li>
          <li class="divider"></li>
        </ul>

        <!-- Right Nav Section -->
        <ul class="right">
          <li class="divider hide-for-small"></li>
          <li class="divider"></li>
          <li class="has-form">
            <form>
              <div class="row collapse">
                <div class="small-8 columns">
                  <input type="text">
                </div>
                <div class="small-4 columns">
                  <a href="#" class="button">Search</a>
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



