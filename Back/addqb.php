  <?php
  //Tyler Zamski - CS 490 - Beta (Back end)
  //this program recieves data via a post curl request
  //adds it to the question bank & test case database tables
  //and returns "Success!" on successful insert 
  //or "Error" if insert failed
  //and deletes a question if test case fail to insert
  
  //database variables
  $dbServer = "sql1.njit.edu";
  $dbUser = "tz6";
  $dbPass = "DataBaseB0!";
  $dbName = "tz6";
  
  //post data
  $teacher = $_POST['teacherId'];
  $funct_Name = $_POST['fName'];
  $difficulty = $_POST['dif'];
  $category = $_POST['cat'];
  $constraint = $_POST['con'];
  if(empty($constraint)){
    $constraint = 0;
  }
  $desc = $_POST['qDescript'];
  $testIn1 = $_POST['T1'];
  $testOut1 = $_POST['T1O'];
  $testIn2 = $_POST['T2'];
  $testOut2 = $_POST['T2O'];
  $testIn3 = $_POST['T3'];
  $testOut3 = $_POST['T3O'];
  $testIn4 = $_POST['T4'];
  $testOut4 = $_POST['T4O'];
  $testIn5 = $_POST['T5'];
  $testOut5 = $_POST['T5O'];
  
  //connect to mysql database
  $sqlConnection = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
  if ($sqlConnection->connect_error){
    die("Connection failed: " . $sqlConnection->connect_error);
  }
  
  //question bank insert
  $sql = "INSERT INTO QUESTION_BANK (question_id, teacher_id, function_name, difficulty, category, constr, description) VALUES (NULL, '$teacher', '$funct_Name', '$difficulty', '$category', '$constraint', '$desc')";
  $result = $sqlConnection->query($sql);
  
  //error check the insert 
  if($result){
  
    //get new question id
    $tcFailFlag = 0;
    $questionCheck = "SELECT question_id FROM QUESTION_BANK WHERE teacher_id='$teacher' AND function_name='$funct_Name' AND difficulty='$difficulty' AND category='$category' AND description='$desc'";
    $questionResult = $sqlConnection->query($questionCheck);
    $q_id = $questionResult->fetch_assoc();
    $q = $q_id['question_id'];
    
    //add test case 1
    $test_insert1 = "INSERT INTO TEST_CASE (test_id, question_id, test_input, test_output) VALUES (NULL, '$q', '$testIn1', '$testOut1')";
    $resultTestCase = $sqlConnection->query($test_insert1);
    if(!$resultTestCase){$tcFailFlag = 1;}
    
    //add test case 2
    $test_insert2 = "INSERT INTO TEST_CASE (test_id, question_id, test_input, test_output) VALUES (NULL, '$q', '$testIn2', '$testOut2')";
    $resultTestCase = $sqlConnection->query($test_insert2);
    if(!$resultTestCase){$tcFailFlag = 1;}
    
    //add test case 3
    if(!empty($testIn3)){
      $test_insert3 = "INSERT INTO TEST_CASE (test_id, question_id, test_input, test_output) VALUES (NULL, '$q', '$testIn3', '$testOut3')";
      $resultTestCase = $sqlConnection->query($test_insert3);
      if(!$resultTestCase){$tcFailFlag = 1;}
    }
    
    //add test case 4
    if(!empty($testIn4)){
      $test_insert4 = "INSERT INTO TEST_CASE (test_id, question_id, test_input, test_output) VALUES (NULL, '$q', '$testIn4', '$testOut4')";
      $resultTestCase = $sqlConnection->query($test_insert4);
      if(!$resultTestCase){$tcFailFlag = 1;}
    }
    
    //add test case 5
    if(!empty($testIn5)){
      $test_insert5 = "INSERT INTO TEST_CASE (test_id, question_id, test_input, test_output) VALUES (NULL, '$q', '$testIn5', '$testOut5')";
      $resultTestCase = $sqlConnection->query($test_insert5);
      if(!$resultTestCase){$tcFailFlag = 1;}
    }
    
    //delete question if tcFailFlag is 1
    if($tcFailFlag == 1){
      $sql = "DELETE FROM QUESTION_BANK WHERE question_id = $q;";
      $result = $sqlConnection->query($sql);
    }
    
    //generate response
    if($tcFailFlag == 0){
      $resp = "Success - Question added to bank";
    }
    else{
      $resp = "Error - Failed to add a test case";
    }
  }
  else{
    $resp = "Error - Failed to add question to bank";
  }
  
  //return data
  $sqlConnection->close();
  $return = json_encode($resp);
  echo $return;
  ?>