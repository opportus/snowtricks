<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * The trick interface.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickInterface extends EntityInterface
{
    /**
     * Updates the trick.
     *
     * @param  string $title
     * @param  string $description
     * @param  string $body
     * @param  App\Entity\UserInterface $author
     * @param  null|App\Entity\TrickGroupInterface $group
     * @param  null|Doctrine\Common\Collections\Collection $attachments
     * @param  null|Doctrine\Common\Collections\Collection $comments
     * @param  null|Doctrine\Common\Collections\Collection $versions
     * @return App\Entity\TrickInterface
     */
    public function update(
        string               $title,
        string               $description,
        string               $body,
        UserInterface        $author,
        ?TrickGroupInterface $group       = null,
        ?Collection          $attachments = null,
        ?Collection          $comments    = null,
        ?Collection          $versions    = null
    ) : TrickInterface;

    /**
     * Gets the update datetime.
     *
     * @return null|\DateTimeInterface
     */
    public function getUpdatedAt() : ?\DateTimeInterface;

    /**
     * Gets the slug.
     *
     * @return string
     */
    public function getSlug() : string;

    /**
     * Gets the version.
     *
     * @return App\Entity\TrickVersionInterface
     */
    public function getVersion() : TrickVersionInterface;

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
     * @return Doctrine\Common\Collections\Collection
     */
    public function getVersions() : Collection;

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
     * @return Doctrine\Common\Collections\Collection
     */
    public function getComments() : Collection;

    /**
     * Adds a comment.
     *
     * @param  App\Entity\TrickCommentInterface $comment
     * @return App\Entity\TrickInterface
     */
    public function addComment(TrickCommentInterface $comment) : TrickInterface;

    /**
     * Gets the group.
     *
     * @return null|App\Entity\TrickGroupInterface
     */
    public function getGroup() : ?TrickGroupInterface;

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
     * Gets the attachments.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAttachments() : Collection;

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
     * Gets the author.
     *
     * @return App\Entity\User
     */
    public function getAuthor() : User;

    /**
     * Checks whether the given user is an author.
     *
     * @param  App\Entity\User $user
     * @return bool
     */
    public function hasAuthor(User $user) : bool;
}
