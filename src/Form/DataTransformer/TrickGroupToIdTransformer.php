<?php

namespace App\Form\DataTransformer;

use App\Entity\TrickGroup;
use App\Entity\TrickGroupInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The trick group to ID transformer...
 *
 * @version 0.0.1
 * @package App\Form\DataTransformer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickGroupToIdTransformer implements DataTransformerInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * Constructs the trick group to ID transformer.
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

        if (! $value instanceof TrickGroupInterface) {
            throw new TransformationFailedException(
                sprintf(
                    'The value must be an instance of %s',
                    TrickGroupInterface::class
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

        $user = $this->entityManager->getRepository(TrickGroup::class)
            ->findOneById($value)
        ;

        if ($trickGroup === null) {
            throw new TransformationFailedException(
                sprintf(
                    'Trick group %s not found',
                    $value
                )
            );
        }

        return $trickGroup;
    }
}

