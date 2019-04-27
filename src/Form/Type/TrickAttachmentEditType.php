<?php

namespace App\Form\Type;

use App\Form\Data\TrickAttachmentData;
use App\EventListener\TrickAttachmentDataBuilderListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

/**
 * The trick attachment edit type.
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAttachmentEditType extends AbstractType
{
    /**
     * @var App\EventListener\TrickAttachmentDataBuilderListener $trickAttachmentDataBuilderListener
     */
    private $trickAttachmentDataBuilderListener;

    /**
     * Constructs the trick attachment edit type.
     *
     * @param App\EventListener\TrickAttachmentDataBuilderListener $trickAttachmentDataBuilderListener
     */
    public function __construct(TrickAttachmentDataBuilderListener $trickAttachmentDataBuilderListener)
    {
        $this->trickAttachmentDataBuilderListener = $trickAttachmentDataBuilderListener;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'embed',
                TextType::class,
                [
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add(
                'upload',
                FileType::class,
                [
                    'mapped' => false,
                    'required' => false,
                ]
            )
             ->add(
                'src',
                UrlType::class,
                [
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this->trickAttachmentDataBuilderListener, 'onFormSubmit']
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickAttachmentData::class,
        ]);
    }
}
