<?php

namespace App\Entity;

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
     * Gets the ID.
     *
     * @return null|int
     */
    public function getId() : ?int;

    /**
     * Gets the creation datetime.
     *
     * @return \DateTimeInterface
     */
    public function getCreatedAt() : \DateTimeInterface;
}

