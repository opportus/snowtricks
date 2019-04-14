<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * The trick group.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickGroupRepository")
 * @ORM\Table(name="trick_group")
 */
class TrickGroup extends Entity
{
    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $slug;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $title;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $description;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Trick", mappedBy="group", cascade={"persist"})
     * @Assert\Valid()
     */
    protected $tricks;

    /**
     * Constructs the trick group.
     *
     * @param string $title
     * @param string $description
     */
    public function __construct(
        string $title,
        string $description
    )
    {
        $this->id          = $this->generateId();
        $this->createdAt   = new \DateTime();
        $this->slug        = preg_replace('/[\s]+/', '-', strtolower(trim($title)));
        $this->title       = $title;
        $this->description = $description;
        $this->tricks      = new ArrayCollection();
    }

    /**
     * Gets the slug.
     *
     * @return string
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Gets the description.
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Gets the tricks.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTricks() : Collection
    {
        return clone $this->tricks;
    }

    /**
     * Adds a trick.
     *
     * @param App\Entity\Trick $trick
     */
    public function addTrick(Trick $trick)
    {
        if ($this->tricks->contains($trick) === false) {
            $this->tricks->add($trick);
        }
    }

    /**
     * Removes a trick.
     *
     * @param App\Entity\Trick $trick
     */
    public function removeTrick(Trick $trick)
    {
        $this->tricks->removeElement($trick);
    }
}
