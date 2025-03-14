<?php

namespace App\Controller;


use App\Service\ProductService;

class ProductController
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    public function get(array $request)
    {

        echo json_encode(["message" => "Добро пожаловать в API"]);
    }
}