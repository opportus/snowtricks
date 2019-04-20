<?php

namespace App\Entity\Dto;

use App\Entity\User;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The trick dto.
 *
 * @version 0.0.1
 * @package App\Entity\Dto
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @AppAssert\UniqueEntity(
 *     entityClass="App\Entity\TrickVersion",
 *     primaryKey="title",
 *     message="trick.edit.form.message.title_conflict",
 *     groups={"trick.form.edit"}
 * )
 */
class TrickDto implements AuthorableInterface
{
    /**
     * @var null|string $title
     *
     * @Assert\NotBlank(groups={"trick.form.edit"})
     * @Assert\Type(type="string", groups={"trick.form.edit"})
     * @Assert\Length(max=255, groups={"trick.form.edit"})
     */
    public $title;

    /**
     * @var null|string $description
     *
     * @Assert\NotBlank(groups={"trick.form.edit"})
     * @Assert\Type(type="string", groups={"trick.form.edit"})
     * @Assert\Length(max=255, groups={"trick.form.edit"})
     */
    public $description;

    /**
     * @var null|string $body
     *
     * @Assert\NotBlank(groups={"trick.form.edit"})
     * @Assert\Type(type="string", groups={"trick.form.edit"})
     * @Assert\Length(max=64512, groups={"trick.form.edit"})
     */
    public $body;

    /**
     * @var null|Doctrine\Common\Collections\ArrayCollection $attachments
     *
     * @Assert\Valid()
     */
    public $attachments;

    /**
     * @var null|App\Entity\TrickGroup $group
     *
     * @Assert\NotNull(groups={"trick.form.edit"})
     * @Assert\Valid(groups={"trick.form.edit"})
     */
    public $group;

    /**
     * @var null|App\Entity\User $author
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $author;

    /**
     * @var null|Doctrine\Common\Collections\ArrayCollection $comments
     *
     * @Assert\Valid()
     */
    public $comments;

    /**
     * @var null|Doctrine\Common\Collections\ArrayCollection $versions
     *
     * @Assert\Valid()
     */
    public $versions;

    /**
     * @var null|App\Entity\Dto\TrickAttachmentDto $featuredAttachment
     */
    public $featuredAttachment;

    /**
     * {@inheritdoc}
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * Adds an attachment.
     *
     * Used by the form component.
     *
     * @param App\Entity\Dto\TrickAttachmentDto $attachment
     */
    public function addAttachment(TrickAttachmentDto $attachment)
    {
        if ($this->attachments === null) {
            $this->attachments = new ArrayCollection();
        }

        $this->attachments->add($attachment);
    }

    /**
     * Removes an attachment.
     *
     * Used by the form component.
     *
     * @param App\Entity\Dto\TrickAttachmentDto $attachment
     */
    public function removeAttachment(TrickAttachmentDto $attachment)
    {
        $this->attachments->removeElement($attachment);
    }
}
