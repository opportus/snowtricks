<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * The trick group.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickGroupRepository", readOnly=true)
 * @ORM\Table(name="trick_group")
 */
class TrickGroup extends Entity
{
    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    private $title;

    /**
     * Constructs the trick group.
     *
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->id = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->title = $title;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
