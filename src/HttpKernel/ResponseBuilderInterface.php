<?php

namespace App\HttpKernel;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response builder interface...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ResponseBuilderInterface
{
    /**
     * Builds the response from GetResponseForExceptionEvent.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function buildFromGetResponseForExceptionEvent(GetResponseForExceptionEvent $event) : Response;

    /**
     * Builds the response from GetResponseForControllerResultEvent.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function buildFromGetResponseForControllerResultEvent(GetResponseForControllerResultEvent $event) : Response;
}

