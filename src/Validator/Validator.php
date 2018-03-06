<?php

namespace App\Validator;

use App\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Validator\TraceableValidator;
use Symfony\Component\Validator\Exception\ValidatorException;
use Psr\Log\LoggerInterface;

/**
 * The validator...
 *
 * @version 0.0.1
 * @package App\Validator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class Validator extends TraceableValidator implements ValidatorInterface
{
    /**
     * @var Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * Constructs the validator.
     *
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(SymfonyValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger    = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function validateWithException($value, $constraints = null, $groups = null, string $exception = ValidatorException::class)
    {
        $constraintViolationList = $this->validate($value, $constraints, $groups);

        if ($constraintViolationList->count() > 0) {
            throw new $exception(
                (string) $constraintViolationList
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateWithExceptionAndLog($value, $constraints = null, $groups = null, string $exception = ValidatorException::class)
    {
        try {
            $this->validateWithException($value, $constraints, $groups, $exception);

        } catch (\Exception $e) {
            $this->logger->critical(
                sprintf(
                    'A % has occured',
                    get_class($e)
                ),
                array(
                    'exception'   => $e,
                    'value'       => $value,
                    'constraints' => $constraints,
                    'groups'      => $groups,
                )
            );

            throw $e;
        }
    }
}

