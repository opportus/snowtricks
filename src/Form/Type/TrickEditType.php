<?php

namespace App\Form\Type;

use App\Entity\Dto\TrickDto;
use App\Entity\Dto\TrickAttachmentDto;
use App\Entity\TrickGroup;
use App\EventListener\AuthorizerListener;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The trick edit type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickEditType extends AbstractType
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var App\EventListener\AuthorizerListener $authorizerListener
     */
    private $authorizerListener;

    /**
     * Constructs the trick edit type.
     *
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param App\EventListener\AuthorizerListener $authorizerListener
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AuthorizerListener     $authorizerListener
    ) {
        $this->entityManager      = $entityManager;
        $this->authorizerListener = $authorizerListener;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->getData() instanceof TrickDto && $builder->getData() !== null && $builder->getData()->group !== null) {
            $preferredChoices = array($this->entityManager->getReference(TrickGroup::class, $builder->getData()->group->getId()));
        } else {
            $preferredChoices =array();
        }

        $builder
            ->add(
                'title',
                TextType::class,
                array(
                    'translation_domain' => false,
                )
            )
            ->add(
                'description',
                TextType::class,
                array(
                    'translation_domain' => false,
                )
            )
            ->add(
                'body',
                TextareaType::class,
                array(
                    'translation_domain' => false,
                )
            )
            ->add(
                'group',
                EntityType::class,
                array(
                    'class'              => TrickGroup::class,
                    'choice_label'       => 'title',
                    'choice_name'        => 'id',
                    'preferred_choices'  => $preferredChoices,
                    'translation_domain' => false,
                )
            )
            ->add(
                'attachments',
                CollectionType::class,
                array(
                    'allow_add'          => true,
                    'allow_delete'       => true,
                    'by_reference'       => false,
                    'required'           => false,
                    'delete_empty'       => true,
                    'entry_type'         => TrickAttachmentEditType::class,
                    'translation_domain' => false,
                    'entry_options' => array(
                        'label'              => false,
                        'data_class'         => TrickAttachmentDto::class,
                        'translation_domain' => false,
                    )
                )
            )
            ->add(
                'submit',
                SubmitType::class
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                array($this->authorizerListener, 'onFormSubmit')
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TrickDto::class,
        ));
    }
}
