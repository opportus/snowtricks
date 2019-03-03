<?php

namespace App\Validator;

use App\Validator\ValidatorInterface as AppValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
class Validator implements AppValidatorInterface
{
    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * @var Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    private $validator;

    /**
     * @var Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * Constructs the validator.
     *
     * @param array $parameters
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(array $parameters, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->parameters = $parameters;
        $this->validator  = $validator;
        $this->logger     = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function validateWithException($value, $constraints = null, $groups = null, string $exception = ValidatorException::class)
    {
        $constraintViolationList = $this->validator->validate($value, $constraints, $groups);

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
