<?php

namespace App\Entity;

use App\Security\AuthorizableInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\Collection;

/**
 * The user interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface UserInterface extends EntityInterface, DtoAwareInterface, AuthorizableInterface, AdvancedUserInterface, \Serializable
{
    /**
     * Gets the email.
     *
     * @return string
     */
    public function getEmail() : string;

    /**
     * Gets the plain password.
     *
     * @return null|string
     */
    public function getPlainPassword() : ?string;

    /**
     * Gets the activation.
     *
     * @return bool
     */
    public function getActivation() : bool;

    /**
     * Adds a role.
     *
     * @param  string $role
     * @return App\Entity\UserInterface
     */
    public function addRole(string $role) : UserInterface;

    /**
     * Removes a role.
     *
     * @param  string $role
     * @return App\Entity\UserInterface
     */
    public function removeRole(string $role) : UserInterface;

    /**
     * Checks whether or not the user has this role.
     *
     * @param  string $role
     * @return bool
     */
    public function hasRole(string $role) : bool;

    /**
     * Gets the activation token.
     *
     * @return null|App\Entity\UserTokenInterface
     */
    public function getActivationToken() : ?UserTokenInterface;

    /**
     * Gets the password reset token.
     *
     * @return null|App\Entity\UserTokenInterface
     */
    public function getPasswordResetToken() : ?UserTokenInterface;

    /**
     * Creates an activation token.
     *
     * @param  int $ttl
     * @return App\Entity\UserTokenInterface
     */
    public function createActivationToken(int $ttl = 24) : UserTokenInterface;

    /**
     * Creates a password reset token.
     *
     * @param  int $ttl
     * @return App\Entity\UserTokenInterface
     */
    public function createPasswordResetToken(int $ttl = 24) : UserTokenInterface;

    /**
     * Gets the gravatar.
     *
     * @param  null|int $size
     * @param  null|string $imageSet
     * @param  null|string $rating
     * @return null|string
     */
    public function getGravatar(?int $size = 80, ?string $imageSet = 'mm', ?string $rating = 'g') : string;

    /**
     * Enables.
     *
     * @return App\Entity\UserInterface
     */
    public function enable() : UserInterface;

    /**
     * Disables.
     *
     * @return App\Entity\UserInterface
     */
    public function disable() : UserInterface;

    /**
     * Returns the username.
     *
     * @return string
     */
    public function __tostring() : string;
}

