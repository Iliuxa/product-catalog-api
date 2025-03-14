<?php

use App\Controller\ProductController;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('POST', '/products', [ProductController::class, 'get']);
    $r->addRoute('GET', '/product/{id:\d+}', [ProductController::class, 'getUser']);
    $r->addRoute('POST', '/product', [ProductController::class, 'create']);
    $r->addRoute('PUT', '/product/{id:\d+}', [ProductController::class, 'update']);
    $r->addRoute('DELETE', '/product/{id:\d+}', [ProductController::class, 'delete']);
};
