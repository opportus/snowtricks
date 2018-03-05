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
     * @return null|string
     */
    public function getSrc();

    /**
     * Sets the src.
     *
     * @param  string $src
     * @return App\Entity\TrickAttachmentInterface
     */
    public function setSrc(string $src) : TrickAttachmentInterface;

    /**
     * Gets the title.
     *
     * @return null|string
     */
    public function getTitle();

    /**
     * Sets the title.
     *
     * @param  string $title
     * @return App\Entity\TrickAttachmentInterface
     */
    public function setTitle(string $title) : TrickAttachmentInterface;

    /**
     * Gets the alt.
     *
     * @return null|string
     */
    public function getAlt();

    /**
     * Sets the alt.
     *
     * @param  string $alt
     * @return App\Entity\TrickAttachmentInterface
     */
    public function setAlt(string $alt): TrickAttachmentInterface;

    /**
     * Gets the type.
     *
     * @return null|string
     */
    public function getType();

    /**
     * Sets the type.
     *
     * @param  string $typee
     * @return App\Entity\TrickAttachmentInterface
     */
    public function setType(string $type) : TrickAttachmentInterface;

    /**
     * Gets the trick version.
     *
     * @return null|App\Entity\TrickVersionInterface
     */
    public function getTrickVersion();

    /**
     * Sets the trick version.
     *
     * @param  App\Entity\TrickVersionInterface $trickVersion
     * @return App\Entity\TrickAttachmentInterface
     */
    public function setTrickVersion(TrickVersionInterface $trickVersion) : TrickAttachmentInterface;
}
