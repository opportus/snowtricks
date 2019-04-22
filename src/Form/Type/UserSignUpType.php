<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * The user sign up type.
 * 
 * @todo Decouple view.
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserSignUpType extends AbstractType
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
                    'label' => 'user.sign_up.form.label.username',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr'  => array('autocomplete' => 'email'),
                    'label' => 'user.sign_up.form.label.email',
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'attr'  => array('autocomplete' => 'new-password'),
                    'label' => 'user.sign_up.form.label.password',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'user.sign_up.form.label.submit',
                ]
            )
        ;
    }
}
