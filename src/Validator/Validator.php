<?php

namespace App\Validator;

use App\Entity\EntityInterface;
use App\Validator\ValidatorInterface as AppValidatorInterface;
use App\Exception\EntityNotValidException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
    protected $parameters;

    /**
     * @var Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    protected $validator;

    /**
     * @var Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * Constructs the validator.
     *
     * @param array $parameters
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        array                  $parameters,
        ValidatorInterface     $validator,
        LoggerInterface        $logger
    )
    {
        $this->parameters    = $parameters;
        $this->validator     = $validator;
        $this->logger        = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(EntityInterface $entity, $constraints = null, $groups = null) : ConstraintViolationListInterface
    {
        return $this->validator
            ->validate($entity, $constraints, $groups)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function validateWithException(EntityInterface $entity, $constraints = null, $groups = null)
    {
        $entityConstraintViolationList = $this->validate($entity, $constraints, $groups);

        if ($entityConstraintViolationList->count() > 0) {
            throw new EntityNotValidException(
                (string) $entityConstraintViolationList
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateWithExceptionAndLog(EntityInterface $entity, $constraints = null, $groups = null)
    {
        try {
            $this->validateWithException($entity, $constraints, $groups);

        } catch (EntityNotValidException $exception) {
            $this->logger->critical(
                sprintf(
                    'Invalid %s',
                    get_class($entity)
                ),
                array(
                    'exception'          => $exception,
                    'entity'             => $entity,
                    'entity_constraints' => $constraints,
                    'entity_groups'      => $groups,
                )
            );

            throw $exception;
        }
    }
}

