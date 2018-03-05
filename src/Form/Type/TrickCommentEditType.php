<?php

namespace App\Form\Type;

use App\Entity\TrickComment;
use App\Form\DataTransformer\TrickToIdTransformer;
use App\Form\DataTransformer\TrickCommentToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The trick comment edit type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentEditType extends AbstractType
{
    /**
     * @var App\Form\DataTransformer\TrickToIdTransformer $trickToIdTransformer
     */
    protected $trickToIdTransformer;

    /**
     * @var App\Form\DataTransformer\TrickCommentToIdTransformer $trickCommentToIdTransformer
     */
    protected $trickCommentToIdTransformer;

    /**
     * Constructs the trick comment type.
     *
     * @param App\Form\DataTransformer\TrickToIdTransformer $trickToIdTransformer
     * @param App\Form\DataTransformer\TrickCommentToIdTransformer $trickCommentToIdTransformer
     */
    public function __construct(TrickToIdTransformer $trickToIdTransformer, TrickCommentToIdTransformer $trickCommentToIdTransformer)
    {
        $this->trickToIdTransformer        = $trickToIdTransformer;
        $this->trickCommentToIdTransformer = $trickCommentToIdTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'body',
                TextareaType::class
            )
            ->add(
                'thread',
                HiddenType::class
            )
            ->add(
                'parent',
                HiddenType::class
            )
            ->add(
                'id',
                HiddenType::class,
                array(
                    'disabled' => true,
                )
            )
            ->add(
                'submit',
                ButtonType::class
            )
        ;

        $builder->get('thread')->addModelTransformer($this->trickToIdTransformer);
        $builder->get('parent')->addModelTransformer($this->trickCommentToIdTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => TrickComment::class,
            'validation_groups' => array('trick_comment.edit.form'),
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'security',
        ));
    }
}

