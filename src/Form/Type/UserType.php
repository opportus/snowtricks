<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The user type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class
            )
            ->add(
                'email',
                EmailType::class
            )
            ->add(
                'plainPassword',
                PasswordType::class
            )
            ->add(
                'activation',
                CheckboxType::class
            )
            ->add(
                'roles',
                ChoiceType::class,
                array(
                    'choices' => array(
                        'ROLE_USER' => 'ROLE_USER',
                    )
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
            'data_class'      => User::class,
            'empty_data'      => new User(),
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'security',
        ));
    }
}

