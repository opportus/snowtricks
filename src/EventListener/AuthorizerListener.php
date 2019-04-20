<?php

namespace App\EventListener;

use App\Entity\Dto\AuthorableInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * The authorizer listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class AuthorizerListener
{
    /**
     * @var Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;

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
        $authorable = $event->getData();

        if (!\is_object($authorable) || !$authorable instanceof AuthorableInterface) {
            return;
        }

        $accessMethod = $event->getForm()->getConfig()->getMethod();
        $authorableAccessMethods = ['POST', 'PUT', 'PATCH'];

        if (!\in_array($accessMethod, $authorableAccessMethods)) {
            return;
        }

        $author = $this->tokenStorage->getToken()->getUser();

        if (null === $author) {
            return;
        }

        $authorable->setAuthor($author);
    }
}
