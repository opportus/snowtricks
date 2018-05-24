<?php

namespace App\Form\Data;

use App\Entity\Dto\DtoInterface;
use App\Entity\Dto\DtoTrait;
use App\Entity\UserInterface;
use App\Entity\TrickInterface;
use App\Entity\TrickCommentInterface;
use App\Security\AuthorableInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

/**
 * The trick comment data...
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentData implements DtoInterface, AuthorableInterface
{
    use DtoTrait;

    /**
     * @var null|string $body
     *
     * @Assert\NotBlank(groups={"trick_comment.form.edit"})
     * @Assert\Type(type="string", groups={"trick_comment.form.edit"})
     * @Assert\Length(max=64512, groups={"trick_comment.form.edit"})
     */
    public $body;

    /**
     * @var null|App\Entity\UserInterface $author
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $author;

    /**
     * @var null|App\Entity\TrickInterface $thread
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $thread;

    /**
     * @var null|App\Entity\TrickCommentInterface $parent
     *
     * @Assert\Valid()
     */
    public $parent;

    /**
     * @var null|Doctrine\Common\Collections\Collection
     *
     * @Assert\Valid()
     */
    public $children;

    /**
     * Constructs the trick comment data.
     *
     * @param null|string $body
     * @param null|App\Entity\UserInterface $author
     * @param null|App\Entity\TrickInterface $thread
     * @param null|App\Entity\TrickCommentInterface $parent
     * @param null\Doctrine\Common\Collections\Collection $children
     */
    public function __construct(
        ?string                $body     = null,
        ?UserInterface         $author   = null,
        ?TrickInterface        $thread   = null,
        ?TrickCommentInterface $parent   = null,
        ?Collection            $children = null
    )
    {
        $this->body     = $body;
        $this->author   = $author;
        $this->thread   = $thread;
        $this->parent   = $parent;
        $this->children = $children;
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

