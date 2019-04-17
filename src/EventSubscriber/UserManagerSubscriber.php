<?php

namespace App\EventSubscriber;

use App\Mailer\MailerInterface;
use App\Controller\UserController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The user manager subscriber.
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
        $this->mailer = $mailer;
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
     * Proceeds the user sign up.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function proceedUserSignUp(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== UserController::class.'::postUserBySignUpForm' ||
            $event->getControllerResult()->getStatusCode() !== Response::HTTP_SEE_OTHER
        ) {
            return;
        }

        $user = $event->getControllerResult()->getData();

        $this->mailer->sendUserSignUpEmail($user);

        $user->createActivationToken();

        $this->entityManager->flush();

        $this->mailer->sendUserActivationEmail($user);
    }

    /**
     * Proceeds the user password reset.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function proceedUserPasswordReset(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== UserController::class.'::proceedByUserPasswordResetRequestForm' ||
            $event->getControllerResult()->getStatusCode() !== Response::HTTP_SEE_OTHER
        ) {
            return;
        }

        $user = $event->getControllerResult()->getData();

        $user->createPasswordResetToken();

        $this->entityManager->flush();

        $this->mailer->sendUserPasswordResetEmail($user);
    }
}
