<?php

namespace App\Repository;

use App\Entity\UserToken;

/**
 * The user token repository interface.
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface UserTokenRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Finds one user token by key.
     *
     * @param  string $key
     * @return null|App\Entity\UserToken
     */
    public function findOneByKey(string $key) : ?UserToken;

    /**
     * Finds all user tokens by user ID.
     *
     * @param  int userId
     * @return array
     */
    public function findAllByUserId(int $userId) : array;
}
