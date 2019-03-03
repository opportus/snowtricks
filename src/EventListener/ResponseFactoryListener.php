<?php

namespace App\EventListener;

use App\HttpFoundation\ResponseFactoryInterface;
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
    private $responseFactory;

    /**
     * Constructs the response factory listener.
     *
     * @param App\HttpFoundation\ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
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

        $event->setResponse($this->responseFactory->createResponse(
            $event->getRequest(),
            $event->getControllerResult()
        ));
    }
}
