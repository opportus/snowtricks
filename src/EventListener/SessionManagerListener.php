<?php

namespace App\EventListener;

use App\HttpFoundation\SessionManagerInterface;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * The session manager listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class SessionManagerListener
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
     * Listens on kernel view.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        if (!\is_object($controllerResult) || !$controllerResult instanceof ControllerResult) {
            return;
        }

        if (null === $flashAnnotations = $event->getRequest()->attributes->get('_flash')) {
            return;
        }

        foreach ($flashAnnotations as $flashAnnotation) {
            if ($flashAnnotation->getStatusCode() === $controllerResult->getStatusCode()) {
                $this->sessionManager->generateFlash($flashAnnotation, $controllerResult);
            }
        }
    }
}
