<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The authorizable trait...
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
trait AuthorizableTrait
{
    /**
     * @var null|App\Entity\UserInterface $author
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $author;

    /**
     * Checks whether the authorizable has the given author.
     *
     * @param  Symfony\Component\Security\Core\User\UserInterface $author
     * @return bool
     */
    public function hasAuthor(UserInterface $author) : bool
    {
        return $this->author === $author;
    }

    /**
     * Gets the author.
     *
     * @return Symfony\Component\Security\Core\User\UserInterface
     */
    public function getAuthor() : UserInterface
    {
        return $this->author;
    }

    /**
     * Sets the author.
     *
     * @param  Symfony\Component\Security\Core\User\UserInterface $author
     * @return App\Security\AuthorizableInterface
     */
    public function setAuthor(UserInterface $author) : AuthorizableInterface
    {
        $this->author = $author;

        return $this;
    }
}

