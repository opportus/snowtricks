<?php

namespace App\Security;

use App\Entity\EntityInterface;
use App\Entity\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * The entity access voter...
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class EntityAccessVoter extends Voter
{
    const GET    = 'GET';
    const PUT    = 'PUT';
    const DELETE = 'DELETE';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if (! in_array($attribute, array(self::GET, self::PUT, self::DELETE))) {
            return false;
        }

        if (! $subject instanceof EntityInterface) {
            return false;
        }

        if (! $this->supportsSubject($subject)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (! $user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::GET:
                return $this->canGet($subject, $user);
            case self::PUT:
                return $this->canPut($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }
    }

    /**
     * Checks whether the user can get the subject.
     *
     * @param  App\Entity\EntityInterface $subject
     * @param  App\Entity\UserInterface $user
     * @return bool
     */
    abstract protected function canGet(EntityInterface $subject, UserInterface $user) : bool;

    /**
     * Checks whether the user can put the subject.
     *
     * @param  App\Entity\EntityInterface $subject
     * @param  App\Entity\UserInterface $user
     * @return bool
     */
    abstract protected function canPut(EntityInterface $subject, UserInterface $user) : bool;

    /**
     * Checks whether the user can delete the subject.
     *
     * @param  App\Entity\EntityInterface $subject
     * @param  App\Entity\UserInterface $user
     * @return bool
     */
    abstract protected function canDelete(EntityInterface $subject, UserInterface $user) : bool;

    /**
     * Checks whether this voter supports the subject.
     *
     * @param  App\Entity\EntityInterface $subject
     * @return bool
     */
    abstract protected function supportsSubject(EntityInerface $subject) : bool;
}
