<?php

namespace App\Entity\Dto;

use App\Entity\User;

/**
 * The authorable interface.
 *
 * @version 0.0.1
 * @package App\Entity\Dto
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface AuthorableInterface
{
    /**
     * Sets the author.
     *
     * @param App\Entity\User $user
     */
    public function setAuthor(User $user);
}
