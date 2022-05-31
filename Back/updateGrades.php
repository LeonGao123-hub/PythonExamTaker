<?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //it recieves an array of inputs where
  //each input in a string, 
  //which is then spliced to get ids and updates to grades
  
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
  
  $retV = array();
  for ($i = 0; $i < count(array_keys($_POST)); $i++){
    //input comes in as an array of key value pairs of the form:
    //studentId_questionId_examId_"case" => "value"
    $needle = "_";
    $k_temp = array_keys($_POST)[$i];
    $v_temp = array_values($_POST)[$i];
    //$k_temp = $_POST['testKey'];
    //$v_temp = $_POST['testValue'];
  
    //get student id
    $student_id = substr($k_temp, 0, strpos($k_temp, $needle));
    $splicedString = substr($k_temp, strpos($k_temp, $needle)+1);
    //get question id
    $question_id = substr($splicedString, 0, strpos($splicedString, $needle));
    $splicedString = substr($splicedString, strpos($splicedString, $needle)+1);
    //get exam id
    $exam_id = substr($splicedString, 0, strpos($splicedString, $needle));
    $splicedString = substr($splicedString, strpos($splicedString, $needle)+1);
    
    switch($splicedString){
    
      //update comment        
      case "comment":
        if($v_temp === "0" || !empty($v_temp)){
          $comment = $v_temp;
          $sql = "UPDATE STUDENT_RESULTS SET comment = '$comment' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
          $result = $sqlConnection->query($sql);
        }     
        break;
        
      //update function grade        
      case "func":
        if($v_temp === "0" || !empty($v_temp)){
          $func = $v_temp;
          $sql = "UPDATE STUDENT_RESULTS SET function_grade_final = '$func' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
          $result = $sqlConnection->query($sql);
        }      
        break;
        
      //update constraint grade        
      case "constr":
        if($v_temp === "0" || !empty($v_temp)){
          $constr = $v_temp;
          $sql = "UPDATE STUDENT_RESULTS SET constr_grade_final = '$constr' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
          $result = $sqlConnection->query($sql);
        }     
        break;
        
      //defaults to updating test case grades
      default:
        if($v_temp === "0" || !empty($v_temp)){
          //strip out grade
          $gradeKey = substr($splicedString, 0, strpos($splicedString, $needle));
          $splicedString = substr($splicedString, strpos($splicedString, $needle)+1);
          if($gradeKey == "grade"){
            $test_grade = $v_temp;
            $test_id = $splicedString;
            $sql = "UPDATE STUDENT_RESULTS_TESTS SET test_grade_final = '$test_grade' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id' AND test_id = '$test_id'";
            $result = $sqlConnection->query($sql);
          }
        }  
        break;      
    }
  }

  //update grades
  $sql2 = "SELECT * FROM STUDENT_RESULTS WHERE exam_id = '$exam_id' AND student_id = '$student_id' ORDER BY question_id ASC;";
  $result2 = $sqlConnection->query($sql2);
  for($i = 0; $i < $result2->num_rows; $i++){
    $rowQ = $result2->fetch_assoc();
    $question_id = $rowQ['question_id'];
  
    //recalculate question grades
    $question_grade = 0;
    
    //add test cases
    $sql = "SELECT * FROM STUDENT_RESULTS_TESTS WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
    $result = $sqlConnection->query($sql);
    for($j = 0; $j < $result->num_rows; $j++){
      $row = $result->fetch_assoc();
      $question_grade = $question_grade + $row['test_grade_final'];
    }
    
    //add function & constraint grades
    $sql = "SELECT * FROM STUDENT_RESULTS WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
    $result = $sqlConnection->query($sql);
    $row = $result->fetch_assoc();
    $question_grade = $question_grade + $row['function_grade_final'] + $row['constr_grade_final'];
    
    $sql = "UPDATE STUDENT_RESULTS SET question_grade = '$question_grade' WHERE student_id = '$student_id' AND question_id = '$question_id' AND exam_id = '$exam_id'";
    $result = $sqlConnection->query($sql);
  }
  $returnA = "Update successful";
   
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
?>