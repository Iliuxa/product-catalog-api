<?php

namespace App\Service;

use App\Dto\DtoInterface;
use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;

class BasicService
{
    public function __construct(
        protected EntityManager $entityManager,
        protected Logger        $logger
    )
    {
    }

    /**
     * @param AbstractEntity[] $entities
     * @return DtoInterface[]
     */
    public function toItemDto(array $entities): array
    {
        $dtos = [];
        foreach ($entities as $entity) {
            $dtos[] = $entity->toDto();
        }
        return $dtos;
    }
}