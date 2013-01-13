<?php
session_start();
require 'php/functions.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo "Instructor | "; echo $_SESSION['firstName']; echo " "; echo $_SESSION['lastName']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
      
      .joeColumn {
        float: left;
        width: 27%;
        margin-top: 2%;
        padding-top: 2%;
      }



	.basicInfo {
        float: left;
        width: 15%;
        padding: 2%;
        margin: 2%;
	border-width: 2px;
        border-left-style:  solid;
      }



	.tweetColumn {
        float: left;
        width: 27%;
        padding: 2%;
        margin: 2%;
	border-width: 2px;
        border-left-style:  solid;
      }


	.postColumn {
        float: left;
        width: 27%;
        padding: 2%;
        margin: 2%;
	border-width: 2px;
        border-left-style:  solid;
	border-right-style: solid;
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
            <ul class="nav">
              <li class="active"><a href="#">Instructor Dashboard</a></li>
              <li><a href="instructorMessageTo.php">Conversation with Student</a></li>
              <li><a href="post.php">Post</a></li>
            </ul>

		<form class="navbar-form pull-right" method="post" action="php/logout.php">
		 <input type="submit" class="btn" value="Sign out">
		</form>

          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <div class="row">
        <div class="basicInfo">
          <h3>Instructor Dashboard</h3>
          <h5>Instructor ID: &nbsp;&nbsp;<?php echo $_SESSION['instructorId']?></h5>
          <h5><?php echo $_SESSION['firstName'] ?></h5>
          <h5><?php echo $_SESSION['lastName'] ?></h5>
	  <h5>Courses I Teach:</h5>
	  <?php
		coursesTaught($_SESSION['instructorId']);
	  ?>
        </div>
        <div class="tweetColumn">
          <h3>Student Tweets</h3>
	<?php
		myStudentTweets($_SESSION['instructorId']);
	?>
            
       </div>
        <div class="postColumn">
            <h3>My Posts</h3>
            	<?php
		myPosts($_SESSION['instructorId']);
		?>
        </div>
      </div>

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

  </body>
</html>
