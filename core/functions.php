<?php

declare(strict_types=1);

// error_reporting(0);

require 'env.php';
//import database class
require 'pdo.php';
//octavalidate - server-side validation library
require('octaValidate-PHP_v2.1/src/Validate.php');

use Validate\octaValidate;

//instantiate class
$db = new DatabaseClass();

/**
 * 
 * This function generates a USERID for a customer
 * 
 */
function generateUserId($email = ""){
    //check if email is provided
    if(trim($email) === "") return;
    //return md5 hash of the email address and timestamp
    return md5(time().$email);  
}
?>