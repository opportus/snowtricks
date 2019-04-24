<?php

namespace App\Form\Type;

use App\Form\Data\TrickData;
use App\Form\Data\TrickAttachmentData;
use App\Entity\TrickGroup;
use App\EventListener\AuthorizerListener;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * The trick edit type.
 * 
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickEditType extends AbstractType
{
    /**
     * @var App\EventListener\AuthorizerListener $authorizerListener
     */
    private $authorizerListener;

    /**
     * Constructs the trick edit type.
     *
     * @param App\EventListener\AuthorizerListener $authorizerListener
     */
    public function __construct(AuthorizerListener $authorizerListener)
    {
        $this->authorizerListener = $authorizerListener;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (\is_object($builder->getData()) && $builder->getData() instanceof TrickData && null !== $builder->getData()->group) {
            $groupPreferredChoices = [$builder->getData()->group];
        } else {
            $groupPreferredChoices = [];
        }

        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'translation_domain' => false,
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'translation_domain' => false,
                ]
            )
            ->add(
                'body',
                TextareaType::class,
                [
                    'translation_domain' => false,
                ]
            )
            ->add(
                'group',
                EntityType::class,
                [
                    'class'              => TrickGroup::class,
                    'choice_label'       => 'title',
                    'choice_name'        => 'id',
                    'preferred_choices'  => $groupPreferredChoices,
                    'translation_domain' => false,
                ]
            )
            ->add(
                'attachments',
                CollectionType::class,
                [
                    'allow_add'          => true,
                    'allow_delete'       => true,
                    'by_reference'       => false,
                    'required'           => false,
                    'delete_empty'       => true,
                    'entry_type'         => TrickAttachmentEditType::class,
                    'translation_domain' => false,
                    'entry_options' => array(
                        'label'              => false,
                        'data_class'         => TrickAttachmentData::class,
                        'translation_domain' => false,
                    )
                ]
            )
            ->add(
                'submit',
                SubmitType::class
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this->authorizerListener, 'onFormSubmit']
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickData::class,
        ]);
    }
}
