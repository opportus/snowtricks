<?php

namespace App\Entity\Data;

use App\Entity\EntityInterface;

/**
 * The entity data...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class EntityData implements EntityDataInterface
{
    /**
     * @var null|string $id
     */
    protected $id;

    /**
     * @var null|\DateTimeInterface $createdAt
     */
    protected $createdAt;

    /**
     * {@inheritdoc}
     */
    abstract public static function createFromEntity(EntityInterface $entity) : EntityDataInterface;

    /**
     * {@inheritdoc}
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt() : ?\DateTimeInterface
    {
        return $this->createdAt;
    }
}

