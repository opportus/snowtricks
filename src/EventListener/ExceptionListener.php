<?php

namespace App\EventListener;

use App\HttpFoundation\ResponseFactoryInterface;
use App\HttpKernel\ExceptionHandlerInterface;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * The exception listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExceptionListener
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
     * Constructs the exception listener.
     *
     * @param App\HttpKernel\ExceptionHandlerInterface $exceptionHandler
     * @param App\HttpFoundation\ResponseFactoryInterface $responseFactory
     */
    public function __construct(ExceptionHandlerInterface $exceptionHandler, ResponseFactoryInterface $responseFactory)
    {
        $this->exceptionHandler = $exceptionHandler;
        $this->responseFactory  = $responseFactory;
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

        if ($event->getException()->getStatusCode()[0] === 4) {
            $response = $this->responseFactory->createResponse(
                $event->getRequest(),
                new ControllerResult(
                    $event->getException()->getStatusCode(),
                    array('exception' => $event->getException())
                )
            );

            $event->setResponse($response);
        }
    }
}

