<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * The exception handler listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExceptionHandlerListener
{
    /**
     * @var App\HttpKernel\ExceptionHandlerInterface[] $exceptionHandlers
     */
    private $exceptionHandlers;

    /**
     * Constructs the exception handler listener.
     *
     * @param App\HttpKernel\ExceptionHandlerInterface[] $exceptionHandler
     */
    public function __construct(array $exceptionHandlers)
    {
        $this->exceptionHandlers = $exceptionHandlers;
    }

    /**
     * Listens on kernel exception.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        foreach ($this->exceptionHandlers as $exceptionHandler) {
            if ($exceptionHandler->supports($event->getException())) {
                $event->setResponse($exceptionHandler->handle($event->getRequest()));

                return;
            }
        }
    }
}
