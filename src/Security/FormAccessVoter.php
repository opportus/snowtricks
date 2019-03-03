<?php

namespace App\Security;

use Symfony\Component\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * The form access voter...
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class FormAccessVoter extends Voter
{
    const GET = 'GET';

    /**
     * @var Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    private $requestStack;

    /**
     * Constructs the user acces voter.
     *
     * @param Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::GET))) {
            return false;
        }

        if (!$subject instanceof FormInterface) {
            return false;
        }

        if (!$this->supportsSubject($subject)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
            case self::GET:
                return $this->canGet($subject, $token);
        }
    }

    /**
     * Checks whether the user can get the subject.
     *
     * @param  Symfony\Component\Form\FormInterface $subject
     * @param  Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    abstract protected function canGet(FormInterface $subject, TokenInterface $token) : bool;

    /**
     * Checks whether this voter supports the subject.
     *
     * @param  Symfony\Component\Form\FormInterface $subject
     * @return bool
     */
    abstract protected function supportsSubject(FormInterface $subject) : bool;
}
