<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Collection as CollectionConstraint;
use Symfony\Component\Validator\Constraints\Required as RequiredConstraint;
use Symfony\Component\Validator\Constraints\Optional as OptionalConstraint;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;
use Symfony\Component\Validator\Constraints\Choice as ChoiceConstraint;
use Symfony\Component\Validator\Constraints\Type as TypeConstraint;

/**
 * The get trick comment new edit form query constraint...
 *
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class GetTrickCommentNewEditFormQuery extends CollectionConstraint
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct($this->defineOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return CollectionConstraint::class . 'Validator';
    }

    /**
     * Defines the options.
     *
     * @return array
     */
    private function defineOptions()
    {
        return array(
            'fields'=> array(
                'attribute' => new RequiredConstraint(array(
                    new CollectionConstraint(array(
                        'fields' => array(
                            'thread' => new RequiredConstraint(array(
                                new NotBlankConstraint(),
                                new TypeConstraint(array(
                                    'type' => 'digit',
                                )),
                            )),
                            'parent' => new OptionalConstraint(array(
                                new TypeConstraint(array(
                                    'type' => 'digit',
                                )),
                            )),
                        ),
                    )),
                )),
            ),
        );
    }
}

