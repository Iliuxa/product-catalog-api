<?php

namespace App\Exception;

use Exception;

/** Исключение, текст которого необходимо отправить пользователю */
class Notice extends Exception
{
    public function __construct(
        $message = "",
        $code = 500,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}