<?php

require '../app/app.php';

$user->user_email = 'test12@gmsil.com';
$user->user_password = '1234';
$user->created_at = time();

if ($user->register_user() === 'registered'){
    header('location: ../dashboard');
}