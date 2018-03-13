<?php

namespace App\EventListener;

use App\Form\Type\TrickCommentType;
use App\Form\Type\TrickType;
use App\Form\Type\UserType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * The form pre submit listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class FormPreSubmitListener
{
    /**
     * Listens on form pre submit event.
     *
     * @param Symfony\Component\Form\FormEvent $event
     */
    public function onFormPreSubmit(FormEvent $event)
    {
        $this->populateForm($event);
    }

    /**
     * Populates the form.
     *
     * @param Symfony\Component\Form\FormEvent $event
     */
    private function populateForm(FormEvent $event)
    {
        $form = $event->getForm();

        if ($form instanceof TrickCommentType && $form->getConfig()->getMethod() === 'POST') {
            $form->get('author')->setData((string) $this->tokenStorage->getToken()->getUser()->getId());

        } elseif ($form instanceof UserType && $form->getConfig()->getMethod() === 'POST') {

        }
    }
}

