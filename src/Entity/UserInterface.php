<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * The user interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface UserInterface extends EntityInterface, AdvancedUserInterface, \Serializable
{
    /**
     * Sets the username.
     *
     * @param  string $username
     * @return App\Entity\UserInterface
     */
    public function setUsername(string $username) : UserInterface;

    /**
     * Gets the email.
     *
     * @return null|string
     */
    public function getEmail() : ?string;

    /**
     * Sets the email.
     *
     * @param  string $email
     * @return App\Entity\UserInterface
     */
    public function setEmail(string $email) : UserInterface;

    /**
     * Gets the plain password.
     *
     * @return null|string
     */
    public function getPlainPassword() : ?string;

    /**
     * Sets the plain password.
     *
     * @param  string $plainPassword
     * @return App\Entity\UserInterface
     */
    public function setPlainPassword(string $plainPassword) : UserInterface;

    /**
     * Sets the password.
     *
     * @param  string $password
     * @return App\Entity\UserInterface
     */
    public function setPassword(string $password) : UserInterface;

    /**
     * Sets the salt.
     *
     * @param  string $salt
     * @return App\Entity\UserInterface
     */
    public function setSalt(string $salt) : UserInterface;

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
     * Gets the tokens.
     *
     * @return array
     */
    public function getTokens() : array;

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
     * Adds an activation token.
     *
     * @return App\Entity\UserInterface
     */
    public function addActivationToken() : UserInterface;

    /**
     * Adds a password reset token.
     *
     * @return App\Entity\UserInterface
     */
    public function addPasswordResetToken() : UserInterface;

    /**
     * Gets the gravatar.
     *
     * @param  null|int $size
     * @param  null|string $imageSet
     * @param  null|string $rating
     * @return null|string
     */
    public function getGravatar(?int $size = 80, ?string $imageSet = 'mm', ?string $rating = 'g') : ?string;

    /**
     * Returns the username.
     *
     * @return string
     */
    public function __tostring() : string;
}

