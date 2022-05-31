<?php
   ob_start();
   session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet"> 
  </head>
  <body class="text-center">
    <main class="form-signin">
    <div id="LoginForm">
    <form type="index.php" method="post">

    <h1 class="h3 mb-3 fw-normal">Sign In</h1>

    <div class="form-floating">
      <input type="text" name="Uname" id ="username" class="form-control top"   required autofocus>
      <label for="floatingInput">Username</label>
    </div>

    <div class="form-floating">
      <input type="password" name="Pword" id ="password" class="form-control middle"  required>
      <label for="floatingPassword">Password</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" name="submit" type="submit">Login</button>

  </form>
  </div>
  
  <div id="response">
  </div>
  
  </body>
  </html>

<?php
	
  $URL = 'https://afsaccess4.njit.edu/~tz6/back/login.php';

	  $u = $_POST['Uname'];
    $p = $_POST['Pword'];
		$post = "user_name=$u&pass_word=$p";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		curl_close($ch);
    
           
            if (isset($_POST['submit']))
            {
               
                 $finalresult = json_decode($result,true);
                 
				
                 if ($finalresult['resp'] == "student")
                 {
                    //$_SESSION['valid'] = true;
                    $_SESSION['timeout'] = time();
                    $_SESSION['role'] = "student";
                    $_SESSION['fName'] = $finalresult['nameF'];
                    $_SESSION['lName'] = $finalresult['nameL'];
                    $_SESSION['id'] = $finalresult['userId'];
                    header('Location:https://afsaccess4.njit.edu/~lg278/studentlanding.php');
                 }
                 else if($finalresult['resp'] == "teacher")
                 {
                    //$_SESSION['valid'] = true;
                    $_SESSION['timeout'] = time();
                    $_SESSION['role'] = "teacher";
                    $_SESSION['fName'] = $finalresult['nameF'];
                    $_SESSION['lName'] = $finalresult['nameL'];
                    $_SESSION['id'] = $finalresult['userId'];
                    
                    header('Location:https://afsaccess4.njit.edu/~lg278/teacherlanding.php');
                 }   
                  
                 else 
                 {
                    echo "bad credentials, please try again";
                 }
            }
            

?>
  
