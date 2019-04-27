<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The persisted entity constraint validator.
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class PersistedEntityValidator extends ConstraintValidator
{
    /**
     * @var Doctrine\ORM\EntityManagerIntreface $entityManager
     */
    private $entityManager;

    /**
     * Constructs the persisted entity constraint validator.
     *
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($data, Constraint $constraint)
    {
        if (!$constraint instanceof PersistedEntity) {
            throw new UnexpectedTypeException($constraint, PersistedEntity::class);
        }

        if ($this->entityManager->getRepository($constraint->entityClass)->findOneBy([$constraint->entityIdentifier => $data->{$constraint->entityIdentifier}])) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setTranslationDomain('messages')
            ->atPath($constraint->entityIdentifier)
            ->addViolation()
        ;
    }
}
