
<?php
    $back = 'https://afsaccess4.njit.edu/~tz6/back/login.php';
    $username = $_POST['user_name'];
    $password = $_POST['pass_word']; 

    $post = "user_name=$username&pass_word=$password";

    $chback = curl_init();    
    curl_setopt($chback, CURLOPT_URL, $back);
    curl_setopt($chback, CURLOPT_RETURNTRANSFER, 1);    
    curl_setopt($chback, CURLOPT_POSTFIELDS, $post);
    $backreturn = curl_exec($chback);
    
    if ($backreturn == FALSE){
        echo "CURL ERRROR".curl_error($chback);
    }
    curl_close($chback);
    echo $backreturn;
?>