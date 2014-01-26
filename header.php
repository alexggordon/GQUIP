<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Home</title>

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/foundation.css">
  <link rel="javascrip" href="css/foundation.css">

  <script src="js/vendor/custom.modernizr.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  
  

</head>
<body>

  <!-- Header and Nav -->
<div class="contain-to-grid sticky">
<nav class="top-bar">
  <ul class="title-area">
    <!-- Title Area -->
    <li class="name">
      <h1><a href="home.html">G-Quip (faculty) </a></h1>
    </li>
    <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
    <!-- Left Nav Section -->
    <ul class="left">
      <li class="divider"></li>
      <li><a href="departments.html">Departments</a></li>
      <li class="divider"></li>
      <li><a href="users.html">Users</a></li>
      <li class="divider"></li>
      <li class="has-dropdown"><a href="#">Administration</a>

        <ul class="dropdown">
          <li class="has-dropdown"><a href="#">Users</a>

            <ul class="dropdown">
              <li><label>Modify G-Quip Users</label></li>
              <li><a href="#">Add User</a></li>
              <li><a href="#">Delete User</a></li>
              <li><a href="#">Modify User</a></li>
            </ul>
          </li>
          <li class="has-dropdown"><a href="software.html">Software</a>
                      <ul class="dropdown">
                        <li><label>Modify Software</label></li>
                        <li><a href="#">Add Software Item</a></li>
                        <li><a href="#">Give a student a software license</a></li>
                        <li><a href="#">See Software Distribution</a></li>
                      </ul>
                    </li>
          <li class="divider"></li>
          <li><a href="#">Mass Import</a></li>
          <li><a href="#">Mass Export</a></li>
          <li class="divider"></li>
          <li><a href="#">Modify G-Quip Fields</a></li>
          <li class="divider"></li>
          <li><a href="#">Advanced Search and Reports</a></li>
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
        <a class="button" href="logout.php">Logout</a>
      </li>
    </ul>
  </section>
</nav>
</div>