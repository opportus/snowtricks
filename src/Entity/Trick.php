<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * The trick.
 *
 * @todo Implement slug constraint.
 * 
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @ORM\Table(name="trick")
 */
class Trick extends Entity
{
    /**
     * @var null|\DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Assert\Type(type="object")
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    private $slug;

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
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    private $description;

    /**
     * @var string $body
     *
     * @ORM\Column(name="body", type="text")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=64512)
     */
    private $body;

    /**
     * @var null|App\Entity\TrickGroup $group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $group;

    /**
     * @var Doctrine\Common\Collections\Collection $attachments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickAttachment", mappedBy="trick", cascade={"all"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $attachments;

    /**
     * @var App\Entity\UserInterface $author
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $author;

    /**
     * @var Doctrine\Common\Collections\Collection $comments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickComment", mappedBy="thread", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @Assert\Valid()
     */
    private $comments;

    /**
     * Constructs the trick.
     *
     * @param string $title
     * @param string $description
     * @param string $body
     * @param App\Entity\TrickGroup $group
     * @param App\Entity\User $author
     */
    public function __construct(
        string $title,
        string $description,
        string $body,
        TrickGroup $group,
        User $author
    ) {
        $this->id = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->slug = $this->slugify($title);
        $this->title = $title;
        $this->description = $description;
        $this->body = $body;
        $this->group = $group;
        $this->author = $author;
        $this->attachments = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Gets the update datetime.
     *
     * @return null|\DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Gets the slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
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

    /**
     * Sets the title.
     * 
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets the description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description.
     * 
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets the body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sets the body.
     * 
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets the group.
     *
     * @return App\Entity\TrickGroup
     *
     * @todo This was possibly null...
     */
    public function getGroup(): TrickGroup
    {
        return $this->group;
    }

    /**
     * Sets the group.
     * 
     * @param App\Entity\TrickGroup $group
     */
    public function setGroup(TrickGroup $group)
    {
        $this->group = $group;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets the attachments.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    /**
     * Sets the attachments.
     *
     * @param Doctrine\Common\Collections\Collection $attachments
     */
    public function setAttachments(Collection $attachments)
    {
        $this->attachments = $attachments;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Adds an attachment.
     * 
     * @param App\Entity\TrickAttachment $attachment
     */
    public function addAttachment(TrickAttachment $attachment)
    {
        $criteria = new Criteria();

        if (0 < \count($this->attachments->matching(
            $criteria->where(
                $criteria->expr()->eq('src', $attachment->getSrc())
            )
        ))) {
            return;
        }

        $this->attachments->add($attachment);
        $this->updatedAt = new \DateTime();
    }

    /**
     * Removes an attachment.
     * 
     * @param App\Entity\TrickAttachment $attachment
     */
    public function removeAttachment(TrickAttachment $attachment)
    {
        $this->attachments->removeElement($attachment);
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets the featured attachment.
     *
     * @return null|App\Entity\TrickAttachment
     */
    public function getFeaturedAttachment(): ?TrickAttachment
    {
        foreach ($this->attachments as $attachment) {
            if ('i' === $attachment->getType()[0]) {
                return $attachment;
            }
        }

        return null;
    }

    /**
     * Gets the author.
     *
     * @return App\Entity\User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * Sets the author.
     * 
     * @param App\Entity\User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Checks whether this trick has the author.
     * 
     * @param App\Entity\User $user
     * @return bool
     */
    public function hasAuthor(User $user): bool
    {
        return $user->getId() === $this->author->getId();
    }

    /**
     * Gets the comments.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Slugifies.
     *
     * @param string $str
     * @return string
     */
    private function slugify(string $str): string
    {
        return \preg_replace('/[\s]+/', '-', \strtolower(\trim($str)));
    }
}
