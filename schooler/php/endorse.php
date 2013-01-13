<?php
session_start();


/*
 * endorse a tweet
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

$tweetId = $_POST['tweetId'];


//session vars
$instructorId = $_SESSION['instructorId'];




//#############################################
//              MAIN QUERY
//#############################################

//add the endorsement
$query = "INSERT INTO Instructors_endorse_Tweets(Tweets_tweetId, Instructors_instructorId)
		VALUES($tweetId, $instructorId)";
$result = mysqli_query($db_server, $query);
if(!$result) die("endorsement failed: " . mysql_error());



//redirect to news feed
header("Location: ../instructorFeed.php");



//close connection
mysqli_close($db_server);



?>
