<?php

namespace App\Entity\Dto;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user dto...
 *
 * @version 0.0.1
 * @package App\Entity\Dto
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @AppAssert\UniqueEntity(
 *     entityClass="App\Entity\User",
 *     primaryKey="username",
 *     message="user.sign_up.form.message.username_conflict",
 *     groups={"sign_up_form"}
 * )
 * @AppAssert\UniqueEntity(
 *     entityClass="App\Entity\User",
 *     primaryKey="email",
 *     message="user.sign_up.form.message.email_conflict",
 *     groups={"sign_up_form"}
 * )
 * @AppAssert\PersistedEntity(
 *     entityClass="App\Entity\User",
 *     primaryKey="username",
 *     message="user.password_reset_request.form.message.username_not_found",
 *     groups={"password_reset_request_form"}
 * )
 */
class UserDto
{
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
     * @var null|string $plainPassword
     *
     * @Assert\Type(type="string", groups={"user.form.sign_up", "user.form.password_reset"})
     * @Assert\Length(max=4096, groups={"user.form.sign_up", "user.form.password_reset"})
     */
    public $plainPassword;

    /**
     * @var null|bool $activation
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    public $activation;

    /**
     * @var null|array $roles
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="array")
     */
    public $roles;
}
