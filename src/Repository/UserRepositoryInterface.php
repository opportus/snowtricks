<?php

namespace App\Repository;

use App\Entity\User;

/**
 * The user repository interface.
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface UserRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Finds one user by username.
     *
     * @param  string $username
     * @return null|App\Entity\User
     */
    public function findOneByUsername(string $username) : ?User;

    /**
     * Finds one user by username or throws exception if no result.
     *
     * @param  string $username
     * @return App\Entity\User
     * @throws App\Exception\EntityNotFoundException
     */
    public function findOneByUsernameOrThrowExceptionIfNoResult(string $username) : User;
}
