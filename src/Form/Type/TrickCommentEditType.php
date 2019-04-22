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
 * The trick comment edit type.
 * 
 * @todo Decouple view.
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
    private $entityManager;

    /**
     * @var Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    private $requestStack;

    /**
     * @var App\EventListener\AuthorizerListener $authorizerListener
     */
    private $authorizerListener;

    /**
     * @var App\Form\DataTransformer\TrickToIdTransformer $trickToIdTransformer
     */
    private $trickToIdTransformer;

    /**
     * @var App\Form\DataTransformer\TrickCommentToIdTransformer $trickCommentToIdTransformer
     */
    private $trickCommentToIdTransformer;

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
    ) {
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
                [
                    'data'   => $this->requestStack->getCurrentRequest()->attributes->get('id'),
                    'mapped' => false,
                ]
            )
            ->add(
                'submit',
                ButtonType::class
            )
        ;

        if ('GET' === $this->requestStack->getCurrentRequest()->getMethod()) {
            $formQuery = $this->requestStack->getCurrentRequest()->query->get('attribute');

            $thread = null;
            if (isset($formQuery['thread']) && $formQuery['thread']) {
                $thread = $this->entityManager->getRepository(Trick::class)->findOneById($formQuery['thread']);
            }

            $builder
                ->add(
                    'thread',
                    HiddenType::class,
                    [
                        'data_class' => null,
                        'data'       => $thread
                    ]
                )
            ;

            $parent = null;
            if (isset($formQuery['parent']) && $formQuery['parent']) {
                $parent = $this->entityManager->getRepository(TrickComment::class)->findOneById($formQuery['parent']);
            }

            $builder
                ->add(
                    'parent',
                    HiddenType::class,
                    [
                        'data_class' => null,
                        'data'       => $parent
                    ]
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
            [$this->authorizerListener, 'onFormSubmit']
        );
    }
}
