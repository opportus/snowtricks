<?php

namespace App\Repository;

use App\Entity\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Collections\Selectable;

/**
 * The user repository interface...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface UserRepositoryInterface extends ServiceEntityRepositoryInterface, ObjectRepository, Selectable
{
    /**
     * Finds one user by id.
     *
     * @param  int $id
     * @return null|App\Entity\UserInterface
     */
    public function findOneById(int $id);

    /**
     * Finds one user by username.
     *
     * @param  string $username
     * @return null|App\Entity\UserInterface
     */
    public function findOneByUsername(string $username);

    /**
     * Finds one user by email.
     *
     * @param  string $email
     * @return null|App\Entity\UserInterface
     */
    public function findOneByEmail(string $email);
}

