<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
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
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return Symfony\Component\HttpFoundation\Response
     * @throws App\Exception\ConfigurationException
     */
    public function build(Request $request, ControllerResultInterface $controllerResult): Response;
}
