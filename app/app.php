<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'env.php';
require 'models/database.class.php';
require 'models/users.class.php';

//octavalidate - server-side validation library
require('octaValidate-PHP/src/Validate.php');
//require PHP mailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//funtions file
require 'functions.php';
?>