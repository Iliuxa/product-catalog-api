<?php

use App\Exception\Notice;

set_exception_handler(function (Throwable $exception) {

    http_response_code($exception->getCode());
    $errorMessage = $exception instanceof Notice ? $exception->getMessage() : "Произошла ошибка, обратитесь в поддержку!";

    echo json_encode([
        "success" => false,
        "message" => $errorMessage
    ]);
});
