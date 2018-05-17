<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * The trick version interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickVersionInterface extends EntityInterface
{
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
     * Gets the body.
     *
     * @return string
     */
    public function getBody() : string;

    /**
     * Returns whether or not this is enabled.
     *
     * @return bool
     */
    public function isEnabled() : bool;

    /**
     * Enables.
     *
     * @return App\Entity\TrickVersionInterface
     */
    public function enable() : TrickVersionInterface;

    /**
     * Disables.
     *
     * @return App\Entity\TrickVersionInterface
     */
    public function disable() : TrickVersionInterface;

    /**
     * Gets the trick.
     *
     * @return App\Entity\TrickInterface
     */
    public function getTrick() : TrickInterface;

    /**
     * Gets the author.
     *
     * @return App\Entity\UserInterface
     */
    public function getAuthor() : UserInterface;

    /**
     * Gets the group.
     *
     * @return null|App\Entity\TrickGroupInterface
     */
    public function getGroup() : ?TrickGroupInterface;

    /**
     * Gets the attachments.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAttachments() : Collection;
}
