<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response factory interface...
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ResponseFactoryInterface
{
    /**
     * Creates the response.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function createResponse(Request $request, ControllerResultInterface $controllerResult) : Response;
}

