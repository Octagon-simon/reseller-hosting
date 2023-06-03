<?php

declare(strict_types=1);

// error_reporting(0);

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

?>