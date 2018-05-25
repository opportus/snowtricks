<?php

namespace App\Form\Data;

use App\Security\AuthorableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick data...
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickData implements AuthorableInterface
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
     * {@inheritdoc}
     */
    public function setAuthor(UserInterface $author) : AuthorableInterface
    {
        $this->author = $author;

        return $this;
    }
}

