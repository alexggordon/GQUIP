
<?php 
// *************************************************************
// file: footer.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose:  The purpose of the footer is to display a dynamic copyright year and to show general links to CTS websites. 
// 
// *************************************************************
 ?>


  <footer class="row">
    <div class="large-12 columns">
      <hr />
      <div class="row">
        <div class="twelve columns text-center">
          <ul class="inline-list">
            <li>&copy; Copyright Gordon College <?php echo date("Y") ?></li>
            <li><a href="http://www.gordon.edu">Gordon College</a></li>
            <li><a href="http://go.gordon.edu">Go.Gordon</a></li>
            <li><a href="http://support.gordon.edu">Footprints</a></li>
            <li><a href="http://mail.gordon.edu">Gordon College Mail</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  
  <script src="js/foundation.min.js"></script>

  <script>
    $(document).foundation();
  </script>
</body>
</html>