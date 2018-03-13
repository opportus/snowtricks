<?php

namespace App\EventListener;

use App\Form\Type\TrickCommentType;
use App\Form\Type\TrickType;
use App\Form\Type\UserType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * The form pre set data listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class FormPreSetDataListener
{
    /**
     * Listens on form pre submit event.
     *
     * @param Symfony\Component\Form\FormEvent $event
     */
    public function onFormPreSetData(FormEvent $event)
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

        if ($form instanceof TrickCommentType && $form->getConfig()->getMethod() === 'GET' && $event->get) {
            $thread = $this->entityManager->getRepository(Trick::class)->findOne(array('id' => $this->requestStack->getCurrentRequest()->attributes->get('id')));
            $event->setData(array('thread' => $thread));
        }
    }
}

