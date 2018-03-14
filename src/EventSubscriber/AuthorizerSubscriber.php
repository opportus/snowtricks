<?php

namespace App\EventSubscriber;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The authorizer subscriber...
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class AuthorizerSubscriber implements EventSubscriberInterface
{
    /**
     * @var Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @var Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    protected $tokenStorage;

    /**
     * Constucts the authorizer subscriber.
     *
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage         = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SUBMIT => array(
                array('checkAuthorizationOnFormSubmit', 10),
                array('setAuthorOnFormSubmit', 0),
            ),
        );
    }

    /**
     * Checks authorization on form submit.
     *
     * @param  Symfony\Component\Form\FormEvent $event
     * @throws Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function checkAuthorizationOnFormSubmit(FormEvent $event)
    {
        $authorizable = $event->getData();

        if (! $authorizable instanceof AuthorizableInterface) {
            return;
        }

        $accessMethod = $event->getForm()->getOptions()->getMethod();

        if (! $this->authorizationChecker->isGranted($accessMethod, $authorizable)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Sets the author on form submit.
     *
     * @param Symfony\Component\Form\FormEvent $event
     */
    public function setAuthorOnFormSubmit(FormEvent $event)
    {
        $authorizable = $event->getData();
        $accessMethod = $event->getForm()->getOptions()->getMethod();

        if (! $authorizable instanceof AuthorizableInterface || $accessMethod !== 'POST') {
            return;
        }

        $author = $this->tokenStorage->getToken()->getUser();

        $authorizable->setAuthor($author);
    }
}

