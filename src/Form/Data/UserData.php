<?php

namespace App\Form\Data;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user data.
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
 * @AppAssert\PersistedEntity(
 *     entityClass="App\Entity\User",
 *     entityIdentifier="username",
 *     message="user.password_reset_request.form.message.username_not_found",
 *     groups={"user.form.password_reset_request"}
 * )
 */
class UserData
{
    /**
     * @var null|int $id
     */
    public $id;

    /**
     * @var null|string $username
     *
     * @Assert\NotBlank(groups={"user.form.sign_up", "user.form.sign_in", "user.form.password_reset_request"})
     * @Assert\Type(type="string", groups={"user.form.sign_up", "user.form.sign_in", "user.form.password_reset_request"})
     * @Assert\Length(max=35, groups={"user.form.sign_up", "user.form.sign_in", "user.form.password_reset_request"})
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
     * @Assert\Type(type="string", groups={"user.form.sign_up", "user.form.password_reset"})
     * @Assert\Length(max=4096, groups={"user.form.sign_up", "user.form.password_reset"})
     */
    public $password;

    /**
     * @var bool $activation
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    public $activation = false;
}
