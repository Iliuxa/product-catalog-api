<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DtoObject
{
    public function __construct(
        public readonly string $dtoClass,
    )
    {
    }
}