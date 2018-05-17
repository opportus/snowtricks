<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick version...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repsository\TrickVersionRepository", readOnly=true)
 * @ORM\Table(name="trick_version")
 *
 * @todo Implement custom unique title constraint validator...
 */
class TrickVersion extends Entity implements TrickVersionInterface
{
    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
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
     * @var string $body
     *
     * @ORM\Column(name="body", type="text")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=64512)
     */
    protected $body;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    protected $enabled;

    /**
     * @var App\Entity\UserInterface $author
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $author;

    /**
     * @var App\Entity\TrickInterface $trick
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="versions", cascade={"persist"})
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $trick;

    /**
     * @var null|App\Entity\TrickGroupInterface $group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $group;

    /**
     * @var Doctrine\Common\Collections\Collection $attachments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickAttachment", mappedBy="trickVersion", cascade={"all"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    protected $attachments;

    /**
     * Constructs the trick version.
     *
     * @param string $title
     * @param string $description
     * @param string $body
     * @param App\Entity\UserInterface $author
     * @param App\Entity\TrickInterface $trick
     * @param null|App\Entity\TrickGroupInterface $group
     * @param null|Doctrine\Common\Collections\Collection $attachments
     */
    public function __construct(
        string               $title,
        string               $description,
        string               $body,
        UserInterface        $author,
        TrickInterface       $trick,
        ?TrickGroupInterface $group       = null,
        ?Collection          $attachments = null
    )
    {
        $this->id          = $this->generateId();
        $this->createdAt   = new \DateTime();
        $this->title       = $title;
        $this->description = $description;
        $this->body        = $body;
        $this->enabled     = false;
        $this->author      = $author;
        $this->trick       = $trick;
        $this->group       = $group;
        $this->attachments = $attachments === null ? new ArrayCollection() : $attachments;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled() : bool
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function enable() : TrickVersionInterface
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disable() : TrickVersionInterface
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor() : UserInterface
    {
        return $this->author;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrick() : TrickInterface
    {
        return $this->trick;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments() : Collection
    {
        return clone $this->attachments;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup() : ?TrickGroupInterface
    {
        return $this->group;
    }
}

