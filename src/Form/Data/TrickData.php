<?php

namespace App\Form\Data;

use App\Entity\Data\EntityDataInterface;
use App\Entity\Data\TrickDataInterface;
use App\Entity\EntityInterface;
use App\Entity\TrickInterface;
use App\Entity\UserInterface;
use App\Entity\TrickGroupInterface;
use App\Security\AuthorableInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

/**
 * The trick data...
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickData implements TrickDataInterface
{
    /**
     * @var null|string $title
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $title;

    /**
     * @var null|string $description
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $description;

    /**
     * @var null|string $body
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=64512)
     */
    protected $body;

    /**
     * @var null|Doctrine\Common\Collections\Collection $attachments
     *
     * @Assert\Valid()
     */
    protected $attachments;

    /**
     * @var null|App\Entity\TrickGroupInterface $group
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $group;

    /**
     * @var null|App\Entity\UserInterface $author
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $author;

    /**
     * @var null|Doctrine\Common\Collections\Collection $comments
     *
     * @Assert\Valid()
     */
    protected $comments;

    /**
     * @var null|Doctrine\Common\Collections\Collection $versions
     *
     * @Assert\Valid()
     */
    protected $versions;

    /**
     * Constructs the trick data.
     *
     * @param null|string $title
     * @param null|string $description
     * @param null|string $body
     * @param null|Doctrine\Common\Collections\Collection $attachments
     * @param null|App\Entity\UserInterface $author
     * @param null|App\Entity\TrickGroupInterface $group
     * @param null|Doctrine\Common\Collections\Collection $comments
     * @param null|Doctrine\Common\Collections\Collection $versions
     */
    public function __construct(
        ?string              $title       = null,
        ?string              $description = null,
        ?string              $body        = null,
        ?Collection          $attachments = null,
        ?UserInterface       $author      = null,
        ?TrickGroupInterface $group       = null,
        ?Collection          $comments    = null,
        ?Collection          $versions    = null
    )
    {
        $this->title       = $title;
        $this->description = $description;
        $this->body        = $body;
        $this->attachments = $attachments;
        $this->author      = $author;
        $this->group       = $group;
        $this->comments    = $comments;
        $this->versions    = $versions;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromEntity(EntityInterface $entity) : EntityDataInterface
    {
        if (! $data instanceof TrickInterface) {
            throw new \InvalidArgumentException();
        }

        $self = get_called_class();

        return new $self(
            $entity->getTitle(),
            $entity->getDescription(),
            $entity->getBody(),
            $entity->getAttachments(),
            $entity->getAuthor(),
            $entity->getGroup(),
            $entity->getComments(),
            $entity->getVersions()
        );
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
    public function setTitle(string $title) : TrickDataInterface
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
    public function setDescription(string $description) : TrickDataInterface
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
    public function setBody(string $body) : TrickDataInterface
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments() : ?Collection
    {
        return $this->attachments;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttachments(Collection $attachments) : TrickDataInterface
    {
        $this->attachments = $attachments;

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
    public function setAuthor(SecurityUserInterface $author) : AuthorableInterface
    {
        $this->author = $author;

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
    public function setGroup(TrickGroupInterface $group) : TrickDataInterface
    {
        $this->group = $group;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersions() : ?Collection
    {
        return $this->versions;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersions(Collection $versions) : TrickDataInterface
    {
        $this->versions = $versions;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComments() : ?Collection
    {
        return $this->comments;
    }

    /**
     * {@inheritdoc}
     */
    public function setComments(Collection $comments) : TrickDataInterface
    {
        $this->comments = $comments;

        return $this;
    }
}

