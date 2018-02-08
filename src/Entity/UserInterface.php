<?php

namespace App\Entity;

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
interface UserInterface extends AdvancedUserInterface, \Serializable, EntityInterface
{
    /**
     * Gets the id.
     *
     * @return null|int
     */
    public function getId();

    /**
     * Sets the id.
     *
     * @param  int $id
     * @return App\Entity\UserInterface
     */
    public function setId(int $id) : UserInterface;

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
    public function getEmail();

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
    public function getPlainPassword();

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
     * @return App\Entity\UserInterface
     */
    public function setSalt() : UserInterface;

    /**
     * Gets the activation.
     *
     * @return null|bool
     */
    public function getActivation();

    /**
     * Sets the activation.
     *
     * @param  bool $activation
     * @return App\Entity\UserInterface
     */
    public function setActivation(bool $activation) : UserInterface;

    /**
     * Gets the creation datetime.
     *
     * @return null|\Datetime
     */
    public function getCreatedAt();

    /**
     * Sets the creation datetime.
     *
     * @return App\Entity\UserInterface
     */
    public function setCreatedAt() : UserInterface;

    /**
     * Sets the roles.
     *
     * @param  array $roles
     * @return App\Entity\UserInterface
     */
    public function setRoles(array $roles) : UserInterface;

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
     * @return null|Doctrine\Common\Collections\Collection
     */
    public function getTokens();

    /**
     * Sets the tokens.
     *
     * @param  Doctrine\Common\Colelctions\Collection $collection
     * @return App\Entity\UserInterface
     */
    public function setTokens(Collection $collection) : UserInterface;

    /**
     * Returns the username.
     *
     * @return string
     */
    public function __tostring() : string;
}

