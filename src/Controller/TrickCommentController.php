<?php

namespace App\Controller;

use App\Entity\TrickComment;
use App\Form\Type\TrickCommentEditType;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * The trick comment controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentController extends Controller
{
    /**
     * Gets the trick comment list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/ajax/trick-comment", name="trick_comment_get_list_ajax", defaults={"_format": "json"})
     * @Method("GET")
     */
    public function getList(Request $request) : array
    {
        $order    = $request->query->get('order', array());
        $limit    = $this->parameters['get_list']['per_page'];
        $offset   = ($request->query->getInt('page', 1) - 1) * $limit;
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

        $comments = $this->entityManager->getRepository(TrickComment::class)->findBy(
            $criteria,
            $order,
            $limit,
            $offset
        );

        if (empty($comments)) {
            throw new NotFoundHttpException();
        }

        return array(
            'comments' => $comments
        );
    }

    /**
     * Gets the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/ajax/trick-comment/{id}", name="trick_comment_get_ajax", defaults={"_format": "json"})
     * @Method("GET")
     */
    public function get(Request $request) : array
    {
        $comment = $this->entityManager->getRepository(TrickComment::class)
            ->findOneById($request->attributes->getInt('id'))
        ;

        if ($comment === null) {
            throw new NotFoundHttpException();
        }

        return array(
            'comment' => $comment
        );
    }

    /**
     * Posts the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @Route("/ajax/trick-comment", name="trick_comment_post_ajax", defaults={"_format": "json"})
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function post(Request $request) : array
    {
        $comment = new TrickComment();

        $comment->setAuthor(
            $this->tokenStorage->getToken()->getUser()
        );

        $form = $this->formFactory->createNamed(
            'trick_comment_edit_form',
            TrickCommentEditType::class,
            $comment,
            array(
                'action' => $this->router->generate($request->attributes->get('_route')),
                'method' => 'POST',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return array(
                'comment' => $comment
            );

        } else {
            throw new BadRequestHttpException((string) $form->getErrors());
        }
    }

    /**
     * Puts the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/ajax/trick-comment/{id}", name="trick_comment_put_ajax", defaults={"_format": "json"})
     * @Method("PUT")
     * @Security("has_role('ROLE_USER')")
     */
    public function put(Request $request) : array
    {
        $comment = $this->get($request)['comment'];

        if (! $this->authorizationChecker->isGranted('PUT', $comment)) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->formFactory->createNamed(
            'trick_comment_edit_form_' . (string) $comment->getId(),
            TrickCommentEditType::class,
            $comment,
            array(
                'action' => $this->router->generate($request->attributes->get('_route'), array('id' => (string) $comment->getId())),
                'method' => 'PUT',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return array();

        } else {
            throw new BadRequestHttpException((string) $form->getErrors());
        }
    }

    /**
     * Deletes the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/ajax/trick-comment/{id}", name="trick_comment_delete_ajax", defaults={"_format": "json"})
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function delete(Request $request) : array
    {
        $comment = $this->get($request)['comment'];

        if (! $this->authorizationChecker->isGranted('DELETE', $comment)) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->formFactory->createNamed(
            'trick_comment_edit_form_' . (string) $comment->getId(),
            TrickCommentEditType::class,
            $comment,
            array(
                'action' => $this->router->generate($request->attributes->get('_route'), array('id' => (string) $comment->getId())),
                'method' => 'DELETE',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();

            return array();

        } else {
            throw new BadRequestHttpException((string) $form->getErrors());
        }
    }

    /**
     * Gets the trick comment edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/ajax/trick-comment/edit-form/{id}", name="trick_comment_get_edit_form_ajax", defaults={"_format": "json"})
     * @Method("GET")
     */
    public function getEditForm(Request $request) : array
    {
        $comment = $this->get($request)['comment'];
        $form    = $this->formFactory->createNamed(
            'trick_comment_edit_form_' . (string) $comment->getId(),
            TrickCommentEditType::class,
            $comment,
            array(
                'action' => $this->router->generate(str_replace('get_edit_form', 'put', $request->attributes->get('_route')), array('id' => $comment->getId())),
                'method' => 'PUT',
            )
        );

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Gets the new trick comment edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     *
     * @Route("/ajax/trick-comment/edit-form", name="trick_comment_get_new_edit_form_ajax", defaults={"_format": "json"})
     * @Method("GET")
     */
    public function getNewEditForm(Request $request) : array
    {
        $form = $this->formFactory->createNamed(
            'trick_comment_edit_form',
            TrickCommentEditType::class,
            null,
            array(
                'action' => $this->router->generate(str_replace('get_new_edit_form', 'post', $request->attributes->get('_route'))),
                'method' => 'POST',
            )
        );

        $form->submit(array(
            'thread' => $request->query->get('attribute')['thread'],
            'parent' => $request->query->get('attribute', '')['parent'],
        ));

        return array(
            'form' => $form
        );
    }
}

