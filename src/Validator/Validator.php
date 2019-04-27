<?php

namespace App\Validator;

use App\Validator\ValidatorInterface as AppValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * The validator.
 *
 * @version 0.0.1
 * @package App\Validator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class Validator implements AppValidatorInterface
{
    /**
     * @var Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    private $validator;

    /**
     * Constructs the validator.
     *
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function validateWithException($value, $constraints = null, $groups = null)
    {
        $constraintViolationList = $this->validator->validate($value, $constraints, $groups);

        if ($constraintViolationList->count() > 0) {
            throw new ValidatorException(
                (string)$constraintViolationList
            );
        }
    }
}
