<?php

namespace App\Validator;

use App\Entity\EntityInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * The validator interface...
 *
 * @version 0.0.1
 * @package App\Validator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ValidatorInterface
{
    /**
     * Validates the entity.
     *
     * @param  App\Entity\EntityInterface $entity
     * @param  null|array|Symfony\Component\Validator\Constraint $constraints
     * @param  null|array $groups
     * @return Symfony\Component\Validator\ConstraintViolationListInterface
     */
    public function validate(EntityInterface $entity, $constraints = null, $groups = null) : ConstraintViolationListInterface;

    /**
     * Validates the entity by throwing an exception if it's not valid.
     *
     * @param  App\Entity\EntityInterface $entity
     * @param  null|array|Symfony\Component\Validator\Constraint $constraints
     * @param  null|array $groups
     * @throws App\Exception\EntityNotValidException
     */
    public function validateWithException(EntityInterface $entity, $constraints = null, $groups = null);

    /**
     * Validates the entity by throwing an exception and logging if it's not valid.
     *
     * @param  App\Entity\EntityInterface $entity
     * @param  null|array|Symfony\Component\Validator\Constraint $constraints
     * @param  null|array $groups
     * @throws App\Exception\EntityNotValidException
     */
    public function validateWithExceptionAndLog(EntityInterface $entity, $constraints = null, $groups = null);
}

