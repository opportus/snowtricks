<?php

namespace App\EventListener;

use App\Security\AuthorableInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * The authorizer listener...
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class AuthorizerListener
{
    /**
     * @var Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    protected $tokenStorage;

    /**
     * Constucts the authorizer listener.
     *
     * @param Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Authorizes the data on form submit.
     *
     * @param Symfony\Component\Form\FormEvent $event
     */
    public function onFormSubmit(FormEvent $event)
    {
        $authorable   = $event->getData();
        $accessMethod = $event->getForm()->getConfig()->getMethod();

        if ((! $authorable instanceof AuthorableInterface) || $accessMethod !== 'POST') {
            return;
        }

        $author = $this->tokenStorage->getToken()->getUser();

        $authorable->setAuthor($author);
    }
}

