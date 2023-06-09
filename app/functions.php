<?php

declare(strict_types=1);

// error_reporting(0);
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function doReturn(int $status = 400, bool $success = false, $data = [])
{
    //Easily print out errors to the user
    $retval = array(
        "success" => $success,
        "data" => $data
    );
    http_response_code($status);
    return (print_r(json_encode($retval)) . exit());
}

function sendMail($user_email, $user_name, $subject, $body)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'mail.tailorskit.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'support@tailorskit.com'; //SMTP username
        $mail->Password = 'B}?i#~?[d-4i'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('support@tailorskit.com', 'Tailors Kit');
        $mail->addAddress($user_email, $user_name); //Add a recipient
        // $mail->addAddress('ellen@example.com'); //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('hello@tailorskit.com');
        $mail->addBCC('ugorji757@gmail.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body =  $body;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return 1;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return 0;
    }
}
//replaces email placeholders with actual values
function doDynamicEmail($replaceWith, $body)
{

    //return false if it isn't an array
    if (!is_array($replaceWith))
        return;

    //loop through
    foreach ($replaceWith as $key => $val) {
        $body = str_replace('{' . strtoupper($key) . '}', $val, $body);
    }

    return $body;
}
?>