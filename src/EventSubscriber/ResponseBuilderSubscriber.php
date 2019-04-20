<?php

namespace App\EventSubscriber;

use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerException;
use App\HttpFoundation\ResponseBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * The response builder subscriber.
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ResponseBuilderSubscriber implements EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => [
                ['buildOnKernelView', 0],
            ],
            KernelEvents::EXCEPTION => [
                ['buildOnKernelException', 0],
            ],
        );
    }

    /**
     * Builds a response on the kernel view.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function buildOnKernelView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        if (!\is_object($controllerResult) || !$controllerResult instanceof ControllerResult) {
            return;
        }

        $event->setResponse($this->responseBuilder->build(
            $event->getRequest(),
            $controllerResult
        ));
    }

    /**
     * Builds a response on the kernel exception.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function buildOnKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!$exception instanceof ControllerException) {
            return;
        }

        $event->setResponse($this->responseBuilder->build(
            $event->getRequest(),
            $exception
        ));
    }
}
