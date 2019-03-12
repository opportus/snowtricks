<?php

namespace App\Entity\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick attachment dto...
 *
 * @version 0.0.1
 * @package App\Entity\Dto
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAttachmentDto
{
    /**
     * @var string $id
     */
    public $id;

    /**
     * @var string $src
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\Url()
     */
    public $src;

    /**
     * @var string $title
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    public $title;

    /**
     * @var null|App\Entity\TrickVersionInterface $trickVersion
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $trickVersion;
}
