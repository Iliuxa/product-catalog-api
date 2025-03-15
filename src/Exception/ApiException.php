<?php

namespace App\Exception;

use Exception;

/** Исключение, возникающее при работе API */
class ApiException extends Exception
{
    public function __construct(
        $message = "",
        $code = 500,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}