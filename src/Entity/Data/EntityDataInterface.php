<?php

namespace App\Entity\Data;

use App\Entity\EntityInterface;

/**
 * The entity data interface...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface EntityDataInterface
{
    /**
     * Creates from the entity.
     *
     * @param  App\Entity\EntityInterface $entity
     * @return App\Entity\Data\EntityDataInterface
     */
    public static function createFromEntity(EntityInterface $entity) : EntityDataInterface;

    /**
     * Gets the ID.
     *
     * @return string
     */
    public function getId() : ?string;

    /**
     * Gets the creation datetime.
     *
     * @return \DateTimeInterface
     */
    public function getCreatedAt() : ?\DateTimeInterface;
}

