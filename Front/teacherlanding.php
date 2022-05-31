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



<h1 >Instuctor Homepage</h1>
<?php

  echo "Welcome ".$_SESSION['fName']." ".$_SESSION['lName']; 
  
  
?>

   
<nav>
  
 
      
      <div class="btn-group" role="group" aria-label="Basic example">
      
      <a href="teachercreateQuestion.php"><button class="btn btn-outline-primary">Create Questions</button></a><br><br>
      <a href="teachercreateExam.php"><button class="btn btn-outline-primary">Create Exams</button></a><br><br>
      <a href="teacherAutograde.php"><button class="btn btn-outline-primary"> Auto Grade Exams </button></a><br><br>
      <a href="teacherselectExam.php"><button class="btn btn-outline-primary">View and Edit Student Exams</button></a><br><br>
     </div> 
    
</nav>
   
   
	</form>
</body>
