<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The user reset password type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserResetPasswordType extends AbstractType
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
                    'label' => 'user.reset_password.form.label.password',
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'user.reset_password.form.label.submit',
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => User::class,
            'validation_groups' => array('user.reset_password.form'),
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'security',
        ));
    }
}

