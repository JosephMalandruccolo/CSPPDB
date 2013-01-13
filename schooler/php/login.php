<?php
session_start();


/*
 * handle the log in process
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

$studentId = $_POST['studentId'];
$password = $_POST['password'];


//#############################################
//              VALIDATION
//#############################################



//check that the inputs are not empty (blank)
if(!$_POST['studentId']| $_POST['password'])
    die('login failed');





//check that the student Id is valid
$validate_query = "SELECT studentId FROM Students WHERE studentId = $studentId";
$validate_studentId = mysqli_query($db_server, $validate_query)
        or die("Validation query failed" . mysql_error());



$validate_studentId_rows = mysqli_num_rows($validate_studentId);
if($validate_studentId_rows === 0) die('Student ID: ' . $_POST['studentId'] . ' not found in database');




//#############################################
//              CHECK STUDENTID - PASSWORD COMBO
//#############################################


$query = "SELECT studentId, firstName, lastName, password FROM Students WHERE studentId = $studentId";
$result = mysqli_query($db_server, $query);
if(!$result) die("Sign up failed: " . mysql_error());


$row = mysqli_fetch_array($result, MYSQLI_ASSOC)
	or die("fetch failed");

$db_password = $row['password'];

if($password != $db_password) die("Invalid password");



$_SESSION['studentId'] = $studentId;
$_SESSION['firstName'] = $row['firstName'];
$_SESSION['lastName'] = $row['lastName'];


//#############################################
//          REDIRECT AND CLOSE CONNECTION
//#############################################

//re-direct to login success page
header("Location: ../newsFeed.php");



//close connection
mysqli_close($db_server);


?>
