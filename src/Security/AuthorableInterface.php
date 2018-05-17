<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * The authorable interface...
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface AuthorableInterface
{
    /**
     * Sets the author.
     *
     * @param  Symfony\Component\Security\Core\User\UserInterface $user
     * @return App\Security\AuthorableInterface
     */
    public function setAuthor(UserInterface $user) : AuthorableInterface;
}

