<?php

namespace App\Entity\Data;

/**
 * The user data interface...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface UserDataInterface extends EntityDataInterface
{
    /**
     * Gets the usesrname.
     *
     * @return null|string
     */
    public function getUsername() : ?string;

    /**
     * Sets the username.
     *
     * @param  string $username
     * @return App\Entity\Data\UserDataInterface
     */
    public function setUsername(string $username) : UserDataInterface;

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
     * @return App\Entity\Data\UserDataInterface
     */
    public function setEmail(string $email) : UserDataInterface;

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
     * @return App\Entity\Data\UserDataInterface
     */
    public function setPlainPassword(string $plainPassword) : UserDataInterface;

    /**
     * Gets the activation.
     *
     * @return null|bool
     */
    public function getActivation() : ?bool;

    /**
     * Sets the activation.
     *
     * @param  bool $activation
     * @return App\Entity\Data\UserDataInterface
     */
    public function setActivation(bool $activation) : UserDataInterface;

    /**
     * Gets the roles.
     *
     * @return null|array
     */
    public function getRoles() : ?array;

    /**
     * Sets the roles.
     *
     * @param  array $roles
     * @return App\Entity\Data\UserDataInterface
     */
    public function setRoles(array $roles) : UserDataInterface;
}

