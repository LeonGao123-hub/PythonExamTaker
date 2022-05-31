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

<h1>Select an Exam to Auto Grade</h1>


<?php
    $URL= 'https://afsaccess4.njit.edu/~tz6/back/selectExam.php';
    $ch = curl_init();
    $t_id = $_SESSION['id'];
    $req = 'teacher';
    $post = "teacherId=$t_id&request=$req"; 
		curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		curl_close($ch);
    $eArray = json_decode($result, true);
    
   

  ?>

<form type="teacherAutograde.php" method="post" id="selectExam">

</form>

<script>
      var data = <?php echo json_encode($eArray, JSON_HEX_TAG); ?>;
      
      let exam = document.getElementById("selectExam");
      
      data.forEach((item)=>{
      
       let eName = document.createElement("label");
      eName.innerHTML = "Exam ID: " + item[0] +" , " + "Exam Name: " + item[1];
      let select = document.createElement("input");
      select.setAttribute("type","radio");
      select.setAttribute("name","exam");
      select.setAttribute("value",item[0] );
      let br1 = document.createElement("br");
      
      exam.append(eName);
      exam.append(br1);
      exam.append(select);
      let br = document.createElement("br");
      exam.append(br);
      
      });
      
      let b = document.createElement("button");
      b.innerHTML = "Auto Grade";
      b.setAttribute("class","btn btn-outline-primary");
      b.setAttribute("type","submit");
      b.setAttribute("name","submit");
      exam.append(b);
      
       window.addEventListener( "load", function () {
        
        function sendData() {
        const XHR = new XMLHttpRequest();
        const FD = new FormData(exam);

        
        XHR.addEventListener( "load", function(event) {
        alert(event.target.responseText);
        });

      
      XHR.addEventListener( "error", function( event ) {
        alert('Oops! Something went wrong.');
      });

      
      XHR.open("POST", "https://afsaccess4.njit.edu/~lr22/middle_grader.php");
      
      XHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      XHR.send(FD);
    }
      exam.addEventListener( "submit", function ( event ) {
      event.preventDefault();
      sendData();
  });
});
  
      
         
</script>

</main>
</body>