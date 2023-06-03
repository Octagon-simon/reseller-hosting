<?php

require '../app/app.php';


$user->user_id = 2;



print_r($user->get_users());

echo $user->get_users()[0]['email'];