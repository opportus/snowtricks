<?php

namespace App\Form\Data;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user sign up data.
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @AppAssert\UniqueEntityData(
 *     entityClass="App\Entity\User",
 *     entityIdentifier="id",
 *     data={"username", "email"},
 *     message="user.sign_up.form.message.conflict",
 *     groups={"user.form.sign_up"}
 * )
 */
class UserSignUpData
{
    /**
     * @var null|int $id
     */
    public $id;

    /**
     * @var null|string $username
     *
     * @Assert\NotBlank(groups={"user.form.sign_up"})
     * @Assert\Type(type="string", groups={"user.form.sign_up"})
     * @Assert\Length(max=35, groups={"user.form.sign_up"})
     */
    public $username;

    /**
     * @var null|string $email
     *
     * @Assert\NotBlank(groups={"user.form.sign_up"})
     * @Assert\Type(type="string", groups={"user.form.sign_up"})
     * @Assert\Length(max=255, groups={"user.form.sign_up"})
     * @Assert\Email(groups={"user.form.sign_up"})
     */
    public $email;

    /**
     * @var null|string $password
     *
     * @Assert\Type(type="string", groups={"user.form.sign_up"})
     * @Assert\Length(max=4096, groups={"user.form.sign_up"})
     */
    public $password;
}
