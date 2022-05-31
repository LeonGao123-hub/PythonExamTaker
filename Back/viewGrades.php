<?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //takes as input a student id and exam id
  //and returns a 2D array where
  //the outer array is a specific question that student answered
  //the inner array is of the following form:
  //question_id, function_name, number of test cases
  //then a nested 2D array of test cases in the form:
  //    outer arrays are each test case, inner arrays are the following:
  //    test_id, test_input, test_output, student_output, test_grade, test_grade_final
  //then
  //array of function_grade, function_grade_final, 
  //constr (yes/no if constr exist)
  //array of constr_grade, constr_grade_final,
  //comment, final_question_grade
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post variables
  $student_id = $_POST['studentId'];
  $exam_id = $_POST['examId'];
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //database select
  $returnA = array();
  $tempA = array();
  
  //iterate over all questions a student answered
  $sql = "SELECT * FROM STUDENT_RESULTS WHERE exam_id = $exam_id AND student_id = $student_id ORDER BY question_id ASC;";
  $result = $sqlConnection->query($sql);
  for($i = 0; $i < $result->num_rows; $i++){
    //get question_id
    $row = $result->fetch_assoc();
    $q_temp = $row['question_id'];
    
    //get function name
    $sqlFunctName = "SELECT * FROM QUESTION_BANK WHERE question_id = $q_temp";
    $fNameResult = $sqlConnection->query($sqlFunctName);
    $row2 = $fNameResult->fetch_assoc();
    $fName = $row2['function_name'];
    
    //get test count
    $sqlTestCount = "SELECT * FROM TEST_CASE WHERE question_id = $q_temp";
    $tCountResult = $sqlConnection->query($sqlTestCount);
    $tCount = $tCountResult->num_rows;
    
    //add to temp array
    $returnT = array();
    //$returnT = array($fName, $tCount);
    $returnT = array($q_temp, $fName, $tCount);
    
    //get test_input, test_output, student_output, test_grade
    $sqlTestResults = "SELECT * FROM STUDENT_RESULTS_TESTS JOIN TEST_CASE ON STUDENT_RESULTS_TESTS.test_id = TEST_CASE.test_id WHERE STUDENT_RESULTS_TESTS.exam_id = $exam_id AND STUDENT_RESULTS_TESTS.student_id = $student_id AND TEST_CASE.question_id = $q_temp ORDER BY TEST_CASE.test_id ASC;";
    $resultTests = $sqlConnection->query($sqlTestResults); 
    for($j = 0; $j < $resultTests->num_rows; $j++){
      $rowTests = $resultTests->fetch_assoc();
      $returnT[] = array($rowTests['test_id'], $rowTests['test_input'], $rowTests['test_output'], $rowTests['student_output'], $rowTests['test_grade'], $rowTests['test_grade_final']);
    }
    
    //get function_name_grade, constr_yes_no, constr_grade, comments
    $returnT[] = array($row['function_grade'], $row['function_grade_final']);
    $returnT[] = $row2['constr'];
    $returnT[] = array($row['constr_grade'], $row['constr_grade_final']);
    $returnT[] = $row['comment'];
    $returnT[] = $row['question_grade'];
    
    //build output array
    $returnA[] = $returnT;
    
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
?>