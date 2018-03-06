<?php

namespace App\HttpKernel;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * The exception converter...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExceptionConverter implements ExceptionConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert(\Exception $exception) : HttpException
    {
        if ($exception instanceof HttpException) {
            return $exception;
        }

        if ($exception instanceof UniqueConstraintViolationException) {
            return new ConflictHttpException(
                $exception->getMessage(),
                $exception
            );

        } else {
            return new HttpException(
                $excetion->getMessage(),
                $exception
            );
        }
    }
}

