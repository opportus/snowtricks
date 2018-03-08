<?php

namespace App\EventListener;

use App\HttpKernel\ResponseBuilderInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * The view listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ViewListener
{
    /**
     * @var App\HttpKernel\ResponseBuilderInterface $responseBuilder
     */
    protected $responseBuilder;

    /**
     * Constructs the exception listener.
     *
     * @param App\HttpKernel\ResponseGeneratorInterface $responseBuilder
     */
    public function __construct(ResponseBuilderInterface $responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * Listens on kernel view.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $event->setResponse(
            $this->responseBuilder->buildResponseFromGetResponseForControllerResultEvent($event)
        );
    }
}

