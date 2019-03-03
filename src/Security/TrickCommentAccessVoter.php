<?php

namespace App\Security;

use App\Entity\UserInterface;
use App\Entity\TrickCommentInterface;
use App\Security\AuthorizableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
    protected function canGet(AuthorizableInterface $subject, TokenInterface $token) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function canPost(AuthorizableInterface $subject, TokenInterface $token) : bool
    {
        return $token->isAuthenticated();
    }

    /**
     * {@inheritdoc}
     */
    protected function canPut(AuthorizableInterface $subject, TokenInterface $token) : bool
    {
        if (!$token->getUser() instanceof UserInterface) {
            return false;
        }

        return $subject->hasAuthor($token->getUser());
    }

    /**
     * {@inheritdoc}
     */
    protected function canPatch(AuthorizableInterface $subject, TokenInterface $token) : bool
    {
        if (!$token->getUser() instanceof UserInterface) {
            return false;
        }

        return $subject->hasAuthor($token->getUser());
    }

    /**
     * {@inheritdoc}
     */
    protected function canDelete(AuthorizableInterface $subject, TokenInterface $token) : bool
    {
        if (!$token->getUser() instanceof UserInterface) {
            return false;
        }

        return $subject->hasAuthor($token->getUser());
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject(AuthorizableInterface $subject) : bool
    {
        return $subject instanceof TrickCommentInterface;
    }
}
