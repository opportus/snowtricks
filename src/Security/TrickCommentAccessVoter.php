<?php

namespace App\Security;

use App\Entity\EntityInterface;
use App\Entity\TrickCommentInterface;
use App\Entity\UserInterface;

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
    protected function canGet(EntityInterface $subject, UserInterface $user) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function canPut(EntityInterface $subject, UserInterface $user) : bool
    {
        return $subject->getAuthor()->getId() === $user->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function canDelete(EntityInterface $subject, UserInterface $user) : bool
    {
        return $subject->getAuthor()->getId() === $user->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject(EntityInterface $subject) : bool
    {
        return $subject instanceof TrickCommentInterface;
    }
}

