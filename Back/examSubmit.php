<?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //and takes as input student_id and exam_id
  //and updates the attempt_flag column in the db
  
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
  
  //set flag
  $sql = "UPDATE STUDENT_RESULTS_EXAM SET attempt_flag = 1 WHERE student_id = '$student_id' AND exam_id = '$exam_id'";
  $result = $sqlConnection->query($sql);
  
  //return message
  if($result){
    $resp = "Exam Submitted";
  }
  else{
    $resp = "Failed to Submit Exam";
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($resp);
  echo $return;
?>