<?php

namespace App\EventListener;

use App\HttpFoundation\ResponseBuilderInterface;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * The response builder listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ResponseBuilderListener
{
    /**
     * @var App\HttpFoundation\ResponseBuilderInterface $responseBuilder
     */
    private $responseBuilder;

    /**
     * Constructs the response builder listener.
     *
     * @param App\HttpFoundation\ResponseBuilderInterface $responseBuilder
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
        if (!$event->getControllerResult() instanceof ControllerResult) {
            return;
        }

        $event->setResponse($this->responseBuilder->build(
            $event->getRequest(),
            $event->getControllerResult()
        ));
    }
}
