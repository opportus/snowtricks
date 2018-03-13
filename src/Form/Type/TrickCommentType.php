<?php

namespace App\Form\Type;

use App\Entity\TrickComment;
use App\Form\DataTransformer\TrickToIdTransformer;
use App\Form\DataTransformer\TrickCommentToIdTransformer;
use App\Form\DataTransformer\UserToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The trick comment type...
 *
 * @version 0.0.1
 * @package App\Form\Type
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentType extends AbstractType
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
     * @var App\Form\DataTransformer\UserToIdTransformer $userToIdTransformer
     */
    protected $userToIdTransformer;

    /**
     * Constructs the trick comment type.
     *
     * @param App\Form\DataTransformer\TrickToIdTransformer $trickToIdTransformer
     * @param App\Form\DataTransformer\TrickCommentToIdTransformer $trickCommentToIdTransformer
     * @param App\Form\DataTransformer\UserToIdTransformer $userToIdTransformer
     */
    public function __construct(
        TrickToIdTransformer        $trickToIdTransformer,
        TrickCommentToIdTransformer $trickCommentToIdTransformer,
        UserToIdTransformer         $userToIdTransformer
    )
    {
        $this->trickToIdTransformer        = $trickToIdTransformer;
        $this->trickCommentToIdTransformer = $trickCommentToIdTransformer;
        $this->userToIdTransformer         = $userToIdTransformer;
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
                'parent',
                TextType::class
            )
            ->add(
                'thread',
                TextType::class
            )
            ->add(
                'author',
                TextType::class
            )
        ;

        $builder->get('parent')->addModelTransformer($this->trickCommentToIdTransformer);
        $builder->get('thread')->addModelTransformer($this->trickToIdTransformer);
        $builder->get('author')->addModelTransformer($this->userToIdTransformer);
    }
}

