<?php

namespace App\Entity\Data;

use App\Entity\UserInterface;
use App\Entity\TrickGroupInterface;
use App\Security\AuthorableInterface;
use Doctrine\Common\Collections\Collection;

/**
 * The trick data interface...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickDataInterface extends EntityDataInterface, AuthorableInterface
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
     * @return App\Entity\Data\TrickDataInterface
     */
    public function setTitle(string $title) : TrickDataInterface;

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
     * @return App\Entity\Data\TrickDataInterface
     */
    public function setDescription(string $description) : TrickDataInterface;

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
     * @return App\Entity\Data\TrickDataInterface
     */
    public function setBody(string $body) : TrickDataInterface;

    /**
     * Gets the attachments.
     *
     * @return null|Doctrine\Common\Collections\Collection
     */
    public function getAttachments() : ?Collection;

    /**
     * Sets the attachments.
     *
     * @param  Doctrine\Common\Collections\Collection $attachments
     * @return App\Entity\Data\TrickDataInterface
     */
    public function setAttachments(Collection $attachments) : TrickDataInterface;

    /**
     * Gets the author.
     *
     * @return null|App\Entity\UserInterface
     */
    public function getAuthor() : ?UserInterface;

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
     * @return App\Entity\Data\TrickDataInterface
     */
    public function setGroup(TrickGroupInterface $group) : TrickDataInterface;

    /**
     * Gets the versions.
     *
     * @return null|Doctrine\Common\Collections\Collection
     */
    public function getVersions() : ?Collection;

    /**
     * Sets the versions.
     *
     * @param  Doctrine\Common\Collections\Collection $versions
     * @return App\Entity\Data\TrickDataInterface
     */
    public function setVersions(Collection $versions) : TrickDataInterface;

    /**
     * Gets the comments.
     *
     * @return null|Doctrine\Common\Collections\Collection
     */
    public function getComments() : ?Collection;

    /**
     * Sets the comments.
     *
     * @param  Doctrine\Common\Collections\Collection $comments
     * @return App\Entity\Data\TrickDataInterface
     */
    public function setComments(Collection $comments) : TrickDataInterface;
}

