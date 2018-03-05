<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Collections\Selectable;

/**
 * The entity repository interface...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface EntityRepositoryInterface extends ServiceEntityRepositoryInterface, ObjectRepository, Selectable
{
    /**
     * Finds one entity by ID.
     *
     * @param  int $id
     * @return null|App\Entity\EntityInterface
     */
    public function findOneById(int $id) : ?EntityInterface;
}

