  <?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //and returns an array of students in a class
  
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
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //build array of student ids
  $sql = "SELECT * FROM CLASS_ROSTER WHERE class_id = $class_id ORDER BY student_id ASC;";
  $result = $sqlConnection->query($sql);
  $studentA = array();
  for($i = 0; $i < $result->num_rows; $i++){
    $row = $result->fetch_assoc();
    $studentA[] = $row['student_id'];
  }
  
  //build output array
  $returnA = array();
  for($i = 0; $i < count($studentA); $i++){
    $sTemp =  $studentA[$i];
    $sql2 = "SELECT * FROM USER_IDS JOIN STUDENT ON USER_IDS.user_id WHERE USER_IDS.user_id = STUDENT.user_id AND student_id = $sTemp ORDER BY STUDENT.student_id ASC;";
    $result = $sqlConnection->query($sql2);
    $row = $result->fetch_assoc();
    $returnA[] = array($row['student_id'], $row['last_name'], $row['first_name'] );
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
  ?>