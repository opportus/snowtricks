<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The session manager interface...
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface SessionManagerInterface
{
    /**
     * Generates the flash message.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     */
    public function generateFlash(Request $request, ControllerResultInterface $controllerResult);
}

