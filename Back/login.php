  <?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program validates a user's credentials
  //and logs them into their respective landing page
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post variables
  $postUser = $_POST['user_name'];
  $postPass = $_POST['pass_word'];
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  $sql = "SELECT * FROM USER_IDS WHERE user_name='$postUser' AND password='$postPass'";
  $result = $sqlConnection->query($sql);
  
  //interpret query results
  $returnA =array();
  if ($result->num_rows > 0){
      $row = $result->fetch_assoc();
      if ($row['role'] == '2'){
        $thisUser = $row['user_id'];
        $nameF = $row['first_name'];
        $nameL = $row['last_name'];
        $sqlUserId = "SELECT student_id FROM STUDENT WHERE user_id='$thisUser'";
        $resultId = $sqlConnection->query($sqlUserId);
        $userRow = $resultId->fetch_assoc();
        $idFinal = $userRow['student_id'];
        $resp = array("resp"=>"student", "userId"=>$idFinal, "nameF"=>$nameF, "nameL"=>$nameL);
      }
      else if ($row['role'] == '1'){
        $thisUser = $row['user_id'];
        $nameF = $row['first_name'];
        $nameL = $row['last_name'];
        $sqlUserId = "SELECT teacher_id FROM TEACHER WHERE user_id='$thisUser'";
        $resultId = $sqlConnection->query($sqlUserId);
        $userRow = $resultId->fetch_assoc();
        $idFinal = $userRow['teacher_id'];
        $resp = array("resp"=>"teacher", "userId"=>$idFinal, "nameF"=>$nameF, "nameL"=>$nameL);
      }
      else{
        $resp = array("resp"=>"bad");
      } 
  } 
  else{
    $resp = array("resp"=>"bad");
  }
  
  //close connection and respond to post
  $sqlConnection->close();
  $respEncode = json_encode($resp);
  echo $respEncode;
  ?>