<?php

namespace App\HttpFoundation;

use App\Configuration\Flash as FlashConfiguration;
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
     * @param App\Configuration\FlashConfiguration $flashConfiguration
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     */
    public function generateFlash(FlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult);
}
