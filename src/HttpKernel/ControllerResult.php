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
    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var mixed $data
     */
    private $data;

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

    /**
     * {@inheritdoc}
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }
}
