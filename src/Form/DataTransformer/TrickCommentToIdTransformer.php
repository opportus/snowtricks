<?php

namespace App\Form\DataTransformer;

use App\Entity\TrickComment;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The trick comment to ID transformer.
 *
 * @version 0.0.1
 * @package App\Form\DataTransformer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentToIdTransformer implements DataTransformerInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * Constructs the trick comment to ID transformer.
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

        if (!$value instanceof TrickComment) {
            throw new TransformationFailedException(
                sprintf(
                    'The value must be an instance of %s',
                    TrickComment::class
                )
            );
        }

        return $value->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $trickComment = $this->entityManager->getRepository(TrickComment::class)
            ->findOneById($value)
        ;

        if ($trickComment === null) {
            throw new TransformationFailedException(
                sprintf(
                    'Trick comment %s not found',
                    $value
                )
            );
        }

        return $trickComment;
    }
}
