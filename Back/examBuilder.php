<?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //and returns a 2D array where the outer array is 
  //a question in the question bank and the inner array is
  //that question's id, description, difficulty, and category 
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post variables
  $id = $_POST['request'];
  $teacher_id = $_POST['teacherId'];
  if(empty($teacher_id)){
    $teacher_id = 'teacher_id';
  }
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  $returnA =array();
  $d_temp;
  $c_temp;
  $con_temp;
  for ($i = 0; $i < count(array_keys($_POST)); $i++){
    //database select
    $q_temp = array_keys($_POST)[$i];
    $sql = "SELECT * FROM QUESTION_BANK WHERE question_id = $q_temp ORDER BY question_id ASC;";
    $result = $sqlConnection->query($sql);
    $row = $result->fetch_assoc();
    switch($row['difficulty']) {
      case 1:
        $d_temp = "Easy";
        break;
      case 2:
        $d_temp = "Medium";
        break;
      case 3:
        $d_temp = "Hard";
        break;
    }
    switch($row['category']) {
      case 1:
        $c_temp = "If Statements";
        break;
      case 2:
        $c_temp = "While Loops";
        break;
      case 3:
        $c_temp = "For Loops";
        break;
      case 4:
        $c_temp = "Arrays";
        break;
      case 5:
        $c_temp = "Recursion";
        break;
      case 9:
        $c_temp = "Misc";
        break;
    }
    switch($row['constr']) {
      case 0:
        $con_temp = "None";
        break;
      case 1:
        $con_temp = "For Loop";
        break;
      case 2:
        $con_temp = "While Loop";
        break;
      case 3:
        $con_temp = "Recursion";
        break;
    }
    
    //build output array
    $returnA[] = array($row['question_id'], $row['description'], $d_temp, $c_temp, $con_temp );
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
  ?>