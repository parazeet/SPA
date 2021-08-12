<?php

namespace App\Helpers;

class Error
{
    public static function show()
    {
        http_response_code(404);
        require_once __DIR__ . "/../Views/404.php";
        die();
    }
}