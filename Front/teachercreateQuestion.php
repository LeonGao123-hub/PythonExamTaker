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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style_qb.css" rel="stylesheet">
</head>
<body class="text-center">
<main class="content">



<div class="split left" id="createQuestion">

<form method="post">
<button class="btn btn-outline-primary" name="logOut">logout</button>
</form>

<br>
<a href="https://afsaccess4.njit.edu/~lg278/teacherlanding.php">
<button class="btn btn-outline-primary">back to landing page</button>
</a>


<button class="btn btn-outline-primary" id="add">add a testcase</button>
</a>


<button class="btn btn-outline-primary" id="del">remove extra testcases</button>
</a>

<h1>Enter Exam Questions</h1>
<h3>(Use " " for string test cases)</h3>
<h3>(Use \' \'  for single quotes)</h3>
<br><br>
<form  id= "question" method="post">
	

	
  <b>Function Name<br>
	<input type="text" name="fName"/>
	</b><br><br><br>
 
  <b>Test Case 1 
	<br><input type="text" name="T1"/>
	</b><br>
 
  <b>Test Case 1 Expected output
	<br><input type="text" name="T1O"/>
	</b><br>
 
  <b>Test Case 2 
	<br><input type="text" name="T2"/>
	</b><br>
 
  <b>Test Case 2 Expected output
	<br><input type="text" name="T2O"/>
	</b><br>
 
  <div id="testCases">
  </div>
 
 
 <br><br><br>
  <b>Description</b><br>
	<textarea name="qDescript" id="qDescript" style="width:300px"></textarea><br><br><br>
 
  <b>Difficulty level</b><br>
	<label>Easy</label>
  <input type="radio" name="dif" value=1><br>
  <label>Medium</label>
  <input type="radio" name="dif" value=2><br>
  <label>Hard</label>
  <input type="radio" name="dif" value=3>
  <br><br><br>
  
  
  <b>Category</b><br>
  <label>IF statements</label>
  <input type="radio" name="cat" value=1><br>
  <label>FOR loops</label>
  <input type="radio" name="cat" value=2><br>
  <label>WHILE loops</label>
  <input type="radio" name="cat" value=3><br>
  <label>Arrays</label>
  <input type="radio" name="cat" value=4><br>
  <label>Recursions</label>
  <input type="radio" name="cat" value=5><br>
  <label>Misc</label>
  <input type="radio" name="cat" value=9>
  <br><br><br>
  
  
  <b>Constraints</b><br>
  <label>None</label>
  <input type="radio" name="con" value=0><br>
  <label>FOR</label>
  <input type="radio" name="con" value=1><br>
  <label>WHILE</label>
  <input type="radio" name="con" value=2><br>
  <label>Recursion</label>
  <input type="radio" name="con" value=3><br>
  <br><br><br>
  
  
     
	</form> 
 </div>

 <div class="split right" id="viewQb">
 <h1>Question Bank</h1>

 <form type='createquestionandandviewQb.php' method='post' id='sort'>
 
 
  
 
 <b>Sort by Category</b><br>
  <label>IF statements</label>
  <input type="radio" name="cat" value=1><br>
  <label>FOR loops</label>
  <input type="radio" name="cat" value=2><br>
  <label>WHILE loops</label>
  <input type="radio" name="cat" value=3><br>
  <label>Arrays</label>
  <input type="radio" name="cat" value=4><br>
  <label>Recursions</label>
  <input type="radio" name="cat" value=5><br>
  <label>Misc</label>
  <input type="radio" name="cat" value=9><br>
  <label>Show all Category</label>
  <input type="radio" name="cat" value=0>
  <br><br><br>
  
  
  <b>Difficulty level</b><br>
	<label>Easy</label>
  <input type="radio" name="dif" value=1><br>
  <label>Medium</label>
  <input type="radio" name="dif" value=2><br>
  <label>Hard</label>
  <input type="radio" name="dif" value=3><br>
  <label>Show all Diffculty</label>
  <input type="radio" name="dif" value=0>
 
  <br><br><br>
  
  <label>KeyWord: </label>
  <input id="keyWord" name="keyWord">
  
  
  <br><br>
  
  
  
  </form>
  
  
  
  
<form type="qbandview.php" method="post" id="qb">
  
</form>
</div>

 <?php
 
     $id = $_SESSION['id'];
 ?>
 
 
 <script>
        
        let questionBank = document.getElementById("qb");
        let catergory = document.getElementById("sort");
        let keywordFilter = document.getElementById("keyWord");

 
  window.addEventListener( "load", function () {
        
        function sendInit() {
        const XHR = new XMLHttpRequest();
        //const FD = new FormData(init);
        
                
        XHR.onload = function () {
      if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
            
            
            let qbArray = JSON.parse(XHR.response);
            
                
        
        var count = 1;
      
      //outer loop that loops through the array of arrays
      qbArray.forEach((item)=>{
        
        let q = document.createElement("label");
        var index = 0;
        // inner loops that prints the arrays indexs
        item.forEach((item2)=>{
        // hides the id       
        if(index == 0)
          {
            q_id = item2;
            index = index + 1;
            return;
          }
        // adds extra text to diff  
        if(index == 2)
          {
            let q1 = document.createElement("b");
            let br = document.createElement("br");
            q1.innerHTML = "Difficulty: " + item2;
            index = index + 1;
            questionBank.append(q1);
            questionBank.append(br);
            return;
          }
          // adds extra text to cater
          if(index == 3)
          {
            let q1 = document.createElement("b");
            let br = document.createElement("br");
            q1.innerHTML = "Category: " + item2;
            index = index + 1;
            questionBank.append(q1);
            questionBank.append(br);
            return;
          }
          
          // constr, 
          if(index == 4)
          {
            let q1 = document.createElement("b");
            let br = document.createElement("br");
            q1.innerHTML = "Constraint: " + item2;
            index = index + 1;
            questionBank.append(q1);
            questionBank.append(br);
            return;
          }
          
          let q1 = document.createElement("label");
          let br = document.createElement("br");
          q1.innerHTML = item2;
          
          questionBank.append(q1);
          questionBank.append(br);
          
          index = index + 1;
          
          
        });
        
        
        
        let line = document.createElement("h1");
        line.innerHTML = "_____________________________________________________";
        
        
       
        questionBank.append(line);
        count++;
        
        
        });
      
      let br3 = document.createElement("br");
      questionBank.append(br3);
      questionBank.append(br3);
      questionBank.append(br3);
      questionBank.append(br3); 
       

                
        
            
            
              
          }
      }
    };
    
    XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~lg278/testing.php
      //https://afsaccess4.njit.edu/~tz6/back/addexam.php
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/getqb.php");
      
      XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      XHR.send('teacher');
      
    }
    
      
      sendInit();
  
});
 
//-----------------------------------------------------------------------------------------------------

    let filter = document.getElementById("sort");
   
    window.addEventListener( "load", function () {
        
        function sendFilter() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(filter);

        
        XHR.onload = function () {
        if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
        
                  
            questionBank.innerHTML="";
            let examArr = JSON.parse(XHR.response);            
                     
            examArr.forEach((item)=>{
            
                var index = 0;         
                item.forEach((innerItem)=>{
                    
                if(index == 0)
                {
                    q_id = innerItem;
                    index = index + 1;
                    return;
                }
                // adds extra text to diff  
                if(index == 2)
                  {
                    let q1 = document.createElement("b");
                    let br = document.createElement("br");
                    q1.innerHTML = "Difficulty: " + innerItem;
                    index = index + 1;
                    questionBank.append(q1);
                    questionBank.append(br);
                    return;
                  }
                  // adds extra text to cater
                  if(index == 3)
                  {
                    let q1 = document.createElement("b");
                    let br = document.createElement("br");
                    q1.innerHTML = "Category: " + innerItem;
                    index = index + 1;
                    questionBank.append(q1);
                    questionBank.append(br);
                    return;
                  }
                  
                  // constr, 
                 if(index == 4)
                 {
                   let q1 = document.createElement("b");
                   let br = document.createElement("br");
                   q1.innerHTML = "Constraint: " + innerItem;
                   index = index + 1;
                   questionBank.append(q1);
                   questionBank.append(br);
                  return;
                }
                  
                  let q1 = document.createElement("label");
                  let br = document.createElement("br");
                  q1.innerHTML = innerItem;
                  
                  questionBank.append(q1);
                  questionBank.append(br);
                  
                  index = index + 1;
                  
                  });
                  
                  
        
        
                let br4 = document.createElement("br");
                let line = document.createElement("h1");
                line.innerHTML = "_____________________________________________________";
       
        
       
        
        
              questionBank.append(br4);
              questionBank.append(br4);               
              questionBank.append(line);
       
        
        
        });            
          }
      }
    };

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });


      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/getqbFiltered.php");
      
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
    
    filter.addEventListener( "change", function ( event ) {
      event.preventDefault();
      sendFilter();
  });
  
  keywordFilter.addEventListener( "change", function ( event ) {
      event.preventDefault();
      sendFilter();
  });
}); 
 
 
 
 
 
//------------------------------------------------------------------------------------------------------   
   
   
   let teacher_id = <?php echo json_encode($id, JSON_HEX_TAG); ?>;
   let addTestcase = document.getElementById("add");
   let delTestcase = document.getElementById("del");
   let field = document.getElementById("testCases");
   let question = document.getElementById("question");
   let cnt = 2;
   
   addTestcase.addEventListener( "click", function ( event ) {
      
      event.preventDefault();
      cnt++;
      let testcase = document.createElement('input');
      testcase.setAttribute("name","T"+cnt);
      let testcaseLabel = document.createElement('b');
      testcaseLabel.innerHTML = "Test Case " + cnt;
      let testcaseOutput = document.createElement('input');
      testcaseOutput.setAttribute("name","T"+cnt+"O");
      let testcaseOutputLabel = document.createElement('b');
      testcaseOutputLabel.innerHTML = "TestCase " + cnt + " Expected Output";
      
      let br = document.createElement("br");
      let br2 = document.createElement("br");
      let br3 = document.createElement("br");
      let br4 = document.createElement("br");
      
      field.appendChild(testcaseLabel);
      field.appendChild(br);
     
      field.appendChild(testcase);
      field.appendChild(br2);
      
      field.appendChild(testcaseOutputLabel);
      field.appendChild(br3);
      
      field.appendChild(testcaseOutput);
      field.appendChild(br4);
      
           
  });
  
  delTestcase.addEventListener( "click", function ( event ) {
  
  cnt = 2;
  
  field.innerHTML="";
  
  });
  
      let t_id = document.createElement("input");
      t_id.setAttribute("name","teacherId");
      t_id.setAttribute("value",teacher_id);
      t_id.setAttribute("type","hidden");
      question.append(t_id);
    
  
      let b = document.createElement("button");
      b.innerHTML = "Submit Question";
      b.setAttribute("class","btn btn-outline-primary");
      b.setAttribute("type","submit");
      b.setAttribute("name","submit");
      b.setAttribute("id","subQ");
      question.append(b); 
      
      
    
    
  
  window.addEventListener( "load", function () {
        
        function sendData() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(question);

        
        XHR.addEventListener( "load", function(event) {
        alert(event.target.responseText);
        });

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~tz6/back/addqb.php
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/addqb.php");
      
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
    question.addEventListener( "submit", function ( event ) {
      event.preventDefault();
      sendData();
  });
});
  
  
  
      
                        
   </script>   
  </main>
 </body>
 </html>