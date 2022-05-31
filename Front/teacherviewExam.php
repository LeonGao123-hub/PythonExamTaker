<?php
session_start();
if( $_SESSION['role'] != "teacher" || isset($_POST['logOut']) ){
    session_destroy();
    header("Location: https://afsaccess4.njit.edu/~lg278/index.php");
    
}

?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
 <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>
<body class="text-center">
<main class="content">

<form method="post">
<button class="btn btn-outline-primary" name="logOut">logout</button>
</form>

<br>
<a href="https://afsaccess4.njit.edu/~lg278/teacherlanding.php">
<button class="btn btn-outline-primary">back to landing page</button>
</a>

<a href="https://afsaccess4.njit.edu/~lg278/teacherselectExam.php">
<button class="btn btn-outline-primary">return to select exam</button>
</a>

<h1>Exam Result</h1>
<?php

    $URL = 'https://afsaccess4.njit.edu/~tz6/back/viewGrades.php';   
    $test_id = $_POST["exam"];    
    $t_id = $_SESSION['id']; 
    $s_id = $_POST["student"];
		$post="studentId=$s_id&examId=$test_id";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
		curl_close($ch);
    $resultArray = json_decode($result, true);
    
?>

<form  type = "teacherviewexam_beta.php" method="post" id ="viewExam">
</form>


<script>
  var results = <?php echo json_encode($resultArray, JSON_HEX_TAG); ?>;
  var e_id = <?php echo json_encode($test_id, JSON_HEX_TAG); ?>;
  var s_id = <?php echo json_encode($s_id, JSON_HEX_TAG); ?>;
  var temp =0;
  let viewresult = document.getElementById("viewExam");
  

   results.forEach((question)=>{
   var headerCreated = false;
   var questionIndex = 0;
   var numofCase = 0;  
   var q_id ="";
   var t_id = "";
   var displayConstr = true;
  
   var caseCnt = 0;
   var caseCounter = 0;
   
   
   
   let table = document.createElement("table");
   table.setAttribute("border",1);
   let row = document.createElement("tr");          
   table.append(row);
   
 
   question.forEach((testcases)=>{
   
  
   
   if(questionIndex == 0)
   {
       q_id = testcases;
       questionIndex = questionIndex + 1;
       return;
      
   }
    // get the function 
   if(questionIndex == 1)
   {
      let box = document.createElement("th");
      box.setAttribute("colspan","7");
      box.width="150";
      box.append(document.createTextNode("function Name: " + testcases));
      row.append(box);
      questionIndex = questionIndex + 1;
      return;
   
   }
 // get num of testcases
   if(questionIndex == 2)
   {
      numofCase = testcases;
      questionIndex = questionIndex + 1;
      return;
   
   }

 
   
          
    // the actual test cases and questions                
   if(questionIndex < 3 + numofCase)
   {  
           caseCounter++;
           
           if(headerCreated == false)
           {
             let row = document.createElement("tr");
             table.append(row); 
             
             let blank = document.createElement("th");
             blank.append(document.createTextNode("     "));
             
             let test_input = document.createElement("th");
             test_input.append(document.createTextNode("Input"));
             
             let test_output = document.createElement("th");
             test_output.append(document.createTextNode("Expected output"));
             
             let student_output = document.createElement("th");
             student_output.append(document.createTextNode("student_output"));
             
             let test_ans = document.createElement("th");
             test_ans.append(document.createTextNode("Autograder grade"));
             
             let test_grade = document.createElement("th");
             test_grade.append(document.createTextNode("Overrideable grade"));
             
             let change_grade = document.createElement("th");
             change_grade.append(document.createTextNode("Change grade"));
             
             row.append(blank);
             row.append(test_input);
             row.append(test_output);
             row.append(student_output);
             row.append(test_ans);
             row.append(test_grade);
             row.append(change_grade);             
             headerCreated = true;
             
                                   
           }
           var testcaseIdx = 0;
           
           
 
           caseCnt++;                                 
           let row = document.createElement("tr");     
           table.append(row);                
           
           
           let testcaseHeader = document.createElement("tr");
           testcaseHeader.width="150";
           testcaseHeader.append(document.createTextNode("TestCase " + caseCnt));
           row.append(testcaseHeader);    
             
           
           testcases.forEach((testcase)=>{
             
             
             if(testcaseIdx == 0)
             {
               
               t_id = testcase;
               testcaseIdx++;
               return; 
                      
             }             
             let box = document.createElement("td");
             let i = document.createElement('input');
             
             box.width="150";
             box.append(document.createTextNode(testcase));
             row.append(box);
             
                    
             testcaseIdx++;
             
             if(testcaseIdx == 6)
             {
                 let box = document.createElement("td");
                 let editGrade = document.createElement("input");
                 editGrade.setAttribute("name",s_id + "_"  + q_id + "_" + e_id  + "_grade" + "_"+ t_id);                 
                 box.width="150";
                 box.append(editGrade);
                 row.append(box);
             
             
             
             }      
             
             
     });
     
     
     }
     
     
     // prints function name grade
     if( questionIndex == 3 + numofCase)
     {
        let row = document.createElement("tr");                     
        table.append(row);
        let box = document.createElement("td");
        box.setAttribute("colspan","4");       
        box.width="150";
        box.append(document.createTextNode("Function name grade"));
        row.append(box);
          
        
        
        
        testcases.forEach((testcase)=>{
        
         let box1 = document.createElement("td");
        box1.setAttribute("colspan","1");
        box1.width="150";
        box1.append(document.createTextNode(testcase));  
        row.append(box1);    
          
         
         
          
           });
           
           let box3 = document.createElement("td");
          box3.setAttribute("colspan","1");
          box3.width="150";
          let i = document.createElement("input");
          i.setAttribute("name",s_id + "_"+  q_id  + "_" + e_id + "_func");
          box3.append(i);  
          box3.append(i);  
          row.append(box3);
        
          
     
     }
     
     //prints constraint check grade
     if( questionIndex == 3 + numofCase + 1)
     {
        if(testcases == 0)
        {
            questionIndex = questionIndex + 1;
            displayConstr = false;
            return;
            
        }
        
        
          
        
     
     }
     
     //prints constraint grade
     if( questionIndex == 3 + numofCase + 2 && displayConstr == true)
     {
        let row = document.createElement("tr");                     
        table.append(row);
        let box = document.createElement("td");
        box.setAttribute("colspan","4");
        box.width="150";
        box.append(document.createTextNode("Constraint Grade"));
        row.append(box);
        
               
        
        
        testcases.forEach((testcase)=>{
          
          let box1 = document.createElement("td");
          box1.setAttribute("colspan","1");
          box1.width="150";
          box1.append(document.createTextNode(testcase));  
          row.append(box1);   
     
        
        });
        
        let box3 = document.createElement("td");
          box3.setAttribute("colspan","1");
          box3.width="150";
          let i = document.createElement("input");
          i.setAttribute("name",s_id + "_"+  q_id  + "_" + e_id + "_constr");
          box3.append(i);  
          row.append(box3);
     
     }
     
     //prints comments 
     if( questionIndex == 3 + numofCase + 3 )
       {
                 
          let row = document.createElement("tr");
          table.append(row);          
          let box = document.createElement("td");
          let box1 = document.createElement("td");
          let i = document.createElement("input");
          i.setAttribute("name",s_id + "_"+  q_id  + "_" + e_id + "_comment");
          box1.append(i);
          box.setAttribute("colspan","4");
          box1.setAttribute("colspan","3");
          box.width="150";
          box.append(document.createTextNode("Comments: "+ testcases));
          row.append(box);
          row.append(box1);
        
        
        }
        
 //prints final grade
        if(questionIndex == 3 + numofCase + 4)
        {
              let row = document.createElement("tr");
              table.append(row); 
              let box = document.createElement("td");
              box.setAttribute("colspan","7");
              box.width="150";
              box.append(document.createTextNode("Final Question Grade: " + testcases));
              row.append(box);
              
          }
          
          
     
     
       
       
   questionIndex = questionIndex + 1; 
   
 });   
   
   viewresult.append(table);
   let tableBr = document.createElement("br");
   let tableBr1 = document.createElement("br");
   
   viewresult.append(tableBr);
   viewresult.append(tableBr1);
   
   
   
    
                                         
    temp++;
            
             
    
   
            
 });
      let br = document.createElement("br");
      viewresult.append(br);
      let b = document.createElement("button");
      b.innerHTML = "Edit";
      b.setAttribute("class","w-100 btn btn-outline-primary");
      b.setAttribute("type","submit");
      b.setAttribute("name","submit");
      viewresult.append(b); 
      
      window.addEventListener( "load", function () {
        
        function sendData() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(viewresult);

        
        XHR.addEventListener( "load", function(event) {
        alert(event.target.responseText);
        });

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~tz6/back/teacherupdategrades.php
      //https://afsaccess4.njit.edu/~lg278/testing.php
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/updateGrades.php")
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
      viewresult.addEventListener( "submit", function ( event ) {
      event.preventDefault();
      sendData();
      
      });
  });
  


</script>

</body>
</main>
</html>
