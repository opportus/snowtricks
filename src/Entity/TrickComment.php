<?php

namespace App\Entity;

use App\Entity\Data\EntityDataInterface;
use App\Entity\Data\TrickCommentDataInterface;
use App\Entity\Data\TrickCommentData;
use App\Security\AuthorizableTrait;
use Doctrine\Common\Collections\Collection;
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
    use AuthorizableTrait;

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
     * @var string $body
     *
     * @ORM\Column(name="body", type="text")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=64512)
     */
    protected $body;

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
     * @var App\Entity\TrickInterface $thread
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="comments")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $thread;

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
     * Constructs the trick comment.
     *
     * @param string $body
     * @param App\Entity\UserInterface $author
     * @param App\Entity\TrickInterface $thread
     * @param App\Entity\TrickCommentInterface $parent
     * @param Doctrine\Common\Collections\Collection $children
     */
    private function __construct(
        string                 $body,
        UserInterface          $author,
        TrickInterface         $thread,
        ?TrickCommentInterface $parent   = null,
        ?Collection            $children = null
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
     * {@inheritdoc}
     */
    public static function createFromData(EntityDataInterface $data) : EntityInterface
    {
        if (! $data instanceof TrickCommentDataInterface) {
            throw new \InvalidArgumentException();
        }

        $self = get_called_class();

        return new $self(
            $data->getBody(),
            $data->getAuthor(),
            $data->getThread(),
            $data->getParent(),
            $data->getChildren()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function updateFromData(EntityDataInterface $data) : EntityInterface
    {
        if (! $data instanceof TrickCommentDataInterface) {
            throw new \InvalidArgumentException();
        }

        if ($data->getBody()) {
            $this->body      = $data->getBody();
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toData() : EntityDataInterface
    {
        return TrickCommentData::createFromEntity($this);
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
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function getThread() : TrickInterface
    {
        return $this->thread;
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
    public function getChildren() : Collection
    {
        return clone $this->children;
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
}

