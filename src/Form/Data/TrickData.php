<?php

namespace App\Form\Data;

use App\Entity\User;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The trick data.
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @AppAssert\UniqueEntityData(
 *     entityClass="App\Entity\Trick",
 *     entityIdentifier="id",
 *     data={"title"},
 *     message="trick.edit.form.message.title_conflict",
 *     groups={"trick.form.edit"}
 * )
 */
class TrickData implements AuthorableInterface
{
    /**
     * @var null|string $id
     */
    public $id;

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
     * @var null|App\Entity\TrickGroup $group
     *
     * @Assert\NotNull(groups={"trick.form.edit"})
     * @Assert\Valid(groups={"trick.form.edit"})
     */
    public $group;

    /**
     * @var Doctrine\Common\Collections\Collection $attachments
     *
     * @Assert\Valid()
     */
    public $attachments;

    /**
     * @var null|App\Entity\TrickAttachment
     */
    public $featuredAttachment;

    /**
     * @var null|App\Entity\User $author
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $author;

    /**
     * Cosntructs the trick data.
     */
    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     * 
     * Used by the authorizer listener on form submit.
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
     * @param App\Form\Data\TrickAttachmentData $attachment
     */
    public function addAttachment(TrickAttachmentData $attachment)
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
     * @param App\Form\Data\TrickAttachmentData $attachment
     */
    public function removeAttachment(TrickAttachmentData $attachment)
    {
        $this->attachments->removeElement($attachment);
    }
}
