<?php

namespace App\HttpKernel;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * The exception handler...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function convertToHttpException(\Exception $exception) : HttpException
    {
        if (!$exception instanceof HttpException) {
            if ($exception instanceof UniqueConstraintViolationException) {
                $exception = new ConflictHttpException(
                    $exception->getMessage(),
                    $exception
                );
            } elseif ($exception instanceof AccessDeniedException) {
                $exception = new AccessDeniedHttpException(
                    $exception->getMessage(),
                    $exception
                );
            } else {
                $exception = new HttpException(
                    500,
                    $exception->getMessage(),
                    $exception
                );
            }
        }

        return $exception;
    }
}
