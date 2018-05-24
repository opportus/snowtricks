<?php

namespace App\Entity;

use App\Entity\Dto\DtoInterface;

/**
 * The DTO aware interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface DtoAwareInterface
{
    /**
     * Creates the entity from DTO.
     *
     * @param  App\Entity\Dto\DtoInterface $dto
     * @return App\Entity\EntityInterface
     */
    public static function createFromDto(DtoInterface $dto) : EntityInterface;

    /**
     * Updates the entity from DTO.
     *
     * @param  App\Entity\Dto\DtoInterface $dto
     * @return App\Entity\EntityInterface
     */
    public function updateFromDto(DtoInterface $dto) : EntityInterface;
}

