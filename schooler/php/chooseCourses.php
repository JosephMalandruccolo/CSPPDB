<?php
session_start();

/*
 * method to update the courses a student follows
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
//      CONSTANTS
//#############################################
$year = 2012;
$quarter = 'fall';



//#############################################
//          CATCH THE NEEDED SESSION VARIABLES
//#############################################
$studentId = $_SESSION['studentId'];


//#############################################
//          CATCH THE USER INPUTS
//#############################################
$courses = $_POST['course'];
if(empty($courses)) die("You didn't select any courses");
else{
    $nNumCourses = count($courses);
    
    
    //execute the queries
    for($j=0; $j < $nNumCourses; $j++){
        
        //parse the value
        $parts = explode("*",$courses[$j]);
        
        
        
        //execute the query
        $query = "INSERT INTO Students_follow_Courses(Students_studentId, Courses_departmentCode, Courses_courseNumber, Courses_quarter, Courses_year)
                  VALUES($studentId, '$parts[0]', $parts[1], '$quarter',$year)";
        
        
        $result = mysqli_query($db_server, $query);
        if(!$result) die("Update failed: " . mysql_error());       
        
        
    }//end for
}//end if-else




//#############################################
//          REDIRECT AND CLOSE CONNECTION
//#############################################
//re-direct to login success page
header("Location: ../newsFeed.php");



//close connection
mysqli_close($db_server);



?>
