<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
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
     * @var null|\DateTimeInterface $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Assert\Type(type="object")
     * @Assert\DateTime()
     */
    protected $updatedAt;

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
     * @var Doctrine\Common\Collections\Collection $comments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickComment", mappedBy="thread", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @Assert\Valid()
     */
    protected $comments;

    /**
     * @var null|App\Entity\TrickGroupInterface $group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup", inversedBy="tricks", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    protected $group;

    /**
     * @var Doctrine\Common\Collections\Collection $versions
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickVersion", mappedBy="trick", cascade={"all"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @Assert\Valid()
     */
    protected $versions;

    /**
     * @var App\Entity\TrickVersionInterface $version
     */
    protected $version;

    /**
     * Constructs the trick.
     *
     * @param string $title
     * @param string $description
     * @param string $body
     * @param App\Entity\UserInterface $author
     * @param null|App\Entity\TrickGroupInterface $group
     * @param null|Doctrine\Common\Collections\Collection $attachments
     * @param null|Doctrine\Common\Collections\Collection $comments
     * @param null|Doctrine\Common\Collections\Collection $versions
     */
    public function __construct(
        string               $title,
        string               $description,
        string               $body,
        UserInterface        $author,
        ?TrickGroupInterface $group       = null,
        ?Collection          $attachments = null,
        ?Collection          $comments    = null,
        ?Collection          $versions    = null
    )
    {
        $this->id        = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->comments  = $comments === null ? new ArrayCollection() : $comments;
        $this->versions  = $versions === null ? new ArrayCollection() : $versions;

        $version = new TrickVersion(
            $title,
            $description,
            $body,
            $author,
            $this,
            $group,
            $attachments
        );

        $version->enable();

        $this->versions->add($version);

        $this->setVersion($version);
    }

    /**
     * {@inheritdoc}
     */
    public function update(
        string               $title,
        string               $description,
        string               $body,
        UserInterface        $author,
        ?TrickGroupInterface $group       = null,
        ?Collection          $attachments = null,
        ?Collection          $comments    = null,
        ?Collection          $versions    = null
    ) : TrickInterface
    {
        $version = new TrickVersion(
            $title,
            $description,
            $body,
            $author,
            $this,
            $group,
            $attachments,
            $comments,
            $versions
        );

        $this->setVersion($version);

        return $this;
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
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersions() : Collection
    {
        return clone $this->versions;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion() : TrickVersionInterface
    {
        if ($this->version !== null && $this->version->isEnabled()) {
            return $this->version;
        }

        foreach ($this->versions as $version) {
            if ($version->isEnabled()) {
                return $this->version = $version;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion(TrickVersionInterface $version) : TrickInterface
    {
        if ($this->getVersion()->getId() !== $version->getId()) {
            $this->getVersion()->disable();

            $version->enable();

            $this->updatedAt = new \DateTime();
        }

        $this->addVersion($version);

        $this->version = $version;
        $this->group   = $version->getGroup();
        $this->slug    = preg_replace('/[\s]+/', '-', strtolower(trim($version->getTitle())));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addVersion(TrickVersionInterface $version) : TrickInterface
    {
        if ($this->versions->contains($version) === false) {
            $this->versions->add($version);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComments() : Collection
    {
        return clone $this->comments;
    }

    /**
     * {@inheritdoc}
     */
    public function addComment(TrickCommentInterface $comment) : TrickInterface
    {
        if ($this->comments->contains($comment) === false) {
            $this->comments->add($comment);
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
    public function getTitle() : string
    {
        return $this->getVersion()->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() : string
    {
        return $this->getVersion()->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        return $this->getVersion()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments() : Collection
    {
        return $this->getVersion()->getAttachments();
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
    public function getAuthor() : UserInterface
    {
        return $this->getVersion()->getAuthor();
    }

    /**
     * {@inheritdoc}
     */
    public function hasAuthor(UserInterface $user) : bool
    {
        foreach ($this->getAuthors() as $author) {
            if ($author->getId() === $user->getId()) {
                return true;
            }
        }

        return false;
    }
}

