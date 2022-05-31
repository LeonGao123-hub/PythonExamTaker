<?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //and executes a number of different queries
  //depending on the post value of $request
  //student - returns an array of all students in a class
  //question - returns a 2D array where the outer array is number of questions
  //      in an exam and the inner array is said that question's id, function name, and weight
  //answers - returns a 2D array where the outer array is all students in the class
  //      and the inner array is a given student's answers to each question
  //testing - returns a 3D array where the outer array is all questions on the exam,
  //    the middle array is all test cases for a given question
  //    and the inner array is a given test case's input, output, and id
  //gradeTest - validates if a student has been graded for a specific test case,
  //    and then either inserts or updates depending on the result
  //gradeQuestion - updates a student's question grade 
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post variables
  //ids  
  $request = $_POST['request'];
  $class_id = $_POST['classId'];
  if(empty($class_id)){$class_id = 1;} 
  $exam_id = $_POST['examId'];
  $student_id = $_POST['studentId'];
  $question_id = $_POST['questionId'];
  $test_id = $_POST['testId'];
  //test case data dat  
  $student_output = $_POST['studentOutput'];
  $test_ans = $_POST['testAns'];
  $test_grade = $_POST['testGrade'];
  //question data    
  $function_ans = $_POST['functionAns'];
  $function_grade = $_POST['functionGrade'];
  $question_grade = $_POST['questionGrade'];
  $constr_ans = $_POST['constraintAns'];
  $constr_grade = $_POST['constraintGrade'];
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //build output
  //$returnA = array();
  $returnQ = array();
  switch($request){
  
    //student
    case "student":
      $sql = "SELECT * FROM CLASS_ROSTER WHERE class_id = $class_id ORDER BY student_id ASC;";
      $result = $sqlConnection->query($sql);
      for($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_assoc();
        $returnA[] = $row['student_id'];
      }
      break;
    
    //question   
    case "question":
      $sql = "SELECT * FROM EXAM_QUESTION LEFT OUTER JOIN QUESTION_BANK ON EXAM_QUESTION.question_id = QUESTION_BANK.question_id WHERE exam_id = $exam_id ORDER BY EXAM_QUESTION.question_id ASC;";
      $result = $sqlConnection->query($sql);
      for($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_assoc();
        $returnA[] = array($row['question_id'], $row['function_name'], $row['weight'], $row['constr']);
      }
      break;
      
  //answers
    case "answers":
      //build array of students
      $sql = "SELECT * FROM CLASS_ROSTER WHERE class_id = $class_id ORDER BY student_id ASC;";
      $result = $sqlConnection->query($sql);
      for($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_assoc();
        $returnS[] = $row['student_id'];
      } 
      //iterate over the array of students
      for($j = 0; $j < count($returnS); $j++){
        $student_id = $returnS[$j];
        $sql2 = "SELECT * FROM STUDENT_RESULTS WHERE exam_id = $exam_id AND student_id = $student_id ORDER BY question_id ASC;";
        $result2 = $sqlConnection->query($sql2);
        $returnQ = array();
        for($k = 0; $k < $result2->num_rows; $k++){
          $row2 = $result2->fetch_assoc();
          $returnQ[] = $row2['q_input'];
        }
        $returnA[] = $returnQ;
      }
      
      break;
      
  //testing
    case "testing":
      //build array of questions
      $sql = "SELECT * FROM STUDENT_RESULTS WHERE exam_id = $exam_id AND student_id = $student_id ORDER BY question_id ASC;";
      $result = $sqlConnection->query($sql);
      for($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_assoc();
        $returnQ[] = $row['question_id'];
      } 
      //iterate over array of question
      for($j = 0; $j < count($returnQ); $j++){
        $question_id = $returnQ[$j];
        $sql2 = "SELECT * FROM TEST_CASE WHERE question_id = $question_id ORDER BY test_id ASC;";
        $result2 = $sqlConnection->query($sql2);
        $returnT = array();
        for($k = 0; $k < $result2->num_rows; $k++){
          $row2 = $result2->fetch_assoc();
          //$returnT[] = array($row2['test_input'], $row2['test_output']);
          $returnT[] = array($row2['test_input'], $row2['test_output'], $row2['test_id']);
        }
        $returnA[] = $returnT;
      }
      break;   
       
  //gradeTest 
     case "gradeTest":
       //check if a row with data exists
       $sqlRowCheck = "SELECT * FROM STUDENT_RESULTS_TESTS WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id' AND test_id='$test_id'";
       $resultRowCheck = $sqlConnection->query($sqlRowCheck);
       $existingRow = $resultRowCheck->fetch_assoc();
       //check if row exists, if yes then update, if no then insert
       if (empty($existingRow)){
         $sql = "INSERT STUDENT_RESULTS_TESTS (result_id, student_id, question_id, exam_id, test_id, student_output, test_ans, test_grade, test_grade_final) VALUES ( NULL, '$student_id','$question_id', '$exam_id', '$test_id', '$student_output', '$test_ans', '$test_grade', '$test_grade')";
       }
       else{
         $sql = "UPDATE STUDENT_RESULTS_TESTS SET student_output = '$student_output', test_ans = '$test_ans', test_grade = '$test_grade', test_grade_final = '$test_grade' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id' AND test_id='$test_id'";
       }   
       $result = $sqlConnection->query($sql);
       if ($result){
         $returnA =  "Update Successful!\n";
       }
       else{
         $returnA = "Update Unsuccessful.\n";
       }
       break;
     
  //gradeQuestion
     case "gradeQuestion":
       $sql = "UPDATE STUDENT_RESULTS SET function_ans = '$function_ans', function_grade = '$function_grade',function_grade_final = '$function_grade', question_grade = '$question_grade', constr_ans = '$constr_ans', constr_grade = '$constr_grade', constr_grade_final = '$constr_grade' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
       $result = $sqlConnection->query($sql);
       if ($result){
         $returnA =  "Update Successful!\n";
       }
       else{
         $returnA = "UPDATE FAILED.\n";
       }
       break;
  
  //default       
     default:
       $returnA = "defaulted...";
       break; 
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
  ?>