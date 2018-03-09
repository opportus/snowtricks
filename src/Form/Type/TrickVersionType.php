<?php

namespace App\Form\Type;

use App\Entity\TrickVersion;
use App\Entity\TrickGroup;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextAreaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * The trick version type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickVersionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                array(
                    'label' => false,
                )
            )
            ->add(
                'description',
                TextType::class,
                array(
                    'label' => false,
                )
            )
            ->add(
                'body',
                TextAreaType::class,
                array(
                    'label' => false,
                )
            )
            ->add(
                'group',
                EntityType::class,
                array(
                    'label'        => false,
                    'class'        => TrickGroup::class,
                    'choice_label' => 'title',
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'trick_version.edit.form.label.submit',
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
            'data_class'        => TrickVersion::class,
            'validation_groups' => array('trick_version.edit.form'),
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'security',
        ));
    }
}

