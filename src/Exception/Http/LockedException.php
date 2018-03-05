<?php

namespace App\Exception\Http;

/**
 * The locked exception...
 *
 * @version 0.0.1
 * @package App\Exception\Http
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class LockedException extends ClientException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = '', $code = 423, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

