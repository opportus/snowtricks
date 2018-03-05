<?php

namespace App\Repository;

use App\Entity\TrickInterface;

/**
 * The trick repository interface...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Finds one trick by slug.
     *
     * @param  string $slug
     * @return null|App\Entity\TrickInterface
     */
    public function findOneBySlug(string $slug) : ?TrickInterface;
}

