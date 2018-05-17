<?php

namespace App\Form\Type;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Form\DataTransformer\TrickToIdTransformer;
use App\Form\DataTransformer\TrickCommentToIdTransformer;
use App\EventListener\AuthorizerListener;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

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
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    protected $requestStack;

    /**
     * @var App\EventListener\AuthorizerListener $authorizerListener
     */
    protected $authorizerListener;

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
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param App\EventListener\AuthorizerListener $authorizerListner
     * @param App\Form\DataTransformer\TrickToIdTransformer $trickToIdTransformer
     * @param App\Form\DataTransformer\TrickCommentToIdTransformer $trickCommentToIdTransformer
     */
    public function __construct(
        EntityManagerInterface      $entityManager,
        RequestStack                $requestStack,
        AuthorizerListener          $authorizerListener,
        TrickToIdTransformer        $trickToIdTransformer,
        TrickCommentToIdTransformer $trickCommentToIdTransformer
    )
    {
        $this->entityManager               = $entityManager;
        $this->requestStack                = $requestStack;
        $this->authorizerListener          = $authorizerListener;
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
                'id',
                HiddenType::class,
                array(
                    'data'   => $this->requestStack->getCurrentRequest()->attributes->get('id'),
                    'mapped' => false,
                )
            )
            ->add(
                'submit',
                ButtonType::class
            )
        ;

        if (! $builder->getData()) {
            $formQuery = $this->requestStack->getCurrentRequest()->query->get('attribute');

            if (isset($formQuery['thread']) && $formQuery['thread']) {
                $thread = $this->entityManager->getRepository(Trick::class)->findOneById($formQuery['thread']);
            }

            if (! isset($thread)) {
                $thread = null;
            }

            $builder
                ->add(
                    'thread',
                    HiddenType::class,
                    array(
                        'data_class' => null,
                        'data'       => $thread
                    )
                )
            ;

            if (isset($formQuery['parent']) && $formQuery['parent']) {
                $parent = $this->entityManager->getRepository(TrickComment::class)->findOneById($formQuery['parent']);
            }

            if (! isset($parent)) {
                $parent = null;
            }

            $builder
                ->add(
                    'parent',
                    HiddenType::class,
                    array(
                        'data_class' => null,
                        'data'       => $parent
                    )
                )
            ;

        } else {
            $builder
                ->add(
                    'thread',
                    HiddenType::class
                )
                ->add(
                    'parent',
                    HiddenType::class
                )
            ;
        }

        $builder->get('thread')->addModelTransformer($this->trickToIdTransformer);
        $builder->get('parent')->addModelTransformer($this->trickCommentToIdTransformer);

        $builder->addEventListener(
            FormEvents::SUBMIT,
            array($this->authorizerListener, 'onFormSubmit')
        );
    }
}

