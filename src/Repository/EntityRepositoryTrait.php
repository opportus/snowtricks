<?php

namespace App\Repository;

use App\Entity\EntityInterface;

/**
 * The entity repository trait...
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
     * @param  int $id
     * @return null|App\Entity\EntityInterface
     */
    public function findOneById(int $id) : ?EntityInterface
    {
        return $this->findOneBy(
            array(
                'id' => $id
            )
        );
    }
}

