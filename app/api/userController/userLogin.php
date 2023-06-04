<?php

require('../../app.php');

use Validate\octaValidate;

$user = new users();

//Init the validation library
$validate = new octaValidate('form_login', [
    "strictMode" => true
]);

//validation rules for validating user registration
$valRules = array(
    "email" => array(
        ["R", "Your email is required"],
        ["EMAIL", "Your email contains invalid characters"]
    ),
    "pass" => array(
        ["R", "Your password is required"],
        ["MINLENGTH", "8", "Your password must have a minimum of 8 characters"]
    )
);

//handle user signup
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    try {
        //validate the form and check if validation is successful
        if ($validate->validateFields($valRules, $_POST)) {
            $user->user_email = $_POST['email'];
            $user->user_password = $_POST['pass'];

            //check if user exists
            if ($user->user_exists()) {
                //retrieve user
                $user_details = $user->get_user()[0];
                //verify password
                if (password_verify($user->user_password, $user_details['secret'])) {
                    //create session
                    $_SESSION['user'] = [
                        "id" => $user_details['id'],
                        "loggedIn" => time()
                    ];
                    //return data
                    doReturn(200, true, [
                        "message" => "Login Successful"
                    ]);
                }else{
                    doReturn(401, false, [
                        "message" => "Invalid Email or password"
                    ]);
                }
            } else {
                doReturn(401, false, [
                    "message" => "Invalid Email or password"
                ]);
            }
        } else {
            doReturn(401, false, [
                "message" => "Form validation failed",
                "formError" => true,
                "formErrors" => $validate->getErrors()
            ]);
        }
    } catch (Exception $e) {
        error_log($e);
        doReturn(500, false, [
            "message" => "Internal server error"
        ]);
    }
}
