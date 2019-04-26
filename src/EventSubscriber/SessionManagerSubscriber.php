<?php

namespace App\EventSubscriber;

use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerException;
use App\HttpFoundation\SessionManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * The session manager subscriber.
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class SessionManagerSubscriber implements EventSubscriberInterface
{
    /**
     * @var App\HttpFoundation\SessionManagerInterface $sessionManager
     */
    private $sessionManager;

    /**
     * Constructs the session manager listener.
     *
     * @param App\HttpFoundation\SessionManagerInterface $sessionManager
     */
    public function __construct(SessionManagerInterface $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => [
                ['generateFlashOnKernelView', 50],
            ],
            KernelEvents::EXCEPTION => [
                ['generateFlashOnKernelException', 50],
            ],
        );
    }

    /**
     * Generates the flash on kernel view.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function generateFlashOnKernelView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        if (!\is_object($controllerResult) || !$controllerResult instanceof ControllerResult) {
            return;
        }

        if (null === $flashConfigurations = $event->getRequest()->attributes->get('_flash_configurations')) {
            return;
        }

        foreach ($flashConfigurations as $flashConfiguration) {
            if ($flashConfiguration->getStatusCode() === $controllerResult->getStatusCode()) {
                $this->sessionManager->generateFlash($flashConfiguration, $controllerResult);
            }
        }
    }

    /**
     * Generates the flash on kernel exception.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function generateFlashOnKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!$exception instanceof ControllerException) {
            return;
        }

        if (null === $flashConfigurations = $event->getRequest()->attributes->get('_flash_configurations')) {
            return;
        }

        foreach ($flashConfigurations as $flashConfiguration) {
            if ($flashConfiguration->getStatusCode() === $exception->getStatusCode()) {
                $this->sessionManager->generateFlash($flashConfiguration, $exception);
            }
        }
    }
}
