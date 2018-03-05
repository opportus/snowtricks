<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var null|string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(groups={"trick_version.edit.form"})
     * @Assert\Type(type="string", groups={"trick_version.edit.form"})
     * @Assert\Length(max=255, groups={"trick_version.edit.form"})
     */
    protected $title;

    /**
     * @var null|string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank(groups={"trick_version.edit.form"})
     * @Assert\Type(type="string", groups={"trick_version.edit.form"})
     * @Assert\Length(max=255, groups={"trick_version.edit.form"})
     */
    protected $description;

    /**
     * @var null|string $body
     *
     * @ORM\Column(name="body", type="text")
     * @Assert\NotBlank(groups={"trick_version.edit.form"})
     * @Assert\Type(type="string", groups={"trick_version.edit.form"})
     * @Assert\Length(max=64512, groups={"trick_version.edit.form"})
     */
    protected $body;

    /**
     * @var null|bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    protected $enabled;

    /**
     * @var Doctrine\Common\Collections\Collection $attachments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickAttachment", mappedBy="trickVersion", cascade={"all"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    protected $attachments;

    /**
     * @var null|App\Entity\TrickGroupInterface $group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(groups={"trick_version.edit.form"})
     * @Assert\Valid(groups={"trick_version.edit.form"})
     */
    protected $group;

    /**
     * @var null|App\Entity\TrickInterface $trick
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="versions", cascade={"persist"})
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $trick;

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
     * Constructs the trick version.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachments = new ArrayCollection();
        $this->current     = false;
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
    public function setTitle(string $title) : TrickVersionInterface
    {
        $this->title = $title;

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
    public function setDescription(string $description) : TrickVersionInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : ?string
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody(string $body) : TrickVersionInterface
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled() : bool
    {
        return (bool) $this->enabled;
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
    public function getAttachments() : array
    {
        return $this->attachments->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(TrickAttachmentInterface $attachment) : TrickVersionInterface
    {
        $attachment->setTrickVersion($this);

        if ($this->attachments->contains($attachment) === false) {
            $this->attachments->add($attachment);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup() : ?TrickGroupInterface
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroup(TrickGroupInterface $group) : TrickVersionInterface
    {
        $this->group = $group;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrick() : ?TrickInterface
    {
        return $this->trick;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrick(TrickInterface $trick, bool $enable = false) : TrickVersionInterface
    {
        $this->trick   = $trick;
        $this->enabled = $enable;

        if ($enable) {
            if ($trick->getVersion() === null || $trick->getVersion()->getId() !== $this->id) {
                $trick->setVersion($this);
            }

        } else {
            $trick->addVersion($this);
        }

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
    public function setAuthor(UserInterface $author) : TrickVersionInterface
    {
        $this->author = $author;

        return $this;
    }
}

