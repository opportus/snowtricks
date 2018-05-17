<?php

namespace App\EventListener;

use App\HttpFoundation\ResponseFactoryInterface;
use App\HttpFoundation\SessionManagerInterface;
use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * The response factory listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ResponseFactoryListener
{
    /**
     * @var App\HttpFoundation\ResponseFactoryInterface $responseBuilder
     */
    protected $responseFactory;

    /**
     * @var App\HttpFoundation\SessionManagerInterface $sessionManager
     */
    protected $sessionManager;

    /**
     * Constructs the response factory listener.
     *
     * @param App\HttpFoundation\ResponseFactoryInterface $responseFactory
     * @param App\HttpFoundation\SessionManagerInterface $sessionManager
     */
    public function __construct(ResponseFactoryInterface $responseFactory, SessionManagerInterface $sessionManager)
    {
        $this->responseFactory = $responseFactory;
        $this->sessionManager  = $sessionManager;
    }

    /**
     * Listens on kernel view.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if (! $event->getControllerResult() instanceof ControllerResultInterface) {
            return;
        }

        $this->sessionManager->generateFlash(
            $event->getRequest(),
            $event->getControllerResult()
        );

        $event->setResponse($this->responseFactory->createResponse(
            $event->getRequest(),
            $event->getControllerResult()
        ));
    }
}

