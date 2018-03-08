<?php

namespace App\HttpKernel;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The exception handler interface...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ExceptionHandlerInterface
{
    /**
     * Handles the exception.
     *
     * @param  \Exception $exception
     * @return Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handleException(\Exception $exception) : HttpException;

    /**
     * Converts system's exception into appropriate HTTP exception.
     *
     * @param  \Exception $exception
     * @return Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function convertToHttpException(\Exception $exception) : HttpException;

    /**
     * Logs server error exceptions.
     *
     * @param  Symfony\Component\HttpKernel\Exception\HttpException $exception
     * @return App\HttpKernel\ExceptionHandlerInterface
     */
    public function logServerErrorException(HttpException $exception) : ExceptionHandlerInterface;
}

