<?php

namespace App\Form\Type;

use App\Entity\Dto\TrickAttachmentDto;
use App\EventListener\TrickAttachmentDtoBuilderListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

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
     * @var App\EventListener\TrickAttachmentDtoBuilderListener $trickAttachmentDtoBuilderListener
     */
    private $trickAttachmentDtoBuilderListener;

    /**
     * Constructs the trick attachment edit type.
     *
     * @param App\EventListener\TrickAttachmentDtoBuilderListener $trickAttachmentDtoBuilderListener
     */
    public function __construct(TrickAttachmentDtoBuilderListener $trickAttachmentDtoBuilderListener)
    {
        $this->trickAttachmentDtoBuilderListener = $trickAttachmentDtoBuilderListener;
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
                array(
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'upload',
                FileType::class,
                array(
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'src',
                UrlType::class
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                array($this->trickAttachmentDtoBuilderListener, 'onFormSubmit')
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TrickAttachmentDto::class,
        ));
    }
}
