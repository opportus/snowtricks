<?php

namespace App\EventListener;

use App\HttpKernel\ExceptionHandlerInterface;
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
    private $exceptionHandler;

    /**
     * Constructs the exception handler listener.
     *
     * @param App\HttpKernel\ExceptionHandlerInterface $exceptionHandler
     */
    public function __construct(ExceptionHandlerInterface $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }

    /**
     * Listens on kernel exception.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $event->setException(
            $this->exceptionHandler->convertToHttpException($event->getException())
        );
    }
}
