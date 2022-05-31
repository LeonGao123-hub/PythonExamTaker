  <?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //and returns an array of all available exams
  //based on a given request variable
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post variables
  //default values are temporary!
  $class_id = $_POST['classId'];
  if(empty($class_id)){
    $class_id = 1;
  }
  $student_id = $_POST['studentId'];
  $teacher_id = $_POST['teacherId'];
  $request = $_POST['request'];
  
  //build sql select
  switch($request){
  
  case "studentResult":
    $sql = "SELECT * FROM STUDENT_RESULTS_EXAM JOIN EXAM ON EXAM.exam_id WHERE class_id = '$class_id' AND STUDENT_RESULTS_EXAM.student_id = '$student_id' AND EXAM.exam_id = STUDENT_RESULTS_EXAM.exam_id  ORDER BY EXAM.exam_id ASC;";
    break;
    
  case "studentExam":
    $sql = "SELECT * FROM STUDENT_RESULTS_EXAM JOIN EXAM ON EXAM.exam_id WHERE class_id = '$class_id' AND STUDENT_RESULTS_EXAM.student_id = '$student_id' AND EXAM.exam_id = STUDENT_RESULTS_EXAM.exam_id AND attempt_flag = 0 ORDER BY EXAM.exam_id ASC;";
    break;
    
  case "teacher":
    $sql = "SELECT * FROM EXAM WHERE class_id = $class_id AND teacher_id = $teacher_id ORDER BY exam_id ASC;";
    break;
  }  
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //database select
  $result = $sqlConnection->query($sql);

  //build output array
  $returnA =array();
  for($i = 0; $i < $result->num_rows; $i++){
    $row = $result->fetch_assoc();
    $returnA[] = array($row['exam_id'], $row['exam_name']);
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
  ?>