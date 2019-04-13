<?php

namespace App\Entity\Dto;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick comment dto.
 *
 * @version 0.0.1
 * @package App\Entity\Dto
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentDto implements AuthorableInterface
{
    /**
     * @var null|string $body
     *
     * @Assert\NotBlank(groups={"trick_comment.form.edit"})
     * @Assert\Type(type="string", groups={"trick_comment.form.edit"})
     * @Assert\Length(max=64512, groups={"trick_comment.form.edit"})
     */
    public $body;

    /**
     * @var null|App\Entity\User $author
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
     * {@inheritdoc}
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }
}
