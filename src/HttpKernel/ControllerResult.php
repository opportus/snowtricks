<?php

namespace App\HttpKernel;

/**
 * The controller result.
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ControllerResult implements ControllerResultInterface
{
    use ControllerResultTrait;
    
    /**
     * Constructs the controller result.
     *
     * @param int $statusCode
     * @param mixed $data
     */
    public function __construct(int $statusCode, $data = null)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }
}
