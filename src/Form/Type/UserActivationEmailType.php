<?php

namespace App\Form\Type;

use App\Validator\Constraints\UserToken;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * The user activation email type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserActivationEmailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'activation',
                CheckboxType::class,
                array(
                    'data' => true,
                )
            )
            ->add(
                'token',
                HiddenType::class,
                array(
                    'mapped' => false,
                )
            )
        ;
    }
}

