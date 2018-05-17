<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * The  entity base constraint.
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class Entity extends Constraint
{
    /**
     * @var string $entityClass
     */
    public $entityClass;

    /**
     * @var string $primaryKey
     */
    public $primaryKey;

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
            'primaryKey',
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

