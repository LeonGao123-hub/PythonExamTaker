  <?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program recieves data via a post curl request
  //adds it to the exam & exam questions database tables
  //and returns a status message of success on successful inserts
  //and an error message if any insert fails
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post data
  $teacher_id = $_POST['teacherId'];
  $class_id = $_POST['classId'];
  if(empty($class_id)){
    $class_id = 1;
  }
  $exam_name = $_POST['eName'];
  if(empty($exam_name)){
    $exam_name = "New Exam";
  }
  $function_weight = $_POST['functionWeight'];
  if(empty($function_weight)){
    $function_weight = 0;
  }
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //exam insert
  $sql = "INSERT INTO EXAM (exam_id, class_id, teacher_id, exam_name, function_weight) VALUES (NULL, '$class_id', '$teacher_id', '$exam_name', '$function_weight')";
  $result = $sqlConnection->query($sql);
  
  //error check the insert 
  if($result){
    //get exam_id
    $examCheck = "SELECT exam_id FROM EXAM WHERE class_id='$class_id' AND teacher_id='$teacher_id' AND exam_name='$exam_name' AND function_weight='$function_weight' ORDER BY exam_id DESC";
    $examResult = $sqlConnection->query($examCheck);
    $e_id = $examResult->fetch_assoc();
    $exam_id = $e_id['exam_id'];
    
    //build exam question pairs
    for ($i = 0; $i < count(array_keys($_POST)); $i++){
      $q_temp = array_keys($_POST)[$i];
      $w_temp = array_values($_POST)[$i];
      
      //skip key=>value pairs that aren't question_id=>weight
      if(!is_int($q_temp)){
        continue;
      }
      
      //add to exam_question table
      $sql2 = "INSERT INTO EXAM_QUESTION (exam_id, question_id, weight) VALUES ($exam_id, '$q_temp', '$w_temp')";
      $result2 = $sqlConnection->query($sql2);
    }
    
    //build student array
    $studentA = array();
    $sql = "SELECT student_id FROM CLASS_ROSTER WHERE class_id='$class_id' ORDER BY student_id ASC";
    $result = $sqlConnection->query($sql);
    for($i = 0; $i < $result->num_rows; $i++){
       $row = $result->fetch_assoc();
       $studentA[] = $row['student_id'];
    }
    
    //populate student_results_exam
    for($i = 0; $i < count($studentA); $i++){
      $student_id = $studentA[$i];
      $sql = "INSERT INTO STUDENT_RESULTS_EXAM (student_id, exam_id, attempt_flag, exam_grade) VALUES ($student_id, $exam_id, 0, 0)";
      $result = $sqlConnection->query($sql);
    }
    
    //build exam question array
    $questionA = array();
    $sql = "SELECT question_id FROM EXAM_QUESTION WHERE exam_id='$exam_id' ORDER BY question_id ASC";
    $result = $sqlConnection->query($sql);
    for($i = 0; $i < $result->num_rows; $i++){
       $row = $result->fetch_assoc();
       $questionA[] = $row['question_id'];
    }
    
    //populate student_results
    for($i = 0; $i < count($studentA); $i++){
      $student_id = $studentA[$i];
      for($j = 0; $j < count($questionA); $j++){
        $question_id = $questionA[$j];
        $sql = "INSERT INTO STUDENT_RESULTS (student_id, question_id, exam_id, q_input, function_ans, function_grade, function_grade_final, constr_ans, constr_grade, constr_grade_final, comment, question_grade) VALUES ($student_id, $question_id, $exam_id,'', 0, 0, 0, 0, 0, 0, '', 0)";
      $result = $sqlConnection->query($sql);
      }
    }
    $return = "Success - exam created.";
  }
  else{
    $return = "Error - Failed to create exam.";
  }

  //return data
  $sqlConnection->close();
  $return = json_encode($return);
  echo $return;
  ?>