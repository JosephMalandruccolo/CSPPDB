<?php

session_start();


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
//          CATCH DUMMY INPUTS
//#############################################

$instructorId = $_POST['instructorId'];


$query = "SELECT instructorId, firstName, lastName FROM Instructors WHERE instructorId = $instructorId";
$result = mysqli_query($db_server, $query);
if(!$result) die("Sign up failed: " . mysql_error());


$row = mysqli_fetch_array($result, MYSQLI_ASSOC)
	or die("fetch failed");




$_SESSION['instructorId'] = $instructorId;
$_SESSION['firstName'] = $row['firstName'];
$_SESSION['lastName'] = $row['lastName'];


//re-direct to login success page
header("Location: ../instructorFeed.php");



//close connection
mysqli_close($db_server);


?>
