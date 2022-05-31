<?php
session_start();
if( $_SESSION['role'] != "student" || isset($_POST['logOut']) ){
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
<main class="form-signin">

<form method="post">
<button class="btn btn-outline-dark" name="logOut">logout</button>
</form>

<h1 >Student Homepage</h1>
<?php

echo "Welcome ".$_SESSION['fName']." ".$_SESSION['lName']; 

?>

	<div class="btn-group" role="group" aria-label="Basic example">
      <a href="studentselectResult.php"><button class="btn btn-outline-dark">Select Result</button></a>
      <a href="studentselectExam.php"><button class="btn btn-outline-dark">Select Exam</button></a>
		</div>
   
  
   
  </main>
  </body>
	
