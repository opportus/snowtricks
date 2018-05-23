<?php

namespace App\Form\Data;

use App\Entity\Dto\DtoInterface;
use App\Entity\Dto\DtoTrait;
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
class TrickData implements DtoInterface, AuthorableInterface
{
    use DtoTrait;

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
     * @var null|Doctrine\Common\Collections\Collection $attachments
     *
     * @Assert\Valid()
     */
    public $attachments;

    /**
     * @var null|App\Entity\TrickGroupInterface $group
     *
     * @Assert\NotNull(groups={"trick.form.edit"})
     * @Assert\Valid(groups={"trick.form.edit"})
     */
    public $group;

    /**
     * @var null|App\Entity\UserInterface $author
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $author;

    /**
     * @var null|Doctrine\Common\Collections\Collection $comments
     *
     * @Assert\Valid()
     */
    public $comments;

    /**
     * @var null|Doctrine\Common\Collections\Collection $versions
     *
     * @Assert\Valid()
     */
    public $versions;

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
    public function setAuthor(SecurityUserInterface $author) : AuthorableInterface
    {
        $this->author = $author;

        return $this;
    }
}

