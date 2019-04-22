<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * The persisted entity constraint.
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class PersistedEntity extends Constraint
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
