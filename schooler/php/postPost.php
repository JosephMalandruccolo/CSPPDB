<?php
session_start();


/*
 * instructor post
 */

//#############################################
//          INCLUDED FILES
//#############################################
require_once 'credentials.php';



//#############################################
//          CONNECT TO SERVER AND DB
//#############################################

//connect to the server
$db_server = mysqli_connect($db_hostname, $db_username, $db_password);
if(!$db_server) die("Unable to connect to MySQL: " . mysql_error());


//connect to the database
mysqli_select_db($db_server, $db_database)
        or die("Unable to connect to Database: " . mysql_error());



//#############################################
//          CATCH USER INPUTS
//#############################################

$course = $_POST['course'];
$text = $_POST['postText'];

//session vars
$instructorId = $_SESSION['instructorId'];


//check that tweet text is not blank
if(!$_POST['postText'])
    die('You must tweet some text');




$datetime = date('Y-m-d H:i:s');


//parse the course value
$parts = explode("*",$course);


//#############################################
//              MAIN QUERY
//#############################################

//add the tweet
$query = "INSERT INTO Posts(text, time, Instructors_instructorId, Courses_departmentCode,
		Courses_courseNumber, Courses_quarter, Courses_year)
          VALUES('$text', '$datetime', $instructorId, '$parts[0]', $parts[1], '$parts[2]', $parts[3])";
$result = mysqli_query($db_server, $query);
if(!$result) die("post failed: " . mysql_error());



//redirect to news feed
header("Location: ../instructorFeed.php");



//close connection
mysqli_close($db_server);



?>
