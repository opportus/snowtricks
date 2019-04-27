<?php

namespace App\Form\Data;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user password reset request data.
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @AppAssert\PersistedEntity(
 *     entityClass="App\Entity\User",
 *     entityIdentifier="username",
 *     message="user.password_reset_request.form.message.username_not_found",
 *     groups={"user.form.password_reset_request"}
 * )
 */
class UserPasswordResetRequestData
{
    /**
     * @var null|string $username
     *
     * @Assert\NotBlank(groups={"user.form.password_reset_request"})
     * @Assert\Type(type="string", groups={"user.form.password_reset_request"})
     * @Assert\Length(max=35, groups={"user.form.password_reset_request"})
     */
    public $username;
}
