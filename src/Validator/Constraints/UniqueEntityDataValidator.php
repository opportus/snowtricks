<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The unique entity data constraint validator.
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UniqueEntityDataValidator extends ConstraintValidator
{
    /**
     * @var Doctrine\ORM\EntityManagerIntreface $entityManager
     */
    private $entityManager;

    /**
     * Constructs the unique entity data constraint validator.
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
        if (!$constraint instanceof UniqueEntityData) {
            throw new UnexpectedTypeException($constraint, UniqueEntityData::class);
        }

        foreach ($constraint->data as $attribute) {
            $entity = $this->entityManager->getRepository($constraint->entityClass)->findOneBy([$attribute => $data->{$attribute}]);

            if (null === $entity) {
                continue;
            }

            if (null === $data->{$constraint->entityIdentifier} || $entity->{'get'.\ucfirst($constraint->entityIdentifier)}() !== $data->{$constraint->entityIdentifier}) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%attribute%', $attribute)
                    ->setTranslationDomain('messages')
                    ->atPath($attribute)
                    ->addViolation()
                ;
            }
        }
    }
}
