<?php

namespace App\Security;

use App\Entity\Entity;
use App\Security\AuthorizableInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * The entity access voter.
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class EntityAccessVoter extends Voter
{
    const GET    = 'GET';
    const POST   = 'POST';
    const PUT    = 'PUT';
    const PATCH  = 'PATCH';
    const DELETE = 'DELETE';

    /**
     * @var Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    protected $requestStack;

    /**
     * Constructs the entity acces voter.
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
        if (!in_array($attribute, array(self::GET, self::POST, self::PUT, self::PATCH, self::DELETE))) {
            return false;
        }

        if (!$subject instanceof Entity) {
            return false;
        }

        if ($this->supportsSubject($subject) === false) {
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
            case self::POST:
                return $this->canPost($subject, $token);
            case self::PUT:
                return $this->canPut($subject, $token);
            case self::PATCH:
                return $this->canPatch($subject, $token);
            case self::DELETE:
                return $this->canDelete($subject, $token);
        }
    }

    /**
     * Checks whether the user can get the subject.
     *
     * @param  App\Entity\Entity $subject
     * @param  Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    abstract protected function canGet(Entity $subject, TokenInterface $token) : bool;

    /**
     * Checks whether the user can post the subject.
     *
     * @param  App\Entity\Entity $subject
     * @param  Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    abstract protected function canPost(Entity $subject, TokenInterface $token) : bool;

    /**
     * Checks whether the user can put the subject.
     *
     * @param  App\Entity\Entity $subject
     * @param  Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    abstract protected function canPut(Entity $subject, TokenInterface $token) : bool;

    /**
     * Checks whether the user can patch the subject.
     *
     * @param  App\Entity\Entity $subject
     * @param  Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    abstract protected function canPatch(Entity $subject, TokenInterface $token) : bool;

    /**
     * Checks whether the user can delete the subject.
     *
     * @param  App\Entity\Entity $subject
     * @param  Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    abstract protected function canDelete(Entity $subject, TokenInterface $token) : bool;

    /**
     * Checks whether this voter supports the subject.
     *
     * @param  App\Entity\Entity $subject
     * @return bool
     */
    abstract protected function supportsSubject(Entity $subject) : bool;
}
