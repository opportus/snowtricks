<?php

namespace App\Entity;

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
     * @return null|string
     */
    public function getTitle() : ?string;

    /**
     * Sets the title.
     *
     * @param  string $title
     * @return App\Entity\TrickVersionInterface
     */
    public function setTitle(string $title) : TrickVersionInterface;

    /**
     * Gets the description.
     *
     * @return null|string
     */
    public function getDescription() : ?string;

    /**
     * Sets the description.
     *
     * @param  string $description
     * @return App\Entity\TrickVersionInterface
     */
    public function setDescription(string $description) : TrickVersionInterface;

    /**
     * Gets the body.
     *
     * @return null|string
     */
    public function getBody() : ?string;

    /**
     * Sets the body.
     *
     * @param  string $body
     * @return App\Entity\TrickVersionInterface
     */
    public function setBody(string $body) : TrickVersionInterface;

    /**
     * Returns whether or not this is enabled.
     *
     * @return bool
     */
    public function isEnabled() : bool;

    /**
     * Disables.
     *
     * @return App\Entity\TrickVersionInterface
     */
    public function disable() : TrickVersionInterface;

    /**
     * Gets the attachments.
     *
     * @return array
     */
    public function getAttachments() : array;

    /**
     * Adds an attachment.
     *
     * @param  App\Entity\TrickAttachmentInterface $attachment
     * @return App\Entity\TrickVerionInterface
     */
    public function addAttachment(TrickAttachmentInterface $attachment) : TrickVersionInterface;

    /**
     * Gets the group.
     *
     * @return null|App\Entity\TrickGroupInterface
     */
    public function getGroup() : ?TrickGroupInterface;

    /**
     * Sets the group.
     *
     * @param  App\Entity\TrickGroupInterface $group
     * @return App\Entity\TrickVersionInterface
     */
    public function setGroup(TrickGroupInterface $group) : TrickVersionInterface;

    /**
     * Gets the trick.
     *
     * @return null|App\Entity\TrickInterface
     */
    public function getTrick() : ?TrickInterface;

    /**
     * Sets the trick.
     *
     * @param  App\Entity\TrickInterface $trick
     * @param  bool $enable
     * @return App\Entity\TrickVersionInterface
     */
    public function setTrick(TrickInterface $trick, bool $enable = false) : TrickVersionInterface;

    /**
     * Gets the author.
     *
     * @return null|App\Entity\UserInterface
     */
    public function getAuthor() : ?UserInterface;

    /**
     * Sets the author.
     *
     * @param  App\Entity\UserInterface $author
     * @return App\Entity\TrickVersionInterface
     */
    public function setAuthor(UserInterface $author) : TrickVersionInterface;
}

