<?php

//This script sends a password reset link to a user

require('../../app.php');

use Validate\octaValidate;

$user = new users();

//Init the validation library
$validate = new octaValidate('form_send_link', OV_OPTIONS);

//validation rules for validating user registration
$valRules = array(
    "email" => array(
        ["R", "Your email is required"],
        ["EMAIL", "Your email contains invalid characters"]
    )
);

//handle user signup
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    try {
        //validate the form and check if validation is successful
        if ($validate->validateFields($valRules, $_POST)) {
            $user->user_email = $_POST['email'];

            //check if user exists
            if ($user->user_exists()) {
                $user_details = $user->get_user()[0];

                //create a reset token by hashing the user's current password using sha256 algo
                $hash = hash('sha256', $user_details['secret']);
                $time = strtotime("+10 minutes");
                $query = "?token=".$hash."&uid=".base64_encode($user_details['email'])."&exp=".$time;
                //send mail
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
                        <p>We received a request to reset your password.</p>
                        <p>We understand that mistakes do happen and we are here to assist you in getting back into your account.</p>
                        <p>Please click on the link below to reset your password or you can ignore it if you did not initiate this request.</p>
                        <p><a href="{LINK}">Reset Password</a></p>
                        <p>Best regards,</p>
                        <p>Vistacool Team</p>
                    </div>
                </body>
                </html>
                ';
                //send email to user after replacing the placeholders
                // sendMail($user_details['email'], 'Vistacool User', 'Reset Your Password', 
                // doDynamicEmail([
                //     "NAME" => $user_details['name'],
                //     "LINK" => ORIGIN . 'forgot-password.php' . $query
                // ], $body));
                //return data
                doReturn(200, true, [
                    "link" => $query,
                    "message" => "Please check your inbox for a password reset link"
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