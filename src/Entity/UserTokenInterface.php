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
     * @return string
     */
    public function getKey() : string;

    /**
     * Gets the type.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Gets the ttl.
     *
     * @return int
     */
    public function getTtl() : int;

    /**
     * Gets the user.
     *
     * @return App\Entity\UserInterface
     */
    public function getUser() : UserInterface;

    /**
     * Checks whether or not the token is expired.
     *
     * @return bool
     */
    public function isExpired() : bool;

    /**
     * Checks whether or not the token key is equal to the given string.
     *
     * @param  string $token
     * @return bool
     */
    public function hasKey(string $token) : bool;

    /**
     * Returns the key.
     *
     * @return string
     */
    public function __toString() : string;
}

