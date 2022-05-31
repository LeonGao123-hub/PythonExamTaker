<?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //which takes as input an exam_id, student_id and question_id:q_input
  //and updates the database with that question input
  
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
  
  //get question id and q_input
  for ($i = 0; $i < count(array_keys($_POST)); $i++){
    $question_id = array_keys($_POST)[$i];
    $q_input = array_values($_POST)[$i];
    
    //skip key=>value pairs that aren't question_id=>weight
    if(!is_int($question_id)){
      continue;
    }
    
    //set flag
    $sql = "UPDATE STUDENT_RESULTS SET q_input = '$q_input' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
    $result = $sqlConnection->query($sql);
    
    //return message
    if($result){
      $resp = "Question Submitted";
    }
    else{
      $resp = "Failed to Submit Question";
    }
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($resp);
  echo $return;
?>