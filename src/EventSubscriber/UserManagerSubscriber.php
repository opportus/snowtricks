<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The user manager subscriber...
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserManagerSubscriber implements EventSubscriberInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var App\Mailer\MailerInterface $mailer
     */
    private $mailer;

    /**
     * Constucts the user manager subscriber.
     *
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param App\Mailer\MailerInterface $mailer
     */
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer        = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => array(
                array('proceedUserSignUp', 90),
                array('proceedUserPasswordReset', 90),
            ),
        );
    }

    /**
     * Proceeds user sign up.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @throws \Swift_Exception
     */
    public function proceedUserSignUp(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== 'App\Controller\UserController::postUserBySignUpForm' ||
            $event->getControllerResult()->getStatusCode() !== 201
        ) {
            return;
        }

        $user = $event->getControllerResult()->getData()['entity'];

        $this->mailer->sendUserSignUpEmail($user);

        $user->createActivationToken();

        $this->entityManager->flush();

        $this->mailer->sendUserActivationEmail($user);
    }

    /**
     * Proceeds user password reset.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @throws \Swift_Exception
     */
    public function proceedUserPasswordReset(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== 'App\Controller\UserController::proceedByUserPasswordResetRequestForm' ||
            $event->getControllerResult()->getStatusCode() !== 202
        ) {
            return;
        }

        $form = $event->getControllerResult()->getData()['form'];
        $user = $this->entityManager->getRepository(User::class)->findOneByUsername($form->getData()->username);

        $user->createPasswordResetToken();

        $this->entityManager->flush();

        $event->getControllerResult()->setStatusCode(204);
        $event->getControllerResult()->setData(
            array(
                'entity' => $user,
            )
        );

        $this->mailer->sendUserPasswordResetEmail($user);
    }
}
