<?php

namespace App\EventListener;

use App\HttpFoundation\ResponseFactoryInterface;
use App\HttpFoundation\SessionManagerInterface;
use App\HttpKernel\ExceptionHandlerInterface;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * The exception handler listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExceptionHandlerListener
{
    /**
     * @var App\HttpKernel\ExceptionHandlerInterface $exceptionHandler
     */
    protected $exceptionHandler;

    /**
     * @var App\HttpFoundation\ResponseFactoryInterface $responseFactory
     */
    protected $responseFactory;

    /**
     * @var App\HttpFoundation\SessionManagerInterface $sessionManager
     */
    protected $sessionManager;

    /**
     * Constructs the exception handler listener.
     *
     * @param App\HttpKernel\ExceptionHandlerInterface $exceptionHandler
     * @param App\HttpFoundation\ResponseFactoryInterface $responseFactory
     * @param App\HttpFoundation\SessionManagerInterface $sessionManager
     */
    public function __construct(
        ExceptionHandlerInterface $exceptionHandler,
        ResponseFactoryInterface  $responseFactory,
        SessionManagerInterface   $sessionManager
    )
    {
        $this->exceptionHandler = $exceptionHandler;
        $this->responseFactory  = $responseFactory;
        $this->sessionManager   = $sessionManager;
    }

    /**
     * Listens on kernel exception.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $event->setException(
            $this->exceptionHandler->handleException($event->getException())
        );

        if ($event->getException()->getStatusCode() >= 400 && $event->getException()->getStatusCode() < 500) {
            $controllerResult = new ControllerResult(
                $event->getException()->getStatusCode(),
                array('exception' => $event->getException())
            );

            $this->sessionManager->generateFlash(
                $event->getRequest(),
                $controllerResult
            );

            $event->setResponse($this->responseFactory->createResponse(
                $event->getRequest(),
                $controllerResult
            ));
        }
    }
}

