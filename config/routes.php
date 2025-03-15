<?php

use App\Controller\ProductController;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('POST', '/products', [ProductController::class, 'getByFilter']);
    $r->addRoute('GET', '/product/{id:\d+}', [ProductController::class, 'get']);
    $r->addRoute(['POST', 'PUT'], '/product', [ProductController::class, 'save']);
    $r->addRoute('DELETE', '/product/{id:\d+}', [ProductController::class, 'delete']);
};
