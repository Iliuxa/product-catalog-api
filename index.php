<?php

require __DIR__ . '/bootstrap.php';

use FastRoute\Dispatcher;

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

header('Content-Type: application/json');
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(["error" => "Маршрут не найден"]);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(["error" => "Метод не разрешён"]);
        break;
    case Dispatcher::FOUND:
        [$controllerClass, $method] = $routeInfo[1];
        $varsUrl = $routeInfo[2];

        $controller = $container->get($controllerClass);
        $controller->$method($_REQUEST, $varsUrl);
        break;
}
