<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * The persisted entity constraint validator.
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class PersistedEntityValidator extends EntityValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($data, Constraint $constraint)
    {
        if (! $constraint instanceof PersistedEntity) {
            throw new UnexpectedTypeException();
        }

        $getter = 'get' . $constraint->primaryKey;
        $value  = $data->$getter();

        if ($this->entityManager->getRepository($constraint->entityClass)->findOneBy(array($constraint->primaryKey => $value))) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->setTranslationDomain('messages')
            ->atPath($constraint->primaryKey)
            ->addViolation()
        ;
    }
}

