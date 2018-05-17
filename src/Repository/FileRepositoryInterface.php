<?php

namespace App\Repository;

use App\Entity\FileInterface;

/**
 * The file repository interface...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface FileRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Finds one file by path.
     *
     * @param  string $path
     * @return null|App\Entity\FileInterface
     */
    public function findOneByPath(string $path) : ?FileInterface;
}

