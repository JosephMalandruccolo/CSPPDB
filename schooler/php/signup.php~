<?php
session_start();


/*
 * handle the signup process
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
$email = $_POST['email'];
$password = $_POST['password'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$gender = $_POST['gender'];

//#############################################
//              VALIDATION
//#############################################



//check that the inputs are not empty (blank)
if(!$_POST['studentId'] | $_POST['email'] | $_POST['password'] | 
        $_POST['firstName'] | $_POST['lastName'] | $_POST['gender'])
    die('You must complete all fields in the sign up form');





//check that the student Id is not taken
$validate_query = "SELECT studentId FROM Students WHERE studentId = $studentId";
$validate_studentId = mysqli_query($db_server, $validate_query)
        or die("Validation query failed" . mysql_error());



$validate_studentId_rows = mysqli_num_rows($validate_studentId);
if($validate_studentId_rows != 0) die('Sorry, the studentId' . $_POST['studentId'] . ' is taken');



//validation of size of input
if(strlen($studentId) === 0 || strlen($studentId) > 20) die("Student id must consist of 1 - 20 integers");
if(strlen($email) === 0 || strlen($email) > 50) die("Email cannot be blank");
if(strlen($password) === 0 || strlen($password) > 50) die("Password cannot be blank");
if(strlen($firstName) === 0 || strlen($firstName) > 25) die("First name cannot be blank");
if(strlen($lastName) === 0 || strlen($lastName) > 50) die("Last name cannot be blank");


//#############################################
//              MAIN QUERY
//#############################################


$query = "INSERT INTO Students(studentId, email, password, firstName, lastName, gender)
          VALUES($studentId, '$email', '$password', '$firstName', '$lastName', '$gender')";
$result = mysqli_query($db_server, $query);
if(!$result) die("Sign up failed: " . mysql_error());



$_SESSION['studentId'] = $studentId;
$_SESSION['firstName'] = $firstName;
$_SESSION['lastName'] = $lastName;


//#############################################
//          REDIRECT AND CLOSE CONNECTION
//#############################################

//re-direct to login success page
header("Location: ../courses.php");



//close connection
mysqli_close($db_server);


?>
