<?php

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick attachment data...
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAttachmentData
{
    /**
     * @var null|string $title
     *
     * @Assert\NotBlank(groups={"trick_attachment.form.edit"})
     * @Assert\Type(type="string", groups={"trick_attachment.form.edit"})
     * @Assert\Length(max=255, groups={"trick_attachment.form.edit"})
     */
    public $title;
}

