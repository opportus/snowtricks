<?php

namespace App\Controller;

use App;
use App\Entity\TrickComment;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The trick comment controller.
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentController extends AbstractEntityController
{
    /**
     * Gets the trick comment edit form.
     *
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick-comment/edit/{id}", name="get_trick_comment_edit_form", methods={"GET"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\TrickComment",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "id"="id",
     *         "form_type"="App\Form\Type\TrickCommentEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickCommentDto",
     *             "method"="PUT"
     *         },
     *         "grant"="PUT"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="application/json",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/comment/edit.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_FORBIDDEN
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND
     * )
     */
    public function getTrickCommentEditForm(FormInterface $form) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $form
        );
    }

    /**
     * Gets the trick comment empty edit form.
     *
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick-comment/edit", name="get_trick_comment_empty_edit_form", methods={"GET"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\TrickComment",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\TrickCommentEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickCommentDto",
     *             "method"="POST"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="application/json",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/comment/edit.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/comment/edit.html.twig"
     *         }
     *     )
     * )
     */
    public function getTrickCommentEmptyEditForm(FormInterface $form) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $form
        );
    }

    /**
     * Gets the trick comment collection.
     *
     * @param Doctrine\Common\Collections\ArrayCollection $trickCommentCollection
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick-comment", name="get_trick_comment_collection", methods={"GET"})
     * 
     * @ParamConverter(
     *     "trickCommentCollection",
     *     class="App\Entity\TrickComment",
     *     converter="app.entity_collection_param_converter",
     *     options={
     *         "repository_method"="findAllByCriteria"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="application/json", 
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/comment/collection.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND
     * )
     */
    public function getTrickCommentCollection(ArrayCollection $trickCommentCollection) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $trickCommentCollection
        );
    }

    /**
     * Gets the trick comment.
     *
     * @param App\Entity\TrickComment $trickComment
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick-comment/{id}", name="get_trick_comment", methods={"GET"})
     * 
     * @ParamConverter(
     *     "trickComment",
     *     class="App\Entity\TrickComment",
     *     converter="app.entity_param_converter"
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="application/json", 
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/comment/get.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND
     * )
     */
    public function getTrickComment(TrickComment $trickComment) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $trickComment
        );
    }

    /**
     * Posts the trick comment by edit form.
     *
     * @param App\Entity\TrickComment $trickComment
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick-comment/edit", name="post_trick_comment_by_edit_form", methods={"POST"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * 
     * @ParamConverter(
     *     "trickComment",
     *     class="App\Entity\TrickComment",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\TrickCommentEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickCommentDto",
     *             "method"="POST",
     *             "validation_groups"={"trick_comment.form.edit"}
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NO_CONTENT
     * )
     */
    public function postTrickCommentByEditForm(TrickComment $trickComment) : ControllerResult
    {
        $this->entityManager->persist($trickComment);
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_NO_CONTENT,
            $trickComment
        );
    }

    /**
     * Puts the trick comment by edit form.
     *
     * @param App\Entity\TrickComment $trickComment
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick-comment/edit/{id}", name="put_trick_comment_by_edit_form", methods={"PUT"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * 
     * @ParamConverter(
     *     "trickComment",
     *     class="App\Entity\TrickComment",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\TrickCommentEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickCommentDto",
     *             "method"="PUT",
     *             "validation_groups"={"trick_comment.form.edit"}
     *         },
     *         "grant"="PUT"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NO_CONTENT
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_FORBIDDEN
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND
     * )
     */
    public function putTrickCommentByEditForm(TrickComment $trickComment) : ControllerResult
    {
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_NO_CONTENT,
            $trickComment
        );
    }

    /**
     * Deletes the trick comment by delete form.
     *
     * @param App\Entity\TrickComment $trickComment
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick-comment/delete/{id}", name="delete_trick_comment_by_delete_form", methods={"DELETE"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * 
     * @ParamConverter(
     *     "trickComment",
     *     class="App\Entity\TrickComment",
     *     converter="app.entity_param_converter"
     * )
     *
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NO_CONTENT
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_FORBIDDEN
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND
     * )
     */
    public function deleteTrickCommentByDeleteForm(TrickComment $trickComment) : ControllerResult
    {
        $this->entityManager->remove($trickComment);
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_NO_CONTENT,
            null
        );
    }
}
