<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The user sign up form type...
 *
 * @version 0.0.1
 * @package App\Form
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserSignUpFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                array(
                    'attr'  => array('autocomplete' => 'username'),
                    'label' => 'user.sign_up.form.label.username',
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'attr'  => array('autocomplete' => 'email'),
                    'label' => 'user.sign_up.form.label.email',
                )
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                array(
                    'attr'  => array('autocomplete' => 'new-password'),
                    'label' => 'user.sign_up.form.label.password',
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'user.sign_up.form.label.submit',
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
            'empty_data'        => new User(),
            'validation_groups' => array('user.sign_up.form'),
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'security',
        ));
    }
}
