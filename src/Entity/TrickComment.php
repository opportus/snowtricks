<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    protected $updatedAt;

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
     * @var App\Entity\User $author
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $author;

    /**
     * @var App\Entity\Trick $thread
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="comments")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $thread;

    /**
     * @var null|App\Entity\TrickComment $parent
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickComment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    protected $parent;

    /**
     * @var Doctrine\Common\Collections\Collection $children
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickComment", mappedBy="parent", cascade={"all"})
     * @Assert\Valid()
     */
    protected $children;

    /**
     * Constructs the trick comment.
     *
     * @param string $body
     * @param App\Entity\User $author
     * @param App\Entity\Trick $thread
     * @param null|App\Entity\TrickComment $parent
     * @param null|Collection $children
     */
    public function __construct(
        string        $body,
        User          $author,
        Trick         $thread,
        ?TrickComment $parent   = null,
        ?Collection   $children = null
    )
    {
        $this->id        = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->body      = $body;
        $this->author    = $author;
        $this->thread    = $thread;
        $this->parent    = $parent;
        $this->children  = $children === null ? new ArrayCollection() : $children;
    }

    /**
     * Updates the trick comment.
     *
     * @param string $body
     */
    public function update(string $body)
    {
        $this->body      = $body;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets the update datetime.
     *
     * @return null|\DateTime
     */
    public function getUpdatedAt() : ?\DateTime
    {
        return $this->updatedAt === null ? null : clone $this->updatedAt;
    }

    /**
     * Gets the body.
     *
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * Gets the author.
     *
     * @return App\Entity\User
     */
    public function getAuthor() : User
    {
        return $this->author;
    }

    /**
     * Checks whether the given user is an author.
     *
     * @param  App\Entity\User $user
     * @return bool
     */
    public function hasAuthor(User $author) : bool
    {
        return $author->getUsername() === $this->author->getUsername();
    }

    /**
     * Gets the thread.
     *
     * @return App\Entity\Trick
     */
    public function getThread() : Trick
    {
        return clone $this->thread;
    }

    /**
     * Gets the parent.
     *
     * @return null|App\Entity\TrickComment
     */
    public function getParent() : ?TrickComment
    {
        return $this->parent === null ? null : clone $this->parent;
    }

    /**
     * Checks whether or not the comment has a parent.
     *
     * @return bool
     */
    public function hasParent() : bool
    {
        return (bool) $this->parent;
    }

    /**
     * Gets the children.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren() : Collection
    {
        return clone $this->children;
    }

    /**
     * Checks whether or not the comment has children.
     *
     * @return bool
     */
    public function hasChildren() : bool
    {
        return ! $this->children->isEmpty();
    }

    /**
     * Adds a child.
     *
     * @param  App\Entity\TrickComment $child
     */
    public function addChild(TrickComment $child)
    {
        $child->setParent($this);

        if ($this->children->contains($child) === false) {
            $this->children->add($child);
        }
    }

    /**
     * Removes a child.
     *
     * @param  App\Entity\TrickComment $child
     */
    public function removeChild(TrickComment $child)
    {
        $child->removeParent();

        $this->children->removeElement($child);
    }
}
