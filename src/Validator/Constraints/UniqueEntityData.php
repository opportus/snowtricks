<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * The unique entity data constraint.
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class UniqueEntityData extends Constraint
{
    /**
     * @var string $entityClass
     */
    public $entityClass;

    /**
     * @var string $entityIdentifier
     */
    public $entityIdentifier;

    /**
     * @var array $data
     */
    public $data;

    /**
     * @var string $message
     */
    public $message;

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return array(
            'entityClass',
            'entityIdentifier',
            'data',
            'message',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
