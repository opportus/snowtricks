<?php

namespace App\Entity;

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
     * Sets the title.
     *
     * @param  string $title
     * @return App\Entity\TrickGroupInterface
     */
    public function setTitle(string $title) : TrickGroupInterface;

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
     * @return App\Entity\TrickGroupInterface
     */
    public function setDescription(string $description) : TrickGroupInterface;

    /**
     * Gets the tricks.
     *
     * @return array
     */
    public function getTricks() : array;

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
     * @return App\Entity\TrickGroupInterface
     */
    public function setAuthor(UserInterface $author) : TrickGroupInterface;
}
