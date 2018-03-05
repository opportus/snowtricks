<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @ORM\Table(name="trick")
 */
class Trick extends Entity implements TrickInterface
{
    /**
     * @var null|string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $slug;

    /**
     * @var null|\DateTimeInterface $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Assert\Type(type="object")
     * @Assert\DateTime()
     */
    protected $updatedAt;

    /**
     * @var null|App\Entity\TrickGroupInterface $group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup", inversedBy="tricks", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    protected $group;

    /**
     * @var Doctrine\Common\Collections\Collection $comments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickComment", mappedBy="thread", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @Assert\Valid()
     */
    protected $comments;

    /**
     * @var Doctrine\Common\Collections\Collection $versions
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickVersion", mappedBy="trick", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @Assert\Valid()
     */
    protected $versions;

    /**
     * @var null|App\Entity\TrickVersionInterface $version
     */
    protected $version;

    /**
     * Constructs the trick.
     */
    public function __construct()
    {
        parent::__construct();

        $this->versions = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
        return $this->getVersion() === null
            ? null
            : $this->getVersion()->getTitle()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() : ?string
    {
        return $this->getVersion() === null
            ? null
            : $this->getVersion()->getDescription()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : ?string
    {
        return $this->getVersion() === null
            ? null
            : $this->getVersion()->getBody()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments() : array
    {
        return $this->getVersion() === null
            ? array()
            : $this->getVersion()->getAttachments()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedAttachment() : ?TrickAttachmentInterface
    {
        foreach ($this->getVersion()->getAttachments() as $attachment) {
            if ($attachment->getType()[0] === 'i') {
                return $attachment;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthors() : array
    {
        $authors = array();

        foreach ($this->versions as $version) {
            if (in_array($version->getAuthor(), $authors)) {
                continue;
            }

            $authors[] = $version->getAuthor();
        }

        return $authors;
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthor(UserInterface $user) : bool
    {
        foreach ($this->getAuthors() as $author) {
            if ($author->getId() === $user->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt() : ?\DateTimeInterface
    {
        return $this->updatedAt;
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
    public function setGroup(TrickGroupInterface $group) : TrickInterface
    {
        $this->group = $group;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeGroup() : TrickInterface
    {
        $this->group = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion() : ?TrickVersionInterface
    {
        if ($this->version !== null && $this->version->isEnabled()) {
            return $this->version;
        }

        $criteria = Criteria::create();

        $criteria->expr()->eq('enabled', true);

        $version = $this->versions->matching($criteria)[0];

        $this->version = $version instanceof TrickVersionInterface ? $version : null;

        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersions() : array
    {
        return $this->versions->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion(TrickVersionInterface $version) : TrickInterface
    {
        if ($this->getVersion()) {
            $trick->getVersion()->disable();

            $this->updatedAt = new \DateTime();
        }

        $this->addVersion($version, $current = true);

        $this->group = $version->getGroup();
        $this->slug  = preg_replace('/[\s]+/', '-', strtolower(trim($version->getTitle())));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addVersion(TrickVersionInterface $version, bool $current = false) : TrickInterface
    {
        if ($version->getTrick() === null || $version->getTrick()->getId() !== $this->id) {
            $version->setTrick($this, $current);
        }

        if ($this->versions->contains($version) === false) {
            $this->versions->add($version);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComments() : array
    {
        return $this->comments->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function addComment(TrickCommentInterface $comment) : TrickInterface
    {
        $comment->setTrick($this);

        if ($this->comments->contains($comment) === false) {
            $this->comments->add($comment);
        }

        return $this;
    }
}

