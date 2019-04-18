<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response builder interface.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ResponseBuilderInterface
{
    /**
     * Builds the response.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResult $controllerResult
     * @return Symfony\Component\HttpFoundation\Response
     * @throws App\Exception\ResponseBuildingException
     */
    public function build(Request $request, ControllerResult $controllerResult): Response;
}