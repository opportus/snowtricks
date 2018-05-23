<?php

namespace App\Entity\Dto;

use App\Entity\EntityInterface;

/**
 * The DTO trait...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
trait DtoTrait
{
    /**
     * Creates the DTO from the entity.
     *
     * @param  App\Entity\EntityInterface $entity
     * @return App\Entity\Dto\DtoInterface
     */
    public static function createFromEntity(EntityInterface $entity) : DtoInterface
    {
        $reflectionMethod      = new \ReflectionMethod(__CLASS__, '__construct');
        $constructorParameters = $reflectionMethod->getParameters();

        foreach ($constructorParameters as $param) {
            $getter = 'get' . ucfirst($param->getName());

            $constructorArgs[$param->getPosition()] = $entity->$getter();
        }

        $reflectionClass = new \ReflectionClass(__CLASS__);

        return $reflectionClass->newInstanceArgs($constructorArgs);
    }
}

