<?php

namespace App\Entity;

/**
 * The trick attachment interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickAttachmentInterface extends EntityInterface
{
    /**
     * Gets the src.
     *
     * @return string
     */
    public function getSrc();

    /**
     * Gets the type.
     *
     * @return string
     */
    public function getType();

    /**
     * Gets the trick version.
     *
     * @return App\Entity\TrickVersionInterface
     */
    public function getTrickVersion();
}
