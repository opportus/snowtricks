<?php

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user activation email data.
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserActivationEmailData
{
    /**
     * @var bool $activation
     *
     * @Assert\NotNull(groups={"user.form.activation_email"})
     * @Assert\Type(type="bool", groups={"user.form.activation_email"})
     */
    public $activation = false;
}
