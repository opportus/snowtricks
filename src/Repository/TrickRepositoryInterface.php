<?php

namespace App\Repository;

use App\Entity\Trick;

/**
 * The trick repository interface.
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Finds one trick by slug or throws exception if no result.
     *
     * @param  string $slug
     * @return App\Entity\Trick
     * @throws App\Exception\EntityNotFoundException
     */
    public function findOneBySlugOrThrowExceptionIfNoResult(string $slug) : Trick;
}
