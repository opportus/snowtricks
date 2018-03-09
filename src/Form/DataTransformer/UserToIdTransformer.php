<?php

namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Entity\UserInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The user to ID transformer...
 *
 * @version 0.0.1
 * @package App\Form\DataTransformer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserToIdTransformer implements DataTransformerInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * Constructs the user to ID transformer.
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
    public function transform($value)
    {
        if ($value === null) {
            return '';
        }

        if (! $value instanceof UserInterface) {
            throw new TransformationFailedException(
                sprintf(
                    'The value must be an instance of %s',
                    UserInterface::class
                )
            );
        }

        return (string) $value->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (! $value) {
            return null;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneById((int) $value)
        ;

        if ($user === null) {
            throw new TransformationFailedException(
                sprintf(
                    'User %s not found',
                    $value
                )
            );
        }

        return $user;
    }
}

