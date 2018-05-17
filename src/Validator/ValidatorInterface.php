<?php

namespace App\Validator;

use Symfony\Component\Validator\Exception\ValidatorException;

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
     * Validates by throwing an exception.
     *
     * @param  mixed $value
     * @param  null|array|Symfony\Component\Validator\Constraint $constraints
     * @param  null|array $groups
     * @throws mixed
     */
    public function validateWithException($value, $constraints = null, $groups = null, string $exception = ValidatorException::class);

    /**
     * Validates by throwing an exception and logs.
     *
     * @param  mixed $value
     * @param  null|array|Symfony\Component\Validator\Constraint $constraints
     * @param  null|array $groups
     * @throws mixed
     */
    public function validateWithExceptionAndLog($value, $constraints = null, $groups = null, string $exception = ValidatorException::class);
}

