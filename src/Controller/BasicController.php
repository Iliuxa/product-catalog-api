<?php

namespace App\Controller;

use App\Dto\DtoInterface;
use App\Exception\ApiException;
use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0", title: "Product catalog API")]
#[OA\Server(url: "http://localhost:80")]
class BasicController
{
    protected function arrayToDto(array $arr, string $dtoClass, array $dtoClassRecursive = []): DtoInterface
    {
        $dto = new $dtoClass();
        if (!$dto instanceof DtoInterface) {
            throw new ApiException("class $dtoClass must be instance of DtoInterface");
        }

        foreach ($dto as $fieldName => &$value) {
            if (isset($dtoClassRecursive[$fieldName], $arr[$fieldName])) {
                if (is_array($arr[$fieldName])) {
                    foreach ($arr[$fieldName] as $subValue) {
                        $value[] = $this->arrayToDto($subValue, $dtoClassRecursive[$fieldName]);
                    }
                } else {
                    $value = $this->arrayToDto($arr[$fieldName], $dtoClassRecursive[$fieldName]);
                }
            } else {
                $value = $arr[$fieldName] ?? null;
            }

        }

        return $dto;
    }
}