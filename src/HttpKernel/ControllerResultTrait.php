<?php

namespace App\HttpKernel;

/**
 * The controller result trait.
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
trait ControllerResultTrait
{
    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var mixed $data
     */
    private $data;

    /**
     * Gets the status code.
     * 
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Gets the data.
     * 
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
