<?php

namespace App\HttpKernel;

/**
 * The controller result interface.
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ControllerResultInterface
{
    /**
     * Gets the status code.
     * 
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Gets the data.
     * 
     * @return mixed
     */
    public function getData();
}
