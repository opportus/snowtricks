<?php

namespace App\Entity;

use App\Entity\Data\EntityDataInterface;

/**
 * The transferable data interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TransferableDataInterface
{
    /**
     * Creates from data.
     *
     * @param  App\Entity\Data\EntityDataInterface $data
     * @return App\Entity\EntityInterface
     * @throws \InvalidArgumentException
     */
    public static function createFromData(EntityDataInterface $data) : EntityInterface;

    /**
     * Updates from data.
     *
     * @param  App\Entity\Data\EntityDataInterface $data
     * @return App\Entity\EntityInterface
     * @throws \InvalidArgumentException
     */
    public function updateFromData(EntityDataInterface $data) : EntityInterface;
}

