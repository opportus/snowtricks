<?php

namespace App\Entity;

use App\Entity\Dto\TrickAttachmentDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick version.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repsository\TrickVersionRepository", readOnly=true)
 * @ORM\Table(name="trick_version")
 */
class TrickVersion extends Entity
{
    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    private $enabled;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
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
     * @var null|App\Entity\TrickGroupInterface $group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $group;

    /**
     * @var Doctrine\Common\Collections\Collection $attachments
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrickAttachment", mappedBy="trickVersion", cascade={"all"}, orphanRemoval=true)
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
     * @var App\Entity\TrickInterface $trick
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="versions", cascade={"persist"})
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $trick;

    /**
     * Constructs the trick version.
     *
     * @param string $title
     * @param string $description
     * @param string $body
     * @param App\Entity\UserInterface $author
     * @param App\Entity\TrickInterface $trick
     * @param null|App\Entity\TrickGroupInterface $group
     * @param null|Doctrine\Common\Collections\Collection $attachments
     */
    public function __construct(
        string      $title,
        string      $description,
        string      $body,
        User        $author,
        Trick       $trick,
        ?TrickGroup $group       = null,
        ?Collection $attachments = null
    ) {
        $this->id          = $this->generateId();
        $this->createdAt   = new \DateTime();
        $this->title       = $title;
        $this->description = $description;
        $this->body        = $body;
        $this->enabled     = false;
        $this->author      = $author;
        $this->trick       = $trick;
        $this->group       = $group;
        $this->attachments = new ArrayCollection();

        if (isset($attachments)) {
            foreach ($attachments as $attachment) {
                if (!$attachment instanceof TrickAttachmentDto) {
                    continue;
                }

                $this->attachments->add(new TrickAttachment(
                    $attachment->src,
                    $attachment->type,
                    $this
                ));
            }
        }
    }

    /**
     * Enables.
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disables.
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * Returns whether or not this is enabled.
     *
     * @return bool
     */
    public function isEnabled() : bool
    {
        return $this->enabled;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Gets the description.
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
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
     * Gets the group.
     *
     * @return null|App\Entity\TrickGroup
     */
    public function getGroup() : ?TrickGroup
    {
        return $this->group;
    }

    /**
     * Gets the attachments.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAttachments() : Collection
    {
        return clone $this->attachments;
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
     * Gets the trick.
     *
     * @return App\Entity\Trick
     */
    public function getTrick() : Trick
    {
        return $this->trick;
    }
}
