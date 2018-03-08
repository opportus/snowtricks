<?php

namespace App\Security;

use App\Entity\EntityInterface;
use App\Entity\TrickInterface;
use App\Entity\UserInterface;

/**
 * The trick access voter...
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAccessVoter extends EntityAccessVoter
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
        return $subject->isAuthor($user);
    }

    /**
     * {@inheritdoc}
     */
    protected function canDelete(EntityInterface $subject, UserInterface $user) : bool
    {
        return $subject->isAuthor($user);
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject(EntityInterface $subject) : bool
    {
        return $subject instanceof TrickInterface;
    }
}

