<?php

namespace App\Repository;

use App\Entity\TrickGroupInterface;

/**
 * The trick group repository interface...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickGroupRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Finds one trick group by slug.
     *
     * @param  string $slug
     * @return null|App\Entity\TrickGroupInterface
     */
    public function findOneBySlug(string $slug) : ?TrickGroupInterface;
}

