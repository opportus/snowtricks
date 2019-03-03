<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * The unique entity constraint validator.
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UniqueEntityValidator extends EntityValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($data, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException();
        }

        $getter = 'get' . $constraint->primaryKey;
        $value  = $data->$getter();

        if ($this->entityManager->getRepository($constraint->entityClass)->findOneBy(array($constraint->primaryKey => $value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setTranslationDomain('messages')
                ->atPath($constraint->primaryKey)
                ->addViolation()
            ;
        }
    }
}
