<?php

namespace App\HttpKernel;

use Psr\Log\LoggerInterface;
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
     * @var Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * Constructs the exception handler.
     *
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handleException(\Exception $exception) : HttpException
    {
        $exception = $this->convertToHttpException($exception);

        $this->logServerErrorException($exception);

        return $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToHttpException(\Exception $exception) : HttpException
    {
        if (! $exception instanceof HttpException) {
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
                    $excetion->getMessage(),
                    $exception
                );
            }
        }

        return $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function logServerErrorException(HttpException $exception) : ExceptionHandlerInterface
    {
        if ($exception->getStatusCode()[0] === 5) {
            $this->logger->critical(
                sprintf(
                    'A server error exception has occured'
                ),
                array(
                    'exception' => $exception
                )
            );
        }

        return $this;
    }
}

