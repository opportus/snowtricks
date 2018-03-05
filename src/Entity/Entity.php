<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\MappedSuperclass
 */
abstract class Entity implements EntityInterface
{
    /**
     * @var null|int $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @Assert\Type(type="integer")
     * @Assert\Range(min=1)
     */
    protected $id;

    /**
     * @var \DateTimeInterface $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotNull()
     * @Assert\Type(type="object")
     * @Assert\DateTime()
     */
    protected $createdAt;

    /**
     * Constructs the entity.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt() : \DateTimeInterface
    {
        return $this->createdAt;
    }
}

