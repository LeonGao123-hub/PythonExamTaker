<?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //which takes as input an exam_id and a student_id
  //and question_id if given
  //and returns with an array where
  //the elements are question_id, description, weight, student input
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post variables
  $exam_id = $_POST['examId'];
  //if(empty($exam_id)){$exam_id=69;}
  $student_id = $_POST['studentId'];
  //if(empty($student_id)){$student_id=1;}
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //post variable for question, default handling for no given question id
  $question_id = $_POST['questionId'];
  if(empty($question_id)){
    $sql = "SELECT * FROM EXAM_QUESTION WHERE exam_id = $exam_id ORDER BY question_id ASC;";
    $result = $sqlConnection->query($sql);
    $row = $result->fetch_assoc();
    $question_id = $row['question_id'];
  }
  
  //database select - description
  $sql = "SELECT * FROM QUESTION_BANK WHERE question_id = $question_id ORDER BY question_id ASC;";
  $result = $sqlConnection->query($sql);
  $row = $result->fetch_assoc();
  $descTemp = $row['description'];
  
  //database select - weight
  $sql = "SELECT * FROM EXAM_QUESTION WHERE exam_id = $exam_id AND question_id = $question_id ORDER BY question_id ASC;";
  $result = $sqlConnection->query($sql);
  $row = $result->fetch_assoc();
  $wTemp = $row['weight'];
  
  //database select - q_input
  $sql = "SELECT * FROM STUDENT_RESULTS WHERE exam_id = $exam_id AND question_id = $question_id AND student_id = $student_id ORDER BY question_id ASC;";
  $result = $sqlConnection->query($sql);
  $row = $result->fetch_assoc();
  $inputTemp = $row['q_input'];
  
  //database select - next question
  $sql = "SELECT * FROM EXAM_QUESTION WHERE exam_id = $exam_id AND question_id = (SELECT min(question_id) FROM EXAM_QUESTION WHERE question_id > $question_id);";
  $result = $sqlConnection->query($sql);
  if(mysqli_num_rows($result) == 0){
    $next_question_id = 0;
    $next_flag = 0;
  }
  else{
    $row = $result->fetch_assoc();
    $next_question_id = $row['question_id'];
    $next_flag = 1; 
  }
  
  //database select - previous question
  $sql = "SELECT * FROM EXAM_QUESTION WHERE exam_id = $exam_id AND question_id = (SELECT max(question_id) FROM EXAM_QUESTION WHERE question_id < $question_id);";
  $result = $sqlConnection->query($sql);
  if(mysqli_num_rows($result) == 0){
    $prev_question_id = 0;
    $prev_flag = 0;
  }
  else{
    $row = $result->fetch_assoc();
    $prev_question_id = $row['question_id'];
    $prev_flag = 1;
  }
   
  //build output array
  $returnA = array($question_id, $descTemp, $wTemp, $inputTemp);
  //$returnA = array($question_id, $descTemp, $wTemp, $inputTemp, $prev_flag, $prev_question_id, $next_flag, $next_question_id);
  //this return was supposed to be used to create next & previous buttons on the exam, 
  //but we couldnt implement it in time 
  
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
  ?>