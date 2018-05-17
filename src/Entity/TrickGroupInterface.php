<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * The trick group interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickGroupInterface extends EntityInterface
{
    /**
     * Gets the slug.
     *
     * @return string
     */
    public function getSlug() : string;

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle() : string;

    /**
     * Gets the description.
     *
     * @return string
     */
    public function getDescription() : string;

    /**
     * Gets the tricks.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTricks() : Collection;

    /**
     * Adds a trick.
     *
     * @param  App\Entity\TrickInterface $trick
     * @return App\Entity\TrickGroupInterface
     */
    public function addTrick(TrickInterface $trick) : TrickGroupInterface;

    /**
     * Removes a trick.
     *
     * @param  App\Entity\TrickInterface $trick
     * @return App\Entity\TrickGroupInterface
     */
    public function removeTrick(TrickInterface $trick) : TrickGroupInterface;
}

