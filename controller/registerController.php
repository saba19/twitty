<?php
require_once __DIR__ . "/../model/DB.php";
require_once __DIR__ . "/../model/User.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['email']) && isset($_POST['pass'])) {
        $email = trim($_POST['email']);
        $pass = trim($_POST['pass']);
        if(strlen($pass) >= 4) {
            $conn = DB::getInstance()->initConnection();
            try {
                $newUser = new User;
                $newUser->setEmail($email);
                $newUser->setPass($pass);
                $result = $newUser->saveToDB($conn);
                if($result) {
                    header('Location: http://localhost/blabla/tempaltes/login.html');
                }
            } catch (Exception $e) {
                $errorMessage = 'Login exist';
            }
        } else {
            $errorMessage = 'Password too short';

        }
    }
}







//if($_SERVER['REQUEST_METHOD'] === 'POST') {
//    if(isset($_POST['email']) && isset($_POST['pass'])) {
//        $email = trim($_POST['email']);
//        $password = trim($_POST['pass']);
//
//        $conn = DB::getInstance()->initConnection();
//        var_dump($conn);
//        try {
//            $user = new User();
//            $user->setEmail($email);
//            $user->setPass($password);
//
//            $result = $user->saveToDB($conn);
//
//            var_dump($user);
//            if($result) {
//                var_dump($result);
//               echo "ok";
//            }
//        } catch (Exception $e) {
//            $errorMessage = 'Login exist';
//        }
//
//    }
//}
