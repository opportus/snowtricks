<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\MappedSuperclass
 */
abstract class Entity
{
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="string")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Uuid()
     */
    protected $id;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotNull()
     * @Assert\Type(type="object")
     * @Assert\DateTime()
     */
    protected $createdAt;

    /**
     * Gets the ID.
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Gets the creation datetime.
     *
     * @return \DateTime
     */
    public function getCreatedAt() : \DateTime
    {
        return clone $this->createdAt;
    }

    /**
     * Generates a V4 UUID.
     *
     * @return string
     */
    protected function generateId() : string
    {
        $random = random_bytes(16);

        assert(strlen($random) == 16);

        $random[6] = chr(ord($random[6]) & 0x0f | 0x40); // Sets version to 0100
        $random[8] = chr(ord($random[8]) & 0x3f | 0x80); // Sets bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($random), 4));
    }
}
