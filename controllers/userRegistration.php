<?php

require('../app/app.php');

use Validate\octaValidate;

$user = new users();

//Init the validation library
$validate = new octaValidate('form_register', [
    "strictMode" => true
]);

//validation rules for validating user registration
$valRules = array(
    "first_name" => array(
        ["R", "Your first name is required"],
        ["NAME", "Your First name contains invalid characters"]
    ),
    "last_name" => array(
        ["R", "Your last name is required"],
        ["NAME", "Your Last name contains invalid characters"]
    ),
    "email" => array(
        ["R", "Your email is required"],
        ["EMAIL", "Your email contains invalid characters"]
    ),
    "pass" => array(
        ["R", "Your password is required"],
        ["MINLENGTH", "8", "Your password must have a minimum of 8 characters"]
    ),
    "con_pass" => array(
        ["EQUAL", "pass", "Both passwords do not match"]
    ),
);

//handle user signup
if($_SERVER['REQUEST_METHOD'] === "POST"){

    try{
        //validate the form and check if validation is successful
        if($validate->validateFields($valRules, $_POST)){
            $user->user_fname = $_POST['first_name'];
            $user->user_lname = $_POST['last_name'];
            $user->user_email = $_POST['email'];
            $user->user_password = $_POST['pass'];

            //check if user does not exist
            if(!$user->user_exists()){
                //store this information
                if($user->register_user()){
                    doReturn(200, true, [
                        "message" => "Account created successfully"
                    ]);
                }else{ 
                    doReturn(400, false, [
                        "message" => "Account creation failed"
                    ]);
                }
            }else{
                doReturn(400, false, [
                    "message" => "Account already exists. Please login or reset your password"
                ]);
            }

        }else{
            doReturn(400, false, [
                "message" => "Form validation failed",
                "error" => $validate->getErrors()
            ]);
        }
    }catch(Exception $e){
        error_log($e);
        doReturn(500, false, [
            "message" => "Internal server error"
        ]);
    }
}
