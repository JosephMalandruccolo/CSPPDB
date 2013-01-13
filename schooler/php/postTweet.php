<?php
session_start();


/*
 * students write tweets to a wall
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
$text = $_POST['tweetText'];
$hashtag = $_POST['tweetHashtag'];



//session vars
$studentId = $_SESSION['studentId'];


//check that tweet text is not blank
if(!$_POST['tweetText'])
    die('You must tweet some text');



//constants
$year = 2012;
$quarter = 'fall';

$datetime = date('Y-m-d H:i:s');


//parse the course value
$parts = explode("*",$course);


//#############################################
//              MAIN QUERY
//#############################################


//add the tweet
$query = "INSERT INTO Tweets(text, time, Students_studentId, Courses_departmentCode,
		Courses_courseNumber, Courses_quarter, Courses_year)
          VALUES('$text', '$datetime', $studentId, '$parts[0]', $parts[1], '$quarter', $year)";
$result = mysqli_query($db_server, $query);
if(!$result) die("tweet failed: " . mysql_error());

//recover the tweet id
$query2 = "SELECT LAST_INSERT_ID()";
$result = mysqli_query($db_server, $query2);
if(!$result) die("tweet id recover failed: " . mysql_error());

$tweetId = mysqli_fetch_row($result);


//post the hashtag
$query3 = "INSERT INTO Students_hashtag_Tweets(Students_studentId, Tweets_tweetId, text)
	   VALUES($studentId, $tweetId[0], '$hashtag')";

$result = mysqli_query($db_server, $query3);
if(!$result) die("hash tag write failed: " . mysql_error());

//redirect to news feed
header("Location: ../newsFeed.php");



//close connection
mysqli_close($db_server);



?>
