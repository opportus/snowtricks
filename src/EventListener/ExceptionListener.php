<?php

namespace App\EventListener;

use App\HttpKernel\ExceptionHandlerInterface;
use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Events;

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
     * @var Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Constructs the exception listener.
     *
     * @param App\HttpKernel\ExceptionHandlerInterface $exceptionHandler
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ExceptionHandlerInterface $exceptionHandler, EventDispatcherInterface $eventDispatcher)
    {
        $this->exceptionHandler = $exceptionHandler;
        $this->eventDispatcher  = $eventDispatcher;
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
            $viewEvent = new GetResponseForControllerResultEvent(
                $event->getKernel(),
                $event->getRequest(),
                $event->getRequestType(),
                new ControllerResult(
                    $event->getException()->getStatusCode(),
                    array('exception' => $event->getException())
                )
            );

            $this->eventDispatcher->dispatch(Events::VIEW, $viewEvent);

            $event->setResponse(
                $viewEvent->getResponse()
            );
        }
    }
}

