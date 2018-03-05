<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick comment...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickCommentRepository")
 * @ORM\Table(name="trick_comment")
 */
class TrickComment extends Entity implements TrickCommentInterface
{
    /**
     * @var null|string $body
     *
     * @ORM\Column(name="body", type="text")
     *
     * @Assert\NotBlank(groups={"trick_comment.edit.form"})
     * @Assert\Type(type="string", groups={"trick_comment.edit.form"})
     * @Assert\Length(max=64512, groups={"trick_comment.edit.form"})
     */
    protected $body;

    /**
     * @var null|\DateTimeInterface $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     *
     * @Assert\Type(type="object")
     * @Assert\DateTime()
     */
    protected $updatedAt;

    /**
     * @var null|App\Entity\TrickCommentInterface $parent
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickComment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    protected $parent;

    /**
     * @var Doctrine\Common\Collections\Collection $children
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickComment", mappedBy="parent", cascade={"all"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    protected $children;

    /**
     * @var null|App\Entity\TrickInterface $thread
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="comments")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $thread;

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
     * Constructs the trick comment.
     */
    public function __construct()
    {
        parent::__construct();

        $this->children = new ArrayCollection();
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
    public function setBody(string $body) : TrickCommentInterface
    {
        if ($this->body !== null) {
            $this->updatedAt = new \DateTime();
        }

        $this->body = $body;

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
    public function getParent() : ?TrickCommentInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(TrickCommentInterface $parent) : TrickCommentInterface
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParent() : bool
    {
        return (bool) $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function removeParent() : TrickCommentInterface
    {
        $this->parent = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren() : array
    {
        return $this->children->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(TrickCommentInterface $child) : TrickCommentInterface
    {
        $child->setParent($this);

        if ($this->children->contains($child) === false) {
            $this->children->add($child);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(TrickCommentInterface $child) : TrickCommentInterface
    {
        $child->removeParent();
        $this->children->removeElement($child);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren() : bool
    {
        return !$this->children->isEmpty();
    }


    /**
     * {@inheritdoc}
     */
    public function getThread() : ?TrickInterface
    {
        return $this->thread;
    }

    /**
     * {@inheritdoc}
     */
    public function setThread(TrickInterface $thread) : TrickCommentInterface
    {
        $this->thread = $thread;

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
    public function setAuthor(UserInterface $author) : TrickCommentInterface
    {
        $this->author = $author;

        return $this;
    }
}

