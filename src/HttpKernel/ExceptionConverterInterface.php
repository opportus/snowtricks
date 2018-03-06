<?php

namespace App\HttpKernel;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The exception converter interface...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ExceptionConverterInterface
{
    /**
     * Converts.
     *
     * @param  \Exception $exception
     * @return Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function convert(\Exception $exception) : HttpException;
}

