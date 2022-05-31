  <?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program receives a post curl request
  //which takes as input an exam_id
  //and returns with an array where
  //each element is a question_id in that exam
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post variables
  $exam_id = $_POST['examId'];
  if(empty($exam_id)){
    $exam_id = 69;
  }
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //database select
  $sql = "SELECT * FROM EXAM_QUESTION WHERE exam_id = $exam_id ORDER BY question_id ASC;";
  $result = $sqlConnection->query($sql);
  
  //build output array
  $returnA =array();
  $temp;
  for($i = 0; $i < $result->num_rows; $i++){
    $row = $result->fetch_assoc();
    $temp = $row['question_id'];
    $sqlgetQuestion = "SELECT * FROM QUESTION_BANK WHERE question_id = $temp;";
    $result2 = $sqlConnection->query($sqlgetQuestion);
    $row2 = $result2->fetch_assoc();
    $returnA[] = $row2['question_id'];
  }
  
  //return array
  $sqlConnection->close();
  $return = json_encode($returnA);
  echo $return;
  ?>