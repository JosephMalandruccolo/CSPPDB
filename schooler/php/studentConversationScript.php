<?php

session_start();


/*
 * student sends private message to instructor
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

$instructorId = $_POST['sendMessageTo'];
$text = $_POST['messageText'];
$subject = $_POST['messageSubject'];

//session vars
$studentId = $_SESSION['studentId'];


//check that message text is not blank
if(!$_POST['messageText'])
    die('You must send some text');


//check that message subject is not blank
if(!$_POST['messageSubject'])
    die('Subject must not be blank');


$datetime = date('Y-m-d H:i:s');


//#############################################
//              MAIN QUERY
//#############################################

//add the message
$query = "INSERT INTO Students_privateMessage_Instructors(studentId, instructorId, sentBy, subject, text, time)
	  VALUES($studentId, $instructorId, $studentId, '$subject', '$text', '$datetime')";
$result = mysqli_query($db_server, $query);
if(!$result) die("message send failure: " . mysql_error());



//redirect to conversation page
header("Location: ../studentConversation.php");



//close connection
mysqli_close($db_server);




?>
