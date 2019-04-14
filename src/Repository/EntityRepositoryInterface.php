<?php

namespace App\Repository;

use App\Entity\Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The entity repository interface.
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
     * @param  string $id
     * @return null|App\Entity\Entity
     */
    public function findOneById(string $id) : ?Entity;

    /**
     * Finds one entity by ID or throws exception if no result.
     *
     * @param  string $id
     * @return App\Entity\Entity
     * @throws App\Exception\EntityNotFoundException
     */
    public function findOneByIdOrThrowExceptionIfNoResult(string $id) : Entity;

    /**
     * Finds all entities by criteria.
     *
     * @param mixed[]  $criteria
     * @param mixed[]  $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllByCriteria(array $criteria, ?array $orderBy = null, $limit = null, $offset = null) : ArrayCollection;

    /**
     * Finds all entities by criteria or throws exception if no result.
     *
     * @param mixed[]  $criteria
     * @param mixed[]  $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Doctrine\Common\Collections\ArrayCollection
     * @throws App\Exception\EntityNotFoundException
     */
    public function findAllByCriteriaOrThrowExceptionIfNoResult(array $criteria, ?array $orderBy = null, $limit = null, $offset = null) : ArrayCollection;
}
