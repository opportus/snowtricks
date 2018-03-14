<?php

namespace App\Entity;

use App\Entity\Data\EntityDataInterface;

/**
 * The entity interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface EntityInterface
{
    /**
     * Creates from data.
     *
     * @param  App\Entity\Data\EntityDataInterface $data
     * @return App\Entity\EntityInterface
     * @throws \InvalidArgumentException
     */
    public static function createFromData(EntityDataInterface $data) : EntityInterface;

    /**
     * Updates from data.
     *
     * @param  App\Entity\Data\EntityDataInterface $data
     * @return App\Entity\EntityInterface
     * @throws \InvalidArgumentException
     */
    public function updateFromData(EntityDataInterface $data) : EntityInterface;

    /**
     * To data.
     *
     * @return App\Entity\Data\EntityDataInterface
     */
    public function toData() : EntityDataInterface;

    /**
     * Gets the ID.
     *
     * @return string
     */
    public function getId() : string;

    /**
     * Gets the creation datetime.
     *
     * @return \DateTimeInterface
     */
    public function getCreatedAt() : \DateTimeInterface;
}

