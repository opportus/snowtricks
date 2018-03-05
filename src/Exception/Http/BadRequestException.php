<?php

namespace App\Exception\Http;

/**
 * The bad request exception...
 *
 * @version 0.0.1
 * @package App\Exception\Http
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class BadRequestException extends ClientException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = '', $code = 400, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

