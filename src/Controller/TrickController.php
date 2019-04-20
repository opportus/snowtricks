<?php

namespace App\Controller;

use App;
use App\Entity\Trick;
use App\Validator\Constraints\TrickCollectionQueryParameters;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The trick controller.
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickController extends AbstractEntityController
{
    /**
     * Get the trick edit form.
     *
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick/edit/{slug}", name="get_trick_edit_form", methods={"GET"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\Trick",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "id"="slug",
     *         "form_type"="App\Form\Type\TrickEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickDto",
     *             "method"="PUT"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/edit.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="error/not-found.html.twig"
     *         }
     *     )
     * )
     */
    public function getTrickEditForm(FormInterface $form) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $form
        );
    }

    /**
     * Get the empty trick edit form.
     *
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick/edit", name="get_trick_edit_empty_form", methods={"GET"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\Trick",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\TrickEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickDto",
     *             "method"="POST"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/edit.html.twig"
     *         }
     *     )
     * ) 
     */
    public function getTrickEditEmptyForm(FormInterface $form) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $form
        );
    }

    /**
     * Gets the trick collection.
     *
     * @param Doctrine\Common\Collections\ArrayCollection $trickCollection
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick", name="get_trick_collection", methods={"GET"})
     *
     * @ParamConverter(
     *     "trickCollection",
     *     class="App\Entity\Trick",
     *     converter="app.entity_collection_param_converter",
     *     options={
     *         "repository_method"="findAllByCriteria",
     *         "query_constraint"=TrickCollectionQueryParameters::class
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="application/json",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/collection.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND
     * )
     */
    public function getTrickCollection(ArrayCollection $trickCollection) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $trickCollection
        );
    }

    /**
     * Gets the trick.
     *
     * @param App\Entity\Trick $trick
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick/{slug}", name="get_trick", methods={"GET"})
     *
     * @ParamConverter(
     *     "trick",
     *     class="App\Entity\Trick",
     *     converter="app.entity_param_converter",
     *     options={
     *         "id"="slug"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/get.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="error/not-found.html.twig"
     *         }
     *     )
     * )
     */
    public function getTrick(Trick $trick) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $trick
        );
    }

    /**
     * Posts the trick.
     *
     * @param App\Entity\Trick $trick
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick/edit", name="post_trick_by_edit_form", methods={"POST"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @ParamConverter(
     *     "trick",
     *     class="App\Entity\Trick",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\TrickEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickDto",
     *             "method"="POST",
     *             "validation_groups"={"trick.form.edit"}
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(
     *             name="get_trick",
     *             parameters={
     *                 "slug"=@App\Annotation\DatumGetterReference(name="getSlug")
     *             }
     *         )
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/edit.html.twig"
     *         }
     *     )
     * )
     */
    public function postTrickByEditForm(Trick $trick) : ControllerResult
    {
        $this->entityManager->persist($trick);
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_SEE_OTHER,
            $trick
        );
    }

    /**
     * Puts the trick.
     *
     * @param App\Entity\Trick $trick
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick/edit/{slug}", name="put_trick_by_edit_form", methods={"PUT"})
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @ParamConverter(
     *     "trick",
     *     class="App\Entity\Trick",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "id"="slug",
     *         "form_type"="App\Form\Type\TrickEditType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\TrickDto",
     *             "method"="PUT",
     *             "validation_groups"={"trick.form.edit"}
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(
     *             name="get_trick",
     *             parameters={
     *                 "slug"=@App\Annotation\DatumGetterReference(name="getSlug")
     *             }
     *         )
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="trick/edit.html.twig"
     *         }
     *     )
     * )
     */
    public function putTrickByEditForm(Trick $trick) : ControllerResult
    {
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_SEE_OTHER,
            $trick
        );
    }

    /**
     * Deletes the trick.
     *
     * @param App\Entity\Trick $trick
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/trick/delete/{slug}", name="delete_trick", methods={"DELETE"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * 
     * @ParamConverter(
     *     "trick",
     *     class="App\Entity\Trick",
     *     converter="app.entity_param_converter",
     *     options={
     *         "id"="slug",
     *         "grant"="DELETE"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(
     *             name="get_home"
     *         )
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="error/not-found.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="error/forbidden.html.twig"
     *         }
     *     )
     * )
     */
    public function deleteTrick(Trick $trick) : ControllerResult
    {
        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_SEE_OTHER,
            null
        );
    }
}
