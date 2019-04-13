<?php

namespace App\Security;

use App\Entity\Entity;
use App\Entity\TrickComment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * The trick comment access voter.
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
    protected function canGet(Entity $subject, TokenInterface $token) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function canPost(Entity $subject, TokenInterface $token) : bool
    {
        return $token->isAuthenticated();
    }

    /**
     * {@inheritdoc}
     */
    protected function canPut(Entity $subject, TokenInterface $token) : bool
    {
        return $subject->hasAuthor($token->getUser());
    }

    /**
     * {@inheritdoc}
     */
    protected function canPatch(Entity $subject, TokenInterface $token) : bool
    {
        return $subject->hasAuthor($token->getUser());
    }

    /**
     * {@inheritdoc}
     */
    protected function canDelete(Entity $subject, TokenInterface $token) : bool
    {
        return $subject->hasAuthor($token->getUser());
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject(Entity $subject) : bool
    {
        return $subject instanceof TrickComment;
    }
}
