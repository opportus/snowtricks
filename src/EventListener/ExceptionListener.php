<?php

namespace App\EventListener;

use App\HttpKernel\ExceptionConverterInterface;
use App\HttpKernel\ResponseBuilderInterface;
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
     * @var App\HttpKernel\ExceptionConverterInterface $exceptionConverter
     */
    protected $exceptionConverter;

    /**
     * @var App\HttpKernel\ResponseBuilderInterface $responseBuilder
     */
    protected $responseBuilder;

    /**
     * Constructs the exception listener.
     *
     * @param App\HttpKernel\ExceptionConverterInterface $exceptionConverter
     * @param App\HttpKernel\ResponseGeneratorInterface $responseBuilder
     */
    public function __construct(
        ExceptionConverterInterface $exceptionConverter,
        ResponseBuilderInterface    $responseBuilder
    )
    {
        $this->exceptionConverter = $exceptionConverter;
        $this->responseBuilder    = $responseBuilder;
    }

    /**
     * Listens on kernel exception.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $event->setException(
            $this->exceptionConverter->convert($event->getException())
        );

        $event->setResponse(
            $this->responseBuilder->buildFromGetResponseForExceptionEvent($event)
        );
    }
}

