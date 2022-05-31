<?php
session_start();
if( $_SESSION['role'] != "student" || isset($_POST['logOut']) ){
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
    <link href="style_exam.css" rel="stylesheet">
  
</head>
<body class="text-center">
<main class="center">

<div class="split-left">

<form method="post">
<button class="btn btn-outline-dark" name="logOut">logout</button>
</form>

<br>
<a href="https://afsaccess4.njit.edu/~lg278/studentlanding.php">
<button class="btn btn-outline-dark">back to landing page</button>
</a>
    <h1>NJIT CS100 Exam</h1>

<form id="prev" method="post">

</form>
    
<form id="examPage" method="post">
<button class="btn btn-outline-dark" type="submit">Submit Question</button>
</form>





 
</div>
     
<?php

    $URL = 'https://afsaccess4.njit.edu/~tz6/back/examLegend.php';   
    $test_id = $_POST["exam"];    
    $s_id = $_SESSION['id']; 
		$post="studentId=$s_id&examId=$test_id";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
		curl_close($ch);
    $qbArray = json_decode($result, true);

    
?>

<div class = "split-right">
<form method="post" id="legend">
<br>
</form>

<form id='submitExam' method='post'>
<br>
<button class="btn btn-outline-dark">Submit Exam</button>
</form>
</div>

<script>
      var data = <?php echo json_encode($qbArray, JSON_HEX_TAG); ?>;
      var studentId = <?php echo json_encode($s_id, JSON_HEX_TAG); ?>;
      var examId = <?php echo json_encode($test_id, JSON_HEX_TAG); ?>;
      
      
      let legendBox = document.getElementById("legend");
      let next = document.getElementById("next");
      let prev = document.getElementById("prev");
      let exam = document.getElementById("examPage");
      let submitExam = document.getElementById("submitExam");
      var count = 1;


  
//---------------------------------------------------------------------

        window.addEventListener( "load", function () {
        
        function submitE() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(submitExam);

       XHR.addEventListener( "load", function(event) {
        alert(event.target.responseText);
        }); 
      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~lg278/testing.php
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/examSubmit.php");
      
      XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      XHR.send("studentId="+ studentId + "&examId=" + examId);
    }
    
    submitExam.addEventListener( "submit", function ( event ) {
    event.preventDefault();    
    submitE();
    window.location.href = "https://afsaccess4.njit.edu/~lg278/studentlanding.php";
  });
});     
     
//---------------------------------------------------------------------            
     // setting the examid and sid 
      let id1 = document.createElement("input");
      id1.setAttribute("name","studentId");
      id1.setAttribute("type","hidden");
      id1.setAttribute("value",studentId);
      
      let id2 = document.createElement("input");
      id2.setAttribute("name","examId");
      id2.setAttribute("type","hidden");
      id2.setAttribute("value",examId);
            
      legendBox.append(id1);
      legendBox.append(id2);
      
      
      data.forEach((item)=>{
        
        
        let q = document.createElement("input");
        let br = document.createElement("br");
        let l = document.createElement("label");
        l.innerHTML = "Question " + count;
        
        q.setAttribute("class","btn btn-outline-primary");
        q.setAttribute("type","radio");
        q.setAttribute("name","questionId");
        q.setAttribute("value",item);
        
        legendBox.append(l); 
        legendBox.append(q);
        legendBox.append(br); 
          
        
        count++;
        
       
        legendBox.append(br); 
      
      });
      
      
      
      
 //----------------------------------------------------------
 
 
     window.addEventListener( "load", function () {
        
        function sendInit() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(legendBox);

        
        XHR.onload = function () {
        if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
        
            
            
            let examArr = JSON.parse(XHR.response);
            let index = 0;
            exam.innerHTML="";
            
            let s_id = document.createElement("input");
            s_id.setAttribute("name","studentId");
            s_id.setAttribute("type","hidden");
            s_id.setAttribute("value",studentId);
      
            let e_id = document.createElement("input");
            e_id.setAttribute("name","examId");
            e_id.setAttribute("type","hidden");
            e_id.setAttribute("value",examId);
      
            exam.append(e_id);
            exam.append(s_id);
            
            examArr.forEach((item2)=>{
            
            if(index == 0)
            {
              
              q_id = item2;
              index++;
              return;
              
            }
            if(index == 1)
            {
            
              let desc = document.createElement("label");
              let br1 = document.createElement("br");
              desc.innerHTML = item2;
              exam.append(desc);
              exam.append(br1);       
              index++;
              return;
            }
            if(index == 2)
            {
              let w = document.createElement("b");
              let br2 = document.createElement("br");
              let br3 = document.createElement("br");
              w.innerHTML = "(Worth " + item2 + " points)";
              exam.append(w);
              exam.append(br2); 
              let i = document.createElement("textarea");
              i.setAttribute("name", q_id )             
              i.setAttribute("id","t");           
              i.setAttribute("rows","10");
              i.setAttribute("cols","60");
              exam.append(i);
              exam.append(br3); 
              
              index++;
              return;
            }
            
            if(index == 3)
            {
               document.getElementById("t").value = item2;
                           
            }
            
            
            
            
            
            });
        
        
        let b = document.createElement("button");
        b.innerHTML = "Submit Question";
        b.setAttribute("class","btn btn-outline-dark");
        b.setAttribute("type","submit");
        b.setAttribute("name","submit");
        exam.append(b); 
        
        
        
        
        
        document.getElementById('t').addEventListener('keydown', function(e) {
        if (e.key == 'Tab') {
          e.preventDefault();
          var start = this.selectionStart;
          var end = this.selectionEnd;

        
          this.value = this.value.substring(0, start) +
          "\t" + this.value.substring(end);

       
        this.selectionStart =
          this.selectionEnd = start + 1;
        }
});
                           
                                 
       }     
           
    }
    
    };
      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~lg278/testing.php
      //
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/examQuestion.php");
      
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
    
   
    sendInit();
  
});
 
      
      
 //----------------------------------------------------------
      
      window.addEventListener( "load", function () {
        
        function changeQuestion() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(legendBox);

        
        XHR.onload = function () {
        if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
            
            let examArr = JSON.parse(XHR.response);
            let index = 0;
            exam.innerHTML="";
            
            let s_id = document.createElement("input");
            s_id.setAttribute("name","studentId");
            s_id.setAttribute("type","hidden");
            s_id.setAttribute("value",studentId);
      
            let e_id = document.createElement("input");
            e_id.setAttribute("name","examId");
            e_id.setAttribute("type","hidden");
            e_id.setAttribute("value",examId);
      
            exam.append(e_id);
            exam.append(s_id);
            
            examArr.forEach((item2)=>{
            
            if(index == 0)
            {
              
              q_id = item2;
              index++;
              return;
              
            }
            if(index == 1)
            {
              let desc = document.createElement("label");
              let br1 = document.createElement("br");
              desc.innerHTML = item2;
              exam.append(desc);
              exam.append(br1);       
              index++;
              return;
            }
            if(index == 2)
            {
              let w = document.createElement("b");
              let br2 = document.createElement("br");
              let br3 = document.createElement("br");
              w.innerHTML = "(Worth " + item2 + " points)";
              exam.append(w);
              exam.append(br2); 
              let i = document.createElement("textarea");  
              i.setAttribute("name",q_id)          
              i.setAttribute("id","t");           
              i.setAttribute("rows","10");
              i.setAttribute("cols","60");
              exam.append(i);
              exam.append(br3); 
              
              index++;
              return;
            }
            
            if(index == 3)
            {
               document.getElementById("t").value = item2;
                           
            }
            
           
            
            
            
            });
        
        
        let b = document.createElement("button");
        b.innerHTML = "Submit Question";
        b.setAttribute("class","btn btn-outline-dark");
        b.setAttribute("type","submit");
        b.setAttribute("name","submit");
        exam.append(b); 
        
        document.getElementById('t').addEventListener('keydown', function(e) {
        if (e.key == 'Tab') {
          e.preventDefault();
          var start = this.selectionStart;
          var end = this.selectionEnd;

       
          this.value = this.value.substring(0, start) +
          "\t" + this.value.substring(end);

        
        this.selectionStart =
          this.selectionEnd = start + 1;
        }
});
        
        
                  
                 
       }     
           
    }
    
    };

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~lg278/testing.php
      //
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/examQuestion.php");
      
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
    
    legendBox.addEventListener( "change", function ( event ) {
    event.preventDefault();
    changeQuestion();
    
     });
    

        
  
});
//---------------------------------------------------------------------------



      
       
       window.addEventListener( "load", function () {
        
        function sendQuestion() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(exam);

        
        XHR.onload = function () {
        if (XHR.readyState === XHR.DONE) {
        
        if (XHR.status === 200) {
            
            alert(XHR.response);
            
        
                                             
       }     
           
    }
    
    };

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      //https://afsaccess4.njit.edu/~lg278/testing.php
      XHR.open("POST", "https://afsaccess4.njit.edu/~tz6/back/examQuestionSubmit.php");
      
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
    
    exam.addEventListener( "submit", function ( event ) {
    event.preventDefault();
    sendQuestion();
    
  });
});
//---------------------------------------------------------


     
        
</script>
    
   

</body>
  </html>