<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The trick comment.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickCommentRepository")
 * @ORM\Table(name="trick_comment")
 */
class TrickComment extends Entity
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
     * @var string $body
     *
     * @ORM\Column(name="body", type="text")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=64512)
     */
    private $body;

    /**
     * @var App\Entity\Trick $thread
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="comments")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $thread;

    /**
     * @var App\Entity\User $author
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $author;

    /**
     * @var null|App\Entity\TrickComment $parent
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickComment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $parent;

    /**
     * @var Doctrine\Common\Collections\Collection $children
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickComment", mappedBy="parent", cascade={"all"})
     * @Assert\Valid()
     */
    private $children;

    /**
     * Constructs the trick comment.
     *
     * @param string $body
     * @param App\Entity\Trick $thread
     * @param App\Entity\User $author
     * @param null|App\Entity\TrickComment $parent
     */
    public function __construct(
        string $body,
        Trick $thread,
        User $author,
        ?TrickComment $parent = null
    ) {
        $this->id = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->body = $body;
        $this->thread = $thread;
        $this->author = $author;
        $this->parent = $parent;
        $this->children = new ArrayCollection();
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
     * Gets the thread.
     *
     * @return App\Entity\Trick
     */
    public function getThread(): Trick
    {
        return $this->thread;
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
     * Checks whether this comment has the author.
     * 
     * @param App\Entity\User $user
     * @return bool
     */
    public function hasAuthor(User $user): bool
    {
        return $user->getId() === $this->author->getId();
    }

    /**
     * Gets the parent.
     *
     * @return null|App\Entity\TrickComment
     */
    public function getParent(): ?TrickComment
    {
        return $this->parent;
    }

    /**
     * Gets the children.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }
}
