<?php

namespace App\HttpKernel;

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
     * Builds the response from the GetResponseForControllerResultEvent.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function buildResponseFromGetResponseForControllerResultEvent(GetResponseForControllerResultEvent $event) : Response;
}

