<?php

namespace App\Entity\Data;

use App\Entity\EntityInterface;
use App\Entity\TrickCommentInterface;
use App\Entity\TrickInterface;
use App\Entity\UserInterface;
use App\Security\AuthorizableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick comment data...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentData implements TrickCommentDataInterface
{
    use AuthorizableTrait;

    /**
     * @var null|\DateTimeInterface $updatedAt
     */
    protected $updatedAt;

    /**
     * @var null|string $body
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=64512)
     */
    protected $body;

    /**
     * @var null|App\Entity\UserInterface $author
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $author;

    /**
     * @var null|App\Entity\TrickInterface $thread
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $thread;

    /**
     * @var null|App\Entity\TrickCommentInterface $parent
     *
     * @Assert\Valid()
     */
    protected $parent;

    /**
     * @var Doctrine\Common\Collections\Collection $children
     *
     * @Assert\Valid()
     */
    protected $children;

    /**
     * Constructs the trick comment data.
     *
     * @param string $body
     * @param App\Entity\UserInterface $author
     * @param App\Entity\TrickInterface $thread
     * @param App\Entity\TrickCommentInterface $parent
     * @param Doctrine\Common\Collections\Collection $children
     */
    public function __construct(
        string                $body     = null,
        UserInterface         $author   = null,
        TrickInterface        $thread   = null,
        TrickCommentInterface $parent   = null,
        Collection            $children = null
    )
    {
        $this->body     = $body;
        $this->author   = $author;
        $this->thread   = $thread;
        $this->parent   = $parent;
        $this->children = $children === null ? new ArrayCollection : $children;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromEntity(EntityInterface $entity) : EntityDataInterface
    {
        if (! $entity instanceof TrickCommentInterface) {
            throw new \InvalidArgumentException();
        }

        $self = get_called_class();

        return new $self(
            $entity->getBody(),
            $entity->getAuthor(),
            $entity->getThread(),
            $entity->getParent(),
            $entity->getChildren()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt() : \DateTimeInterface
    {
        return $this->updatedAt();
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
    public function setBody(string $body) : TrickCommentDataInterface
    {
        $this->body = $body;

        return $this;
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
    public function setThread(TrickInterface $thread) : TrickCommentDataInterface
    {
        $this->thread = $thread;

        return $this;
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
    public function setParent(TrickCommentInterface $parent) : TrickCommentDataInterface
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren() : Collection
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function setChildren(Collection $children) : TrickCommentDataInterface
    {
        $this->children = $children;

        return $this;
    }
}

