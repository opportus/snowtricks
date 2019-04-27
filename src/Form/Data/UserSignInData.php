<?php

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user sign in data.
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserSignInData
{
    /**
     * @var null|string $username
     *
     * @Assert\NotBlank(groups={"user.form.sign_in"})
     * @Assert\Type(type="string", groups={"user.form.sign_in"})
     * @Assert\Length(max=35, groups={"user.form.sign_in"})
     */
    public $username;

    /**
     * @var null|string $password
     *
     * @Assert\Type(type="string", groups={"user.form.sign_in"})
     * @Assert\Length(max=4096, groups={"user.form.sign_in"})
     */
    public $password;
}
