<?php

namespace App\Entity\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

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
     * @var string $src
     *
     * @Assert\NotBlank(groups={"trick.form.edit"})
     * @Assert\Type(type="string", groups={"trick.form.edit"})
     * @Assert\Url(groups={"trick.form.edit"})
     */
    public $src;

    /**
     * @var string $type
     *
     * @Assert\NotBlank(groups={"trick.form.edit"})
     * @Assert\Type(type="string", groups={"trick.form.edit"})
     * @AppAssert\TrickAttachmentMimeType()
     */
    public $type;
}
