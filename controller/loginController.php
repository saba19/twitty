<?php

require_once __DIR__ . "/../model/DB.php";
require_once __DIR__ . "/../model/User.php";

session_start();


//if(isset($_SESSION['logged']))
//{
//        header('Location: http://localhost/blabla/tempaltes/allTweets.html');
//}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['pass'])) {
        $email = trim($_POST['email']);
        $pass = trim($_POST['pass']);

//       if (strlen($email) != null && strlen($pass) != null){
        $conn = DB::getInstance()->initConnection();
        try {
            $user = User::logIn($conn, $email, $pass);
            var_dump($user);
            if ($user != false) {
                header('Location: http://localhost/blabla/tempaltes/allTweets.html');
            }
        } catch (Exception $e) {
            header('Location: http://localhost/blabla/tempaltes/login.html');
            $errorMessage = 'email doesnt exist';
        }
    }

}










