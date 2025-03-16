<?php

namespace App\Controller;


use App\Dto\CategoryDto;
use App\Dto\DtoInterface;
use App\Dto\ProductDto;
use App\Service\ProductService;
use OpenApi\Attributes as OA;

class ProductController extends BasicController
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    #[OA\Post(
        path: "/products",
        description: "Получает список товаров по фильтру",
        summary: "Фильтрация продуктов",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/ProductDto"
            )
        ),
        tags: ["Product"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Список отфильтрованных товаров",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/ProductDto")
                )
            ),
            new OA\Response(response: 400, description: "Некорректные данные"),
            new OA\Response(response: 500, description: "Произошла ошибка, обратитесь в поддержку!")
        ]
    )]
    public function getByFilter(array $postData): array
    {
        $dto = $this->arrayToDto($postData, ProductDto::class, ['categories' => CategoryDto::class]);
        return $this->service->toItemDto($this->service->getByFilter($dto));
    }

    #[OA\Get(
        path: "/product/{id:\d+}",
        description: "Возвращает информацию о товаре по его ID",
        summary: "Получение товара по ID",
        tags: ["Product"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID товара",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Данные о товаре", content: new OA\JsonContent(ref: "#/components/schemas/ProductDto")),
            new OA\Response(response: 404, description: "Товар не найден!"),
            new OA\Response(response: 500, description: "Ошибка при получении товара!")
        ]
    )]
    public function get(array $postData, int $id): DtoInterface
    {
        return $this->service->get($id)->toDto();
    }

    #[OA\Delete(
        path: "/product/{id:\d+}",
        description: "Удаляет товар по его ID",
        summary: "Удаление товара по ID",
        tags: ["Product"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID товара",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Товар успешно удален"),
            new OA\Response(response: 404, description: "Товар не найден!"),
            new OA\Response(response: 500, description: "Ошибка при удалении товара!")
        ]
    )]
    public function delete(array $postData, int $id): void
    {
        $this->service->delete($id);
    }

    #[OA\Post(
        path: "/product",
        description: "Создает новый товар",
        summary: "Создание нового товара",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/ProductDto")),
        tags: ["Product"],
        responses: [
            new OA\Response(response: 200, description: "Товар успешно создан"),
            new OA\Response(response: 400, description: "Некорректные данные"),
            new OA\Response(response: 404, description: "Категория не найдена!"),
            new OA\Response(response: 500, description: "Не корректный ИНН! / Ошибка при сохранении товара!")
        ]
    )]
    #[OA\Put(
        path: "/product",
        description: "Обновляет данные о товаре",
        summary: "Обновление существующего товара",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/ProductDto")),
        tags: ["Product"],
        responses: [
            new OA\Response(response: 200, description: "Товар успешно обновлен"),
            new OA\Response(response: 400, description: "Некорректные данные"),
            new OA\Response(response: 404, description: "Категория не найдена!"),
            new OA\Response(response: 500, description: "Не корректный ИНН! / Ошибка при сохранении товара!")
        ]
    )]
    public function save(array $postData): void
    {
        $dto = $this->arrayToDto($postData, ProductDto::class, ['categories' => CategoryDto::class]);
        $this->service->save($dto);
    }
}