<?php

namespace App\HttpKernel;

/**
 * The controller result...
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
     * @var array $data
     */
    private $data;

    /**
     * Constructs the controller result.
     *
     * @param int $statusCode
     * @param array $data
     */
    public function __construct(int $statusCode, array $data = array())
    {
        $this->statusCode = $statusCode;
        $this->data       = $data;
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
    public function getData() : array
    {
        return $this->data;
    }
}
