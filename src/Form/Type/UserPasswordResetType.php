<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * The user reset password type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserPasswordResetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plainPassword',
                PasswordType::class,
                array(
                    'attr'  => array('autocomplete' => 'new-password'),
                    'label' => 'user.password_reset.form.label.password',
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'user.password_reset.form.label.submit',
                )
            )
        ;
    }
}

