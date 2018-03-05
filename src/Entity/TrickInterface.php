<?php

namespace App\Entity;

/**
 * The trick interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickInterface extends EntityInterface
{
    /**
     * Gets the slug.
     *
     * @return null|string
     */
    public function getSlug() : ?string;

    /**
     * Gets the title.
     *
     * @return null|string
     */
    public function getTitle() : ?string;

    /**
     * Gets the description.
     *
     * @return null|string
     */
    public function getDescription() : ?string;

    /**
     * Gets the body.
     *
     * @return null|string
     */
    public function getBody() : ?string;

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
     * @return App\Entity\TrickInterface
     */
    public function setGroup(TrickGroupInterface $group) : TrickInterface;

    /**
     * Removes the group.
     *
     * @return App\Entity\TrickInterface
     */
    public function removeGroup() : TrickInterface;

    /**
     * Gets the attachments.
     *
     * @return array
     */
    public function getAttachments() : array;

    /**
     * Gets the featured attachment.
     *
     * @return null|App\Entity\TrickAttachmentInterface
     */
    public function getFeaturedAttachment() : ?TrickAttachmentInterface;

    /**
     * Gets the authors.
     *
     * @return array
     */
    public function getAuthors() : array;

    /**
     * Checks whether or not the user is one of the authors.
     *
     * @param  App\Entity\UserInterface $user
     * @return bool
     */
    public function isAuthor(UserInterface $user) : bool;

    /**
     * Gets the update datetime.
     *
     * @return null|\DateTimeInterface
     */
    public function getUpdatedAt() : ?\DateTimeInterface;

    /**
     * Gets the version.
     *
     * @return null|App\Entity\TrickVersionInterface
     */
    public function getVersion() : ?TrickVersionInterface;

    /**
     * Sets the version.
     *
     * @param  App\Entity\TrickVersionInterface $version
     * @return App\Entity\TrickInterface
     */
    public function setVersion(TrickVersionInterface $version) : TrickInterface;

    /**
     * Gets the versions.
     *
     * @return array
     */
    public function getVersions() : array;

    /**
     * Adds a version.
     *
     * @param  App\Entity\TrickVersionInterface $version
     * @return App\Entity\TrickInterface
     */
    public function addVersion(TrickVersionInterface $version) : TrickInterface;

    /**
     * Gets the comments.
     *
     * @return array
     */
    public function getComments() : array;

    /**
     * Adds a comment.
     *
     * @param  App\Entity\TrickCommentInterface $comment
     * @return App\Entity\TrickInterface
     */
    public function addComment(TrickCommentInterface $comment) : TrickInterface;
}

