<?php

namespace App\Entity;

use App\Attribute\DtoObject;
use App\Dto\DtoInterface;
use App\Exception\ApiException;
use Doctrine\Common\Collections\Collection;
use ReflectionClass;

abstract class AbstractEntity
{
    public function toDto(): DtoInterface
    {
        $dtoClass = $this->getDtoObject();
        $dto = new $dtoClass;
        foreach ($dto as $property => &$value) {
            $method = 'get' . ucfirst($property);
            if (method_exists($this, $method)) {
                $val = $this->$method();
                if ($val instanceof Collection) {
                    foreach ($val->toArray() as $item) {
                        $value[] = $item->toDto();
                    }
                } else {
                    $value = $val;
                }
            }
        }
        return $dto;
    }

    private function getDtoObject(): string
    {
        $reflection = new ReflectionClass(static::class);
        $attributes = $reflection->getAttributes(DtoObject::class);

        if (empty($attributes)) {
            throw new ApiException('DtoAttribute not found');
        }

        $attribute = $attributes[0]->newInstance();
        return $attribute->dtoClass;
    }
}