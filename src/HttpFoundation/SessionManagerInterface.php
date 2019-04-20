<?php

namespace App\HttpFoundation;

use App\Annotation\Flash as FlashAnnotation;
use App\HttpKernel\ControllerResultInterface;

/**
 * The session manager interface.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface SessionManagerInterface
{
    /**
     * Generates the flash.
     *
     * @param App\Annotation\Flash $flashAnnotation
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     */
    public function generateFlash(FlashAnnotation $flashAnnotation, ControllerResultInterface $controllerResult);
}
