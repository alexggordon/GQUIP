
<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Login</title>

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/foundation.css">

  <script src="js/vendor/custom.modernizr.js"></script>

</head>
<body>

  <!-- Header and Nav -->

<div class="row">
    <div class="large-3 columns">
      <h1><img src="img/logo.gif"></h1>
    </div>
    <div class="large-9 columns">
      <ul class="button-group right">
		<li><h1>G-Quip</h1></li>
      </ul>
    </div>
  </div>
	  <!-- First Band (Image) -->
 <div class="row">
  <form data-abide>
   <fieldset>
    <legend>Log In</legend>
    <div class="row">
       
       <div class="large-9 large-centered columns">
         <div class="row collapse">
           <label>Username <small>required</small></label>
           <div class="small-9 columns">
             <input type="text" placeholder="first.last" pattern="alpha_numeric" required>
             <small class="error">A valid username is required.</small>
           </div>
           <div class="small-3 columns">
             <span class="postfix">@gordon.edu</span>
           </div>
         </div>
       </div>
    </div>
    <div class="row">
      <div class="large-9 large-centered columns">
        <div class="row collapse">
        <label for="password">Password <small>required</small></label>
          <div class="small-10 columns">
            <input type="password" id="password" pattern="password_" placeholder="password" name="password" required="">
            <small class="error">You might want to check your password. It doesn't meet Gordon's standards so it shouldn't work!</small>
          </div>
          <div class="small-2 columns">
<!--             <a href="home.php" class="button postfix">Log In</a> -->
<input type="submit" href="home.php" class="button postfix" value="Log In">
          </div>
        </div>
      </div>
    </div>
  </fieldset>
  </form>
</div>

  <!-- Footer -->
<div><?php include('footer.php');?></div>
  <script>
    $(document)
      .foundation()
      .foundation('abide', {
        patterns: {
          password_: /(?=^.{7,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
        }
      });
  </script>


	
</body>
</html>