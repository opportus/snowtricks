<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * The trick group...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickGroupRepository")
 * @ORM\Table(name="trick_group")
 *
 * @UniqueEntity(fields={"title"}, groups={"trick_group.edit.form"})
 */
class TrickGroup extends Entity implements TrickGroupInterface
{
    /**
     * @var null|string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $slug;

    /**
     * @var null|string $title
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"trick_group.edit.form"})
     * @Assert\Type(type="string", groups={"trick_group.edit.form"})
     * @Assert\Length(max=255, groups={"trick_group.edit.form"})
     */
    protected $title;

    /**
     * @var null|string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank(groups={"trick_group.edit.form"})
     * @Assert\Type(type="string", groups={"trick_group.edit.form"})
     * @Assert\Length(max=255, groups={"trick_group.edit.form"})
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
     * @var null|App\Entity\UserInterface $author
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $author;

    /**
     * Constructs the trick group.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tricks = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug() : ?string
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle(string $title) : TrickGroupInterface
    {
        $this->title = $title;
        $this->slug  = preg_replace('/[\s]+/', '-', strtolower(trim($this->title)));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(string $description) : TrickGroupInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTricks() : array
    {
        return $this->tricks->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function addTrick(TrickInterface $trick) : TrickGroupInterface
    {
        $trick->setGroup($this);

        if ($this->tricks->contains($trick) === false) {
            $this->tricks->add($trick);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTrick(TrickInterface $trick) : TrickGroupInterface
    {
        $trick->removeGroup();
        $this->tricks->removeElement($trick);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor() : ?UserInterface
    {
        return $this->author;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor(UserInterface $author) : TrickGroupInterface
    {
        $this->author = $author;

        return $this;
    }
}
