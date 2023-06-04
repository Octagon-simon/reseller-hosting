<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if user is logged in
if(!isset($_SESSION) || !isset($_SESSION['user']) || empty($_SESSION['user']['id']) || empty($_SESSION['user']['loggedIn'])){
    //go to login
    header("Location: ../index.php");
}else{
    //check if user is logged in for more than 12 hours
    if(time() > strtotime("+12 hours", intval($_SESSION['user']['loggedIn']))){
        session_destroy();
        header("Location: ../index.php");
    }
}

?>