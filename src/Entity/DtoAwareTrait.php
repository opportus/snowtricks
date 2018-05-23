<?php

namespace App\Entity;

use App\Entity\Dto\DtoInterface;

/**
 * The DTO aware trait...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
trait DtoAwareTrait
{
    /**
     * Creates the entity from DTO.
     *
     * @param  App\Entity\Dto\DtoInterface $dto
     * @return App\Entity\EntityInterface
     */
    public static function createFromDto(DtoInterface $dto) : EntityInterface
    {
        $dtoVars               = get_object_vars($dto);
        $reflectionMethod      = new \ReflectionMethod(__CLASS__, '__construct');
        $constructorParameters = $reflectionMethod->getParameters();

        foreach ($constructorParameters as $param) {
            $constructorArgs[$param->getPosition()] = $dtoVars[$param->getName()];
        }

        $reflectionClass = new \ReflectionClass(__CLASS__);

        return $reflectionClass->newInstanceArgs($constructorArgs);
    }

    /**
     * Updates the entity from DTO.
     *
     * @param  App\Entity\Dto\DtoInterface $dto
     * @return App\Entity\EntityInterface
     */
    public function updateFromDto(DtoInterface $dto) : EntityInterface
    {
        $vars = get_object_vars($dto);

        foreach ($vars as $var => $value) {
            $this->$var = $value;
        }

        return $this;
    }
}

