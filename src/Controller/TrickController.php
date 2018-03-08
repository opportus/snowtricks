<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Form\Type\TrickCommentEditType;
use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * The trick controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickController extends Controller
{
    /**
     * Gets the trick list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/ajax/trick", name="trick_get_list_ajax", defaults={"_format": "json"})
     * @Method("GET")
     */
    public function getList(Request $request) : ControllerResultInterface
    {
        $limit    = 10;
        $offset   = ($request->query->getInt('page', 1) - 1) * $limit;
        $order    = $request->query->get('order', array());
        $criteria = array_map(
            function ($value) {
                if ($value === '') {
                    return null;

                } else {
                    return $value;
                }
            },
            $request->query->get('attribute', array())
        );

        $tricks = $this->entityManager->getRepository(Trick::class)->findBy(
            $criteria,
            $order,
            $limit,
            $offset
        );

        if (empty($tricks)) {
            throw new NotFoundHttpException();
        }

        $data = array(
            'tricks' => $tricks,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Gets the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/trick/{slug}", name="trick_get", defaults={"_format": "html"})
     * @Method("GET")
     */
    public function get(Request $request) : ControllerResultInterface
    {
        $trick = $this->entityManager->getRepository(Trick::class)
            ->findOneBySlug($request->attributes->get('slug'))
        ;

        if ($trick === null) {
            throw new NotFoundHttpException();
        }

        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            $comment = new TrickComment();

            $comment->setThread($trick);

            $commentForm = $this->formFactory->createNamed(
                'trick_comment_edit_form',
                TrickCommentEditType::class,
                $comment,
                array(
                    'action' => $this->router->generate('trick_comment_post_ajax'),
                    'method' => 'POST',
                )
            );
        }

        $data = array(
            'trick'       => $trick,
            'commentForm' => $commentForm
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Deletes the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/ajax/trick/{slug}", name="trick_delete_ajax", defaults={"_format": "json"})
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function delete(Request $request) : ControllerResultInterface
    {
        $trick = $this->entityManager->getRepository(Trick::class)
            ->findOneById($request->attributes->get('slug'))
        ;

        if ($trick === null) {
            throw new NotFoundHttpException();
        }

        if (! $this->authorizationChecker->isGranted('DELETE', $trick)) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->formFactory->create(
            FormType::class,
            $trick,
            array(
                'action' => $this->router->generate($request->attributes->get('_route'), array('slug' => $trick->getSlug())),
                'method' => 'DELETE',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($trick);
            $this->entityManager->flush();

            return new ControllerResult(204);

        } else {
            throw new BadRequestHttpException((string) $form->getErrors());
        }
    }
}

