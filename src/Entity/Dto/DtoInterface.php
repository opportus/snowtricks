<?php

namespace App\Entity\Dto;

use App\Entity\EntityInterface;

/**
 * The DTO interface...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface DtoInterface
{
    /**
     * Creates the DTO from the entity.
     *
     * @param  App\Entity\EntityInterface $entity
     * @return App\Entity\Dto\DtoInterface
     */
    public static function createFromEntity(EntityInterface $entity) : DtoInterface;
}

