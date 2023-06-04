<?php

//This script resets a user's password

require('../../app.php');

use Validate\octaValidate;

$user = new users();

//Init the validation library
$validate = new octaValidate('form_reset', OV_OPTIONS);

//validation rules for validating user registration
$valRules = array(
    //user email address
    "uid" => array(
        ["R", "An email address is required"],
        ["EMAIL", "Email contains invalid characters"]
    ),
    //token
    "token" => array(
        ["R", "Invalid password reset link"],
        ["ALPHA_NUMERIC", "Invalid password reset link"]
    ),
    //expiry time
    "exp" => array(
        ["R", "Invalid password reset link"],
        ["DIGITS", "Invalid password reset link"]
    ),
    //new password
    "pass" => array(
        ["R", "Your password is required"],
        ["MINLENGTH", "8", "Your password must have a minimum of 8 characters"]
    ),
    //confirm password
    "con_pass" => array(
        ["R", "Your password is required"],
        ["EQUALTO", "pass", "Both passwords do not match"]
    ),
);

//handle user signup
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    try {
        //validate the form and check if validation is successful
        if ($validate->validateFields($valRules, $_POST)) {
            $user->user_email = $_POST['uid'];
            //check if user exists
            if ($user->user_exists()) {
                //assign details
                $user_details = $user->get_user()[0];
                //new password
                $user->user_password = $_POST['pass'];
                //user id
                $user->user_id = $user_details['id'];

                //check if time is valid
                if(time() > intval($_POST['exp'])){
                    doReturn(400, false, [
                        "message" => "The password reset link is invalid"
                    ]);
                }
                //check if token is valid
                if(hash('sha256', $user_details['secret']) !== $_POST['token']){
                    doReturn(400, false, [
                        "message" => "The password reset link is invalid"
                    ]);
                }
                
                //update record and check if it was successful
                if(!$user->update_password()){
                    //record did not update
                    doReturn(400, false, [
                        "message" => "Your password was not updated. Please try again later"
                    ]);
                }

                //else proceed and send mail
                $body = '<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Password Reset</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 14px;
                            line-height: 1.4;
                            color: #333333;
                        }
                        h2 {
                            color: #333333;
                            font-size: 20px;
                            margin-bottom: 20px;
                        }
                        p {
                            margin-bottom: 10px;
                        }
                        a {
                            color: #ffffff;
                            background-color: #007bff;
                            border-radius: 4px;
                            padding: 10px 15px;
                            text-decoration: none;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            border: 1px solid #cccccc;
                            border-radius: 4px;
                            background-color: #ffffff;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Hi {NAME},</h2>
                        <p>Your password reset has been completed successfully!</p>
                        <p>If you still cannot get access to your account, please try again after a few hours or contact support.</p>
                        <p>Please click on the link below to login to your account.</p>
                        <p><a href="{LINK}">Login</a></p>
                        <p>Best regards,</p>
                        <p>Vistacool Team</p>
                    </div>
                </body>
                </html>
                ';
                //send email to user after replacing the placeholders
                // sendMail($user_details['email'], 'Vistacool User', 'Password Reset Completed', 
                // doDynamicEmail([
                //     "NAME" => $user_details['name'],
                //     "LINK" => ORIGIN . '/index.php'
                // ], $body));
                //return data
                doReturn(200, true, [
                    "message" => "Password reset was successful"
                ]);
            } else {
                doReturn(401, false, [
                    "message" => "Account does not exist"
                ]);
            }
        } else {
            doReturn(400, false, [
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
?>