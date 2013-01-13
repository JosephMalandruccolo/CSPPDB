<?php

	/*
	 * repository of functions used throughout the web app
	 *
 	 */







	/**
	 * method to print the checkbox-style form containing all the classes in the database
	 * @param $firstRow - start printing the $firstRow in the courses query
	 * @param $lastRow - stop printing at the $lastRow in the courses query
	 */
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
        while($j < $firstRow && $row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
          $j++;
        }//end while
        
        
        //actual results to populate
        $k = $firstRow;
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
          
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
          if($k >= $lastRow) break;
        }//end while


	mysqli_close($db_server);
        
        
        
      }//end produceClassCheckbox





	//method to list all the courses (departmentCode, course number) that a student is taking
	function listMyCourses($studentId){


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
		

	//make the query
	$query = "CALL getMyClasses($studentId)";
	$result = mysqli_query($db_server, $query)
		or die("getMyClasses failed");


	//print results
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		$departmentCode = $row['Courses_departmentCode'];
		$courseNumber = $row['Courses_courseNumber'];


		$str = $departmentCode;
		$str = $str . " ";
		$str = $str . $courseNumber;
		$str = $str . "<br />";
		echo $str;
		
	}//end while

	//close the connections
	mysqli_close($db_server);

	}//end listMyCourses




	//list the tweeter name and the course info and obviously the tweet info
	function listMyTweets($studentId){

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



		//second connect
		//connect to the server
      		$db_server2 = mysqli_connect($db_hostname, $db_username, $db_password);
      		if(!$db_server) die("Unable to connect to MySQL: " . mysql_error());
      
      		//second connect
      		//connect to the database
        	mysqli_select_db($db_server2, $db_database)
        	 or die("Unable to connect to Database: " . mysql_error());


		//third connect
		//connect to the server
      		$db_server3 = mysqli_connect($db_hostname, $db_username, $db_password);
      		if(!$db_server) die("Unable to connect to MySQL: " . mysql_error());
      
      		//third connect
      		//connect to the database
        	mysqli_select_db($db_server3, $db_database)
        	 or die("Unable to connect to Database: " . mysql_error());




		//build the query
		$query = "SELECT s.firstName, s.lastName, NewTweets.tweetId, NewTweets.text, NewTweets.time, NewTweets.departmentCode, NewTweets.number
			FROM Students s
			JOIN (

			SELECT Tweets.Students_studentId AS studentId, Tweets.tweetId, Tweets.text AS text, Tweets.time AS time, Tweets.Courses_departmentCode AS departmentCode, Tweets.Courses_courseNumber AS number
			FROM Tweets
			-- the where clause creates a set of each piece of the Course key in the Students_follow_Courses table
			-- we pull each tweet that matches each of the courses the student is taking
			WHERE 
    				Courses_departmentCode IN 
        				(SELECT Courses_departmentCode
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    			AND 
    				Courses_courseNumber IN 
        				(SELECT Courses_courseNumber
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    			AND
    				Courses_quarter IN 
    					(SELECT Courses_quarter
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    			AND
    				Courses_year IN 
        				(SELECT Courses_year
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
			) AS NewTweets ON NewTweets.studentId = s.studentId
			ORDER BY time DESC";
		$result = mysqli_query($db_server, $query)
			or die("failed to get my tweets");


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$departmentCode = $row['departmentCode'];
			$courseNumber = $row['number'];
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];
			$text = $row['text'];
			$time = $row['time'];
			$tweetId = $row['tweetId'];


			$query2 = "SELECT text FROM Students_hashtag_Tweets WHERE Tweets_tweetId = $tweetId";
			$result2 = mysqli_query($db_server2, $query2)
				or die("failed to get hashtag");
			
			$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
			$hashtag = $row2['text'];



			$query3 = "SELECT * FROM Instructors_endorse_Tweets WHERE Tweets_tweetId = $tweetId";
			$result3 = mysqli_query($db_server3, $query3)
				or die("failed to find endorsement");

			//should be > 0 if this instructor has endorsed this tweet
			$isEndorsed = mysqli_num_rows($result3);
			

			
			echo "<strong>";
			echo $departmentCode;
			echo " ";
			echo $courseNumber;
			echo "</strong>";
			echo " ";
			echo $firstName;
			echo " ";
			echo $lastName;
			echo "<br /><em>";
			echo $time;
			echo "</em><br />";
			echo $text;
			echo "<br /><em>";
			if(strlen($hashtag) > 0) echo "#";
			echo $hashtag;
			echo "</em><br />";




			//control flow for the instructor endorsement
			//if the tweet is endorsed, show it!
			if($isEndorsed > 0){
				echo '<p style="color:red">Your instructor endorsed this tweet!</p><br />';
			}//end if
			else{
				echo '<br />';
			}
			
		
		}//end while


		//close connections
		mysqli_close($db_server);
		mysqli_close($db_server2);


	}//end listMyTweets







	//prints the <option></option> tags with a student's courses
	function myCoursesAsOptions($studentId){


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


		//build the query
		$query = "CALL getMyClasses($studentId)";
		$result = mysqli_query($db_server, $query)
			or die("getMyClasses failed");
		

		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$departmentCode = $row['Courses_departmentCode'];
			$courseNumber = $row['Courses_courseNumber'];

			$str = '<option value="';
			$str = $str . $departmentCode;
			$str = $str . "*";
			$str = $str . $courseNumber;
			$str = $str . '">';
			$str = $str . $departmentCode . " " . $courseNumber;
			$str = $str . "</option>";
			echo $str;
		
		}//end while


		//close the connection
		mysqli_close($db_server);

	}//end myCoursesAsOptions


	
	//finds the first and last names of all of the students who are following my courses
	//the parameter we pass is the student who is using the app
	function studentsInMyCoursesAsOptions($studentId){


	


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


		//build the query
		$query = "SELECT DISTINCT s.studentId AS classmateId, s.firstName, s.lastName
			FROM Students s
			JOIN (

    				SELECT Students_follow_Courses.Students_studentId AS studentId
    				FROM Students_follow_Courses
    				WHERE 
   				 Courses_departmentCode IN 
    				    (SELECT Courses_departmentCode
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    				AND 
   				 Courses_courseNumber IN 
      				  (SELECT Courses_courseNumber
       				 FROM Students_follow_Courses
      				  WHERE Students_studentId = $studentId)
    				AND
   				 Courses_quarter IN 
    				(SELECT Courses_quarter
      				  FROM Students_follow_Courses
      				  WHERE Students_studentId = $studentId)
    				AND
    				Courses_year IN 
       				 (SELECT Courses_year
       				 FROM Students_follow_Courses
      				  WHERE Students_studentId = $studentId)
				) AS MyStudents ON MyStudents.studentId = s.studentId";

		$result = mysqli_query($db_server, $query)
			or die("getMyStudents failed");
		


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$classmateId = $row['classmateId'];
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];

			//if statement is untested
			if($classmateId !== $studentId){

				$str = '<option value="';
				$str = $str . $classmateId;
				$str = $str . '">';
				$str = $str . $firstName . " " . $lastName;
				$str = $str . "</option>";
				echo $str;
			}//end if
		}//end while


		//close the connection
		mysqli_close($db_server);



	

	}//end studentsInMyCoursesAsOptions





	//method to find the sender, time, subject, and text for each message sent to a student
	function listMyStudentMessages($studentId){



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





		//build the query
		$query = "SELECT * FROM Students_privateMessage_Students JOIN Students 
				ON Students.studentId = Students_privateMessage_Students. studentId_sender 
				WHERE studentId_receiver = $studentId ORDER BY time DESC";
		$result = mysqli_query($db_server, $query)
			or die("failed to get my tweets");


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$from = $row['firstName'];
			$from = $from . " ";
			$from = $from . $row['lastName'];
			$subject = $row['subject'];
			$time = $row['time'];
			$text = $row['text'];
			

			
			echo '<div class="message">';
			echo "<strong>From: </strong>";
			echo $from;
			echo "<br /> ";
			echo "<strong>Subject: </strong>";
			echo $subject;
			echo "<br />";
			echo "<strong>Time: </strong>";
			echo "<em>";
			echo $time;
			echo "</em>";
			echo "<br />";
			echo $text;
			echo "<br />";
			echo "<br /><br /></div>";
			
		
		}//end while


		//close connections
		mysqli_close($db_server);


	}//end listMyStudentMessages







	//returns a list of courses taught by an instructor
	function coursesTaught($instructorId){

		
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





		//build the query
		$query = "SELECT departmentCode, courseNumber, quarter, year FROM Courses WHERE Instructors_instructorId = $instructorId";
		$result = mysqli_query($db_server, $query)
			or die("failed to get instructor courses");

	
		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$departmentCode = $row['departmentCode'];
			$courseNumber = $row['courseNumber'];
			$quarter = $row['quarter'];
			$year = $row['year'];


			$str = $departmentCode;
			$str = $str . " ";
			$str = $str . $courseNumber . " | " . $quarter . " " . $year;
			$str = $str . "<br /><br />";
			echo $str;
		
		}//end while

		//close the connections
		mysqli_close($db_server);
		

	}//end coursesTaught




	//tweets that students make in all instructor's courses
	function myStudentTweets($instructorId){


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



		//second connect
		//connect to the server
      		$db_server2 = mysqli_connect($db_hostname, $db_username, $db_password);
      		if(!$db_server) die("Unable to connect to MySQL: " . mysql_error());
      
      		//second connect
      		//connect to the database
        	mysqli_select_db($db_server2, $db_database)
        	 or die("Unable to connect to Database: " . mysql_error());

		
		
		//third connect
		//connect to the server
      		$db_server3 = mysqli_connect($db_hostname, $db_username, $db_password);
      		if(!$db_server) die("Unable to connect to MySQL: " . mysql_error());
      
      		//third connect
      		//connect to the database
        	mysqli_select_db($db_server3, $db_database)
        	 or die("Unable to connect to Database: " . mysql_error());



		//build the query
		$query = "SELECT s.firstName, s.lastName, NewTweets.tweetId, NewTweets.text, NewTweets.time, NewTweets.departmentCode, NewTweets.number
FROM Students s
JOIN (

SELECT Tweets.Students_studentId AS studentId, Tweets.tweetId AS tweetId, Tweets.text AS text, Tweets.time AS time, Tweets.Courses_departmentCode AS departmentCode, Tweets.Courses_courseNumber AS number
FROM Tweets
-- the where clause creates a set of each piece of the Course key in the Students_follow_Courses table
-- we pull each tweet that matches each of the courses the student is taking
WHERE 
    Courses_departmentCode IN 
        (SELECT departmentCode
        FROM Courses
        WHERE Instructors_instructorId = $instructorId)
    AND 
    Courses_courseNumber IN 
        (SELECT courseNumber
        FROM Courses
        WHERE Instructors_instructorId = $instructorId)
    AND
    Courses_quarter IN 
    (SELECT quarter
        FROM Courses
        WHERE Instructors_instructorId = $instructorId)
    AND
    Courses_year IN 
        (SELECT year
        FROM Courses
        WHERE Instructors_instructorId = $instructorId)
) AS NewTweets ON NewTweets.studentId = s.studentId
ORDER BY time DESC";
		$result = mysqli_query($db_server, $query)
			or die("failed to get my tweets");


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$departmentCode = $row['departmentCode'];
			$courseNumber = $row['number'];
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];
			$text = $row['text'];
			$time = $row['time'];
			$tweetId = $row['tweetId'];


			$query2 = "SELECT text FROM Students_hashtag_Tweets WHERE Tweets_tweetId = $tweetId";
			$result2 = mysqli_query($db_server2, $query2)
				or die("failed to get hashtag");
			
			$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
			$hashtag = $row2['text'];


			$query3 = "SELECT * FROM Instructors_endorse_Tweets WHERE Instructors_instructorId = $instructorId 
					AND Tweets_tweetId = $tweetId";
			$result3 = mysqli_query($db_server3, $query3)
				or die("failed to find endorsement");

			//should be > 0 if this instructor has endorsed this tweet
			$isEndorsed = mysqli_num_rows($result3);
			
			

			//html for the tweet contents
			echo "<strong>";
			echo $departmentCode;
			echo " ";
			echo $courseNumber;
			echo "</strong>";
			echo " ";
			echo $firstName;
			echo " ";
			echo $lastName;
			echo "<br /><em>";
			echo $time;
			echo "</em><br />";
			echo $text;
			echo "<br /><em>";
			if(strlen($hashtag) > 0) echo "#";
			echo $hashtag;
			echo "</em><br />";
			

			//control flow for the instructor endorsement
			//if the tweet is endorsed, show it!
			if($isEndorsed > 0){
				echo '<p style="color:red">You endorsed this tweet!</p><br /><br />';
			}//end if
			// the tweet is not endorsed, print the endorse this form/btn
			else{


				echo '<form method="post" action="php/endorse.php">';
				echo '<input type="hidden" name="tweetId" value="';
				echo "$tweetId";
				echo '">';
				echo '<input type="submit" value="Endorse this tweet"></form><br />';


			}//end if-else




		
		}//end while


		//close connections
		mysqli_close($db_server);
		mysqli_close($db_server2);
		mysqli_close($db_server3);


	}//end myStudentTweets

	


	//returns a list of courses taught by an instructor as <option></option> tags
	function coursesTaughtAsOptions($instructorId){

		
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





		//build the query
		$query = "SELECT departmentCode, courseNumber, quarter, year FROM Courses WHERE Instructors_instructorId = $instructorId";
		$result = mysqli_query($db_server, $query)
			or die("failed to get instructor courses");

	
		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$departmentCode = $row['departmentCode'];
			$courseNumber = $row['courseNumber'];
			$quarter = $row['quarter'];
			$year = $row['year'];


			$str = '<option value="';
			$str = $str . $departmentCode;
			$str = $str . "*";
			$str = $str . $courseNumber;
			$str = $str . "*";
			$str = $str . $quarter;
			$str = $str . "*";
			$str = $str . $year;
			$str = $str . '">';
			$str = $str . $departmentCode . " " . $courseNumber . " | " . $quarter. " " . $year;
			$str = $str . "</option>";
			echo $str;
		
		}//end while

		//close the connections
		mysqli_close($db_server);
		

	}//end coursesTaught




	//show posts made by this instructor
	function myPosts($instructorId){

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





		//build the query
		$query = "SELECT * FROM Posts WHERE Instructors_instructorId = $instructorId ORDER BY time DESC";
		$result = mysqli_query($db_server, $query)
			or die("failed to get instructor posts");

	
		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$departmentCode = $row['Courses_departmentCode'];
			$courseNumber = $row['Courses_courseNumber'];
			$quarter = $row['Courses_quarter'];
			$year = $row['Courses_year'];
			$text = $row['text'];
			$time = $row['time'];


			echo "<strong>";
			echo $departmentCode . " " . $courseNumber . " | " . $quarter . " " . $year;
			echo "</strong>";
			echo "<br /><em>";
			echo $time;
			echo "</em><br />";
			echo $text;
			echo "<br /><br />";
			
		
		}//end while

		//close the connections
		mysqli_close($db_server);
			
	}//end myPosts




	//method to list all posts by instructors for the courses that i'm following
	function listInstructorPosts($studentId){

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





		//build the query
		$query = "SELECT firstName, lastName, text, time, Courses_departmentCode, Courses_courseNumber, Courses_quarter, Courses_year
				FROM Posts
				JOIN Instructors ON Instructors.instructorId = Posts.Instructors_instructorId
				WHERE
    				Courses_departmentCode IN 
        				(SELECT Courses_departmentCode
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    			AND 
    				Courses_courseNumber IN 
        				(SELECT Courses_courseNumber
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    			AND
    				Courses_quarter IN 
    					(SELECT Courses_quarter
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    			AND
    				Courses_year IN 
        				(SELECT Courses_year
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
			ORDER BY time DESC";
		$result = mysqli_query($db_server, $query)
			or die("failed to get instructor posts");

	
		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$departmentCode = $row['Courses_departmentCode'];
			$courseNumber = $row['Courses_courseNumber'];
			$quarter = $row['Courses_quarter'];
			$year = $row['Courses_year'];
			$text = $row['text'];
			$time = $row['time'];
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];


			echo "<strong>";
			echo $departmentCode . " " . $courseNumber . " | " . $quarter . " " . $year;
			echo "</strong>";
			echo "<br />";
			echo $firstName . " " . $lastName . "<br /><em>";
			echo $time;
			echo "</em><br />";
			echo $text;
			echo "<br /><br />";
			
		
		}//end while

		//close the connections
		mysqli_close($db_server);


	}//end listInstructorPosts




	//list students taught by an instructor as <option></option> tags
	function studentsTaughtAsOptions($instructorId){

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


		//build the query
		$query = $query = "SELECT DISTINCT s.studentId AS classmateId, s.firstName, s.lastName
			FROM Students s
			JOIN (

    				SELECT Students_follow_Courses.Students_studentId AS studentId
    				FROM Students_follow_Courses
    				WHERE 
   				 Courses_departmentCode IN 
    				    (SELECT departmentCode
        				FROM Courses
        				WHERE Instructors_instructorId = $instructorId)
    				AND 
   				 Courses_courseNumber IN 
      				  (SELECT courseNumber
       				 FROM Courses
      				  WHERE Instructors_instructorId = $instructorId)
    				AND
   				 Courses_quarter IN 
    				(SELECT quarter
      				  FROM Courses
      				  WHERE Instructors_instructorId = $instructorId)
    				AND
    				Courses_year IN 
       				 (SELECT year
       				 FROM Courses
      				  WHERE Instructors_instructorId = $instructorId)
				) AS MyStudents ON MyStudents.studentId = s.studentId";

		$result = mysqli_query($db_server, $query)
			or die("getMyStudents failed");
		


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$classmateId = $row['classmateId'];
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];

			//if statement is untested
			if($classmateId !== $studentId){

				$str = '<option value="';
				$str = $str . $classmateId;
				$str = $str . '">';
				$str = $str . $firstName . " " . $lastName;
				$str = $str . "</option>";
				echo $str;
			}//end if
		}//end while


		//close the connection
		mysqli_close($db_server);



	}//end studentsTaughtAsOptions



	//list of instructors as <option></option> tags
	function myInstructorsAsOptions($studentId){

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
	
		//build the query
		$query = "SELECT i.firstName, i.lastName, i.instructorId, c.departmentCode, c.courseNumber, c.quarter, c.year
				FROM Courses c
				JOIN Instructors i ON i.instructorId = c.Instructors_instructorId
				WHERE 
   				 c.departmentCode IN 
    				    (SELECT Courses_departmentCode
        				FROM Students_follow_Courses
        				WHERE Students_studentId = $studentId)
    				AND 
   				 c.courseNumber IN 
      				  (SELECT Courses_courseNumber
       				 FROM Students_follow_Courses
      				  WHERE Students_studentId = $studentId)
    				AND
   				 c.quarter IN 
    				(SELECT Courses_quarter
      				  FROM Students_follow_Courses
      				  WHERE Students_studentId = $studentId)
    				AND
    				c.year IN 
       				 (SELECT Courses_year
       				 FROM Students_follow_Courses
      				  WHERE Students_studentId = $studentId) ";

		$result = mysqli_query($db_server, $query)
			or die("get instructors failed");
		


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$instructorId = $row['instructorId'];
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];
			$departmentCode = $row['departmentCode'];
			$courseNumber = $row['courseNumber'];
			$quarter = $row['quarter'];
			$year = $row['year'];

			

			$str = '<option value="';
			$str = $str . $instructorId;
			$str = $str . '">';
			$str = $str . $firstName . " " . $lastName . ": " . $departmentCode . " " . $courseNumber . " | " . $quarter . " " . $year;
			$str = $str . "</option>";
			echo $str;
			
		}//end while


		//close the connection
		mysqli_close($db_server);


	}//end myInstructorsAsOptions





	//list messages from instructors
	function listMessagesFromInstructors($studentId){



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





		//build the query
		$query = "SELECT i.firstName, i.lastName, m.subject, m.text, m.time 
				FROM Students_privateMessage_Instructors m 
				JOIN Instructors i
				ON i.instructorId = m.instructorId 
				WHERE m.sentBy <> $studentId
				AND m.studentId = $studentId
				ORDER BY time DESC";
		$result = mysqli_query($db_server, $query)
			or die("failed to get messages");


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$from = $row['firstName'];
			$from = $from . " ";
			$from = $from . $row['lastName'];
			$subject = $row['subject'];
			$time = $row['time'];
			$text = $row['text'];
			

			
			echo '<div class="message">';
			echo "<strong>From: </strong>";
			echo $from;
			echo "<br /> ";
			echo "<strong>Subject: </strong>";
			echo $subject;
			echo "<br />";
			echo "<strong>Time: </strong>";
			echo "<em>";
			echo $time;
			echo "</em>";
			echo "<br />";
			echo $text;
			echo "<br />";
			echo "<br /><br /></div>";
			
		
		}//end while


		//close connections
		mysqli_close($db_server);






	}//end listMessagesFromInstructors



	//list private messages from students to instructor
	function listMessagesFromMyStudents($instructorId){


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





		//build the query
		$query = "SELECT s.firstName, s.lastName, m.subject, m.text, m.time 
				FROM Students_privateMessage_Instructors m 
				JOIN Students s
				ON s.studentId = m.studentId
				WHERE m.sentBy <> $instructorId 
				AND m.instructorId = $instructorId
				ORDER BY time DESC";
		$result = mysqli_query($db_server, $query)
			or die("failed to get messages");


		//print results
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			$from = $row['firstName'];
			$from = $from . " ";
			$from = $from . $row['lastName'];
			$subject = $row['subject'];
			$time = $row['time'];
			$text = $row['text'];
			

			
			echo '<div class="message">';
			echo "<strong>From: </strong>";
			echo $from;
			echo "<br /> ";
			echo "<strong>Subject: </strong>";
			echo $subject;
			echo "<br />";
			echo "<strong>Time: </strong>";
			echo "<em>";
			echo $time;
			echo "</em>";
			echo "<br />";
			echo $text;
			echo "<br />";
			echo "<br /><br /></div>";
			
		
		}//end while


		//close connections
		mysqli_close($db_server);

	



	}//end listMessagesFromMyStudents





?>
