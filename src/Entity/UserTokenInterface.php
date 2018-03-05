<?php

namespace App\Entity;

/**
 * The user token interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface UserTokenInterface extends EntityInterface
{
    /**
     * Gets the key.
     *
     * @return null|string
     */
    public function getKey() : ?string;

    /**
     * Gets the type.
     *
     * @return null|string
     */
    public function getType() : ?string;

    /**
     * Sets the type.
     *
     * @param  string $type
     * @return App\Entity\UserTokenInterface
     */
    public function setType(string $type) : UserTokenInterface;

    /**
     * Gets the ttl.
     *
     * @return null|int
     */
    public function getTtl() : ?int;

    /**
     * Gets the user.
     *
     * @return null|App\Entity\UserInterface
     */
    public function getUser() : ?UserInterface;

    /**
     * Sets the user.
     *
     * @param  App\Entity\UserInterface
     * @return App\Entity\UserTokenInterface
     */
    public function setUser(UserInterface $user) : UserTokenInterface;

    /**
     * Checks whether or not the token is expired.
     *
     * @return bool
     */
    public function isExpired() : bool;

    /**
     * Checks whether or not the token is equal to the given token.
     *
     * @param  string $token
     * @return bool
     */
    public function isEqualTo(string $token) : bool;

    /**
     * Returns the key.
     *
     * @return string
     */
    public function __toString() : string;
}

