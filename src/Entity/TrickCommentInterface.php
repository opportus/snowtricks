<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * The trick comment interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickCommentInterface extends EntityInterface
{
    /**
     * Updates the trick comment.
     *
     * @param  string $body
     * @return App\Entity\TrickCommentInterface
     */
    public function update(string $body) : TrickCommentInterface;

    /**
     * Gets the update datetime.
     *
     * @return null|\DateTimeInterface
     */
    public function getUpdatedAt() : ?\DateTimeInterface;

    /**
     * Gets the body.
     *
     * @return string
     */
    public function getBody() : string;

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

    /**
     * Gets the thread.
     *
     * @return App\Entity\TrickInterface
     */
    public function getThread() : TrickInterface;

    /**
     * Gets the parent.
     *
     * @return null|App\Entity\TrickCommentInterface
     */
    public function getParent() : ?TrickCommentInterface;

    /**
     * Checks whether or not the comment has a parent.
     *
     * @return bool
     */
    public function hasParent() : bool;

    /**
     * Gets the children.
     *
     * @return array
     */
    public function getChildren() : Collection;

    /**
     * Checks whether or not the comment has children.
     *
     * @return bool
     */
    public function hasChildren() : bool;

    /**
     * Adds a child.
     *
     * @param  App\Entity\TrickCommentInterface $child
     * @return App\Entity\TrickCommentInterface
     */
    public function addChild(TrickCommentInterface $child) : TrickCommentInterface;

    /**
     * Removes a child.
     *
     * @param  App\Entity\TrickCommentInterface $child
     * @return App\Entity\TrickCommentInterface
     */
    public function removeChild(TrickCommentInterface $child) : TrickCommentInterface;
}

