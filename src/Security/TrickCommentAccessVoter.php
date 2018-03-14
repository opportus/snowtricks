<?php

namespace App\Security;

use App\Entity\TrickCommentInterface;
use App\Security\AuthorizableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * The trick comment access voter...
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentAccessVoter extends EntityAccessVoter
{
    /**
     * {@inheritdoc}
     */
    protected function canGet(AuthorizableInterface $subject, UserInterface $user) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function canPut(AuthorizableInterface $subject, UserInterface $user) : bool
    {
        return $subject->hasAuthor($user);
    }

    /**
     * {@inheritdoc}
     */
    protected function canDelete(AuthorizableInterface $subject, UserInterface $user) : bool
    {
        return $subject->hasAuthor($user);
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject(AuthorizableInterface $subject) : bool
    {
        return $subject instanceof TrickCommentInterface;
    }
}

