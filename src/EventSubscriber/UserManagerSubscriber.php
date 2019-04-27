<?php

namespace App\EventSubscriber;

use App\Entity\UserToken;
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
            KernelEvents::VIEW => [
                ['onUserSignUp', 100],
                ['onUserActivation', 100],
                ['onUserPasswordResetRequest', 100],
                ['onUserPasswordReset', 100],
            ],
        );
    }

    /**
     * On user sign up.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onUserSignUp(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== UserController::class.'::postUserBySignUpForm' ||
            $event->getControllerResult()->getStatusCode() !== Response::HTTP_SEE_OTHER
        ) {
            return;
        }

        $user = $event->getControllerResult()->getData();

        $activationToken = new UserToken($user, UserToken::ACTIVATION_TYPE);
        $user->addToken($activationToken);
        $this->entityManager->flush();

        $this->mailer->sendUserActivationEmail($user);
        $this->mailer->sendUserSignUpEmail($user);
    }

    /**
     * On user activation.
     * 
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onUserActivation(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== UserController::class.'::patchUserByActivationEmailForm' ||
            ($event->getControllerResult()->getStatusCode() !== Response::HTTP_SEE_OTHER &&
            $event->getControllerResult()->getStatusCode() !== Response::HTTP_FORBIDDEN)
        ) {
            return;
        }

        $user = $event->getControllerResult()->getData();
        $activationToken = $user->getLastActivationToken();

        if (null === $activationToken) {
            return;
        }

        $user->removeToken($activationToken);

        $this->entityManager->flush();
    }

    /**
     * On user password reset request.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onUserPasswordResetRequest(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== UserController::class.'::proceedByUserPasswordResetRequestForm' ||
            $event->getControllerResult()->getStatusCode() !== Response::HTTP_SEE_OTHER
        ) {
            return;
        }

        $user = $event->getControllerResult()->getData();

        $passwordResetToken = new UserToken($user, UserToken::PASSWORD_RESET_TYPE);
        $user->addToken($passwordResetToken);
        $this->entityManager->flush();

        $this->mailer->sendUserPasswordResetEmail($user);
    }

    /**
     * On user password reset.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onUserPasswordReset(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequest()->attributes->get('_controller') !== UserController::class.'::patchUserByPasswordResetForm' ||
            ($event->getControllerResult()->getStatusCode() !== Response::HTTP_SEE_OTHER &&
            $event->getControllerResult()->getStatusCode() !== Response::HTTP_FORBIDDEN)
        ) {
            return;
        }

        $user = $event->getControllerResult()->getData();
        $passwordResetToken = $user->getLastPasswordResetToken();

        if (null === $passwordResetToken) {
            return;
        }

        $user->removeToken($passwordResetToken);

        $this->entityManager->flush();
    }
}
