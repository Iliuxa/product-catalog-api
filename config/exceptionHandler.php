<?php

use App\Exception\Notice;

set_exception_handler(function (Throwable $exception) {

    //TODO logger
    var_dump($exception->getMessage()); die;

    http_response_code($exception->getCode());
    $errorMessage = $exception instanceof Notice ? $exception->getMessage() : "Произошла ошибка, обратитесь в поддержку!";

    echo json_encode([
        "success" => false,
        "message" => $errorMessage
    ]);
});
