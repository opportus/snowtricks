<?php

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user password reset data.
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserPasswordResetData
{
    /**
     * @var null|string $password
     *
     * @Assert\Type(type="string", groups={"user.form.password_reset"})
     * @Assert\Length(max=4096, groups={"user.form.password_reset"})
     */
    public $password;
}
