<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Schooler | Learn. Everywhere.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
      
     

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      
      
      .joeColumn {
        float: left;
        width: 33%;
        
      }
      
    </style>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><?php echo $_SESSION['firstName'];?> &nbsp; <?php echo $_SESSION['lastName'];?></a>
          <div class="nav-collapse collapse">
            
           
            
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1>The first step in Schooler is to choose your classes</h1><br />
        <p>Choose from any of the classes below!</p><br />
        <h2>It is HIGHLY recommended that you take Accountancy 100</h2>
        
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <form method="post" action="php/chooseCourses.php">
        <div class="joeColumn">
          <input type="submit" value="PRESS HERE TO SUBMIT WHEN FINISHED!"/><br />
          <h2>Courses</h2>
            <?php produceClassCheckbox(0, 498) ?>
        </div>
        <div class="joeColumn">
          <br /><br />
          <h2>More Courses</h2>
            <?php produceClassCheckbox(499, 998) ?>
       </div>
        <div class="joeColumn">
            <br />
            <br />
            <h2>Even More Courses</h2>
            <?php produceClassCheckbox(998, 1496) ?>
        </div>
          </form>
      </div>

      <hr>

      <footer>
        <p>&copy; Company 2012</p>
      </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-alert.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-dropdown.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script>
    
    
    
    
    <!-- php functions
    ===================================================== -->
    <!-- loads all courses from db -->
    <?php
    
    
    function produceClassCheckbox($firstRow, $lastRow) {
      
      
      
      //#############################################
      //          INCLUDED FILES
      //#############################################
      require 'php/credentials.php';


      
      //#############################################
      //          CONNECT TO SERVER AND DB
      //#############################################

      //connect to the server
      $db_server = mysqli_connect($db_hostname, $db_username, $db_password);
      if(!$db_server) die("Unable to connect to MySQL: " . mysql_error());
      
      
      //connect to the database
          mysqli_select_db($db_server, $db_database)
          or die("Unable to connect to Database: " . mysql_error());
    
        
      
        //execute the query
        $query = 'SELECT DISTINCT departmentCode, courseNumber FROM Courses';
        $result = mysqli_query($db_server, $query)
            or die("checkboxes failed to populate");
        
        
        
        //rows to throw away
        $j = 0;
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC && $j < $firstRow)){
          $j++;
        }//end while
        
        
        
        //actual results to populate
        $k = $firstRow;
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC && $k < $lastRow)){
          
          $departmentCode = $row['departmentCode'];
          $courseNumber = $row['courseNumber'];
          
          
          
          
          echo $departmentCode . '&nbsp;' . $courseNumber . '&nbsp;';
          $str = '<input type="checkbox" name="course[]" value="';
          $str = $str . $departmentCode;
          $str = $str . "*";
          $str = $str . $courseNumber;
          $str = $str . '">';
          echo $str;
          echo '<br />';
          
          $k++;
          
        }//end while
        
        
        
      }//end produceClassCheckbox
    
    
    
    ?>
    
    

  </body>
</html>
