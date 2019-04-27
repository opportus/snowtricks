<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * The user sign in type.
 * 
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserSignInType extends AbstractType
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
                [
                    'attr'  => array('autocomplete' => 'username'),
                    'label' => 'user.sign_in.form.label.username',
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'attr'  => array('autocomplete' => 'current-password'),
                    'label' => 'user.sign_in.form.label.password',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'user.sign_in.form.label.submit',
                ]
            )
        ;
    }
}
