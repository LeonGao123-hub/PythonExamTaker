<?php
session_start();
if( $_SESSION['role'] != "teacher" || isset($_POST['logOut']) ){
    session_destroy();
    header("Location: https://afsaccess4.njit.edu/~lg278/index.php");
    
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Exam Page</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style_qb.css" rel="stylesheet">
  
</head>
<body class="text-center">
<main class="content">
   

 

<div class="split left">

<form method="post">
<button class="btn btn-outline-primary" name="logOut">logout</button>
</form>

<br>
<a href="https://afsaccess4.njit.edu/~lg278/teacherlanding.php">
<button class="btn btn-outline-primary">back to landing page</button>
</a>

<h1>Create Exam</h1>

<form method="post" id="clearExam">

<button type="submit" class="btn btn-outline-primary">Clear Exam</button>
<br><br>

</form>
<form method="post" id="examPage">

<button type="submit" class="btn btn-outline-primary">Submit Exam</button>
<br><br>

<b>Enter Exam Name: 
	<input type="text" name="eName"/>
	</b><br><br><br>

</form>



</div>

<div class="split right">



<h1>Question Bank</h1>
  
 <form type='qbandcreateExam.php' method='post' id='sort'>
 
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
  <input id = 'keyWord' name="keyWord">
  <br><br>
  
  
  </form>

<form type="qbandview.php" method="post" id="qb">
  
</form>
</div>

<?php
  $t_id = $_SESSION['id'];
?>


<script>
   
   let questionBank = document.getElementById("qb");
   let catergory = document.getElementById("sort");
   let exam = document.getElementById("examPage");
   let clear = document.getElementById("clearExam");
   let keywordFilter = document.getElementById("keyWord");
   
   var t_id = <?php echo json_encode($t_id, JSON_HEX_TAG); ?>;
   
   
   
   
   
   clear.addEventListener( "click", function( event ) {
        exam.innerHTML = "";
      });
   
   
   
   
   
//--------------------------------------------------------   
    window.addEventListener( "load", function () {
        
        function sendInit() {
        const XHR = new XMLHttpRequest();
        //const FD = new FormData(init);
        
                
        XHR.onload = function () {
      if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
            
            
            let examArr = JSON.parse(XHR.response);
            
            let b2 = document.createElement("button");
            let brr = document.createElement("br");
            b2.innerHTML = "pick question";
            b2.setAttribute("class","btn btn-outline-primary");
            b2.setAttribute("type","submit");
            questionBank.append(b2);
            questionBank.append(brr);  
            
            
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
                  // constr, hides it
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
                  
                  
                  let checkBox = document.createElement("input");
                  checkBox.setAttribute("type","checkbox");
                  checkBox.setAttribute("value","selected");
                  checkBox.setAttribute("name",q_id);
                  questionBank.append(checkBox);
        
        
                 let br4 = document.createElement("br");
                 let line = document.createElement("h1");
                 line.innerHTML = "_____________________________________________________";
       
        
       
        
        
              questionBank.append(br4);
              questionBank.append(br4);               
              questionBank.append(line);
       
        
        
        });
        
       

                
        
            
            
           let b1 = document.createElement("button");
           b1.innerHTML = "pick question";
           b1.setAttribute("class","btn btn-outline-primary");
           b1.setAttribute("type","submit");
           questionBank.append(b1);  
            
            
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


//-----------------------------------------------------


   let filter = document.getElementById("sort");
   
    window.addEventListener( "load", function () {
        
        function sendFilter() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(filter);

        
        XHR.onload = function () {
        if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
        
                  
            questionBank.innerHTML="";
            
            let b2 = document.createElement("button");
            
            b2.innerHTML = "pick question";
            b2.setAttribute("class","btn btn-outline-primary");
            b2.setAttribute("type","submit");
            questionBank.append(b2);
            let brr1 = document.createElement("br");
            questionBank.append(brr1);  
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
                  
                  
                  
                       
            
            
                  checkBox = document.createElement("input");
                  checkBox.setAttribute("type","checkbox");
                  checkBox.setAttribute("value","selected");
                  checkBox.setAttribute("name",q_id);
                  questionBank.append(checkBox);
        
        
                let br4 = document.createElement("br");
                let line = document.createElement("h1");
                line.innerHTML = "_____________________________________________________";
       
        
       
        
        
              questionBank.append(br4);
              questionBank.append(br4);               
              questionBank.append(line);
       
        
        
        });
        
       

                
                               
           let b1 = document.createElement("button");
           b1.innerHTML = "pick question";
           b1.setAttribute("class","btn btn-outline-primary");
           b1.setAttribute("type","submit");
           questionBank.append(b1); 
            
            
          }
      }
    };

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~lg278/testing.php
      //https://afsaccess4.njit.edu/~tz6/back/addexam.php
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
   

//----------------------------------------------------------------
  
        
       
        
        window.addEventListener( "load", function () {
        
          function sendData() {
          const XHR = new XMLHttpRequest();
          const FD = new FormData(questionBank);

        
          
        
      XHR.onload = function () {
      if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
            
            
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
                    exam.append(q1);
                    exam.append(br);
                    return;
                  }
                  // adds extra text to cater
                  if(index == 3)
                  {
                    let q1 = document.createElement("b");
                    let br = document.createElement("br");
                    q1.innerHTML = "Category: " + innerItem;
                    index = index + 1;
                    exam.append(q1);
                    exam.append(br);
                    return;
                  }
                  if(index == 4)
                  {
                    let q1 = document.createElement("b");
                    let br = document.createElement("br");
                    q1.innerHTML = "Constraint: " + innerItem;
                    index = index + 1;
                    exam.append(q1);
                    exam.append(br);
                    return;
                  }
                  
                  let q1 = document.createElement("label");
                  let br = document.createElement("br");
                  q1.innerHTML = innerItem;
                  
                  exam.append(q1);
                  exam.append(br);
                  
                  index = index + 1;
                  
                  });
                  
                                    
                  let br4 = document.createElement("br");
                  let points = document.createElement("input");
                  let pLabel = document.createElement("label");
                  let line = document.createElement("h1");
                  points.setAttribute("name",q_id);
                  pLabel.innerHTML = "Points:";
                  
                  let teacher = document.createElement("input");
                  teacher.setAttribute("name","teacherId");
                  teacher.setAttribute("type","hidden");
                  teacher.setAttribute("value",t_id);
        
                  exam.append(br4);
                  exam.append(br4);
                  exam.append(pLabel);
                  exam.append(points);
                  exam.append(line);
                  exam.append(teacher);
        
            });
            
           
            
            
          }
      }
    };

      
        XHR.addEventListener( "error", function( event ) {
          alert('Oops! Something went wrong.');
        });

        //https://afsaccess4.njit.edu/~tz6/back/exambuilder.php
        XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/examBuilder.php");
      
        XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        XHR.send(FD);
    }
    
      questionBank.addEventListener( "submit", function ( event ) {
      event.preventDefault();
      sendData();
  });
});
//--------------------------------------------------------------
         
        window.addEventListener( "load", function () {
        
        function sendExam() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(exam);

        
        XHR.addEventListener( "load", function(event) {
        alert(event.target.responseText);
        });

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~lg278/testing.php
      //https://afsaccess4.njit.edu/~tz6/back/addexam.php
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/addExam.php");
      
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
    exam.addEventListener( "submit", function ( event ) {
      event.preventDefault();
      sendExam();
  });
}); 


           
        
        
   
        
   
   
  
   
                    
    </script>
</main>
</body>
  </html>