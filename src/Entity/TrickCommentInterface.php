<?php

namespace App\Entity;

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
     * Gets the body.
     *
     * @return null|string
     */
    public function getBody() : ?string;

    /**
     * Sets the body.
     *
     * @param  string $body
     * @return App\Entity\TrickCommentInterface
     */
    public function setBody(string $body) : TrickCommentInterface;

    /**
     * Gets the update datetime.
     *
     * @return null|\DateTimeInterface
     */
    public function getUpdatedAt() : ?\DateTimeInterface;

    /**
     * Gets the parent.
     *
     * @return null|App\Entity\TrickCommentInterface
     */
    public function getParent() : ?TrickCommentInterface;

    /**
     * Sets the parent.
     *
     * @param  App\Entity\TrickCommentInterface $parent
     * @return App\Entity\TrickCommentInterface
     */
    public function setParent(TrickCommentInterface $parent) : TrickCommentInterface;

    /**
     * Removes the parent.
     *
     * @return App\Entity\TrickCommentInterface
     */
    public function removeParent() : TrickCommentInterface;

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
    public function getChildren() : array;

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

    /**
     * Checks whether or not the comment has children.
     *
     * @return bool
     */
    public function hasChildren() : bool;

    /**
     * Gets the thread.
     *
     * @return null|App\Entity\TrickInterface
     */
    public function getThread() : ?TrickInterface;

    /**
     * Sets the thread.
     *
     * @param  App\Entity\TrickInterface $thread
     * @return App\Entity\TrickCommentInterface
     */
    public function setThread(TrickInterface $thread) : TrickCommentInterface;

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
     * @return App\Entity\TrickCommentInterface
     */
    public function setAuthor(UserInterface $author) : TrickCommentInterface;
}

