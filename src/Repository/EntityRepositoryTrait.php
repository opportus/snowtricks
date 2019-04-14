<?php

namespace App\Repository;

use App\Entity\Entity;

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
        return $this->findOneBy(
            array(
                'id' => $id
            )
        );
    }
}
