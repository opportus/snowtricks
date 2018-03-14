<?php

namespace App\Entity\Data;

use App\Entity\TrickCommentInterface;
use App\Entity\TrickInterface;
use App\Entity\UserInterface;
use App\Security\AuthorizableInterface;
use Doctrine\Common\Collections\Collection;

/**
 * The trick comment data interface...
 *
 * @version 0.0.1
 * @package App\Entity\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface TrickCommentDataInterface extends EntityDataInterface, AuthorizableInterface
{
    /**
     * Gets the update datetime.
     *
     * @return \DateTimeInterface
     */
    public function getUpdatedAt() : \DateTimeInterface;

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
     * @return App\Entity\Data\TrickCommentDataInterface
     */
    public function setBody(string $body) : TrickCommentDataInterface;

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
     * @return App\Entity\Data\TrickCommentDataInterface
     */
    public function setThread(TrickInterface $thread) : TrickCommentDataInterface;

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
     * @return App\Entity\Data\TrickCommentDataInterface
     */
    public function setParent(TrickCommentInterface $parent) : TrickCommentDataInterface;

    /**
     * Gets the children.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren() : Collection;

    /**
     * Sets the children.
     *
     * @param  Doctrine\Common\Collections\Collection $children
     * @return App\Entity\Data\TrickCommentDataInterface
     */
    public function setChildren(Collection $children) : TrickCommentDataInterface;
}

