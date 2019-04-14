<?php

namespace App\Repository;

use App\Entity\Entity;
use App\Exception\EntityNotFoundException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The entity repository trait.
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
trait EntityRepositoryTrait
{
    /**
     * Finds one entity by ID.
     *
     * @param  string $id
     * @return null|App\Entity\Entity
     */
    public function findOneById(string $id) : ?Entity
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Finds one entity by ID or throws exception if no result.
     *
     * @param  string $id
     * @return App\Entity\Entity
     * @throws App\Exception\EntityNotFoundException
     */
    public function findOneByIdOrThrowExceptionIfNoResult(string $id) : Entity
    {
        $entity = $this->findOneById($id);

        if (null === $entity) {
            throw new EntityNotFoundException('No entity matches this set of criteria');
        }

        return $entity;
    }

    /**
     * Finds all entities by criteria.
     *
     * @param mixed[]  $criteria
     * @param mixed[]  $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllByCriteria(array $criteria, ?array $orderBy = null, $limit = null, $offset = null) : ArrayCollection
    {
        return new ArrayCollection($this->findBy($criteria, $orderBy, $limit, $offset));
    }

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
    public function findAllByCriteriaOrThrowExceptionIfNoResult(array $criteria, ?array $orderBy = null, $limit = null, $offset = null) : ArrayCollection
    {
        $entityCollection = $this->findAllByCriteria($criteria, $orderBy, $limit, $offset);

        if ($entityCollection->isEmpty()) {
            throw new EntityNotFoundException('No entity matches this set of criteria.');
        }

        return $entityCollection;
    }
}
