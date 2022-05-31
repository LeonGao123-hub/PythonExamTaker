<?php
  $back = "https://afsaccess4.njit.edu/~tz6/back/autoGrader.php";
  $exam_id = $_POST['exam'];
  if(empty($exam_id))
  {
    $exam_id = "142";
  }
  $class_id = "1";
  $student = "student";
  $answers = "answers";
  $question = "question";
  $testing = "testing";
  $constraint = 1;
  $question_request = "request=$question&examId=$exam_id";
  $student_request = "examId=$exam_id&classId=$class_id&request=$student";
  $questions = curl($back, $question_request);
  $students = curl($back, $student_request);
  $s = $students[0];
  $error = False;
  if(empty($s)){echo "no"; $error = True;}
  $test_request = "request=$testing&studentId=$s&examId=$exam_id";
  $answer_request = "studentId=$s&request=$answers&classId=$class_id&examId=$exam_id";
  $answers = curl($back, $answer_request);
  $in_out = curl($back, $test_request);
  function curl($back,$post)
  {
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $back);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);   
    $return = json_decode(curl_exec($ch),true);    
    curl_close($ch);
    return $return; 
  }
  function checkConstraint($lines, $constraint_pattern)
  {
      foreach ($lines as $line)
      {
        if(preg_match($constraint_pattern, $line) != 0)
        {
          return True;
        }
      }
      return False; 
  }
  function checkRecurssion($lines, $constraint_pattern)
  {
      $matches = 0;
      foreach ($lines as $line)
      {
        if(preg_match($constraint_pattern, $line) != 0)
        { 
          $matches++;
        }
      } 
      if(preg_match($constraint_pattern, $line) != 0)  
      {
        return True;
      }
      return False;
  } 
  function grade_this($back, $stu, $questions, $code, $in_out, $exam_id, $sid, $constraint)
  {
    $gradeTest = "gradeTest";
    $test_grade = 0;
    $gradeQuestion = "gradeQuestion";
    $test_ans = 1;
    $function_ans = 1;
    $constraint_pattern = " ";
    $function_grade = 0;
    $constraint_grade = 0;
    $constraint_ans = 0;
    $student_output = "";
    $equal = True;
    $cons = True;
    $results = "";
    $correct = "";
    $output = array();
    $correct = array();
    $lined = "lined.txt";
    $unlined = "unlined.txt";
    $maxq = count($questions);
    $question_grade = 0;

    for($i = 0; $i < $maxq; $i++)
    {
      $constraint = $questions[$i][3];
      if($constraint == 0)
      {

        $test_percent = 0.9/count($in_out[$i]);

      }
      else
      {
        $test_percent = 0.8/count($in_out[$i]);
      }
      
      $question_weight = $questions[$i][2];
      $function_name = $questions[$i][1];
      $question_grade = 0;
      $qid = $questions[$i][0];
      $myfile = fopen($lined, "w") or die("Unable to open file!");
      $testfile = fopen("hi.txt", "w") or die("Unable to open file!");
      fwrite($myfile, $code[$i]);
      /*
      if($i == 1)
      {
        fwrite($testfile, $code[$i]);
      }*/
      fclose($myfile);
      if (file_exists($unlined)) {
        unlink($unlined);
      }
      exec("sed -r '/^\s*$/d' $lined > $unlined",$output,$retval);
      $output = array();
      $lines = file($unlined);
      $pattern = '/def '.$function_name.'/';
      $replaced = "/def.*\(/";
      //$replaced = "/def [a-zA-Z][a-zA-Z0-9]*([[a-zA-Z][a-zA-Z0-9]*[,[a-zA-Z][a-zA-Z0-9]*]*]*)/";
      if(preg_match($pattern, $lines[0]) == 0)
      {
        $function_ans = 0;
        $function_grade = 0;
        $lines[0] = preg_replace($replaced,'def '.$function_name."( ", $lines[0]);
      }
      else
      {
        $function_ans = 1;
        $function_grade = 0.1*$question_weight;
        $question_grade += $function_grade;
      }
      file_put_contents($unlined,$lines);
      switch ($constraint) 
      {
        case 0:
          $cons = false;
          break;
        case 1:
          $cons = checkConstraint($lines,"/for/");
          break;
        case 2:
          $cons = checkConstraint($lines,"/while/");
          break;
        case 3:
          $cons = checkRecurssion($lines,"/".$function_name."/");  
      }
      if(!$cons)
      {
        $constraint_grade = 0; 
        $constraint_ans = 0;
      }
      else
      {
        $constraint_grade = 0.1*$question_weight; 
        $constraint_ans = 1;
        $question_grade += $constraint_grade;
      }
      $out = "output_".$stu."_".$i.".py";
      $out2 = "output_".$stu."_".$i;
      if (file_exists($out)) 
      {
        unlink($out);
      }
      if (file_exists($out2)) 
      {
        unlink($out2);
      }
      exec("cat $unlined > $out",$output,$retval);
      
      $output = array();
      for($j = 0; $j < count($in_out[$i]); $j++)
      {
        $params = $in_out[$i][$j][0];
        $correct = $in_out[$i][$j][1];
        $test_id = $in_out[$i][$j][2];
        $command = "python -c 'import ".$out2."; "."print(".$out2.".".$function_name."(".$params."))'";
        exec($command,$ho,$retval);
        $results = explode("\r\n", $correct);
        for($k = 0; $k < count($results); $k++)
        {
          if($results[$k] != $ho[$k])
          {$equal = False;}
        }

        $student_output = implode("<br>", $ho);
        if($equal  == True)
        {
          $test_ans = 1;
          $test_grade = $test_percent*$question_weight;
        }
        else
        {
          $test_ans = 0; 
          $test_grade = 0;
        }
        $equal = True;
        $ho = array();
        $send_test_grade = "request=$gradeTest&classId=$class_id&studentId=$sid&examId=$exam_id&questionId=$qid&testId=$test_id&studentOutput=$student_output&testAns=$test_ans&testGrade=$test_grade";
        curl($back, $send_test_grade);
        $question_grade += $test_grade;
      }
      $send_question_grade = "request=$gradeQuestion&studentId=$sid&examId=$exam_id&questionId=$qid&functionAns=$function_ans&functionGrade=$function_grade&questionGrade=$question_grade&constraintGrade=$constraint_grade&constraintAns=$constraint_ans";   
      curl($back, $send_question_grade);
    }
  } 
  if($error == False)
  { 
    for($a = 0; $a < count($students); $a++)
    {   
        grade_this($back, $a, $questions, $answers[$a], $in_out, $exam_id, $students[$a], $constraint);
    }
    echo "success";
  }
?>