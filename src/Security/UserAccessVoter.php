<?php

namespace App\Security;

use App\Entity\Entity;
use App\Entity\User;
use App\Controller\UserController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * The user access voter.
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserAccessVoter extends EntityAccessVoter
{
    /**
     * {@inheritdoc}
     */
    protected function canGet(Entity $subject, TokenInterface $token) : bool
    {
        return $subject->getUsername() === $token->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    protected function canPost(Entity $subject, TokenInterface $token) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function canPut(Entity $subject, TokenInterface $token) : bool
    {
        return $subject->getUsername() === $token->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    protected function canPatch(Entity $subject, TokenInterface $token) : bool
    {
        if ($this->requestStack->getCurrentRequest()->attributes->get('_controller') === UserController::class.'::patchUserByActivationEmailForm') {
            $token = $subject->getLastActivationToken();

            if ($token === null) {
                return false;
            }

            $requestToken = $this->requestStack->getCurrentRequest()->request->get('user_activation_email')['token'];

            if (null === $requestToken) {
                return false;
            }

            return \hash_equals((string)$token, $requestToken) && !$token->isExpired();
        }

        if ($this->requestStack->getCurrentRequest()->attributes->get('_controller') === UserController::class.'::patchUserByPasswordResetForm') {
            $token = $subject->getLastPasswordResetToken();

            if ($token === null) {
                return false;
            }

            $requestToken = $this->requestStack->getCurrentRequest()->query->get('user_password_reset_email')['token'];

            if (null === $requestToken) {
                return false;
            }

            return \hash_equals((string)$token, $requestToken) && !$token->isExpired();
        }

        return $subject->getUsername() === $token->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    protected function canDelete(Entity $subject, TokenInterface $token) : bool
    {
        return $subject->getUsername() === $token->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject(Entity $subject) : bool
    {
        return $subject instanceof User;
    }
}
