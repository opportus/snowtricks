<?php

namespace App\Form\DataTransformer;

use App\Entity\Trick;
use App\Entity\TrickInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The trick to ID transformer...
 *
 * @version 0.0.1
 * @package App\Form\DataTransformer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickToIdTransformer implements DataTransformerInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * Constructs the trick to ID transformer.
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

        if (! $value instanceof TrickInterface) {
            throw new TransformationFailedException(
                sprintf(
                    'The value must be an instance of %s',
                    TrickInterface::class
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

        $trick = $this->entityManager->getRepository(Trick::class)
            ->findOneById((int) $value)
        ;

        if ($trick === null) {
            throw new TransformationFailedException(
                sprintf(
                    'Trick %s not found',
                    $value
                )
            );
        }

        return $trick;
    }
}

